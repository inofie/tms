@extends('layout.master')

@section('title')
Dashboard | TMS
@endsection
@section('css2')
<link href="{{ asset('assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css')}}" rel="stylesheet" type="text/css" media="screen"/>
<link rel="stylesheet" href="{{ asset('css/owl.carousel.css')}}" type="text/css">
@endsection	


@section('content')
<!--main content start-->
   <section id="main-content">
          <section class="wrapper">
              <!--state overview start-->
              <div class="row state-overview">
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-user"></i>
                          </div>
                          <div class="value">
                              <h1 class="count">
                                  0
                              </h1>
                              <p>New Users</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol red">
                              <i class="fa fa-tags"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2">
                                  0
                              </h1>
                              <p>Sales</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol yellow">
                              <i class="fa fa-shopping-cart"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count3">
                                  0
                              </h1>
                              <p>New Order</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol blue">
                              <i class="fa fa-bar-chart-o"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count4">
                                  0
                              </h1>
                              <p>Total Profit</p>
                          </div>
                      </section>
                  </div>
              </div>
              <!--state overview end-->

              
              
              
              

          </section>
      </section>
      <!--main content end-->
@endsection

@section('js1')
{{-- <script src="{{ asset('js/jquery.js') }}"></script> --}}
<script src="{{ asset('js/jquery-1.8.3.min.js')}}"></script>
@endsection

@section('js2')
<script src="{{ asset('js/jquery.sparkline.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js')}}"></script>
<script src="{{ asset('js/owl.carousel.js')}}" ></script>
<script src="{{ asset('js/jquery.customSelect.min.js')}}" ></script>
<script src="{{ asset('assets/flot/jquery.flot.js')}}"></script>
    <script src="{{ asset('assets/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{ asset('assets/flot/jquery.flot.pie.js')}}"></script>
    <script src="{{ asset('assets/flot/jquery.flot.stack.js')}}"></script>
    <script src="{{ asset('assets/flot/jquery.flot.crosshair.js')}}"></script>
@endsection

@section('js3')
<!--script for this page-->
     <script src="{{ asset('js/sparkline-chart.js')}}"></script>
   
    <script src="{{ asset('js/count.js')}}"></script>
    
@endsection

@section('js4')
<!--script for this page-->
   <script type="text/javascript">
     countUp(4);
     countUp2(9);
     countUp3(3);
     countUp4(103);
   </script>
    <script src="{{ asset('js/flot-chart.js')}}"></script>
    
@endsection