  <?php date_default_timezone_set("Asia/Kolkata"); ?>

  @extends('layout.master')

  @section('title')
  Add Shipment | TMS
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
                  <div class="col-lg-12">
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
                          <header class="panel-heading " style="line-height: 30px;">
                             Add Shipment
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
                                  
                                <form class="cmxform form-horizontal tasi-form" id="shipmentform" method="post" action="{{ route('shipmentsave') }}" enctype="multipart/form-data">
                                  @csrf

                                  <div class="form-group">
                                  <label class="control-label col-lg-2">Date<span style="color: red">*</span>:</label>
                                  <div class="col-md-3 col-lg-3">
                                   
                                    @if(old('date'))
                                    <input class="form-control" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="" name="date" type="date" value="{{ old('date') }}">

                                      @else 

                                      <input class="form-control" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="" name="date" type="date" value="{{ date('Y-m-d') }}">
                                      

                                      @endif
                                      @error('date')
                                        <span class="text-danger"> {{ $message }} </span>
                                      @enderror
                                  </div>
                              </div>

                                      <div class="form-group ">
                                          <label for="cars" class="control-label col-lg-2">Choose Company<span style="color: red">*</span>:</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="company" id="company" >
                                              <option value="">Choose Company</option>
                                              @foreach($company as $value)
                                              @if(old('company') == $value->id)
                                              <option data-code="{{ $value->code }}" data-no="{{ $value->last_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @else  
                                              <option data-code="{{ $value->code }}" data-no="{{ $value->last_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @endif
                                              @endforeach
                                              
                                          </select>
                                          @error('company')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                 <!-- <div class="form-group ">
                                          <label for="firstname" class="control-label col-lg-2">Shipment No</label>
  
                                          <div class="col-lg-10">
                                              <input class=" form-control" id="shipment_no" name="shipment_no" type="text" value="{{ old('shipment_no') }}" />
                                               @error('shipment_no')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>   
                                  </div> -->
                             


                                        <div class="form-group">
                                      <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Shipment<span style="color: red">*</span>:</label>
                                      <div class="col-lg-10">
                                      <div class="col-lg-6">
                                        
                                        @if(old('type1') == "export")

                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="type1"  id="import" value="import"> 
                                                  Import
                                              </label>
                                          </div>
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="type1"  id="export"checked="checked" value="export">
                                                  Export
                                              </label>
                                          </div>

                                          @else


                                            <div class="radio">
                                              <label>
                                                  <input type="radio" name="type1"  id="import"checked="checked" value="import"> 
                                                  Import
                                              </label>
                                          </div>
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="type1"  id="export" value="export">
                                                  Export
                                              </label>
                                          </div>

                                          @endif


                                      </div>

                                         <div class="col-lg-6">
                                        
                                        @if(old('type2') == "fcl")

                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="type2" id="lcl" value="lcl"> 
                                                  LCL
                                              </label>
                                          </div>
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="type2" id="fcl" checked="checked" value="fcl">
                                                  FCL
                                              </label>
                                          </div>

                                          @else

                                            <div class="radio">
                                              <label>
                                                  <input type="radio" name="type2" id="lcl" checked="checked" value="lcl"> 
                                                  ICL
                                              </label>
                                          </div>
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="type2" id="fcl" value="fcl">
                                                  FCL
                                              </label>
                                          </div>

                                          @endif


                                      </div>
                                    </div>

                                    </div>


                                    <div class="form-group">
                                          <label class="col-lg-2 control-label">From<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" id="From"  name="from1" value="{{ old('from1') }}" placeholder="From"/>
                                               @error('from1')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>
                                      <div class="form-group">
                                            <label class="col-lg-2 control-label">To<span style="color: red">*</span>:</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="to1" value="{{ old('to1') }}" class="form-control"   placeholder="To"/>
                                                @error('to1')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                            </div>
                                        </div>

                                        <div id="myfcls"></div>

                                        <div class="form-group ">
                                          <label for="cars" class="control-label col-lg-2">Truck Type:</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="truck_type" >
                                              
                                            <option value="">Choose Truck Type</option>
                                              @foreach($truck_type as $value)
                                              @if(old('truck_type') == $value->id)
                                              <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @else  
                                              <option  value="{{ $value->id }}">{{ $value->name }}</option>
                                              @endif
                                              @endforeach
                                              
                                              
                                          </select>
                                          @error('truck_type')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                      <div class="form-group mytransporter">
                                          <label for="cars" class="control-label col-lg-2">Transporter :</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="transporter" id="transporter" >
                                              
                                            <option value="">Choose Transporter</option>
                                              @foreach($transporter as $value)
                                              @if(old('transporter') == $value->id)
                                              <option selected="selected" data-number="{{ $value->truck_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @else  
                                              <option data-number="{{ $value->truck_no }}"  value="{{ $value->id }}">{{ $value->name }}</option>
                                              @endif
                                              @endforeach
                                              
                                              
                                          </select>
                                           @error('transporter')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>
                                        <input type="hidden" id="old_driver" name="old_driver" value="{{ old('driver_id')  }}">

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Truck Number</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control"  placeholder="Truck Number" id="truck_no" name="truck_no" value="{{ old('truck_no') }}" />
                                               @error('truck_no')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                      <div class="form-group ">
                                          <label for="cars" class="control-label col-lg-2">Forwarder<span style="color: red">*</span>:</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="forwarder" id="forwarder">
                                              
                                            <option value="">Choose Forwarder</option>
                                              @foreach($forwarder as $value)
                                              @if(old('forwarder') == $value->id)
                                              <option selected="selected" data-name="{{ $value->name }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @else  
                                              <option data-name="{{ $value->name }}"  value="{{ $value->id }}">{{ $value->name }}</option>
                                              @endif
                                              @endforeach
                                              
                                              
                                          </select>
                                           @error('forwarder')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>



                                       <div class="form-group ">
                                          <label for="cars" class="control-label col-lg-2">Forwarder View Details<span style="color: red">*</span>:</label>
                                           <div class="col-lg-10">
                                             @if(old('show_detail') == 1)

                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="show_detail"   value="1"> 
                                                  NO
                                              </label>
                                          </div>
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="show_detail"  checked="checked" value="1">
                                                  YES
                                              </label>
                                          </div>

                                          @else


                                            <div class="radio">
                                              <label>
                                                  <input type="radio" name="show_detail" checked="checked" value="0"> 
                                                  NO
                                              </label>
                                          </div>
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="show_detail" value="1">
                                                  YES
                                              </label>
                                          </div>

                                          @endif
                                           </div>

                                      </div>
                                     
                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignor :</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="consignor" id="consignor" value="{{ old('consignor') }}"  placeholder="Consignor"/>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignor Address :</label>
                                          <div class="col-lg-10">
                                              <textarea class="form-control" rows="5" name="consignor_add" id="consignor_add"  placeholder="Consignor Address"/>{{ old('consignor_add') }}</textarea>  
                                          </div>
                                      </div>

                                         <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignee :</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="consignee" id="consignee" value="{{ old('consignee') }}" placeholder="Consignor"/>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignee Address :</label>
                                          <div class="col-lg-10">
                                              
                                              <textarea class="form-control" rows="5" name="consignee_add" id="consignee_add"  placeholder="Consignee Address"/>{{ old('consignee_add') }}</textarea>
                                          </div>
                                      </div>

                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">No. of Package<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="package"  value="{{ old('package') }}"  placeholder="10"/>
                                              @error('package')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Cargo Description:</label>
                                          <div class="col-lg-10">
                                              <textarea class="form-control wysihtml5" rows="5" name="cargo_description" id="cargo_description"  placeholder="Enter Cargo Description">{{ old('cargo_description') }}</textarea>



                                          </div>
                                      </div>

                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">Total Gross Weight<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control"  name="weight"  placeholder="1000" value="{{ old('weight') }}" />
                                              @error('package')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Shipper Invoice No:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="shipper_no" value="{{ old('shipper_no') }}" placeholder="Enter Shipper Invoice No"/>
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Forward Reference No:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" value="{{ old('for_ref_no') }}" name="for_ref_no" placeholder="Enter Forward Reference No"/>
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">B/E No:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="be_no" value="{{ old('be_no') }}"  placeholder="Enter B/E No"/>
                                          </div>
                                      </div>

                                    <div id="myfcl"></div>


                                    <div class="form-group">
                                          <label class="col-lg-2 control-label">Driver Name:</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="driver_name" value="{{ old('driver_name') }}" class="form-control" placeholder=""/>
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Licence Number:</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="licence_no" value="{{ old('licence_no') }}" class="form-control" placeholder=""/>
                                          </div>
                                      </div>
                                  
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Invoice Amount:</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="invoice_amount" value="{{ old('invoice_amount') }}" class="form-control" placeholder="12000"/>
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Remark :</label>
                                          <div class="col-lg-10">
                                             
                                              <textarea rows="3" class="form-control" name="remark" placeholder="Enter Remark">{{old('remark') }}</textarea>
                                                
                                              </textarea>
                                          </div>
                                      </div>

                                  
                                      <div class="form-group" style="margin-top:1%;">
                                          <div class="col-lg-12" style="text-align: center;">
                                              <button class="btn btn-success" id="msubmit" >Save</button>
                                              <button style="display: none;"  id="sform" class="btn btn-success" type="submit">Save</button>
                                              <button class="btn btn-default" type="button">Cancel</button>
                                          </div>
                                      </div>

                                          </form>
                                  
                              </div>

                              <div id="hfcls" style="display: none;">
                                          <div class="form-group">
                                            <label class="col-lg-2 control-label">To :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" value="{{ old('to2') }}" name="to2"   placeholder="To"/>
                                                 @error('to2')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                            </div>
                                        </div>
                                  

                              <div id="hfcl" style="display: none;">
                                 
                                         <div class="form-group">
                                          <label class="col-lg-2 control-label">Container Type:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="container_type"  placeholder="Enter Container Type" value="{{ old('container_type') }}" />
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">De-Stuffing Date:</label>
                                          <div class="col-md-3 col-lg-3">
                                             

                                               @if(old('destuffing'))


                                    <input type="date" id="dds" name="destuffing"  class="form-control" value="{{ old('destuffing') }}">

                                      @else 
                                      
                                      <input type="date" id="dds"  name="destuffing" class="form-control" value="">

                                      @endif
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Container No<span style="color:red;">*</span>:</label>
                                          <div class="col-lg-10">
                                              <input type="text"  class="form-control"  placeholder="Enter Container No" value="{{ old('container_no') }}" name="container_no" />
                                          </div>
                                        </div>
                                     

                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Shipping Line:</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="shipping_line" value="{{ old('shipping_line') }}" class="form-control"  placeholder="Enter Shipping Line"/>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">CHA:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="cha" value="{{ old('cha') }}"  placeholder="Enter CHA"/>
                                          </div>
                                      </div>

                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">Seal No<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="seal_no" placeholder="Enter Seal No" value="{{ old('seal_no') }}" />
                                          </div>
                                      </div>

                                      <div class="form-group" style="border-bottom: 1px solid #eff2f7;padding-bottom: 15px;margin-bottom: 15px;">
                                          <label class="col-lg-2 control-label">POD:</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control" name="pod"  placeholder="Enter POD" value="{{ old('pod') }}" />
                                          </div>
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
  $( document ).ready(function() {

  
   $('#dds').datepicker({ dateFormat: 'dd-mm-yy' });

    $('#msubmit').click(function(){
      var truck = $('#truck_no').val();
      var transporter = $('#transporter').val();

      //alert(truck);

      if(truck != "" && transporter == ""){
        alert('Please Select Transporter');
        return false;
      }

      $('#sform').click();
      //sform
    });

    $('#fcl').click(function(){

      var impo = $('#hfcl').html();
      $('#myfcl').html(impo);
      $('#hfcl').html('');

    });

    $('#lcl').click(function(){

     var impo = $('#myfcl').html();
      $('#hfcl').html(impo);
      $('#myfcl').html('');

    });
  $('#fcl').click(function(){

      var impo = $('#hfcls').html();
      $('#myfcls').html(impo);
      $('#hfcls').html('');

    });

    $('#lcl').click(function(){

     var impo = $('#myfcls').html();
      $('#hfcls').html(impo);
      $('#myfcls').html('');

    });

    $("#mydate").each(function() {    
        $(this).datepicker('setDate', $(this).val());
        });
    });

  

    
  $(document).ready(function() {
    $('#transporter').change(function(){
      finddriver();
    });
    finddriver();

    function finddriver() {
      $(".driverdiv").remove();
       var selected = $('#transporter').find('option:selected');
       if(selected) {
        var no = selected.data('number'); 
        $('#truck_no').val(no);
        var mytransporter = $('#transporter').val();
        var old_driver = $('#old_driver').val();
        
         $.ajax({
            url: '{{route('shipmentdriverlist')}}',
            data: {"_token": "{{ csrf_token() }}","transporter_id":mytransporter,"old_driver":old_driver},
            type: 'post',
            success: function(result)
            {
              $('.mytransporter').after(result);
              $('#driver').focus();
              $('.mydriver').change(function(){
                  var selected = $('#transporter').find('option:selected'); 
                  var no = selected.data('number'); 
                  $('#truck_no').val(no);
                  $('#truck_no').focus();
              });
            }
        }); 
       }
    }

    $('#company').change(function(){
       var selected = $(this).find('option:selected');
       
       var code = selected.data('code'); 
       var no = selected.data('no'); 
      
       var shipment_no = code+''+no;
       $('#shipment_no').val(shipment_no);
       $('#shipment_no').focus();
      
    });

    $('#import').click(function(){

       var  consignor =  $('#consignor').val();
       var consignee =  $('#consignee').val();
        var  consignoradd =  $('#consignor_add').val();
       var consigneeadd =  $('#consignee_add').val();

        $('#consignee').val(consignor);
        $('#consignor').val(consignee);
        $('#consignee_add').val(consignoradd);
        $('#consignor_add').val(consigneeadd);

    });

    $('#export').click(function(){

       var  consignor =  $('#consignor').val();
       var consignee =  $('#consignee').val();
       var  consignoradd =  $('#consignor_add').val();
       var consigneeadd =  $('#consignee_add').val();

        $('#consignee').val(consignor);
        $('#consignor').val(consignee);
        $('#consignee_add').val(consignoradd);
        $('#consignor_add').val(consigneeadd);
    });




    $('#forwarder').change(function(){

      var forw = $(this).find('option:selected');
       var name = forw.data('name');

       var aa= $('input[type=radio][name=type1]:checked').val();


       if(aa == "import"){
        $('#consignor').val(name);
        $('#consignee').val('');
       } else {
        $('#consignor').val('');
        $('#consignee').val(name);
       }

        
      
      });

});
 

</script>
  @endsection