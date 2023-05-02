  @extends('layout.master')

  @section('title')
  Add Transporter | TMS
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
                               Add Transporter
                               <div class="btn-group pull-right">
                                 <a href="{{ route('shipmentlist') }}"> <button style="padding: 7%;" class="btn btn-info"><!-- id="editable-sample_new" -->
                                      Back <i class="fa fa-reply"></i>
                                  </button></a>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="signupForm" method="post" action="{{ route('savetransporter') }}" enctype="multipart/form-data">
                                      @csrf
                                      <input type="hidden" name="shipment_no" value="{{ $ship->shipment_no }}">
                                     

                                        <div class="form-group mytransporter">
                                          <label for="transporter_id" class="control-label col-lg-2">Transporter :</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="transporter_id" id="transporter" >
                                              
                                            <option value="">Choose Transporter</option>
                                              @foreach($data as $value)
                                              @if(old('transporter_id') == $value->id)
                                              <option selected="selected" data-number="{{ $value->truck_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                              @else  
                                              <option data-number="{{ $value->truck_no }}"  value="{{ $value->id }}">{{ $value->name }}</option>
                                              @endif
                                              @endforeach
                                              
                                              
                                          </select>
                                           @error('transporter_id')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">Truck Number</label>
                                          <div class="col-lg-10">
                                              <input type="text" class="form-control"  placeholder="Truck Number" id="truck_no" name="truck_no" value="{{ old('truck_no') }}" />
                                               @error('truck_no')
                                            <span class="text-danger"> {{ $message }} </span>
                                          @enderror
                                          </div>
                                      </div>

                                        <div class="form-group" style="margin-top:1%;">
                                          <div class="col-lg-12" style="text-align: center;">
                                              <button class="btn btn-success" id="msubmit" >Save</button>
                                              <button style="display: none;"  id="sform" class="btn btn-success" type="submit">Save</button>
                                              <button class="btn btn-default" type="reset">Cancel</button>
                                          </div>
                                      </div>

                                    </form>
                                </div>
                            </div>
                        </section>


              <section class="panel">
                  <header class="panel-heading" style="line-height: 30px;">
                      Transporter List
                       
                  </header>

               
                        <div class="adv-table" style="padding: 1%;">
                             <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                <tr>
                                    <th class="center">Sr.No</th>
                                    <th class="center">Name</th>
                                    <th class="center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <?php $i = 0; ?>
                                @foreach($shiptransporter as $value)

                                <tr>
                                    <td class="center"><?php echo $i = $i+1; ?></b></td>
                                    <td class="center">{{ $value->name }}</td>
                                    <td class="center"> <form action="{{ route('deleteshiptransporter') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}">
                                      <button type="save" onclick="return confirm('Are you sure you want to Delete?');" style="margin-top: 2%;width: auto;min-width: 35%;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</i></button>
                                      </form></td>
                                </tr>


                                @endforeach
                                  
                                
                                
                                </tbody>
                            </table>

                        </div>
                
              </section>
                 
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
  
  /*  $('#transporter').change(function(){
       var selected = $(this).find('option:selected');
       var no = selected.data('number'); 
       
       $('#truck_no').val(no);
       $('#truck_no').focus();
      
    });*/


    $(document).ready(function() {
    $('#transporter').change(function(){
      $(".driverdiv").remove();
       var selected = $(this).find('option:selected');
       var no = selected.data('number'); 
       $('#truck_no').val(no);
       var mytransporter = $(this).val();
         $.ajax({
                    url: '{{route('shipmentdriverlist')}}',
                    data: {"_token": "{{ csrf_token() }}","transporter_id":mytransporter},
                    type: 'post',
                    success: function(result)
                    {
                      $('.mytransporter').after(result);
                      $('#driver').focus();
                      $('.mydriver').change(function(){
                          var selected = $(this).find('option:selected'); 
                          var no = selected.data('number'); 
                          $('#truck_no').val(no);
                          $('#truck_no').focus();
                      });
                    }
                }); 
    });

 });

</script>
  @endsection