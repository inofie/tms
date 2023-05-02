<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('login',"API\ApiController@Login");


//Mange Comapny
Route::post('companylist',"API\ApiController@CompanyList");
Route::post('companyadd',"API\ApiController@CompanyAdd");
Route::post('companyedit',"API\ApiController@CompanyEdit");
Route::post('companydetail',"API\ApiController@CompanyDetail");
Route::post('companydelete',"API\ApiController@CompanyDelete");


//Manage Forwarder
Route::post('forwarderlist',"API\ApiController@ForwarderList");
Route::post('forwarderadd',"API\ApiController@ForwarderAdd");
Route::post('forwarderedit',"API\ApiController@ForwarderEdit");
Route::post('forwarderdetail',"API\ApiController@ForwarderDetail");
Route::post('forwarderdelete',"API\ApiController@ForwarderDelete");

//Manage Truck
Route::post('trucklist',"API\ApiController@TruckList");
Route::post('truckdetail',"API\ApiController@TruckDetail");

//Manage transporter
Route::post('transporterlist',"API\ApiController@TransporterList");
Route::post('transporteradd',"API\ApiController@TransporterAdd");
Route::post('transporteredit',"API\ApiController@TransporterEdit");
Route::post('transporterdetail',"API\ApiController@TransporterDetail");
Route::post('transporterdelete',"API\ApiController@TransporterDelete");


//Manage warehouse
Route::post('warehouselist',"API\ApiController@WarehouseList");
Route::post('warehouseadd',"API\ApiController@WarehouseAdd");
Route::post('warehouseedit',"API\ApiController@WarehouseEdit");
Route::post('warehousedetail',"API\ApiController@WarehouseDetail");
Route::post('warehousedelete',"API\ApiController@WarehouseDelete");



//Manage driver
Route::post('driverlist',"API\ApiController@DriverList");
Route::post('driveradd',"API\ApiController@DriverAdd");
Route::post('driveredit',"API\ApiController@DriverEdit");
Route::post('driverdetail',"API\ApiController@DriverDetail"); 
Route::post('driverdelete',"API\ApiController@DriverDelete");

//Manage employee
Route::post('employeelist',"API\ApiController@EmployeeList");
Route::post('employeeadd',"API\ApiController@EmployeeAdd");
Route::post('employeeedit',"API\ApiController@EmployeeEdit");
Route::post('employeedetail',"API\ApiController@EmployeeDetail");
Route::post('employeedelete',"API\ApiController@EmployeeDelete"); 

//Manage shipment
Route::post('shipmentform',"API\ApiController@ShipmentForm");
Route::post('shipmentadd',"API\ApiController@ShipmentFormAdd");
Route::post('shipmentdetail',"API\ApiController@ShipmentDetail");
Route::post('shipmentedit',"API\ApiController@ShipmentFormEdit");
Route::post('shipmentdelete',"API\ApiController@ShipmentFormDelete");

//Change Status
Route::post('shipmentadminstatus',"API\ApiController@ShipmentChangeStatusAdmin");
Route::post('shipmenttransporterstatus',"API\ApiController@ShipmentChangeStatusTransporter");

// Pending Shipment
Route::post('shipmentpendinglist',"API\ApiController@ShipmentpendingList");
Route::post('shipmenttransporterlist',"API\ApiController@ShipmentTransporterList");
Route::post('shipmenttransportersave',"API\ApiController@ShipmentTransporterSave");
Route::post('shipmenttransporterdelete',"API\ApiController@ShipmentTransporterDelete");

//shipment Driver
Route::post('shipmentdriverlist',"API\ApiController@ShipmentDriverList");
Route::post('shipmentdriversave',"API\ApiController@ShipmentDriverSave");
Route::post('shipmentdriverdelete',"API\ApiController@ShipmentDriverDelete");

//Manage Expense
Route::post('expenseadd',"API\ApiController@ExpenseAdd");

// OntheWay Shipment
Route::post('shipmentonthewaylist',"API\ApiController@ShipmentOnTheWayList");

// Delivery Shipment
Route::post('shipmentdeliverylist',"API\ApiController@ShipmentDeliveryList");

// Shipment Amount Update
Route::post('shipmentamountaupdate',"API\ApiController@ShipmentAmountUpdate");

//With Deleted List
Route::post('deletedcompany',"API\ApiController@DeleteCompany");
Route::post('deletedtransporter',"API\ApiController@DeleteTransporter");
Route::post('deletedforwarder',"API\ApiController@DeleteForwarder");
Route::post('deletedwarehouse',"API\ApiController@DeleteWarehouse");