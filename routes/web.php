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

//Auth::routes();
Route::get('/', function () {
    return view('auth.login');
});
Route::group(['middleware' => ['auth:web', 'admin']], function () {
    Route::get('/screen/capture/change/', 'UserController@screenCapturePage')->name('screen.capture.page');
    Route::patch('/screen/capture/update/{id}', 'UserController@screenCaptureUpdate')->name('screen.capture.update');
//    Route::get('/applicants/lists', 'ApplicantsController@home')->name('applicant_list');
    Route::get('admin/home', 'HomeController@home')->name('admin.home');
    Route::get('/admin/register', 'UserController@showRegisterForm')->name('register.admin.form');
    Route::get('/attendance/search', 'AttendanceController@search')->name('attendance.search');
    Route::get('/employee', 'UserController@show')->name('employee.show');
    Route::post('/admin/register/success/', 'UserController@store')->name('admin.register');
//    Route::post('/upload-cv/','ApplicantsController@uploadCvPost');
//    Route::get('/upload-csv/view','ApplicantsController@uploadCsv')->name('upload.cvs');
//    Route::post('/upload-csv','ApplicantsController@uploadCsvPost');
//    Route::get('/view-cv/{id}','ApplicantsController@viewCv');
//    Route::get('/delete/{id}','ApplicantsController@delete');
//    Route::post('/add/manually/applicant','ApplicantsController@addApplicant')->name('applicant.manual');
    Route::get('/question-answers/search', 'QuestionAnswerController@searchQuestionByCategoryAdmin')->name('searchQuestionByCategoryAdmin');

    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'Auth\RegisterController@register');
    Route::resource('/timetable', 'TimeTableController');
    Route::resource('/leave', 'LeaveController');
    Route::get('/search/inform', 'InformController@search')->name('inform.search');
    Route::resource('/inform', 'InformController');
    Route::get('/search/task', 'TaskController@search')->name('task.search');
//    Route::resource('/task','TaskController');
    Route::delete('task/{task}', 'TaskController@destroy')->name('task.destroy');
    Route::get('/leaves', 'LeaveController@leave');
    Route::get('/delete-task/{id}', 'TaskController@modal');
    Route::delete('/attendance/{id}', 'AttendanceController@destroy')->name('attendance.destroy');
//    Route::resource('/qNA/category', 'QNACategoryController');
//    Route::resource('/question-answers', 'QuestionAnswerController');
//    Route::get('/print', 'QuestionAnswerController@printView')->name('print.view');
//    Route::post('/print', 'QuestionAnswerController@printCreate')->name('print.create');
    Route::get('/search/report', 'AttendanceController@getViewAdminReportPage')->name('view.admin.report');
    Route::get('/generate/report', 'AttendanceController@makeReportByAdmin')->name('admin.report.search');
    Route::get('/screen/capture/{id}', 'UserController@screenCapture')->name('screen.capture.get');
    Route::get('/generate/monthly/report', 'AttendanceController@monthlyAdminReportPage')->name('view.admin..monthly.report');
    Route::get('/search/monthly/report', 'AttendanceController@makeMonthlyReportByAdmin')->name('admin.monthly.report.search');
    Route::get('/generate/inaccuracy/report', 'AttendanceController@taskInaccuracyReportPage')->name('view.admin.inaccuracy.report');
    Route::get('/search/inaccuracy/report', 'AttendanceController@makeInaccuracyReportByAdmin')->name('admin.monthly.inaccuracy.search');
    Route::get('/employee/tracking/', 'TimeTrackerController@makeTimeTrackReportByAdmin')->name('admin.time.tracking.search');
    Route::get('/search/request', 'RequestLeaveController@search')->name('request.search');
    Route::resource('/task/project', 'ProjectTaskController');
    Route::get('/search/project', 'ProjectTaskController@search')->name('project.search');
    Route::post('/task/project/print', 'ProjectTaskController@print_report')->name('project.print');
    Route::get('/all/admins', 'UserController@indexAdmin')->name('admin.list');
    Route::get('/view/leave/compensatory', 'AttendanceController@compensatory')->name('view.admin.report.compensatory');


});
Route::group(['middleware' => 'checkHr', 'auth'], function () {
    Route::get('/applicants/lists', 'ApplicantsController@home')->name('applicant_list');
    Route::post('/upload-cv/', 'ApplicantsController@uploadCvPost');
    Route::get('/upload-csv/view', 'ApplicantsController@uploadCsv')->name('upload.cvs');
    Route::post('/upload-csvss', 'ApplicantsController@uploadCsvPost');
    Route::get('/view-cv/{id}', 'ApplicantsController@viewCv');
    Route::get('/delete/{id}', 'ApplicantsController@delete');
    Route::post('/add/manually/applicant', 'ApplicantsController@addApplicant')->name('applicant.manual');
    Route::resource('/interview/status', 'StatusController');
    Route::get('/get_sub_status/{id}', 'StatusController@sub_status');
    Route::resource('/interview', 'InterviewController');
    Route::resource('/interviewtest', 'TestInterviewController');
    Route::get('/get_state/{id}', 'ApplicantsController@getState');
    Route::get('/get_city/{id}', 'ApplicantsController@getCity');
    Route::get('/get_single_applicant/{id}', 'ApplicantsController@get_single_applicant')->name('get_single_applicant');
    Route::resource('/dropdown','DropDownController');
    Route::post('/reject/applicant', 'ApplicantsController@reject')->name('reject.applicant');
    Route::resource('/qNA/category', 'QNACategoryController');
    Route::resource('/question-answers', 'QuestionAnswerController');
    Route::get('/print', 'QuestionAnswerController@printView')->name('print.view');
    Route::post('/print', 'QuestionAnswerController@printCreate')->name('print.create');
    Route::resource('/call_status', 'CallStatusController');

});
Route::group(['middleware' => ['firstLogin', 'auth', 'employee']], function () {

    Route::get('employee/home', 'HomeController@employeeHome')->name('employee.home');


});
Route::group(['middleware' => ['auth']], function () {
    Route::get('/search/question/{id}', 'QuestionAnswerController@showEmployeeTestSearch')->name('question.search');
    Route::get('/question/search', 'QuestionAnswerController@getPage')->name('question.page');
    Route::get('/attendance/search', 'AttendanceController@search')->name('attendance.search');
    Route::get('/employee/task', 'TaskController@search')->name('task.search.employee');
    Route::get('/task/create', 'TaskController@create')->name('task.create');
    Route::post('/task', 'TaskController@store')->name('task.store');
    Route::get('/task', 'TaskController@index')->name('task.index');
    Route::get('/attendance/create', 'AttendanceController@create')->name('attendance.create');
    Route::post('/attendance', 'AttendanceController@store')->name('attendance.store');
    Route::get('/attendance', 'AttendanceController@index')->name('attendance.index');
    Route::get('/attendance/{id}', 'AttendanceController@show')->name('attendance.show');
    Route::patch('/attendance/{id}', 'AttendanceController@update')->name('attendance.update');
    Route::get('/attendance/{id}/edit', 'AttendanceController@edit')->name('attendance.edit');
    Route::patch('/task/{id}', 'TaskController@update')->name('task.update');
    Route::get('/task/{id}/edit', 'TaskController@edit')->name('task.edit');
    Route::get('task/{id}', 'TaskController@show')->name('task.show');
    Route::post('tracking', 'TimeTrackerController@makeTimeTrackReport')->name('time.tracking.search');
    Route::get('tracking/report', 'TimeTrackerController@index')->name('view.time.tracking');
    Route::resource('approval/request', 'RequestLeaveController');
    Route::get('/project/project/{id}', 'ProjectTaskController@project')->name('project.project');
    Route::get('/search/categories/questions', 'QuestionAnswerController@searchQuestionByCategory')->name('searchQuestionByCategory');


});

Route::resource('profile', 'UserController');
Route::group(['middleware' => ['web', 'guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login/success', 'Auth\LoginController@login')->name('login.success');
});
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/change/password', 'UserController@changePassword')->middleware('auth')->name('password');
Route::post('/password', 'UserController@newPassword')->middleware('auth')->name('new.password');
// Forgot password Route
Route::group(['middleware' => ['web', 'guest']], function () {

    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/rest', 'Auth\ResetPasswordController@reset')->name('reset');
    Route::get('/password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

});

