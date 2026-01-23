<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Inventory System - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--light-green) 0%, #f0f9ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(26, 86, 50, 0.15);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-top: 4px solid var(--gold);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-icon {
            background: var(--primary-green);
            color: var(--white);
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 15px;
            box-shadow: 0 4px 12px rgba(26, 86, 50, 0.2);
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 5px;
        }
        
        .tagline {
            color: var(--gold);
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.3px;
        }
        
        .login-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark-gray);
            text-align: center;
            margin-bottom: 25px;
        }
        
        /* Update alert box styles for different message types */
        .alert-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            color: #991b1b;
            font-size: 14px;
            display: flex;
            align-items: center;
            transition: opacity 0.5s ease;
        }

        .alert-box i {
            margin-right: 8px;
            font-size: 16px;
        }

        .alert-box.success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-box.success i {
            color: #16a34a;
        }

        .alert-box.info {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }

        .alert-box.info i {
            color: #3b82f6;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            background: var(--light-gray);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(26, 86, 50, 0.1);
            background: var(--white);
        }
        
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 16px;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            margin-bottom: 20px;
        }

        .forgot-password-link {
            color: var(--primary-green);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .forgot-password-link:hover {
            color: #134526;
            text-decoration: underline;
        }

        .forgot-password-link i {
            margin-right: 6px;
            font-size: 13px;
        }
        
        .submit-btn {
            padding: 14px 30px;
            background: var(--primary-green);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .submit-btn i {
            margin-right: 10px;
        }
        
        .submit-btn:hover {
            background: #134526;
            box-shadow: 0 4px 12px rgba(26, 86, 50, 0.3);
        }
        
        .test-accounts {
            background: #fefce8;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            border: 1px solid #fef08a;
        }
        
        .test-accounts h3 {
            font-size: 16px;
            color: var(--dark-gray);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            color: var(--primary-green);
        }
        
        .test-accounts h3 i {
            margin-right: 10px;
            color: var(--gold);
        }
        
        .account-group {
            margin-bottom: 12px;
        }
        
        .account-group:last-child {
            margin-bottom: 0;
        }
        
        .account-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 3px;
        }
        
        .account-details {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }
        
        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #666;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 25px;
            }
            
            .form-footer {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            
            .forgot-password-link {
                justify-content: center;
                order: 2;
                margin-top: 10px;
            }
            
            .submit-btn {
                justify-content: center;
                width: 100%;
                order: 1;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="logo-text">Inventory System</div>
            <div class="tagline">Admin & Staff Login</div>
        </div>
        
        <div class="login-title">Sign In to Your Account</div>

        <!-- VERIFICATION MESSAGES -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert-box success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="alert-box">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
            <div class="alert-box info">
                <i class="fas fa-info-circle"></i>
                <span><?php echo e(session('info')); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert-box">
                <i class="fas fa-exclamation-circle"></i>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span><?php echo e($error); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    autofocus
                    class="form-input"
                    placeholder="Enter your email"
                    value="<?php echo e(old('email')); ?>"
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-container">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        class="form-input"
                        placeholder="Enter your password"
                    >
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-footer">
                <a href="<?php echo e(route('password.request')); ?>" class="forgot-password-link">
                    <i class="fas fa-key mr-1"></i> Forgot Password?
                </a>
                
                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </div>
        </form>
        
        <div class="footer">
            &copy; <?php echo e(date('Y')); ?> Inventory System. For authorized personnel only.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            
            // Password toggle functionality
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'password') {
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                } else {
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                }
            });
            
            // Add form submission debug
            loginForm.addEventListener('submit', function(e) {
                console.log('Form submitting...');
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            });
            
            // Auto-remove all alert messages after 5 seconds
            setTimeout(function() {
                const alertBoxes = document.querySelectorAll('.alert-box');
                alertBoxes.forEach(function(alertBox) {
                    alertBox.style.opacity = '0';
                    setTimeout(function() {
                        alertBox.remove();
                    }, 500);
                });
            }, 5000);
        });
    </script>
</body>
</html><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/auth/login.blade.php ENDPATH**/ ?>