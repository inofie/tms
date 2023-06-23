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
                                 <a href="{{ url()->previous() }}">
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
                                  <label class="control-label col-lg-6">Shipment Type : @if($data->export == 1) Export @else Import @endif</label>
                                  <label class="control-label col-lg-6">Shipment Type : @if($data->lcl == 1) LCL @else FCL @endif</label>
                                   </div>

                                   <div class="form-group" style="border-bottom: 1px solid #2a3542;">
                                  <label class="control-label col-lg-12">Company : {{ $data->company_name }}</label>
                                   </div>

                                   <div class="form-group" style="border-bottom: 1px solid #2a3542;">
                                  <label class="control-label col-lg-4">From No : {{ $data->from1 }}</label>
                                  <label class="control-label col-lg-4">To : {{$data->to1 }}</label>
                                  <label class="control-label col-lg-4">To : {{$data->to2 }}</label>
                                   </div>

                                    <div class="form-group">
                                  <label class="control-label col-lg-12">Truck Type : {{ $data->trucktype_name }}</label>
                                 
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


                                   <div class="form-group" >
                                    <div class="control-label col-lg-6 col-sm-12">
                                      <label >Shipment Load Proof:</label>
                                      <img  style="width: 25%;" src="{{ asset('uploads/').'/'.$load_image}}">
                                    </div>
                                     <div class="control-label col-lg-6 col-sm-12">
                                      <label >Shipment Unload Proof:</label>
                                      <img  style="width: 25%;" src="{{ asset('uploads/').'/'.$unload_image}}">
                                    </div>
                                    
                                    
                                  
                                   </div>
                                  </form>
                    
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