@extends('layout.master')
@section('title')
Shipment List | TMS
@endsection
@section('css2')
<link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
<link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
<style type="text/css">
  .pannel{
    overflow:hidden;
  }
table.dataTable{
   display: block !important;
    overflow-x: auto !important;
}
.state-overview .symbol{
    width: 35% !important;
    padding: 20px 15px !important;
}
.state-overview .value{
       float: inherit !important;
      width: 55% !important;
}
.state-overview .panel .value h1 {
    font-weight: 500 !important;
    font-size: 20px !important;
}
.text-blue
{
  color:blue;
}
.text-orange
{
  color:orange;
}
.text-green
{
  color:green;
}
.text-pink
{
  color:pink;
}
</style>
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
                                <a style="padding:0px 1px;" href="{{route('myfilter')}}">
                                  <button  class="btn btn-primary">
                                      <i class="fa fa-plus"></i> Filter
                                  </button>
                                </a>
                      </div>
                  </header>
                  <div class="adv-table" style="padding: 1%;">
                  {!! $html->table(['class' => 'table table-bordered', 'id' => 'ShipmentLatestDataTable']) !!}
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
@section('js4')
{!! $html->scripts() !!}
<script type="text/javascript">
  $(document).ready(function() {
    $('#editable-sample').DataTable( {
      "aaSorting": [[ 4, "desc" ]],
       "columnDefs":
           [
               {
                   "targets": [4],
                   "visible": false,
               },
           ],
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
  $(document.body).on('click', '.warehouse' ,function(event){
  // $(".warehouse").click(function(){
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
             location.reload();
          } else {
              console.log("Some Error");
          }
        },
       });
  }
});
</script>
@endsection