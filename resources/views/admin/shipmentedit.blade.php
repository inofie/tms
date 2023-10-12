  <?php date_default_timezone_set("Asia/Kolkata"); ?>

  @extends('layout.master')

  @section('title')
  Edit Shipment | TMS
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
                        Please check the form for below errors
                      </div>
                      @endif
                      <section class="panel">
                          <header class="panel-heading " style="line-height: 30px;">
                             Edit Shipment
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
                                  
                                <form class="cmxform form-horizontal tasi-form" id="shipmentform" method="post" action="{{ route('shipmentupdate') }}" enctype="multipart/form-data">
                                  @csrf
                                  <input type="hidden" name="id" value="{{ $data->id }}">

                                  <div class="form-group">
                                  <label class="control-label col-lg-2">Date<span style="color: red">*</span>:</label>
                                  <div class="col-md-3 col-lg-3">
                                   
                                    @if(old('date'))
                                    <input class="form-control"  max="" name="date" type="date" value="{{ old('date') }}">

                                      @else 

                                      <input class="form-control"  max="" name="date" type="date" value="{{ date('Y-m-d',strtotime($data->date)) }}">
                                    
                                      @endif
                                      @error('date')
                                        <span class="text-danger"> {{ $message }} </span>
                                      @enderror
                                  </div>
                              </div>

                                      <div class="form-group " style="display: none;">
                                          <label for="cars" class="control-label col-lg-2">Choose Company<span style="color: red">*</span>:</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="company" id="company" required="required">
                                              <option value="">Choose Company</option>
                                              @foreach($company as $value)
                                              @if(old('company') == $value->id)
                                              <option data-code="{{ $value->code }}" data-no="{{ $value->last_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @elseif($data->company == $value->id)
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
                                              <input class=" form-control" id="shipment_no" name="shipment_no" type="text" value="{{ $data->shipment_no }}" />
                                               @error('shipment_no')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>   
                                  </div> -->

                                        <div class="form-group">
                                      <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Shipment<span style="color: red">*</span>:</label>
                                      <div class="col-lg-10">
                                      <div class="col-lg-6">

                                      @if(old('type1'))

                                        
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

                                        @else

                                        @if($data->exports == 1)

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

                                        @endif





                                      </div>

                                         <div class="col-lg-6">

                                          @if(old('type1'))
                                        
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


                                           @else

                                           @if($data->fcl == 1)

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



                                           @endif
                                      </div>
                                    </div>

                                    </div>


                                    <div class="form-group">
                                          <label class="col-lg-2 control-label">From<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                            @if(old('from1'))
                                              <input type="text" class="form-control" id="From" required="required" name="from1" value="{{ old('from1') }}" placeholder="From"/>
                                              @else
                                              <input type="text" class="form-control" id="From" required="required" name="from1" value="{{ $data->from1 }}" placeholder="From"/> 
                                              @endif
                                               @error('from1')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>
                                      <div class="form-group">
                                            <label class="col-lg-2 control-label">To<span style="color: red">*</span>:</label>
                                            <div class="col-lg-10">
                                              @if(old('to1'))
                                                <input type="text" name="to1" value="{{ old('to1') }}" class="form-control" required="required"  placeholder="To"/>
                                              @else
                                                 <input type="text" name="to1" value="{{ $data->to1 }}" class="form-control" required="required"  placeholder="To"/>
                                              @endif
                                          @error('to1')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                            </div>
                                        </div>
                                          <div class="form-group">
                                            <label class="col-lg-2 control-label">To :</label>
                                            <div class="col-lg-10">
                                               @if(old('to2'))
                                                <input type="text" class="form-control" value="{{ old('to2') }}" name="to2" placeholder="To"/>
                                              @else
                                                 <input type="text" name="to2" value="{{ $data->to2 }}" class="form-control"  placeholder="To"/>
                                              @endif
                                                 @error('to2')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                          <label for="cars" class="control-label col-lg-2">Truck Type:</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="truck_type" >
                                              
                                            <option value="">Choose Truck Type</option>
                                              @foreach($truck_type as $value)
                                              @if(old('truck_type') == $value->id)
                                              <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @elseif($data->trucktype == $value->id)
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

                                      <div class="form-group ">
                                          <label for="cars" class="control-label col-lg-2">Forwarder<span style="color: red">*</span>:</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="forwarder" id="forwarder">
                                              
                                            <option value="">Choose Forwarder</option>
                                              @foreach($forwarder as $value)
                                              @if(old('forwarder') == $value->id)
                                              <option selected="selected" data-name="{{ $value->name }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                               @elseif($data->forwarder == $value->id)
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
                                             @if(old('show_detail'))
                                             @if(old('show_detail') == 1)
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="show_detail"   value="0"> 
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
                                          @else
                                            @if($data->show_detail == 1)

                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="show_detail"   value="0"> 
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


                                           @endif
                                           </div>

                                      </div>
                                     
                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignor :</label>
                                          <div class="col-lg-10">
                                             @if(old('consignor'))
                                              <input type="text" class="form-control" name="consignor" id="consignor" value="{{ old('consignor') }}"  placeholder="Consignor"/>
                                              @else
                                              <input type="text" class="form-control" name="consignor" id="consignor" value="{{ $data->consignor }}"  placeholder="Consignor"/>
                                              @endif
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignor Address :</label>
                                          <div class="col-lg-10">
                                            @if(old('consignor_add'))
                                              <textarea class="form-control" rows="5" name="consignor_add" id="consignor_add"  placeholder="Consignor Address"/>{{ old('consignor_add') }}</textarea> 
                                              @else 
                                               <textarea class="form-control" rows="5" name="consignor_add" id="consignor_add"  placeholder="Consignor Address"/>{{ $data->consignor_address }}</textarea>
                                              @endif
                                          </div>
                                      </div>

                                         <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignee :</label>
                                          <div class="col-lg-10">
                                             @if(old('consignor'))
                                              <input type="text" class="form-control" name="consignee" id="consignee" value="{{ old('consignee') }}" placeholder="Consignor"/>
                                              @else
                                                <input type="text" class="form-control" name="consignee" id="consignee" value="{{ $data->consignee }}" placeholder="Consignor"/>
                                               @endif
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Consignee Address :</label>
                                          <div class="col-lg-10">
                                              @if(old('consignee_add'))
                                              <textarea class="form-control" rows="5" name="consignee_add" id="consignee_add"  placeholder="Consignee Address"/>{{ old('consignee_add') }}</textarea>
                                              @else
                                              <textarea class="form-control" rows="5" name="consignee_add" id="consignee_add"  placeholder="Consignee Address"/>{{ $data->consignee_address }}</textarea>
                                              @endif
                                          </div>
                                      </div>

                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">No. of Package<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                            @if(old('package'))
                                              <input type="text" class="form-control" name="package" required="required" value="{{ old('package') }}"  placeholder="10"/>
                                               @else
                                                <input type="text" class="form-control" name="package" required="required" value="{{ $data->package }}"  placeholder="10"/>
                                               @endif
                                              @error('package')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Cargo Description:</label>
                                          <div class="col-lg-10">
                                            @if(old('cargo_description'))
                                              <textarea class="form-control wysihtml5" rows="5" name="cargo_description" id="cargo_description"  placeholder="Enter Cargo Description">{{ old('cargo_description') }}</textarea>
                                              @else
                                              <textarea class="form-control wysihtml5" rows="5" name="cargo_description" id="cargo_description"  placeholder="Enter Cargo Description">{{ $data->description }}</textarea>

                                              @endif

                                          </div>
                                      </div>

                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">Total Gross Weight<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                            @if(old('weight'))
                                              <input type="text" class="form-control"  required="required" name="weight"  placeholder="1000" value="{{ old('weight') }}" />
                                            @else
                                            <input type="text" class="form-control"  required="required" name="weight"  placeholder="1000" value="{{ $data->weight }}" />
                                            @endif
                                          @error('weight')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Shipper Invoice No:</label>
                                          <div class="col-lg-10">
                                             @if(old('shipper_no'))
                                              <input type="text" class="form-control" name="shipper_no" value="{{ old('shipper_no') }}" placeholder="Enter Shipper Invoice No"/>
                                              @else
                                              <input type="text" class="form-control" name="shipper_no" value="{{ $data->shipper_invoice }}" placeholder="Enter Shipper Invoice No"/>
                                              @endif
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Forward Reference No:</label>
                                          <div class="col-lg-10">
                                            @if(old('for_ref_no'))
                                              <input type="text" class="form-control" value="{{ old('for_ref_no') }}" name="for_ref_no" placeholder="Enter Forward Reference No"/>
                                              @else
                                              <input type="text" class="form-control" value="{{ $data->forwarder_ref_no }}" name="for_ref_no" placeholder="Enter Forward Reference No"/>
                                              @endif
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">B/E No:</label>
                                          <div class="col-lg-10">
                                            @if(old('for_ref_no'))
                                              <input type="text" class="form-control" name="be_no" value="{{ old('be_no') }}"  placeholder="Enter B/E No"/>
                                               @else
                                               <input type="text" class="form-control" name="be_no" value="{{$data->b_e_no}}"  placeholder="Enter B/E No"/>
                                              @endif
                                          </div>
                                      </div>

                                      @if($data->lcl == 1)
                                      <div id="myfcl"></div>
                                      @else
                                      <div id="myfcl">
                                          
                                 
                                         <div class="form-group">
                                          <label class="col-lg-2 control-label">Container Type:</label>
                                          <div class="col-lg-10">
                                            @if(old('for_ref_no'))
                                              <input type="text" class="form-control" name="container_type"  placeholder="Enter Container Type" value="{{ old('container_type') }}" />
                                              @else
                                               <input type="text" class="form-control" name="container_type"  placeholder="Enter Container Type" value="{{ $data->container_type }}" />
                                              @endif
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">De-Stuffing Date:</label>
                                          <div class="col-md-3 col-lg-3">
                                             

                                    @if(old('destuffing'))
                                     {{--  <input type="date" id="dds" name="destuffing"  class="form-control" value="{{ old('destuffing') }}"> --}}
                                     
                                     <input class="form-control"  name="destuffing" type="date" value="{{ old('destuffing') }}">
                                      @else                                      
                                     {{--  <input type="date" id="dds"  name="destuffing" class="form-control" value="{{ date('d-M-Y',strtotime($data->destuffing_date)) }}"> --}}
                                    <input class="form-control" name="destuffing" type="date" value="{{ $data->destuffing_date }}">
                                      @endif
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Container No<span style="color:red;">*</span>:</label>
                                          <div class="col-lg-10">
                                            @if(old('container_no'))
                                              <input type="text" required="required" class="form-control"  placeholder="Enter Container No" value="{{ old('container_no') }}" name="container_no" />
                                              @else                                      
                                              <input type="text" required="required" class="form-control"  placeholder="Enter Container No" value="{{ $data->container_no }}" name="container_no" />
                                            @endif
                                          </div>
                                        </div>
                                     

                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Shipping Line:</label>
                                          <div class="col-lg-10">
                                             @if(old('shipping_line'))
                                              <input type="text" name="shipping_line" value="{{ old('shipping_line') }}" class="form-control"  placeholder="Enter Shipping Line"/>
                                              @else                                      
                                              <input type="text" name="shipping_line" value="{{ $data->shipping_line }}" class="form-control"  placeholder="Enter Shipping Line"/>
                                            @endif
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">CHA:</label>
                                          <div class="col-lg-10">
                                            @if(old('cha'))
                                              <input type="text" class="form-control" name="cha" value="{{ old('cha') }}"  placeholder="Enter CHA"/>
                                               @else                                      
                                              <input type="text" class="form-control" name="cha" value="{{ $data->cha }}"  placeholder="Enter CHA"/>
                                            @endif
                                          </div>
                                      </div>

                                       <div class="form-group">
                                          <label class="col-lg-2 control-label">Seal No<span style="color: red">*</span>:</label>
                                          <div class="col-lg-10">
                                            @if(old('seal_no'))
                                              <input type="text" class="form-control" name="seal_no" required="required"  placeholder="Enter Seal No" value="{{ old('seal_no') }}" />
                                              @else                                      
                                               <input type="text" class="form-control" name="seal_no" required="required"  placeholder="Enter Seal No" value="{{ $data->seal_no }}" />
                                            @endif
                                          </div>
                                      </div>

                                      <div class="form-group" style="border-bottom: 1px solid #eff2f7;padding-bottom: 15px;margin-bottom: 15px;">
                                          <label class="col-lg-2 control-label">POD:</label>
                                          <div class="col-lg-10">
                                            @if(old('pod'))
                                              <input type="text" class="form-control" name="pod"  placeholder="Enter POD" value="{{ old('pod') }}" />
                                              @else                                      
                                               <input type="text" class="form-control" name="pod"  placeholder="Enter POD" value="{{ $data->pod }}" />
                                              @endif
                                          </div>
                                      </div>
                                    
                                      </div>

                                      @endif

                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Driver Name:</label>
                                          <div class="col-lg-10">
                                             @if(old('driver_name'))
                                              <input type="text" name="driver_name" value="{{ old('driver_name') }}" class="form-control" placeholder=""/>
                                               @else                                      
                                                <input type="text" name="driver_name" value="{{$data->driver_name }}" class="form-control" placeholder=""/>
                                              @endif
                                          </div>
                                      </div>


                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Licence Number:</label>
                                          <div class="col-lg-10">
                                             @if(old('licence_no'))
                                              <input type="text" name="licence_no" value="{{ old('licence_no') }}" class="form-control" placeholder=""/>
                                               @else                                      
                                                <input type="text" name="licence_no" value="{{$data->licence_no }}" class="form-control" placeholder=""/>
                                              @endif
                                          </div>
                                      </div>
                                  
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Invoice Amount:</label>
                                          <div class="col-lg-10">
                                             @if(old('invoice_amount'))
                                              <input type="text" name="invoice_amount" value="{{ old('invoice_amount') }}" class="form-control" placeholder="12000"/>
                                               @else                                      
                                                <input type="text" name="invoice_amount" value="{{$data->invoice_amount }}" class="form-control" placeholder="12000"/>
                                              @endif
                                          </div>
                                      </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Remark :</label>
                                          <div class="col-lg-10">
                                              @if(old('remark'))
                                              <textarea rows="3" class="form-control" name="remark" placeholder="Enter Remark">{{old('remark') }}</textarea>
                                              @else
                                              <textarea rows="3" class="form-control" name="remark" placeholder="Enter Remark">{{$data->remark }}</textarea>
                                              @endif
                                                
                                              </textarea>
                                          </div>
                                      </div>

                                  
                                      <div class="form-group" style="margin-top:1%;">
                                          <div class="col-lg-12" style="text-align: center;">
                                              <button class="btn btn-success" id="msubmit" >Save</button>
                                              <button style="display: none;"  id="sform" class="btn btn-success" type="submit">Save</button>
                                               <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
                                          </div>
                                      </div>

                                          </form>
                                  
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
                                              <input type="text" required="required" class="form-control"  placeholder="Enter Container No" value="{{ old('container_no') }}" name="container_no" />
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
                                              <input type="text" class="form-control" name="seal_no" required="required"  placeholder="Enter Seal No" value="{{ old('seal_no') }}" />
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

      if(truck != "" && transporter ==""){
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


    $("#mydate").each(function() {    
        $(this).datepicker('setDate', $(this).val());
        });
    });
    
  $(function(){
    $('#transporter').change(function(){
       var selected = $(this).find('option:selected');
       var no = selected.data('number'); 
       
       $('#truck_no').val(no);
       $('#truck_no').focus();
      
    });


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