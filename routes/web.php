<?php

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

/*********************General Function for Both (Front-end & Back-end) ***********************/
/* Route::post('/get_states', 'HomeController@getStates');
Route::post('/get_product_views', 'HomeController@getProductViews');
Route::post('/get_product_other_info', 'HomeController@getProductOtherInformation');
Route::post('/delete_action', 'HomeController@deleteAction')->middleware('auth');
 */


Route::get('/clear-cache', function() {

    Artisan::call('config:clear');
	Artisan::call('view:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('route:cache');
     /* $exitCode = \Artisan::call('BirthDate:birthdate');
        $output = \Artisan::output();
        return $output;  */
    // return what you want
});

/*********************Exception Handling ***********************/
Route::get('/exception', 'ExceptionController@index')->name('exception');
Route::post('/exception', 'ExceptionController@index')->name('exception');
// Route::get('destinationtour', 'DestinationController@destinationList');
// Route::get('/import', 'TestController@import');
/*********************Front Panel Start ***********************/
//Coming Soon
// Route::get('/coming_soon', 'HomeController@coming_soon')->name('coming_soon');

//Home Page
//Route::get('/', 'HomeController@index')->name('home');
//Route::get('/index', 'HomeController@index')->name('home');
// Route::get('/searchtour', 'HomeController@Searchtour')->name('Searchtour');
// Route::get('page/{slug}', 'HomeController@Page')->name('page.slug');

// Route::get('/enquiry', 'HomeController@enquiry')->name('enquiry');
// Route::post('/enquiry/store', 'HomeController@store')->name('enquiry.store');

// // Route::get('invoice/secure/{slug}', 'InvoiceController@invoice')->name('invoice');
// Route::get('/invoice/download/{id}', 'InvoiceController@customer_invoice_download')->name('invoice.customer_invoice_download');
// Route::get('/invoice/print/{id}', 'InvoiceController@customer_invoice_print')->name('invoice.customer_invoice_print');
// //singlepackage
// Route::get('/singlepackage', 'HomeController@singlepack')->name('singlepackage');
// Route::get('/packdetails', 'HomeController@packdetails')->name('packdetails');
// Route::get('/profile', 'HomeController@myprofile')->name('profile');
//Login and Register
Auth::routes();
// Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
// Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

// Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');
// //Forgot Password
/* Route::get('/forgot_password', 'HomeController@forgotPassword')->name('forgot_password');
Route::post('/forgot_password', 'HomeController@forgotPassword')->name('forgot_password');

//Reset Link
Route::get('/reset_link/{token}', 'HomeController@resetLink')->name('reset_link');
Route::post('/reset_link', 'HomeController@resetLink')->name('reset_link');	 */

//Review Panel
// Route::post('/add_review', 'DashboardController@addReview')->name('dashboard.add_review');

//Shipping Info
// Route::get('/address', 'DashboardController@address')->name('dashboard.address');
// Route::post('/address', 'DashboardController@address')->name('dashboard.address');

//Payment Process
//Route::get('/payment', 'PaymentController@index')->name('payment.index');
//Route::post('/checkout', 'PaymentController@checkout')->name('payment.checkout');
//Route::get('/payment_status', 'PaymentController@status')->name('payment.status');

//Thankyou Page
// Route::get('/thankyou', 'PaymentController@thankyou')->name('payment.thankyou');

// //Inner Dashboard
// Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
// Route::get('dashboard/view_order_summary/{id}', 'DashboardController@viewOrderSummary')->name('dashboard.view_order_summary');
// Route::get('/view_test_series_order/{id}', 'DashboardController@viewTestSeriesOrder')->name('dashboard.view_test_series_order');
// Route::post('/logout', 'DashboardController@logout')->name('logout');

// //Other Functions
// Route::get('/change_password', 'DashboardController@changePassword')->name('change_password');
// Route::post('/change_password', 'DashboardController@changePassword')->name('change_password');
// Route::get('/edit_profile', 'DashboardController@editProfile')->name('dashboard.edit_profile');
// Route::post('/edit_profile', 'DashboardController@editProfile')->name('dashboard.edit_profile');

// Frontend Route
//Home Page
Route::get('/', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@index')->name('home');
//Route::get('/about-us', 'HomeController@about')->name('about');
Route::get('/testimonials', 'HomeController@testimonial')->name('testimonial');
Route::get('/ourservices', 'HomeController@ourservices')->name('ourservices');
Route::get('/ourservices/{slug}', 'HomeController@servicesdetail')->name('servicesdetail');
Route::get('/blogs', 'HomeController@blogs')->name('blogs');
Route::get('/search_result', 'HomeController@search_result')->name('search_result');

//Route::get('/blogs/{slug}', 'HomeController@blogdetail')->name('blogdetail');
Route::get('/contact-us', 'HomeController@contactus');
Route::post('/contact-us', 'HomeController@contact')->name('contact.emailsubmit');

// Unified Contact Form Route
Route::post('/contact/submit', 'ContactController@contactSubmit')->name('contact.submit');

// Contact Form Test Page
Route::get('/contact-form-test', function () {
    return view('contact-form-test');
})->name('contact-form-test');

Route::get('stripe/{appointmentId}', 'HomeController@stripe');
Route::post('stripe', 'HomeController@stripePost')->name('stripe.post1');

Route::get('/book-an-appointment', 'HomeController@bookappointment')->name('bookappointment');
Route::get('/book-an-appointment1', 'HomeController@bookappointment1')->name('bookappointment1');
Route::post('/book-an-appointment/store', 'AppointmentBookController@store');
Route::post('/book-an-appointment/storepaid', 'AppointmentBookController@storepaid')->name('stripe.post');
Route::post('/getdatetime', 'HomeController@getdatetime');
Route::post('/getdatetimebackend', 'HomeController@getdatetimebackend');

Route::post('/getdisableddatetime', 'HomeController@getdisableddatetime');

//Route::get('/mission-vision', 'HomeController@missionvision')->name('mission_vision');
Route::get('page/{slug}', 'HomeController@Page')->name('page.slug');
Route::post('enquiry-contact', 'PackageController@enquiryContact')->name('query.contact');
Route::get('thanks', 'PackageController@thanks')->name('thanks');
Route::get('/profile', 'HomeController@myprofile')->name('profile');
/*---------------Agent Route-------------------*/
// Agent routes removed
/*********************Admin Panel Start ***********************/
Route::prefix('admin')->group(function() {
     //Login and Logout
		Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
		Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
		Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login');
		Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

	//General
        Route::get('/dashboard', 'Admin\AdminController@dashboard')->name('admin.dashboard');
		Route::get('/get_customer_detail', 'Admin\AdminController@CustomerDetail')->name('admin.get_customer_detail');
		Route::get('/my_profile', 'Admin\AdminController@myProfile')->name('admin.my_profile');
		Route::post('/my_profile', 'Admin\AdminController@myProfile')->name('admin.my_profile');
		Route::get('/change_password', 'Admin\AdminController@change_password')->name('admin.change_password');
		Route::post('/change_password', 'Admin\AdminController@change_password')->name('admin.change_password');
		Route::get('/sessions', 'Admin\AdminController@sessions')->name('admin.sessions');
		Route::post('/sessions', 'Admin\AdminController@sessions')->name('admin.sessions');
        Route::post('/delete_action', 'Admin\AdminController@deleteAction');


        //Blog
		Route::get('/blog', 'Admin\BlogController@index')->name('admin.blog.index');
		Route::get('/blog/create', 'Admin\BlogController@create')->name('admin.blog.create');
		Route::post('/blog/store', 'Admin\BlogController@store')->name('admin.blog.store');
		Route::get('/blog/edit/{id}', 'Admin\BlogController@edit')->name('admin.blog.edit');
		Route::post('/blog/edit', 'Admin\BlogController@edit')->name('admin.blog.edit');

	    //Blog Category
		Route::get('/blogcategories', 'Admin\BlogCategoryController@index')->name('admin.blogcategory.index');
		Route::get('/blogcategories/create', 'Admin\BlogCategoryController@create')->name('admin.blogcategory.create');
		Route::post('/blogcategories/store', 'Admin\BlogCategoryController@store')->name('admin.blogcategory.store');
		Route::get('/blogcategories/edit/{id}', 'Admin\BlogCategoryController@edit')->name('admin.blogcategory.edit');
		Route::post('/blogcategories/edit', 'Admin\BlogCategoryController@edit')->name('admin.blogcategory.edit');

		//CMS Pages
		Route::get('/cms_pages', 'Admin\CmsPageController@index')->name('admin.cms_pages.index');
		Route::get('/cms_pages/create', 'Admin\CmsPageController@create')->name('admin.cms_pages.create');
		Route::post('/cms_pages/store', 'Admin\CmsPageController@store')->name('admin.cms_pages.store');
		Route::get('/cms_pages/edit/{id}', 'Admin\CmsPageController@editCmsPage')->name('admin.edit_cms_page');
		Route::post('/cms_pages/edit', 'Admin\CmsPageController@editCmsPage')->name('admin.edit_cms_page');


        Route::post('/update_action', 'Admin\AdminController@updateAction');

        // Contact Management Routes (Admin only)
        Route::group(['middleware' => 'auth:admin'], function() {
            Route::get('/contact-management', 'Admin\ContactManagementController@index')->name('admin.contact-management.index');
            Route::get('/contact-management/dashboard', 'Admin\ContactManagementController@dashboard')->name('admin.contact-management.dashboard');
            Route::get('/contact-management/{id}', 'Admin\ContactManagementController@show')->name('admin.contact-management.show');
            Route::put('/contact-management/{id}/status', 'Admin\ContactManagementController@updateStatus')->name('admin.contact-management.update-status');
            Route::post('/contact-management/{id}/forward', 'Admin\ContactManagementController@forward')->name('admin.contact-management.forward');
            Route::delete('/contact-management/{id}', 'Admin\ContactManagementController@destroy')->name('admin.contact-management.destroy');
            Route::post('/contact-management/bulk-action', 'Admin\ContactManagementController@bulkAction')->name('admin.contact-management.bulk-action');
            Route::get('/contact-management/export', 'Admin\ContactManagementController@export')->name('admin.contact-management.export');
        });
});

//Twilio api
Route::post('/verify/is-phone-verify-or-not', 'SMSTwilioController@isPhoneVerifyOrNot')->name('verify.is-phone-verify-or-not');
Route::post('/verify/send-code', 'SMSTwilioController@sendVerificationCode')->name('verify.send-code');
Route::post('/verify/check-code', 'SMSTwilioController@verifyCode')->name('verify.check-code');
//Route::get('/show-form', 'SMSTwilioController@showForm')->name('sms.form');
//Route::post('/send-sms', 'SMSTwilioController@sendSMS')->name('send.sms');



//Cellcast api
//Route::post('/verify/is-phone-verify-or-not', 'SmsController@isPhoneVerifyOrNot')->name('verify.is-phone-verify-or-not');
//Route::post('/verify/send-code', 'SmsController@sendVerificationCode')->name('verify.send-code');
//Route::post('/verify/check-code', 'SmsController@verifyCode')->name('verify.check-code');
Route::get('/sms', 'SmsController@showForm')->name('sms.form');
Route::post('/sms', 'SmsController@send')->name('sms.send');
Route::get('/sms/status/{messageId}', 'SmsController@checkStatus')->name('sms.status');
Route::get('/sms/responses', 'SmsController@getResponses')->name('sms.responses');




//PR point calculator
Route::get('/pr-calculator', 'AustralianPRCalculatorController@index');
Route::post('/calculate-pr-points', 'AustralianPRCalculatorController@calculate');

//Postcode checker
Route::get('/postcode-checker', 'PostcodeController@index')->name('postcode.index');
Route::get('/check', 'PostcodeController@check')->name('postcode.check');
Route::get('/suggest', 'PostcodeController@suggest')->name('postcode.suggest');

//Student visa financial calculator
Route::get('/student-visa-financial-calculator', 'VisaCalculatorController@index')->name('visa.calculator');
Route::post('/student-visa-financial-calculator/calculate', 'VisaCalculatorController@calculate')->name('visa.calculate');

Route::get('/{slug}', 'HomeController@Page')->name('page.slug');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
