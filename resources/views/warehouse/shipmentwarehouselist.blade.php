@extends('layout.master')

@section('title')
Warehouse Shipment List | TMS
@endsection

@section('css2')

  <link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />

    <link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('assets/data-tables/DT_bootstrap.css')}}" />
@endsection


@section('content')
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
                  <header class="panel-heading" style="line-height: 30px;">
                      Warehouse Shipment List
                      <div class="btn-group pull-right">
                    
                                <a style="padding:0px 1px;" href="{{route('myfilterwarehouse')}}">
                                  <button  class="btn btn-primary">
                                      <i class="fa fa-plus"></i> Filter
                                  </button>
                                </a>
                                 
                                
                                </div>
                    </header>

               
                        <div class="adv-table" style="padding: 1%;">
                             <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                <tr>
                                  <th>W.Name</th>
                                    <th>Ship.No.</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Consignor</th>
                                    <th>Consignee</th>
                                    <th>From</th>
                                    <th>To</th>
                                    
                                    <th class="center">Others</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $value)

                                <tr id="{{ $value->shipment_no }}">
                                  <td style="vertical-align: middle;">{{$value->wname}}</td>
                                    <td class="center" style="vertical-align: middle;"><b>{{ $value->shipment_no }}</b></td>
                                    
                                    <td style="vertical-align: middle;">{{ date('d-m-Y',strtotime($value->date)) }}</td>
                                    
                                    <td style="vertical-align: middle;"> 
                                      @if($value->imports == 1) 
                                        <span style="color: #ab7e2d;font-weight: 700;">Import</span> 
                                      @else 
                                        <span style="color:#2d71ab;font-weight: 700;">Export</span> 
                                      @endif / 
                                      @if($value->lcl == 1) 
                                        <span style="color: #ab7e2d;font-weight: 700;">LCL</span>
                                      @else
                                        <span style="color:#2d71ab;font-weight: 700;">FCL</span>
                                      @endif
                                    </td>
                                    
                                    <td style="vertical-align: middle;">{{ $value->consignor }}</td>
                                    <td style="vertical-align: middle;">{{ $value->consignee }}</td>
                                    <td style="vertical-align: middle;">{{ $value->from1 }}</td>
                                    <td style="vertical-align: middle;">{{ $value->to1 }}</td>
                                    
                                    
                                    <td style="width: 18%;text-align: center;vertical-align: middle;">
                                      <a data-id="{{ $value->shipment_no }}" style="margin-top: 3%;width: auto;min-width: 80%;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs ontheway"><i class="fa fa-reply"></i> Go to OnTheWay</a>
                                      <a data-id="{{ $value->shipment_no }}" {{-- href="{{ route('shipmentedit',['id'=>$value->myid]) }}" --}} style="margin-top: 3%;width: auto;min-width: 80%;"  class="btn btn-success newshipment btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-plus"></i> New Shipment</a>
                                      <a href="{{ route('shipmentWaretransporter',['id'=>$value->myid]) }}" style="margin-top: 3%;width: auto;min-width: 80%;"  class="btn btn-warning btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-plus"></i> Add Transporter</a>
                                    </td>
                                     <td class="center" style="vertical-align: middle;">
                                     <a href="{{ route('shipmentwaredetails',['id'=>$value->myid]) }}" style="margin-top: 3%;width: auto;min-width: 80%;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>
                                    
                                     <br><a href="{{ route('shipmentwareedit',['id'=>$value->myid]) }}" style="margin-top: 3%;width: auto;min-width: 80%;"  class="btn btn-success btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-pencil"></i> Edit</a>

                                     {{-- <form method="post" action="{{ route('shipmentdelete') }}">
                                      @csrf
                                      <input type="hidden" name="id" value="{{ $value->shipment_no }}">
                                      <button onclick="return confirm('Are you sure you want to Delete?');" style="margin-top: 3%;width: auto;min-width: 80%;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide" type="submit" ><i class="fa fa-trash"></i> Delete</i></button>
                                      </form> --}}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                
              </section>
              <!-- page end-->
          </section>
      </section>
       <button style="display: none;" type="button" id="popbtn" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>     <!-- The Mod$Request->al -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> Add New Shipment</h4>
      </div>
      <div class="modal-body">
        <div class="form">
                                    <form>   
                                      <input type="hidden" name="shipment_no" id="shipment_no" value="">
                                      <div class="form-group ">
                                            <label for="name" class="control-label col-lg-12">Shipment_No<span style="color: red">*</span> :</label>
                                            <div class="col-lg-12">
                                              <input type="hidden" id="ship_no">
                                               <input class="form-control" type="text" id="new_id" value="">
                                                <p class="text-danger" id="newerror"></p>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-12">From<span style="color: red">*</span> :</label>
                                            <div class="col-lg-12">
                                               <input class="form-control" type="text" id="from1" value="">
                                                <p class="text-danger" id="fromerror"></p>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-12">To<span style="color: red">*</span> :</label>
                                            <div class="col-lg-12">
                                               <input class="form-control" type="text" id="to1" value="">
                                                <p class="text-danger" id="toerror"></p>
                                            </div>
                                        </div>
                                       
                                         <div class="form-group "></div>
                                         <div class="form-group save_cancle">
                                            <div style="text-align: center;width: 100%">
                                                <a style="margin: 2% 0px 0px 0px;" class="btn btn-success" id="newbtn">Save</a>
                                                <a style="margin: 2% 0px 0px 0px;" class="btn btn-default" id="popclose" data-dismiss="modal" type="button">Cancel</a>
                                            </div>
                                        </div>
                                      </form>
                                    </div>
      </div>
     {{--  <div class="modal-footer">
        <button type="button" id="popclose" class="btn btn-default" data-dismiss="modal">Close</button> 
      </div>--}}
    </div>

  </div>
