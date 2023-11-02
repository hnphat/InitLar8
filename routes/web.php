<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $pass = Hash::make('admin');
    return view('server.welcome', ['pass' => $pass]);
});

Route::get('/admin', function() {
    if (Auth::check())
        return view('server.mainpanel');
    return view('login');
})->name('login_panel');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', function(){
    Auth::logout();
    return view('login');
});

Route::prefix('management')->middleware(['m_login'])->group(function(){
    Route::view('/mainpanel', 'server.mainpanel');
    Route::prefix('account')->middleware(['m_login'])->group(function(){
        Route::name('quanlytaikhoan.')->group(function(){
            Route::get('/',[UserController::class, 'index'])->name('panel');
            Route::get('/getdata',[UserController::class, 'loadData']);
            Route::post('/post',[UserController::class, 'postData'])->name('post');
            Route::post('/delete',[UserController::class, 'delete'])->name('delete');
            Route::get('/getedit',[UserController::class, 'getEdit'])->name('getedit');
            Route::post('/postedit',[UserController::class, 'postEdit'])->name('postedit');
        });        
    });
});
