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
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <i class="fas fa-envelope text-blue-500 text-6xl mb-4"></i>
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Verify Your Email Address
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Before proceeding, please check your email for a verification link.
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                        <i class="fas fa-envelope-open-text text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Verification Required
                    </h3>
                    <p class="text-gray-600">
                        You need to verify your email address to access all features.
                    </p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                A verification email has been sent to:
                                <strong><?php echo e(auth()->user()->email); ?></strong>
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
                                Check your spam folder if you don't see it in your inbox.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Important:</strong> Your account access is limited until you verify your email.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-3">
                        Didn't receive the email? Click below to resend:
                    </p>
                    
                    <form method="POST" action="<?php echo e(route('verification.resend')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="email" value="<?php echo e(auth()->user()->email); ?>">
                        
                        <button type="submit" 
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Resend Verification Email
                        </button>
                    </form>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm text-green-600 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <?php echo e(session('success')); ?>

                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <?php echo e(session('error')); ?>

                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="text-sm text-gray-600">
                        <p class="mb-2">Need help with verification?</p>
                        <div class="flex space-x-4">
                            <a href="mailto:admin@inventory.com" class="font-medium text-blue-600 hover:text-blue-500">
                                <i class="fas fa-envelope mr-1"></i> Contact Admin
                            </a>
                            <a href="<?php echo e(route('dashboard')); ?>" class="font-medium text-green-600 hover:text-green-500">
                                <i class="fas fa-home mr-1"></i> Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                               class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-resend:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .btn-resend:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resendForm = document.querySelector('form[action*="verification.resend"]');
            const resendBtn = resendForm ? resendForm.querySelector('button[type="submit"]') : null;
            
            if (resendBtn) {
                let isCooldown = false;
                
                resendForm.addEventListener('submit', function(e) {
                    if (isCooldown) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Show loading state
                    const originalText = resendBtn.innerHTML;
                    resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
                    resendBtn.disabled = true;
                    
                    // Set cooldown for 60 seconds
                    isCooldown = true;
                    let seconds = 60;
                    
                    const countdown = setInterval(function() {
                        seconds--;
                        resendBtn.innerHTML = `<i class="fas fa-clock mr-2"></i> Wait ${seconds}s`;
                        
                        if (seconds <= 0) {
                            clearInterval(countdown);
                            isCooldown = false;
                            resendBtn.disabled = false;
                            resendBtn.innerHTML = originalText;
                        }
                    }, 1000);
                    
                    // Reset after 60 seconds
                    setTimeout(() => {
                        clearInterval(countdown);
                        isCooldown = false;
                        resendBtn.disabled = false;
                        resendBtn.innerHTML = originalText;
                    }, 60000);
                });
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
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>