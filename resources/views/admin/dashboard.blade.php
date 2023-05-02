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
                      <a href="{{ route('accounts')}}">
                        <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-user"></i>
                          </div>
                          <div class="value">
                              <h1 class="count5">
                                  0
                              </h1>
                              <p>P/L Report</p>
                          </div>
                      </section>
                    </a>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <a href="{{ route('shipmentlist')}}">
                      <section class="panel">
                          <div class="symbol red">
                              <i class="fa fa-tags"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2">
                                  0
                              </h1>
                              <p>Pending Shipment</p>
                          </div>
                      </section></a>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <a href="{{ route('shipmentlist')}}">
                      <section class="panel">
                          <div class="symbol yellow">
                              <i class="fa fa-shopping-cart"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count3">
                                  0
                              </h1>
                              <p>OnTheWay Shipment</p>
                          </div>
                      </section>
                    </a>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <a href="{{ route('unpaidshipmentlist')}}">
                      <section class="panel">
                          <div class="symbol blue">
                              <i class="fa fa-bar-chart-o"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count4">
                                  0
                              </h1>
                              <p>Bill Status</p>
                          </div>
                      </section>
                    </a>
                  </div>
              </div>
              <!--state overview end-->

               <section class="panel">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="#" >
                                    
                                      <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Company :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="company_id" id="companyform" required="required"> 
                                                   <option value=""> -- Please Select Company -- </option>
                                                      @foreach($company as $value)
                                                      @if($company_id == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
              
              
              

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
     countUp5({{ $data['pl_report'] }});
     countUp2({{ $data['pending'] }});
     countUp3({{ $data['ontheway'] }});
     countUp4({{ $data['bill_status'] }});
   </script>

<script type="text/javascript">
  $('#companyform').change(function(){

    $('#comform').submit();

  });
</script>

    <script src="{{ asset('js/flot-chart.js')}}"></script>
    
@endsection