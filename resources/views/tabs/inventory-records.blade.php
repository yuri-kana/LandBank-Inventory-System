<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Enhanced Header with Year Filter -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h4 class="font-semibold text-lg text-gray-900 flex items-center">
                    <i class="fas fa-archive mr-2 text-blue-600"></i>
                    Complete Inventory Analysis
                </h4>
                <div class="flex items-center mt-2 space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-boxes mr-1"></i>
                        {{ $totalItems ?? 0 }} Total Items
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Viewing {{ $selectedYear === 'all' ? 'All Time' : $selectedYear }}
                    </span>
                </div>
            </div>
            
            <!-- Year Filter Dropdown -->
            <div class="flex items-center space-x-3">
    <div class="relative">
        <form method="GET" action="{{ route('dashboard', ['tab' => 'inventory-records']) }}" id="year-filter-form" class="flex items-center">
            <label for="year-select" class="mr-2 text-sm font-medium text-gray-700">
                <i class="fas fa-filter mr-1"></i> Filter by Year:
            </label>
            <div class="relative">
                <select id="year-select" name="year" 
                        onchange="this.form.submit()"
                        class="appearance-none bg-white border border-gray-300 rounded-lg py-2 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @php
                        $currentYear = date('Y');
                        $currentMonth = date('n'); // Current month (1-12)
                        
                        // Remove 'all' from available years
                        $availableYears = array_filter($availableYears, function($year) {
                            return $year !== 'all';
                        });
                        // Ensure all are numeric and unique
                        $availableYears = array_unique(array_filter($availableYears, 'is_numeric'));
                        
                        // Always include next year (2027)
                        $nextYear = $currentYear + 1;
                        if (!in_array($nextYear, $availableYears)) {
                            $availableYears[] = $nextYear;
                        }
                        
                        // Check if current year is finished (after December)
                        $isCurrentYearFinished = $currentMonth == 12 && date('j') == 31; // December 31st
                        // Or you can check if we're in January of next year
                        $isNextYearStarted = $currentMonth == 1 && $currentYear == $nextYear - 1;
                        
                        // Sort in descending order (newest first)
                        rsort($availableYears);
                    @endphp
                    
                    @foreach($availableYears as $year)
                        @if($year < $currentYear)
                            <!-- Past years - always selectable -->
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @elseif($year == $currentYear)
                            <!-- Current year - always selectable -->
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }} (Current)
                            </option>
                        @elseif($year == $nextYear)
                            <!-- Next year (2027) - conditionally disabled -->
                            @if($isCurrentYearFinished || $isNextYearStarted)
                                <!-- Current year is finished, next year becomes selectable -->
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @else
                                <!-- Current year not finished yet, next year is disabled -->
                                <option value="{{ $year }}" disabled class="text-gray-400 bg-gray-100">
                                    {{ $year }} (Available Jan 1, {{ $nextYear }})
                                </option>
                            @endif
                        @elseif($year > $nextYear)
                            <!-- Future years beyond next year - always disabled -->
                            @php
                                $yearsAway = $year - $currentYear;
                            @endphp
                            <option value="{{ $year }}" disabled class="text-gray-400 bg-gray-100">
                                {{ $year }} (Available in {{ $yearsAway }} year{{ $yearsAway > 1 ? 's' : '' }})
                            </option>
                        @endif
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Year Navigation Buttons -->
    <div class="flex items-center space-x-2">
        <button onclick="navigateYear('prev')" 
                class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                title="Previous Year">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button onclick="navigateYear('next')" 
                class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                title="Next Year"
                {{ $selectedYear >= date('Y') ? 'disabled' : '' }}>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="grid grid-cols-3 gap-4 justify-items-center">
            <div class="bg-white rounded-lg p-4 border border-gray-200 w-full max-w-xs">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                        <i class="fas fa-hand-paper"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Requests</p>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($totalRequests) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200 w-full max-w-xs">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-green-100 text-green-600 mr-3">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Items Restocked</p>
                        <p class="text-xl font-semibold {{ $totalRestocked > 0 ? 'text-green-600' : 'text-gray-900' }}">
                            {{ $totalRestocked > 0 ? '+' : '' }}{{ number_format($totalRestocked) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200 w-full max-w-xs">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-purple-100 text-purple-600 mr-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500">Items Claimed</p>
                        <p class="text-xl font-semibold text-gray-900">
                            @php
                                $sumClaimed = collect($monthlyReports)->sum('total_claimed');
                            @endphp
                            {{ number_format($sumClaimed) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Table Section - REMOVED SCROLL -->
    <div class="w-full">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        <div class="flex items-center">
                            <span>Month</span>
                            <i class="fas fa-sort ml-1 text-gray-400 cursor-pointer hover:text-gray-600"></i>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        <div class="flex items-center">
                            <span>Total Requests</span>
                            <i class="fas fa-sort ml-1 text-gray-400 cursor-pointer hover:text-gray-600"></i>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        <div class="flex items-center">
                            <span>Items Restocked</span>
                            <i class="fas fa-sort ml-1 text-gray-400 cursor-pointer hover:text-gray-600"></i>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        <div class="flex items-center">
                            <span>Items Claimed</span>
                            <i class="fas fa-sort ml-1 text-gray-400 cursor-pointer hover:text-gray-600"></i>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        <div class="flex items-center">
                            <span>Status</span>
                            <i class="fas fa-sort ml-1 text-gray-400 cursor-pointer hover:text-gray-600"></i>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($monthlyReports as $report)
                @php
                    $statusColor = match($report->status) {
                        'High Activity' => 'bg-purple-100 text-purple-800',
                        'Medium Activity' => 'bg-blue-100 text-blue-800',
                        'No Activity' => 'bg-gray-100 text-gray-800',
                        default => 'bg-emerald-100 text-emerald-800'
                    };
                    
                    $statusIcon = match($report->status) {
                        'High Activity' => 'fas fa-fire',
                        'Medium Activity' => 'fas fa-chart-line',
                        'No Activity' => 'fas fa-minus-circle',
                        default => 'fas fa-check-circle'
                    };
                    
                    $monthName = \DateTime::createFromFormat('!m', $report->month)->format('F');
                    $period = $monthName . ' ' . $report->year;
                    
                    // Check if month has ended
                    $currentDate = now();
                    $monthEndDate = \Carbon\Carbon::create($report->year, $report->month, 1)->endOfMonth();
                    $hasMonthEnded = $currentDate->greaterThan($monthEndDate);
                    
                    // Check if report is already finalized
                    $isFinalized = $report->is_finalized ?? false;
                    
                    // Show finalize button only if: month has ended AND report is not already finalized
                    $showFinalizeButton = $hasMonthEnded && !$isFinalized;
                @endphp
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="px-6 py-4 w-1/6">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $period }}</div>
                                <div class="text-xs {{ $isFinalized ? 'text-green-600 bg-green-50' : 'text-yellow-600 bg-yellow-50' }} px-2 py-1 rounded-full inline-block mt-1">
                                    @if($isFinalized)
                                        <i class="fas fa-check-circle mr-1"></i>Final Report
                                    @else
                                        <i class="fas fa-clock mr-1"></i>Preliminary Report
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 w-1/6">
                        <div class="flex items-center">
                            <span class="font-medium text-gray-900 mr-2">{{ $report->total_requests ?? 0 }}</span>
                            <span class="text-xs text-gray-500 mt-1">Requests made</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 w-1/6">
                        <div class="flex items-center">
                            <span class="font-medium {{ $report->total_restocked > 0 ? 'text-green-600' : 'text-gray-900' }} mr-2">
                                {{ $report->total_restocked > 0 ? '+' : '' }}{{ $report->total_restocked }}
                            </span>
                            <span class="text-xs text-gray-500">Quantity added</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 w-1/6">
                        <div class="flex items-center">
                            <span class="font-medium text-gray-900 mr-2">{{ $report->total_claimed }}</span>
                            <div class="text-xs text-gray-500 mt-1">Items claimed</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 w-1/6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                            <i class="{{ $statusIcon }} mr-1"></i>
                            {{ $report->status }}
                            @if(!$hasMonthEnded)
                            <i class="fas fa-spinner fa-spin ml-1 text-blue-500" title="Month still in progress"></i>
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 w-1/6">
                        <div class="flex items-center space-x-2">
                            <!-- Download Button with Dropdown -->
                            <div class="relative">
                                <button class="monthly-report-download-btn p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                        title="Download Report">
                                    <i class="fas fa-download"></i>
                                </button>
                                
                                <!-- Monthly Report Dropdown - PDF and Excel Only -->
                                <div class="monthly-report-dropdown absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden">
                                    <div class="p-2">
                                        <button onclick="downloadReport('pdf', {{ $report->year }}, {{ $report->month }}, '{{ $monthName }}')"
                                                class="download-pdf-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg flex items-center">
                                            <i class="fas fa-file-pdf mr-2 text-red-500"></i> PDF
                                        </button>
                                        <button onclick="downloadReport('excel', {{ $report->year }}, {{ $report->month }}, '{{ $monthName }}')"
                                                class="download-excel-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg mt-1 flex items-center">
                                            <i class="fas fa-file-excel mr-2 text-green-500"></i> Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Finalize Report Button - ONLY SHOW IF MONTH HAS ENDED -->
                            @if($showFinalizeButton)
                            <button onclick="finalizeReport({{ $report->year }}, {{ $report->month }})" 
                                    class="finalize-report p-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" 
                                    title="Finalize Report">
                                <i class="fas fa-lock"></i>
                            </button>
                            @endif
                            
                            <!-- Show info icon if month hasn't ended yet -->
                            @if(!$hasMonthEnded && !$isFinalized)
                            <div class="relative group">
                                <div class="p-2 text-gray-400 cursor-help" title="Report cannot be finalized yet - Month still in progress">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                    Can finalize after {{ $monthEndDate->format('F j, Y') }}
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-calendar-times text-2xl text-gray-300 mb-2"></i>
                        <p>No monthly reports available</p>
                        <button id="generate-reports-btn" onclick="generateMissingReports()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-magic mr-2"></i>Generate Reports
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">{{ count($monthlyReports) }}</span> monthly reports for 
                <span class="font-medium">{{ $selectedYear === 'all' ? 'All Time' : $selectedYear }}</span>
            </div>
            <div class="text-xs text-gray-500">
                Data as of {{ now()->format('F j, Y g:i A') }}
            </div>
        </div>
    </div>
</div>

<!-- Yearly Summary Section -->
<div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h5 class="font-medium text-gray-900 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
            Yearly Comparison Summary
        </h5>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="text-sm text-gray-500 mb-2">{{ $yearlyComparison['previous_year']['year'] ?? ($selectedYear - 1) }} Total Requests</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($yearlyComparison['previous_year']['total_requests'] ?? 0) }}</div>
                <div class="text-xs text-gray-500 mt-1">
                    @if(($yearlyComparison['previous_year']['total_requests'] ?? 0) > 0)
                        {{ $yearlyComparison['previous_year']['most_requested_item']['name'] ?? 'No data' }} was most requested
                    @else
                        No data available
                    @endif
                </div>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="text-sm text-gray-500 mb-2">{{ $selectedYear }} YTD Requests</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($yearlyComparison['current_year']['total_requests'] ?? 0) }}</div>
                <div class="text-xs {{ $yearlyComparison['comparison']['direction'] == 'up' ? 'text-green-600' : ($yearlyComparison['comparison']['direction'] == 'down' ? 'text-red-600' : 'text-gray-600') }} mt-1">
                    @if(($yearlyComparison['previous_year']['total_requests'] ?? 0) > 0)
                        @if($yearlyComparison['comparison']['direction'] == 'up')
                            <i class="fas fa-arrow-up mr-1"></i>
                        @elseif($yearlyComparison['comparison']['direction'] == 'down')
                            <i class="fas fa-arrow-down mr-1"></i>
                        @endif
                        {{ number_format($yearlyComparison['comparison']['change'] ?? 0, 1) }}% from last year
                    @else
                        Year to Date
                    @endif
                </div>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="text-sm text-gray-500 mb-2">Items Claimed YTD</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($yearlyComparison['current_year']['total_claimed'] ?? 0) }}</div>
                <div class="text-xs text-gray-500 mt-1">Year to Date</div>
            </div>
        </div>
    </div>
