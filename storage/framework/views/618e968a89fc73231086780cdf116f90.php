<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', config('app.name', 'Inventory System')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Original layout fixes merged */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f9ff 100%);
            font-family: 'Figtree', sans-serif;
        }

        /* Fixed navigation container */
        .nav-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }

        /* Main content offset */
        .main-content {
            margin-top: 64px; /* height of navbar */
            min-height: calc(100vh - 64px);
        }

        /* Gradient page header */
        .page-header {
            background: white;
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Smooth scrollbar */
        .theme-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .theme-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .theme-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .theme-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Your updated styles */
        .stock-low { background-color: #fef3c7; color: #92400e; }
        .stock-out { background-color: #fee2e2; color: #991b1b; }
        .stock-ok { background-color: #d1fae5; color: #065f46; }
    </style>
</head>

<body class="font-sans antialiased theme-scrollbar">
    
    <!-- Fixed Navigation -->
    <div class="nav-container">
        <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <!-- Main layout content -->
    <div class="main-content">

        <!-- Page Header -->
        <header class="page-header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($header)): ?>
                    <?php echo e($header); ?>

                <?php else: ?>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">
                                <?php echo $__env->yieldContent('title', 'Dashboard'); ?>
                            </h1>
                            <?php if (! empty(trim($__env->yieldContent('subtitle')))): ?>
                                <p class="mt-1 text-sm text-white/90">
                                    <?php echo $__env->yieldContent('subtitle'); ?>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mt-4 sm:mt-0">
                            <?php if (! empty(trim($__env->yieldContent('header-actions')))): ?>
                                <?php echo $__env->yieldContent('header-actions'); ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <?php echo e($slot); ?>

        </main>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alert dismiss
            document.querySelectorAll('[data-dismiss]').forEach(el => {
                el.addEventListener('click', () => {
                    el.parentElement.style.display = 'none';
                });
            });

            // Active link highlight
            const currentPath = window.location.pathname;
            document.querySelectorAll('nav a').forEach(link => {
                if (
                    link.getAttribute('href') === currentPath ||
                    (currentPath.startsWith('/items') && link.href.includes('/items')) ||
                    (currentPath.startsWith('/requests') && link.href.includes('/requests')) ||
                    (currentPath.startsWith('/teams') && link.href.includes('/teams'))
                ) {
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>
</html><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/components/app-layout.blade.php ENDPATH**/ ?>