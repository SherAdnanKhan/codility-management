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

use App\Attendance;
//use App\Mail\MailCheckIn;
use App\Mail\MailCheckIn;
use App\Mail\MailReport;
use App\Mail\MailTask;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

//use Mail;

//
//Auth::routes();
Route::get('/', function () {
    return view('auth.login');
});
    Route::group(['middleware' => ['auth','admin']], function () {
    Route::get('/applicants/lists', 'ApplicantsController@home')->name('applicant_list');
    Route::get('admin/home', 'HomeController@home')->name('admin.home');
    Route::get('/admin/register','UserController@showRegisterForm')->name('register.admin.form');
    Route::post('/admin/register/success/','UserController@store')->name('admin.register');
    Route::post('/upload-cv/','ApplicantsController@uploadCvPost');
    Route::get('/upload-csv/view','ApplicantsController@uploadCsv')->name('upload.cvs');
    Route::post('/upload-csv','ApplicantsController@uploadCsvPost');
    Route::get('/view-cv/{id}','ApplicantsController@viewCv');
    Route::get('/delete/{id}','ApplicantsController@delete');
    Route::get('/register','Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register','Auth\RegisterController@register');
    Route::resource('/timetable','TimeTableController');
    Route::resource('/leave','LeaveController');
    Route::resource('/inform','InformController');
    Route::resource('/task','TaskController');
    Route::get('/leaves','LeaveController@leave');
    Route::get('/delete-task/{id}','TaskController@modal');
    Route::delete('/attendance/{id}','AttendanceController@destroy')->name('attendance.destroy');

    });
    Route::group(['middleware' => ['firstLogin','auth','employee']], function () {

    Route::get('employee/home', 'HomeController@employeeHome')->name('employee.home');


});
Route::group(['middleware' => ['auth']], function () {
    Route::get('/task/create','TaskController@create')->name('task.create');
    Route::post('/task','TaskController@store')->name('task.store');
    Route::get('/task','TaskController@index')->name('task.index');
    Route::get('/attendance/create','AttendanceController@create')->name('attendance.create');
    Route::post('/attendance','AttendanceController@store')->name('attendance.store');
    Route::get('/attendance','AttendanceController@index')->name('attendance.index');
    Route::get('/attendance/{id}','AttendanceController@show')->name('attendance.show');
    Route::patch('/attendance/{id}','AttendanceController@update')->name('attendance.update');
    Route::get('/attendance/{id}/edit','AttendanceController@edit')->name('attendance.edit');

});

    Route::resource('profile','UserController');
    Route::group(['middleware' => ['web','guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login/success', 'Auth\LoginController@login')->name('login.success');
});
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/change/password', 'UserController@changePassword')->middleware('auth')->name('password');
    Route::post('/password', 'UserController@newPassword')->middleware('auth')->name('new.password');
// Forgot password Route
    Route::group(['middleware' => ['web','guest']], function () {

    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/rest', 'Auth\ResetPasswordController@reset')->name('reset');
    Route::get('/password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

});

Route::get('/sendmail',function () {

    //get_attendance
    //tasks
    //users
});
