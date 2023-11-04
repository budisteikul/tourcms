<?php


//Booking
Route::get('/cms/booking/checkout','budisteikul\tourcms\Controllers\BookingController@checkout')->middleware(['web','auth','verified','CoreMiddleware']);
Route::resource('/cms/booking','budisteikul\tourcms\Controllers\BookingController',[ 'names' => 'route_tourcms_booking' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Schedule
Route::resource('/cms/schedule','budisteikul\tourcms\Controllers\ScheduleController',[ 'names' => 'route_tourcms_schedule' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Completed
Route::resource('/cms/completed','budisteikul\tourcms\Controllers\CompletedController',[ 'names' => 'route_tourcms_completed' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Category
Route::get('/cms/category/structure','budisteikul\tourcms\Controllers\CategoryController@structure')->middleware(['web','auth','verified','CoreMiddleware']);
Route::resource('/cms/category','budisteikul\tourcms\Controllers\CategoryController',[ 'names' => 'route_tourcms_category' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

//Product
Route::resource('/cms/product','budisteikul\tourcms\Controllers\ProductController',[ 'names' => 'route_tourcms_product' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

//Channel	
Route::resource('/cms/channel','budisteikul\tourcms\Controllers\ChannelController',[ 'names' => 'route_tourcms_channel' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

//Review
Route::resource('/cms/review','budisteikul\tourcms\Controllers\ReviewController',[ 'names' => 'route_tourcms_review' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

//Page
Route::resource('/cms/page','budisteikul\tourcms\Controllers\PageController',[ 'names' => 'route_tourcms_page' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

//Voucher	
Route::resource('/cms/voucher','budisteikul\tourcms\Controllers\VoucherController',[ 'names' => 'route_tourcms_voucher' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

//Past
Route::resource('/cms/remittance','budisteikul\tourcms\Controllers\RemittanceController',[ 'names' => 'route_tourcms_remittance' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

//CloseOut	
Route::resource('/cms/closeout','budisteikul\tourcms\Controllers\CloseOutController',[ 'names' => 'route_tourcms_closeout' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

