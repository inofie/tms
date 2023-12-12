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
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="{{ route('myfilterforwarder') }}" >
                                       <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Search :</label>
                                            <div class="col-lg-10">
                                              <input type="text" name="searchValue" class="form-control" value="{{ $search }}">
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
                                                <select class="form-control" name="year" id="year" >
                                                   <option value=""> -- Select Year -- </option>
                                                      @foreach($yearRange as $value)
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
                                                   <option value=""> -- Select Month -- </option>
                                                   <option value='1'@if($month == '1') selected @endif>January</option>
                                                    <option value='2'@if($month == '2') selected @endif>February</option>
                                                    <option value='3'@if($month == '3') selected @endif>March</option>
                                                    <option value='4'@if($month == '4') selected @endif>April</option>
                                                    <option value='5'@if($month == '5') selected @endif>May</option>
                                                    <option value='6'@if($month == '6') selected @endif>June</option>
                                                    <option value='7'@if($month == '7') selected @endif>July</option>
                                                    <option value='8'@if($month == '8') selected @endif>August</option>
                                                    <option value='9'@if($month == '9') selected @endif>September</option>
                                                    <option value='10'@if($month == '10') selected @endif>October</option>
                                                    <option value='11'@if($month == '11') selected @endif>November</option>
                                                    <option value='12' @if($month == '12') selected @endif>December</option>
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
                                                   <option value=""> -- Select Date -- </option>
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
                                                <a  href="{{ route('myfilterforwarder') }}" class="btn btn-default" type="reset">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                  @if($showTable)
                  <section class="panel">
                  <header class="panel-heading">
                      Filter List
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                        {!! $html->table(['class' => 'table table-bordered', 'id' => 'ShipmentFilterDataTable']) !!}
                      </div>
                  </div>
              </section>
              @endif
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
{!! $html->scripts() !!}
<script type="text/javascript">
  $(document).ready(function() {
    // $('#editable-sample').DataTable( {
    //    "aaSorting": [[ 4, "desc" ]],
    //     "lengthChange": true,
    //   "lengthMenu": [ 10, 25, 50, 75, 100 ],
    //     dom: 'Bfrtip',
    //     "columnDefs":
    //        [
    //            {
    //                "targets": [7,8,10,11],
    //                "visible": false,
    //            },
    //        ],
    //     buttons: [
    //         {
    //             extend: 'csvHtml5',
    //             exportOptions: {
    //               columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
    //             }
    //         },
    //         {
    //             extend: 'excelHtml5',
    //             exportOptions: {
    //               columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
    //             }
    //         },
    //     ]
    // } );
} );
$(document.body).on('click', '.warehouse' ,function(event){
  // $(".warehouse").click(function(){
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
$(document.body).on('click', '.delivered' ,function(event){
// $(".delivered").click(function(){
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