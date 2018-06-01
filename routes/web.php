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
use App\User;
//
Route::get('/', function () {
    return view('auth.login');
});

//Route::get('/upload-csv','ApplicantsController@uploadCsv');
//Route::get('/',function (){
//
//    dd('hello');
//});



//Auth::routes();

//Route::post('/login/custom',[
//'uses'=>'LoginController@login',
//'as'=>'login.custom'
//]);

Route::group(['middleware' => ['auth','admin']], function () {
    Route::get('/applicants/lists', 'ApplicantsController@home')->name('applicant_list');
    Route::get('admin/home', 'HomeController@home')->name('admin.home');
    Route::get('/admin/register','UserController@showRegisterForm')->name('register.admin.form');
    Route::post('/admin/register/success/','UserController@store')->name('admin.register');
    Route::post('/upload-cv','ApplicantsController@uploadCvPost');
    Route::get('/upload-csv/view','ApplicantsController@uploadCsv')->name('upload.cvs');
    Route::post('/upload-csv','ApplicantsController@uploadCsvPost');
    Route::get('/view-cv/{id}','ApplicantsController@viewCv');
    Route::get('/delete/{id}','ApplicantsController@delete');
    Route::get('/register','Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register','Auth\RegisterController@register');

});
Route::group(['middleware' => ['firstLogin','auth','employee']], function () {
    Route::get('employee/home', 'HomeController@employeeHome')->name('employee.home');

});
//Auth::routes();
Route::resource('profile','UserController');
//Route::post('/password/email','Auth\ForgotPasswordController@sendRequestLinkEmail');
Route::group(['middleware' => ['web','guest']], function () {

    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
});
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('/login/success', 'Auth\LoginController@login')->name('login');
    Route::get('/change/password', 'UserController@changePassword')->middleware('auth')->name('password');
    Route::post('/password', 'UserController@newPassword')->middleware('auth')->name('new.password');

// Forgot password Route
    Route::group(['middleware' => ['web','guest']], function () {
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/rest', 'Auth\ResetPasswordController@reset')->name('reset');
    Route::get('/password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

});

//Route::get('/home', 'HomeController@index')->name('home');
//
//
//Route::get('/home', 'HomeController@index')->name('home');
