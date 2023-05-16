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

Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('sendattachmentemail','MailController@attachment_email');

Route::get('/', 'LoginController@index');
Route::get('/privacy_policy', 'LoginController@Privacy');
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/logincheck', 'LoginController@Logincheck')->name('logincheck');
Route::get('/logout', 'LoginController@Logout')->name('logout');
Route::get('/pdff', 'PDFController@pdf');
Route::get('/tanmay', 'PDFController@tanmay');
Route::get('/tanmaypdf', 'PDFController@tanmaypdf');


Route::get('/yashpdf', 'PDFController@yoginivbillpdf');
Route::get('/yash', 'PDFController@yoginivbill');

Route::get('/yoginipdf', 'PDFController@yoginibillpdf');
Route::get('/yogini', 'PDFController@yoginibill');

Route::get('/hanshpdf', 'PDFController@hanshbillpdf');
Route::get('/hansh', 'PDFController@hanshbill');

Route::get('/ssipdf', 'PDFController@ssibillpdf');
Route::get('/ssi', 'PDFController@ssibill');

Route::get('/bmfpdf', 'PDFController@bmfbillpdf');
Route::get('/bmf', 'PDFController@bmfbill');

Route::get('/yoginilrpdf', 'PDFController@yoginilrpdf');
Route::get('/yoginilr', 'PDFController@yoginilr');

Route::get('/hanshlrpdf', 'PDFController@hanshlrpdf');
Route::get('/hanshlr', 'PDFController@hanshlr');

Route::get('/ssilrpdf', 'PDFController@ssilrpdf');
Route::get('/ssilr', 'PDFController@ssilr');

Route::get('/bmflrpdf', 'PDFController@bmflrpdf');
Route::get('/bmflr', 'PDFController@bmflr');

Route::get('/pushmsg', 'WebNotificationController@sendWebNotification')->name('pushmsg');

Route::get('/updateExpenseDate', 'PDFController@updateExpenseDate');

