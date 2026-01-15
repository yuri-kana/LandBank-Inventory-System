<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Land Bank</title>
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
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .forgot-password-card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.6s ease-out;
            border-top: 5px solid var(--gold);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .logo-icon {
            background: var(--primary-green);
            color: var(--white);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .logo-section h2 {
            color: var(--primary-green);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .logo-section p {
            color: var(--dark-gray);
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--light-gray);
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(26, 86, 50, 0.25);
            background-color: var(--white);
            transform: translateY(-2px);
        }

        .btn-send {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            border: none;
            color: var(--white);
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-send:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(26, 86, 50, 0.3);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }

        .back-link a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-link a:hover {
            color: var(--gold);
            transform: translateX(-5px);
        }

        .alert-success {
            background-color: var(--light-green);
            color: var(--primary-green);
            border-left: 4px solid var(--primary-green);
            border-radius: 8px;
            animation: fadeIn 0.5s ease-out;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 576px) {
            .forgot-password-card {
                padding: 25px;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-password-card">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-key"></i>
            </div>
            <h2>Forgot Password?</h2>
            <p>Enter your email to receive a password reset link</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="email" 
                       autofocus>
                
                @error('email')
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-send" id="submitBtn">
                    <span id="btnText">Send Reset Link</span>
                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            form.addEventListener('submit', function(e) {
                // Show loading state
                btnText.textContent = 'Sending...';
                btnSpinner.classList.remove('d-none');
                submitBtn.disabled = true;
                
                // Add slight delay to show loading state
                setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });
    </script>
</body>
</html>