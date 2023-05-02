@extends('layout.master')

@section('title')
All Trucks List | TMS
@endsection

@section('css2')

  <link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />

    <link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('assets/data-tables/DT_bootstrap.css')}}" />
@endsection


@section('content')


</div>
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
                      Truck List
                       <div class="btn-group pull-right">
                                 <a href="{{route('shipmentlist')}}">
                                  <button  class="btn btn-primary">
                                      <i class="fa fa-reply"></i> Back
                                  </button>
                                </a>
                                </div>
                  </header>


               
                        <div class="adv-table" style="padding: 1%;">
                             <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                <tr>
                                    <th class="center">Shipment No.</th>
                                    <th class="center">Truck No.</th>
                                    <th class="center">Mobile No.</th>
                                    <th class="center">Status</th>
                                    <th class="center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $value)

                                <tr>
                                    <td class="center"><b>{{ $value->shipment_no }}</b></td>
                                    <td class="center">{{ $value->truck_no}}</td>
                                    <td class="center">{{ $value->mobile}}</td>
                                    <td class="center">{{ $value['status_name'] }}</td>
                                    <td class="center" style="width: 20%">
                                 
                                     
                                    
                                         <a data-id="{{ $value->id }}" style="margin-top: 2%;width: auto;min-width: 35%;float: left;" id="change"  class="btn btn-success btn-xs "><i class="fa fa-pencil"></i> Change Status</a>
                                           <form action="{{ route('deletetruckstatusadmin') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}">
                                      <button type="save" onclick="return confirm('Are you sure you want to Delete?');" style="margin-top: 2%;width: auto;min-width: 35%;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</i></button>
                                      </form>
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
        <h4 class="modal-title"> Change Truck Status</h4>
      </div>
      <div class="modal-body">
        <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="signupForm" method="post" action="{{ route('changetruckstatusadmin') }}" enctype="multipart/form-data">
                                      @csrf
                                      <input type="hidden" name="shipment_no" value="{{ $shipment_no }}">
                                      <input type="hidden" id="truck_id" name="truck_id" value="">

                                      <div class="form-group ">
                                            <label for="name" class="control-label col-lg-2">Status<span style="color: red">*</span> :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="changestatus" name="status" required="required"> 
                                                   <option value=""> -- Please Select Stauts -- </option>
                                                      @foreach($status as $value1)
                                                     
                                                      <option value="{{ $value1->id }}">{{ $value1->name }}</option>
                                                     
                                                      @endforeach

                                                </select>

                                                @error('transporter')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-2">Reason :</label>
                                            <div class="col-lg-10">
                                               
                                               <textarea class=" form-control" id="reason" name="reason"></textarea>
                                                
                                            </div>
                                        </div>
                                         <div class="form-group save_cancle">
                                            <div style="text-align: center;width: 100%">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                <button class="btn btn-default" id="popclose" data-dismiss="modal" type="button">Cancel</button>
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

<!-- Model End -->

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
<!-- Modal -->

<script type="text/javascript">
 // Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("change");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}
  

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

$('.btn-success').click(function(){

  $("#popbtn").click();

  var id = $(this).attr("data-id");
  
  $('#truck_id').val(id);
  
    var modal = document.getElementById("myModal");

    var dropDown = document.getElementById("changestatus");
    dropDown.selectedIndex = 0;

    $('#reason').val('');
 
  window.onclick = function(event) {
  if (event.target == modal) {
    $("#popclose").click();
    }
  }

});


</script>


@endsection