Route::group(['namespace' => 'Admin','prefix' =>'admin' , 'middleware' => 'auth'], function () {

	Route::get('/dashboard','AdminController@Dashboard')->name('admindashboard');
	Route::get('/transporter/list','TransporterController@List')->name('transporterlist');
	Route::get('/transporter/add','TransporterController@ADD')->name('transporteradd');
	Route::post('/transporter/save','TransporterController@Save')->name('transportersave');
	Route::get('/transporter/edit/{id}','TransporterController@Edit')->name('transporteredit');
	Route::post('/transporter/update','TransporterController@Update')->name('transporterupdate');
	Route::post('/transporter/delete','TransporterController@Delete')->name('transporterdelete');
	Route::get('transporter/type/add','TransporterController@TypeADD')->name('transporttypeadd');
	Route::post('transporter/type/save','TransporterController@TypeSave')->name('transporttypesave');
	Route::get('transporter/type/edit/{id}','TransporterController@TypeEdit')->name('transporttypeedit');
	Route::post('transporter/type/update','TransporterController@TypeUpdate')->name('transporttypeupdate');
	Route::get('transporter/type/list','TransporterController@TypeList')->name('transporttypelist');
	Route::post('transporter/type/delete','TransporterController@TypeDelete')->name('transporttypedelete');


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

	Route::get('/shipment/list','ShipmentController@List')->name('shipmentlist');
	Route::get('/shipment/add','ShipmentController@Add')->name('shipmentadd');
	Route::post('/shipment/save','ShipmentController@Save')->name('shipmentsave');
	Route::get('/shipment/{id}','ShipmentController@Detail')->name('shipmentdetail');
	Route::get('/shipment/trucks/list/{id}','ShipmentController@TruckList')->name('shipmenttrucklist');
	Route::post('/shipment/change/truckstatus','ShipmentController@ChangeTruckStatus')->name('changetruckstatusadmin');
	Route::post('/shipment/truck/delete','ShipmentController@DeleteTruckStatus')->name('deletetruckstatusadmin');
	Route::get('/shipment/expense/add/{id}','ShipmentController@AddExpense')->name('addexpensebyadmin');
	Route::post('/shipment/expense/save','ShipmentController@SaveExpense')->name('expensesave1');
	Route::get('/shipment/transporter/add/{id}','ShipmentController@AddTransporter')->name('shipmenttransporter');
	Route::post('/shipment/transporter/save','ShipmentController@SaveTransporter')->name('savetransporter');
	Route::post('/shipment/transporter/delete','ShipmentController@DeleteTransporter')->name('deleteshiptransporter');
	Route::get('/lr/download/{id}','ShipmentController@DownloadLR')->name('downloadlr');
	Route::get('/shipment/detail/{id}','ShipmentController@ShipmentDetails')->name('shipmentdetails');
	Route::post('/shipment/amount/update','ShipmentController@ShipmentAmount')->name('shipmentamount');
	Route::get('/shipment/edit/{id}','ShipmentController@ShipmentEdit')->name('shipmentedit');
	Route::post('/shipment/update','ShipmentController@ShipmentUpdate')->name('shipmentupdate');
	Route::post('/shipment/delete','ShipmentController@ShipmentDelete')->name('shipmentdelete');
	Route::post('/shipment/add/warehouse','ShipmentController@WarehouseAdd')->name('shipwarehousein');
	Route::post('/shipment/delivered','ShipmentController@ShipmentDelivered')->name('shipmentdelivered');
	Route::any('/shipment/driverlist','ShipmentController@Driverlist')->name('shipmentdriverlist');
	
	

	Route::get('/shipment/warehouse/list','ShipmentController@WarehouseShipmentList')->name('warehouseshiplist');
	Route::get('/shipment/warehouse/transporter/add/{id}','ShipmentController@AddWareTransporter')->name('shipmentWaretransporter');
	Route::post('/shipment/warehouse/tansporter/save','ShipmentController@SaveWareTransporter')->name('savewaretransporter');
	Route::get('/shipment/warehouse/edit/{id}','ShipmentController@ShipmentWareEdit')->name('shipmentwareedit');
	Route::post('/shipment/warehouse/update','ShipmentController@ShipmentWareUpdate')->name('shipmentwareupdate');
	Route::get('/shipment/warehouse/detail/{id}','ShipmentController@ShipmentWareDetails')->name('shipmentwaredetails');
	Route::post('/shipment/ontheway','ShipmentController@ShipmentOntheway')->name('shipmentontheway');
	Route::post('/shipment/get/newid','ShipmentController@ShipmentNewID')->name('shipmentnewid');
	Route::post('/shipment/newshipment','ShipmentController@NewShipment')->name('newshipment');
	Route::get('/shipment/all/filter','ShipmentController@MyFilter')->name('myfilter');

	Route::get('/shipment/all/list','ShipmentController@ShipmentAllList')->name('allshipmentlist');
	Route::get('/shipment/old/detail/{id}','ShipmentController@ShipmentAllDetails')->name('shipalldetail');

	Route::get('/invoice/unpaid/list','InvoiceController@UnpaidList')->name('unpaidshipmentlist');
	Route::get('/invoice/paid/list','InvoiceController@PaidList')->name('paidshipmentlist');
	Route::get('/invoice/new','InvoiceController@InvoiceAdd')->name('invoiceadd');
	Route::post('/invoice/new/list','InvoiceController@ShipmentList')->name('invoiceshipmentlist');
	Route::post('/invoice/new/gst','InvoiceController@ShipmentGST')->name('invoiceshipmentgst');
	Route::post('/invoice/new/save','InvoiceController@ShipmentSave')->name('invoicesave');
	Route::get('/invoice/download/{id}','InvoiceController@Download')->name('downloadinvoice');
	Route::get('/invoice/edit/{id}','InvoiceController@InvoiceEdit')->name('invoiceedit');
	Route::post('/invoice/update','InvoiceController@InvoiceUpdate')->name('invoiceupdate');
	Route::get('/invoice/view/{id}','InvoiceController@InvoiceView')->name('invoiceview');
	Route::post('/invoice/delete','InvoiceController@InvoiceDelete')->name('invoicedelete');


	Route::get('/voucher/list','VoucherController@List')->name('voucherlist');
	Route::get('/voucher/credit','VoucherController@Credit')->name('voucherlcredit');
	Route::post('/voucher/credit/save','VoucherController@CreditSave')->name('voucherlcreditsave');
	Route::get('/voucher/debit','VoucherController@Debit')->name('voucherldebit');
	Route::post('/voucher/debit/save','VoucherController@DebitSave')->name('voucherldebitsave');
	Route::post('/voucher/forwarder','VoucherController@InvoiceBills')->name('invoicebills');
	Route::post('/voucher/delete','VoucherController@Delete')->name('voucherdelete');
	Route::get('/voucher/view/{id}','VoucherController@View')->name('voucherview');

	Route::get('/expense/list','VoucherController@ExpenseList')->name('expenselist');
	Route::get('/expense/add','VoucherController@ExpenseAdd')->name('expenseadd');
	Route::post('/expense/save','VoucherController@ExpenseSave')->name('expensesave');
	Route::get('/expense/view/{id}','VoucherController@ExpenseView')->name('expenseview');
	Route::post('/expense/delete','VoucherController@ExpenseDelete')->name('expensedelete');


	Route::get('/account','AccountController@Account')->name('accounts');
	Route::post('/account','AccountController@AccountPDF')->name('accountspdf');
	Route::post('/account/list','AccountController@AccountData')->name('accountdata');


});


