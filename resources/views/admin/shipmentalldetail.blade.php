  <?php date_default_timezone_set("Asia/Kolkata"); ?>

  @extends('layout.master')

  @section('title')
  Shipment Details | TMS
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
                            Shipment Detail
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
                                 
                                  <div class="form-group">
                                  <label class="control-label col-lg-6">Shipment No : <b>{{ $data->shipment_no }}</b></label>
                                  <label class="control-label col-lg-6">Date : {{date('d-m-Y',strtotime($data->date)) }}</label>
                                   </div>

                                    <div class="form-group">
                                  <label class="control-label col-lg-6">Shipment Type : @if($data->exports != 0) Export @else Import @endif</label>
                                  <label class="control-label col-lg-6">Shipment Type : @if($data->lcl == 1) LCL @else FCL @endif</label>
                                   </div>

                                   <div class="form-group" style="border-bottom: 1px solid #2a3542;">
                                  <label class="control-label col-lg-6">Company : {{ $data->company_name }}</label>
                                   <label class="control-label col-lg-6">Amount : {{ $data->invoice_amount }}</label>
                                   </div>

                                   <div class="form-group" style="border-bottom: 1px solid #2a3542;">
                                  <label class="control-label col-lg-4">From No : {{ $data->from1 }}</label>
                                  <label class="control-label col-lg-4">To : {{$data->to1 }}</label>
                                  <label class="control-label col-lg-4">To : {{$data->to2 }}</label>
                                   </div>

                                    <div class="form-group">
                                  <label class="control-label col-lg-6">Truck Type : {{ $data->trucktype_name }}</label>
                                  <label class="control-label col-lg-6">Forwarder : {{ $data->forwarder_name}}</label>
                                   </div>

                                   <div class="form-group">
                                  <label class="control-label col-lg-12">Transporter : {{ $data->transporters_list }}</label>
                                  </div>

                                  <div class="form-group">
                                  <label class="control-label col-lg-12">Driver : {{ $data->drivers_list }}</label>
                                  </div>

                                  <div class="form-group">
                                  <label class="control-label col-lg-12">Truck Number : {{ $data->truck_no }}</label>
                                  </div>


                                  <div class="form-group" style="border-bottom: 1px solid #2a3542;">
                                  <label class="control-label col-lg-6">Consignor : {{ $data->consignor }}<br>Consignor Address : {{ $data->consignor_address }}</label>
                                 <label class="control-label col-lg-6">Consignee : {{ $data->consignee }}<br>Consignee Address : {{ $data->consignee_address }}</label>
                                   </div>

                                <div class="form-group" >
                                  <label class="control-label col-lg-6">No. of Package : {{ $data->package }}</label>
                                 <label class="control-label col-lg-6">Total Gross Weight : {{ $data->weight }}</label>
                               </div>

                                 <div class="form-group" style="border-bottom: 1px solid #2a3542;">
                                  <label class="control-label col-lg-2">Cargo Description : </label> <label class="control-label col-lg-1"><?php echo  html_entity_decode($data->description); ?> </label>
                                </div>

                                 <div class="form-group" style="border-bottom: 1px solid #2a3542;">
                                  <label class="control-label col-lg-4">Shipper Invoice No : {{ $data->shipper_invoice }}</label>
                                  <label class="control-label col-lg-4">Forwarder Reference No : {{$data->forwarder_ref_no }}</label>
                                  <label class="control-label col-lg-4">B/E No : {{$data->b_e_no }}</label>
                                   </div>
                                   <div class="form-group" ></div>
                                  </form>
                      

               
                        <div class="adv-table table-responsive" style="padding: 1%;">
                             <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                <tr>
                                    <th class="center">Truck No.</th>
                                    <th class="center">Loaded Photo</th>
                                    <th class="center">Unload Photo</th>
									                  <th class="center">Pickup Confirmation Photo</th>
                                    <th class="center">Reach Company Photo</th>
                                    <th class="center">Damage Cargo Photo</th>
									                  <th class="center">Document Received Photo</th>
                                    <th class="center">Missing Pkg Photo</th>
                                    <th class="center">Hold Photo</th>
                                    <th class="center">Other Photo</th>
                                    <th class="center">TruckTransfer Reach at port</th>
                                    <th class="center">Reach at port</th>
                                    <th class="center">TruckTransfer Reach at company</th>
                                    <th class="center">Load Container</th>
                                    <th class="center">Load Cargo</th>
                                    <th class="center">Unload Container</th>
                                    <th class="center">Unload Cargo</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($trucks as $value)
                                  <tr>
                                    <td class="center" style="vertical-align: middle;">{{ $value->truck_no }}</td>
                                   @if($value->loaded_photo != "")
                                    <td class="center"><a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->loaded_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->loaded_photo }}" width="100px"></a></td>
                                     @else 
                                     <td class="center"></td>

                                     @endif
                                     @if($value->unloaded_photo != "")
                                     <td class="center"><a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloaded_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->unloaded_photo }}" width="100px"></a></td>
                                    @else 
                                     <td class="center"></td>

                                     @endif
									  <td class="center">@if($value->pickup_conformation != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloaded_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->pickup_conformation }}" width="100px"></a>@endif</td>
									  <td class="center">@if($value->reach_company != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloaded_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->reach_company }}" width="100px"></a>@endif</td>
									  <td class="center">@if($value->damage_cargo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloaded_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->damage_cargo }}" width="100px"></a>@endif</td>
									  <td class="center">@if($value->document_received != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloaded_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->document_received }}" width="100px"></a>@endif</td>
									  <td class="center">@if($value->missing_pkg != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloaded_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->missing_pkg }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->hold_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->hold_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->hold_photo}}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->other_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->other_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->other_photo }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->trucktransreachprt_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->trucktransreachprt_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->trucktransreachprt_photo }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->reachprt_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->reachprt_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->reachprt_photo }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->trucktransreachcompany_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->trucktransreachcompany_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->trucktransreachcompany_photo }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->loadcontainer_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->loadcontainer_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->loadcontainer_photo }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->loadcargo_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->loadcargo_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->loadcargo_photo }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->unloadcargo_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloadcargo_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->unloadcargo_photo }}" width="100px"></a>@endif</td>
                    <td class="center">@if($value->unloadedcontainer_photo != "") <a target="_blank" href="{{ getenv('APP_URL') }}/uploads/{{ $value->unloadedcontainer_photo }}"> <img src="{{ getenv('APP_URL') }}/uploads/{{ $value->unloadedcontainer_photo }}" width="100px"></a>@endif</td>
                                  </tr>
                                  @endforeach
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

    
    
} );
</script>




@endsection