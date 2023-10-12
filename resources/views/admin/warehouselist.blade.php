@extends('layout.master')

@section('title')
All WareHouse List | TOT
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
                      Warehouse List
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                          <div class="clearfix">
                              <div class="btn-group pull-right">
                                 <a href="{{ route('warehouseadd') }}"> <button  class="btn btn-success"><!-- id="editable-sample_new" -->
                                      Add Warehouse <i class="fa fa-plus"></i>
                                  </button></a>
                                </div>
                              <div class="btn-group ">
                                  <!-- <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i> -->
                                  </button>
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
                                  <th style="width:12.11%; ">Full Name</th>
                                  <th style="width:12.11%; ">User Name</th>
                                  <th style="width:12.11%; ">Company</th>
                                  <th style="width:12.11%; ">Address</th>
                                  <th style="width:9.11%; ">Add Proof</th>
                                  <th style="width:12.11%; ">Phone Number</th>
                                  <th style="width:12.11%; ">GST Number</th>
                                  <th style="width:12.11%; ">Pan Number</th>
                                  <th style="width:9.11%; ">Status</th>
                                  <th style="width:9.11%; ">Action</th>
                              </tr>
                              </thead>
                              <tbody>

                               @foreach($data as $value)

                               <tr class="table_space">
                                  <td  id="change_color">{{ $value->name }}</td>
                                  <td>{{ $value->user_name }}</td>
                                  <td>{{ $value->company_name }}</td>
                                  <td>{{ $value->address }}</td>
                                  <td><img src="{{ asset('/uploads') }}/{{ $value->address_proof }}" width="50px" alt="" class="zoom"></td>
                                  <td>{{ $value->phone }}</td>
                                  <td>{{ $value->gst }}</td>
                                  <td>{{ $value->pan }}</td> 
                                  <td class="center">
                                  @if($value->status == 0 )Active  @else Deactive @endif
                                  </td>
                                  <td class="edit_delete">
                                    <a href="{{ route('warehouseedit',['id'=>$value->myid]) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                      <button onclick="deleteItem('{{ $value->myid }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                      <form action="{{ route('warehousedelete') }}" id="delete{{$value->myid}}" method="post">
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

@endsection