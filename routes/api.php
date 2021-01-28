<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\GoogleController;
use App\Http\Controllers\API\Auth\VerifyController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\ForgetPasswordController;
use App\Http\Controllers\API\Auth\ProfileController;
use App\Http\Controllers\API\categories\CategoriesController;
use App\Http\Controllers\API\restaurants\RestaurantController;
use App\Http\Controllers\API\order\OrderController;
use App\Http\Controllers\API\order\DriverOrderController;
use App\Http\Controllers\API\track\TrackController;
use App\Http\Controllers\API\cart\CartController;
use App\Http\Controllers\API\drivers\Auth\DriverRegisterController;
use App\Http\Controllers\API\drivers\Auth\ActiveController;
use App\Http\Controllers\API\drivers\Location\LocationController;
use App\Http\Controllers\API\chats\ChatController;
use App\Http\Controllers\API\notification\NotifcationController;
use App\Http\Controllers\Admin\users\UserController;
use App\Http\Controllers\Admin\restaurants\RestaurantsController;
use App\Http\Controllers\Admin\content\ContentController;
use App\Http\Controllers\Admin\cuisines\CuisinesController;
use App\Http\Controllers\Admin\categories\CategoryController;
use App\Http\Controllers\Admin\drivers\DriversController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Restaurant\Menu\MenuController;
use App\Http\Controllers\Restaurant\Products\ProductsController;
use App\Http\Controllers\Restaurant\Auth\RestRegisterController;
use App\Http\Controllers\Restaurant\Auth\RestLoginController;
use App\Http\Controllers\Restaurant\Orders\RestOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/google', [GoogleController::class, 'register']);
    Route::post('/verify', [VerifyController::class, 'index']);
    Route::post('/forget/password', [ForgetPasswordController::class, 'index']);
    Route::post('/reset/password', [ForgetPasswordController::class, 'update']);  
    Route::prefix('driver')->group(function () {
        Route::post('/register', [DriverRegisterController::class, 'create']);
    });
    Route::get('/show/all/cuisine', [CuisinesController::class, 'index']);
    Route::post('/create', [RestRegisterController::class, 'create']);
    Route::post('/restaurant/login', [RestLoginController::class, 'index']);
    Route::post('/admin/login', [AdminLoginController::class, 'index']);
    Route::post('/admin/change/password', [AdminLoginController::class, 'update']);
});

Route::prefix('restaurant')->group(function () {
    Route::post('/all', [LoginController::class, 'index']);
    Route::post('/menu', [RegisterController::class, 'register']);
});

Route::prefix('categories')->group(function () {
    Route::get('/all', [CategoriesController::class, 'index']);
});

Route::prefix('restaurants')->group(function () {
    Route::get('/all', [RestaurantController::class, 'index']);
});
Route::middleware(['auth:api'])->group(function () {
    Route::prefix('cart')->group(function () {
        Route::post('/insert', [CartController::class, 'insert']);
    });
    Route::prefix('order')->group(function () {
        Route::post('/insert', [OrderController::class, 'insert']);
        Route::get('/insert/drivers', [DriverOrderController::class, 'insert'])->name('pendingDriverOrders');;
        Route::get('/user/all', [OrderController::class, 'show']);
        Route::post('/cancel', [OrderController::class, 'remove']);
        Route::get('/track/{driver_id}/location', [TrackController::class, 'show']);
        Route::post('/update', [OrderController::class, 'update']);
        Route::post('/timing', [OrderController::class, 'timing']);
    });
    Route::prefix('driver')->group(function () {
        Route::post('/update/active/status', [ActiveController::class, 'update']);
        Route::get('/show/pending/order', [DriverOrderController::class, 'show']);
        Route::post('/accept/order', [DriverOrderController::class, 'create']);
        Route::get('/order/info', [DriverOrderController::class, 'index']);
        Route::post('/current/location', [LocationController::class, 'insert']);
        Route::post('/order/update', [DriverOrderController::class, 'update']);
    });
    Route::prefix('chat')->group(function () {
        Route::post('/create/room', [ChatController::class, 'create']);
        Route::post('/send/message', [ChatController::class, 'insert']);
        Route::get('/room/{room_id}/messages', [ChatController::class, 'show']);
    });
    Route::prefix('content')->group(function () {
        Route::get('/all', [ContentController::class, 'index']);
        Route::get('/driver/support', [ContentController::class, 'index']);
    });
    Route::prefix('profile')->group(function () {
        Route::get('/show', [ProfileController::class, 'show']);
        Route::put('/update', [ProfileController::class, 'update']);
    });
    
    Route::prefix('notification')->group(function () {
        Route::get('/insert/{user_id}/{order_code}/{status}', [NotifcationController::class, 'insert']);
        Route::get('/show', [NotifcationController::class, 'show']);
    });
    Route::group(['prefix' => 'restaurant',  'middleware' => 'restaurant'], function(){
        Route::prefix('menu')->group(function () {
            Route::post('/create', [MenuController::class, 'create']);
            Route::get('/show', [MenuController::class, 'show']);
            Route::post('/remove', [MenuController::class, 'delete']);
            Route::put('/update', [MenuController::class, 'update']);
        });
        Route::prefix('products')->group(function () {
            Route::post('/create', [ProductsController::class, 'create']);
            Route::get('/show', [ProductsController::class, 'show']);
            Route::post('/remove', [ProductsController::class, 'delete']);
            Route::post('/update', [ProductsController::class, 'update']);
        });
        Route::prefix('orders')->group(function () {
            Route::get('/show', [RestOrderController::class, 'show']);
            Route::post('/update', [RestOrderController::class, 'update']);
        });
    });
});

Route::prefix('admin')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/all', [UserController::class, 'index']);
        Route::post('/update/active', [UserController::class, 'update']);
        Route::post('/remove', [UserController::class, 'remove']);
    });
    Route::prefix('restaurants')->group(function () {
        Route::get('/all', [RestaurantsController::class, 'index']);
        Route::post('/update/active', [RestaurantsController::class, 'update']);
        Route::post('/remove', [RestaurantsController::class, 'remove']);
    });
    Route::prefix('drivers')->group(function () {
        Route::get('/all', [DriversController::class, 'index']);
        Route::post('/update/active', [DriversController::class, 'update']);
        Route::post('/remove', [DriversController::class, 'remove']);
    });
    Route::prefix('cuisines')->group(function () {
        Route::get('/all', [CuisinesController::class, 'index']);
        Route::post('/create', [CuisinesController::class, 'create']);
        Route::post('/remove', [CuisinesController::class, 'remove']);
    });
    Route::prefix('categories')->group(function () {
        Route::get('/all', [CategoryController::class, 'index']);
        Route::post('/create', [CategoryController::class, 'create']);
        Route::post('/insert', [CategoryController::class, 'insert']);
        Route::post('/remove', [CategoryController::class, 'remove']);
    });
    Route::prefix('content')->group(function () {
        Route::get('/all', [ContentController::class, 'index']);
        Route::put('/update', [ContentController::class, 'update']);
        Route::put('/fees', [ContentController::class, 'fees']);
    });
    
});
