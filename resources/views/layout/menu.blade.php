<aside>
          <div id="sidebar"  class="nav-collapse " style="overflow-y:scroll">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
                @if(Auth::user()->role == 'admin' )
                  <li>
                      <a class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admindashboard') }}">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  <li>
                      <a class="{{ Request::is('admin/liveboard*') ? 'active' : '' }}" href="{{ route('liveboard') }}">
                          <i class="fa fa-list"></i>
                          <span>Live Board</span>
                      </a>
                  </li>
                 {{--  <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>  --}}
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/shipment*') ? 'active' : '' }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                      <ul class="sub">
                      	 <li class="{{ Request::is('admin/shipment/add') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentadd') }}">Add New</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/list') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentlist') }}">Latest List</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/warehouse/list') ? 'active' : '' }}">
                              <a  href="{{ route('warehouseshiplist') }}">Warehouse List</a>
                          </li>
                            <li class="{{ Request::is('admin/shipment/all/list') ? 'active' : '' }}">
                              <a  href="{{ route('allshipmentlist') }}">Old List</a>
                            </li>
                             <!-- <li class="{{ Request::is('admin/shipment/all/filter') ? 'active' : '' }}">
                              <a  href="{{ route('myfilter') }}">Filter</a>
                            </li> -->
                      </ul>
                  </li>
                    <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/invoice*') ? 'active' : '' }}">
                          <i class="fa fa-file-text"></i>
                          <span>Manage Invoices</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/invoice/new') ? 'active' : '' }}">
                            <a  href="{{ route('invoiceadd') }}">New Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/unpaid/list') ? 'active' : '' }}">
                            <a  href="{{ route('unpaidshipmentlist') }}">Unpaid Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/paid/list') ? 'active' : '' }}">
                              <a  href="{{ route('paidshipmentlist') }}">Paid Invoices</a>
                          </li>
                      </ul>
                  </li>
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/voucher*') ? 'active' : '' }}">
                          <i class="fa fa-square"></i>
                          <span>Manage Vouchers</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/voucher/credit') ? 'active' : '' }}">
                            <a  href="{{ route('voucherlcredit') }}">New Credit Voucher</a>
                          </li>
                          <li class="{{ Request::is('admin/voucher/debit') ? 'active' : '' }}">
                            <a  href="{{ route('voucherldebit') }}">New Debit Voucher</a>
                          </li>
                          <li class="{{ Request::is('admin/voucher/list') ? 'active' : '' }}">
                            <a  href="{{ route('voucherlist') }}">Voucher List</a>
                          </li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/expense*') ? 'active' : '' }}">
                          <i class="fa fa-inr"></i>
                          <span>Manage Expenses</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/expense/add') ? 'active' : '' }}">
                            <a  href="{{ route('expenseadd') }}">Add Expense</a>
                          </li>
                          <li class="{{ Request::is('admin/expense/list') ? 'active' : '' }}">
                            <a  href="{{ route('expenselist') }}">Expense List</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <a class="{{ Request::is('admin/account*') ? 'active' : '' }}" href=" {{ route('accounts') }}">
                          <i class="fa fa-suitcase"></i>
                          <span>Manage Accounts</span>
                      </a>
                  </li>
                   {{-- <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('shipmentlist') }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>     --}}
                  <li>
                      <a class="{{ Request::is('admin/transporter*') ? 'active' : '' }}" href="{{ route('transporterlist') }}">
                          <i class="fa fa-truck"></i>
                          <span>Manage Transporter</span>
                      </a>
                  </li>
                  <li>
                      <a class="{{ Request::is('admin/company*') ? 'active' : '' }}" href="{{ route('companylist') }}">
                          <i class="fa fa-building"></i>
                          <span>Manage Company</span>
                      </a>
                  </li>
                   <li>
                      <a class="{{ Request::is('admin/forwarder*') ? 'active' : '' }}" href="{{ route('forwarderlist') }}">
                          <i class="fa fa-university"></i>
                          <span>Manage Forwarder</span>
                      </a>
                  </li>
                    <li>
                      <a class="{{ Request::is('admin/employee*') ? 'active' : '' }}" href="{{ route('employeelist') }}">
                          <i class="fa fa-users"></i>
                          <span>Manage Employee</span>
                      </a>
                  </li>
                  <li>
                      <a class="{{ Request::is('admin/warehouse*') ? 'active' : '' }}" href="{{ route('warehouselist') }}">
                          <i class="fa fa-home"></i>
                          <span>Manage Warehouse</span>
                      </a>
                  </li>
                   <li>
                      <a class="{{ Request::is('admin/driver*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Driver</span>
                      </a>
                  </li>
                    <li class="@if(Request::is('admin/roles') ||Request::is('admin/roles/*') ) active @endif treeview">
                    <a href="{{url('admin/roles')}}">
                    <i class="fa fa-globe"></i><span>Manage Roles</span>
                    </a>
                    </li>
                        <li class="@if(Request::is('admin/roleuser') ||Request::is('admin/roleuser/*') ) active @endif treeview">
                        <a href="{{url('admin/roleuser')}}">
                        <i class="fa fa-user"></i><span>Manage Role Users</span>
                        </a>
                        </li>
                    <!-- <li>
                    <a class="{{ Request::is('admin/notifications*') ? 'active' : '' }}" href="{{ route('notificationlist') }}">
                    <i class="fa fa-building"></i>
                    <span>Notifications</span>
                </a>
                </li> -->
                  @elseif(Auth::user()->role == 'company' )
                  <li>
                      <a class="{{ Request::is('company/dashboard*') ? 'active' : '' }}" href="{{ route('companydashboard') }}">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                 {{--  <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>  --}}
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/shipment*') ? 'active' : '' }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                      <ul class="sub">
                      	 <li class="{{ Request::is('admin/shipment/add') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentadd') }}">Add New</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/list') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentlist') }}">Latest List</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/warehouse/list') ? 'active' : '' }}">
                              <a  href="{{ route('warehouseshiplist') }}">Warehouse List</a>
                          </li>
                            <li class="{{ Request::is('admin/shipment/all/list') ? 'active' : '' }}">
                              <a  href="{{ route('allshipmentlist') }}">Old List</a>
                            </li>
                             <!-- <li class="{{ Request::is('admin/shipment/all/filter') ? 'active' : '' }}">
                              <a  href="{{ route('myfilter') }}">Filter</a>
                            </li> -->
                      </ul>
                  </li>
                    <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/invoice*') ? 'active' : '' }}">
                          <i class="fa fa-file-text"></i>
                          <span>Manage Invoices</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/invoice/new') ? 'active' : '' }}">
                            <a  href="{{ route('invoiceadd') }}">New Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/unpaid/list') ? 'active' : '' }}">
                            <a  href="{{ route('unpaidshipmentlist') }}">Unpaid Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/paid/list') ? 'active' : '' }}">
                              <a  href="{{ route('paidshipmentlist') }}">Paid Invoices</a>
                          </li>
                      </ul>
                  </li>
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/voucher*') ? 'active' : '' }}">
                          <i class="fa fa-square"></i>
                          <span>Manage Vouchers</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/voucher/credit') ? 'active' : '' }}">
                            <a  href="{{ route('voucherlcredit') }}">New Credit Voucher</a>
                          </li>
                          <li class="{{ Request::is('admin/voucher/debit') ? 'active' : '' }}">
                            <a  href="{{ route('voucherldebit') }}">New Debit Voucher</a>
                          </li>
                          <li class="{{ Request::is('admin/voucher/list') ? 'active' : '' }}">
                            <a  href="{{ route('voucherlist') }}">Voucher List</a>
                          </li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/expense*') ? 'active' : '' }}">
                          <i class="fa fa-inr"></i>
                          <span>Manage Expenses</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/expense/add') ? 'active' : '' }}">
                            <a  href="{{ route('expenseadd') }}">Add Expense</a>
                          </li>
                          <li class="{{ Request::is('admin/expense/list') ? 'active' : '' }}">
                            <a  href="{{ route('expenselist') }}">Expense List</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <a class="{{ Request::is('admin/account*') ? 'active' : '' }}" href=" {{ route('accounts') }}">
                          <i class="fa fa-suitcase"></i>
                          <span>Manage Accounts</span>
                      </a>
                  </li>
                   {{-- <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('shipmentlist') }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>     --}}
                  <li>
                      <a class="{{ Request::is('admin/transporter*') ? 'active' : '' }}" href="{{ route('transporterlist') }}">
                          <i class="fa fa-truck"></i>
                          <span>Manage Transporter</span>
                      </a>
                  </li>
                   <li>
                      <a class="{{ Request::is('admin/forwarder*') ? 'active' : '' }}" href="{{ route('forwarderlist') }}">
                          <i class="fa fa-university"></i>
                          <span>Manage Forwarder</span>
                      </a>
                  </li>
                    <li>
                      <a class="{{ Request::is('company/employee*') ? 'active' : '' }}" href="{{ route('employeelistcompany') }}">
                          <i class="fa fa-users"></i>
                          <span>Manage Employee</span>
                      </a>
                  </li>
                  <li>
                      <a class="{{ Request::is('admin/warehouse*') ? 'active' : '' }}" href="{{ route('warehouselist') }}">
                          <i class="fa fa-home"></i>
                          <span>Manage Warehouse</span>
                      </a>
                  </li>
                   <li>
                      <a class="{{ Request::is('admin/driver*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Driver</span>
                      </a>
                  </li>
                    @elseif(Auth::user()->role == 'employee')
                  <li>
                      <a class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admindashboard') }}">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                 {{--  <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>  --}}
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/shipment*') ? 'active' : '' }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/shipment/add') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentadd') }}">Add New</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/list') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentlist') }}">Latest List</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/warehouse/list') ? 'active' : '' }}">
                              <a  href="{{ route('warehouseshiplist') }}">Warehouse List</a>
                          </li>
                            <li class="{{ Request::is('admin/shipment/all/list') ? 'active' : '' }}">
                              <a  href="{{ route('allshipmentlist') }}">Old List</a>
                            </li>
                      </ul>
                  </li>
                    <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/invoice*') ? 'active' : '' }}">
                          <i class="fa fa-file-text"></i>
                          <span>Manage Invoices</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/invoice/new') ? 'active' : '' }}">
                            <a  href="{{ route('invoiceadd') }}">New Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/unpaid/list') ? 'active' : '' }}">
                            <a  href="{{ route('unpaidshipmentlist') }}">Unpaid Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/paid/list') ? 'active' : '' }}">
                              <a  href="{{ route('paidshipmentlist') }}">Paid Invoices</a>
                          </li>
                      </ul>
                  </li>
                   {{-- <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('shipmentlist') }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>     --}}
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/transporter*') ? 'active' : '' }}">
                          <i class="fa fa-truck"></i>
                          <span>Manage Transporter</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/transporter/add') ? 'active' : '' }}">
                            <a  href="{{ route('transporteradd') }}">Add Transporter</a>
                          </li>
                          <li class="{{ Request::is('admin/transporter/vehicle') ? 'active' : '' }}">
                              <a  href="{{ route('transporterlist') }}">Transporter List</a>
                          </li>
                          <li class="{{ Request::is('admin/transporter/type/add') ? 'active' : '' }}">
                              <a  href="{{ route('transporttypeadd') }}">Add Vehicle Type</a>
                          </li>
                          <li class="{{ Request::is('admin/transporter/type/list') ? 'active' : '' }}">
                              <a  href="{{ route('transporttypelist') }}">Vehicle Type List</a>
                          </li>
                      </ul>
                  </li>
                  <!-- <li>
                      <a class="{{ Request::is('admin/company*') ? 'active' : '' }}" href="{{ route('companylist') }}">
                          <i class="fa fa-building"></i>
                          <span>Manage Company</span>
                      </a>
                  </li>  -->
                   <li>
                      <a class="{{ Request::is('admin/forwarder*') ? 'active' : '' }}" href="{{ route('forwarderlist') }}">
                          <i class="fa fa-university"></i>
                          <span>Manage Forwarder</span>
                      </a>
                  </li>
                    <!-- <li>
                      <a class="{{ Request::is('admin/employee*') ? 'active' : '' }}" href="{{ route('employeelist') }}">
                          <i class="fa fa-users"></i>
                          <span>Manage Employee</span>
                      </a>
                  </li>  -->
                  <li>
                      <a class="{{ Request::is('admin/warehouse*') ? 'active' : '' }}" href="{{ route('warehouselist') }}">
                          <i class="fa fa-home"></i>
                          <span>Manage Warehouse</span>
                      </a>
                  </li>
                   <li>
                      <a class="{{ Request::is('admin/driver*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Driver</span>
                      </a>
                  </li>
                  @elseif(Auth::user()->role == 'forwarder')
                   <li>
                      <a class="{{ Request::is('forwarder/dashboard*') ? 'active' : '' }}" href="{{ route('forwarderdashboard') }}">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('forwarder/shipment*') ? 'active' : '' }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                      <ul class="sub">
                          <li class="{{ Request::is('forwarder/shipment/list') ? 'active' : '' }}">
                            <a  href="{{ route('forwarder-shipmentlist') }}">Latest List</a>
                          </li>
                          <li class="{{ Request::is('forwarder/shipment/all/list') ? 'active' : '' }}">
                              <a  href="{{ route('forwarder-allshipmentlist') }}">Old List</a>
                            </li>
                      </ul>
                  </li>
                  
                  <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('forwarder/account*') ? 'active' : '' }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Accounts</span>
                      </a>
                      <ul class="sub">
                          <li class="{{ Request::is('forwarder/account/invoice/list') ? 'active' : '' }}">
                            <a  href="{{ route('faccounts') }}">Invoice List</a>
                          </li>
                          <li class="{{ Request::is('forwarder/account/ledger') ? 'active' : '' }}">
                              <a  href="{{ route('f-main-account') }}">Ledger Account</a>
                            </li>
                      </ul>
                  </li>
                  <li>
                      <a class="{{ Request::is('level*') ? 'active' : '' }}" href="{{url('forwarder/level')}}">
                          <i class="fa fa-globe"></i>
                          <span>Forwarder Level</span>
                      </a>
                  </li>
                  <li>
                      <a class="{{ Request::is('user*') ? 'active' : '' }}" href="{{url('forwarder/user')}}">
                          <i class="fa fa-user"></i>
                          <span>Forwarder Users</span>
                      </a>
                  </li>
                  
                  @elseif(Auth::user()->role == 'Forwarder_level1' || Auth::user()->role == 'Forwarder_level2' || Auth::user()->role == 'Forwarder_level3' || Auth::user()->role == 'Forwarder_level4'
                  || Auth::user()->role == 'Forwarder_level5' || Auth::user()->role == 'Forwarder_level6' || Auth::user()->role == 'Forwarder_level7' || Auth::user()->role == 'Forwarder_level8'
                  || Auth::user()->role == 'Forwarder_level9' || Auth::user()->role == 'Forwarder_level10')
                  <li>
                      <a class="{{ Request::is('user*') ? 'active' : '' }}" href="{{ route('userlist2')}}">
                          <i class="fa fa-user"></i>
                          <span>Forwarder Users</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('forwarder/shipment*') ? 'active' : '' }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                      <ul class="sub">
                          <li class="{{ Request::is('forwarder/shipment/list') ? 'active' : '' }}">
                            <a  href="{{ route('forwarder-shipmentlist') }}">Latest List</a>
                          </li>
                          <li class="{{ Request::is('forwarder/shipment/all/list') ? 'active' : '' }}">
                              <a  href="{{ route('forwarder-allshipmentlist') }}">Old List</a>
                            </li>
                      </ul>
                  </li>
                  
                  
                @elseif(Auth::user()->role == 'transporter')
                        <li>
                        <a class="{{ Request::is('transporter/dashboard*') ? 'active' : '' }}" href="{{ route('transporterdashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                        </li>
                        <li class="sub-menu">
                        <a href="javascript:;" class="{{ Request::is('transporter/shipment*') ? 'active' : '' }}">
                            <i class="fa fa-bus"></i>
                            <span>Manage Shipment</span>
                        </a>
                        <ul class="sub">
                      	 <!-- <li class="{{ Request::is('admin/shipment/add') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentadd') }}">Add New</a>
                          </li> -->
                          <li class="{{ Request::is('transporter/shipment/list') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentlisttransporter') }}">Latest List</a>
                          </li>
                          <!-- <li class="{{ Request::is('admin/shipment/warehouse/list') ? 'active' : '' }}">
                              <a  href="{{ route('warehouseshiplisttransporter') }}">Warehouse List</a>
                          </li> -->
                            <li class="{{ Request::is('transporter/shipment/all/list') ? 'active' : '' }}">
                              <a  href="{{ route('allshipmentlisttransporter') }}">Old List</a>
                            </li>
                             <!-- <li class="{{ Request::is('transporter/shipment/all/filter') ? 'active' : '' }}">
                              <a  href="{{ route('myfiltertransporter') }}">Filter</a>
                            </li> -->
                        </ul>
                        <li>
                        <a class="{{ Request::is('transporter/driver*') ? 'active' : '' }}" href="{{ route('transporterdriverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Driver</span>
                        </a>
                        </li>
                        <li>
                        <a class="{{ Request::is('transporter/account*') ? 'active' : '' }}" href=" {{ route('transporteraccounts') }}">
                            <i class="fa fa-suitcase"></i>
                            <span>Manage Accounts</span>
                        </a>
                        </li>
                        </li>
                        <!-- <li>
                            <a class="{{ Request::is('admin/notifications*') ? 'active' : '' }}" href="{{ route('notificationlist') }}">
                            <i class="fa fa-building"></i>
                            <span>Notifications</span>
                        </a>
                        </li> -->
                        @elseif(Auth::user()->role == 'warehouse')
                        <li>
                        <a class="{{ Request::is('warehouse/dashboard*') ? 'active' : '' }}" href="{{ route('warehousedashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                        </li>
                        <li class="sub-menu">
                        <a href="javascript:;" class="{{ Request::is('warehouse/shipment*') ? 'active' : '' }}">
                            <i class="fa fa-bus"></i>
                            <span>Manage Shipment</span>
                        </a>
                        <ul class="sub">
                          <li class="{{ Request::is('warehouse/shipment/warehouse/list') ? 'active' : '' }}">
                              <a  href="{{ route('warehouseshiplistwarehouse') }}">Warehouse List</a>
                          </li>
                          <!-- <li class="{{ Request::is('warehouse/shipment/all/filter') ? 'active' : '' }}">
                              <a  href="{{ route('myfilterwarehouse') }}">Filter</a>
                            </li> -->
                        </ul>
                        </li>
                        @else
                        @permission('dashboard-list')
                        <li>
                      <a class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admindashboard') }}">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  @endpermission
                 {{--  <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>  --}}
                  @permission('shipment-list')
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/shipment*') ? 'active' : '' }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                      <ul class="sub">
                      	 <li class="{{ Request::is('admin/shipment/add') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentadd') }}">Add New</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/list') ? 'active' : '' }}">
                            <a  href="{{ route('shipmentlist') }}">Latest List</a>
                          </li>
                          <li class="{{ Request::is('admin/shipment/warehouse/list') ? 'active' : '' }}">
                              <a  href="{{ route('warehouseshiplist') }}">Warehouse List</a>
                          </li>
                            <li class="{{ Request::is('admin/shipment/all/list') ? 'active' : '' }}">
                              <a  href="{{ route('allshipmentlist') }}">Old List</a>
                            </li>
                             <!-- <li class="{{ Request::is('admin/shipment/all/filter') ? 'active' : '' }}">
                              <a  href="{{ route('myfilter') }}">Filter</a>
                            </li> -->
                      </ul>
                  </li>
                  @endpermission
                  @permission('invoice-list')
                    <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/invoice*') ? 'active' : '' }}">
                          <i class="fa fa-file-text"></i>
                          <span>Manage Invoices</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/invoice/new') ? 'active' : '' }}">
                            <a  href="{{ route('invoiceadd') }}">New Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/unpaid/list') ? 'active' : '' }}">
                            <a  href="{{ route('unpaidshipmentlist') }}">Unpaid Invoices</a>
                          </li>
                          <li class="{{ Request::is('admin/invoice/paid/list') ? 'active' : '' }}">
                              <a  href="{{ route('paidshipmentlist') }}">Paid Invoices</a>
                          </li>
                      </ul>
                  </li>
                  @endpermission
                  @permission('voucher-list')
                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/voucher*') ? 'active' : '' }}">
                          <i class="fa fa-square"></i>
                          <span>Manage Vouchers</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/voucher/credit') ? 'active' : '' }}">
                            <a  href="{{ route('voucherlcredit') }}">New Credit Voucher</a>
                          </li>
                          <li class="{{ Request::is('admin/voucher/debit') ? 'active' : '' }}">
                            <a  href="{{ route('voucherldebit') }}">New Debit Voucher</a>
                          </li>
                          <li class="{{ Request::is('admin/voucher/list') ? 'active' : '' }}">
                            <a  href="{{ route('voucherlist') }}">Voucher List</a>
                          </li>
                      </ul>
                  </li>
                  @endpermission
                  @permission('expense-list')
                  <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/expense*') ? 'active' : '' }}">
                          <i class="fa fa-inr"></i>
                          <span>Manage Expenses</span>
                      </a>
                      <ul class="sub">
                         <li class="{{ Request::is('admin/expense/add') ? 'active' : '' }}">
                            <a  href="{{ route('expenseadd') }}">Add Expense</a>
                          </li>
                          <li class="{{ Request::is('admin/expense/list') ? 'active' : '' }}">
                            <a  href="{{ route('expenselist') }}">Expense List</a>
                          </li>
                      </ul>
                  </li>
                  @endpermission
                  @permission('account-list')
                  <li>
                      <a class="{{ Request::is('admin/account*') ? 'active' : '' }}" href=" {{ route('accounts') }}">
                          <i class="fa fa-suitcase"></i>
                          <span>Manage Accounts</span>
                      </a>
                  </li>
                  @endpermission
                   {{-- <li>
                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('shipmentlist') }}">
                          <i class="fa fa-bus"></i>
                          <span>Manage Shipment</span>
                      </a>
                  </li>     --}}
                  @permission('transporter-list')
                  <li>
                      <a class="{{ Request::is('admin/transporter*') ? 'active' : '' }}" href="{{ route('transporterlist') }}">
                          <i class="fa fa-truck"></i>
                          <span>Manage Transporter</span>
                      </a>
                  </li>
                  @endpermission
                  @permission('company-list')
                  <li>
                      <a class="{{ Request::is('admin/company*') ? 'active' : '' }}" href="{{ route('companylist') }}">
                          <i class="fa fa-building"></i>
                          <span>Manage Company</span>
                      </a>
                  </li>
                  @endpermission
                  @permission('forwarder-list')
                   <li>
                      <a class="{{ Request::is('admin/forwarder*') ? 'active' : '' }}" href="{{ route('forwarderlist') }}">
                          <i class="fa fa-university"></i>
                          <span>Manage Forwarder</span>
                      </a>
                  </li>
                  @endpermission
                  @permission('employee-list')
                    <li>
                      <a class="{{ Request::is('admin/employee*') ? 'active' : '' }}" href="{{ route('employeelist') }}">
                          <i class="fa fa-users"></i>
                          <span>Manage Employee</span>
                      </a>
                  </li>
                  @endpermission
                  @permission('warehouse-list')
                  <li>
                      <a class="{{ Request::is('admin/warehouse*') ? 'active' : '' }}" href="{{ route('warehouselist') }}">
                          <i class="fa fa-home"></i>
                          <span>Manage Warehouse</span>
                      </a>
                  </li>
                  @endpermission
                  @permission('driver-list')
                   <li>
                      <a class="{{ Request::is('admin/driver*') ? 'active' : '' }}" href="{{ route('driverlist') }}">
                          <i class="fa fa-car"></i>
                          <span>Manage Driver</span>
                      </a>
                  </li>
                  @endpermission
                  @permission('roles-list')
                    <li class="@if(Request::is('admin/roles') ||Request::is('admin/roles/*') ) active @endif treeview">
                    <a href="{{url('admin/roles')}}">
                    <i class="fa fa-globe"></i><span>Manage Roles</span>
                    </a>
                    </li>
                    @endpermission
                    @permission('roleuser-list')
                        <li class="@if(Request::is('admin/roleuser') ||Request::is('admin/roleuser/*') ) active @endif treeview">
                        <a href="{{url('admin/roleuser')}}">
                        <i class="fa fa-user"></i><span>Manage Role Users</span>
                        </a>
                        </li>
                        @endpermission
                        @endif
{{--
                   <li>
                      <a class="{{ Request::is('admin/users*') ? 'active' : ''  }}"  href="{{ route('userlist') }}">
                          <i class="fa fa-user"></i>
                          <span>Manage Users</span>
                      </a>
                  </li>
                   <li>
                      <a  class="{{ Request::is('admin/restaurant*','branch*','menu*') ? 'active' : ''  }}"  href="{{ route('res_list') }}">
                          <i class="fa fa-building-o"></i>
                          <span>Manage Resturants</span>
                      </a>
                  </li>
                   <li>
                      <a  class="{{ Request::is('admin/report*') ? 'active' : ''  }}"  href="{{ route('report') }}">
                          <i class="fa fa-bar-chart-o"></i>
                          <span>Manage Reports</span>
                      </a>
                  </li>
                @endif
                 @if(Auth::user()->role == 1)
                   <li>
                      <a class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admindashboard') }}">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                   <li>
                      <a  class="{{ Request::is('owner/restaurant*','branch*','menu*') ? 'active' : ''  }}"  href="{{ route('myres_list') }}">
                          <i class="fa fa-building-o"></i>
                          <span>Manage Resturants</span>
                      </a>
                  </li>
                   <li>
                      <a  class="{{ Request::is('owner/report*','owner/report?*') ? 'active' : ''  }}"  href="{{ route('myreport') }}">
                          <i class="fa fa-bar-chart-o"></i>
                          <span>Manage Reports</span>
                      </a>
                  </li>
                 @endif --}}
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
      <!-- <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
      <script src="{{ asset('js/jquery.nicescroll.js') }}" type="text/javascript"></script> -->


    <script type="text/javascript">
        // $("html").mouseover(function() {
        //     $("html").getNiceScroll().resize();
        // });
//         function isMobileView() {
//     return $(window).width() < 768; // You can adjust the threshold value as needed
// }
// // Function to dynamically load a JavaScript file
// function loadMobileScript() {
//     var script = document.createElement('script');
//     script.src = "http://localhost/Github/tms/js/jquery.nicescroll.js"; // Replace with the path to your mobile JavaScript file
//     document.body.appendChild(script);
//     $("html").mouseover(function() {
//         $("html").getNiceScroll().resize();
//     });
//     $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});

// $("html").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});
// }
// // Function to update the display based on the result
// function updateDisplay() {
//     if (isMobileView()) {
//     } else {
//         loadMobileScript();
//     }
// }
// // Check and update the display when the page loads and when it's resized
// $(document).ready(function () {
//     updateDisplay(); // Check on page load
// });

    </script>