<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Inventory Report - <?php echo e($period); ?></title>
    <style>
        @page { margin: 20px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .section { margin-bottom: 20px; page-break-inside: avoid; }
        .section-title { background-color: #f0f0f0; padding: 8px; font-weight: bold; border-left: 4px solid #333; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 8px; text-align: left; font-weight: bold; }
        td { border: 1px solid #dee2e6; padding: 8px; }
        .summary-box { border: 1px solid #dee2e6; padding: 15px; margin-bottom: 15px; background-color: #f8f9fa; }
        .highlight { background-color: #fff3cd; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .page-break { page-break-before: always; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 10px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .finalized-badge { 
            display: inline-block; 
            padding: 8px 15px; 
            border-radius: 5px; 
            font-weight: bold;
            margin: 10px 0;
        }
        .finalized-yes { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb;
        }
        .finalized-no { 
            background-color: #fff3cd; 
            color: #856404; 
            border: 1px solid #ffeaa7;
        }
        .total-row { 
            background-color: #f8f9fa; 
            font-weight: bold; 
            border-top: 2px solid #333;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1 style="margin-bottom: 5px;">Land Bank Inventory System</h1>
        <h2 style="margin-top: 0; color: #666;">Monthly Inventory Analysis Report</h2>
        <h3 style="color: #333;"><?php echo e($period); ?></h3>
        <p>Generated on: <?php echo e($generatedAt->format('F d, Y h:i A')); ?></p>
        
        <!-- FINALIZED STATUS BADGE -->
        <div class="finalized-badge <?php echo e($isFinalized ? 'finalized-yes' : 'finalized-no'); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFinalized): ?>
                ✓ FINALIZED REPORT
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($finalizedAt) && $finalizedAt): ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                ⚠️ DRAFT REPORT
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="section">
        <div class="section-title">📊 Executive Summary</div>
        <div class="summary-box">
            <table>
                <tr>
                    <td width="33%"><strong>Total Items:</strong> <?php echo e(number_format($totalItems)); ?></td>
                    <td width="33%"><strong>Items Restocked:</strong> <?php echo e(number_format($totalRestocked)); ?></td>
                    <td width="33%"><strong>Items Claimed:</strong> <?php echo e(number_format($totalClaimed)); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Request Status Breakdown -->
<div class="section">
    <div class="section-title">📊 Request Status Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>STATUS</th>
                <th class="text-right">COUNT</th>
                <th class="text-right">PERCENTAGE</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $total = $request_stats['total_requests'] ?? 0;
            ?>
            <tr>
                <td><strong>Total Requests</strong></td>
                <td class="text-right"><strong><?php echo e(number_format($total)); ?></strong></td>
                <td class="text-right"><strong>100%</strong></td>
            </tr>
            <tr>
                <td>✅ Approved</td>
                <td class="text-right"><?php echo e(number_format($request_stats['approved_requests'] ?? 0)); ?></td>
                <td class="text-right"><?php echo e($total > 0 ? number_format((($request_stats['approved_requests'] ?? 0) / $total) * 100, 1) : 0); ?>%</td>
            </tr>
            <tr>
                <td>❌ Rejected</td>
                <td class="text-right"><?php echo e(number_format($request_stats['rejected_requests'] ?? 0)); ?></td>
                <td class="text-right"><?php echo e($total > 0 ? number_format((($request_stats['rejected_requests'] ?? 0) / $total) * 100, 1) : 0); ?>%</td>
            </tr>
            <tr>
                <td>📦 Claimed</td>
                <td class="text-right"><?php echo e(number_format($request_stats['claimed_requests'] ?? 0)); ?></td>
                <td class="text-right"><?php echo e($total > 0 ? number_format((($request_stats['claimed_requests'] ?? 0) / $total) * 100, 1) : 0); ?>%</td>
            </tr>
            <tr>
                <td>⏳ Pending</td>
                <td class="text-right"><?php echo e(number_format($request_stats['pending_requests'] ?? 0)); ?></td>
                <td class="text-right"><?php echo e($total > 0 ? number_format((($request_stats['pending_requests'] ?? 0) / $total) * 100, 1) : 0); ?>%</td>
            </tr>
        </tbody>
    </table>
</div>

    <!-- Inventory Flow Analysis -->
    <div class="section">
        <div class="section-title">📈 Inventory Flow Analysis</div>
        <table>
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th class="text-right">BEGINNING</th>
                    <th class="text-right">REQUESTED</th>
                    <th class="text-right">CLAIMED</th>
                    <th class="text-right">RESTOCKED</th>
                    <th class="text-right">ENDING</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $inventoryFlowItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($item->name); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->beginning_quantity)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->requested_quantity)); ?></td>
                    <td class="text-right" style="color: #dc3545;">-<?php echo e(number_format($item->claimed_quantity)); ?></td>
                    <td class="text-right" style="color: #28a745;">+<?php echo e(number_format($item->restocked_quantity)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->ending_quantity)); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->status === 'Out of Stock'): ?>
                            <span class="badge badge-danger">Out of Stock</span>
                        <?php elseif($item->status === 'Needs Restock'): ?>
                            <span class="badge badge-warning">Needs Restock</span>
                        <?php elseif($item->status === 'Restocked'): ?>
                            <span class="badge badge-success">Restocked</span>
                        <?php else: ?>
                            <span class="badge badge-success">In Stock</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center">No inventory flow data available</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- TOTAL ROW -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inventoryFlowItems->count() > 0): ?>
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($inventoryFlowItems->sum('beginning_quantity'))); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($inventoryFlowItems->sum('requested_quantity'))); ?></strong></td>
                    <td class="text-right"><strong style="color: #dc3545;">-<?php echo e(number_format($inventoryFlowItems->sum('claimed_quantity'))); ?></strong></td>
                    <td class="text-right"><strong style="color: #28a745;">+<?php echo e(number_format($inventoryFlowItems->sum('restocked_quantity'))); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($inventoryFlowItems->sum('ending_quantity'))); ?></strong></td>
                    <td>
                        <?php
                            $totalItemsNeedingRestock = $inventoryFlowItems->where('status', 'Needs Restock')->count();
                            $totalItemsOutOfStock = $inventoryFlowItems->where('status', 'Out of Stock')->count();
                        ?>
                        <small>
                            <?php if($totalItemsOutOfStock > 0): ?>
                                <?php echo e($totalItemsOutOfStock); ?> out of stock
                            <?php endif; ?>
                            <?php if($totalItemsNeedingRestock > 0): ?>
                                <?php echo e($totalItemsOutOfStock > 0 ? ', ' : ''); ?><?php echo e($totalItemsNeedingRestock); ?> need restock
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </small>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        
        <!-- Flow Summary -->
        <div class="summary-box">
            <h4>Flow Summary:</h4>
            <p><strong>Total Beginning Value:</strong> <?php echo e(number_format($inventoryFlowItems->sum('beginning_quantity'))); ?> units</p>
            <p><strong>Net Change:</strong> 
                <?php
                    $netChange = $inventoryFlowItems->sum('restocked_quantity') - $inventoryFlowItems->sum('claimed_quantity');
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($netChange > 0): ?>
                    <span style="color: #28a745;">+<?php echo e(number_format($netChange)); ?> units (Increase)</span>
                <?php elseif($netChange < 0): ?>
                    <span style="color: #dc3545;"><?php echo e(number_format($netChange)); ?> units (Decrease)</span>
                <?php else: ?>
                    <span><?php echo e(number_format($netChange)); ?> units (No Change)</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
            <p><strong>Items Needing Restock:</strong> <?php echo e($inventoryFlowItems->where('status', 'Needs Restock')->count()); ?></p>
            <p><strong>Items Out of Stock:</strong> <?php echo e($inventoryFlowItems->where('status', 'Out of Stock')->count()); ?></p>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Usage Patterns -->
    <div class="section">
        <div class="section-title">📊 Usage Patterns - All Items by Request Count (Last 30 Days)</div>
        <table>
            <thead>
                <tr>
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
                    <td class="text-right"><?php echo e(number_format($item->quantity)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->minimum_stock)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->total_requests)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->avg_requests_per_day, 2)); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->total_requests > 20): ?>
                            <span class="badge badge-danger">High Demand</span>
                        <?php elseif($item->total_requests > 10): ?>
                            <span class="badge badge-warning">Medium Demand</span>
                        <?php elseif($item->total_requests > 0): ?>
                            <span class="badge badge-success">Normal Demand</span>
                        <?php else: ?>
                            <span>No Demand</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center">No usage data available</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- TOTAL ROW for Usage Patterns -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topRequestedItems->count() > 0): ?>
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($topRequestedItems->sum('quantity'))); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($topRequestedItems->sum('minimum_stock'))); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($topRequestedItems->sum('total_requests'))); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($topRequestedItems->avg('avg_requests_per_day'), 2)); ?></strong></td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Most Active Teams -->
    <div class="section">
        <div class="section-title">👥 Most Active Teams</div>
        <table>
            <thead>
                <tr>
                    <th>TEAM</th>
                    <th>MEMBERS</th>
                    <th>REQUEST COUNT</th>
                    <th>PERCENTAGE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Sort teams alphabetically by name
                    $sortedTeams = collect($mostActiveTeams)->sortBy(function($team) {
                        // Extract numeric part for natural sorting (Team 1, Team 2, etc.)
                        $name = $team->name ?? $team['name'] ?? '';
                        preg_match('/\d+/', $name, $matches);
                        $number = isset($matches[0]) ? (int)$matches[0] : 999;
                        return $number;
                    })->values();
                    
                    $maxRequests = $sortedTeams->max('request_count') ?? 1;
                    $totalMembers = $sortedTeams->sum('members_count');
                    $totalRequests = $sortedTeams->sum('request_count');
                    $teamCount = $sortedTeams->count();
                ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sortedTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($team->name ?? $team['name'] ?? 'N/A'); ?></td>
                    <td class="text-right"><?php echo e(number_format($team->members_count ?? $team['members_count'] ?? 0)); ?></td>
                    <td class="text-right"><?php echo e(number_format($team->request_count ?? $team['request_count'] ?? 0)); ?></td>
                    <td class="text-right">
                        <?php
                            $teamRequestCount = $team->request_count ?? $team['request_count'] ?? 0;
                            $percentage = $maxRequests > 0 ? ($teamRequestCount / $maxRequests) * 100 : 0;
                        ?>
                        <?php echo e(number_format($percentage, 1)); ?>%
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center">No team request data available</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- TOTAL ROW -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($teamCount > 0): ?>
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($totalMembers)); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($totalRequests)); ?></strong></td>
                    <td class="text-right">
                        <?php
                            $avgPercentage = $maxRequests > 0 ? ($totalRequests / ($maxRequests * $teamCount)) * 100 : 0;
                        ?>
                        <strong><?php echo e(number_format($avgPercentage, 1)); ?>%</strong> (avg)
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Fastest Depleting Items -->
    <div class="section">
        <div class="section-title">⚡ Fastest Depleting Items</div>
        <table>
            <thead>
                <tr>
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
                <tr class="<?php echo e($item->days_to_depletion <= 7 ? 'highlight' : ''); ?>">
                    <td><?php echo e($item->name); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->quantity)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->minimum_stock)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->requests_last_30_days)); ?></td>
                    <td class="text-right">
                        <span style="color: <?php echo e($item->depletion_rate > 50 ? '#dc3545' : ($item->depletion_rate > 0 ? '#ffc107' : '#28a745')); ?>">
                            <?php echo e(number_format($item->depletion_rate, 1)); ?>%
                        </span>
                    </td>
                    <td class="text-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->days_to_depletion == 999 || $item->days_to_depletion >= 999): ?>
                            ∞ days
                        <?php else: ?>
                            <span style="color: <?php echo e($item->days_to_depletion <= 7 ? '#dc3545' : ($item->days_to_depletion <= 14 ? '#ffc107' : '#28a745')); ?>">
                                <?php echo e($item->days_to_depletion); ?> days
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->quantity <= 0): ?>
                            <span class="badge badge-danger">Out of Stock</span>
                        <?php elseif($item->quantity <= $item->minimum_stock): ?>
                            <span class="badge badge-warning">Low Stock</span>
                        <?php elseif($item->days_to_depletion <= 7): ?>
                            <span class="badge badge-danger">Critical</span>
                        <?php elseif($item->days_to_depletion <= 14): ?>
                            <span class="badge badge-warning">Warning</span>
                        <?php else: ?>
                            <span class="badge badge-success">Normal</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center">No depletion data available</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- TOTAL/SUMMARY ROW for Fast Depleting Items -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fastDepletingItems->count() > 0): ?>
                <?php
                    $totalCurrentStock = $fastDepletingItems->sum('quantity');
                    $totalMinimumStock = $fastDepletingItems->sum('minimum_stock');
                    $totalRequestsLast30Days = $fastDepletingItems->sum('requests_last_30_days');
                    $avgDepletionRate = $fastDepletingItems->avg('depletion_rate');
                    $criticalItems = $fastDepletingItems->where('days_to_depletion', '<=', 7)->count();
                    $warningItems = $fastDepletingItems->whereBetween('days_to_depletion', [8, 14])->count();
                    $normalItems = $fastDepletingItems->where('days_to_depletion', '>', 14)->count();
                ?>
                <tr class="total-row">
                    <td><strong>SUMMARY</strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($totalCurrentStock)); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($totalMinimumStock)); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($totalRequestsLast30Days)); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($avgDepletionRate, 1)); ?>%</strong></td>
                    <td class="text-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalCurrentStock > 0): ?>
                            <strong>
                                <?php
                                    $totalDailyUsage = $totalRequestsLast30Days / 30;
                                    $avgDaysToDepletion = $totalDailyUsage > 0 ? round($totalCurrentStock / $totalDailyUsage) : 999;
                                ?>
                                <?php echo e($avgDaysToDepletion == 999 ? '∞ days' : $avgDaysToDepletion . ' days'); ?>

                            </strong>
                        <?php else: ?>
                            <strong>0 days</strong>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <small>
                            C:<?php echo e($criticalItems); ?> W:<?php echo e($warningItems); ?> N:<?php echo e($normalItems); ?>

                        </small>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Depletion Analysis -->
    <div class="section">
        <div class="section-title">📉 Depletion Analysis - Stock Level Predictions</div>
        <div class="summary-box">
            <h4>Stock Status Categories</h4>
            <table>
                <tr>
                    <td width="33%" class="text-center" style="background-color: #f8d7da; padding: 15px;">
                        <h3 style="margin: 0; color: #721c24;"><?php echo e($criticalItemsCount); ?></h3>
                        <p style="margin: 5px 0 0 0; color: #721c24;">Out of Stock</p>
                        <small>Immediate attention needed</small>
                    </td>
                    <td width="33%" class="text-center" style="background-color: #fff3cd; padding: 15px;">
                        <h3 style="margin: 0; color: #856404;"><?php echo e($warningItemsCount); ?></h3>
                        <p style="margin: 5px 0 0 0; color: #856404;">Low Stock</p>
                        <small>Below minimum levels</small>
                    </td>
                    <td width="33%" class="text-center" style="background-color: #d4edda; padding: 15px;">
                        <h3 style="margin: 0; color: #155724;"><?php echo e($safeItemsCount); ?></h3>
                        <p style="margin: 5px 0 0 0; color: #155724;">In Stock</p>
                        <small>Above minimum levels</small>
                    </td>
                </tr>
            </table>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($criticalItemsCount > 0): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-top: 15px; border-radius: 5px;">
                <strong>⚠️ Urgent Attention Needed:</strong> <?php echo e($criticalItemsCount); ?> items are out of stock and need immediate restocking.
            </div>
            <?php elseif($warningItemsCount > 0): ?>
            <div style="background-color: #fff3cd; color: #856404; padding: 10px; margin-top: 15px; border-radius: 5px;">
                <strong>⚠️ Low Stock Warning:</strong> <?php echo e($warningItemsCount); ?> items are below minimum stock levels.
            </div>
            <?php else: ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-top: 15px; border-radius: 5px;">
                <strong>✓ Stock Levels Good:</strong> All items are at or above minimum stock levels.
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Yearly Comparison -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($yearlyComparison)): ?>
    <div class="section">
        <div class="section-title">📅 Yearly Comparison Summary</div>
        <div class="summary-box">
            <table>
                <tr>
                    <td style="background-color: #f8f9fa; padding: 15px; text-align: center;">
                        <h4 style="margin: 0;"><?php echo e($yearlyComparison['previous_year']['year'] ?? ($selectedYear - 1)); ?></h4>
                        <h2 style="margin: 10px 0; color: #6c757d;"><?php echo e(number_format($yearlyComparison['previous_year']['total_requests'] ?? 0)); ?></h2>
                        <small>Total Requests</small>
                    </td>
                    <td style="background-color: #e9ecef; padding: 15px; text-align: center;">
                        <h4 style="margin: 0;">Comparison</h4>
                        <h2 style="margin: 10px 0; color: <?php echo e($yearlyComparison['comparison']['direction'] == 'up' ? '#28a745' : ($yearlyComparison['comparison']['direction'] == 'down' ? '#dc3545' : '#6c757d')); ?>;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($yearlyComparison['previous_year']['total_requests'] ?? 0) > 0): ?>
                                <?php echo e($yearlyComparison['comparison']['direction'] == 'up' ? '↑' : ($yearlyComparison['comparison']['direction'] == 'down' ? '↓' : '→')); ?>

                                <?php echo e(number_format($yearlyComparison['comparison']['change'] ?? 0, 1)); ?>%
                            <?php else: ?>
                                N/A
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </h2>
                        <small>Year-over-Year Change</small>
                    </td>
                    <td style="background-color: #f8f9fa; padding: 15px; text-align: center;">
                        <h4 style="margin: 0;"><?php echo e($selectedYear); ?> YTD</h4>
                        <h2 style="margin: 10px 0; color: #007bff;"><?php echo e(number_format($yearlyComparison['current_year']['total_requests'] ?? 0)); ?></h2>
                        <small>Year-to-Date</small>
                    </td>
                </tr>
            </table>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($yearlyComparison['current_year']['most_requested_item'] ?? false): ?>
            <p style="margin-top: 15px;">
                <strong>Most Requested Item in <?php echo e($selectedYear); ?>:</strong> 
                <?php echo e($yearlyComparison['current_year']['most_requested_item']['name']); ?> 
                (<?php echo e($yearlyComparison['current_year']['most_requested_item']['request_count']); ?> requests)
            </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Recommendations -->
    <div class="section">
        <div class="section-title">💡 Recommendations & Action Items</div>
        <div class="summary-box">
            <?php
                $recommendations = [];
                
                // Check for critical items
                if ($criticalItemsCount > 0) {
                    $recommendations[] = "Immediate restocking needed for {$criticalItemsCount} out-of-stock items";
                }
                
                // Check for low stock items
                if ($warningItemsCount > 0) {
                    $recommendations[] = "Schedule restocking for {$warningItemsCount} low-stock items";
                }
                
                // Check for fast depleting items
                $criticalDepletion = $fastDepletingItems->where('days_to_depletion', '<=', 7)->count();
                if ($criticalDepletion > 0) {
                    $recommendations[] = "Review procurement schedule for {$criticalDepletion} critically depleting items";
                }
                
                // Check for high demand items
                $highDemand = $topRequestedItems->where('total_requests', '>', 20)->count();
                if ($highDemand > 0) {
                    $recommendations[] = "Consider increasing stock levels for {$highDemand} high-demand items";
                }
                
                // If no issues
                if (empty($recommendations)) {
                    $recommendations[] = "Inventory levels are optimal. Continue current restocking schedule";
                }
            ?>
            
            <ol style="margin: 10px 0; padding-left: 20px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li style="margin-bottom: 8px;"><?php echo e($rec); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ol>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Report ID: <?php echo e($reportId ?? 'N/A'); ?></p>
        <p>Report Status: <?php echo e($isFinalized ? 'FINALIZED' : 'DRAFT'); ?></p>
        <p>Generated by: Land Bank Inventory System v1.0</p>
        <p>This is a system-generated report. For questions, contact the inventory department.</p>
        <p>Page <pdf:pagenumber> of <pdf:pagecount></p>
    </div>
</body>
</html><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/reports/pdf/monthly.blade.php ENDPATH**/ ?>