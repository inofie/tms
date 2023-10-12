@extends('layout.master')

@section('title')
All Paid Invoice List | TOT
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection


@section('content')
  
<section id="main-content">
          <section class="wrapper">
              <section class="panel">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="{{ route('paidshipmentlist') }}" >
                                      
                                   
                                    
                                    <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Forwarder :</label>
                                            <div class="col-lg-6">
                                                <select class="form-control" name="forwarder" id="forwarder" > 
                                                   <option value=""> -- Please Select Forwarder -- </option>
                                                      @foreach($all_forwarder as $value)
                                                      @if($forwarder == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="company_id" class="control-label col-lg-2">Company :</label>
                                            <div class="col-lg-6">
                                                <select class="form-control" name="company" id="company" > 
                                                   <option value=""> -- Please Select Company -- </option>
                                                      @foreach($all_company as $value)
                                                      @if($company == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Year :</label>
                                            <div class="col-lg-6">
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
                                            <div class="col-lg-6">
                                              <?php 
												// $all_month= ['1','2','3','4','5','6','7','8','9','10','11','12']; 
												//$all_month = range(1, 12);
												?>
                                                <select class="form-control" name="month" id="month" > 
                                                <option value=""> -- Please Select Month -- </option>
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



                                         <div class="form-group save_cancle">
                                            <div class="col-lg-10 center">
                                                <button class="btn btn-success" id="searchbtn"type="submit">Save</button>
                                                 
                                                <a  href="{{ route('paidshipmentlist') }}" class="btn btn-default" type="reset">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

      
              <section class="panel">
                  <header class="panel-heading">
                      Paid Invoice List
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                          <div class="clearfix">
                              <div class="btn-group pull-right">
                                 
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
                          {!! $html->table(['class' => 'table table-bordered', 'id' => 'InvoiceDataTable']) !!}
                      </div>
                  </div>
              </section>
              <!-- page end-->
          </section>
      </section>
      <!--main content end-->

@endsection

@section('js4')
{!! $html->scripts() !!}

<script type="text/javascript">
  $(document.body).on('click','#searchbtn', function() {
    var month=$('#month').val();
    var year=$('#year').val();
    var company=$('#company').val();
    var forwarder=$('#forwarder').val();
    $('#InvoiceDataTable').on('preXhr.dt', function ( e, settings, data ) {
      data.month = month;
      data.year = year;
        data.company = company;
      data.forwarder = forwarder;
    });
    window.LaravelDataTables["InvoiceDataTable"].draw();
  });

  function deleteItem(id){
    alert(1);
    var r = confirm("Are You Sure Delete It!");
      if (r == true) {
        $("#delete"+id).submit();  
      } 

    }

    
  $(document).ready(function() {
   
    $('#editable-sample').DataTable( {
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