    <?php

use App\Http\Controllers\Admin\{
    DashboardController,
    ReportController,
    UserController,
    ShipmentController,
    AdminTransactionController,
    AuthController as AdminAuthController
};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    CategoryController,
    TransactionController
};

    // Landing page
    Route::get('/', [AdminAuthController::class, 'landing'])->name('landing');

    // Admin login & register (guest only)
    Route::prefix('admin')->name('admin.')->middleware('guest')->group(function () {
        Route::get('/loginadmin', [AdminAuthController::class, 'showLoginForm'])->name('login.form');
        Route::post('/loginadmin', [AdminAuthController::class, 'login'])->name('login.submit');
        Route::get('/registeradmin', [AdminAuthController::class, 'showRegisterForm'])->name('register.form');
        Route::post('/registeradmin', [AdminAuthController::class, 'register'])->name('register.submit');
    });

    // Admin authenticated routes
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('settings', [AdminAuthController::class, 'editSettings'])->name('settings');
        Route::put('settings', [AdminAuthController::class, 'updateSettings'])->name('settings.update');

        // Produk (CRUD via form di admin/products/form.blade.php)
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // User management routes
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::post('users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');



        // Kategori
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        
        // Transaksi
    Route::get('transactions/{id}', [AdminTransactionController::class, 'show'])->name('transactions.show');
    // Route untuk menampilkan detail transaksi
    Route::get('transactions/{id}/details', [AdminTransactionController::class, 'showTransactionDetails'])->name('transactions.showDetails');

    Route::post('transactions/{id}/update-status', [AdminTransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::post('transactions/{id}/validate-payment', [AdminTransactionController::class, 'validatePayment'])->name('transactions.validatePayment');
    // Rute untuk menampilkan transaksi
    Route::get('transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');

    // Rute untuk update status menjadi 'processed'
    Route::post('transactions/{id}/processed', [AdminTransactionController::class, 'updateToProcessed'])->name('transactions.updateToProcessed');

    // Rute untuk update status menjadi 'shipped'
    Route::post('transactions/{id}/shipped', [AdminTransactionController::class, 'updateToShipped'])->name('transactions.updateToShipped');


    //Deliveries
    Route::get('shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    // Admin routes untuk mengubah status transaksi menjadi 'delivered'
    Route::put('transactions/{transaction}/mark-delivered', [AdminTransactionController::class, 'markDelivered'])->name('transactions.markDelivered');
});