Route::group(['namespace' => 'Forwarder','prefix' =>'forwarder' , 'middleware' => 'auth'], function () {

	Route::get('/dashboard','AdminController@Dashboard')->name('forwarderdashboard');
	Route::get('/shipment/list','ShipmentController@List')->name('forwarder-shipmentlist');
	Route::get('/shipment/all/list','ShipmentController@AllList')->name('forwarder-allshipmentlist');
	Route::get('/shipment/{id}','ShipmentController@ShipmentDetails')->name('forwarder-shipmentdetail');
	Route::get('/lr/download/{id}','ShipmentController@DownloadLR')->name('forwarder-downloadlr');
	
	Route::get('/account/invoice/list','AccountController@Account')->name('faccounts');
	Route::post('/account/invoice/list','AccountController@AccountData')->name('f-invoices-list');
	Route::get('/account/invoice/download/{id}','AccountController@InvoiceDownload')->name('f-invoices-download');
	Route::get('/account/invoice/view/{id}','AccountController@InvoiceView')->name('f-invoices-view');

	Route::get('/account/ledger','AccountController@LedgerAccount')->name('f-main-account');
	Route::post('/account/ledger','AccountController@LegerData')->name('f-main-account-data');

});
Route::group(['namespace' => 'Transporter','prefix' =>'transporter' , 'middleware' => 'auth'], function () {

	Route::get('/dashboard','AdminController@Dashboard')->name('transporterdashboard');

	Route::get('/shipment/list','ShipmentController@List')->name('shipmentlisttransporter');
	Route::get('/shipment/add','ShipmentController@Add')->name('shipmentadd');
	Route::post('/shipment/save','ShipmentController@Save')->name('shipmentsave');
	Route::get('/shipment/{id}','ShipmentController@Detail')->name('shipmentdetail');
	Route::get('/shipment/trucks/list/{id}','ShipmentController@TruckList')->name('shipmenttrucklist');
	Route::post('/shipment/change/truckstatus','ShipmentController@ChangeTruckStatus')->name('changetruckstatusadmin');
	Route::post('/shipment/truck/delete','ShipmentController@DeleteTruckStatus')->name('deletetruckstatusadmin');
	Route::get('/shipment/expense/add/{id}','ShipmentController@AddExpense')->name('addexpensebyadmin');
	Route::post('/shipment/expense/save','ShipmentController@SaveExpense')->name('expensesave1');
	Route::get('/shipment/transporter/add/{id}','ShipmentController@AddTransporter')->name('shipmenttransporter');
	Route::post('/shipment/transporter/save','ShipmentController@SaveTransporter')->name('savetransporter');
	Route::post('/shipment/transporter/delete','ShipmentController@DeleteTransporter')->name('deleteshiptransporter');
	Route::get('/lr/download/{id}','ShipmentController@DownloadLR')->name('downloadlr');
	Route::get('/shipment/detail/{id}','ShipmentController@ShipmentDetails')->name('shipmentdetails');
	Route::post('/shipment/amount/update','ShipmentController@ShipmentAmount')->name('shipmentamount');
	Route::get('/shipment/edit/{id}','ShipmentController@ShipmentEdit')->name('shipmentedit');
	Route::post('/shipment/update','ShipmentController@ShipmentUpdate')->name('shipmentupdate');
	Route::post('/shipment/delete','ShipmentController@ShipmentDelete')->name('shipmentdelete');
	Route::post('/shipment/add/warehouse','ShipmentController@WarehouseAdd')->name('shipwarehousein');
	Route::post('/shipment/delivered','ShipmentController@ShipmentDelivered')->name('shipmentdelivered');
	Route::any('/shipment/driverlist','ShipmentController@Driverlist')->name('shipmentdriverlist');
	Route::get('/shipment/warehouse/list','ShipmentController@WarehouseShipmentList')->name('warehouseshiplisttransporter');
	Route::get('/shipment/warehouse/transporter/add/{id}','ShipmentController@AddWareTransporter')->name('shipmentWaretransporter');
	Route::post('/shipment/warehouse/tansporter/save','ShipmentController@SaveWareTransporter')->name('savewaretransporter');
	Route::get('/shipment/warehouse/edit/{id}','ShipmentController@ShipmentWareEdit')->name('shipmentwareedit');
	Route::post('/shipment/warehouse/update','ShipmentController@ShipmentWareUpdate')->name('shipmentwareupdate');
	Route::get('/shipment/warehouse/detail/{id}','ShipmentController@ShipmentWareDetails')->name('shipmentwaredetails');
	Route::post('/shipment/ontheway','ShipmentController@ShipmentOntheway')->name('shipmentontheway');
	Route::post('/shipment/get/newid','ShipmentController@ShipmentNewID')->name('shipmentnewid');
	Route::post('/shipment/newshipment','ShipmentController@NewShipment')->name('newshipment');
	Route::get('/shipment/all/filter','ShipmentController@MyFilter')->name('myfilter');

	Route::get('/shipment/all/list','ShipmentController@ShipmentAllList')->name('allshipmentlisttransporter');
	Route::get('/shipment/old/detail/{id}','ShipmentController@ShipmentAllDetails')->name('shipalldetail');
	Route::get('/driver/list','DriverController@List')->name('transporterdriverlist');
	Route::get('/driver/add','DriverController@ADD')->name('transporterdriveradd');
	Route::post('/driver/save','DriverController@Save')->name('transporterdriversave');
	Route::get('/driver/edit/{id}','DriverController@Edit')->name('transporterdriveredit');
	Route::post('/driver/update','DriverController@Update')->name('transporterdriverupdate');
	Route::get('/driver/delete/{id}','DriverController@Delete')->name('transporterdriverdelete');
});


