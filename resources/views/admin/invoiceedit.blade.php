  @extends('layout.master')

  @section('title')
  Edit Invoice | TMS
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
                            <header class="panel-heading " style="line-height: 40px">
                               Edit Invoice
                                 <div class="btn-group pull-right">
                                 <a href="{{ route('unpaidshipmentlist') }}"> <button  class="btn btn-success"><!-- id="editable-sample_new" -->
                                    <i class="fa fa-bars"></i>  List 
                                  </button></a>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" >
                                      @csrf

                                      <?php $gst = array(); 

              $gst[] = array('id'=>'1','name'=>'5% GST');
              
              $gst[] = array('id'=>'2','name'=>'18% GST');
              
              $gst[] = array('id'=>'3','name'=>'5% IGST');
              
              $gst[] = array('id'=>'4','name'=>'18% IGST'); 
              
              $gst[] = array('id'=>'5','name'=>'5% UTGST'); 
              
              $gst[] = array('id'=>'6','name'=>'18% UTGST'); 
              
              
              ?>

                 <div class="form-group" style="width: 80%; padding-left: 20%">
                                            <label for="gst" class="control-label col-lg-2">GST :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="gst" name="gst" required="required"> 
                                                 
                                                      @foreach($gst as $value)
                                                      @if($data->mygst == $value)
                                                      <option selected="selected" value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                                      @else 
                                                      <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>
                                               
                                            </div>
                                        </div>
                                        @foreach($trucks as $key2 => $value2)
                                        <div class="form-group " style="width: 80%; padding-left: 20%">
                                            <label for="name" class="control-label col-lg-2">Invoice No :</label>
                                            <div class="col-lg-10">
                                          
                                            <input type="text" class="form-control" id="invoice_no" value="{{ $value2['invoice_no'] }}">
                                                @error('invoice_no')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @endforeach

                                        <div class="form-group " style="width: 80%; padding-left: 20%">
                                            <label for="forwarder_id" class="control-label col-lg-2">Forwarder :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="forwarder_id" name="forwarder_id"> 
                                                   <option value=""> -- Please Select Forwarder -- </option>
                                                      @foreach($forwarder as $value)
                                                      @if($data->forwarder_id == $value->id)
                                                      <option data-number="{{ $value->gst_no }}" selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option data-number="{{ $value->gst_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach 
                                                </select>
                                                @error('forwarder_id')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group " style="width: 80%; padding-left: 20%">
                                            <label for="name" class="control-label col-lg-2">GST No :</label>
                                            <div class="col-lg-10">
                                          
                                            <input type="text" class="form-control" id="gst_no" value="{{ $data['gst_no'] }}">
                                                @error('gst_no')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                       <div class="adv-table" style="padding: 1%;">
                                                        <table class="table table-striped table-hover table-bordered" >
                                            <tr>
                                            
                                              <th>
                                                Truck No.
                                              </th>
                                              <!-- <th>
                                                Invoice No.
                                              </th> -->
                                              <th>
                                                Freight
                                              </th>
                                              <th>
                                                Detention
                                              </th>
                                              <th>
                                                Loading<br>Unloading
                                              </th>
                                              <th>
                                                Others
                                              </th>
                                              <th>Ex. Total</th>
                                            </tr>
                                              <?php $aa = 0; ?>
                                              @foreach($trucks as $key2 => $value2)

                                               <tr>
                                                <td><input type="hidden" class="myid{{ $key2 }} myid" id="myid{{ $key2 }}" value="{{ $value2['id'] }}"> <input type="text" class="trow{{ $key2 }} truck truck{{ $key2 }}" value="{{ $value2['truck_no'] }}"></td>
                                                <!-- <td> 
                                                
                                              </td> -->
                                                <td> <input type="text" class="trow{{ $key2 }} fright fright{{ $key2 }} edit" id="fright{{ $key2 }}" value="{{ $value2['fright'] }}"></td>
                                                <td> <input type="text" class="trow{{ $key2 }} detention detention{{ $key2 }} edit" id="detention{{ $key2 }}" value="{{ $value2['detention'] }}"></td>
                                                <td> <input type="text" class="trow{{ $key2 }} loading loading{{ $key2 }} edit" id="loading{{ $key2 }}" value="{{ $value2['loading'] }}"></td>
                                                <td> <input type="text" class="trow{{ $key2 }} other other{{ $key2 }} edit" id="other{{ $key2 }}" value="{{ $value2['other'] }}"></td>
                                                <td> <input type="text" class="trow{{ $key2 }} total total{{ $key2 }}" id="total{{ $key2 }}" readonly="readonly" value="{{ $value2['totals'] }}"></td>
                                              </tr> 

                                              <?php $aa = $aa+$value2['fright']; ?>
                                              @endforeach 
                                              <tr>
                                                <td>Total</td>
                                                <td id="ftotal" style="font-weight: 700;">{{ $aa }}</td>
                                                
                                                <td colspan="3"></td>
                                                <td id="extotal" style="font-weight: 700;">{{ $data->extra_amount }}</td> 
                                              </tr>


                                          </table>
                                        </div>
                                    <div class="adv-table1" style="padding: 1%;">
                                    <table class="table table-striped table-hover table-bordered" >
                                    <tr>
                                    
                                      </tr>
                                      <tr>
                                      <td>Remarks:</td><td> <input style="width: 1150px" type="text" class="remarks edit" id="remarks" value="{{ $value2['remarks'] }}"></td>
                                      <tr>
                                  </table>
                                  </div>
                                        <div class="adv-table" style="padding: 1%;">
                                                      <table class="table table-striped table-hover table-bordered" >
                                          <tr>
                                            <th class="center">
                                              CGST
                                            </th>
                                            <th class="center">
                                              SGST
                                            </th>
                                            <th class="center">
                                              IGST
                                            </th>
                                            <th class="center">
                                              UTGST
                                            </th>
                                            <th class="center">
                                              TOTAL GST
                                            </th>
                                            <th class="center">
                                              <b> Grand Total </b>
                                            </th>
                                          </tr>

                                          <tr>

                                            @if($data->mygst == 1 || $data->mygst == 2)
                                            <td id="cgst" class="center">{{ $data->cgst }}</td><td id="sgst" class="center">{{ $data->sgst }}</td>
                                            @else 
                                            <td id="cgst" class="center">0</td><td id="sgst" class="center">0</td>
                                            @endif
                                            @if($data->mygst == 3 || $data->mygst == 4)
                                            <td id="igst" class="center">{{ $data->igst }}</td>
                                            @else
                                            <td id="igst" class="center">0</td>
                                            @endif

                                            @if($data->mygst == 5 || $data->mygst == 6)
                                            <td id="utgst" class="center">{{ $data->utgst }}</td>
                                            @else
                                            <td id="utgst" class="center">0</td>
                                            @endif
                                            <td id="totalgst" class="center">@if($data->mygst == 1 || $data->mygst == 2){{ $data->totls_gst }}@endif @if($data->mygst == 3 || $data->mygst == 4){{ $data->totls_gst }}@endif @if($data->mygst == 5 || $data->mygst == 6){{ $data->totls_gst }}@endif</td>
                                            <td id="grandtotal" class="center" style="font-weight: 700;">{{ $data->grand_total}}</td>
                                          </tr>
                                          
                                        </table>
                                       
                                        </div>
                              
                                        <div class="form-group save_cancle btn3">
                                          <div class="col-lg-12 center">
                                          
                                          <button class="btn btn-success" id="btn3" type="submit">Save </button>
                                          <a class="btn btn-default" id="btn3" href="{{ route('unpaidshipmentlist') }}">Cancel </a>
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
    $('#forwarder_id').change(function(){
      finddriver();
    });

    function finddriver() {
      
       var selected = $('#forwarder_id').find('option:selected');
       if(selected) {
        var no = selected.data('number'); 
        $('#gst_no').val(no);
        var mytransporter = $('#forwarder_id').val();
        
         
       }
    }

               $('.edit').focusout(function(){

            var tt= $('.fright').length - 1;  
    
            var tfright = 0;

            var bt = 0;

                    $('.fright').each(function(index) {

                      tfright = parseInt($(this).val()) + parseInt(tfright) ;
                      bf = parseInt($(this).val());
                      bd = parseInt($(".detention"+index).val());
                      bl = parseInt($(".loading"+index).val());
                      bo = parseInt($(".other"+index).val());
                      
                      $(".total"+index).val(bd+bl+bo);
                      bt = parseInt(bt) + parseInt($(".total"+index).val()) ;




                      if(index === tt){
                  $('#extotal').html(bt );
                  $('#ftotal').html(tfright);
                  $('#grandtotal').html(tfright+bt);
                }
                   

                    });




                    
                    var mygst = $('#gst').val();

                    if(mygst == 1){
                      var cgst = parseInt((tfright*2.5)/100);
                      //alert(cgst);
                      $('#cgst').html(cgst);
                      $('#sgst').html(cgst);
                      $('#igst').html(0);
                      $('#utgst').html(0);
                      $('#totalgst').html(cgst*2);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 2){
                      var cgst = parseInt((tfright*9)/100);
                      //alert(cgst);
                      $('#cgst').html(cgst);
                      $('#sgst').html(cgst);
                      $('#igst').html(0);
                      $('#utgst').html(0);
                      $('#totalgst').html(cgst*2);
                      $('#ftotal').html(tfright);
                    }

                    

                    if(mygst == 3){
                      var igst = parseInt((tfright*5)/100);
                      //alert(cgst);
                       $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(igst);
                      $('#utgst').html(0);
                      $('#totalgst').html(igst);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 4){
                      var igst = parseInt((tfright*18)/100);
                      //alert(cgst);
                       $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(igst);
                      $('#utgst').html(0);
                      $('#totalgst').html(igst);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 5){
                      var utgst = parseInt((tfright*5)/100);
                      //alert(cgst);
                      $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(0);
                      $('#utgst').html(utgst);
                      $('#totalgst').html(utgst);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 6){
                      var utgst = parseInt((tfright*18)/100);
                      //alert(cgst);
                      $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(0);
                      $('#utgst').html(utgst);
                      $('#totalgst').html(utgst);
                      $('#ftotal').html(tfright);               
                    }


           });


              $('#gst').change(function(){

                  var tt= $('.fright').length - 1;  
    
            var tfright = 0;

            var bt = 0;

                    $('.fright').each(function(index) {

                      tfright = parseInt($(this).val()) + parseInt(tfright) ;
                      bf = parseInt($(this).val());
                      bd = parseInt($(".detention"+index).val());
                      bl = parseInt($(".loading"+index).val());
                      bo = parseInt($(".other"+index).val());
                      
                      $(".total"+index).val(bd+bl+bo);
                      bt = parseInt(bt) + parseInt($(".total"+index).val()) ;




                      if(index === tt){
                  $('#extotal').html(bt);
                  $('#ftotal').html(tfright);
                  $('#grandtotal').html(tfright+bt);
                }
                   

                    });




                    
                     var mygst = $('#gst').val();

                    if(mygst == 1){
                      var cgst = parseInt((tfright*2.5)/100);
                      //alert(cgst);
                      $('#cgst').html(cgst);
                      $('#sgst').html(cgst);
                      $('#igst').html(0);
                      $('#utgst').html(0);
                      $('#totalgst').html(cgst*2);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 2){
                      var cgst = parseInt((tfright*9)/100);
                      //alert(cgst);
                      $('#cgst').html(cgst);
                      $('#sgst').html(cgst);
                      $('#igst').html(0);
                      $('#utgst').html(0);
                      $('#totalgst').html(cgst*2);
                      $('#ftotal').html(tfright);
                    }

                    

                    if(mygst == 3){
                      var igst = parseInt((tfright*5)/100);
                      //alert(cgst);
                       $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(igst);
                      $('#utgst').html(0);
                      $('#totalgst').html(igst);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 4){
                      var igst = parseInt((tfright*18)/100);
                      //alert(cgst);
                       $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(igst);
                      $('#utgst').html(0);
                      $('#totalgst').html(igst);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 5){
                      var utgst = parseInt((tfright*5)/100);
                      //alert(cgst);
                      $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(0);
                      $('#utgst').html(utgst);
                      $('#totalgst').html(utgst);
                      $('#ftotal').html(tfright);               
                    }

                    if(mygst == 6){
                      var utgst = parseInt((tfright*18)/100);
                      //alert(cgst);
                      $('#cgst').html(0);
                      $('#sgst').html(0);
                      $('#igst').html(0);
                      $('#utgst').html(utgst);
                      $('#totalgst').html(utgst);
                      $('#ftotal').html(tfright);               
                    }
              });

   $("#btn3").click(function(){

              var myid = '';

               var total_trucks = $('.myid').length;

                $('.myid').each(function(index) {

                  if (index === total_trucks - 1) {

                    myid +=  $(this).val() ;

                  }else {

                  myid +=  $(this).val() + ',';

                  }

                });
	   
	   			var truck_number = '';
				var trucks_number = $('.truck').length;
				$('.truck').each(function(index) {
					if (index === trucks_number - 1) {
						truck_number +=  $(this).val() ;
					}else {
						truck_number +=  $(this).val() + ',';
					}
				});
				console.log(truck_number);

                var freight = '';

               var total_trucks = $('.fright').length;

                $('.fright').each(function(index) {

                  if (index === total_trucks - 1) {

                    freight +=  $(this).val() ;

                  }else {

            freight +=  $(this).val() + ',';

                  }

                });


                var detention = '';

               var total_trucks = $('.detention').length;

                $('.detention').each(function(index) {

                  if (index === total_trucks - 1) {

                    detention +=  $(this).val() ;

                  }else {

            detention +=  $(this).val() + ',';

                  }

                });



                var loading = '';

               var total_trucks = $('.loading').length;

                $('.loading').each(function(index) {

                  if (index === total_trucks - 1) {

                    loading +=  $(this).val() ;

                  }else {

            loading +=  $(this).val() + ',';

                  }

                });




                 var other = '';

               var total_trucks = $('.other').length;

                $('.other').each(function(index) {

                  if (index === total_trucks - 1) {

                    other +=  $(this).val() ;

                  }else {

            other +=  $(this).val() + ',';

                  }

                });


                



                  var total = '';

               var total_trucks = $('.total').length;

                $('.total').each(function(index) {

                  if (index === total_trucks - 1) {

                    total +=  $(this).val() ;

                  }else {

            total +=  $(this).val() + ',';

                  }

                });
               

         var gst = $("#gst option:selected" ).text();
         console.log("gst = "+gst);

         var cgst = $('#cgst').html();
         console.log("cgst = "+cgst);

         var sgst = $('#sgst').html();
         console.log("sgst = "+sgst);

         var igst =$('#igst').html();
         console.log("igst = "+igst);

         var invoice_no =$('#invoice_no').val();
         console.log("invoice_no = "+invoice_no);

         var forwarder_id =$('#forwarder_id').val();
         console.log("forwarder_id = "+forwarder_id);

         var gst_no =$('#gst_no').val();
         console.log("gst_no = "+gst_no);

         var remarks =$('#remarks').val();
         console.log("remarks = "+remarks);

         var utgst =$('#utgst').html();
         console.log("utgst = "+utgst);

         var totalgst =$('#totalgst').html();
         console.log("totalgst = "+totalgst);

         var ftotal = $('#ftotal').html();
         console.log("ftotal = "+ftotal);

         var extotal =$('#extotal').html();
         console.log("extotal = "+extotal);

         var grandtotal =$('#grandtotal').html();
         console.log("grandtotal = "+grandtotal);

         var invoiceid = {{ $data->id }};

         if(grandtotal == "" || grandtotal == null || grandtotal =="undefined" ){
          alert("Please Enter Correct Value in All Fields");
          return false;
        }
         var _token   = $('meta[name="csrf-token"]').attr('content');

         var gstoption =  $("#gst").val();

console.log(truck_number);
         $.ajax({
                url: "{{ route('invoiceupdate') }}",
                type:"POST",
                data:{
                  gstoption:gstoption,
                  invoiceid:invoiceid,
                  myid:myid,
                  freight:freight,
                  detention:detention,
                  loading:loading,
                  other:other,
                  invoice_no:invoice_no,
                  gst_no:gst_no,
                  forwarder_id:forwarder_id,
                  remarks:remarks,
                  total:total,
                  gst:gst,
                  cgst:cgst,
                  sgst:sgst,
                  igst:igst,
                  utgst:utgst,
                  totalgst:totalgst,
                  ftotal:ftotal,
                  extotal:extotal,
                  grandtotal:grandtotal,
                  _token: _token,
				 truck_number:truck_number
                },
                success:function(response){
                  
                  if(response == 1){

                    window.location.replace("{{ route('unpaidshipmentlist') }}");

                  } else {

                  console.log(response);

                  }

                },
          });



   });




   

     

</script>
  @endsection