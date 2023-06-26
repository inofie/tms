<?php date_default_timezone_set("Asia/Kolkata"); ?>

@extends('layout.master')

@section('title')
Shipment Summary | TMS
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


@section('content')
      <!--main content start-->
       <section id="main-content">
        <section class="wrapper">
            <!-- page start-->

            <div class="row">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button> 
                            <strong>{{ $message }}</strong>
                    </div>
                    @endif


                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button> 
                            <strong>{{ $message }}</strong>
                    </div>
                    @endif


                    @if ($message = Session::get('warning'))
                    <div class="alert alert-warning alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button> 
                      <strong>{{ $message }}</strong>
                    </div>
                    @endif


                    @if ($message = Session::get('info'))
                    <div class="alert alert-info alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button> 
                      <strong>{{ $message }}</strong>
                    </div>
                    @endif


                    @if ($errors->any())
                    <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert">×</button> 
                      Please check the form below for errors
                    </div>
                    @endif
                    <section class="panel">
                        <header class="panel-heading" style="border-bottom: 1px solid #2a3542;line-height: 30px;">
                        
                          Shipment Summary ({{$shipment_no}})
                          
                           <div class="btn-group pull-right">
                               <a href="{{ URL::previous() }}">
                                <button  class="btn btn-primary">
                                    <i class="fa fa-reply"></i> Back
                                </button>
                              </a>
                              </div>
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                
                              <form class="cmxform form-horizontal tasi-form" >

                              <div class="adv-table" style="padding: 1%;">
                             <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                <tr>
                                    <!-- <th>Shipment No</th> -->
                                    <th>Description</th>
                                    <th>Flag</th>
                                    <th>Time</th>
                                    <th>Time Difference</th>
                                </tr>
                                </thead>
                                <tbody>

                              @foreach($data as $value)
                               
                              <!-- <td class="center" style="vertical-align: middle;"><b>{{ $value->shipment_no }}</b></td> -->
                              
                              <td  style="vertical-align: middle;">{{ $value->description }}</td>
                              <td  style="vertical-align: middle;">{{ $value->flag }}</td>
                      
                              <td  style="vertical-align: middle;">{{ ($value->created_at->format('d-m-Y h:i A')) }}</td>
                              
                              </td>
                                </tr>
                                 @endforeach
                                </form>
                         
                                </tbody>
                            </table>

                        </div>
                            </div>

                            
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
      <!--main content end-->

@endsection

@section('js1')

<script type="text/javascript" language="javascript" src="{{ asset('js/jquery.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.js') }}"></script>

@endsection

@section('js3')

<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/data-tables/DT_bootstrap.js') }}"></script>
@endsection
@section('js4')

<script type="text/javascript">
$(document).ready(function() {
  $('#editable-sample').DataTable( {
     "aaSorting": [[ 0, "desc" ]],
      "bPaginate": false,
      "bLengthChange": true,
      "bFilter": false,
      "bInfo": false,
      
     /* "lengthChange": true,
    "lengthMenu": [ 10, 25, 50, 75, 100 ],
      dom: 'Bfrtip',
      buttons: [      
          'excelHtml5',
          'csvHtml5',     
      ]*/
  } );

  $('#editable-sample_wrapper').css( "maxWidth",'100%').css('overflow','auto');
  
} );
</script>




@endsection