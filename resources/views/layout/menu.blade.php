<aside>

          <div id="sidebar"  class="nav-collapse ">

              <!-- sidebar menu start-->

              <ul class="sidebar-menu" id="nav-accordion">



                @if(Auth::user()->role == 'admin' )
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
                          <span>Manage Shipmant</span>
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
                             <li class="{{ Request::is('admin/shipment/all/filter') ? 'active' : '' }}">
                              <a  href="{{ route('myfilter') }}">Filter</a>
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

                          <span>Manage Acoounts</span>

                      </a>

                  </li>  
                   {{-- <li>

                      <a class="{{ Request::is('admin/shipment*') ? 'active' : '' }}" href="{{ route('shipmentlist') }}">

                          <i class="fa fa-bus"></i>

                          <span>Manage Shipmant</span>

                      </a>

                  </li>     --}}   

                  <li>

                      <a class="{{ Request::is('admin/transporter*') ? 'active' : '' }}" href="{{ route('transporterlist') }}">

                          <i class="fa fa-truck"></i>

                          <span>Manage Transport</span>

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
                          <span>Manage Shipmant</span>
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

                          <span>Manage Shipmant</span>

                      </a>

                  </li>     --}} 


                   <li class="sub-menu">
                      <a href="javascript:;" class="{{ Request::is('admin/transporter*') ? 'active' : '' }}">
                          <i class="fa fa-truck"></i>
                          <span>Manage Transport</span>
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
                          <span>Manage Shipmant</span>
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
                          <span>Manage Acoounts</span>
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