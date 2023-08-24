@extends('layout.master')

@section('title')
Shipment Filter | TMS
@endsection
@section('css2')

  <link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />

  <link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">

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


@section('content')
<!--main content start-->
   <section id="main-content">
          <section class="wrapper">
              <section class="panel">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="{{ route('myfilter') }}" >
                                      
                                       <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Search :</label>
                                            <div class="col-lg-10">
                                              <input type="text" name="search" class="form-control" value="{{ $search }}">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Shipment ID :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="shipment" id="shipment" > 
                                                   <option value=""> -- Please Select Shipment ID -- </option>
                                                      @foreach($tt as $value)
                                                      @if($ttt == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->shipment_no }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->shipment_no }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        
                                      <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Transporter :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="transporter" id="transporter" > 
                                                   <option value=""> -- Please Select Transporter -- </option>
                                                      @foreach($all_transporter as $value)
                                                      @if($transporter == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Forwarder :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="forwarder" id="forwarder" > 
                                                   <option value=""> -- Please Select Forwarder -- </option>
                                                      @foreach($all_forwarder as $value)
                                                      @if($forwarder == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="company_id" class="control-label col-lg-2">Company :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="company" id="company" > 
                                                   <option value=""> -- Please Select Company -- </option>
                                                      @foreach($all_company as $value)
                                                      @if($company == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Status :</label>
                                            <div class="col-lg-10">
                                            <?php 
												                    $all_year = ['Pending','Ontheway','Delivered'];
                                            ?>
                                                <select class="form-control" name="status" id="status" > 
                                                   <option value=""> -- Please Select Status -- </option>
                                                      @foreach($all_year as $value)
                                                      @if($tts == $value)
                                                      <option selected="selected" value="{{ $value }}">{{ $value }}</option>
                                                      @else
                                                      <option value="{{ $value }}">{{ $value }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Year :</label>
                                            <div class="col-lg-2">
                                              <?php 
												$all_year = ['2020','2021','2022','2023','2024','2025','2026','2027','2028','2029','2030','2031','2032','2033','2034','2035','2036','2037','2038','2039','2040'];
												//$all_year = range(2020, date('Y',strtotime('+2 year')));
												?>
                                                <select class="form-control" name="year" id="year" > 
                                                   <option value=""> -- Please Select Year -- </option>
                                                      @foreach($all_year as $value)
                                                      @if($year == $value)
                                                      <option selected="selected" value="{{ $value }}">{{ $value }}</option>
                                                      @else
                                                      <option value="{{ $value }}">{{ $value }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Month :</label>
                                            <div class="col-lg-2">
                                              <?php 
												// $all_month= ['1','2','3','4','5','6','7','8','9','10','11','12']; 
												//$all_month = range(1, 12);
												?>
                                                <select class="form-control" name="month" id="month" > 
                                                   <option value=""> -- Please Select Month -- </option>
                                                   <option value='1'>January</option>
                                                    <option value='2'>February</option>
                                                    <option value='3'>March</option>
                                                    <option value='4'>April</option>
                                                    <option value='5'>May</option>
                                                    <option value='6'>June</option>
                                                    <option value='7'>July</option>
                                                    <option value='8'>August</option>
                                                    <option value='9'>September</option>
                                                    <option value='10'>October</option>
                                                    <option value='11'>November</option>
                                                    <option value='12'>December</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Date :</label>
                                            <div class="col-lg-2">
                                              <?php 
												$all_day = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31']; 
												//$all_day = range(1, 31);
												?>
                                                <select class="form-control" name="date" id="date" > 
                                                   <option value=""> -- Please Select Date -- </option>
                                                      @foreach($all_day as $value)
                                                      @if($date == $value)
                                                      <option selected="selected" value="{{ $value }}">{{ $value }}</option>
                                                      @else
                                                      <option value="{{ $value }}">{{ $value }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>

                                         <div class="form-group save_cancle">
                                            <div class="col-lg-10 center">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                 {{-- <button class="btn btn-default" type="reset">Reset</button> --}}
                                                <a  href="{{ route('myfilter') }}" class="btn btn-default" type="reset">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>



                        <section class="panel">
                  <header class="panel-heading">
                      Filter List
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                          
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
                                    <th>Transporter Name</th>
                                    <th>Truck No</th>
                                    <th>Status</th>
                                    
                                    <th>Invoice Cost</th>
                                    <th>Transporter Cost</th>
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
                                    <td style="vertical-align: middle;">{{ $value->transporter_name }}</td>
                                    <td style="vertical-align: middle;">{{ $value->truck_no }}</td>
                                    
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

                                    <td style="vertical-align: middle;">{{ $value->invoice_cost }}</td>
                                    <td style="vertical-align: middle;">{{ $value->transporter_cost }}</td>
                                     <td class="center" style="vertical-align: middle;">
                                     <a href="{{ route('shipalldetail',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>
                                     <a href="{{ route('shipmenttrucklists',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-primary btn-xs"><i class="fa fa-truck"></i> Trucks
                                    </a>
                                    <a data-id="{{ $value->shipment_no }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #047fb9; color: #fff;" class="btn btn-xs delivered"><i class="fa fa-bus"></i> Delivered</i>
                                    </a>
                                    <a href="{{ route('addexpensebyadmin',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff"  class="btn expense btn-xs"><i class="fa fa-plus"></i> Expense </a>
                                  
                                    <a href="{{ route('shipmenttransporters',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-plus"></i> Transporter</i></a> 
                                    <a  data-id="{{ $value->shipment_no }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #7ca00f; color: #fff;" class="btn btn-xs warehouse {{ $value->shipment_no }}hide"><i class="fa fa-plus"></i> Add in Warehouse</i></a>

                                    <a href="{{ route('downloadlr',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide "><i class="fa fa-download "></i> LR</i></a>
                                    <a href="{{ route('allshipmentsummarylist',['shipment_no'=>$value->shipment_no]) }}" style="margin-top: 3%;width: auto;min-width: 60%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> Shipment Summary</a>
                                    
                                    <br><a href="{{ route('shipmentedit',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;"  class="btn btn-success btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-pencil"></i> Edit</a><br>
                                    <form method="post" action="{{ route('shipmentdelete') }}">
                                     @csrf
                                     <input type="hidden" name="id" value="{{ $value->shipment_no }}">
                                     <button onclick="return confirm('Are you sure you want to Delete?');" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide" type="submit" ><i class="fa fa-trash"></i> Delete</i></button>
                                     </form>

                                    </td>
                                </tr>


                                @endforeach

                              </tbody>
                          </table>
                      </div>
                  </div>
              </section>
              
              
              

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
      <!--main content end-->
@endsection

@section('js4')


<script type="text/javascript">
  $(document).ready(function() {
    $('#editable-sample').DataTable( {
       "aaSorting": [[ 4, "desc" ]],
        "lengthChange": true,
      "lengthMenu": [ 10, 25, 50, 75, 100 ],
        dom: 'Bfrtip',
        "columnDefs":
           [
               {
                   "targets": [7,8,10,11],
                   "visible": false, 
               },
           ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
                }
            },
           
            
        ]
    } );
} );

  $(".warehouse").click(function(){

    var shipment_no = $(this).attr('data-id');
    $("#warehouseerror").html('');
        //alert(shipment_no);
    $("#popbtn").click();
    $("#shipment_no").val(shipment_no);

  });

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