@extends('layout.master')

@section('title')
All Expenses List | Helard
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
                  <header class="panel-heading" style="line-height: 30px;">
                      Expense List
                        <div class="btn-group pull-right">
                                
                                 <a href="{{ route('expenseadd') }}" style="min-width: 20%; width: auto;" class="btn btn-primary "><i class="fa fa-plus"></i> Add</a>
                                </div>
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                        
                          <table class="table table-striped table-hover table-bordered" id="editable-sample">
                              <thead>
                              <tr>
                                  <th>Date</th>
                                   <th>Company Name</th>
                                   <th>Transporter Name</th>
                                   <th>Type</th>
                                  <th class="center">Details</th>
                                  <th class="center">Amount</th>
                                 <th class="center">Action</th>
                              </tr>
                              </thead>
                              <tbody>
                                  @foreach($data as $value)

                               <tr class="table_space"> 
                                  <td class="center"> {{ date('d-m-Y',strtotime($value->dates))}}</td>
                                  <td style="vertical-align: middle;">{{ $value->company_name }}</td>
                                  <td style="vertical-align: middle;">{{ $value->transporter_name }}</td>
                                  <td style="vertical-align: middle;">{{ $value->type }}</td>
                                  <td style="vertical-align: middle;">{{ $value->reason }}</td>
                                  <td class="center" style="vertical-align: middle;">{{ $value->amount }}</td>
                                  <td class="edit_delete center" style="vertical-align: middle;">
                                    <a href="{{ route('expenseview',['id'=>$value->id]) }}" style="min-width: 20%; width: auto;" class="btn btn-primary "><i class="fa fa-eye"></i> View</a>

                                     <button onclick="deleteItem('{{ $value->id }}')" style="min-width: 20%;width: auto;" class="btn btn-danger "><i class="fa fa-trash-o "></i> Delete</button>
                                      <form action="{{ route('expensedelete') }}" id="delete{{$value->id}}" method="post">
                                        @csrf
                                        <input type="hidden"  name="id" value="{{$value->id}}">
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