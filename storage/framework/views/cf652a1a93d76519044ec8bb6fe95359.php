<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Inventory System')); ?> - Change Password</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Background matches the subtle gradient from the login screen */
        body {
            background: #f7f9f7; /* Light background */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        /* Styles for the Card to match the Login Card */
        .login-card {
            border-top: 5px solid #4a824e; /* Gold/Green accent border */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: white;
            padding: 2rem;
        }

        /* Input field style for consistency */
        .custom-input {
            border-color: #e5e7eb;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .custom-input:focus {
            border-color: #4a824e; /* Focus color matches primary green */
            box-shadow: 0 0 0 3px rgba(74, 130, 78, 0.2);
            outline: none;
        }

        /* Custom Styles for Password Requirements (Matching UI colors) */
        .password-requirements {
            background-color: #f7fff8; /* Very light green background */
            border: 1px solid #d1e7dd;
            border-left: 4px solid #4a824e; /* Green border */
            padding: 10px 15px;
            margin-top: 8px;
            border-radius: 6px;
        }
        
        .requirement {
            font-size: 13px;
            color: #4a5568;
            margin-bottom: 2px;
        }
        
        .requirement.met {
            color: #38a169; /* Green for met requirements */
        }
        
        .checkmark {
            margin-right: 8px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            min-width: 15px; /* Ensure alignment */
            display: inline-block;
        }
        
        .match-success { color: #38a169; }
        .match-error { color: #e53e3e; }

        /* Password strength indicator */
        .strength-meter {
            height: 4px;
            width: 100%;
            background-color: #e5e7eb;
            border-radius: 2px;
            margin-top: 4px;
            overflow: hidden;
        }
        
        .strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        /* Eye icon styling */
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }
        
        .password-toggle:hover {
            color: #4a824e;
        }

    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 sm:px-0">
        <div class="w-full max-w-md login-card">

            <div class="flex flex-col items-center pt-6 pb-2">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center border-2 border-[#4a824e] mb-2">
                    <svg class="w-8 h-8 text-[#4a824e]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#4a824e] mb-1">Inventory System</h2>
                <h3 class="text-sm font-semibold text-gray-700 mt-4">Set a New Password</h3>
                <p class="text-sm text-gray-500 mt-1 text-center">First login detected. For security, please update your password.</p>
            </div>

            <div class="py-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-300">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded border border-red-300">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded border border-red-300">
                        <p class="font-semibold mb-1">Please correct the following errors:</p>
                        <ul class="list-disc list-inside ml-2 text-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form method="POST" action="<?php echo e(route('password.force-change.store')); ?>" class="space-y-4" id="passwordChangeForm">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            New Password
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   placeholder="Enter new password"
                                   class="mt-1 block w-full px-4 py-2 pr-10 custom-input border rounded-md shadow-sm text-sm"
                                   required
                                   oninput="validatePassword()">
                            <span class="password-toggle" onclick="togglePasswordVisibility('password')">
                                <i class="far fa-eye" id="passwordEyeIcon"></i>
                            </span>
                        </div>
                        
                        <!-- Password strength meter -->
                        <div class="strength-meter mt-2">
                            <div class="strength-meter-fill" id="passwordStrength"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" id="strengthText">Password strength: None</div>
                        
                        <div id="passwordRequirements" class="password-requirements mt-2">
                            <p class="text-xs font-semibold text-gray-600 mb-1">Password requirements:</p>
                            <div id="lengthReq" class="requirement flex items-center">
                                <span class="checkmark text-gray-400">○</span> At least 8 characters
                            </div>
                            <div id="upperReq" class="requirement flex items-center">
                                <span class="checkmark text-gray-400">○</span> One uppercase letter
                            </div>
                            <div id="lowerReq" class="requirement flex items-center">
                                <span class="checkmark text-gray-400">○</span> One lowercase letter
                            </div>
                            <div id="numberReq" class="requirement flex items-center">
                                <span class="checkmark text-gray-400">○</span> One number
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   placeholder="Confirm new password"
                                   class="mt-1 block w-full px-4 py-2 pr-10 custom-input border rounded-md shadow-sm text-sm"
                                   required
                                   oninput="validatePassword()">
                            <span class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')">
                                <i class="far fa-eye" id="confirmPasswordEyeIcon"></i>
                            </span>
                        </div>
                        
                        <div id="passwordMatch" class="mt-2 text-sm flex items-center hidden">
                            <span id="matchIcon" class="mr-1 font-bold">○</span>
                            <span id="matchText">Passwords match</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#4a824e] hover:bg-[#386b3b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4a824e] disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0v4m-4 7v2"></path>
                            </svg>
                            Change Password & Sign In
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-2 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center">
                    © 2025 Inventory System. For authorized personnel only.
                </p>
            </div>
        </div>
    </div>

    <script>
        const newPasswordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');
        
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + 'EyeIcon');
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
        
        function calculatePasswordStrength(password) {
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 10;
            
            // Character variety checks
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 15;
            if (/[^A-Za-z0-9]/.test(password)) strength += 10;
            
            return Math.min(strength, 100);
        }
        
        function getStrengthText(strength) {
            if (strength < 30) return {text: "Weak", color: "#ef4444"};
            if (strength < 60) return {text: "Fair", color: "#f59e0b"};
            if (strength < 80) return {text: "Good", color: "#10b981"};
            return {text: "Strong", color: "#059669"};
        }
        
        function validatePassword() {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Check password requirements
            const hasLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            
            // Update requirement indicators
            updateRequirement('lengthReq', hasLength);
            updateRequirement('upperReq', hasUpper);
            updateRequirement('lowerReq', hasLower);
            updateRequirement('numberReq', hasNumber);
            
            // Calculate and display password strength
            const strength = calculatePasswordStrength(password);
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            
            const strengthInfo = getStrengthText(strength);
            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = strengthInfo.color;
            strengthText.textContent = `Password strength: ${strengthInfo.text}`;
            strengthText.style.color = strengthInfo.color;
            
            // Check if passwords match
            const passwordMatchEl = document.getElementById('passwordMatch');
            const matchIcon = document.getElementById('matchIcon');
            const matchText = document.getElementById('matchText');
            
            if (confirmPassword) {
                const passwordsMatch = password === confirmPassword;
                passwordMatchEl.classList.remove('hidden');
                passwordMatchEl.classList.remove('match-success', 'match-error');
                
                if (passwordsMatch) {
                    passwordMatchEl.classList.add('match-success');
                    matchIcon.textContent = '✓';
                    matchText.textContent = 'Passwords match';
                } else {
                    passwordMatchEl.classList.add('match-error');
                    matchIcon.textContent = '✗';
                    matchText.textContent = 'Passwords do not match';
                }
            } else {
                passwordMatchEl.classList.add('hidden');
            }
            
            // Enable/disable submit button
            const isValid = hasLength && hasUpper && hasLower && hasNumber && 
                          (confirmPassword ? password === confirmPassword : false);
            
            submitBtn.disabled = !isValid;
            
            return isValid;
        }
        
        function updateRequirement(elementId, isMet) {
            const element = document.getElementById(elementId);
            const checkmark = element.querySelector('.checkmark');
            if (isMet) {
                element.classList.add('met');
                checkmark.classList.add('text-green-600');
                checkmark.classList.remove('text-gray-400');
                checkmark.textContent = '✓';
            } else {
                element.classList.remove('met');
                checkmark.classList.add('text-gray-400');
                checkmark.classList.remove('text-green-600');
                checkmark.textContent = '○';
            }
        }
        
        // Event listeners
        newPasswordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);
        
        // Form submission handler
        document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
            if (!validatePassword()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Changing Password...';
            
            return true;
        });
        
        // Initial validation
        document.addEventListener('DOMContentLoaded', validatePassword);
    </script>
</body>
</html><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views\auth\force-password-change.blade.php ENDPATH**/ ?>