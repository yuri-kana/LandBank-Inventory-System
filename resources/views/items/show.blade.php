@section('title', 'Item Details - Inventory System')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Item Details: ') . $item->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Back to Inventory
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.items.edit', $item) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                        Edit Item
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @php
                        // Calculate reserved stock
                        $approvedReserved = $item->teamRequests()
                            ->where('status', 'approved')
                            ->sum('quantity_requested');
                        
                        $pendingReserved = $item->teamRequests()
                            ->where('status', 'pending')
                            ->sum('quantity_requested');
                        
                        $totalReserved = $approvedReserved + $pendingReserved;
                        
                        // Calculate available stock (total minus reserved)
                        $availableStock = max(0, $item->quantity - $totalReserved);
                        
                        // Determine stock status based on available stock
                        $stockStatus = $availableStock <= 0 ? 'Out of Stock' : 
                                      ($availableStock <= $item->minimum_stock ? 'Low Stock' : 'In Stock');
                        $stockStatusColor = $availableStock <= 0 ? 'bg-red-100 text-red-800' : 
                                           ($availableStock <= $item->minimum_stock ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                    @endphp

                    <!-- Item Details Card -->
                    <div class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Current Stock -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Current Stock</h3>
                                <p class="text-3xl font-bold text-gray-900">{{ $item->quantity }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $item->unit ? 'in ' . ucfirst($item->unit) : 'Unit not set' }}</p>
                            </div>

                            <!-- Minimum Stock Level -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Minimum Stock Level</h3>
                                <p class="text-3xl font-bold text-gray-900">{{ $item->minimum_stock }}</p>
                                <p class="text-sm text-gray-500 mt-1">Alert when stock falls below this level</p>
                            </div>

                            <!-- Stock Availability -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Stock Availability</h3>
                                <p class="text-3xl font-bold text-green-700">{{ $availableStock }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $item->unit ? ucfirst($item->unit) : 'Unit' }}</p>
                                <div class="mt-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $stockStatusColor }}">
                                        {{ $stockStatus }}
                                    </span>
                                </div>
                            </div>

                            <!-- Reserved Total -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Reserved (Total)</h3>
                                <p class="text-3xl font-bold text-blue-700">{{ $totalReserved }}</p>
                                
                                <!-- Colored badges for Approved and Pending -->
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if($approvedReserved > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Approved: {{ $approvedReserved }}
                                        </span>
                                    @endif
                                    
                                    @if($pendingReserved > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Pending: {{ $pendingReserved }}
                                        </span>
                                    @endif
                                    
                                    @if($totalReserved == 0)
                                        <span class="text-sm text-gray-500 mt-1">No reserved stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Item Information -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div>
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-700 mb-2">Item Name</h4>
                                    <p class="text-gray-900 text-lg">{{ $item->name }}</p>
                                </div>
                            </div>

                            <!-- Right Column - Reserved Breakdown -->
                            <div>
                                <!-- Last Updated -->
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">Last Updated</h4>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt text-gray-400 mr-3"></i>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->updated_at->format('M d, Y') }}</p>
                                                <p class="text-sm text-gray-500">{{ $item->updated_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Request This Item Button (for team members) -->
                    @if(auth()->user()->isTeamMember())
                        <div class="mb-8">
                            @if($availableStock > 0)
                                <a href="{{ route('requests.create') }}?item_id={{ $item->id }}" 
                                   class="inline-flex items-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Request This Item for {{ auth()->user()->team->name }}
                                </a>
                                <p class="text-sm text-gray-500 mt-2">
                                    Request additional {{ $item->unit ? $item->unit . '(s)' : 'units' }} of {{ $item->name }}
                                    <span class="text-green-600 font-medium">({{ $availableStock }} available)</span>
                                </p>
                            @else
                                <button disabled class="inline-flex items-center bg-gray-400 text-white px-6 py-3 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-ban mr-2"></i>
                                    Item Not Available for Request
                                </button>
                                <p class="text-sm text-red-500 mt-2">
                                    No stock available for new requests.
                                    @if($totalReserved > 0)
                                        <span class="text-blue-600">({{ $totalReserved }} {{ $item->unit ? $item->unit . '(s)' : 'units' }} reserved)</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Recent Requests for this Item -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Requests for this Item</h3>
                            @if($totalReserved > 0)
                                <div class="text-sm text-gray-500">
                                    Total Reserved: <span class="font-medium text-blue-600">{{ $totalReserved }}</span> {{ $item->unit ? $item->unit . '(s)' : '' }}
                                </div>
                            @endif
                        </div>
                        
                        @if($item->teamRequests->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Team
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quantity Requested
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Unit
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($item->teamRequests()->latest()->take(10)->get() as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $request->team->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $request->quantity_requested }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item->unit ? ucfirst($item->unit) : 'Unit' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                                                        {{ $request->status === 'approved' ? 'bg-blue-100 text-blue-800' : 
                                                           ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($request->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $request->created_at->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                    No requests yet for this item
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">No requests have been made for this item yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>