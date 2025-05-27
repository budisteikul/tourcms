<?php


//Booking

Route::get('/cms/booking/question/{id}/edit','budisteikul\tourcms\Controllers\BookingController@question_edit')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::post('/cms/booking/question/{id}','budisteikul\tourcms\Controllers\BookingController@question_update')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::get('/cms/booking/checkout','budisteikul\tourcms\Controllers\BookingController@checkout')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::resource('/cms/booking','budisteikul\tourcms\Controllers\BookingController',[ 'names' => 'route_tourcms_booking' ])
	->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);

//Schedule
Route::resource('/cms/schedule','budisteikul\tourcms\Controllers\ScheduleController',[ 'names' => 'route_tourcms_schedule' ])
	->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);

//Shoppingcart
	Route::get('/api/activity/{activityId}/calendar/json/{year}/{month}', 'budisteikul\tourcms\Controllers\APIController@snippetscalendar')->middleware(['SettingMiddleware']);
	Route::post('/api/activity/invoice-preview', 'budisteikul\tourcms\Controllers\APIController@snippetsinvoice')->middleware(['SettingMiddleware']);
	Route::post('/api/activity/remove', 'budisteikul\tourcms\Controllers\APIController@removebookingid')->middleware(['SettingMiddleware']);
	Route::post('/api/widget/cart/session/{id}/activity', 'budisteikul\tourcms\Controllers\APIController@addshoppingcart')->middleware(['SettingMiddleware']);
	Route::post('/api/shoppingcart', 'budisteikul\tourcms\Controllers\APIController@shoppingcart')->middleware(['SettingMiddleware']);
	Route::post('/api/promocode', 'budisteikul\tourcms\Controllers\APIController@applypromocode')->middleware(['SettingMiddleware']);
	Route::post('/api/promocode/remove', 'budisteikul\tourcms\Controllers\APIController@removepromocode')->middleware(['SettingMiddleware']);

//Completed
Route::resource('/cms/completed','budisteikul\tourcms\Controllers\CompletedController',[ 'names' => 'route_tourcms_completed' ])
	->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);

