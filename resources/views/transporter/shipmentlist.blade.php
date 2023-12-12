@extends('layout.master')

@section('title')
Shipment List | TMS
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
                        

                                 <!-- <a style="padding:0px 1px;" href="{{route('shipmentadd')}}">
                                  <button  class="btn btn-success">
                                      <i class="fa fa-plus"></i> ADD
                                  </button>
                                </a> -->
                                <a style="padding:0px 1px;" href="{{route('myfiltertransporter')}}">
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
                                    <th>Ship.No.</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Consignor</th>
                                    <th>Created Date</th>
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
                                    <td style="vertical-align: middle;">{{ $value->created_at }}</td>
                                    
                                    <td style="vertical-align: middle;">{{ $value->consignee }}</td>
                                    
                                    <td style="vertical-align: middle;">{{ $value->from1 }}</td>
                                    
                                    <td style="vertical-align: middle;">{{ $value->to1 }}</td>

                                    <td style="vertical-align: middle;">{{ $value->invoice_amount??"0" }}</td>
                                    
                                   
                                   
                                    <td id="{{ $value->shipment_no }}mystatus" style="vertical-align: middle;text-align: center;">
                                      @if($value->status == 1) 
                                        <span style="color: blue">Pending</span> 
                                      @elseif($value->status == 2 ) 
                                        <span style="color: orange">Ontheway</span>
                                      @elseif($value->status == 3 ) 
                                        <span style="color: green">Delivered</span>
                                  
                                      @endif
                                    </td>
                                
                                   
                                    
                                    <td style="width: 18%;text-align: center;vertical-align: middle;">
                                  
                                      <div style="width: 100%;float: left;" class="{{ $value->shipment_no }}hide">
                                        @if($value->status == 2 )
                                         <a href="{{ route('shipmenttrucklist',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-primary btn-xs"><i class="fa fa-truck"></i> Trucks
                                         </a>
                                         @endif
                                        
                                      </div>
                                      <div style="width: 100%;float: left;">
                                        
                                          
                                        <a href="{{ route('addexpensebytransporter',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff"  class="btn expense btn-xs"><i class="fa fa-plus"></i> Expense </a>
                                          @if($value->status == 1 || $value->status == 2)
                                       <a href="{{ route('shipmenttransporter',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-plus"></i> Transporter</i></a> 
                                       @endif                         
                                      </div>

                                      

                                    </td>
                                     <td class="center" style="vertical-align: middle;">
                                     <a href="{{ route('shipmentdetailstransporter',['id'=>$value->myid]) }}" style="margin-top: 3%;width: auto;min-width: 80%;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>
                                     <a href="{{ route('allshipmentsummarylisttransporter',['shipment_no'=>$value->shipment_no]) }}" style="margin-top: 3%;width: auto;min-width: 60%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> Shipment Summary</a>
                                     
                                      
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

    

  </div>
</div>

@endsection


@section('js4')

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
 
</script>

<script type="text/javascript">
  
</script>




@endsection