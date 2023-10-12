  @extends('layout.master')

  @section('title')
  Add Expense | TMS
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


  @section('js0')

  <script type="text/javascript" language="javascript" src="{{ asset('js/jquery.js')}}"></script>

  @endsection


  @section('content')
        <!--main content start-->
         <section id="main-content">
          <section class="wrapper site-min-height">


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
                <!-- page start-->
              
                        <section class="panel">
                            <header class="panel-heading ">
                               Add Expense
                              
                            </header>
                            <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" method="post" action="{{ route('expensesave') }}">
                                      @csrf

                                      <div class="form-group " id="company_id">
                                            <label for="company_id" class="control-label col-lg-2"> Company :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="fcompany" name="company_id" > 
                                                   <option value=""> -- Please Select Company -- </option>
                                                      @foreach($company as $value)
                                                      @if(old('company_id') == $value->id)
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>
                                            </div>
                                        </div>
                                         
                                         <div class="form-group " id="transporter_div">
                                            <label for="transporter_id" class="control-label col-lg-2"> Transporter :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="transporter" name="transporter_id" > 
                                                   <option value=""> -- Please Select Transporter -- </option>
                                                      @foreach($transporter as $value)
                                                      @if(old('transporter_id') == $value->id)
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>
                                            </div>
                                        </div>

                                         <div class="form-group " id="forwarder_div">
                                            <label for="forwarder_id" class="control-label col-lg-2"> Forwarder :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="forwarder" name="forwarder_id" > 
                                                   <option value=""> -- Please Select Forwarder -- </option>
                                                      @foreach($forwarder as $value)
                                                      @if(old('forwarder_id') == $value->id)
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="company_id" class="control-label col-lg-2"> Type :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="type" name="type" required="required"> 
                                                  @if(old('type') == 'cheque')
                                                  <option value="cheque" selected="selected">Cheque</option>
                                                  <option value="netbanking">Net Banking</option>
                                                  <option value="cash">Cash</option>
                                                  <option value="NEFT">NEFT</option>
                                                  <option value="IMPS">IMPS</option>
                                                  <option value="UPI">UPI</option>
                                                  @elseif(old('type') == 'netbanking')
                                                  <option value="cheque">Cheque</option>
                                                  <option value="netbanking" selected="selected">Net Banking</option>
                                                  <option value="cash">Cash</option>
                                                  <option value="NEFT">NEFT</option>
                                                  <option value="IMPS">IMPS</option>
                                                  <option value="UPI">UPI</option>
                                                  @elseif(old('type')=='cash')
                                                  <option value="cheque">Cheque</option>
                                                  <option value="netbanking">Net Banking</option>
                                                  <option value="cash" selected="selected">Cash</option>
                                                  <option value="NEFT">NEFT</option>
                                                  <option value="IMPS">IMPS</option>
                                                  <option value="UPI">UPI</option>
                                                  @else
                                                  <option value="cheque" selected="selected">Cheque</option>
                                                  <option value="netbanking">Net Banking</option>
                                                  <option value="cash">Cash</option>
                                                  <option value="NEFT">NEFT</option>
                                                  <option value="IMPS">IMPS</option>
                                                  <option value="UPI">UPI</option>
                                                  @endif
                                                </select>

                                               
                                            </div>
                                        </div>


                                        <div class="form-group">
                                                <label class="control-label col-lg-2">Date<span style="color: red">*</span>:</label>
                                                <div class="col-md-3 col-lg-3">
                                                 
                                                  @if(old('date'))
                                                  <input class="form-control form-control-inline input-medium default-date-picker" size="16" id="ship_date" name="date" type="text" value="{{ old('date') }}">

                                                    @else 

                                                    <input class="form-control form-control-inline input-medium default-date-picker" size="16" id="ship_date" name="date" type="text" value="{{ date('d-m-Y') }}">
                                                    

                                                    @endif
                                                    
                                                </div>
                                            </div>  


                                        <section id="1">

                                            <div class="form-group">
                                          <label class="col-lg-2 control-label">Cheque Number:</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="cheque_number" value="{{ old('cheque_number') }}" class="form-control" id="cheque_number" placeholder="148975" />
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Bank Name :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="cheque_bank_name" value="{{ old('cheque_bank_name') }}" class="form-control" id="cheque_bank_name" placeholder="National Bank" />
                                          </div>
                                      </div>


                            </section>



                                <section id="2" style="display: none;">

                                             <div class="form-group">
                                          <label class="col-lg-2 control-label">Payment By :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="rtgs_paymentby" value="{{ old('rtgs_paymentby') }}" class="form-control" id="rtgs_paymentby" placeholder="RTGS,NEFT,PhonePay,GooglePay" />
                                          </div>
                                      </div>  

                                            <div class="form-group">
                                          <label class="col-lg-2 control-label">Transaction ID :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="rtgs_transaction" value="{{ old('rtgs_transaction') }}" class="form-control" id="rtgs_transaction" placeholder="984521" />
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Account Number :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="rtgs_account_number" value="{{ old('rtgs_account_number') }}" class="form-control" id="rtgs_account_number" placeholder="00145258202" />
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Bank Name :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="rtgs_bank_name" value="{{ old('rtgs_bank_name') }}" class="form-control" id="rtgs_bank_name" placeholder="National Bank" />
                                          </div>
                                      </div>
                                     
                            </section>




                            <section id="3" style="display: none;">

                                             <div class="form-group">
                                          <label class="col-lg-2 control-label">From Person Name :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="cash_from_name" value="{{ old('cash_from_name') }}" class="form-control" id="cash_from_name" placeholder="" />
                                          </div>
                                      </div>  

                                            <div class="form-group">
                                          <label class="col-lg-2 control-label">To Person Name :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="cash_to_name" value="{{ old('cash_to_name') }}" class="form-control" id="cash_to_name" placeholder="" />
                                          </div>
                                      </div>

                                      
                                     
                            </section>



                            <section id="all" style="padding-top: 2%;" >


                               <div class="form-group">
                                          <label class="col-lg-2 control-label">Amount :</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="amount" value="{{ old('amount') }}" class="form-control" id="amount" required="required" placeholder="100000" />
                                          </div>
                                      </div>

                              <div class="form-group">
                                          <label class="col-lg-2 control-label">Description :</label>
                                          <div class="col-lg-10">
                                              <textarea type="text" name="description" class="form-control" id="description">{{ old('description') }}</textarea>
                                          </div>
                                      </div>



                            </section>


                            <section id="mylist"></section>

                            <section id="saveall" style="padding-top: 1%">
                                      
                                      <div id="#billlist"></div>
                                         <div class="form-group save_cancle">
                                            <div class="col-lg-12 center">
                                                <button class="btn btn-success"  type="submit"> Save </button>
                                                
                                            </div>
                                        </div>

                            </section>

                                    </form>
                                </div>
                            </div>
                        </section>
                 
                <!-- page end-->
            </section>
        </section>
        <!--main content end-->

  @endsection

  

  @section('js2')


   
  @endsection
  @section('js3')

  <script type="text/javascript" src="{{ asset('assets/fuelux/js/spinner.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-daterangepicker/moment.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/bootstrap-timepicker/js/bootstrap-timepicker.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/jquery-multi-select/js/jquery.multi-select.js')}}"></script>
  <script type="text/javascript" src="{{ asset('assets/jquery-multi-select/js/jquery.quicksearch.js')}}"></script>
  
  @endsection

  @section('js4')
 

   <script src="{{ asset('js/advanced-form-components.js')}}"></script>