//Category
Route::get('/cms/category/structure','budisteikul\tourcms\Controllers\CategoryController@structure')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::resource('/cms/category','budisteikul\tourcms\Controllers\CategoryController',[ 'names' => 'route_tourcms_category' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Product
Route::resource('/cms/product','budisteikul\tourcms\Controllers\ProductController',[ 'names' => 'route_tourcms_product' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Channel	
Route::resource('/cms/channel','budisteikul\tourcms\Controllers\ChannelController',[ 'names' => 'route_tourcms_channel' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Order
Route::get('/cms/fin/orders/create/dft','budisteikul\tourcms\Controllers\OrderController@create_dft')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::get('/cms/fin/orders/create/uft','budisteikul\tourcms\Controllers\OrderController@create_uft')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::get('/cms/fin/orders/create/tat','budisteikul\tourcms\Controllers\OrderController@create_tat')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::get('/cms/fin/orders/create/jnft','budisteikul\tourcms\Controllers\OrderController@create_jnft')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::get('/cms/fin/orders/create/jmft','budisteikul\tourcms\Controllers\OrderController@create_jmft')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::resource('/cms/fin/orders','budisteikul\tourcms\Controllers\OrderController',[ 'names' => 'route_tourcms_orders' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Salary
Route::resource('/cms/salary','budisteikul\tourcms\Controllers\SalaryController',[ 'names' => 'route_tourcms_salary' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Petty Cash
Route::get('/cms/fin/pettycash/saldo','budisteikul\tourcms\Controllers\PettyCashController@saldo')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::resource('/cms/fin/pettycash','budisteikul\tourcms\Controllers\PettyCashController',[ 'names' => 'route_tourcms_pettycash' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Expenses
Route::resource('/cms/fin/expenses','budisteikul\tourcms\Controllers\ExpensesController',[ 'names' => 'route_tourcms_expenses' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Revenue
Route::resource('/cms/fin/revenue','budisteikul\tourcms\Controllers\RevenueController',[ 'names' => 'route_tourcms_revenue' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Review
Route::resource('/cms/review','budisteikul\tourcms\Controllers\ReviewController',[ 'names' => 'route_tourcms_review' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Page
Route::resource('/cms/page','budisteikul\tourcms\Controllers\PageController',[ 'names' => 'route_tourcms_page' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Cancel
Route::resource('/cms/cancel','budisteikul\tourcms\Controllers\CancelController',[ 'names' => 'route_tourcms_cancel' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Voucher	
Route::resource('/cms/voucher','budisteikul\tourcms\Controllers\VoucherController',[ 'names' => 'route_tourcms_voucher' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Past
Route::resource('/cms/remittance','budisteikul\tourcms\Controllers\RemittanceController',[ 'names' => 'route_tourcms_remittance' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//CloseOut	
Route::resource('/cms/closeout','budisteikul\tourcms\Controllers\CloseOutController',[ 'names' => 'route_tourcms_closeout' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//CloseOut V2
Route::resource('/cms/closeoutv2','budisteikul\tourcms\Controllers\CloseOutV2Controller',[ 'names' => 'route_tourcms_closeout_v2' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Setting
Route::resource('/cms/setting','budisteikul\tourcms\Controllers\SettingController',[ 'names' => 'route_tourcms_setting' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Whatsapp
Route::post('/cms/contact/clear_messages','budisteikul\tourcms\Controllers\ContactController@clear_messages')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::post('/cms/contact/template','budisteikul\tourcms\Controllers\ContactController@template')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::post('/cms/contact/message','budisteikul\tourcms\Controllers\ContactController@message')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::resource('/cms/contact','budisteikul\tourcms\Controllers\ContactController',[ 'names' => 'route_tourcms_contact' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

//Partner	
Route::get('/cms/partner/report','budisteikul\tourcms\Controllers\PartnerController@report')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);
Route::resource('/cms/partner','budisteikul\tourcms\Controllers\PartnerController',[ 'names' => 'route_tourcms_partner' ])
	->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);


//Download vCard
Route::get('/cms/vcard/download/{id}','budisteikul\tourcms\Controllers\VCardController@index')->middleware(['SettingMiddleware']);

// ==================================
// API
	Route::get('/api/index_jscript', 'budisteikul\tourcms\Controllers\APIController@config')->middleware(['SettingMiddleware']);
	Route::get('/api/config', 'budisteikul\tourcms\Controllers\APIController@config')->middleware(['SettingMiddleware']);
	Route::get('/api/{sessionId}/navbar', 'budisteikul\tourcms\Controllers\APIController@navbar')->middleware(['SettingMiddleware']);
	Route::get('/api/tawkto/{id}', 'budisteikul\tourcms\Controllers\APIController@tawkto')->middleware(['SettingMiddleware']);

	//Review
	Route::post('/api/review', 'budisteikul\tourcms\Controllers\APIController@review')->middleware(['SettingMiddleware']);
	Route::get('/api/review/count', 'budisteikul\tourcms\Controllers\APIController@review_count')->middleware(['SettingMiddleware']);
	Route::get('/api/review/jscript', 'budisteikul\tourcms\Controllers\APIController@review_jscript')->middleware(['SettingMiddleware']);

	//Schedule
	Route::post('/api/schedule', 'budisteikul\tourcms\Controllers\APIController@schedule')->middleware(['SettingMiddleware']);
	Route::get('/api/schedule/jscript', 'budisteikul\tourcms\Controllers\APIController@schedule_jscript')->middleware(['SettingMiddleware']);

	//Page
	Route::get('/api/page/{slug}', 'budisteikul\tourcms\Controllers\APIController@page')->middleware(['SettingMiddleware']);

	//Category
    Route::get('/api/categories', 'budisteikul\tourcms\Controllers\APIController@categories')->middleware(['SettingMiddleware']);
    Route::get('/api/category/{slug}', 'budisteikul\tourcms\Controllers\APIController@category')->middleware(['SettingMiddleware']);

    //Product
    Route::post('/api/product/add', 'budisteikul\tourcms\Controllers\APIController@product_add')->middleware(['SettingMiddleware']);
    Route::post('/api/product/remove', 'budisteikul\tourcms\Controllers\APIController@product_remove')->middleware(['SettingMiddleware']);
    Route::get('/api/product/{slug}', 'budisteikul\tourcms\Controllers\APIController@product')->middleware(['SettingMiddleware']);
	Route::get('/api/product/{slug}/{sessionId}/product_jscript', 'budisteikul\tourcms\Controllers\APIController@product_jscript')->middleware(['SettingMiddleware']);





	//Create Payment
	Route::post('/api/payment/checkout', 'budisteikul\tourcms\Controllers\PaymentController@checkout')->middleware(['SettingMiddleware']);
	//Stripe
	Route::get('/api/payment/stripe/jscript/{sessionId}', 'budisteikul\tourcms\Controllers\PaymentController@stripe_jscript')->middleware(['SettingMiddleware']);
	Route::post('/api/payment/stripe', 'budisteikul\tourcms\Controllers\PaymentController@createpaymentstripe')->middleware(['SettingMiddleware']);
	//Xendit
	Route::get('/api/payment/xendit/jscript/{sessionId}', 'budisteikul\tourcms\Controllers\PaymentController@xendit_jscript')->middleware(['SettingMiddleware']);
	Route::post('/api/payment/xendit', 'budisteikul\tourcms\Controllers\PaymentController@createpaymentxendit')->middleware(['SettingMiddleware']);
	//Paypal
	Route::get('/api/payment/paypal/jscript/{sessionId}', 'budisteikul\tourcms\Controllers\PaymentController@paypal_jscript')->middleware(['SettingMiddleware']);
	Route::post('/api/payment/paypal', 'budisteikul\tourcms\Controllers\PaymentController@createpaymentpaypal')->middleware(['SettingMiddleware']);
	//QRIS
	Route::get('/api/payment/qris/jscript/{sessionId}', 'budisteikul\tourcms\Controllers\PaymentController@qris_jscript')->middleware(['SettingMiddleware']);
	//Wise
	Route::get('/api/payment/wise/jscript/{sessionId}', 'budisteikul\tourcms\Controllers\PaymentController@wise_jscript')->middleware(['SettingMiddleware']);

	//Checkout
	Route::get('/api/checkout/jscript', 'budisteikul\tourcms\Controllers\APIController@checkout_jscript')->middleware(['SettingMiddleware']);

	//Receipt
	Route::get('/api/receipt/jscript', 'budisteikul\tourcms\Controllers\APIController@receipt_jscript')->middleware(['SettingMiddleware']);
	Route::get('/api/receipt/{sessionId}/{confirmationCode}', 'budisteikul\tourcms\Controllers\APIController@receipt')->middleware(['SettingMiddleware']);

	//Cancellation
	Route::post('/api/cancel/{sessionId}/{confirmationCode}', 'budisteikul\tourcms\Controllers\APIController@cancellation')->middleware(['SettingMiddleware']);

	//Callback Payment
	Route::post('/api/payment/stripe/confirm', 'budisteikul\tourcms\Controllers\CallbackController@confirmpaymentstripe')->middleware(['SettingMiddleware']);
	Route::post('/api/payment/paypal/confirm', 'budisteikul\tourcms\Controllers\CallbackController@confirmpaymentpaypal')->middleware(['SettingMiddleware']);
	Route::post('/api/payment/xendit/confirm', 'budisteikul\tourcms\Controllers\CallbackController@confirmpaymentxendit')->middleware(['SettingMiddleware']);
	
	//PDF
	Route::get('/api/pdf/manual/{sessionId}/Manual-{id}.pdf', 'budisteikul\tourcms\Controllers\APIController@manual')->middleware(['SettingMiddleware']);
	Route::get('/api/pdf/invoice/{sessionId}/Invoice-{id}.pdf', 'budisteikul\tourcms\Controllers\APIController@invoice')->middleware(['SettingMiddleware']);
	Route::get('/api/pdf/ticket/{sessionId}/Ticket-{id}.pdf', 'budisteikul\tourcms\Controllers\APIController@ticket')->middleware(['SettingMiddleware']);
	Route::get('/api/pdf/instruction/{sessionId}/Instruction-{id}.pdf', 'budisteikul\tourcms\Controllers\APIController@instruction')->middleware(['SettingMiddleware']);

	//Download
	Route::get('/api/qrcode/{sessionId}/{id}', 'budisteikul\tourcms\Controllers\APIController@downloadQrcode')->middleware(['SettingMiddleware']);

	//Last Order
	Route::get('/api/ticket/{sessionId}/last-order', 'budisteikul\tourcms\Controllers\APIController@last_order')->middleware(['SettingMiddleware']);

	// Webhook
	Route::post('/webhook/{webhook_app}', 'budisteikul\tourcms\Controllers\WebhookController@webhook')->middleware(['SettingMiddleware']);
	Route::get('/webhook/{webhook_app}', 'budisteikul\tourcms\Controllers\WebhookController@webhook')->middleware(['SettingMiddleware']);

	//TASK
	Route::post('/task', 'budisteikul\tourcms\Controllers\TaskController@task')->middleware(['SettingMiddleware']);

	//LOG
	Route::post('/logger/{identifier}', 'budisteikul\tourcms\Controllers\LogController@log')->middleware(['SettingMiddleware']);

	//Billing Tools
	Route::post('/api/tool/billing/{sessionId}', 'budisteikul\tourcms\Controllers\ToolController@billing')->middleware(['SettingMiddleware']);
	Route::post('/api/tool/bin', 'budisteikul\tourcms\Controllers\ToolController@bin')->middleware(['SettingMiddleware']);



Route::get('/cms/fin/transactions/categories/structure','budisteikul\tourcms\Controllers\FinCategoryController@structure')->middleware(['web','auth','verified','CoreMiddleware']);

Route::resource('/cms/fin/transactions/categories','budisteikul\tourcms\Controllers\FinCategoryController',[ 'names' => 'route_fin_categories' ])
    ->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::post('/cms/fin/transactions/payment','budisteikul\tourcms\Controllers\TransactionController@post_payment')->middleware(['web','auth','verified','CoreMiddleware']);
Route::get('/cms/fin/transactions/payment','budisteikul\tourcms\Controllers\TransactionController@get_payment')->middleware(['web','auth','verified','CoreMiddleware']);
    
Route::resource('/cms/fin/transactions','budisteikul\tourcms\Controllers\TransactionController',[ 'names' => 'route_fin_transactions' ])
    ->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::resource('/cms/fin/profitloss', 'budisteikul\tourcms\Controllers\SalesController',[ 'names' => 'route_fin_profitloss' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::resource('/cms/fin/banking', 'budisteikul\tourcms\Controllers\BankingController',[ 'names' => 'route_fin_banking' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::resource('/cms/fin/report/asset', 'budisteikul\tourcms\Controllers\AssetController',[ 'names' => 'route_fin_asset' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::resource('/cms/fin/report/monthly', 'budisteikul\tourcms\Controllers\ReportMonthlyController',[ 'names' => 'route_fin_report_monthly' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware','SettingMiddleware']);

Route::resource('/cms/fin/tax', 'budisteikul\tourcms\Controllers\TaxController',[ 'names' => 'route_fin_tax' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::resource('/cms/fin/neraca', 'budisteikul\tourcms\Controllers\NeracaController',[ 'names' => 'route_fin_neraca' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::get('/cms/fin/report/pdf/{tahun}','budisteikul\tourcms\Controllers\LaporanController@pdf')->middleware(['web','auth','verified','CoreMiddleware']);

Route::resource('/cms/fin/profitloss-old', 'budisteikul\tourcms\Controllers\SalesControllerOld',[ 'names' => 'route_fin_profitloss_old' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::resource('/cms/fin/report/payment', 'budisteikul\tourcms\Controllers\ReportPaymentController',[ 'names' => 'route_fin_report_payment' ])->middleware(['web','auth','verified','CoreMiddleware','LevelMiddleware']);

Route::get('/test','budisteikul\tourcms\Controllers\APIController@test')->middleware(['web','auth','verified','CoreMiddleware','SettingMiddleware']);

Route::post('/cms/test','budisteikul\tourcms\Controllers\BookingController@test')->middleware(['SettingMiddleware']);