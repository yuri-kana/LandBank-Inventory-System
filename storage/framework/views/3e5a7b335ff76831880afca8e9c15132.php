<nav x-data="{ open: false, notificationOpen: false }" class="bg-gradient-to-r from-green-500 to-emerald-600 shadow-lg fixed top-0 left-0 right-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center hover:opacity-90 transition-opacity">
                        <div class="bg-yellow-400 text-white p-2 rounded-lg mr-3 shadow-md">
                            <i class="fas fa-boxes text-xl"></i>
                        </div>
                        <div>
                            <span class="font-bold text-white text-lg">Inventory System</span>
                            <div class="text-xs text-yellow-200 font-medium">Professional Management</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">
                    <a href="<?php echo e(route('dashboard')); ?>" 
                       class="inline-flex items-center px-1 pb-1 text-sm font-medium transition-all duration-200 relative group <?php echo e(request()->routeIs('dashboard') ? 'text-white' : 'text-green-100 hover:text-white'); ?>">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        <!-- Active Indicator -->
                        <div class="absolute bottom-0 left-0 right-0 h-0.5 <?php echo e(request()->routeIs('dashboard') ? 'bg-yellow-400' : 'bg-transparent group-hover:bg-yellow-300'); ?> transition-all duration-200"></div>
                    </a>
                    <!-- FIXED: Show correct route based on user role -->
                    <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index')); ?>" 
                    class="inline-flex items-center px-1 pb-1 text-sm font-medium transition-all duration-200 relative group <?php echo e((request()->routeIs('admin.items.*') || request()->routeIs('items.*')) ? 'text-white' : 'text-green-100 hover:text-white'); ?>">
                        <i class="fas fa-boxes mr-2"></i> Inventory
                        <!-- Active Indicator -->
                        <div class="absolute bottom-0 left-0 right-0 h-0.5 <?php echo e((request()->routeIs('admin.items.*') || request()->routeIs('items.*')) ? 'bg-yellow-400' : 'bg-transparent group-hover:bg-yellow-300'); ?> transition-all duration-200"></div>
                    </a>
                    <a href="<?php echo e(route('requests.index')); ?>" 
                       class="inline-flex items-center px-1 pb-1 text-sm font-medium transition-all duration-200 relative group <?php echo e(request()->routeIs('requests.*') ? 'text-white' : 'text-green-100 hover:text-white'); ?>">
                        <i class="fas fa-clipboard-list mr-2"></i> Requests
                        <!-- Active Indicator -->
                        <div class="absolute bottom-0 left-0 right-0 h-0.5 <?php echo e(request()->routeIs('requests.*') ? 'bg-yellow-400' : 'bg-transparent group-hover:bg-yellow-300'); ?> transition-all duration-200"></div>
                    </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdmin()): ?>
                        <!-- FIXED: Changed from teams.index to admin.teams.index -->
                        <a href="<?php echo e(route('admin.teams.index')); ?>" 
                           class="inline-flex items-center px-1 pb-1 text-sm font-medium transition-all duration-200 relative group <?php echo e(request()->routeIs('admin.teams.*') ? 'text-white' : 'text-green-100 hover:text-white'); ?>">
                            <i class="fas fa-users mr-2"></i> Teams
                            <!-- Active Indicator -->
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 <?php echo e(request()->routeIs('admin.teams.*') ? 'bg-yellow-400' : 'bg-transparent group-hover:bg-yellow-300'); ?> transition-all duration-200"></div>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">

                <!-- Notification Bell - Now clickable to go to notifications page -->
                <div class="mr-4 relative" id="notification-bell-container">
                    <a href="<?php echo e(route('notifications.index')); ?>" 
                       class="relative p-2 text-yellow-200 hover:text-white hover:bg-emerald-700 rounded-full transition-all duration-200 inline-flex items-center"
                       title="Go to Notifications"
                       @click="notificationOpen = false">
                        <i class="fas fa-bell text-xl"></i>

                        <?php
                            $unreadCount = auth()->user()->getUnreadNotificationsCount();
                            $unreadNotifications = auth()->user()->getUnreadNotifications();
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                            <span id="notification-badge-desktop" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                <?php echo e($unreadCount); ?>

                            </span>
                        <?php else: ?>
                            <span id="notification-badge-desktop" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse" style="display: none;"></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                </div>

                <!-- Settings Dropdown -->
                <div class="relative group">
                    <button class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-400 hover:to-yellow-300 transition-all duration-200 shadow-md focus:outline-none">
                        <div class="flex items-center">
                            <?php if(auth()->user()->profile_photo_path): ?>
                                <!-- Show profile photo if exists -->
                                <div class="mr-2 flex-shrink-0">
                                    <img src="<?php echo e(auth()->user()->profile_photo_url); ?>" 
                                         alt="<?php echo e(auth()->user()->name); ?>"
                                         class="h-8 w-8 rounded-full object-cover border-2 border-white shadow-sm">
                                </div>
                            <?php else: ?>
                                <!-- Fallback to user icon -->
                                <div class="bg-white text-emerald-600 p-2 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="text-left">
                                <div class="font-medium"><?php echo e(Auth::user()->name); ?></div>
                                <div class="text-xs text-yellow-100"><?php echo e(auth()->user()->isAdmin() ? 'Inventory Head' : 'Staff Member'); ?></div>
                            </div>
                            <div class="ml-2">
                                <svg class="fill-current h-4 w-4 text-yellow-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">   
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </button>

                    <!-- Dropdown Content -->
                    <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-green-50">
                            <div class="flex items-center">
                                <?php if(auth()->user()->profile_photo_path): ?>
                                    <div class="mr-3 flex-shrink-0">
                                        <img src="<?php echo e(auth()->user()->profile_photo_url); ?>" 
                                             alt="<?php echo e(auth()->user()->name); ?>"
                                             class="h-10 w-10 rounded-full object-cover border-2 border-emerald-200 shadow-sm">
                                    </div>
                                <?php else: ?>
                                    <div class="mr-3 bg-emerald-100 text-emerald-600 p-2 rounded-full shadow-sm">
                                        <i class="fas fa-user text-lg"></i>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div>
                                    <div class="font-medium text-emerald-800"><?php echo e(Auth::user()->name); ?></div>
                                    <div class="text-xs text-emerald-600 truncate"><?php echo e(Auth::user()->email); ?></div>
                                    <?php if(auth()->user()->team): ?>
                                        <div class="flex items-center mt-1 text-xs">
                                            <i class="fas fa-users text-yellow-500 mr-1"></i>
                                            <span class="text-emerald-700"><?php echo e(auth()->user()->team->name); ?></span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-gray-100">
                            <a href="<?php echo e(route('profile.settings')); ?>" class="block px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition duration-150">
                                <i class="fas fa-user-cog mr-2 text-emerald-500"></i> Profile Settings
                            </a>
                            <!-- REMOVED: Notifications link since bell is now clickable -->
                        </div>
                        <!-- FIXED: Logout form with improved error handling -->
                        <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form-desktop">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="_method" value="POST">
                            <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition duration-150 border-t border-gray-100">
                                <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-yellow-200 hover:text-white hover:bg-emerald-700 focus:outline-none transition duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gradient-to-r from-emerald-600 to-green-700 shadow-inner mt-16">
        <div class="pt-2 pb-3 space-y-1">
            <a href="<?php echo e(route('dashboard')); ?>" class="block pl-4 pr-4 py-3 text-base font-medium border-l-4 transition-all duration-200 <?php echo e(request()->routeIs('dashboard') ? 'border-yellow-400 text-white bg-emerald-700' : 'border-transparent text-green-100 hover:text-white hover:bg-emerald-700'); ?>">
                <i class="fas fa-tachometer-alt mr-3 text-yellow-300"></i> Dashboard
            </a>
            <!-- FIXED: Show correct route based on user role (Mobile) -->
            <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index')); ?>" class="block pl-4 pr-4 py-3 text-base font-medium border-l-4 transition-all duration-200 <?php echo e((request()->routeIs('admin.items.*') || request()->routeIs('items.*')) ? 'border-yellow-400 text-white bg-emerald-700' : 'border-transparent text-green-100 hover:text-white hover:bg-emerald-700'); ?>">
                <i class="fas fa-boxes mr-3 text-yellow-300"></i> Inventory
            </a>
            <a href="<?php echo e(route('requests.index')); ?>" class="block pl-4 pr-4 py-3 text-base font-medium border-l-4 transition-all duration-200 <?php echo e(request()->routeIs('requests.*') ? 'border-yellow-400 text-white bg-emerald-700' : 'border-transparent text-green-100 hover:text-white hover:bg-emerald-700'); ?>">
                <i class="fas fa-clipboard-list mr-3 text-yellow-300"></i> Requests
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdmin()): ?>
                <!-- FIXED: Changed from teams.index to admin.teams.index -->
                <a href="<?php echo e(route('admin.teams.index')); ?>" class="block pl-4 pr-4 py-3 text-base font-medium border-l-4 transition-all duration-200 <?php echo e(request()->routeIs('admin.teams.*') ? 'border-yellow-400 text-white bg-emerald-700' : 'border-transparent text-green-100 hover:text-white hover:bg-emerald-700'); ?>">
                    <i class="fas fa-users mr-3 text-yellow-300"></i> Teams
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <!-- Profile Link in Mobile Menu -->
            <div class="border-t border-emerald-500 pt-4">
                <a href="<?php echo e(route('profile.settings')); ?>" class="block pl-4 pr-4 py-3 text-base font-medium border-l-4 border-l-yellow-400 text-green-100 hover:text-white hover:bg-emerald-700">
                    <i class="fas fa-user-cog mr-3 text-yellow-300"></i> Profile Settings
                </a>
                
                <!-- Notifications link in mobile menu -->
                <a href="<?php echo e(route('notifications.index')); ?>" 
                   class="block pl-4 pr-4 py-3 text-base font-medium border-l-4 border-l-yellow-400 text-green-100 hover:text-white hover:bg-emerald-700 relative">
                    <i class="fas fa-bell mr-3 text-yellow-300"></i> Notifications
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                        <span id="notification-badge-mobile" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            <?php echo e($unreadCount); ?>

                        </span>
                    <?php else: ?>
                        <span id="notification-badge-mobile" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;"></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-emerald-500">
            <div class="px-4">
                <div class="flex items-center">
                    <?php if(auth()->user()->profile_photo_path): ?>
                        <div class="mr-3 flex-shrink-0">
                            <img src="<?php echo e(auth()->user()->profile_photo_url); ?>" 
                                 alt="<?php echo e(auth()->user()->name); ?>"
                                 class="h-10 w-10 rounded-full object-cover border-2 border-yellow-300 shadow-sm">
                        </div>
                    <?php else: ?>
                        <div class="mr-3 bg-yellow-400 text-white p-2 rounded-full shadow-sm">
                            <i class="fas fa-user text-lg"></i>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div>
                        <div class="font-medium text-base text-white"><?php echo e(Auth::user()->name); ?></div>
                        <div class="font-medium text-sm text-green-100"><?php echo e(Auth::user()->email); ?></div>
                        <?php if(auth()->user()->team): ?>
                            <div class="flex items-center mt-1 text-sm">
                                <i class="fas fa-users text-yellow-300 mr-1"></i>
                                <span class="text-green-100"><?php echo e(auth()->user()->team->name); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-sm">
                        <?php echo e(auth()->user()->isAdmin() ? 'Inventory Head' : 'Staff Member'); ?>

                    </span>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- FIXED: Logout form for mobile with improved error handling -->
                <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form-mobile">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" value="POST">
                    <button type="submit" class="block w-full text-left pl-4 pr-4 py-3 text-base font-medium text-green-100 hover:text-white hover:bg-emerald-700 border-t border-emerald-500 transition-all duration-200">
                        <i class="fas fa-sign-out-alt mr-3 text-yellow-300"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Add this script at the end of your navigation.blade.php -->