<script type="text/javascript">

$("#transporter").change(function(){

var trans = $(this).val();

// $('#mylist').html('');

if(trans == ''){
  $("#forwarder").val("");
  $('#forwarder_div').css('display','block');
  $("#fcompany").val("");
  $('#fcompany_id').css('display','block');

} else {

  $('#forwarder_div').css('display','none');
  $("#forwarder").val("");
  $('#fcompany_id').css('display','none');
  $("#fcompany").val("");

}  

});

$("#forwarder").change(function(){

var trans = $(this).val();

// $('#mylist').html('');

if(trans == ''){
$("#transporter").val("");
$('#transporter_div').css('display','block');
$("#fcompany").val("");
$('#fcompany_id').css('display','block');
} else {

  $('#transporter_div').css('display','none');
  $("#transporter").val("");
  $('#fcompany_id').css('display','none');
  $("#fcompany").val("");
} 

});

$("#fcompany").change(function(){
var trans = $(this).val();
// $('#mylist').html('');
if(trans == ''){
$("#transporter").val("");
$('#transporter_div').css('display','block');
$("#forwarder").val("");
$('#forwarder_div').css('display','block');

} else {

  $('#transporter_div').css('display','none');
  $("#transporter").val("");
  $('#forwarder_div').css('display','none');
  $("#forwarder").val("");
} 
});


    $('#type').change(function(){
     
      var val = $(this).val();

      


      if(val == 'cheque'){

        $('#2').css('display','none');
        $('#3').css('display','none');
        $('#1').css('display','block');

      }

      if(val == 'netbanking' || val == 'UPI' || val == 'IMPS' || val == 'NEFT'){

        $('#1').css('display','none');
        $('#3').css('display','none');
        $('#2 ').css('display','block');

      }

      if(val == 'cash'){

        $('#1').css('display','none');
        $('#2').css('display','none');
        $('#3').css('display','block');

      }

    });


    

</script>
  @endsection