<?php date_default_timezone_set("Asia/Kolkata"); ?>

@extends('layout.master')

@section('title')
Live Board | TMS
@endsection

@section('css2')
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-datepicker/css/datepicker.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-timepicker/compiled/timepicker.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-colorpicker/css/colorpicker.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-datetimepicker/css/datetimepicker.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/jquery-multi-select/css/multi-select.css') }}" />
@endsection

<style>
  /* body{background-color: #8f3f65;} */
  .bgcolor
  {
    background-color: #8f3f65;
    overflow : hidden;
  }
  .todo-project {
      top: 0 !important;
      bottom: 0 !important;
      overflow-y: hidden !important;
      overflow-x: auto !important;
      -ms-transform: translate(0%, 0%) !important;
      transform: translate(0%, 0%) !important;
      height:90vh;
  }
  .todo-project .container{
    padding-left: 0;
    padding-right: 0;
    margin-right: 0px;
    margin-left: 0px;
  }

  .card {
      position: relative;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-direction: column;
      flex-direction: column;
      min-width: 0;
      word-wrap: break-word;
      background-clip: border-box;
      border: 1px solid rgba(0,0,0,.125);
      border-radius: 15px;
      padding:10px;
      width: 350px;
      margin: 10px 0;
  }
  .box-height {
      height: 350px;
  }
  .file-icon{float: right;}

  .mt-5{margin-top:15px;}
  .text-white{color:#ffffff;}
  .bg-green{background: #008000;}
  .bg-red {background-color: #FF0000;}
  .bg-info {background-color: #67bfcc;}
  .bg-orange {background-color: #FFA500;}
  .bg-yellow {background-color: #FFFF00;}
  .bg-primary {background-color: #007bff;}
  .bg-magenta {background-color: #FF00FF;}

  .bg-danger .project-list ul li {background-color: #e87c87;}
  .bg-gray-dark .project-list ul li {background-color: #5a5a5a;}
  .bg-gray .project-list ul li {background-color: #a0a6ab;}
  .bg-primary .project-list ul li {background-color: #5aaaff;}
  .bg-teal .project-list ul li {background-color: #6fdcbc;}

  .card-header:first-child {
      border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
  }
  .card-header {
      border-bottom: 1px solid rgba(0,0,0,.125);
      padding: 0.75rem 1.25rem;
      position: relative;
      border-top-left-radius: 0.25rem;
      border-top-right-radius: 0.25rem;
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 10px;
  }
  .card-header span i{float: right;}
  .box-display{display:flex;}
  .card-body {
      -ms-flex: 1 1 auto;
      flex: 1 1 auto;
      min-height: 1px;
      padding: 1.25rem;
  }
  .card-body {
      overflow-y: auto;
      padding: 0;
      position: relative;
      overflow-x: hidden;
  }
  .project-list {
      width: 95%;
  }
  .project-list ul li {
      background:#22272b;;
      padding: 1.25rem;
      margin-bottom: 10px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
  }
  .card-footer {
      padding: 15px 1.25rem;
      border-top: 1px solid rgba(0,0,0,.125);
      font-size: 15px;
      font-weight: 600;
      margin-top:10px;
  }
  .card-footer .add-card{cursor: pointer;color: #fff;}
  .card-footer .add-card:hover {
      color: #fff;
  }
  .card-footer:last-child {
      border-radius: 0 0 calc(0.25rem - 1px) calc(0.25rem - 1px);
  }

  /* width */
  ::-webkit-scrollbar {
    width: 5px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
    background: #a74975;
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #ab5d82;
  }

  @media only screen and (max-width: 1200px) {
      .card{margin-bottom: 20px;}
  }
  .colorBlack
  {
    color:#000000;
  }
  .colorWhite
  {
    color:#ffffff;
  }
</style>


@section('content')
      <!--main content start-->
<section id="main-content" class="bgcolor">
  <section class="wrapper todo-project">
    <div class="container">
      <div class="row box-display">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="card bg-green text-white box-height">
            <div class="card-header colorWhite">Pickup Confirm<span><i class="fa fa-ellipsis-h"></i></span></div>
            <div class="card-body">
              <div class="project-list">
              <ul>
                @foreach($pickup as $value)
                <li>{{$value->shipment_no}},{{$value->from1}} - {{$value->to1}}</li>
                @endforeach
              </ul>
              </div>
            </div>
            <div class="card-footer">
              <!-- <a class="add-card">+ Add a card</a> -->
              <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card bg-orange text-white box-height">
              <div class="card-header colorBlack">Truck Transfer<span><i class="fa fa-ellipsis-h"></i></span></div>
              <div class="card-body">
                <div class="project-list">
                  <ul>
                    @foreach($trucktransfer as $value)
                      <li>{{$value->shipment_no}},{{$value->from1}} - {{$value->to1}}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            <div class="card-footer">
            <!-- <a class="add-card">+ Add a card</a> -->
            <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
          </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card bg-yellow text-white box-height">
              <div class="card-header colorBlack">Reach at Company<span><i class="fa fa-ellipsis-h"></i></span></div>
              <div class="card-body">
                <div class="project-list">
                <ul>
                  @foreach($reachcompany as $value)
                  <li>{{$value->shipment_no}},{{$value->from1}} - {{$value->to1}}</li>
                  @endforeach
              </ul>
                </div>
              </div>
            <div class="card-footer">
            <!-- <a class="add-card">+ Add a card</a> -->
            <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
          </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card bg-red text-white box-height">
              <div class="card-header colorWhite">Damage/Missing/Hold<span><i class="fa fa-ellipsis-h"></i></span></div>
              <div class="card-body">
                <div class="project-list">
                  <ul>
                      @foreach($damagemissinghold as $value)
                      <li>{{$value->shipment_no}},{{$value->from1}} - {{$value->to1}}</li>
                    @endforeach
                </ul>
                </div>
              </div>
            <div class="card-footer">
            <!-- <a class="add-card">+ Add a card</a> -->
            <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
          </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card bg-primary text-white box-height">
              <div class="card-header colorWhite">Reach at port<span><i class="fa fa-ellipsis-h"></i></span></div>
              <div class="card-body">
                <div class="project-list">
                <ul>
                  @foreach($reachport as $value)
                    <li>{{$value->shipment_no}},{{$value->from1}} - {{$value->to1}}</li>
                  @endforeach
              </ul>
                </div>
              </div>
            <div class="card-footer">
            <!-- <a class="add-card">+ Add a card</a> -->
            <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
          </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card bg-magenta text-white box-height">
              <div class="card-header colorBlack">Delivered<span><i class="fa fa-ellipsis-h"></i></span></div>
              <div class="card-body">
                <div class="project-list">
                  <ul>
                    @foreach($delivered as $value)
                      <li>{{$value->shipment_no}},{{$value->from1}} - {{$value->to1}}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <div class="card-footer">
                <!-- <a class="add-card">+ Add a card</a> -->
                <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </section>
</section>
<!--main content end-->
@endsection
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
@section('js1')
<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.js') }}"></script>

@endsection

@section('js3')
<!--
<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/data-tables/DT_bootstrap.js') }}"></script> -->
@endsection
<script>
  // ready -> loadhtmldata(0);
  // function loadhtmldata(id) {
  //   ajax -> success -> 1) count , html data
  //   2) count -> 6 hidden save
  // }

  // setinterval -> 30 second -> checknewdata();

  // function checknewdata() {
  //   ajax -> count - loadhtmldata hidden count -> diffrence
  //   status1 -> loadhtmldata(1);
  // }
  //   var time = new Date().getTime();
  //   $(document.body).bind("mousemove keypress", function(e) {
  //   time = new Date().getTime();
  //  });
  //   function refresh() {
  //   if(new Date().getTime() - time >= 60000)
  //       window.location.reload(true);
  //   else
  //       setTimeout(refresh, 10000);
  //  }
  //   setTimeout(refresh, 10000);
  // function autoRefreshPage()
  //   {
  //     // alert(1);
  //       window.location = window.location.href;
  //   }
  //   setInterval('autoRefreshPage()', 10000);
</script>