</div>

<!-- SIMPLE WORKING JAVASCRIPT -->
<script>
// Make sure DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('📊 Inventory Records JavaScript loaded');
    
    // Initialize dropdown functionality
    initializeDropdowns();
    
    // Check if buttons are working
    testButtons();
});

// Initialize dropdown functionality
function initializeDropdowns() {
    // Add click event to download buttons to show dropdown
    document.querySelectorAll('.monthly-report-download-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Download button clicked');
            
            // Find the dropdown
            const dropdown = this.nextElementSibling;
            
            // Close all other dropdowns first
            document.querySelectorAll('.monthly-report-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.classList.add('hidden');
                }
            });
            
            // Toggle this dropdown
            dropdown.classList.toggle('hidden');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.monthly-report-download-btn') && 
            !e.target.closest('.monthly-report-dropdown')) {
            document.querySelectorAll('.monthly-report-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
}

// Test if buttons are working
function testButtons() {
    console.log('Testing buttons...');
    console.log('Download buttons found:', document.querySelectorAll('.monthly-report-download-btn').length);
    console.log('View buttons found:', document.querySelectorAll('.view-report-details').length);
    console.log('Finalize buttons found:', document.querySelectorAll('.finalize-report').length);
}

// View Report Details Function
function viewReportDetails(year, month) {
    console.log(`📋 View report details: ${year}-${month}`);
    alert(`View details for ${year}-${month}\n\nThis feature will show detailed report information.`);
}

// Download Report Function
function downloadReport(format, year, month, monthName) {
    console.log(`📥 Downloading ${format} report for ${monthName} ${year}`);
    
    // Simple download - you can update URLs as needed
    let url;
    if (format === 'pdf') {
        url = `/admin/reports/download/pdf?year=${year}&month=${month}`;
    } else if (format === 'excel') {
        url = `/admin/reports/download/excel?year=${year}&month=${month}`;
    }
    
    // Open in new tab
    window.open(url, '_blank');
    
    // Close dropdown
    document.querySelectorAll('.monthly-report-dropdown').forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
}

// Finalize Report Function
function finalizeReport(year, month) {
    const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
    const monthEndDate = new Date(year, month, 0); // Last day of the month
    const today = new Date();
    
    // Check if month has ended
    if (today <= monthEndDate) {
        alert(`⚠️ Cannot finalize report yet!\n\n${monthName} ${year} is still in progress.\nYou can finalize after ${monthEndDate.toLocaleDateString()}.`);
        return;
    }
    
    // Check if report already exists and is finalized
    if (confirm(`Are you sure you want to finalize the report for ${monthName} ${year}?\n\n⚠️ IMPORTANT:\n- This will lock all data for ${monthName}\n- No changes can be made after finalizing\n- Reports will only include data from ${monthName} 1-${monthEndDate.getDate()}`)) {
        console.log(`🔒 Finalizing report: ${year}-${month}`);
        
        // Show loading
        showLoading(`Finalizing ${monthName} ${year} report...`);
        
        // Make API call to finalize
        fetch(`/admin/reports/finalize`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                year: year, 
                month: month,
                finalize_date: today.toISOString().split('T')[0]
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                alert(`✅ Report for ${monthName} ${year} has been finalized!\n\nReport includes data from:\n${monthName} 1 - ${monthEndDate.getDate()}, ${year}\n\nPage will reload...`);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                alert(`❌ Failed to finalize report: ${data.message}`);
            }
        })
        .catch(error => {
            hideLoading();
            alert(`❌ Error: ${error.message}`);
        });
    }
}

