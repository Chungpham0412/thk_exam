<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\Admin\TopController as AdminTopController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/** user screen */
Route::get('/', [TopController::class, 'index'])->name('top');
Route::get('/{prefecture_name_alpha}/hotellist', [HotelController::class, 'showList'])->name('hotelList');
Route::get('/hotel/{hotel_id}', [HotelController::class, 'showDetail'])->name('hotelDetail');

/** admin screen */
Route::get('/admin', [AdminTopController::class, 'index'])->name('adminTop');
Route::get('/admin/hotel/search', [AdminHotelController::class, 'showSearch'])->name('adminHotelSearchPage');
Route::get('/admin/hotel/{hotel_id}/update', [AdminHotelController::class, 'showEdit'])->name('adminHotelEditPage');
Route::get('/admin/hotel/create', [AdminHotelController::class, 'showCreate'])->name('adminHotelCreatePage');
Route::get('/admin/hotel/search/result', function () {
    return redirect()->route('adminHotelSearchPage');
});
Route::post('/admin/hotel/search/result', [AdminHotelController::class, 'searchResult'])->name('adminHotelSearchResult');
Route::post('/admin/hotel/edit/{hotel_id}', [AdminHotelController::class, 'edit'])->name('adminHotelEditProcess');
Route::post('/admin/hotel/create', [AdminHotelController::class, 'create'])->name('adminHotelCreateProcess');
Route::post('/admin/hotel/delete', [AdminHotelController::class, 'delete'])->name('adminHotelDeleteProcess');
//Booking
Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
Route::get('/admin/bookings/search', function () {
    return redirect()->route('admin.bookings.index');
});
Route::post('/admin/bookings/search', [AdminBookingController::class, 'searchResult'])->name('admin.bookings.search');
Route::get('/admin/bookings/create', [AdminBookingController::class, 'create'])->name('admin.bookings.create');
Route::post('/admin/bookings/store', [AdminBookingController::class, 'store'])->name('admin.bookings.store');
Route::get('/admin/bookings/{booking_id}/edit', [AdminBookingController::class, 'edit'])->name('admin.bookings.edit');
Route::put('/admin/bookings/{booking_id}', [AdminBookingController::class, 'update'])->name('admin.bookings.update');
Route::delete('/admin/bookings/{booking_id}', [AdminBookingController::class, 'destroy'])->name('admin.bookings.destroy');
