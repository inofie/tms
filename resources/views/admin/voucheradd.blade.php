  @extends('layout.master')

  @section('title')
  Add Expense | TMS
  @endsection

  @section('css2')
      <link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
      <link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
      <link href="{{ asset('assets/data-tables/DT_bootstrap.css')}}" rel="stylesheet"  />
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.css')}}" />
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
                               <div class="btn-group pull-right">
                                 <a href="{{ route('shipmentlist') }}"> <button style="padding: 7%;" class="btn btn-info"><!-- id="editable-sample_new" -->
                                      Back <i class="fa fa-reply"></i>
                                  </button></a>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="signupForm" method="post" action="{{ route('expensesave') }}" enctype="multipart/form-data">
                                      @csrf
                                      <input type="hidden" name="shipment_no" value="{{ $ship->shipment_no }}">
                                      <div class="form-group ">
                                            <label for="transporter_id" class="control-label col-lg-2">Transporter :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="transporter_id" required="required"> 
                                                   <option value=""> -- Please Select Transporter -- </option>
                                                      @foreach($data as $value)
                                                      @if(old('transporter_id') == $value->transporter_id)
                                                      <option selected="selected" value="{{ $value->transporter_id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->transporter_id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>

                                                @error('transporter_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>


                                      

                                          <div class="form-group">
                                            <label class="col-lg-2 control-label">Reason :</label>
                                            <div class="col-lg-10">
                                                <textarea class="form-control" id="reason" name="reason"  placeholder="Enter Reason">{{ old('reason') }}</textarea>
                                                @error('reason')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                          <div class="form-group ">
                                            <label for="amount" class="control-label col-lg-2">Amount :</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="amount" name="amount" type="text" required="required" value="{{ old('amount') }}" placeholder=" Enter Amount" />

                                                @error('amount')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                      

                                        <div class="form-group save_cancle">
                                            <div class="col-lg-offset-10 col-lg-2">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                <button class="btn btn-default" type="button">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                 
                <!-- page end-->
            </section>
        </section>
        <!--main content end-->

  @endsection

  @section('js1')

  <script type="text/javascript" language="javascript" src="{{ asset('js/jquery.js')}}"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.js')}}"></script>
  @endsection

  @section('js2')

  <script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.dataTables.js')}}"></script>
      <script type="text/javascript" src="{{ asset('assets/data-tables/DT_bootstrap.js')}}"></script>
  @endsection
  @section('js3')

  <script type="text/javascript" src="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.js')}}"></script>
   <script type="text/javascript" src="{{ asset('assets/jquery-multi-select/js/jquery.multi-select.js')}}"></script>

  @endsection

  @section('js4')

<script src="{{ asset('js/advanced-form-components.js')}}"></script>

<script type="text/javascript">
    
    function Addmore(){

      var count = $('.myname').length + 1;

      

      var addrow = '<tr id="row'+count+'"><td><div class="control-label col-lg-10" ><input type="text" class="form-control myname" placeholder="Enter Name" name="name[]"  required="required"></div></td><td><div class="control-label col-lg-10" ><input type="number" class="form-control" name="sorting_id[]"></div></td><td class="text-center"><button onclick="deleterow('+count+')" style="background: #fff;border-radius: 0px;border: 0px;color: red;font-size: 25px;"><i class="fa fa-times"></i></button></tr>';

      $('#last').before(addrow);

    }

     function deleterow(id){

      $("#row"+id).remove();
     }

</script>
  @endsection