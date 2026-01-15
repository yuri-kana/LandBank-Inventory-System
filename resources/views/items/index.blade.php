@section('title', 'Inventory - Inventory System')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Inventory Items') }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center shadow-md transition duration-150 ease-in-out">
                    <i class="fas fa-plus mr-2"></i> Add New Item
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success Notification --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Success</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Search and Filter Section --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <form id="filterForm" method="GET" action="{{ auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index') }}">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="searchItems" 
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search items by name or unit... (Press Enter to search)" 
                                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150"
                                >
                            </div>
                            
                            <div class="flex space-x-4">
                                <select id="stockFilter" name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                                    <option value="">All Stock Status</option>
                                    <option value="in-stock" {{ request('status') == 'in-stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="low-stock" {{ request('status') == 'low-stock' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out-of-stock" {{ request('status') == 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                                
                                {{-- Show clear button if any filter is active --}}
                                @php
                                    $hasActiveFilter = request('search') || request('status');
                                @endphp
                                
                                @if($hasActiveFilter)
                                    <a href="{{ auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 flex items-center transition duration-150 ease-in-out shadow-sm">
                                        <i class="fas fa-times mr-2"></i> Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Hidden fields to preserve pagination --}}
                        @if(request('page'))
                            <input type="hidden" name="page" value="{{ request('page') }}">
                        @endif
                    </form>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($items->count() > 0 || request('search') || request('status') != 'all')
                    
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Item Name
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Unit
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Available Stock
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Min
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Status
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Updated
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $item)
                                        @php
                                            // Calculate available stock
                                            $pendingRequests = $item->pending_quantity_sum ?? 0;
                                            $availableStock = $item->quantity - $pendingRequests;
                                            
                                            // Determine status based on AVAILABLE stock
                                            if ($availableStock <= 0) {
                                                $status = 'out-of-stock';
                                                $statusClass = 'bg-red-100 text-red-800 border-red-300';
                                                $statusIcon = 'fa-times-circle';
                                                $statusText = 'Out of Stock';
                                            } elseif ($availableStock <= $item->minimum_stock) {
                                                $status = 'low-stock';
                                                $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                                $statusIcon = 'fa-exclamation-triangle';
                                                $statusText = 'Low Stock';
                                            } else {
                                                $status = 'in-stock';
                                                $statusClass = 'bg-green-100 text-green-800 border-green-300';
                                                $statusIcon = 'fa-check-circle';
                                                $statusText = 'In Stock';
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded flex items-center justify-center mr-3 border border-blue-200">
                                                        <i class="fas fa-box text-blue-600 text-sm"></i>
                                                    </div>
                                                    <div class="truncate max-w-xs">
                                                        <a href="{{ auth()->user()->isAdmin() ? route('admin.items.show', $item) : route('items.show', $item) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition duration-150 ease-in-out truncate block" title="{{ $item->name }}">
                                                            {{ $item->name }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <div class="font-medium">{{ $item->unit ?: '-' }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ number_format($item->available_stock) }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($item->minimum_stock) }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    <i class="fas {{ $statusIcon }} mr-1"></i> 
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                <div class="text-xs">{{ $item->updated_at->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-400">{{ $item->updated_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-3">
                                                    {{-- Admin Actions (Edit & Delete) --}}
                                                    @if(auth()->user()->isAdmin())
                                                        {{-- Edit --}}
                                                        <a href="{{ route('admin.items.edit', $item) }}" 
                                                        class="text-blue-600 hover:text-blue-800 transition duration-150 p-2"
                                                        title="Edit Item">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        {{-- Delete Button --}}
                                                        <button onclick="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->name) }}')" 
                                                                class="text-red-600 hover:text-red-800 transition duration-150 p-2"
                                                                title="Delete Item">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    {{-- Staff Action (Request Item) --}}
                                                    @if(auth()->user()->isTeamMember() && $availableStock > 0)
                                                        <a href="{{ route('requests.create') }}?item_id={{ $item->id }}" 
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-xs font-medium flex items-center transition duration-150 ease-in-out shadow-sm whitespace-nowrap"
                                                        title="Request this Item">
                                                            <i class="fas fa-hand-paper mr-1.5 text-xs"></i>
                                                            Request
                                                        </a>
                                                    @elseif(auth()->user()->isTeamMember())
                                                        <button class="bg-gray-300 text-gray-500 px-3 py-1.5 rounded-md text-xs font-medium flex items-center cursor-not-allowed whitespace-nowrap"
                                                                title="No stock available for request"
                                                                disabled>
                                                            <i class="fas fa-ban mr-1.5 text-xs"></i>
                                                            Unavailable
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- No Results Message --}}
                        @if($items->total() == 0 && (request('search') || request('status') != 'all'))
                            <div id="noResults" class="text-center py-12">
                                <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Items Found</h3>
                                <p class="text-gray-500 mb-4">Try adjusting your search or filter to find what you're looking for.</p>
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 inline-flex items-center transition duration-150 ease-in-out shadow-md">
                                    <i class="fas fa-times mr-2"></i> Clear All Filters
                                </a>
                            </div>
                        @endif

                        {{-- Pagination --}}
                        @if($items->hasPages())
                            <div class="mt-6">
                                {{ $items->appends(request()->except('page'))->links() }}
                            </div>
                        @endif
                        
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-12">
                            <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Items in Inventory</h3>
                            <p class="text-gray-500 mb-4">Get started by adding your first inventory item.</p>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 inline-flex items-center transition duration-150 ease-in-out shadow-md">
                                    <i class="fas fa-plus mr-2"></i> Add First Item
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if(auth()->user()->isAdmin())
    <div id="deleteModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirm Delete
                            </h3>
                            <div class="mt-2">
                                <p id="deleteMessage" class="text-sm text-gray-500">
                                    Are you sure you want to delete this item? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" 
                            onclick="closeDeleteModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        // Search functionality - only on Enter key press
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchItems');
            const stockFilter = document.getElementById('stockFilter');
            const filterForm = document.getElementById('filterForm');
            
            function submitFilterForm() {
                // Remove page parameter to go back to first page when searching
                const pageInput = filterForm.querySelector('input[name="page"]');
                if (pageInput) pageInput.remove();
                
                // Submit the form
                filterForm.submit();
            }
            
            // Stock filter: still submit on change (dropdown selection)
            if (stockFilter) {
                stockFilter.addEventListener('change', submitFilterForm);
            }
            
            // Search input: only submit on Enter key
            if (searchInput) {
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Prevent form submission if it's inside a form
                        submitFilterForm();
                    }
                });
            }
            
            // Remove any existing timeout-based search (debouncing)
            // This ensures search only happens on Enter
        });
        
        // Simple delete modal functions
        function openDeleteModal(itemId, itemName) {
            console.log('Opening delete modal for item:', itemId, itemName);
            
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const message = document.getElementById('deleteMessage');
            
            if (!modal || !form || !message) {
                console.error('Modal elements not found');
                return;
            }
            
            // Set the delete message
            message.textContent = `Are you sure you want to delete "${itemName}"? This action cannot be undone.`;
            
            // Set the form action
            form.action = `/admin/items/${itemId}`;
            console.log('Delete form action set to:', form.action);
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('deleteModal');
            if (modal && !modal.classList.contains('hidden')) {
                if (event.target === modal || event.target.classList.contains('bg-gray-500')) {
                    closeDeleteModal();
                }
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('deleteModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
            }
        });
        
        // Handle delete form submission
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                console.log('Delete form submitted');
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Deleting...';
                    submitBtn.disabled = true;
                }
            });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded - Inventory Items');
            
            // Add event listeners to all delete buttons
            const deleteButtons = document.querySelectorAll('button[onclick^="openDeleteModal"]');
            console.log('Found delete buttons:', deleteButtons.length);
            
            deleteButtons.forEach(button => {
                button.style.cursor = 'pointer';
                button.addEventListener('click', function(e) {
                    console.log('Delete button clicked!');
                });
            });
            
            // Clear search when Ctrl/Cmd + F is pressed
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
                
                // Clear search on Escape if search input is focused
                if (e.key === 'Escape' && document.activeElement === searchInput) {
                    if (searchInput.value !== '') {
                        e.preventDefault();
                        searchInput.value = '';
                        // Optionally submit empty search
                        submitFilterForm();
                    }
                }
            });
        });
    </script>

    <style>
        /* Make sure delete button is clickable */
        button[onclick^="openDeleteModal"] {
            cursor: pointer !important;
            background: transparent;
            border: none;
        }
        
        button[onclick^="openDeleteModal"]:hover {
            color: #dc2626;
        }
        
        /* Table styling */
        #itemsTable {
            table-layout: fixed;
        }
        
        #itemsTable th:nth-child(1), #itemsTable td:nth-child(1) { width: 22%; }
        #itemsTable th:nth-child(2), #itemsTable td:nth-child(2) { width: 8%; }
        #itemsTable th:nth-child(3), #itemsTable td:nth-child(3) { width: 10%; }
        #itemsTable th:nth-child(4), #itemsTable td:nth-child(4) { width: 12%; }
        #itemsTable th:nth-child(5), #itemsTable td:nth-child(5) { width: 8%; }
        #itemsTable th:nth-child(6), #itemsTable td:nth-child(6) { width: 12%; }
        #itemsTable th:nth-child(7), #itemsTable td:nth-child(7) { width: 12%; }
        
        /* Search input styling */
        #searchItems::placeholder {
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</x-app-layout>