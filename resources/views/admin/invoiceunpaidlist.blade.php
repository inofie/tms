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
          <section class="wrapper">
              <section class="panel">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="{{ route('unpaidshipmentlist') }}" >
                                      
                                   
                                    

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

                                 

                                         <div class="form-group save_cancle">
                                            <div class="col-lg-10 center">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                 {{-- <button class="btn btn-default" type="reset">Reset</button> --}}
                                                <a  href="{{ route('unpaidshipmentlist') }}" class="btn btn-default" type="reset">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

      
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
                                  <th width="10%">SR No</th>
                                  <th width="10%">Invoice No</th>
                                  <th width="10%">Invoice Date</th>
                                  <th width="10%">Invoice Month</th>
                                  <th width="20%">Company Name</th>
                                  <th width="20%">Forwarder Name</th>
                                  <th width="10%">Shipper Name</th>
                                
                                  <th width="10%">Invoice Amount</th>
                                  <th width="10%">Software Shipment Voucher No</th>
                                  <th width="30%" class="center">Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php $aa = 0; ?>
                              @foreach($data as $value)

                               <tr class="table_space">
                                  <td style="vertical-align: middle;"> <?php echo $aa = $aa+1; ?></td>
                                  <td style="vertical-align: middle;">{{ $value->invoice_no }}</td>
                                  <td style="vertical-align: middle;">{{ date('d-m-Y',strtotime($value->invoice_date)) }}</td>
                                  <td style="vertical-align: middle;">{{ date('M-y',strtotime($value->invoice_date)) }}</td>
                                  <td style="vertical-align: middle;">{{ $value->company_name }}</td>
                                  <td style="vertical-align: middle;">{{ $value->forwarder_name }}</td>
                                  <td style="vertical-align: middle;">{{ $value->shipper_name }}</td>
                                 
                                   <td style="vertical-align: middle;"><i class="fa fa-inr"></i>{{ number_format($value->grand_total,0) }}</td>
                                   <td style="vertical-align: middle;">{{$value->voucher_no}}</td>
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
      
      //  "aaSorting": [[ 7, "desc" ]],
      //  "columnDefs":
      //      [
      //          {
      //              "targets": [7],
      //              "visible": false, 
      //          },
      //      ],
      "lengthChange": true,
      "lengthMenu": [ 10, 25, 50, 75, 100 ],
        dom: 'Bfrtip',
        "columnDefs":
           [
               {
                   "targets": [6],
                   "visible": false, 
               },
           ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                }
            },
           
            
        ]
    } );
} );
</script>

@endsection