<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Inventory Report - <?php echo e($period); ?></title>
</head>
<body>
    <h1>Land Bank Inventory System</h1>
    <h2>Monthly Inventory Analysis Report</h2>
    <h3><?php echo e($period); ?></h3>
    <p><strong>Generated on:</strong> <?php echo e($generatedAt->format('F d, Y h:i A')); ?></p>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFinalized): ?><p><strong>Status:</strong> FINALIZED REPORT</p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <hr>
    
    <h2>📊 Executive Summary</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td><strong>Total Requests:</strong> <?php echo e(number_format($totalRequests)); ?></td>
            <td><strong>Items Restocked:</strong> <?php echo e(number_format($totalRestocked)); ?></td>
            <td><strong>Items Claimed:</strong> <?php echo e(number_format($totalClaimed)); ?></td>
        </tr>
        <tr>
            <td><strong>Active Teams:</strong> <?php echo e(number_format($activeTeams)); ?></td>
            <td><strong>Active Items:</strong> <?php echo e(number_format($totalItems)); ?></td>
            <td><strong>Activity Level:</strong> <?php echo e($averageActivity); ?></td>
        </tr>
    </table>
    
    <h2>📈 Inventory Flow Analysis</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ITEM</th>
                <th>BEGINNING</th>
                <th>REQUESTED</th>
                <th>CLAIMED</th>
                <th>RESTOCKED</th>
                <th>ENDING</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $inventoryFlowItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($item->name); ?></td>
                <td align="right"><?php echo e(number_format($item->beginning_quantity)); ?></td>
                <td align="right"><?php echo e(number_format($item->requested_quantity)); ?></td>
                <td align="right" style="color: red;">-<?php echo e(number_format($item->claimed_quantity)); ?></td>
                <td align="right" style="color: green;">+<?php echo e(number_format($item->restocked_quantity)); ?></td>
                <td align="right"><?php echo e(number_format($item->ending_quantity)); ?></td>
                <td><?php echo e($item->status); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" align="center">No inventory flow data available</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
    
    <h2>📊 Usage Patterns - All Items by Request Count (Last 30 Days)</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ITEM</th>
                <th>CURRENT STOCK</th>
                <th>MINIMUM STOCK</th>
                <th>TOTAL REQUESTS</th>
                <th>AVG REQUESTS/DAY</th>
                <th>DEMAND LEVEL</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topRequestedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($item->name); ?></td>
                <td align="right"><?php echo e(number_format($item->quantity)); ?></td>
                <td align="right"><?php echo e(number_format($item->minimum_stock)); ?></td>
                <td align="right"><?php echo e(number_format($item->total_requests)); ?></td>
                <td align="right"><?php echo e(number_format($item->avg_requests_per_day, 2)); ?></td>
                <td>
                    <?php if($item->total_requests > 20): ?> High Demand
                    <?php elseif($item->total_requests > 10): ?> Medium Demand
                    <?php elseif($item->total_requests > 0): ?> Normal Demand
                    <?php else: ?> No Demand
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" align="center">No usage data available</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
    
    <h2>👥 Most Teams Requested</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>TEAM</th>
                <th>MEMBERS</th>
                <th>REQUEST COUNT</th>
                <th>PERCENTAGE</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $mostActiveTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($team->name); ?></td>
                <td align="right"><?php echo e(number_format($team->members_count)); ?></td>
                <td align="right"><?php echo e(number_format($team->request_count)); ?></td>
                <td align="right">
                    <?php
                        $maxRequests = $mostActiveTeams->max('request_count');
                        $percentage = $maxRequests > 0 ? ($team->request_count / $maxRequests) * 100 : 0;
                    ?>
                    <?php echo e(number_format($percentage, 1)); ?>%
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4" align="center">No team request data available</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
    
    <h2>⚡ Fastest Depleting Items</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ITEM</th>
                <th>CURRENT STOCK</th>
                <th>MINIMUM STOCK</th>
                <th>REQUESTS (30 DAYS)</th>
                <th>DEPLETION RATE</th>
                <th>DAYS TO DEPLETION</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $fastDepletingItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($item->name); ?></td>
                <td align="right"><?php echo e(number_format($item->quantity)); ?></td>
                <td align="right"><?php echo e(number_format($item->minimum_stock)); ?></td>
                <td align="right"><?php echo e(number_format($item->requests_last_30_days)); ?></td>
                <td align="right"><?php echo e(number_format($item->depletion_rate, 1)); ?>%</td>
                <td align="right">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->days_to_depletion == 999 || $item->days_to_depletion >= 999): ?>
                        ∞ days
                    <?php else: ?>
                        <?php echo e($item->days_to_depletion); ?> days
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->quantity <= 0): ?> Out of Stock
                    <?php elseif($item->quantity <= $item->minimum_stock): ?> Low Stock
                    <?php elseif($item->days_to_depletion <= 7): ?> Critical
                    <?php elseif($item->days_to_depletion <= 14): ?> Warning
                    <?php else: ?> Normal
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" align="center">No depletion data available</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
    
    <h2>📉 Depletion Analysis</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td align="center" style="background-color: #f8d7da;">
                <strong><?php echo e($criticalItemsCount); ?></strong><br>Out of Stock
            </td>
            <td align="center" style="background-color: #fff3cd;">
                <strong><?php echo e($warningItemsCount); ?></strong><br>Low Stock
            </td>
            <td align="center" style="background-color: #d4edda;">
                <strong><?php echo e($safeItemsCount); ?></strong><br>In Stock
            </td>
        </tr>
    </table>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($yearlyComparison)): ?>
    <h2>📅 Yearly Comparison</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td align="center"><?php echo e($yearlyComparison['previous_year']['year'] ?? ($selectedYear - 1)); ?><br>
                <strong><?php echo e(number_format($yearlyComparison['previous_year']['total_requests'] ?? 0)); ?></strong>
            </td>
            <td align="center">Comparison<br>
                <strong>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($yearlyComparison['previous_year']['total_requests'] ?? 0) > 0): ?>
                        <?php echo e($yearlyComparison['comparison']['direction'] == 'up' ? '↑' : ($yearlyComparison['comparison']['direction'] == 'down' ? '↓' : '→')); ?>

                        <?php echo e(number_format($yearlyComparison['comparison']['change'] ?? 0, 1)); ?>%
                    <?php else: ?>
                        N/A
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </strong>
            </td>
            <td align="center"><?php echo e($selectedYear); ?> YTD<br>
                <strong><?php echo e(number_format($yearlyComparison['current_year']['total_requests'] ?? 0)); ?></strong>
            </td>
        </tr>
    </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <h2>💡 Recommendations</h2>
    <ul>
        <?php
            $recommendations = [];
            if ($criticalItemsCount > 0) $recommendations[] = "Immediate restocking needed for {$criticalItemsCount} out-of-stock items";
            if ($warningItemsCount > 0) $recommendations[] = "Schedule restocking for {$warningItemsCount} low-stock items";
            $criticalDepletion = $fastDepletingItems->where('days_to_depletion', '<=', 7)->count();
            if ($criticalDepletion > 0) $recommendations[] = "Review procurement schedule for {$criticalDepletion} critically depleting items";
            $highDemand = $topRequestedItems->where('total_requests', '>', 20)->count();
            if ($highDemand > 0) $recommendations[] = "Consider increasing stock levels for {$highDemand} high-demand items";
            if (empty($recommendations)) $recommendations[] = "Inventory levels are optimal";
        ?>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($rec); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </ul>
    
    <hr>
    <p><em>Report generated by Land Bank Inventory System</em></p>
</body>
</html><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views\reports\excel\monthly.blade.php ENDPATH**/ ?>