  @extends('layout.master')

  @section('title')
  Add Invoice | TMS
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
                               Add Invoice
                              
                            </header>
                            <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" >
                                      @csrf

                               
                              <section id="section1" > 

                                 <div class="form-group">
                                  <label class="control-label col-lg-2">Date<span style="color: red">*</span>:</label>
                                  <div class="col-md-3 col-lg-3">
                                   
                                    @if(old('date'))
                                    <input class="form-control form-control-inline input-medium default-date-picker" size="16" id="ship_date" name="date" type="text" value="{{ old('date') }}">

                                      @else 

                                      <input class="form-control form-control-inline input-medium default-date-picker" size="16" id="ship_date" name="date" type="text" value="{{ date('d-m-Y') }}">
                                      

                                      @endif
                                      @error('date')
                                        <span class="text-danger"> {{ $message }} </span>
                                      @enderror
                                  </div>
                              </div>  
                                      
                                      <div class="form-group ">
                                            <label for="company_id" class="control-label col-lg-2">Company :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="company" name="company_id" required="required"> 
                                                   <option value=""> -- Please Select Company -- </option>
                                                      @foreach($data['company'] as $value)
                                                      @if(old('company_id') == $value->id)
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 

                                                </select>

                                                @error('company_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="form-group ">
                                            <label for="forwarder_id" class="control-label col-lg-2">Forwarder :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="forwarder" name="forwarder_id" required="required"> 
                                                   <option value=""> -- Please Select Forwarder -- </option>
                                                      @foreach($data['Forwarder'] as $value)
                                                      @if(old('forwarder_id') == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>
                                                @error('forwarder_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Invoice No:</label>
                                          <div class="col-lg-10">
                                              <input type="text" name="invoice_no" value="{{ old('invoice_no') }}" class="form-control" id="invoice_no" />
                                          </div>
                                      </div>

                                         <div class="form-group save_cancle btn1">
                                            <div class="col-lg-12 center">
                                                <button class="btn btn-success" id="btn1" type="button">Next > </button>
                                                
                                            </div>
                                        </div>

                            </section>

                             <section id="section2" style="display:none;">



                             </section>

                            <section id="section3" style="display: none;"> 

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





    
    $('#btn1').click(function(){

        var company = $('#company').val();
        var forwarder = $('#forwarder').val();
        var _token   = $('meta[name="csrf-token"]').attr('content');

        if(company == ""){
          alert('Please Select Company');
          $("#company").focus();
          return false;
        }

        if(forwarder == ""){
          alert('Please Select Forwarder');
          $("#forwarder").focus();
          return false;
        }



         $.ajax({
            url: "{{ route('invoiceshipmentlist') }}",
            type:"POST",
            data:{
              company:company,
              forwarder:forwarder,
               _token: _token
            },
            success:function(response){
              console.log(response);
             $('#section1').css('display','none');
             $('#section2').html(response);
             $('#section2').css('display','block');

            },
       });


    });

    $('#btnback2').click(function(){

            $('#section2').css('display','none');
             $('#section2').html('');
             $('#section1').css('display','block');
    });


    $('#company').change(function(){
     
       var selected = $(this).find('option:selected');
       var code = selected.data('code'); 
       var no = selected.data('bill'); 
      
       var bill_no = code+''+no;
       $('#invoice_no').val(bill_no);
       $('#invoice_no').focus();





    });










   

     

</script>
  @endsection