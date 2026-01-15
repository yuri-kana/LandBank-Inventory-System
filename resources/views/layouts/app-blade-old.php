<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Inventory System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Fix body and layout spacing */
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
        
        /* Main content area */
        .main-content {
            margin-top: 64px; /* Height of navigation */
            min-height: calc(100vh - 64px);
        }
        
        /* Page header styling */
        .page-header {
            background: linear-gradient(to right, #059669, #10b981);
            color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body class="theme-scrollbar">
    <!-- Fixed Navigation -->
    <div class="nav-container">
        @include('layouts.navigation')
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        @if (isset($header))
            <header class="page-header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple alert dismissal
            const alerts = document.querySelectorAll('[data-dismiss]');
            alerts.forEach(alert => {
                alert.addEventListener('click', function() {
                    this.parentElement.style.display = 'none';
                });
            });
            
            // Add active class to current page nav item
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('nav a');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath || 
                    (currentPath.startsWith('/items') && link.getAttribute('href')?.includes('/items')) ||
                    (currentPath.startsWith('/requests') && link.getAttribute('href')?.includes('/requests')) ||
                    (currentPath.startsWith('/teams') && link.getAttribute('href')?.includes('/teams'))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>