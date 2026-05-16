<?php

use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\LikeController as AdminLikeController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // SNS
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // 翻訳
    Route::post('/translate', [TranslationController::class, 'translate'])->name('translate')->middleware('throttle:30,1');

    // いいね
    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/posts/{post}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

    // コメント
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // ユーザー
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('users.unfollow');

    // 商品
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // カート
    Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
    Route::post('/carts/{product}', [CartController::class, 'store'])->name('carts.store');
    Route::delete('/carts/{product}', [CartController::class, 'destroy'])->name('carts.destroy');

    // 注文
    Route::get('/orders/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 管理者
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

        // 注文
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');

        // ユーザー
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // ショップ
        Route::get('shops', [AdminShopController::class, 'index'])->name('shops.index');
        Route::get('shops/{shop}', [AdminShopController::class, 'show'])->name('shops.show');
        Route::post('shops/{shop}/suspend', [AdminShopController::class, 'suspend'])->name('shops.suspend');
        Route::delete('shops/{shop}', [AdminShopController::class, 'destroy'])->name('shops.destroy');

        // 商品
        Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [AdminProductController::class, 'show'])->name('products.show');
        Route::post('products/{product}/suspend', [AdminProductController::class, 'suspend'])->name('products.suspend');
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        // 投稿
        Route::get('posts', [AdminPostController::class, 'index'])->name('posts.index');
        Route::get('posts/{post}', [AdminPostController::class, 'show'])->name('posts.show');
        Route::post('posts/{post}/hide', [AdminPostController::class, 'hide'])->name('posts.hide');
        Route::delete('posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');

        // コメント
        Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
        Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

        // いいね
        Route::get('likes', [AdminLikeController::class, 'index'])->name('likes.index');
    });
});

require __DIR__.'/auth.php';
