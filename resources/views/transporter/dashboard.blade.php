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
                              <i class="fa fa-truck"></i>
                          </div>
                          <div class="value">
                              <h1 class="count">
                                  0
                              </h1>
                              <p>Total Shipment</p>
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
     countUp({{ $data['total'] }});
   
   </script>
    <script src="{{ asset('js/flot-chart.js')}}"></script>
    
@endsection