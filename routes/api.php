<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\api\BookingController;
use App\Http\Controllers\Api\ParkingController;
use App\Http\Controllers\Api\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



  // auth and logout
Route::post('/register',[AuthController::class , 'register']);
Route::post('/auth-access-token' , [AuthController::class , 'store']);

 Route::delete('logout/{token?}', [AuthController::class, 'destroy'])
 ->middleware('auth:sanctum');

 // routes/api.php
Route::prefix('/garages')->group(function () {
    Route::get('/', [PlaceController::class, 'index']);
    Route::get('/{id}', [PlaceController::class, 'shaw']);
    Route::get('/search', [PlaceController::class, 'searchByName']); // البحث بالاسم
    Route::post('/', [PlaceController::class, 'store']);
});
Route::prefix('garages/{place}')->group(function () {

    // إنشاء ركن جديد
    Route::post('/parking', [ParkingController::class, 'store']);

    // عرض كل الركنات
    Route::get('/parking', [ParkingController::class, 'index']);

    // حجز ركنة
    Route::post('/parking/{spot}/reserve', [ParkingController::class, 'reserve']);

    // إلغاء الحجز
    Route::post('/parking/{spot}/release', [ParkingController::class, 'release']);

    // حذف ركنة
    Route::delete('/parking/{spot}', [ParkingController::class, 'destroy']);
});

Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
});
  //  pay
Route::post('/bookings/pay', [BookingController::class, 'payAndBook']);


Route::get('/users/{user}/bookings', [BookingController::class, 'index']);


 Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', [ProfileController::class, 'show']); // للحصول على بيانات الملف الشخصي
    Route::put('/{id}', [ProfileController::class, 'update']); // لتحديث الملف الشخصي
    Route::patch('/{id}', [ProfileController::class, 'update']); // لتحديث جزئي
    Route::delete('/{id}', [ProfileController::class, 'destroy']); // لحذف الحساب


});



