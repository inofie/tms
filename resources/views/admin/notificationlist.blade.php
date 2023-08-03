@extends('layout.master')

@section('title')
Notifications List | TOT
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
              <!-- page start-->
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
              <section class="panel">
                  <header class="panel-heading">
                      Notifications List
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                      <div class="clearfix">
                              <div class="btn-group pull-right">
                                 <!-- <a href="{{ route('driveradd') }}"> <button  class="btn btn-success">
                                      Add Driver <i class="fa fa-plus"></i>
                                  </button></a> -->
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
                                  <th>ID</th>
                                  <th>Notification From</th>
                                  <th>Title</th>
                                  <th>Description</th>
                                  <th>Created At</th>
                                  <th>Action</th>
                              </tr>
                              </thead>

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
  var SITE_URL = "<?php echo URL::to('/'); ?>";
  function deleteItem(id){

    var r = confirm("Are You Sure Delete It!");
      if (r == true) {
        $("#delete"+id).submit();
      }

    }


  $(document).ready(function() {
    fill_datatable();
} );
function fill_datatable(){
  var getNotification = '{{ route('getNotification')}}';
  $('#editable-sample').DataTable({

      ajax: {
                        url: getNotification,
                        // type: "get",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        }
                    },
                    processing: true,
      serverSide: true,
      destroy: true,
      retrieve:true,
                    columns: [{
                            data: 'id',
                            name: 'ID'
                        },
                        {
                            data: 'user_name_from',
                            name: 'Notification From',
                        },
                        {
                            data: 'title',
                            name: 'Title'
                        },
                        {
                            data: 'message',
                            name: 'Description',

                        },
                        {
                            data: 'created_at',
                            name: 'Created At'
                        },
                        {
                            data: 'role',
                            name: 'action'
                        }
                    ],
                    columnDefs: [
                      {
                    targets: 5,
                    render: function (data, type, row) {
                      var ids = data?data.split('_'):[];
                      let adminRoute='#';
                      if(ids.length >= 0){
                        let shipment_id=ids[0];
                        if(ids[1]=='transporter'){
                           adminRoute = SITE_URL + '/admin/shipment/detail/'+shipment_id;
                        }else{
                          adminRoute=SITE_URL + '/admin/shipment/detail/'+shipment_id;

                        }
                      }
return '<a href='+adminRoute+' style="margin-top: 3%;width: auto;min-width: 80%;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>';
                    }
                  }
                    ]
       /* "lengthChange": true,
      "lengthMenu": [ 10, 25, 50, 75, 100 ],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'csvHtml5',
        ]*/
    });
}
</script>

@endsection