<!-- Start Restocking Modal -->
<div id="start-restocking-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="restock-modal-title" role="dialog" aria-modal="true">
    
    <!-- Add this style tag for stock status highlighting -->
    <style>
        .stock-status-out-of-stock {
            background-color: #fef2f2 !important;
            border-left: 4px solid #dc2626 !important;
        }
        
        .stock-status-low-stock {
            background-color: #fffbeb !important;
            border-left: 4px solid #d97706 !important;
        }
        
        .stock-status-in-stock {
            border-left: 4px solid #10b981 !important;
        }
        
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
        
        .status-out-of-stock {
            background-color: #dc2626;
        }
        
        .status-low-stock {
            background-color: #d97706;
        }
        
        .status-in-stock {
            background-color: #10b981;
        }
        
        .stock-level-critical {
            color: #dc2626;
            font-weight: 600;
        }
        
        .stock-level-low {
            color: #d97706;
            font-weight: 600;
        }
        
        .stock-level-good {
            color: #16a34a;
        }
        
        .stock-progress-bar {
            width: 100%;
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            margin-top: 2px;
            overflow: hidden;
        }
        
        .stock-progress-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .progress-critical { background-color: #dc2626; }
        .progress-low { background-color: #d97706; }
        .progress-good { background-color: #10b981; }
        
        input.stock-input-critical {
            border-color: #f87171 !important;
            background-color: #fef2f2 !important;
        }
        
        input.stock-input-low {
            border-color: #fbbf24 !important;
            background-color: #fffbeb !important;
        }
    </style>
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="restock-modal-title">
                                    <i class="fas fa-play-circle mr-2 text-green-600"></i> Add Stock to Inventory
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">Available stock = Total quantity - Requested quantity</p>
                            </div>
                            <button type="button" onclick="closeRestockModal()" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <div class="bg-white rounded-lg border border-gray-200">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <h4 class="font-medium text-gray-700">Add quantities to restock items</h4>
                                    <p class="text-sm text-gray-500 mt-1">Enter how many units to add for each item</p>
                                    <div class="flex items-center space-x-4 mt-2 text-xs">
                                        <div class="flex items-center">
                                            <span class="status-indicator status-out-of-stock mr-1"></span>
                                            <span class="text-red-600">Out of Stock</span>
                                            <span id="out-of-stock-count" class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full">(0)</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="status-indicator status-low-stock mr-1"></span>
                                            <span class="text-yellow-600">Low Stock</span>
                                            <span id="low-stock-count" class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">(0)</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="status-indicator status-in-stock mr-1"></span>
                                            <span class="text-green-600">In Stock</span>
                                            <span id="in-stock-count" class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">(0)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="overflow-x-auto max-h-[50vh]">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ITEM NAME
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    AVAILABLE STOCK<br><span class="text-xs font-normal">(Total - Requested)</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ADD QUANTITY
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    NEW TOTAL
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="restock-items-table">
                                            <!-- Items will be populated by JavaScript -->
                                            <tr id="loading-row">
                                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                    <i class="fas fa-spinner fa-spin text-2xl text-gray-300 mb-2"></i>
                                                    <p>Loading inventory items...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-4 border-t border-gray-200 bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            New Total = Available Stock + Add Quantity
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="button" onclick="closeRestockModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                                Cancel
                                            </button>
                                            <button type="button" id="confirm-restock" onclick="submitRestock()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                                <i class="fas fa-spinner fa-spin mr-2 hidden"></i>
                                                <span>Confirm Restock</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ===========================================
// SIMPLE RESTOCK MODAL FUNCTIONS
// ===========================================

function closeRestockModal() {
    document.getElementById('start-restocking-modal').classList.add('hidden');
}

// Open modal and load items
function startRestocking() {
    console.log('Opening restock modal...');
    const modal = document.getElementById('start-restocking-modal');
    modal.classList.remove('hidden');
    loadInventoryItems();
}

// Load inventory items
async function loadInventoryItems() {
    try {
        const response = await fetch('{{ route("admin.items.restock.bulk") }}');
        const data = await response.json();
        
        console.log('API Response:', data); // Debug
        
        if (data.success && data.items) {
            renderItems(data.items);
            document.getElementById('confirm-restock').disabled = false;
        }
    } catch (error) {
        console.error('Error loading items:', error);
        document.getElementById('restock-items-table').innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-red-500">
                    Error loading items. Please try again.
                </td>
            </tr>
        `;
    }
}

// Render items in table with stock status highlighting
function renderItems(items) {
    const tbody = document.getElementById('restock-items-table');
    let html = '';

    // Initialize counters
    let outOfStockCount = 0;
    let lowStockCount = 0;
    let inStockCount = 0;
    
    items.forEach(item => {
        console.log('Processing item:', item); // Debug
        
        // Calculate available stock: total quantity - REQUESTED quantity
        const totalQuantity = parseInt(item.quantity) || 0;
        
        // Get REQUESTED quantity (from pending/approved requests)
        const requestedQuantity = parseInt(item.requested_quantity) || 
                                 parseInt(item.pending_quantity_sum) || 
                                 parseInt(item.reserved_stock) || 0;
        
        // Calculate available stock (what's actually available for new requests)
        const availableStock = Math.max(0, totalQuantity - requestedQuantity);
        
        console.log(`Item: ${item.name}, Total: ${totalQuantity}, Requested: ${requestedQuantity}, Available: ${availableStock}`);
        
        const minimumStock = parseInt(item.minimum_stock) || 0;
        
        // Determine CSS classes based on available stock status
        let rowClass = 'stock-status-in-stock';
        let statusIndicator = '<span class="status-indicator status-in-stock"></span>';
        let stockLevelClass = 'stock-level-good';
        let statusText = 'In Stock';
        let progressClass = 'progress-good';
        let inputClass = '';
        
        // Use AVAILABLE STOCK for status determination
        if (availableStock <= 0) {
            rowClass = 'stock-status-out-of-stock';
            statusIndicator = '<span class="status-indicator status-out-of-stock"></span>';
            stockLevelClass = 'stock-level-critical';
            statusText = 'Out of Stock';
            progressClass = 'progress-critical';
            inputClass = 'stock-input-critical';
            outOfStockCount++; // Count out of stock items
        } else if (minimumStock > 0 && availableStock <= minimumStock) {
            rowClass = 'stock-status-low-stock';
            statusIndicator = '<span class="status-indicator status-low-stock"></span>';
            stockLevelClass = 'stock-level-low';
            statusText = 'Low Stock';
            progressClass = 'progress-low';
            inputClass = 'stock-input-low';
            lowStockCount++; // Count low stock items
        } else {
            inStockCount++; // Count in stock items
        }
        
        // Calculate stock percentage for visual indicator
        let stockPercentage = 100;
        if (minimumStock > 0) {
            stockPercentage = Math.min(100, (availableStock / minimumStock) * 100);
        }
        
        html += `
            <tr class="hover:bg-gray-50 ${rowClass}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-medium text-gray-900 flex items-center">
                        ${statusIndicator}
                        ${item.name}
                    </div>
                    <div class="text-xs text-gray-500">${item.unit || 'units'}</div>
                    <div class="text-xs ${stockLevelClass} mt-1">
                        ${statusText}
                        ${minimumStock > 0 ? ` | Min: ${minimumStock}` : ''}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-lg font-bold ${stockLevelClass}">${availableStock}</div>
                    <div class="text-xs text-gray-500">
                        <div>Total: ${totalQuantity}</div>
                        ${requestedQuantity > 0 ? `<div class="text-yellow-600">Requested: ${requestedQuantity}</div>` : ''}
                        <div class="mt-1">Available = Total - Requested</div>
                    </div>
                    ${minimumStock > 0 ? `
                        <div class="stock-progress-bar mt-1">
                            <div class="stock-progress-fill ${progressClass}" style="width: ${stockPercentage}%"></div>
                        </div>
                    ` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" 
                           name="add_quantity[${item.id}]" 
                           min="0" 
                           max="10000" 
                           placeholder="0"
                           class="w-24 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${inputClass}"
                           oninput="updateNewTotal(this, ${availableStock})"
                           data-item-id="${item.id}"
                           data-available-stock="${availableStock}"
                           ${availableStock <= 0 ? 'autofocus' : ''}>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-lg font-bold text-blue-600" id="new-total-${item.id}">
                        ${availableStock}
                    </div>
                </td>
            </tr>
        `;
    });
    
    // Update the counter displays
    document.getElementById('out-of-stock-count').textContent = `(${outOfStockCount})`;
    document.getElementById('low-stock-count').textContent = `(${lowStockCount})`;
    document.getElementById('in-stock-count').textContent = `(${inStockCount})`;
    
    tbody.innerHTML = html;
}

// Update new total when quantity changes
function updateNewTotal(input, availableStock) {
    const itemId = input.getAttribute('data-item-id');
    const addQuantity = parseInt(input.value) || 0;
    const newTotal = availableStock + addQuantity;
    document.getElementById(`new-total-${itemId}`).textContent = newTotal;
}

// Submit restock form and show confirmation
async function submitRestock() {
    const formData = new FormData();
    const inputs = document.querySelectorAll('input[name^="add_quantity"]');
    const restockedItems = [];
    
    // Collect all items that have quantity > 0
    inputs.forEach(input => {
        const itemId = input.getAttribute('data-item-id');
        const addQuantity = parseInt(input.value) || 0;
        const availableStock = parseInt(input.getAttribute('data-available-stock'));
        
        if (addQuantity > 0) {
            const newTotal = availableStock + addQuantity;
            const itemName = input.closest('tr').querySelector('.font-medium').textContent;
            const unit = input.closest('tr').querySelector('.text-xs').textContent;
            
            restockedItems.push({
                id: itemId,
                name: itemName,
                unit: unit,
                available_stock: availableStock,
                quantity_added: addQuantity,
                new_total: newTotal
            });
            
            formData.append(`add_quantity[${itemId}]`, addQuantity);
        }
    });
    
    // If nothing to restock
    if (restockedItems.length === 0) {
        alert('Please enter quantities to add for at least one item.');
        return;
    }
    
    // Add CSRF token
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    // Show loading
    const button = document.getElementById('confirm-restock');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    button.disabled = true;
    
    try {
        const response = await fetch('{{ route("admin.items.restock.bulk.process") }}', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Close the restock modal
            closeRestockModal();
            
            // Show confirmation modal with restocked items
            showRestockConfirmation(restockedItems);
        } else {
            alert(data.message || 'Failed to process restock');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    } catch (error) {
        alert('Error: ' + error.message);
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Function to show restock confirmation modal
function showRestockConfirmation(restockedItems) {
    const tableBody = document.getElementById('confirmation-summary-table');
    const summaryText = document.getElementById('restock-summary-text');
    
    // Clear previous content
    tableBody.innerHTML = '';
    
    let totalItems = 0;
    let totalQuantity = 0;
    
    // Populate table rows
    restockedItems.forEach(item => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${item.name}</div>
                <div class="text-sm text-gray-500">${item.unit}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    +${item.quantity_added}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.new_total}
            </td>
        `;
        
        tableBody.appendChild(row);
        
        totalItems++;
        totalQuantity += parseInt(item.quantity_added);
    });
    
    // Update summary text
    summaryText.textContent = `Successfully added ${totalQuantity} units across ${totalItems} items`;
    
    // Show the modal
    const modal = document.getElementById('restock-confirmation-modal');
    modal.classList.remove('hidden');
}

// Add event listeners for closing confirmation modal (only once)
document.addEventListener('DOMContentLoaded', function() {
    const confirmationModal = document.getElementById('restock-confirmation-modal');
    
    if (confirmationModal) {
        // Close modal when clicking X button
        const closeButton = document.getElementById('close-confirmation-modal');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
                location.reload(); // Reload page after closing
            });
        }
        
        // Close modal when clicking Close button
        const closeSummary = document.getElementById('close-summary');
        if (closeSummary) {
            closeSummary.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
                location.reload(); // Reload page after closing
            });
        }
        
        // Close modal when clicking on background
        confirmationModal.addEventListener('click', function(e) {
            if (e.target === confirmationModal) {
                confirmationModal.classList.add('hidden');
                location.reload(); // Reload page after closing
            }
        });
    }
});
</script>