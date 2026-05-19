<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\StartupController;
use App\Http\Controllers\FounderDashboardController;
use App\Http\Controllers\ProfileController;

// Default welcome page can redirect to login if guest, or dashboard if logged in
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/admin', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
});

// Protected Routes (Logged in Users Only)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Shared Dashboard - Dynamic role-based redirection
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        if ($role === 'admin') {
            return redirect()->route('admin.panel');
        } elseif ($role === 'founder') {
            return redirect()->route('founder.panel');
        } elseif ($role === 'investor') {
            return redirect()->route('investor.panel');
        }
        return view('dashboard');
    })->name('dashboard');

    // Role-specific Routes Example (Protected by RoleMiddleware)
    // You can add logic to controllers here, but for this beginner guide, we'll just return a view or closure

    Route::middleware('role:founder')->group(function () {
        Route::get('/founder-panel', [FounderDashboardController::class, 'index'])->name('founder.panel');

        // Startup Management Routes
        Route::get('/startups', [StartupController::class, 'index'])->name('startups.index');
        Route::get('/startups/create', [StartupController::class, 'create'])->name('startups.create');
        Route::post('/startups', [StartupController::class, 'store'])->name('startups.store');
        Route::get('/startups/{id}', [StartupController::class, 'show'])->name('startups.show');
        Route::get('/startups/{id}/edit', [StartupController::class, 'edit'])->name('startups.edit');
        Route::put('/startups/{id}', [StartupController::class, 'update'])->name('startups.update');
        Route::delete('/startups/{id}', [StartupController::class, 'destroy'])->name('startups.destroy');
        
    });

    Route::middleware('role:investor')->group(function () {
        Route::get('/investor-panel', [\App\Http\Controllers\Investor\InvestorDashboardController::class, 'index'])->name('investor.panel');

        Route::get('/investor-panel/startups', [\App\Http\Controllers\Investor\StartupController::class, 'index'])->name('investor.startups.index');
        Route::get('/investor-panel/startups/{id}', [\App\Http\Controllers\Investor\StartupController::class, 'show'])->name('investor.startups.show');
        
        // Investment System Routes
        Route::post('/investor-panel/startups/{id}/invest', [\App\Http\Controllers\Investor\InvestmentController::class, 'store'])->name('investor.startups.invest');
        Route::get('/investor-panel/portfolio', [\App\Http\Controllers\Investor\InvestmentController::class, 'history'])->name('investor.portfolio.index');
        
        // Bookmark Routes
        Route::post('/investor-panel/startups/{id}/bookmark', [\App\Http\Controllers\Investor\BookmarkController::class, 'toggle'])->name('investor.bookmarks.toggle');
        Route::get('/investor-panel/saved', [\App\Http\Controllers\Investor\BookmarkController::class, 'index'])->name('investor.bookmarks.index');
    });

    // Shared Discussion & Profile Routes (Accessible by all logged-in users)
    Route::middleware('auth')->group(function () {
        Route::post('/startups/{id}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
        Route::delete('/comments/{id}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
        
        // Review Routes
        Route::post('/startups/{id}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
        
        // Notification Routes
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

        // Profile Routes (Universal)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin-panel', [StartupController::class, 'adminIndex'])->name('admin.panel');
        Route::get('/admin/startups', [StartupController::class, 'adminStartupsIndex'])->name('admin.startups.index');
        Route::post('/admin/startups/{id}/approve', [StartupController::class, 'approve'])->name('admin.startups.approve');
        Route::post('/admin/startups/{id}/reject', [StartupController::class, 'reject'])->name('admin.startups.reject');
        Route::delete('/admin/startups/{id}', [StartupController::class, 'adminDestroy'])->name('admin.startups.destroy');

        // User Management Routes
        Route::get('/admin/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/{id}/toggle-block', [\App\Http\Controllers\AdminUserController::class, 'toggleBlock'])->name('admin.users.toggle_block');
        Route::delete('/admin/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        // Investment Monitoring Routes
        Route::get('/admin/investments', [\App\Http\Controllers\AdminInvestmentController::class, 'index'])->name('admin.investments.index');

        // Analytics Dashboard Routes
        Route::get('/admin/analytics', [\App\Http\Controllers\AdminAnalyticsController::class, 'index'])->name('admin.analytics.index');
    });
});