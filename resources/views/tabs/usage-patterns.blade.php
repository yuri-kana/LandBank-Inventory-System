<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Usage Patterns Table - Container Made Larger -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-md min-h-[600px]">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
            <h4 class="font-medium text-gray-700">All Items by Request Count</h4>
            <p class="text-sm text-gray-500 mt-1">
                @if(isset($period_info))
                    Current Month Only ({{ $period_info['month_name'] }} {{ $period_info['year'] }})
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">
                        Monthly Reset • Day {{ $period_info['days_elapsed'] }}/{{ $period_info['days_in_month'] }}
                    </span>
                @else
                    Current Month Only (Resets on 1st of each month)
                @endif
            </p>
        </div>
        <div class="divide-y divide-gray-200 h-[500px] overflow-y-auto">
            @forelse($topRequestedItems ?? [] as $item)
                <div class="p-4 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center flex-1">
                            <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-900 truncate">{{ $item->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->total_requests }} requests • {{ $item->teams_info ?? 'No teams' }}
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium {{ $item->demand_color ?? 'text-gray-500' }}">
                                <i class="fas fa-chart-bar mr-1"></i> {{ $item->demand_level ?? 'No Demand' }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detailed Stats -->
                    <div class="grid grid-cols-3 gap-2 text-xs text-gray-600 mb-3">
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="font-medium">Current Stock</div>
                            <div>{{ $item->quantity ?? 0 }}</div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="font-medium">Min. Stock</div>
                            <div>{{ $item->minimum_stock ?? 0 }}</div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="font-medium">Total Qty</div>
                            <div>{{ $item->total_quantity ?? 0 }}</div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Avg. Requests/Day (Monthly)</span>
                            <span class="font-medium">{{ $item->avg_display ?? '0.00/day' }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(($item->total_requests / max($maxRequests, 1)) * 100, 100) }}%"></div>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $item->period_info ?? '' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-chart-line text-2xl text-gray-300 mb-2"></i>
                    <p>No usage data available for this month</p>
                    <p class="text-xs text-gray-400 mt-1">Data resets on 1st of each month</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Most Active Teams - Container Made Larger -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-md min-h-[600px]">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
            <h4 class="font-medium text-gray-700">Most Active Teams</h4>
            <p class="text-sm text-gray-500 mt-1">
                Team request patterns 
                @if(isset($period_info))
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded ml-2">
                        {{ $period_info['month_name'] }} {{ $period_info['year'] }}
                    </span>
                @endif
            </p>
        </div>
        <div class="h-[500px] overflow-y-auto p-6">
            @if(isset($mostActiveTeams) && count($mostActiveTeams) > 0)
                <div class="space-y-6">
                    @foreach($mostActiveTeams as $team)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $team->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $team->members_count }} members • 
                                        {{ $team->item_count ?? 0 }} different items
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-blue-600">
                                    {{ $team->request_count }} requests
                                </div>
                            </div>
                            
                            @if($team->requested_items)
                                <div class="text-xs text-gray-600 mb-2">
                                    <span class="font-medium">Items: </span>
                                    {{ Str::limit($team->requested_items, 60) }}
                                </div>
                            @endif
                            
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $maxRequests = $mostActiveTeams->max('request_count');
                                    $percentage = $maxRequests > 0 ? ($team->request_count / $maxRequests) * 100 : 0;
                                @endphp
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ $team->total_quantity ?? 0 }} total quantity</span>
                                <span>Avg: {{ $team->request_count > 0 ? round($team->total_quantity / $team->request_count, 1) : 0 }}/request</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-2xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">No team request data available this month</p>
                    <p class="text-xs text-gray-400 mt-1">Data resets on 1st of each month</p>
                </div>
            @endif
        </div>
    </div>
</div>