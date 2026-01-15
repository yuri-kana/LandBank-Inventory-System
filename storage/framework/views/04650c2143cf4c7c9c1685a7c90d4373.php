<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Land Bank</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1a5632;
            --light-green: #e8f5e9;
            --gold: #d4af37;
            --white: #ffffff;
            --light-gray: #f5f7fa;
            --medium-gray: #e0e0e0;
            --dark-gray: #333333;
        }

        body {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .reset-password-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 30px;
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.4s ease-out;
            border-top: 4px solid var(--gold);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-icon {
            background: var(--primary-green);
            color: var(--white);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 20px;
        }

        .logo-section h2 {
            color: var(--primary-green);
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .logo-section p {
            color: var(--dark-gray);
            font-size: 0.85rem;
            margin: 0;
        }

        .form-control {
            border: 2px solid var(--medium-gray);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: var(--light-gray);
            height: 42px;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(26, 86, 50, 0.15);
            background-color: var(--white);
            transform: translateY(-1px);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            animation: shake 0.5s ease;
            /* REMOVE the Bootstrap X icon */
            background-image: none !important;
            padding-right: 2.5rem;
        }

        .form-control.is-valid {
            border-color: var(--primary-green);
            /* REMOVE the Bootstrap check icon */
            background-image: none !important;
            padding-right: 2.5rem;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        .input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--dark-gray);
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 10;
            padding: 5px;
            font-size: 0.9rem;
        }

        .password-toggle:hover {
            color: var(--primary-green);
            transform: translateY(-50%) scale(1.1);
        }

        /* Validation icons */
        .validation-icon {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 5;
            font-size: 0.9rem;
        }

        .validation-icon.show {
            opacity: 1;
            animation: tickAppear 0.3s ease-out;
        }

        .validation-icon.valid {
            color: var(--primary-green);
        }

        .validation-icon.invalid {
            color: #dc3545;
        }

        @keyframes tickAppear {
            0% {
                opacity: 0;
                transform: translateY(-50%) scale(0.5);
            }
            100% {
                opacity: 1;
                transform: translateY(-50%) scale(1);
            }
        }

        .password-strength {
            height: 3px;
            background-color: var(--medium-gray);
            border-radius: 1px;
            margin-top: 4px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            border-radius: 1px;
            transition: all 0.3s ease;
        }

        .strength-weak { background-color: #dc3545; width: 25%; }
        .strength-fair { background-color: #ffc107; width: 50%; }
        .strength-good { background-color: #28a745; width: 75%; }
        .strength-strong { background-color: var(--primary-green); width: 100%; }

        .strength-text {
            font-size: 0.75rem;
            margin-top: 3px;
            text-align: right;
            transition: all 0.3s ease;
        }

        .strength-weak-text { color: #dc3545; }
        .strength-fair-text { color: #ffc107; }
        .strength-good-text { color: #28a745; }
        .strength-strong-text { color: var(--primary-green); }

        /* Password Requirements List */
        .password-requirements {
            margin-top: 8px;
            font-size: 0.8rem;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
            color: var(--dark-gray);
            transition: all 0.3s ease;
        }

        .requirement-item.valid {
            color: var(--primary-green);
        }

        .requirement-item.invalid {
            color: #dc3545;
        }

        .requirement-icon {
            font-size: 0.7rem;
            width: 16px;
            text-align: center;
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            border: none;
            color: var(--white);
            padding: 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: -5px;
            height: 40px;
            position: relative;
            overflow: hidden;
        }

        .btn-reset:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 86, 50, 0.2);
        }

        .btn-reset:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--medium-gray);
        }

        .back-link a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: var(--gold);
            transform: translateX(-3px);
        }

        .alert {
            border-radius: 6px;
            border: none;
            padding: 10px 12px;
            font-size: 0.85rem;
            margin-bottom: 15px;
            animation: fadeIn 0.3s ease-out;
        }

        .alert-success {
            background-color: var(--light-green);
            color: var(--primary-green);
            border-left: 3px solid var(--primary-green);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 6px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            font-size: 0.85rem;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .form-text {
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .mb-3 {
            margin-bottom: 15px !important;
        }

        .mb-4 {
            margin-bottom: 20px !important;
        }

        /* Password Match Status Styles */
        .password-match-status {
            margin-top: 5px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            border-radius: 4px;
            opacity: 0;
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }

        .password-match-status.show {
            opacity: 1;
            transform: translateY(0);
        }

        .password-match-status.match {
            color: var(--primary-green);
            background-color: rgba(26, 86, 50, 0.08);
            border: 1px solid rgba(26, 86, 50, 0.2);
        }

        .password-match-status.mismatch {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.2);
            animation: pulseError 0.5s ease;
        }

        @keyframes pulseError {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Success Check Animation */
        .checkmark {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: var(--primary-green);
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px var(--primary-green);
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: var(--primary-green);
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 30px var(--primary-green);
            }
        }

        /* Bounce Animation */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-5px);}
            60% {transform: translateY(-3px);}
        }

        .bounce {
            animation: bounce 0.6s;
        }

        /* Pulse Animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 0.5s ease-in-out;
        }

        @media (max-width: 576px) {
            .reset-password-card {
                padding: 20px;
            }
            
            .logo-icon {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
            
            .logo-section h2 {
                font-size: 1.3rem;
            }
            
            body {
                padding: 10px;
            }
        }

        @media (max-height: 700px) {
            .reset-password-card {
                padding: 20px;
            }
            
            .logo-section {
                margin-bottom: 15px;
            }
            
            .mb-3 {
                margin-bottom: 12px !important;
            }
            
            .mb-4 {
                margin-bottom: 15px !important;
            }
        }
    </style>
</head>
<body>
    <div class="reset-password-card">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Reset Password</h2>
            <p>Create a new secure password</p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i> <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.update')); ?>" id="resetPasswordForm">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="token" value="<?php echo e($token); ?>">
            <input type="hidden" name="email" value="<?php echo e($email); ?>">

            <div class="mb-3">
                <label for="email-display" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <div class="input-group">
                    <input type="email" 
                           class="form-control" 
                           id="email-display" 
                           value="<?php echo e($email); ?>" 
                           disabled
                           style="background-color: var(--light-gray);">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-key"></i> New Password
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="password" 
                           name="password" 
                           required 
                           autocomplete="new-password"
                           minlength="8"
                           placeholder="Enter new password">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                    <span class="validation-icon valid" id="passwordValidationIcon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <div class="password-strength mt-2">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
                <div class="strength-text" id="passwordStrengthText"></div>
                
                <!-- Updated: Changed from "Minimum 8 characters" to detailed requirements -->
                <div class="password-requirements" id="passwordRequirements">
                    <div class="requirement-item" id="reqLength">
                        <span class="requirement-icon"><i class="fas fa-times"></i></span>
                        <span class="requirement-text">At least 8 characters</span>
                    </div>
                    <div class="requirement-item" id="reqUppercase">
                        <span class="requirement-icon"><i class="fas fa-times"></i></span>
                        <span class="requirement-text">At least one uppercase letter</span>
                    </div>
                    <div class="requirement-item" id="reqLowercase">
                        <span class="requirement-icon"><i class="fas fa-times"></i></span>
                        <span class="requirement-text">At least one lowercase letter</span>
                    </div>
                    <div class="requirement-item" id="reqNumber">
                        <span class="requirement-icon"><i class="fas fa-times"></i></span>
                        <span class="requirement-text">At least one number</span>
                    </div>
                    <div class="requirement-item" id="reqSpecial">
                        <span class="requirement-icon"><i class="fas fa-times"></i></span>
                        <span class="requirement-text">At least one special character (@$!%*#?&)</span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="form-label">
                    <i class="fas fa-key"></i> Confirm Password
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="password-confirm" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           minlength="8"
                           placeholder="Confirm your password">
                    <button type="button" class="password-toggle" id="togglePasswordConfirm">
                        <i class="fas fa-eye"></i>
                    </button>
                    <span class="validation-icon" id="confirmValidationIcon">
                        <i class="fas fa-check-circle valid-icon"></i>
                        <i class="fas fa-times-circle invalid-icon d-none"></i>
                    </span>
                </div>
                
                <div class="password-match-status" id="passwordMatchStatus">
                    <span id="matchIcon"></span>
                    <span id="matchText"></span>
                </div>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-reset" id="submitBtn">
                    <span id="btnText">
                        <i class="fas fa-redo-alt me-2"></i>Reset Password
                    </span>
                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    <span id="btnSuccess" class="d-none">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                        Password Reset!
                    </span>
                </button>
            </div>
        </form>

        <div class="back-link">
            <a href="<?php echo e(route('login')); ?>">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password-confirm');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            const passwordMatchStatus = document.getElementById('passwordMatchStatus');
            const matchIcon = document.getElementById('matchIcon');
            const matchText = document.getElementById('matchText');
            const passwordValidationIcon = document.getElementById('passwordValidationIcon');
            const confirmValidationIcon = document.getElementById('confirmValidationIcon');
            const validIcon = confirmValidationIcon.querySelector('.valid-icon');
            const invalidIcon = confirmValidationIcon.querySelector('.invalid-icon');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const btnSuccess = document.getElementById('btnSuccess');
            const form = document.getElementById('resetPasswordForm');

            // Requirement elements
            const reqLength = document.getElementById('reqLength');
            const reqUppercase = document.getElementById('reqUppercase');
            const reqLowercase = document.getElementById('reqLowercase');
            const reqNumber = document.getElementById('reqNumber');
            const reqSpecial = document.getElementById('reqSpecial');

            // Toggle password visibility with animation
            function togglePasswordVisibility(input, button) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = button.querySelector('i');
                
                // Add animation
                icon.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 150);
                }, 150);
            }

            togglePasswordBtn.addEventListener('click', () => {
                togglePasswordVisibility(passwordInput, togglePasswordBtn);
            });

            togglePasswordConfirmBtn.addEventListener('click', () => {
                togglePasswordVisibility(passwordConfirmInput, togglePasswordConfirmBtn);
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
                updateRequirementUI(reqLength, requirements.length, '✓', '✗');
                updateRequirementUI(reqUppercase, requirements.uppercase, '✓', '✗');
                updateRequirementUI(reqLowercase, requirements.lowercase, '✓', '✗');
                updateRequirementUI(reqNumber, requirements.number, '✓', '✗');
                updateRequirementUI(reqSpecial, requirements.special, '✓', '✗');

                return requirements;
            }

            // Update requirement UI element
            function updateRequirementUI(element, isValid, validIcon, invalidIcon) {
                const icon = element.querySelector('.requirement-icon i');
                if (isValid) {
                    element.classList.add('valid');
                    element.classList.remove('invalid');
                    icon.className = 'fas fa-check';
                    icon.style.color = 'var(--primary-green)';
                } else {
                    element.classList.add('invalid');
                    element.classList.remove('valid');
                    icon.className = 'fas fa-times';
                    icon.style.color = '#dc3545';
                }
            }

            // Check password strength
            function checkPasswordStrength(password) {
                if (password.length === 0) {
                    return { text: '', className: '' };
                }
                
                // Check all requirements
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
                const password = passwordInput.value;
                const { text, className } = checkPasswordStrength(password);
                
                if (password === '') {
                    passwordStrengthBar.className = 'password-strength-bar';
                    passwordStrengthBar.style.width = '0';
                    passwordStrengthText.textContent = '';
                    passwordStrengthText.className = 'strength-text';
                    passwordInput.classList.remove('is-valid');
                    passwordValidationIcon.classList.remove('show');
                    
                    // Reset all requirements to default
                    [reqLength, reqUppercase, reqLowercase, reqNumber, reqSpecial].forEach(el => {
                        el.classList.remove('valid', 'invalid');
                        const icon = el.querySelector('.requirement-icon i');
                        icon.className = 'fas fa-times';
                        icon.style.color = 'var(--dark-gray)';
                    });
                } else {
                    passwordStrengthBar.className = `password-strength-bar ${className}`;
                    
                    // Check if all requirements are met
                    const requirements = checkPasswordRequirements(password);
                    const allRequirementsMet = Object.values(requirements).every(req => req === true);
                    
                    if (allRequirementsMet) {
                        passwordInput.classList.add('is-valid');
                        passwordValidationIcon.classList.add('show');
                    } else {
                        passwordInput.classList.remove('is-valid');
                        passwordValidationIcon.classList.remove('show');
                    }
                    
                    // Set strength bar width based on requirements met
                    const requirementsCount = Object.values(requirements).filter(req => req).length;
                    const totalRequirements = Object.keys(requirements).length;
                    const percentage = (requirementsCount / totalRequirements) * 100;
                    
                    passwordStrengthBar.style.width = percentage + '%';
                    
                    passwordStrengthText.textContent = text;
                    passwordStrengthText.className = `strength-text ${className}-text`;
                    
                    // Add bounce animation for strong passwords
                    if (className === 'strength-strong') {
                        passwordStrengthText.classList.add('bounce');
                        setTimeout(() => {
                            passwordStrengthText.classList.remove('bounce');
                        }, 600);
                    }
                }
            }

            // Check if passwords match with animations
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;
                
                if (confirmPassword === '') {
                    passwordMatchStatus.classList.remove('show');
                    passwordConfirmInput.classList.remove('is-valid', 'is-invalid');
                    confirmValidationIcon.classList.remove('show');
                    return false;
                }
                
                if (password === confirmPassword && password.length >= 8) {
                    // Check if password meets all requirements
                    const requirements = checkPasswordRequirements(password);
                    const allRequirementsMet = Object.values(requirements).every(req => req === true);
                    
                    if (allRequirementsMet) {
                        // Passwords match and meet requirements
                        matchIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                        matchText.textContent = 'Passwords match!';
                        passwordMatchStatus.className = 'password-match-status match show';
                        passwordConfirmInput.classList.remove('is-invalid');
                        passwordConfirmInput.classList.add('is-valid');
                        
                        // Show green check icon
                        validIcon.classList.remove('d-none');
                        invalidIcon.classList.add('d-none');
                        confirmValidationIcon.classList.remove('invalid');
                        confirmValidationIcon.classList.add('valid');
                        confirmValidationIcon.classList.add('show');
                        
                        // Add success animations
                        matchIcon.querySelector('i').classList.add('bounce');
                        passwordMatchStatus.classList.add('pulse');
                        
                        setTimeout(() => {
                            matchIcon.querySelector('i').classList.remove('bounce');
                            passwordMatchStatus.classList.remove('pulse');
                        }, 600);
                        
                        return true;
                    }
                }
                
                // Passwords don't match or don't meet requirements
                matchIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
                matchText.textContent = 'Passwords do not match';
                passwordMatchStatus.className = 'password-match-status mismatch show';
                passwordConfirmInput.classList.remove('is-valid');
                passwordConfirmInput.classList.add('is-invalid');
                
                // Show red X icon
                validIcon.classList.add('d-none');
                invalidIcon.classList.remove('d-none');
                confirmValidationIcon.classList.remove('valid');
                confirmValidationIcon.classList.add('invalid');
                confirmValidationIcon.classList.add('show');
                
                // Add error animation
                passwordConfirmInput.style.animation = 'none';
                setTimeout(() => {
                    passwordConfirmInput.style.animation = 'shake 0.5s ease';
                }, 10);
                
                return false;
            }

            // Validate form
            function validateForm() {
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;
                
                // Check all requirements
                const requirements = checkPasswordRequirements(password);
                const allRequirementsMet = Object.values(requirements).every(req => req === true);
                
                if (!allRequirementsMet) return false;
                if (password !== confirmPassword) return false;
                
                return true;
            }

            // Update submit button
            function updateSubmitButton() {
                const isValid = validateForm();
                submitBtn.disabled = !isValid;
                
                if (isValid) {
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    submitBtn.querySelector('i').style.animation = 'pulse 1s infinite';
                } else {
                    submitBtn.style.opacity = '0.6';
                    submitBtn.style.cursor = 'not-allowed';
                    submitBtn.querySelector('i').style.animation = 'none';
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

            // Event listeners with animations
            passwordInput.addEventListener('input', function() {
                updateAll();
                
                // Add typing animation
                this.style.transform = 'scale(1.01)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });

            passwordConfirmInput.addEventListener('input', function() {
                updateAll();
                
                // Add typing animation
                this.style.transform = 'scale(1.01)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });

            // Form submission with enhanced animations
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    // Highlight issues with animation
                    const requirements = checkPasswordRequirements(passwordInput.value);
                    const allRequirementsMet = Object.values(requirements).every(req => req === true);
                    
                    if (!allRequirementsMet) {
                        passwordInput.classList.add('is-invalid');
                        passwordInput.style.animation = 'shake 0.5s ease';
                        setTimeout(() => {
                            passwordInput.style.animation = '';
                        }, 500);
                    }
                    
                    if (passwordInput.value !== passwordConfirmInput.value) {
                        passwordConfirmInput.classList.add('is-invalid');
                        passwordConfirmInput.style.animation = 'shake 0.5s ease';
                        setTimeout(() => {
                            passwordConfirmInput.style.animation = '';
                        }, 500);
                    }
                    
                    return;
                }
                
                // Show loading state with animation
                btnText.style.opacity = '0';
                btnText.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    btnText.style.display = 'none';
                    btnSpinner.classList.remove('d-none');
                    btnSpinner.style.opacity = '1';
                    btnSpinner.style.transform = 'scale(1)';
                }, 300);
                
                submitBtn.disabled = true;
                submitBtn.style.cursor = 'wait';
                submitBtn.style.transform = 'scale(0.98)';
                
                // Simulate processing with loading animation
                setTimeout(() => {
                    // Show success animation before submitting
                    btnSpinner.classList.add('d-none');
                    btnSuccess.classList.remove('d-none');
                    submitBtn.classList.add('pulse');
                    
                    // Submit the form after success animation
                    setTimeout(() => {
                        form.submit();
                    }, 1000);
                }, 1500);
            });

            // Auto focus password field with animation
            setTimeout(() => {
                passwordInput.focus();
                passwordInput.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    passwordInput.style.transform = 'scale(1)';
                }, 200);
            }, 100);

            // Initialize
            updateAll();
            
            // Add hover effects
            const formControls = document.querySelectorAll('.form-control:not([disabled])');
            formControls.forEach(control => {
                control.addEventListener('mouseenter', () => {
                    control.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
                });
                
                control.addEventListener('mouseleave', () => {
                    if (!control.matches(':focus')) {
                        control.style.boxShadow = '';
                    }
                });
            });
        });
    </script>
</body>
</html><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>