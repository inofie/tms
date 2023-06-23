@extends('layout.master')

@section('title')
All Unpaid Invoice List | TOT
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
              <!-- page start-->@if ($message = Session::get('success'))
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
              <section class="panel">
                  <header class="panel-heading">
                      Unpaid Invoice List
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                          <div class="clearfix">
                              <div class="btn-group pull-right">
                                 <a href="{{ route('invoiceadd') }}"> <button  class="btn btn-success"><!-- id="editable-sample_new" -->
                                    <i class="fa fa-plus">  Add Invoice </i>
                                  </button></a>
                                </div>
                              <div class="btn-group ">
                                  <!-- <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i> 
                                  </button>-->
                                  <ul class="dropdown-menu pull-right">
                                      <li><a href="#">Print</a></li>
                                      <li><a href="#">Save as PDF</a></li>
                                      <li><a href="#">Export to Excel</a></li>
                                  </ul>
                              </div>
                          </div>
                          <div class="space15"></div>
                          <table class="table table-striped table-hover table-bordered" id="editable-sample">
                              <thead>
                              <tr>
                                  <th width="10%">Invoice No</th>
                                  <th width="10%">Date</th>
                                  <th width="20%">Company</th>
                                  <th width="20%">Forwarder</th>
                                  <th width="10%">Created Date</th>
                                  <th width="10%">Total Amount</th>
                                  <th width="30%" class="center">Action</th>
                              </tr>
                              </thead>
                              <tbody>

                              @foreach($data as $value)

                               <tr class="table_space">
                                  
                                  <td style="vertical-align: middle;">{{ $value->invoice_no }}</td>
                                  <td style="vertical-align: middle;">{{ date('d-m-Y',strtotime($value->invoice_date)) }}</td>
                                  
                                  <td style="vertical-align: middle;">{{ $value->company_name }}</td>
                                  <td style="vertical-align: middle;">{{ $value->forwarder_name }}</td>
                                  <td style="vertical-align: middle;">{{ $value->created_at }}</td>
                                   <td style="text-align: right;vertical-align: middle;"><i class="fa fa-inr"></i>{{ number_format($value->grand_total,0) }}</td>
                                  
                                  <td class="edit_delete center">
                                     <a href="{{ route('downloadinvoice',['id'=>$value->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn expense "><i class="fa fa-download "></i> Download</a>
                                      <a target="_blank" style="margin-top: 2%;width: auto; margin:1%;" href="{{ route('invoiceview',['id'=>$value->myid]) }}" class="btn btn-warning "><i class="fa fa-eye"></i> View</a>
                                    <a style="margin-top: 2%;width: auto; margin:1%;" href="{{ route('invoiceedit',['id'=>$value->myid]) }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                                      <button  style="margin-top: 2%;width: auto; margin:1%; " onclick="deleteItem('{{ $value->myid }}')" class="btn btn-danger "><i class="fa fa-trash-o "></i> Delete</button>
                                      <form action="{{ route('invoicedelete') }}" id="delete{{$value->myid}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$value->myid}}">
                                      </form>
                                      
                                  </td>
                                  
                              </tr>
                              
                              @endforeach 
                                
                        
                              </tbody>
                          </table>
                      </div>
                  </div>
              </section>
              <!-- page end-->
          </section>
      </section>
      <!--main content end-->

@endsection

@section('js4')


<script type="text/javascript">
  function deleteItem(id){

    var r = confirm("Are You Sure Delete It!");
      if (r == true) {
        $("#delete"+id).submit();  
      } 

    }


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

@endsection