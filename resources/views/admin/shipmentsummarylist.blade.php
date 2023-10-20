<?php date_default_timezone_set("Asia/Kolkata"); ?>

@extends('layout.master')

@section('title')
Shipment Summary | TMS
@endsection

@section('css2')
<link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />

<link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
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
                                    <th>Title</th>
                                    
                                    <th>Image</th>
                                    <th>Time</th>
                                    <th>Time Difference</th>
                                    <th>Created Date</th>
                                    
                                </tr>
                                </thead>
                                <tbody>

                              @foreach($data as $value)
                               
                              <!-- <td class="center" style="vertical-align: middle;"><b>{{ $value->shipment_no }}</b></td> -->
                              
                              <td  style="vertical-align: middle;">{{ $value->description }}</td>
                              <td  style="vertical-align: middle;">{{ $value->flag }}</td>
                             
                              <td><img src="{{ asset('public/uploads') }}/{{ $value->image }}" width="50px" alt="" class="zoom"></td>
                              <td  style="vertical-align: middle;">{{ ($value->created_at->format('d-m-Y h:i A')) }}</td>
                              <td  style="vertical-align: middle;">{{ $value->timedifference }}</td>
                              <td style="vertical-align: middle;">{{ $value->created_at }}</td>
                             
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

@section('js4')

<script type="text/javascript">
$(document).ready(function() {
  $('#editable-sample').DataTable( {
    "aaSorting": [[ 5, "asc" ]],
       "columnDefs":
           [
               {
                   "targets": [5],
                   "visible": false, 
               },
           ],
      "bPaginate": true,
      "bLengthChange": true,
      "bFilter": false,
      "bInfo": true,
      
    "lengthChange": true,
    "lengthMenu": [ 10, 25, 50, 75, 100 ],
      dom: 'Bfrtip',
      buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                  columns: [0, 1, 3, 4 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [0, 1, 3, 4 ]
                }
            },
           
            
        ]
  } );

  $('#editable-sample_wrapper').css( "maxWidth",'100%').css('overflow','auto');
  
} );
</script>




@endsection