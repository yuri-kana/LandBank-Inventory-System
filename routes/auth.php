# Create routes/auth.php that matches your AuthController
echo "<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
" > routes/auth.phpes/auth.phpes/auth.php