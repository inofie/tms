@extends('layout.master')
@section('title')
All Voucher List | Helard
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
      <section id="main-content">
          <section class="wrapper">
            <section class="panel">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="{{ route('voucherlist') }}" >
                                      
                                   
                                    <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Invoice No :</label>
                                            <div class="col-lg-6">
                                                <!-- <select class="form-control" name="invoice_no" id="invoice_no" > 
                                                   <option value=""> -- Please Select Invoice No -- </option>
                                                      @foreach($all_invoice as $value)
                                                      @if($invoice_nos == $value->id)
                                                      <option selected="selected" value="{{ $value->invoice_no }}">{{ $value->invoice_no }}</option>
                                                      @else
                                                      <option value="{{ $value->invoice_no }}">{{ $value->invoice_no }}</option>
                                                      @endif
                                                      @endforeach

                                                </select> -->
                                               
                                                <input type="text" class="form-control" name="invoice_no" id="invoice_no" value="{{ $invoice_nos }}">
                                               
                                              </div>
                                        </div>

                                    

                                         <div class="form-group save_cancle">
                                            <div class="col-lg-10 center">
                                                <button class="btn btn-success" id="searchbtn" type="submit">Save</button>
                                                 
                                                <a  href="{{ route('voucherlist') }}" class="btn btn-default" type="reset">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
            
          
              
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
                  <header class="panel-heading">
                    Voucher List
                  </header>
                  <!-- <header class="panel-heading" style="line-height: 30px;">
                      Voucher List
                       <div class="btn-group pull-right" style="padding: 0px 0.3%;">
                          <a style="min-width: 20%; width: auto;" class="btn btn-danger" href="{{ route('voucherldebit') }}">
                          <b>New Debit Voucher </b>
                          </a>
                        </div>
                        <div class="btn-group pull-right" style="padding: 0px 0.3%;">
                          <a style="min-width: 20%; width: auto;" class="btn btn-primary" href="{{ route('voucherlcredit') }}">
                          <b>New Credit Voucher </b>
                          </a>
                        </div>
                  </header> -->
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                            <div class="clearfix">
                              <div class="btn-group pull-right" style="padding: 0px 0.3%;">
                                 <a href="{{ route('voucherldebit') }}"> <button  class="btn btn-success">
                                 New Debit Voucher <i class="fa fa-plus"></i>
<<<<<<< HEAD
                                  </button></a></br>
=======
                                  </button></a>
>>>>>>> 01ab8dae6d4008aac2eceb550a6f5620f6f77bb1
                              </div>
                              <div class="btn-group pull-right" style="padding: 0px 0.3%;">
                                 <a href="{{ route('voucherlcredit') }}"> <button  class="btn btn-success">
                                 New Credit Voucher <i class="fa fa-plus"></i>
                                  </button></a>
                              </div>
                            </div>
                            <div class="space15"></div>
                              {!! $html->table(['class' => 'table table-bordered', 'id' => 'VoucherDataTable']) !!}
                      </div>
                  </div>
              </section>
              <!-- page end-->
          </section>
      </section>
      <!--main content end-->
@endsection
@section('js4')
{!! $html->scripts() !!}
<script type="text/javascript">
  $(document.body).on('click','#searchbtn', function() {
    var invoice_no=$('#invoice_no').val();
    var date=$('#date').val();
    $('#VoucherDataTable').on('preXhr.dt', function ( e, settings, data ) {
      data.invoice_no = invoice_no;
      data.date = date;
    });
    window.LaravelDataTables["VoucherDataTable"].draw();
  });
  function deleteItem(id){
    var r = confirm("Are You Sure Delete It!");
      if (r == true) {
        $("#delete"+id).submit();
      }
    }
  $(document).ready(function() {
    $('#editable-sample').DataTable( {
       "aaSorting": [[ 0, "desc" ]],
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