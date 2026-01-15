
<?php $__env->startSection('title', 'Profile Settings - Inventory System'); ?>

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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Profile Settings')); ?>

            </h2>
            <a href="<?php echo e(route('dashboard')); ?>" class="text-sm text-emerald-600 hover:text-emerald-800">
                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 fade-in-message">
                    <i class="fas fa-check-circle mr-2"></i> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 fade-in-message">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 fade-in-message">
                    <div class="font-medium">Please fix the following errors:</div>
                    <ul class="list-disc list-inside mt-2 text-sm">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Profile Overview -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-6">
                            <div class="text-center">
                                <!-- Profile Photo Container -->
                                <div class="relative mx-auto w-32 h-32 mb-4">
                                    <div class="w-full h-full rounded-full bg-white border-4 border-white shadow-lg overflow-hidden relative group">
                                        <?php
                                            $hasPhoto = auth()->user()->profile_photo_path && Storage::disk('public')->exists(auth()->user()->profile_photo_path);
                                            $photoUrl = $hasPhoto ? Storage::url(auth()->user()->profile_photo_path) : null;
                                        ?>
                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasPhoto): ?>
                                            <img src="<?php echo e($photoUrl); ?>" 
                                                 alt="<?php echo e(auth()->user()->name); ?>" 
                                                 class="w-full h-full object-cover"
                                                 id="profile-photo-preview"
                                                 onerror="this.style.display='none'; document.getElementById('default-profile-icon').classList.remove('hidden');">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        
                                        <div id="default-profile-icon" class="w-full h-full <?php echo e($hasPhoto ? 'hidden' : 'flex'); ?> bg-emerald-100 items-center justify-center">
                                            <i class="fas fa-user text-4xl text-emerald-600"></i>
                                        </div>
                                        
                                        <!-- Hover Overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-full">
                                            <span class="text-white text-sm font-medium">Change Photo</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Photo Upload Button -->
                                    <button type="button" 
                                            onclick="document.getElementById('profile-photo-input').click()"
                                            class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition duration-200 shadow-md focus:outline-none"
                                            title="Change Photo"
                                            id="camera-button">
                                        <i class="fas fa-camera text-sm"></i>
                                    </button>
                                </div>
                                
                                <h3 class="text-xl font-semibold text-white"><?php echo e(auth()->user()->name); ?></h3>
                                <p class="text-emerald-100 text-sm mt-1"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm text-gray-500">Role</span>
                                    <div class="flex items-center mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-user-tag mr-1"></i>
                                            <?php echo e(auth()->user()->isAdmin() ? 'Inventory Head' : 'Staff Member'); ?>

                                        </span>
                                    </div>
                                </div>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->team): ?>
                                <div>
                                    <span class="text-sm text-gray-500">Team</span>
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-users text-emerald-500 mr-2"></i>
                                        <span class="text-gray-900"><?php echo e(auth()->user()->team->name); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <div>
                                    <span class="text-sm text-gray-500">Member Since</span>
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                        <span class="text-gray-900"><?php echo e(auth()->user()->created_at->format('F d, Y')); ?></span>
                                    </div>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-500">Email Status</span>
                                    <div class="flex items-center mt-1">
                                        <?php if(auth()->user()->hasVerifiedEmail()): ?>
                                            <span class="inline-flex items-center text-green-600">
                                                <i class="fas fa-check-circle mr-2"></i> Verified
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center text-yellow-600">
                                                <i class="fas fa-envelope mr-2"></i> Unverified
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Settings Forms -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Update Profile Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-user-edit mr-2 text-emerald-600"></i>
                                Update Profile Information
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="space-y-6" id="profile-form">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Full Name 
                                        </label>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               value="<?php echo e(old('name', auth()->user()->name)); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="<?php echo e(old('email', auth()->user()->email)); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="pt-4">
                                    <button type="submit" 
                                            class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-200 font-medium"
                                            id="save-profile-btn">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Profile Changes
                                    </button>
                                    <span class="text-xs text-gray-500 ml-4">* Required fields</span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-key mr-2 text-blue-600"></i>
                                Change Password
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="<?php echo e(route('profile.password.update')); ?>" class="space-y-6" id="password-form">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                
                                <!-- Current Password -->
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-key mr-2 text-gray-500"></i> Current Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="current_password" 
                                               name="current_password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-10"
                                               required 
                                               autocomplete="current-password"
                                               placeholder="Enter your current password">
                                        <button type="button" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                                id="toggleCurrentPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-key mr-2 text-gray-500"></i> New Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="new_password" 
                                               name="new_password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-10"
                                               required 
                                               autocomplete="new-password"
                                               minlength="8"
                                               placeholder="Enter new password">
                                        <button type="button" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                                id="toggleNewPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <span class="absolute right-10 top-1/2 transform -translate-y-1/2 opacity-0 transition-opacity duration-300"
                                              id="passwordValidationIcon">
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        </span>
                                    </div>
                                    
                                    <!-- Password requirements -->
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-xs font-medium text-gray-700 mb-2">Password must contain:</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                                            <div class="flex items-center" id="reqLength">
                                                <span class="requirement-icon mr-2 text-gray-400">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                                <span class="requirement-text">At least 8 characters</span>
                                            </div>
                                            <div class="flex items-center" id="reqUppercase">
                                                <span class="requirement-icon mr-2 text-gray-400">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                                <span class="requirement-text">At least one uppercase letter</span>
                                            </div>
                                            <div class="flex items-center" id="reqLowercase">
                                                <span class="requirement-icon mr-2 text-gray-400">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                                <span class="requirement-text">At least one lowercase letter</span>
                                            </div>
                                            <div class="flex items-center" id="reqNumber">
                                                <span class="requirement-icon mr-2 text-gray-400">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                                <span class="requirement-text">At least one number</span>
                                            </div>
                                            <div class="flex items-center" id="reqSpecial">
                                                <span class="requirement-icon mr-2 text-gray-400">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                                <span class="requirement-text">At least one special character (@$!%*#?&)</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Password strength -->
                                    <div class="mt-2">
                                        <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full w-0 transition-all duration-300" id="passwordStrengthBar"></div>
                                        </div>
                                        <div class="text-xs mt-1 text-right" id="passwordStrengthText"></div>
                                    </div>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- Confirm New Password -->
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-key mr-2 text-gray-500"></i> Confirm New Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="new_password_confirmation" 
                                               name="new_password_confirmation" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-10"
                                               required 
                                               autocomplete="new-password"
                                               minlength="8"
                                               placeholder="Confirm your password">
                                        <button type="button" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                                id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <span class="absolute right-10 top-1/2 transform -translate-y-1/2 opacity-0 transition-opacity duration-300"
                                              id="confirmValidationIcon">
                                            <i class="fas fa-check-circle text-green-500"></i>
                                            <i class="fas fa-times-circle text-red-500 hidden"></i>
                                        </span>
                                    </div>
                                    
                                    <!-- Password match status -->
                                    <div class="mt-2 text-sm flex items-center opacity-0 transition-opacity duration-300"
                                         id="passwordMatchStatus">
                                        <span id="matchIcon" class="mr-2"></span>
                                        <span id="matchText"></span>
                                    </div>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="pt-4">
                                    <button type="submit" 
                                            class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                            id="update-password-btn"
                                            disabled>
                                        <span id="btnText" class="flex items-center justify-center">
                                            <i class="fas fa-key mr-2"></i> Update Password
                                        </span>
                                        <span id="btnSpinner" class="hidden">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> Updating...
                                        </span>
                                        <span id="btnSuccess" class="hidden">
                                            <i class="fas fa-check-circle mr-2"></i> Password Updated!
                                        </span>
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2" id="password-validation-summary">
                                        Fill all fields correctly to enable the update button
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for profile photo upload -->
    <form id="profile-photo-form" method="POST" action="<?php echo e(route('profile.photo.update')); ?>" enctype="multipart/form-data" class="hidden">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="file" 
               id="profile-photo-input" 
               name="profile_photo"
               accept="image/*">
    </form>

    <style>
        /* Additional custom styles */
        .fade-in-message {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-5px); }
            60% { transform: translateY(-3px); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .strength-weak { background-color: #dc3545; }
        .strength-fair { background-color: #ffc107; }
        .strength-good { background-color: #28a745; }
        .strength-strong { background-color: #1a5632; }

        .strength-weak-text { color: #dc3545; }
        .strength-fair-text { color: #ffc107; }
        .strength-good-text { color: #28a745; }
        .strength-strong-text { color: #1a5632; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all elements
        const currentPasswordInput = document.getElementById('current_password');
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('new_password_confirmation');
        const toggleCurrentPasswordBtn = document.getElementById('toggleCurrentPassword');
        const toggleNewPasswordBtn = document.getElementById('toggleNewPassword');
        const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
        const passwordStrengthBar = document.getElementById('passwordStrengthBar');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordMatchStatus = document.getElementById('passwordMatchStatus');
        const passwordValidationIcon = document.getElementById('passwordValidationIcon');
        const confirmValidationIcon = document.getElementById('confirmValidationIcon');
        const submitBtn = document.getElementById('update-password-btn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnSuccess = document.getElementById('btnSuccess');

        // Requirement elements
        const reqLength = document.getElementById('reqLength');
        const reqUppercase = document.getElementById('reqUppercase');
        const reqLowercase = document.getElementById('reqLowercase');
        const reqNumber = document.getElementById('reqNumber');
        const reqSpecial = document.getElementById('reqSpecial');

        // Toggle password visibility
        function togglePasswordVisibility(input, button) {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            const icon = button.querySelector('i');
            
            icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            
            // Add animation
            button.classList.add('scale-110');
            setTimeout(() => {
                button.classList.remove('scale-110');
            }, 200);
        }

        toggleCurrentPasswordBtn.addEventListener('click', () => {
            togglePasswordVisibility(currentPasswordInput, toggleCurrentPasswordBtn);
        });

        toggleNewPasswordBtn.addEventListener('click', () => {
            togglePasswordVisibility(newPasswordInput, toggleNewPasswordBtn);
        });

        toggleConfirmPasswordBtn.addEventListener('click', () => {
            togglePasswordVisibility(confirmPasswordInput, toggleConfirmPasswordBtn);
        });

        // Check individual password requirements
        function checkPasswordRequirements(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[@$!%*#?&]/.test(password)
            };

            // Update requirement UI
            updateRequirementUI(reqLength, requirements.length);
            updateRequirementUI(reqUppercase, requirements.uppercase);
            updateRequirementUI(reqLowercase, requirements.lowercase);
            updateRequirementUI(reqNumber, requirements.number);
            updateRequirementUI(reqSpecial, requirements.special);

            return requirements;
        }

        // Update requirement UI element
        function updateRequirementUI(element, isValid) {
            const icon = element.querySelector('.requirement-icon i');
            if (isValid) {
                element.classList.remove('text-gray-400');
                element.classList.add('text-green-600');
                icon.className = 'fas fa-check';
            } else {
                element.classList.remove('text-green-600');
                element.classList.add('text-gray-400');
                icon.className = 'fas fa-times';
            }
        }

        // Check password strength
        function checkPasswordStrength(password) {
            if (password.length === 0) {
                return { text: '', className: '' };
            }
            
            const requirements = checkPasswordRequirements(password);
            
            // Count how many requirements are met
            let metCount = 0;
            let totalCount = 0;
            
            for (const key in requirements) {
                totalCount++;
                if (requirements[key]) metCount++;
            }
            
            if (password.length < 8) {
                return { 
                    text: 'Too short', 
                    className: 'strength-weak' 
                };
            }
            
            // Determine strength based on requirements met
            if (metCount === totalCount) {
                return { text: 'Strong', className: 'strength-strong' };
            } else if (metCount >= 3) {
                return { text: 'Good', className: 'strength-good' };
            } else if (metCount >= 2) {
                return { text: 'Fair', className: 'strength-fair' };
            } else {
                return { text: 'Weak', className: 'strength-weak' };
            }
        }

        // Update password strength indicator
        function updatePasswordStrength() {
            const password = newPasswordInput.value;
            const { text, className } = checkPasswordStrength(password);
            
            if (password === '') {
                passwordStrengthBar.className = 'h-full w-0 transition-all duration-300';
                passwordStrengthText.textContent = '';
                passwordStrengthText.className = 'text-xs mt-1 text-right';
                newPasswordInput.classList.remove('border-green-500', 'border-red-500');
                passwordValidationIcon.classList.remove('opacity-100');
                
                // Reset all requirements to default
                [reqLength, reqUppercase, reqLowercase, reqNumber, reqSpecial].forEach(el => {
                    el.classList.remove('text-green-600');
                    el.classList.add('text-gray-400');
                    const icon = el.querySelector('.requirement-icon i');
                    icon.className = 'fas fa-times';
                });
            } else {
                passwordStrengthBar.className = `h-full transition-all duration-300 ${className}`;
                
                // Check if all requirements are met
                const requirements = checkPasswordRequirements(password);
                const allRequirementsMet = Object.values(requirements).every(req => req === true);
                
                if (allRequirementsMet) {
                    newPasswordInput.classList.add('border-green-500');
                    newPasswordInput.classList.remove('border-red-500');
                    passwordValidationIcon.classList.add('opacity-100');
                } else {
                    newPasswordInput.classList.remove('border-green-500');
                    newPasswordInput.classList.add('border-red-500');
                    passwordValidationIcon.classList.remove('opacity-100');
                }
                
                // Set strength bar width based on requirements met
                const requirementsCount = Object.values(requirements).filter(req => req).length;
                const totalRequirements = Object.keys(requirements).length;
                const percentage = (requirementsCount / totalRequirements) * 100;
                
                passwordStrengthBar.style.width = percentage + '%';
                
                passwordStrengthText.textContent = text;
                passwordStrengthText.className = `text-xs mt-1 text-right ${className}-text`;
            }
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword === '') {
                passwordMatchStatus.classList.remove('opacity-100');
                confirmPasswordInput.classList.remove('border-green-500', 'border-red-500');
                confirmValidationIcon.classList.remove('opacity-100');
                return false;
            }
            
            if (password === confirmPassword && password.length >= 8) {
                // Check if password meets all requirements
                const requirements = checkPasswordRequirements(password);
                const allRequirementsMet = Object.values(requirements).every(req => req === true);
                
                if (allRequirementsMet) {
                    // Passwords match and meet requirements
                    const matchIcon = document.getElementById('matchIcon');
                    const matchText = document.getElementById('matchText');
                    matchIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                    matchText.textContent = 'Passwords match!';
                    matchText.className = 'text-green-600';
                    passwordMatchStatus.className = 'mt-2 text-sm flex items-center opacity-100 transition-opacity duration-300 text-green-600';
                    confirmPasswordInput.classList.remove('border-red-500');
                    confirmPasswordInput.classList.add('border-green-500');
                    
                    // Show green check icon
                    confirmValidationIcon.querySelector('.fa-check-circle').classList.remove('hidden');
                    confirmValidationIcon.querySelector('.fa-times-circle').classList.add('hidden');
                    confirmValidationIcon.classList.remove('text-red-500');
                    confirmValidationIcon.classList.add('text-green-500');
                    confirmValidationIcon.classList.add('opacity-100');
                    
                    return true;
                }
            }
            
            // Passwords don't match or don't meet requirements
            const matchIcon = document.getElementById('matchIcon');
            const matchText = document.getElementById('matchText');
            matchIcon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
            matchText.textContent = 'Passwords do not match';
            matchText.className = 'text-red-600';
            passwordMatchStatus.className = 'mt-2 text-sm flex items-center opacity-100 transition-opacity duration-300 text-red-600';
            confirmPasswordInput.classList.remove('border-green-500');
            confirmPasswordInput.classList.add('border-red-500');
            
            // Show red X icon
            confirmValidationIcon.querySelector('.fa-check-circle').classList.add('hidden');
            confirmValidationIcon.querySelector('.fa-times-circle').classList.remove('hidden');
            confirmValidationIcon.classList.remove('text-green-500');
            confirmValidationIcon.classList.add('text-red-500');
            confirmValidationIcon.classList.add('opacity-100');
            
            return false;
        }

        // Validate form
        function validateForm() {
            const currentPassword = currentPasswordInput.value;
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Check if current password is filled
            if (!currentPassword) return false;
            
            // Check all requirements for new password
            const requirements = checkPasswordRequirements(newPassword);
            const allRequirementsMet = Object.values(requirements).every(req => req === true);
            
            if (!allRequirementsMet) return false;
            if (newPassword !== confirmPassword) return false;
            
            return true;
        }

        // Update submit button
        function updateSubmitButton() {
            const isValid = validateForm();
            submitBtn.disabled = !isValid;
            
            if (isValid) {
                submitBtn.classList.remove('opacity-50');
                submitBtn.classList.add('hover:from-blue-700', 'hover:to-blue-900');
            } else {
                submitBtn.classList.add('opacity-50');
                submitBtn.classList.remove('hover:from-blue-700', 'hover:to-blue-900');
            }
        }

        // Update everything with debouncing
        let updateTimeout;
        function updateAll() {
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(() => {
                updatePasswordStrength();
                checkPasswordMatch();
                updateSubmitButton();
            }, 200);
        }

        // Event listeners
        currentPasswordInput.addEventListener('input', updateSubmitButton);
        newPasswordInput.addEventListener('input', updateAll);
        confirmPasswordInput.addEventListener('input', updateAll);

        // Form submission
        const form = document.getElementById('password-form');
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
        });

        // Initialize
        updateAll();
        
        // Profile photo upload functionality
        const photoInput = document.getElementById('profile-photo-input');
        const profilePhotoForm = document.getElementById('profile-photo-form');
        
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Validate file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('Please select an image file (JPG, PNG, GIF, etc.)');
                        this.value = '';
                        return;
                    }
                    
                    // Show immediate preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Update preview image
                        let preview = document.getElementById('profile-photo-preview');
                        const defaultIcon = document.getElementById('default-profile-icon');
                        
                        if (!preview) {
                            // Create new preview element
                            preview = document.createElement('img');
                            preview.id = 'profile-photo-preview';
                            preview.className = 'w-full h-full object-cover';
                            preview.alt = 'Profile Photo';
                            preview.onerror = function() {
                                this.style.display = 'none';
                                if (defaultIcon) {
                                    defaultIcon.classList.remove('hidden');
                                }
                            };
                            
                            // Insert the preview
                            const container = document.querySelector('.relative.mx-auto.w-32.h-32.mb-4 .rounded-full');
                            if (container) {
                                container.prepend(preview);
                            }
                        }
                        
                        // Set preview source
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        
                        // Hide default icon
                        if (defaultIcon) {
                            defaultIcon.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                    
                    // Submit the form
                    setTimeout(() => {
                        if (profilePhotoForm) {
                            profilePhotoForm.submit();
                        }
                    }, 800);
                }
            });
        }
        
        // Auto-hide success messages after 5 seconds
        const successMessages = document.querySelectorAll('.fade-in-message');
        successMessages.forEach(message => {
            setTimeout(() => {
                message.style.transition = 'opacity 0.5s';
                message.style.opacity = '0';
                setTimeout(() => {
                    if (message.parentNode) {
                        message.remove();
                    }
                }, 500);
            }, 5000);
        });
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
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/auth/profile-settings.blade.php ENDPATH**/ ?>