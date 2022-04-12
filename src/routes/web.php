<?php

//Category
Route::get('/cms/category/structure','budisteikul\tourcms\Controllers\CategoryController@structure')->middleware(['web','auth','verified','CoreMiddleware']);
Route::resource('/cms/category','budisteikul\tourcms\Controllers\CategoryController',[ 'names' => 'route_tourcms_category' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Product
Route::resource('/cms/product','budisteikul\tourcms\Controllers\ProductController',[ 'names' => 'route_tourcms_product' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Channel	
Route::resource('/cms/channel','budisteikul\tourcms\Controllers\ChannelController',[ 'names' => 'route_tourcms_channel' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Review
Route::resource('/cms/review','budisteikul\tourcms\Controllers\ReviewController',[ 'names' => 'route_tourcms_review' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Page
Route::resource('/cms/page','budisteikul\tourcms\Controllers\PageController',[ 'names' => 'route_tourcms_page' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Booking
Route::get('/cms/booking/checkout','budisteikul\tourcms\Controllers\BookingController@checkout')->middleware(['web','auth','verified','CoreMiddleware']);
Route::resource('/cms/booking','budisteikul\tourcms\Controllers\BookingController',[ 'names' => 'route_tourcms_booking' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Vendor	
Route::resource('/cms/vendor','budisteikul\tourcms\Controllers\VendorController',[ 'names' => 'route_tourcms_vendor' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Voucher	
Route::resource('/cms/voucher','budisteikul\tourcms\Controllers\VoucherController',[ 'names' => 'route_tourcms_voucher' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

//Disbursement
Route::get('/cms/disbursement/search/{id}','budisteikul\tourcms\Controllers\DisbursementController@search')->middleware(['web','auth','verified','CoreMiddleware']);	
Route::resource('/cms/disbursement','budisteikul\tourcms\Controllers\DisbursementController',[ 'names' => 'route_tourcms_disbursement' ])
	->middleware(['web','auth','verified','CoreMiddleware']);

