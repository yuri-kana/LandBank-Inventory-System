<?php $__env->startSection('title', 'Create New Team - Inventory System'); ?>

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
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-users mr-2 text-emerald-600"></i> <?php echo e(__('Create New Team')); ?>

                </h2>
                <p class="text-sm text-gray-500 mt-1">Add a new team to your inventory system</p>
            </div>
            <a href="<?php echo e(route('admin.teams.index')); ?>" 
               class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 py-2 rounded-lg flex items-center shadow-md transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Teams
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center animate-fade-in">
                    <i class="fas fa-check-circle mr-3 text-emerald-600 text-xl"></i>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-600 text-xl"></i>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="mt-2 ml-8 list-disc text-sm">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Form Card -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-white/20 text-white mr-3">
                            <i class="fas fa-user-plus text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Team Creation</h3>
                            <p class="text-emerald-100 text-sm">Create a new team - Add members later</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <form method="POST" action="<?php echo e(route('admin.teams.store')); ?>" id="teamForm">
                        <?php echo csrf_field(); ?>

                        <!-- Team Information Section -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="p-2 rounded-full bg-emerald-100 text-emerald-600 mr-2">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900">Team Information</h4>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Team Name -->
                                <div>
                                    <label for="team_name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-users mr-2 text-emerald-600"></i> Team Name
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            name="team_name"
                                            id="team_name"
                                            required
                                            class="pl-10 pr-4 py-3 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                            value="<?php echo e(old('team_name')); ?>"
                                            placeholder="e.g., Team Alpha, Marketing Team"
                                            autofocus
                                        >
                                        <div class="absolute left-3 top-3 text-gray-400">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['team_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-xs text-gray-500">
                                            Choose a descriptive name for the team (will be displayed in the system)
                                        </p>
                                        <span class="text-xs text-gray-400" id="charCounter">0/50</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-gray-200">
                            <div class="mb-4 sm:mb-0">
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i> Add team members after creating the team
                                </p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="<?php echo e(route('admin.teams.index')); ?>" 
                                   class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200 flex items-center">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </a>
                                <button
                                    type="submit"
                                    id="submitBtn"
                                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-8 py-3 font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled
                                >
                                    <i class="fas fa-plus-circle mr-2"></i> Create Team
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-5">
                <div class="flex items-start">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                        <i class="fas fa-lightbulb text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-blue-900">How Teams Work</h4>
                        <ul class="mt-2 text-sm text-blue-700 space-y-1">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-1 text-blue-500 text-xs"></i>
                                <strong>Team Creation:</strong> Create team first, then add members
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-1 text-blue-500 text-xs"></i>
                                <strong>Default Passwords:</strong> Members get auto-generated passwords like "Inventory-Team10@2026"
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-1 text-blue-500 text-xs"></i>
                                <strong>Email Verification:</strong> Members must verify email before first login
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-1 text-blue-500 text-xs"></i>
                                <strong>Password Change:</strong> Members must change password on first login
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-1 text-blue-500 text-xs"></i>
                                <strong>Team Requests:</strong> Members can request inventory items for their team
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const teamForm = document.getElementById('teamForm');
            const teamNameInput = document.getElementById('team_name');
            const submitBtn = document.getElementById('submitBtn');
            const charCounter = document.getElementById('charCounter');
            
            // Character counter
            teamNameInput.addEventListener('input', function() {
                const length = this.value.length;
                charCounter.textContent = `${length}/50`;
                
                // Update colors based on length
                if (length > 45) {
                    charCounter.classList.remove('text-gray-400');
                    charCounter.classList.add('text-yellow-600');
                } else if (length > 50) {
                    charCounter.classList.remove('text-yellow-600');
                    charCounter.classList.add('text-red-600');
                } else {
                    charCounter.classList.remove('text-yellow-600', 'text-red-600');
                    charCounter.classList.add('text-gray-400');
                }
                
                // Enable/disable submit button based on length
                const isValid = length >= 3 && length <= 50;
                submitBtn.disabled = !isValid;
                
                // Visual feedback
                if (isValid) {
                    teamNameInput.classList.remove('border-red-300');
                    teamNameInput.classList.add('border-green-300');
                } else {
                    teamNameInput.classList.remove('border-green-300');
                    teamNameInput.classList.add('border-red-300');
                }
            });
            
            // Initial character counter update
            teamNameInput.dispatchEvent(new Event('input'));
            
            // Real-time team name availability check
            let checkTimeout;
            teamNameInput.addEventListener('input', function() {
                clearTimeout(checkTimeout);
                
                const teamName = this.value.trim();
                if (teamName.length >= 3 && teamName.length <= 50) {
                    checkTimeout = setTimeout(() => {
                        // AJAX call to check if team name exists
                        fetch(`/admin/teams/check-name?team_name=${encodeURIComponent(teamName)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.available) {
                                showAlert('Team name already exists! Please choose a different name.', 'error');
                                submitBtn.disabled = true;
                                teamNameInput.classList.add('border-red-500');
                            } else {
                                teamNameInput.classList.remove('border-red-500');
                                // Re-enable button if length is valid
                                if (teamName.length >= 3 && teamName.length <= 50) {
                                    submitBtn.disabled = false;
                                }
                            }
                        })
                        .catch(error => console.error('Error checking team name:', error));
                    }, 500);
                }
            });
            
            // Form submission validation
            teamForm.addEventListener('submit', function(e) {
                const teamName = teamNameInput.value.trim();
                
                // Basic validation
                if (!teamName) {
                    e.preventDefault();
                    showAlert('Please enter a team name.', 'error');
                    return false;
                }
                
                if (teamName.length < 3) {
                    e.preventDefault();
                    showAlert('Team name must be at least 3 characters long.', 'error');
                    return false;
                }
                
                if (teamName.length > 50) {
                    e.preventDefault();
                    showAlert('Team name cannot exceed 50 characters.', 'error');
                    return false;
                }
                
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
                submitBtn.disabled = true;
                
                return true;
            });

            function showAlert(message, type) {
                // Remove existing alerts
                const existingAlert = document.querySelector('.custom-alert');
                if (existingAlert) existingAlert.remove();

                // Create alert
                const alert = document.createElement('div');
                alert.className = `custom-alert fixed top-20 right-4 z-50 px-6 py-4 rounded-lg shadow-lg animate-fade-in ${
                    type === 'error' ? 'bg-red-500 text-white' : 'bg-emerald-500 text-white'
                }`;
                alert.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} mr-3"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.body.appendChild(alert);

                // Remove after 5 seconds
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }, 5000);
            }
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .custom-alert {
            animation: slideInRight 0.3s ease-out;
            transition: opacity 0.3s, transform 0.3s;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .border-green-300 {
            border-color: #6ee7b7;
        }
        
        .border-red-300 {
            border-color: #fca5a5;
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/teams/create.blade.php ENDPATH**/ ?>