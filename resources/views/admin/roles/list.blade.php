@extends('layout.master')
@section('title') Role @endsection

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


    <!-- Main content -->
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
                  <header class="panel-heading">
                      Manage Role
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                          <div class="clearfix">
                              <div class="btn-group pull-right">
                                 <a href="{{ route('rolesadd') }}"> <button  class="btn btn-success"><!-- id="editable-sample_new" -->
                                      Add Role <i class="fa fa-plus"></i>
                                  </button></a>
                                </div>
                              <div class="btn-group ">
                                  <!-- <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i> -->
                                  </button>
                                  <!-- <ul class="dropdown-menu pull-right">
                                      <li><a href="#">Print</a></li>
                                      <li><a href="#">Save as PDF</a></li>
                                      <li><a href="#">Export to Excel</a></li>
                                  </ul> -->
                              </div>
                          </div>
                          <div class="space15"></div>
                          <table class="table table-striped table-hover table-bordered" id="editable-sample">
                              <thead>
                             <tr>
                                  <th>Id</th>
                                  <!-- <th>Guard Name</th> -->
                                  <th>Name</th>
                                  <th>Created at</th>
                                  
                                  <th>Action</th>
                              </tr>
                              </thead>
                              <tbody>

                               @foreach($result as $value)
                              
                              <tr class="table_space">
                                 
                                  <td>{{ $value->id }}</td>
                                  <!-- <td>{{ $value->guard_name }}</td> -->
                                  <td>{{ $value->name }}</td>
                                  <td>{{ $value->created_at}}</td>
                                  
                                  
                                  <td class="edit_delete">
                                  <a href="{{ route('rolesedit',['id'=>$value->id]) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                    <button onclick="deleteItem('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  
                                      <form action="{{ route('rolesdelete',['id'=>$value->id]) }}" id="delete{{$value->id}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$value->id}}">
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
    <!-- /.content -->
</div>
@endsection


@section('js4')
<script>
    var SITE_URL = "<?php echo URL::to('/'); ?>";
    function deleteItem(id){

var r = confirm("Are You Sure Delete It!");
  if (r == true) {
    $("#delete"+id).submit();  
  }

}
function deleteConfirm(id){
    if(id==1){
       bootbox.alert("You can't delete admin role");
    }
    else{
        bootbox.confirm({
            message: "Are you sure you want to delete ?",
            buttons: {
                'cancel': {label: 'No',className: 'btn-danger'},
                'confirm': {label: 'Yes',className: 'btn-success'}
            },
            callback: function(result){
                if (result){
                    $.ajax({
                        url: SITE_URL + '/admin/roles/'+id,
                        type: "DELETE",
                        cache: false,
                        data:{ _token:'{{ csrf_token() }}'},
                        success: function (data, textStatus, xhr) {
                        if(data== true && textStatus=='success' && xhr.status=='200')
                            {
                                toastr.warning('Role Deleted !!');
                                $('#DataTable').DataTable().ajax.reload(null, false);
                            }else {  
                                toastr.error(data); 
                            }
                        }
                    });
                }
            }
        });
    }       
}  
</script>
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
@endsection
