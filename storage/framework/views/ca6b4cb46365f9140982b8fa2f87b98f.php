<!-- Restock Confirmation Modal -->
<div id="restock-confirmation-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="confirmation-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="confirmation-modal-title">
                                    <i class="fas fa-check-circle mr-2 text-green-600"></i> Restock Confirmed
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">The following items have been added to inventory</p>
                            </div>
                            <button type="button" id="close-confirmation-modal" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <div class="bg-white rounded-lg border border-gray-200">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <h4 class="font-medium text-gray-700">Restock Summary</h4>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ITEM NAME
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    QUANTITY ADDED
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    NEW TOTAL STOCK
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="confirmation-summary-table">
                                            <!-- Summary will be populated by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-4 border-t border-gray-200 bg-green-50">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900" id="restock-summary-text"></p>
                                        </div>
                                        <button type="button" id="close-summary" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            Close
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
</div><?php /**PATH C:\Users\Jhon Rhey\Downloads\inventory-system\resources\views/components/modals/restock-confirmation.blade.php ENDPATH**/ ?>