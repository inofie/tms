<?php

use Illuminate\Http\Request;

Route::post('login',"API\ApiController@Login");
Route::post('logout',"API\ApiController@Logout");
Route::post('notification',"API\ApiController@PushNotification1");

// Appcheck
Route::post('appcheck',"API\ApiController@ApplicationCheck");

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


// Shipment Amount Update
Route::post('lrdownload',"API\ApiController@DownloadLR");

// Warehouse Shipment
Route::post('shipmentwarehouselist',"API\ApiController@ShipmentWarehouseList");
Route::post('shipmentinwarehouse',"API\ApiController@ShipmentInWarehouse");
Route::post('shipmentoutwarehouse',"API\ApiController@ShipmentOUTWarehouse");

//Transporter Add Expense
Route::post('transpoerteraddexpense',"API\ApiController@TransporterAddExpense");
Route::post('shipmentlistfortransporter',"API\ApiController@ShipmentListForTransporterFilter");

//Cargo Status List - 58
Route::post('cargostatuslist',"API\ApiController@CargostatusList");

//Shipment Trucks List - 59
Route::post('shiptrucklist',"API\ApiController@ShipTruckList");

//Shipment Trucks List - 60
Route::post('shipmentadmindelivered',"API\ApiController@Shipmentdelivered");

//Shipment Summary - 61
Route::post('shipmentsummary',"API\ApiController@ShipmentSummary");

//Replace Shipement - 62
Route::post('replaceshipment',"API\ApiController@ReplaceShipment");

//Dashboard - 63
Route::post('dashboard',"API\ApiController@Dashboard");

//Dashboard - 64
Route::post('transportac',"API\ApiController@TransporterAC");

//Dashboard - 65
Route::post('filter',"API\ApiController@Filters");

//Dashboard - 66
Route::post('allshipmentlist',"API\ApiController@ALLShipmentList");

//Dashboard - 67
Route::post('creaditreport',"API\ApiController@CreditReport");

//Dashboard - 68
Route::post('billstatus',"API\ApiController@BillStatus");

//forwarderac - 69
Route::post('forwarderac',"API\ApiController@ForwarderAC");

//forwarderslist - 70
Route::post('forwarderslist',"API\ApiController@ForwardersList");

//forwarderbilllist - 71
Route::post('forwarderbilllist',"API\ApiController@ForwarderBillList");

//Invoice Bill - 72
Route::post('invoicemail',"API\ApiController@InvoiceMail");


//Token Update 74
Route::post('tokenupdate',"API\ApiController@Token_Update");

// Lr Mail - 76
Route::post('lrmail',"API\ApiController@LRMail");

// GET Transporter Drivers
Route::post('mydrivers','API\ApiController@GetTransporterDriver');

// Lr Mail - 78
Route::post('ledgermail',"API\ApiController@LegerMail");

// Lr Mail - 79
Route::post('podmail',"API\ApiController@PodMail");


	
//// Forworder API/////////////

Route::post('forwarder/login',"API\ForwarderController@Login");

Route::post('forwarder/list',"API\ForwarderController@List");

Route::post('forwarder/detail',"API\ForwarderController@Details");

Route::post('/account/list','API\ApiController@AccountData');

Route::post('notificationList', 'API\NotificationController@notificationList');
Route::post('/readAllNotifications', 'API\NotificationController@readAllNotifications');
Route::post('/readSingleNotifications', 'API\NotificationController@readSingleNotifications');
Route::post('testNotification',"API\ApiController@testNotification");

Route::post('changeStatus_Load_To_DocumentReceived',"API\ApiController@changeStatus_Load_To_DocumentReceived");
Route::post('changeStatus_ReachAtPort_To_Unload',"API\ApiController@changeStatus_ReachAtPort_To_Unload");
Route::post('changeStatus_ReachAtPort_To_DocumentReceived',"API\ApiController@changeStatus_ReachAtPort_To_DocumentReceived");
Route::post('changeStatus_ReachAtCompany_To_Unload',"API\ApiController@changeStatus_ReachAtCompany_To_Unload");