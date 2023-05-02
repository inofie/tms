<aside>

          <div id="sidebar"  class="nav-collapse ">

              <!-- sidebar menu start-->

              <ul class="sidebar-menu" id="nav-accordion">



                @if(Auth::user()->role == 0)


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