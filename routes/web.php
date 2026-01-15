<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TeamRequestController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController; 
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL to Dashboard (if authenticated) or Login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home.redirect');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Logout (accessible to authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

// 1. Link Handler
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1']) 
    ->name('verification.verify');

// Routes accessible only to authenticated users who might be unverified/inactive
Route::middleware(['auth', 'verified.email'])->group(function () {
    // 2. Verification Notice
    Route::get('/email/verify', [VerificationController::class, 'showVerificationNotice'])
        ->name('verification.notice');

    // 3. Resend Link Request
    Route::post('/email/verify/resend', [VerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.resend');
    
    // 4. Verification success page
    Route::get('/email/verified', [VerificationController::class, 'showVerificationSuccess'])
        ->name('verification.success');

    // 5. API endpoint to check verification status
    Route::post('/api/check-verification-status', [VerificationController::class, 'checkStatus'])
        ->name('verification.check');
});

/*
|--------------------------------------------------------------------------
| Dashboard Route (MUST COME BEFORE OTHER AUTH ROUTES)
|--------------------------------------------------------------------------
*/

// FIX: Dashboard route with optional tab parameter - this route handles BOTH /dashboard and /dashboard/{tab}
Route::get('/dashboard/{tab?}', [DashboardController::class, 'dashboard'])
    ->name('dashboard')
    ->where('tab', 'usage-pattern|depletion|restock-management|inventory-records')
    ->middleware(['auth', 'verified.email']);

// Legacy dashboard route for backward compatibility - REMOVE THIS LINE or comment it out
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.legacy');

Route::get('/dashboard/metrics', [DashboardController::class, 'getMetrics'])->middleware(['auth', 'verified.email']);

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes (EXCEPT DASHBOARD)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified.email'])->group(function () {
    
    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::get('/count', [NotificationController::class, 'getCount'])->name('count');
    });
    
    // Requests Routes (for all authenticated users)
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [TeamRequestController::class, 'index'])->name('index');
        Route::get('/create', [TeamRequestController::class, 'create'])->name('create');
        Route::post('/', [TeamRequestController::class, 'store'])->name('store');
        Route::delete('/{teamRequest}', [TeamRequestController::class, 'destroy'])->name('destroy');
    });

    // Items Routes (view only for staff)
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Items Management
        Route::resource('items', ItemController::class)->names([
            'index' => 'items.index',
            'create' => 'items.create',
            'store' => 'items.store',
            'show' => 'items.show',
            'edit' => 'items.edit',
            'update' => 'items.update',
            'destroy' => 'items.destroy'
        ]);
        
        // Stock Management
        Route::get('/items/{item}/add-stock', [ItemController::class, 'showAddStockForm'])->name('items.show-add-stock');
        Route::put('/items/{item}/add-stock', [ItemController::class, 'addStock'])->name('items.add-stock');
        
        // Bulk Restocking
        Route::get('/items/restock/bulk', [DashboardController::class, 'getItemsForRestock'])->name('items.restock.bulk');
        Route::post('/items/restock/bulk', [ItemController::class, 'processBulkRestock'])->name('items.restock.bulk.process');
        
        // Teams Management
        Route::resource('teams', TeamController::class)->except(['show', 'edit', 'update'])->names([
            'index' => 'teams.index',
            'create' => 'teams.create',
            'store' => 'teams.store',
            'destroy' => 'teams.destroy'
        ]);
        
        // Team Members Management
        Route::prefix('teams')->name('teams.')->group(function () {
            Route::get('/{team}/members', [TeamController::class, 'getMembers'])->name('members');
            Route::post('/add-member', [TeamController::class, 'addMember'])->name('add-member');
            Route::delete('/{team}/remove-member/{user}', [TeamController::class, 'removeMember'])
                ->name('remove-member');
        });

        // Reports Management
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/download/excel', [ReportController::class, 'downloadExcel'])->name('download.xlsx');
            Route::get('/download/csv', [ReportController::class, 'downloadCSV'])->name('download.csv');
            Route::get('/download/pdf', [ReportController::class, 'downloadPDF'])->name('download.pdf');
            Route::get('/check/{year}/{month}', [ReportController::class, 'checkReportExists'])->name('check');
            Route::post('/generate/{year}/{month}', [ReportController::class, 'generateReportOnDemand'])->name('generate');
            Route::post('/generate-missing', [ReportController::class, 'generateMissingReports'])->name('generate.missing');
            Route::get('/view/{year}/{month}', [ReportController::class, 'view'])->name('view');
            Route::post('/finalize', [ReportController::class, 'finalize'])->name('finalize');
            
            // Add this new route for report details
            Route::get('/{year}/{month}/details', [DashboardController::class, 'getReportDetails'])
                ->name('details');
        });

        // Request Status Management (Admin only)
        Route::post('/requests/{teamRequest}/update-status', [TeamRequestController::class, 'updateStatus'])
            ->name('requests.updateStatus');
        
        Route::post('/requests/{teamRequest}/claim', [TeamRequestController::class, 'claim'])
            ->name('requests.claim');

        // Email Verification Workflow
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::post('/check-email', [TeamController::class, 'checkEmail'])->name('check.email');
            Route::post('/send-verification', [TeamController::class, 'sendVerification'])->name('send');
            Route::post('/verify-code', [TeamController::class, 'verifyCode'])->name('verify');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Password Reset Routes (Public)
|--------------------------------------------------------------------------
*/

Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('reset-password', [ForgotPasswordController::class, 'reset'])
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Profile Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'show'])->name('profile.settings');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
});

// Temporary test route - remove after testing
Route::get('/test-fix-restock', function() {
    try {
        // Check if inventory_logs table exists
        if (!Schema::hasTable('inventory_logs')) {
            echo "<h2>Creating inventory_logs table...</h2>";
            
            Schema::create('inventory_logs', function ($table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                $table->string('action');
                $table->integer('quantity_change');
                $table->integer('beginning_quantity');
                $table->integer('ending_quantity');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
            
            echo "Table created!<br>";
        }
        
        // Test restock logging
        $item = \App\Models\Item::first();
        if ($item) {
            $oldQuantity = $item->quantity;
            $quantity = 5;
            $newQuantity = $oldQuantity + $quantity;
            
            // Update item
            $item->quantity = $newQuantity;
            $item->save();
            
            // Create log
            \App\Models\InventoryLog::create([
                'item_id' => $item->id,
                'action' => 'restock',
                'quantity_change' => $quantity,
                'beginning_quantity' => $oldQuantity,
                'ending_quantity' => $newQuantity,
                'user_id' => auth()->id() ?? 1,
                'notes' => 'Test restock'
            ]);
            
            echo "Test restock successful!<br>";
            echo "Item: {$item->name}<br>";
            echo "Old: {$oldQuantity}, Added: {$quantity}, New: {$newQuantity}<br>";
        }
        
        // Check logs
        $logs = \App\Models\InventoryLog::all();
        echo "<h3>Inventory Logs:</h3>";
        echo "<pre>" . print_r($logs->toArray(), true) . "</pre>";
        
        return "Test complete!";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage() . "<br>Trace: " . $e->getTraceAsString();
    }
});