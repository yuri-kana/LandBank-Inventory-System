<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryLog; // ADD THIS IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index(Request $request)
{
    // Start building the query
    $query = Item::query();
    
    // Apply search filter if provided
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where('name', 'like', '%' . $searchTerm . '%');
    }
    
    // ADD SORTING HERE - Order by name alphabetically
    $query->orderBy('name', 'asc');
    
    // Get all items with their calculated available stock
    $items = $query->withCount(['pendingRequests as pending_quantity_sum' => function($query) {
        $query->select(DB::raw('COALESCE(SUM(quantity_requested), 0)'));
    }])->get();
    
    // Apply status filter using the model's available_stock attribute
    $statusFilter = $request->has('status') && $request->status != 'all' ? $request->status : null;
    
    if ($statusFilter) {
        $items = $items->filter(function ($item) use ($statusFilter) {
            $availableStock = $item->available_stock; // This uses the accessor!
            
            if ($statusFilter === 'low-stock') {
                return $availableStock > 0 && $availableStock <= $item->minimum_stock;
            } elseif ($statusFilter === 'out-of-stock') {
                return $availableStock <= 0;
            } elseif ($statusFilter === 'in-stock') {
                return $availableStock > $item->minimum_stock;
            }
            return true;
        });
    }
    
    // Paginate the filtered results
    $perPage = 10;
    $currentPage = $request->page ?? 1;
    $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
        $items->forPage($currentPage, $perPage),
        $items->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
    // Calculate statistics
    $totalItems = Item::count();
    
    // Get all items to calculate statistics
    $allItems = Item::all(); // Just get all items
    
    $lowStockItems = $allItems->filter(function ($item) {
        return $item->available_stock > 0 && $item->available_stock <= $item->minimum_stock;
    })->count();
    
    $outOfStockItems = $allItems->filter(function ($item) {
        return $item->available_stock <= 0;
    })->count();
    
    return view('items.index', [
        'items' => $paginatedItems,
        'totalItems' => $totalItems,
        'lowStockItems' => $lowStockItems,
        'outOfStockItems' => $outOfStockItems,
        'statusFilter' => $statusFilter
    ]);
}

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        // Debug: Log all incoming data
        Log::info('=== STORE METHOD CALLED ===');
        Log::info('All request data:', $request->all());
        
        // Validate the request data - INCLUDING UNIT FIELD
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:items',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50', // THIS MUST BE IN VALIDATION
            'minimum_stock' => 'required|integer|min:0',
        ]);
        
        Log::info('Validated data:', $validated);

        try {
            // Create the item - the unit field will now be included
            $item = Item::create($validated);
            
            Log::info('Item created successfully! ID: ' . $item->id);
            Log::info('Item details:', $item->toArray());
            
            return redirect()->route('admin.items.index')
                ->with('success', 'Item "' . $item->name . '" added successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error creating item: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withErrors(['error' => 'Failed to create item. Please check all fields.'])
                ->withInput();
        }
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:items,name,' . $item->id,
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        // Update the item
        $item->update($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function destroy(Item $item)
    {
        $itemName = $item->name;
        $item->delete();

        return redirect()->route('admin.items.index')
            ->with('success', 'Item "' . $itemName . '" deleted successfully.');
    }

    public function showAddStockForm(Item $item)
    {
        return view('items.add-stock', compact('item'));
    }

    public function addStock(Request $request, Item $item)
    {
        $request->validate([
            'quantity_to_add' => 'required|integer|min:1|max:999999',
            'reason' => 'nullable|string|max:255',
        ]);
        
        $oldQuantity = $item->quantity;
        $quantityToAdd = $request->quantity_to_add;
        $newQuantity = $oldQuantity + $quantityToAdd;
        
        // Update the item quantity
        $item->quantity = $newQuantity;
        $item->save();
        
        // CREATE INVENTORY LOG FOR THIS RESTOCK
        try {
            InventoryLog::create([
                'item_id' => $item->id,
                'action' => 'restock',
                'quantity_change' => $quantityToAdd,
                'beginning_quantity' => $oldQuantity,
                'ending_quantity' => $newQuantity,
                'user_id' => auth()->id(),
                'notes' => $request->reason ?? 'Manual restock via add stock form'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create inventory log: ' . $e->getMessage());
        }
        
        return redirect()->route('admin.items.index')
            ->with('success', "Stock added successfully! Added $quantityToAdd items to $item->name. New stock: $newQuantity");
    }

    // ========== FIXED BULK RESTOCKING METHOD ==========
    
    /**
     * Get items for bulk restocking (AJAX)
     */
    public function getItemsForRestock()
    {
        try {
            $items = Item::orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load items'
            ], 500);
        }
    }
    
    /**
     * Process bulk restock (AJAX) - FIXED VERSION
     */
    public function processBulkRestock(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $addQuantities = $request->input('add_quantity', []);
            $itemsUpdated = 0;
            $totalAdded = 0;
            $restockedItems = [];
            
            foreach ($addQuantities as $itemId => $quantity) {
                $quantity = intval($quantity);
                
                if ($quantity > 0) {
                    $item = Item::find($itemId);
                    
                    if ($item) {
                        $oldQuantity = $item->quantity;
                        $newQuantity = $oldQuantity + $quantity;
                        
                        // Update item quantity
                        $item->quantity = $newQuantity;
                        $item->save();
                        
                        // CREATE INVENTORY LOG FOR RESTOCK
                        InventoryLog::create([
                            'item_id' => $item->id,
                            'action' => 'restock',
                            'quantity_change' => $quantity, // This field name must match your model
                            'beginning_quantity' => $oldQuantity,
                            'ending_quantity' => $newQuantity,
                            'user_id' => auth()->id(),
                            'notes' => 'Bulk restock via Start Restocking button'
                        ]);
                        
                        $restockedItems[] = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'old_quantity' => $oldQuantity,
                            'quantity_added' => $quantity,
                            'new_quantity' => $newQuantity
                        ];
                        
                        $itemsUpdated++;
                        $totalAdded += $quantity;
                    }
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Restock completed successfully!',
                'data' => [
                    'items_updated' => $itemsUpdated,
                    'total_added' => $totalAdded,
                    'restocked_items' => $restockedItems
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk restock error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process restock: ' . $e->getMessage()
            ], 500);
        }
    }
}