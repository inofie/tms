      <!--header start-->
      <script src="{{ asset('js/jquery.nicescroll.js') }}" type="text/javascript"></script>
      <header class="header white-bg">

          <div class="sidebar-toggle-box">

              <div data-original-title="Toggle Navigation" data-placement="right" class="fa fa-bars tooltips"></div>

          </div>

          @if(Auth::user()->role == 'admin' || Auth::user()->role == 'employee')

              
              <a href="{{ route('admindashboard') }}" class="logo" ><span>T</span>M<span>S</span></a>

          @endif

           @if(Auth::user()->role == 'forwarder')

              
              <a href="" class="logo" ><span>T</span>M<span>S</span></a>

          @endif

           @if(Auth::user()->role == 'transporter')

              
              <a href="" class="logo" ><span>T</span>M<span>S</span></a>

          @endif
          
          
          <!--logo end-->

          <div class="nav notify-row" id="top_menu">

            <!--  notification start -->

         {{--  @include('layout.topmenu') --}}

          

          </div>

          <div class="top-nav ">

              <ul class="nav pull-right top-menu">

                 {{--  <li>

                      <input type="text" class="form-control search" placeholder="Search">

                  </li> --}}
                  <li><a href="{{ route('notificationlist') }}"> <i class="fa fa-bell-o"></i></a></li>
                  <!-- user login dropdown start-->
                   
                  <li class="dropdown">

                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                          {{-- <img alt="" src="img/avatar1_small.jpg"> --}}

                          <span class="username">{{ Auth::user()->username }}</span>

                          <b class="caret"></b>

                      </a>
                      

                      <ul class="dropdown-menu extended logout">

                          <div class="log-arrow-up"></div>

                       {{--    <li class="text-center" style="width: 100%"><a href="{{ route('changepassword') }}"><i class=" fa fa-key"></i>Change Password</a></li> --}}

                           <!-- <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>

                          <li><a href="#"><i class="fa fa-bell-o"></i> Notification</a></li>  -->

                          <li><a href="{{ route('logout') }}"><i class="fa fa-user"></i> Log Out</a></li>

                      </ul>

                  </li>

                  <!-- user login dropdown end -->

              </ul>

          </div>

      </header>

      <!--header end-->