@extends('layout.master')

@section('title')
Add Credit Voucher | TMS
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
                            Credit Note
                            
                          </header>
                          <div class="panel-body">
                              <div class="form">
                                  <form class="cmxform form-horizontal tasi-form" method="POST" action="{{ route('invoiceupdatenote') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $invoice->id }}">
                                      <div class="form-group " id="forwarder_div">
                                          <label for="forwarder_id" class="control-label col-lg-2">Forwarder :</label>
                                          <div class="col-lg-10">
                                              <select class="form-control" id="forwarder" name="forwarder_id" > 
                                                 <option value=""> -- Please Select Forwarder -- </option>
                                                    @foreach($forwarder as $value)
                                                    @if($invoice->forwarder_id == $value->id)
                                                    <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @else
                                                    <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endif
                                                    @endforeach 

                                              </select>

                                              @error('forwarder_id')
                                               <span class="text-danger"> {{ $message }} </span>
                                              @enderror
                                          </div>
                                      </div>

                                      

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


</script>
@endsection