<script>
// Function to update notification badges
function updateNotificationBadge() {
    fetch('<?php echo e(route("notifications.count")); ?>')
        .then(response => response.json())
        .then(data => {
            const count = data.count || 0;
            
            // Update desktop badge
            const desktopBadge = document.getElementById('notification-badge-desktop');
            if (desktopBadge) {
                if (count > 0) {
                    desktopBadge.textContent = count;
                    desktopBadge.style.display = 'flex';
                } else {
                    desktopBadge.style.display = 'none';
                }
            }
            
            // Update mobile badge
            const mobileBadge = document.getElementById('notification-badge-mobile');
            if (mobileBadge) {
                if (count > 0) {
                    mobileBadge.textContent = count;
                    mobileBadge.style.display = 'flex';
                } else {
                    mobileBadge.style.display = 'none';
                }
            }
            
            console.log('Updated notification badge count:', count);
        })
        .catch(error => {
            console.error('Error updating notification badge:', error);
        });
}

// Listen for messages from child pages
window.addEventListener('message', function(event) {
    if (event.data.type === 'notificationUpdated') {
        updateNotificationBadge();
    }
});

// Initial update
document.addEventListener('DOMContentLoaded', function() {
    updateNotificationBadge();
});
</script><?php /**PATH C:\Users\Jhon Rhey\Downloads\inventory-system\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>