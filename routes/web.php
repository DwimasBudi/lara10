<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardPostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/categories', function () {
    return view('categories',[
        'title'=>'Categories',
        'active'=>'categories',
        'categories'=> Category::orderBy('name')->get()
    ]);
});
Route::get('/dashboard/user', function () {
    $rand= 1;
    $randFix= $rand*3;
    return view('dashboard.user',[
        'title'=>'User',
        'active'=>'categories',
        'users'=> User::inRandomOrder($randFix)->paginate(1)
    ]);
})->middleware('admin');


Route::get('/',[PostController::class,'index']);

Route::get('/home',[PostController::class,'index']);

Route::get('/posts',[PostController::class,'index']);
Route::get('/post/{post}',[PostController::class,'show']);

Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login',[LoginController::class,'index'])->name('login')->middleware('guest');
Route::post('/login',[LoginController::class,'authenticate']);

Route::post('/logout',[LoginController::class,'logout']);

//Route Dashboard

Route::get('/dashboard', function () {
    return view('dashboard.index',[
        'post'=>Post::count(),
        'user'=>User::count(),
        'category'=>Category::count()
    ]);
})->middleware('auth');

Route::get('dashboard/posts/checkSlug', [DashboardPostController::class, 'checkSlug'])->middleware('auth');
Route::resource('dashboard/posts', DashboardPostController::class)->middleware('auth');
// Route::resource('dashboard/categories', AdminCategoryController::class)->except('show')->middleware('auth');
Route::resource('dashboard/categories', CategoryController::class)->middleware('admin');