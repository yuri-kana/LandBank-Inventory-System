<?php $__env->startSection('title', 'Notification - Inventory System'); ?>

<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-black leading-tight">
            <?php echo e(__('Notifications')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('title', null, []); ?> Notifications <?php $__env->endSlot(); ?>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                            <p class="text-sm text-gray-600 mt-1">Manage your notifications here</p>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                            <button id="mark-all-read-page" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition duration-200 shadow-md">
                                <i class="fas fa-check-circle mr-2"></i> Mark All as Read
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <!-- Notification Summary -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-800">Summary</h3>
                                <p class="text-sm text-gray-600">
                                    <?php echo e($notifications->total()); ?> total notification(s)
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                                        • <span class="text-blue-600 font-medium"><?php echo e($unreadCount); ?> unread</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            </div>
                            <div class="text-sm text-gray-500">
                                Last updated: <?php echo e(now()->format('M d, Y h:i A')); ?>

                            </div>
                        </div>
                    </div>
                    
                    <!-- Grouped Notifications -->
                    <div class="space-y-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $groupedNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teamName => $teamNotifications): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="team-group border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <!-- Team Header -->
                                <div class="bg-gradient-to-r from-blue-700 to-blue-800 text-white px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                                                <i class="fas fa-users text-white"></i>
                                            </div>
                                            <div>
                                                <!-- Display team with number -->
                                                <h3 class="font-bold text-lg">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_contains($teamName, 'Team') || is_numeric($teamName)): ?>
                                                        <?php echo e($teamName); ?>

                                                    <?php else: ?>
                                                        <?php echo e($teamName); ?> (Team)
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </h3>
                                                <?php
                                                    $teamUnreadCount = $teamNotifications->where('is_read', false)->count();
                                                    $teamTotalCount = $teamNotifications->count();
                                                    $teamResolvedCount = $teamNotifications->where('data.is_resolved', true)->count();
                                                ?>
                                                <div class="flex items-center space-x-4 text-sm text-blue-200">
                                                    <span><?php echo e($teamTotalCount); ?> notification(s)</span>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($teamUnreadCount > 0): ?>
                                                        <span class="px-2 py-0.5 bg-yellow-500 text-white rounded-full text-xs">
                                                            <?php echo e($teamUnreadCount); ?> unread
                                                        </span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($teamResolvedCount > 0): ?>
                                                        <span class="px-2 py-0.5 bg-green-500 text-white rounded-full text-xs">
                                                            <?php echo e($teamResolvedCount); ?> resolved
                                                        </span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($teamUnreadCount > 0): ?>
                                            <button class="mark-team-read px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition duration-150"
                                                    data-team="<?php echo e($teamName); ?>">
                                                <i class="fas fa-check mr-1.5"></i> Mark Team as Read
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Team Notifications -->
                                <div class="divide-y divide-gray-100">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teamNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $data = $notification->data;
                                            $isResolved = $data['is_resolved'] ?? false;
                                            $statusIcon = $data['status_icon'] ?? '';
                                            $statusText = $data['status_text'] ?? ($data['status'] ?? '');
                                            $processedBy = $data['processed_by'] ?? null;
                                            $resolvedAt = $data['resolved_at'] ?? null;
                                            $teamDisplay = $data['team_display'] ?? $teamName;
                                            $requestedBy = $data['requested_by'] ?? ($data['user_name'] ?? 'Team Member');
                                        ?>
                                        
                                        <div class="notification-item <?php echo e($notification->is_read ? 'bg-white' : 'bg-blue-50'); ?> hover:bg-gray-50 transition duration-150">
                                            <div class="p-5">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <div class="flex items-start">
                                                            <!-- Status Icon -->
                                                            <div class="mr-3 mt-1">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notification->type === 'new_request'): ?>
                                                                    <div class="h-10 w-10 rounded-full <?php echo e($isResolved ? 'bg-green-100' : 'bg-blue-100'); ?> flex items-center justify-center">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isResolved): ?>
                                                                            <i class="fas fa-check text-green-600 text-lg"></i>
                                                                        <?php else: ?>
                                                                            <i class="fas fa-plus text-blue-600 text-lg"></i>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </div>
                                                                <?php elseif($notification->type === 'request_status'): ?>
                                                                    <div class="h-10 w-10 rounded-full 
                                                                        <?php if($data['status'] === 'approved'): ?> bg-emerald-100
                                                                        <?php elseif($data['status'] === 'rejected'): ?> bg-red-100
                                                                        <?php elseif($data['status'] === 'claimed'): ?> bg-blue-100
                                                                        <?php else: ?> bg-gray-100 <?php endif; ?> flex items-center justify-center">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['status'] === 'approved'): ?>
                                                                            <i class="fas fa-check text-emerald-600 text-lg"></i>
                                                                        <?php elseif($data['status'] === 'rejected'): ?>
                                                                            <i class="fas fa-times text-red-600 text-lg"></i>
                                                                        <?php elseif($data['status'] === 'claimed'): ?>
                                                                            <i class="fas fa-box text-blue-600 text-lg"></i>
                                                                        <?php else: ?>
                                                                            <i class="fas fa-clock text-gray-600 text-lg"></i>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                                        <i class="fas fa-bell text-gray-600 text-lg"></i>
                                                                    </div>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <h3 class="font-semibold text-gray-900 text-lg"><?php echo e($notification->title); ?></h3>
                                                                        
                                                                        <!-- Team & Requester Information -->
                                                                        <div class="flex items-center mt-1 space-x-4 text-sm text-gray-600">
                                                                            <div class="flex items-center">
                                                                                <i class="fas fa-users mr-1.5 text-gray-400"></i>
                                                                                <span class="font-medium"><?php echo e($teamDisplay); ?></span>
                                                                            </div>
                                                                            <div class="flex items-center">
                                                                                <i class="fas fa-user mr-1.5 text-gray-400"></i>
                                                                                <span>Requested by: <strong><?php echo e($requestedBy); ?></strong></span>
                                                                            </div>
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['item_name'] ?? false): ?>
                                                                            <div class="flex items-center">
                                                                                <i class="fas fa-box mr-1.5 text-gray-400"></i>
                                                                                <span><?php echo e($data['quantity'] ?? 0); ?> × <?php echo e($data['item_name']); ?></span>
                                                                            </div>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <span class="text-sm text-gray-500 block">
                                                                            <?php echo e($notification->created_at->format('M d, Y')); ?>

                                                                        </span>
                                                                        <span class="text-xs text-gray-400 block">
                                                                            <?php echo e($notification->created_at->format('h:i A')); ?>

                                                                        </span>
                                                                        <!-- Status indicator -->
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notification->is_read): ?>
                                                                            <span class="inline-block mt-1 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Read</span>
                                                                        <?php else: ?>
                                                                            <span class="inline-block mt-1 px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded">Unread</span>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Notification Message -->
                                                                <p class="text-gray-700 mt-3 text-sm leading-relaxed"><?php echo e($notification->message); ?></p>
                                                                
                                                                <!-- RESOLVED STATUS SECTION -->
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isResolved): ?>
                                                                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                                        <div class="flex items-center justify-between">
                                                                            <div class="flex items-center">
                                                                                <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center mr-2">
                                                                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                                                                </div>
                                                                                <span class="font-medium text-green-800">Request Completed</span>
                                                                            </div>
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusIcon && $statusText): ?>
                                                                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                                                    <?php echo e($statusIcon); ?> <?php echo e($statusText); ?>

                                                                                </span>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        </div>
                                                                        <div class="mt-2 text-sm text-green-700">
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($processedBy): ?>
                                                                                <p><i class="fas fa-user-check mr-2"></i> Processed by: <?php echo e($processedBy); ?></p>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($resolvedAt): ?>
                                                                                <p><i class="fas fa-calendar-check mr-2"></i> Completed: <?php echo e($resolvedAt); ?></p>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                
                                                                <!-- REQUESTED ITEMS SECTION -->
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($notification->request_items) && count($notification->request_items) > 0): ?>
                                                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                                        <div class="flex items-center justify-between mb-2">
                                                                            <p class="text-sm font-semibold text-gray-800 flex items-center">
                                                                                <i class="fas fa-boxes mr-2 text-gray-600"></i> Requested Items:
                                                                            </p>
                                                                            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded">
                                                                                <?php echo e(count($notification->request_items)); ?> item(s)
                                                                            </span>
                                                                        </div>
                                                                        <div class="space-y-2">
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $notification->request_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($index < 5): ?>
                                                                                    <div class="flex items-center text-sm">
                                                                                        <div class="w-2 h-2 <?php echo e($isResolved ? 'bg-green-500' : 'bg-blue-500'); ?> rounded-full mr-3"></div>
                                                                                        <span class="text-gray-700"><?php echo e($item); ?></span>
                                                                                    </div>
                                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($notification->request_items) > 5): ?>
                                                                                <div class="pt-2 border-t border-gray-200">
                                                                                    <p class="text-xs text-gray-500 text-center">
                                                                                        <i class="fas fa-ellipsis-h mr-1"></i>
                                                                                        +<?php echo e(count($notification->request_items) - 5); ?> more items
                                                                                    </p>
                                                                                </div>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                
                                                                <!-- STATUS & TYPE BADGES -->
                                                                <div class="mt-4 flex items-center flex-wrap gap-2">
                                                                    <!-- STATUS BADGE -->
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data['status'])): ?>
                                                                        <?php
                                                                            $statusColors = [
                                                                                'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
                                                                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200'],
                                                                                'claimed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200'],
                                                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200'],
                                                                            ];
                                                                            $color = $statusColors[$data['status']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200'];
                                                                        ?>
                                                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full <?php echo e($color['bg']); ?> <?php echo e($color['text']); ?> border <?php echo e($color['border']); ?>">
                                                                            <i class="fas fa-flag mr-1.5"></i> <?php echo e(ucfirst($data['status'])); ?>

                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusIcon && !$isResolved): ?>
                                                                                <span class="ml-1"><?php echo e($statusIcon); ?></span>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        </span>
                                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    
                                                                    <!-- RESOLVED BADGE -->
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isResolved): ?>
                                                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                                                            <i class="fas fa-check-circle mr-1.5"></i> Completed
                                                                        </span>
                                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    
                                                                    <!-- NEW BADGE -->
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->is_read): ?>
                                                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-full border border-blue-200">
                                                                            <i class="fas fa-star mr-1.5"></i> NEW
                                                                        </span>
                                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- ACTION BUTTONS -->
                                                        <div class="mt-4 flex justify-between items-center">
                                                            <!-- VIEW DETAILS LINK -->
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notification->url && $notification->url !== '#'): ?>
                                                                <a href="<?php echo e($notification->url); ?>" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-150 font-medium text-sm">
                                                                    <i class="fas fa-external-link-alt mr-2"></i> View Request Details
                                                                </a>
                                                            <?php else: ?>
                                                                <div></div> <!-- Empty div for spacing -->
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            
                                                            <!-- MARK READ BUTTON -->
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->is_read): ?>
                                                                <button class="mark-read-single px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 shadow-sm flex items-center" 
                                                                        data-id="<?php echo e($notification->id); ?>"
                                                                        data-team="<?php echo e($teamName); ?>">
                                                                    <i class="fas fa-check mr-2"></i> Mark as Read
                                                                </button>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-12">
                                <div class="mb-4">
                                    <i class="fas fa-bell-slash text-gray-300 text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications yet</h3>
                                <p class="text-gray-500">You'll see notifications here when you have them.</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notifications->hasPages()): ?>
                        <div class="mt-6">
                            <?php echo e($notifications->links()); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark single notification as read
        document.querySelectorAll('.mark-read-single').forEach(btn => {
            btn.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                const teamName = this.getAttribute('data-team');
                const notificationItem = this.closest('.notification-item');
                
                console.log('Marking notification as read:', notificationId, 'Team:', teamName);
                
                // Add loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Marking...';
                this.disabled = true;
                
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Mark as read response:', data);
                    
                    if (data.success) {
                        // Update classes on the notification item
                        if (notificationItem) {
                            notificationItem.classList.remove('bg-blue-50');
                            notificationItem.classList.add('bg-white');
                            
                            // Update status indicator
                            const statusIndicator = notificationItem.querySelector('.bg-blue-100');
                            if (statusIndicator) {
                                statusIndicator.classList.remove('bg-blue-100', 'text-blue-600');
                                statusIndicator.classList.add('bg-gray-100', 'text-gray-600');
                                statusIndicator.textContent = 'Read';
                            }
                        }
                        
                        // Remove NEW badge
                        const newBadge = notificationItem ? notificationItem.querySelector('.text-blue-700') : null;
                        if (newBadge && newBadge.textContent.includes('NEW')) {
                            newBadge.remove();
                        }
                        
                        // Remove mark read button
                        this.remove();
                        
                        // Update notification badge in navbar
                        updateNotificationBadge();
                        
                        // Update team unread count
                        updateTeamUnreadCount(teamName);
                        
                        // Show success message
                        showToast('Notification marked as read', 'success');
                    } else {
                        console.error('Failed to mark notification as read:', data.message);
                        
                        // Restore button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                        
                        // Show error message
                        showToast(data.message || 'Failed to mark notification as read', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    
                    // Restore button state
                    this.innerHTML = originalText;
                    this.disabled = false;
                    
                    // Show error message
                    showToast('Error marking notification as read. Please try again.', 'error');
                });
            });
        });
        
        // Mark all notifications in a team as read
        document.querySelectorAll('.mark-team-read').forEach(btn => {
            btn.addEventListener('click', function() {
                const teamName = this.getAttribute('data-team');
                const teamGroup = this.closest('.team-group');
                
                console.log('Marking all notifications as read for team:', teamName);
                
                // Get all unread notification IDs for this team
                const notificationIds = [];
                teamGroup.querySelectorAll('.notification-item:not(.bg-white) .mark-read-single').forEach(notificationBtn => {
                    notificationIds.push(notificationBtn.getAttribute('data-id'));
                });
                
                if (notificationIds.length === 0) {
                    showToast('No unread notifications for this team', 'info');
                    return;
                }
                
                // Add loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                this.disabled = true;
                
                // Mark each notification as read
                const promises = notificationIds.map(id => {
                    return fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    }).then(response => response.json());
                });
                
                Promise.all(promises)
                    .then(results => {
                        const successful = results.filter(r => r.success).length;
                        
                        if (successful > 0) {
                            // Update all unread notifications in this team
                            teamGroup.querySelectorAll('.notification-item:not(.bg-white)').forEach(item => {
                                item.classList.remove('bg-blue-50');
                                item.classList.add('bg-white');
                                
                                // Update status indicator
                                const statusIndicator = item.querySelector('.bg-blue-100');
                                if (statusIndicator) {
                                    statusIndicator.classList.remove('bg-blue-100', 'text-blue-600');
                                    statusIndicator.classList.add('bg-gray-100', 'text-gray-600');
                                    statusIndicator.textContent = 'Read';
                                }
                                
                                // Remove NEW badges
                                const newBadge = item.querySelector('.text-blue-700');
                                if (newBadge && newBadge.textContent.includes('NEW')) {
                                    newBadge.remove();
                                }
                            });
                            
                            // Remove all mark read buttons in this team
                            teamGroup.querySelectorAll('.mark-read-single').forEach(btn => {
                                btn.remove();
                            });
                            
                            // Hide team mark read button if no unread notifications left
                            const remainingUnread = teamGroup.querySelectorAll('.notification-item:not(.bg-white)').length;
                            if (remainingUnread === 0) {
                                this.style.display = 'none';
                            }
                            
                            // Update team unread count
                            updateTeamUnreadCount(teamName);
                            
                            // Update notification badge in navbar
                            updateNotificationBadge();
                            
                            // Show success message
                            showToast(`Marked ${successful} notification(s) as read for ${teamName}`, 'success');
                        }
                        
                        // Restore button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error marking team notifications as read:', error);
                        
                        // Restore button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                        
                        // Show error message
                        showToast('Error marking team notifications as read', 'error');
                    });
            });
        });
        
        // Mark all as read on page
        const markAllBtn = document.getElementById('mark-all-read-page');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function() {
                console.log('Marking all notifications as read');
                
                // Add loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Marking All...';
                this.disabled = true;
                
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Mark all as read response:', data);
                    
                    if (data.success) {
                        // Update all unread notifications on page
                        document.querySelectorAll('.notification-item:not(.bg-white)').forEach(item => {
                            // Change from blue to white background
                            item.classList.remove('bg-blue-50');
                            item.classList.add('bg-white');
                            
                            // Update status indicator
                            const statusIndicator = item.querySelector('.bg-blue-100');
                            if (statusIndicator) {
                                statusIndicator.classList.remove('bg-blue-100', 'text-blue-600');
                                statusIndicator.classList.add('bg-gray-100', 'text-gray-600');
                                statusIndicator.textContent = 'Read';
                            }
                        });
                        
                        // Remove all mark read buttons
                        document.querySelectorAll('.mark-read-single').forEach(btn => {
                            btn.remove();
                        });
                        
                        // Remove all team mark read buttons
                        document.querySelectorAll('.mark-team-read').forEach(btn => {
                            btn.style.display = 'none';
                        });
                        
                        // Remove NEW badges
                        document.querySelectorAll('.text-blue-700').forEach(badge => {
                            if (badge.textContent.includes('NEW')) {
                                badge.closest('span').remove();
                            }
                        });
                        
                        // Hide mark all button
                        this.style.display = 'none';
                        
                        // Update all team unread counts
                        updateAllTeamUnreadCounts();
                        
                        // Update notification badge in navbar
                        updateNotificationBadge();
                        
                        // Show success message
                        showToast('All notifications marked as read', 'success');
                    } else {
                        console.error('Failed to mark all notifications as read:', data.message);
                        
                        // Restore button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                        
                        // Show error message
                        showToast(data.message || 'Failed to mark all notifications as read', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                    
                    // Restore button state
                    this.innerHTML = originalText;
                    this.disabled = false;
                    
                    // Show error message
                    showToast('Error marking all notifications as read. Please try again.', 'error');
                });
            });
        }
        
        function updateTeamUnreadCount(teamName) {
            // Find the team group
            const teamGroups = document.querySelectorAll('.team-group');
            teamGroups.forEach(group => {
                const teamHeader = group.querySelector('h3');
                if (teamHeader && teamHeader.textContent.trim().includes(teamName)) {
                    const unreadCount = group.querySelectorAll('.notification-item:not(.bg-white)').length;
                    const totalCount = group.querySelectorAll('.notification-item').length;
                    const resolvedCount = group.querySelectorAll('.bg-green-50').length;
                    
                    // Update team count in header
                    const countContainer = group.querySelector('.text-sm.text-blue-200');
                    if (countContainer) {
                        let countHtml = `${totalCount} notification(s)`;
                        
                        if (unreadCount > 0) {
                            countHtml += ` <span class="px-2 py-0.5 bg-yellow-500 text-white rounded-full text-xs">${unreadCount} unread</span>`;
                        }
                        
                        if (resolvedCount > 0) {
                            countHtml += ` <span class="px-2 py-0.5 bg-green-500 text-white rounded-full text-xs">${resolvedCount} resolved</span>`;
                        }
                        
                        countContainer.innerHTML = countHtml;
                    }
                    
                    // Hide team mark read button if no unread notifications
                    const teamMarkReadBtn = group.querySelector('.mark-team-read');
                    if (teamMarkReadBtn && unreadCount === 0) {
                        teamMarkReadBtn.style.display = 'none';
                    }
                }
            });
        }
        
        function updateAllTeamUnreadCounts() {
            document.querySelectorAll('.team-group').forEach(group => {
                const unreadCount = group.querySelectorAll('.notification-item:not(.bg-white)').length;
                const totalCount = group.querySelectorAll('.notification-item').length;
                const resolvedCount = group.querySelectorAll('.bg-green-50').length;
                
                // Update team count in header
                const countContainer = group.querySelector('.text-sm.text-blue-200');
                if (countContainer) {
                    let countHtml = `${totalCount} notification(s)`;
                    
                    if (unreadCount > 0) {
                        countHtml += ` <span class="px-2 py-0.5 bg-yellow-500 text-white rounded-full text-xs">${unreadCount} unread</span>`;
                    }
                    
                    if (resolvedCount > 0) {
                        countHtml += ` <span class="px-2 py-0.5 bg-green-500 text-white rounded-full text-xs">${resolvedCount} resolved</span>`;
                    }
                    
                    countContainer.innerHTML = countHtml;
                }
                
                // Hide team mark read button if no unread notifications
                const teamMarkReadBtn = group.querySelector('.mark-team-read');
                if (teamMarkReadBtn && unreadCount === 0) {
                    teamMarkReadBtn.style.display = 'none';
                }
            });
        }
        
        function updateNotificationBadge() {
            fetch('/notifications/count')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Unread count after update:', data.count);
                    
                    // Update badges on this page
                    const badges = document.querySelectorAll('.bg-red-500');
                    badges.forEach(badge => {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    });
                    
                    // Force a small delay and update navbar
                    setTimeout(() => {
                        // Try to update parent window if in iframe
                        if (window.parent && window.parent !== window) {
                            window.parent.postMessage({ type: 'notificationUpdated' }, '*');
                        }
                        
                        // Also update the current window's navbar badges
                        const desktopBadge = document.getElementById('notification-badge-desktop');
                        const mobileBadge = document.getElementById('notification-badge-mobile');
                        
                        if (desktopBadge) {
                            if (data.count > 0) {
                                desktopBadge.textContent = data.count;
                                desktopBadge.style.display = 'flex';
                            } else {
                                desktopBadge.style.display = 'none';
                            }
                        }
                        
                        if (mobileBadge) {
                            if (data.count > 0) {
                                mobileBadge.textContent = data.count;
                                mobileBadge.style.display = 'flex';
                            } else {
                                mobileBadge.style.display = 'none';
                            }
                        }
                    }, 100);
                })
                .catch(error => {
                    console.error('Error updating notification badge:', error);
                });
        }
        
        // Toast notification function
        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.getElementById('notification-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast element
            const toast = document.createElement('div');
            toast.id = 'notification-toast';
            toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg border transform transition-all duration-300 translate-y-0 opacity-100 ${
                type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' :
                type === 'error' ? 'bg-red-50 text-red-800 border-red-200' :
                'bg-blue-50 text-blue-800 border-blue-200'
            }`;
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${
                        type === 'success' ? 'fa-check-circle' :
                        type === 'error' ? 'fa-exclamation-circle' :
                        'fa-info-circle'
                    } mr-3"></i>
                    <span>${message}</span>
                    <button class="ml-4 text-gray-500 hover:text-gray-700" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.transform = 'translateY(-20px)';
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
    });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views\notifications\index.blade.php ENDPATH**/ ?>