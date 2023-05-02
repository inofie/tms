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


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

*/
Route::get('/', 'LoginController@index');
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/logincheck', 'LoginController@Logincheck')->name('logincheck');
Route::get('/logout', 'LoginController@Logout')->name('logout');
Route::get('/pdfview/{id}', 'PDFController@pdf');


Route::group(['namespace' => 'Admin','prefix' =>'admin' , 'middleware' => 'auth'], function () {

	Route::get('/dashboard','AdminController@Dashboard')->name('admindashboard');
	Route::get('/transporter/list','TransporterController@List')->name('transporterlist');
	Route::get('/transporter/add','TransporterController@ADD')->name('transporteradd');
	Route::post('/transporter/save','TransporterController@Save')->name('transportersave');
	Route::get('/transporter/edit/{id}','TransporterController@Edit')->name('transporteredit');
	Route::post('/transporter/update','TransporterController@Update')->name('transporterupdate');
	Route::get('/transporter/delete/{id}','TransporterController@Delete')->name('transporterdelete');


	Route::get('/forwarder/list','ForwarderController@List')->name('forwarderlist');
	Route::get('/forwarder/add','ForwarderController@ADD')->name('forwarderadd');
	Route::post('/forwarder/save','ForwarderController@Save')->name('forwardersave');
	Route::get('/forwarder/edit/{id}','ForwarderController@Edit')->name('forwarderedit');
	Route::post('/forwarder/update','ForwarderController@Update')->name('forwarderupdate');
	Route::post('/forwarder/delete','ForwarderController@Delete')->name('forwarderdelete');


	Route::get('/company/list','CompanyController@List')->name('companylist');
	Route::get('/company/add','CompanyController@ADD')->name('companyadd');
	Route::post('/company/save','CompanyController@Save')->name('companysave');
	Route::get('/company/edit/{id}','CompanyController@Edit')->name('companyedit');
	Route::post('/company/update','CompanyController@Update')->name('companyupdate');
	Route::post('/company/delete','CompanyController@Delete')->name('companydelete');



	Route::get('/employee/list','EmployeeController@List')->name('employeelist');
	Route::get('/employee/add','EmployeeController@ADD')->name('employeeadd');
	Route::post('/employee/save','EmployeeController@Save')->name('employeesave');
	Route::get('/employee/edit/{id}','EmployeeController@Edit')->name('employeeedit');
	Route::post('/employee/update','EmployeeController@Update')->name('employeeupdate');
	Route::post('/employee/delete','EmployeeController@Delete')->name('employeedelete');


	Route::get('/warehouse/list','WarehouseController@List')->name('warehouselist');
	Route::get('/warehouse/add','WarehouseController@ADD')->name('warehouseadd');
	Route::post('/warehouse/save','WarehouseController@Save')->name('warehousesave');
	Route::get('/warehouse/edit/{id}','WarehouseController@Edit')->name('warehouseedit');
	Route::post('/warehouse/update','WarehouseController@Update')->name('warehouseupdate');
	Route::post('/warehouse/delete','WarehouseController@Delete')->name('warehousedelete');


	Route::get('/driver/list','DriverController@List')->name('driverlist');
	Route::get('/driver/add','DriverController@ADD')->name('driveradd');
	Route::post('/driver/save','DriverController@Save')->name('driversave');
	Route::get('/driver/edit/{id}','DriverController@Edit')->name('driveredit');
	Route::post('/driver/update','DriverController@Update')->name('driverupdate');
	Route::post('/driver/delete','DriverController@Delete')->name('driverdelete');


	//Route::get('/home', 'HomeController@index')->name('home');

});

