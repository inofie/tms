@extends('layout.master')

@section('title')
Shipment List | TMS
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
                      Shipment List
                       <div class="btn-group pull-right">
                        

                                 <a style="padding:0px 1px;" href="{{route('shipmentadd')}}">
                                  <button  class="btn btn-success">
                                      <i class="fa fa-plus"></i> ADD
                                  </button>
                                </a>

                                 
                                
                                </div>
                  </header>

               
                        <div class="adv-table" style="padding: 1%;">
                             <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                <tr>
                                    <th>Ship.No.</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Consignor</th>
                                    <th>Consignee</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="center">Others</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $value)

                                <tr id="{{ $value->shipment_no }}">
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

                                    <td style="vertical-align: middle;">{{ $value->invoice_amount }}.00</td>
                                    
                                    <td id="{{ $value->shipment_no }}mystatus" style="vertical-align: middle;text-align: center;">
                                      @if($value->status == 0) 
                                        <span style="color: blue">Pending</span> 
                                      @elseif($value->status == 1) 
                                        <span style="color: orange">Ontheway</span>
                                      @elseif($value->status == 2)
                                        <span style="color: green">Delivered</span>
                                      @elseif($value->status == 4) 
                                        <span style="color: pink">Warehouse</span>
                                      @endif
                                    </td>
                                    
                                    <td style="width: 18%;text-align: center;vertical-align: middle;">
                                      <div style="width: 100%;float: left;" class="{{ $value->shipment_no }}hide">
                                        @if($value->status ==1)
                                         <a href="{{ route('shipmenttrucklist',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-primary btn-xs"><i class="fa fa-truck"></i> Trucks
                                         </a>
                                         @endif
                                         @if($value->status == 1)
                                          <a data-id="{{ $value->shipment_no }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #047fb9; color: #fff;" class="btn btn-xs delivered"><i class="fa fa-bus"></i> Delivered</i>
                                          </a>
                                       @endif 
                                      </div>
                                      <div style="width: 100%;float: left;">
                                        
                                          
                                        <a href="{{ route('addexpensebyadmin',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff"  class="btn expense btn-xs"><i class="fa fa-plus"></i> Expense </a>
                                          @if($value->status == 0 || $value->status == 1)
                                       <a href="{{ route('shipmenttransporter',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-plus"></i> Transporter</i></a> 
                                       @endif                         
                                      </div>

                                      <div style="width: 100%;float: left;" class="{{ $value->shipment_no }}hide">
                                        @if( $value->status == 1)
                                        <a  data-id="{{ $value->shipment_no }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #7ca00f; color: #fff;" class="btn btn-xs warehouse {{ $value->shipment_no }}hide"><i class="fa fa-plus"></i> Add in Warehouse</i></a>

                                           <a href="{{ route('downloadlr',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide "><i class="fa fa-download "></i> LR</i></a>
                                       @endif
                                      </div>

                                    </td>
                                     <td class="center" style="vertical-align: middle;">
                                     <a href="{{ route('shipmentdetails',['id'=>$value->myid]) }}" style="margin-top: 3%;width: auto;min-width: 80%;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>
                                     @if($value->status == 0 || $value->status == 1)
                                     <br><a href="{{ route('shipmentedit',['id'=>$value->myid]) }}" style="margin-top: 3%;width: auto;min-width: 80%;"  class="btn btn-success btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-pencil"></i> Edit</a><br>
                                     <form method="post" action="{{ route('shipmentdelete') }}">
                                      @csrf
                                      <input type="hidden" name="id" value="{{ $value->shipment_no }}">
                                      <button onclick="return confirm('Are you sure you want to Delete?');" style="margin-top: 3%;width: auto;min-width: 80%;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide" type="submit" ><i class="fa fa-trash"></i> Delete</i></button>
                                      </form>
                                      @endif
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
        <h4 class="modal-title"> Add In Warehouse</h4>
      </div>
      <div class="modal-body">
        <div class="form">
                                    <form>   
                                      <input type="hidden" name="shipment_no" id="shipment_no" value="">
                                      <div class="form-group ">
                                            <label for="name" class="control-label col-lg-12">Warehouse<span style="color: red">*</span> :</label>
                                            <div class="col-lg-12">
                                                <select class="form-control" id="warehouse_id" name="warehouse_id" required="required"> 
                                                   <option value=""> -- Please Select Warehouse -- </option>
                                                      @foreach($warehouse as $value1)
                                                     
                                                      <option value="{{ $value1->id }}">{{ $value1->name }}</option>
                                                     
                                                      @endforeach

                                                </select>

                                              <p class="text-danger" id="warehouseerror"></p>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-12">Reason :</label>
                                            <div class="col-lg-12">
                                               
                                               <textarea class=" form-control" id="reason" name="reason"></textarea>
                                                
                                            </div>
                                        </div>
                                         <div class="form-group "></div>
                                         <div class="form-group save_cancle">
                                            <div style="text-align: center;width: 100%">
                                                <a style="margin: 2% 0px 0px 0px;" class="btn btn-success" id="warehousebtn">Save</a>
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
       "aaSorting": [[ 1, "desc" ]],
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
  $(".warehouse").click(function(){

    var shipment_no = $(this).attr('data-id');
    $("#warehouseerror").html('');
        //alert(shipment_no);
    $("#popbtn").click();
    $("#shipment_no").val(shipment_no);

  });
</script>

<script type="text/javascript">
  
$("#warehousebtn").click(function(){

  var warehouse_id = $("#warehouse_id").val();
  var shipment_no = $("#shipment_no").val();
  var reason = $("#reason").val();
  var _token   = $('meta[name="csrf-token"]').attr('content');

  if(warehouse_id == ""){
    $("#warehouseerror").html('Please Select Warehouse.');
    $("#warehouse_id").focus();
    return false;
  }
 
  $("#popclose").click();

  $.ajax({
        url: "{{ route('shipwarehousein') }}",
        type:"POST",
        data:{
          warehouse_id:warehouse_id,
          shipment_no:shipment_no,
          reason:reason,
           _token: _token
        },
        success:function(response){
          console.log(response);
          if(response.code == 200) {
            
            $("#"+shipment_no).remove();
          } else {
              console.log("Some Error");
          }
        },
       });


});

$(".delivered").click(function(){
  
  if(confirm('Are you sure this shipment delivered?')){

    var shipment_no = $(this).attr('data-id'); 
    var _token   = $('meta[name="csrf-token"]').attr('content');

     $.ajax({
        url: "{{ route('shipmentdelivered') }}",
        type:"POST",
        data:{
          shipment_no:shipment_no,
           _token: _token
        },
        success:function(response){
          console.log(response);
          if(response.code == 200) {
            $("#"+shipment_no+"mystatus").html('<span style="color: green">Delivered</span>');
             $("."+shipment_no+"hide").css("display",'none');
          } else {
              console.log("Some Error");
          }
        },
       });


  }



});



</script>




@endsection