function showLoading(message) {
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loading-overlay';
    loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    loadingOverlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl mr-3"></i>
                <span class="text-gray-700">${message}</span>
            </div>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
}

function hideLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

// Generate Missing Reports Function
function generateMissingReports() {
    const currentYear = new Date().getFullYear();
    
    if (confirm(`Generate missing monthly reports for ${currentYear}?`)) {
        console.log('✨ Generate missing reports clicked');
        alert(`Generating reports for ${currentYear}...\n\nThis will create monthly reports for all months.`);
        // In real implementation, you would make an API call here
        // setTimeout(() => location.reload(), 1500);
    }
}

// Year Navigation Function
function navigateYear(direction) {
    const yearSelect = document.getElementById('year-select');
    const yearFilterForm = document.getElementById('year-filter-form');
    let currentYear = yearSelect.value;
    
    if (currentYear === 'all') {
        currentYear = new Date().getFullYear();
    }
    
    let newYear;
    if (direction === 'prev') {
        newYear = parseInt(currentYear) - 1;
    } else if (direction === 'next') {
        newYear = parseInt(currentYear) + 1;
        
        // Check if next year is in the future
        const currentActualYear = new Date().getFullYear();
        if (newYear > currentActualYear) {
            // Check if next year is exactly one year ahead (2027 when current is 2026)
            if (newYear === currentActualYear + 1) {
                // Allow navigation to next year even if it's future
                // It will appear as disabled in dropdown
            } else if (newYear > currentActualYear + 1) {
                alert(`Cannot navigate to ${newYear}. You can only view data up to ${currentActualYear + 1}.`);
                return;
            }
        }
    } else {
        return;
    }
    
    // Check if the new year exists in the dropdown
    const optionExists = Array.from(yearSelect.options).some(option => 
        option.value === newYear.toString() && !option.disabled
    );
    
    if (optionExists) {
        yearSelect.value = newYear;
        yearFilterForm.submit();
    } else {
        // Check if it's a disabled future year
        const futureOption = Array.from(yearSelect.options).find(option => 
            option.value === newYear.toString() && option.disabled
        );
        if (futureOption) {
            alert(`${futureOption.textContent} is not available yet.`);
        } else {
            alert(`No data available for ${newYear}`);
        }
    }
}
</script>