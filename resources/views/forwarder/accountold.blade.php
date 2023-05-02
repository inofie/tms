@extends('layout.master')

@section('title')
Account | TMS
@endsection


@section('css2')
    <link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
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
            
               <section class="panel" style="border: 1px solid #dddddd;">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="post" action="{{ route('acountlrlist') }}" >
                                         @csrf
                                        <div class="form-group " id="years">
                                          
                                            <div class="col-lg-5">
                                                <select class="form-control" required="required" id="years" name="years" > 
                                                   <option value=""> -- Please Select Years -- </option>
                                                      @foreach($years as $value)

                                                      @if($select_year == $value)
                                                      <option selected="selected" value="{{ $value}}">{{ $value}}</option>
                                                      @elseif(old('years') == $value)
                                                      <option selected="selected" value="{{ $value}}">{{ $value}}</option>
                                                      @else
                                                      <option  value="{{ $value }}">{{ $value }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>

                                                @error('years')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-5">
                                                <select class="form-control" id="month" required="required" name="month" > 
                                                   <option value=""> -- Please Select Month -- </option>
                                                      @foreach($months as $key => $value)
                                                      @if($select_month == $key)
                                                      <option selected="selected" value="{{$key}}">{{$value}}</option>
                                                      @elseif(old('month') == $key)
                                                      <option selected="selected" value="{{$key}}">{{$value}}</option>
                                                      @else
                                                      <option  value="{{ $key }}">{{ $value }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>

                                                @error('years')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-2 center">
                                                <button class="btn btn-success" id="sbtn" type="submit">Save</button>
                                                
                                            </div>
                                      </div>
                                    </form>
                                </div>
                            </div>
                        </section>

                        <div id="mylist">
                        </div>
              
              

          </section>

          
      </section>
      <!--main content end-->
@endsection

@section('js1')
<script src="{{ asset('js/jquery.js') }}"></script>
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

<script src="{{ asset('js/count.js')}}"></script>
    
@endsection