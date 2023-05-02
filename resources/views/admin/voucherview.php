

  @extends('layout.master')

  @section('title')
  Voucher Details | TMS
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
                            Voucher Detail
                             <div class="btn-group pull-right">
                                
                                <a href="{{ route('expenselist') }}" style="min-width: 20%; width: auto;" class="btn btn-primary "><i class="fa fa-reply"></i> Back</a>
                                </div>
                          </header>
                          <div class="panel-body">
                              <div class="form">
                                  
                                <form class="cmxform form-horizontal tasi-form" >
                                  <div class="form-group"></div>

                                   <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Voucher Type : {{$data->mytype }} </label>
                                  </div>


                                  <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Date : {{ date('d-m-Y',strtotime($data->dates))  }} </label>
                                  </div>

                                 

                                   <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">From : {{$data->from }} </label>
                                  </div>

                                   <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">To : {{$data->to }} </label>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Amount : {{$data->amount }} </label>
                                  </div>


                                  @if($data->type =="cash")

                                    <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">From Person : {{$data->cash_from_name }} </label>
                                  </div>

                                   <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Recieved Person : {{$data->cash_to_name }} </label>
                                  </div>

                                  @endif


                                   @if($data->type =="cheque")

                                    <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Cheque Number : {{$data->chequenumber }} </label>
                                  </div>

                                   <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Cheque Bank Name : {{$data->chequebankname }} </label>
                                  </div>

                                  @endif

                                   @if($data->type =="netbanking")

                                    <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Payment Type : {{$data->rtgs_paymentby }} </label>
                                  </div>

                                   <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Transaction ID : {{$data->rtgs_transaction }} </label>
                                  </div>

                                   <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Account Number : {{$data->rtgs_account_number }} </label>
                                  </div>

                                    <div class="form-group">
                                    <label class="control-label col-lg-1"></label>
                                  <label class="control-label col-lg-11">Bank Name : {{$data->rtgs_bank_name }} </label>
                                  </div>

                                  @endif


                                  
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