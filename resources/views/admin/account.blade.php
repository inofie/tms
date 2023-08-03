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
            
               <section class="panel">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="#" >
                                      @if($data1->role != "company")
                                        <div class="form-group " id="fcompany_id">
                                            <label for="fcompany_id" class="control-label col-lg-2"> Company :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="fcompany" name="fcompany_id" > 
                                                   <option value=""> -- Please Select Company -- </option>
                                                      @foreach($company as $value)
                                                      @if(old('fcompany_id') == $value->id)
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 

                                                </select>

                                                @error('fcompany_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
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

                                                @error('transporter_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
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

                                                @error('forwarder_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @else
                                        <div class="form-group " id="fcompany_id">
                                            <label for="fcompany_id" class="control-label col-lg-2"> Company :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="fcompany" name="fcompany_id" > 
                                                
                                                      @foreach($company as $value)
                                                      @if(old('fcompany_id') == $value->id)
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option data-code="{{ $value->code }}" data-bill="{{ $value->bill_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 

                                                </select>

                                                @error('fcompany_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                         
                                        @endif

                                        <div class="form-group">
                                            <label class="control-label col-md-2">Date Range</label>
                                            <div class="col-md-4">

                                                <div class="input-group input-large" data-date="13/07/2013" data-date-format="dd/mm/yyyy">
                                                   <span class="input-group-addon">From</span>
                                                    <input type="text" class="form-control dpd1" id="ffrom" name="from">
                                                    <span class="input-group-addon">To</span>
                                                    <input type="text" class="form-control dpd2" id="tto" name="to">
                                                </div>
                                                
                                            </div>
                                        </div>




                                         <div class="form-group save_cancle">
                                            <div class="col-lg-12 center">
                                                <button class="btn btn-success" id="sbtn" type="button">Save</button>
                                                
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
<!--script for this page-->
  {{--  <script type="text/javascript">
     countUp5({{ $data['pl_report'] }});
     countUp2({{ $data['pending'] }});
     countUp3({{ $data['ontheway'] }});
     countUp4({{ $data['bill_status'] }});
   </script> --}}

<script type="text/javascript">
  $('#companyform').change(function(){

    $('#comform').submit();

  });

   

    $("#transporter").change(function(){

        var trans = $(this).val();

        $('#mylist').html('');
        
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

        $('#mylist').html('');
        
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

        $('#mylist').html('');
        
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

 $('.dpd1').datepicker({
            format: 'dd-mm-yyyy'
        });
  $('.dpd2').datepicker({
            format: 'dd-mm-yyyy'
  });




  $('#sbtn').click(function(){

    var com =  $("#fcompany").val();
    var ff =  $("#forwarder").val();
    var tt =  $("#transporter").val();
    var _token   = $('meta[name="csrf-token"]').attr('content');
    var from = $('#ffrom').val();
    var to = $('#tto').val();



    if(com != ''){

        var _token   = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
            url: "{{ route('accountdata') }}",
            type:"POST",
            data:{
              id:com,
              from:from,
              to:to,
              type:'company',
               _token: _token
            },
            success:function(response){
              ///console.log(response);
             $('#mylist').html(response);

              $('#editable-sample').DataTable( {
                   "aaSorting": [[ 0, "asc" ]],
                    "lengthChange": true,
                     "lengthMenu": [ 10, 25, 50, 75, 100 ],
                    /*dom: 'Bfrtip',
                    buttons: [      
                        'excelHtml5',
                        'csvHtml5',
                        'pdf',     
                    ]*/
                } );
               

            },
       });


    } else if(ff != ''){

      

        var _token   = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
            url: "{{ route('accountdata') }}",
            type:"POST",
            data:{
              id:ff,
              from:from,
              to:to,
              type:'forwarder',
               _token: _token
            },
            success:function(response){
              //console.log(response);
             $('#mylist').html(response);
               $('#editable-sample').DataTable( {
                   "aaSorting": [[ 0, "asc" ]],
                    "lengthChange": true,
                     "lengthMenu": [ 10, 25, 50, 75, 100 ],
                    /*dom: 'Bfrtip',
                    buttons: [      
                        'excelHtml5',
                        'csvHtml5',
                        'pdf',      
                    ]*/
                } );
              
            },
       });


    } else if(tt != ''){

        

        var _token   = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
            url: "{{ route('accountdata') }}",
            type:"POST",
            data:{
              id:tt,
              from:from,
              to:to,
              type:'transporter',
               _token: _token
            },
            success:function(response){

              //console.log(response);
             
             $('#mylist').html(response);

             $('#editable-sample').DataTable( {
                   "aaSorting": [[ 0, "asc" ]],
                    "lengthChange": true,
                     "lengthMenu": [ 10, 25, 50, 75, 100 ],
                    /*dom: 'Bfrtip',
                    buttons: [      
                        'excelHtml5',
                        'csvHtml5',
                        'pdf',      
                    ]*/
                } );
               
            },
       });

    } else {

      alert ('Please Select Any One');
      return false;

    }


  });




</script>

  <script src="{{ asset('js/count.js')}}"></script>
    <script type="text/javascript">
        function pdfbtn() {

              $("#pdfform").submit();
        }

    </script>
@endsection