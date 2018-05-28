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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload-csv','ApplicantsController@uploadCsv');
Route::post('/upload-csv','ApplicantsController@uploadCsvPost');



Auth::routes();

//Route::post('/login/custom',[
//'uses'=>'LoginController@login',
//'as'=>'login.custom'
//]);

Route::group(['middleware'=>'auth'],function(){
	Route::get('/home', 'ApplicantsController@home')->name('home');

});
Route::get('/home', 'ApplicantsController@home')->name('home');
Route::get('/admin/login','UserController@showLoginForm');
Route::get('/admin/register','UserController@showRegisterForm');
Route::post('/admin/login/success','UserController@login')->name('user.login');
Route::post('/admin/register/success/','UserController@register')->name('admin.register');
Route::post('/change/password','UserController@changePassword')->name('change.password');

//Route::get('admin/home', 'AdminController@index');
//Route::get('employee/home', 'EmployeeController@index');

Route::post('/upload-cv','ApplicantsController@uploadCvPost');

Route::get('/view-cv/{id}','ApplicantsController@viewCv');

Route::get('/delete/{id}','ApplicantsController@delete');

Auth::routes();

Route::get('/data',function(){
    return view('admin');

})->name('users');
//Route::get('/home', 'HomeController@index')->name('home');
//
//
//Route::get('/home', 'HomeController@index')->name('home');