</div>

@endsection

@section('js1')
<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.js') }}"></script>

@endsection

@section('js3')

<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/data-tables/DT_bootstrap.js') }}"></script>
@endsection
@section('js4')

<script type="text/javascript">
  $(document).ready(function() {
    $('#editable-sample').DataTable( {
       "aaSorting": [[ 0, "desc" ]],
       /* "lengthChange": true,
      "lengthMenu": [ 10, 25, 50, 75, 100 ],
        dom: 'Bfrtip',
        buttons: [      
            'excelHtml5',
            'csvHtml5',     
        ]*/
    } );
} );
</script>
 <script type="text/javascript">
  $(".newshipment").click(function(){

    $("#ship_no").html('');
    $("#new_id").html('');
    $("#newerror").html('');
    $("#fromerror").html('');
    $("#toerror").html('');
    $("#from1").html('');
    $("#to1").html('');
    
    var shipment_no = $(this).attr('data-id');
    var _token   = $('meta[name="csrf-token"]').attr('content');
    $("#ship_no").val(shipment_no);
    //alert(shipment_no);

          $.ajax({
              url: "{{ route('shipmentnewid') }}",
              type:"POST",
              data:{
                shipment_no:shipment_no,
                _token: _token
              },
              success:function(response){
                console.log(response);
                if(response.code == 200) {
                   
                  
                   $('#new_id').val(response.newno);
                   $("#popbtn").click();

                } else {
                    console.log("Some Error");
                }
              },
             });

   
    //$("#shipment_no").val(shipment_no);

  });
</script>

<script type="text/javascript">
  
$("#newbtn").click(function(){

  $("#newerror").html('');
  $("#fromerror").html('');
  $("#toerror").html('');

  
  var new_id =  $('#new_id').val();
  var old_id = $('#ship_no').val();
  var from1 =  $('#from1').val();
  var to1 =  $('#to1').val();
  var _token   = $('meta[name="csrf-token"]').attr('content');


  if(new_id == "" || new_id == " " || new_id == "  "){
    $("#newerror").html('Please Select Shipment No.');
    $("#new_id").focus();
    return false;
  }


  if(from1 == "" || from1 == " " || from1 == "  "){
    $("#fromerror").html('Please Enter From.');
    $("#from1").focus();
    return false;
  }


  if(to1 == "" || to1 == " " || to1 == "  "){
    $("#toerror").html('Please Enter To.');
    $("#to1").focus();
    return false;
  }
 
  $("#popclose").click();

  $.ajax({
        url: "{{ route('newshipment') }}",
        type:"POST",
        data:{
          new_id:new_id,
          old_id:old_id,
          from1:from1,
          to1:to1,
           _token: _token
        },
        success:function(response){
          console.log(response);
          if(response.code == 200) {
            
            $("#"+old_id).remove();
          } else {
              console.log("Some Error");
          }
        },
       });


});

$(".ontheway").click(function(){
  
  if(confirm('Are you sure this shipment status for ontheway?')){

    var shipment_no = $(this).attr('data-id'); 
    var _token   = $('meta[name="csrf-token"]').attr('content');

     $.ajax({
        url: "{{ route('shipmentontheway') }}",
        type:"POST",
        data:{
          shipment_no:shipment_no,
           _token: _token
        },
        success:function(response){
          console.log(response);
          if(response.code == 200) {
            //$("#"+shipment_no+"mystatus").html('<span style="color: green">Delivered</span>');
             $("#"+shipment_no).css("display",'none');
          } else {
              console.log("Some Error");
          }
        },
       });


  }



});



</script>




@endsection