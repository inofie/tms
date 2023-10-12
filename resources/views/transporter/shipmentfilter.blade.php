@extends('layout.master')

@section('title')
Shipment Filter | TMS
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
<!--main content start-->
   <section id="main-content">
          <section class="wrapper">
              <section class="panel">
                          <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="comform" method="get" action="{{ route('myfiltertransporter') }}" >
                                      
                                       <!-- <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Search :</label>
                                            <div class="col-lg-10">
                                              <input type="text" name="search" class="form-control" value="{{ $search }}">
                                            </div>
                                        </div> -->
                                        <!-- <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Shipment ID :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="shipment" id="shipment" > 
                                                   <option value=""> -- Please Select Shipment ID -- </option>
                                                      @foreach($tt as $value)
                                                      @if($ttt == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->shipment_no }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->shipment_no }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div> -->
                                        
                                      <!-- <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Transporter :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="transporter" id="transporter" > 
                                                   <option value=""> -- Please Select Transporter -- </option>
                                                      @foreach($all_transporter as $value)
                                                      @if($transporter == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div> -->
                                        <!-- <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Forwarder :</label>
                                            <div class="col-lg-10">
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
                                        </div> -->
                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Status :</label>
                                            <div class="col-lg-2">
                                            <?php 
												                    $all_year = ['Pending','Ontheway','Delivered'];
                                            ?>
                                                <select class="form-control" name="status" id="status" > 
                                                   <option value=""> -Please Select Status- </option>
                                                      @foreach($all_year as $value)
                                                      @if($tts == $value)
                                                      <option selected="selected" value="{{ $value }}">{{ $value }}</option>
                                                      @else
                                                      <option value="{{ $value }}">{{ $value }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Year :</label>
                                            <div class="col-lg-2">
                                              <?php 
												$all_year = ['2020','2021','2022','2023','2024','2025','2026','2027','2028','2029','2030','2031','2032','2033','2034','2035','2036','2037','2038','2039','2040'];
												//$all_year = range(2020, date('Y',strtotime('+2 year')));
												?>
                                                <select class="form-control" name="year" id="year" > 
                                                   <option value=""> - Please Select Year -</option>
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
                                                   <option value=""> - Please Select Month -</option>
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

                                        <!-- <div class="form-group ">
                                            <label for="company_ids" class="control-label col-lg-2">Date :</label>
                                            <div class="col-lg-2">
                                              <?php 
												$all_day = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31']; 
												//$all_day = range(1, 31);
												?>
                                                <select class="form-control" name="date" id="date" > 
                                                   <option value=""> -- Please Select Date -- </option>
                                                      @foreach($all_day as $value)
                                                      @if($date == $value)
                                                      <option selected="selected" value="{{ $value }}">{{ $value }}</option>
                                                      @else
                                                      <option value="{{ $value }}">{{ $value }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>
                                            </div>
                                        </div> -->

                                         <div class="form-group save_cancle">
                                            <div class="col-lg-10 center">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                 {{-- <button class="btn btn-default" type="reset">Reset</button> --}}
                                                <a  href="{{ route('myfiltertransporter') }}" class="btn btn-default" type="reset">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>



                        <section class="panel">
                  <header class="panel-heading">
                      Filter List
                  </header>
                  <div class="panel-body">
                      <div class="adv-table editable-table ">
                          
                          <table class="table table-striped table-hover table-bordered" id="editable-sample">
                              <thead>
                             <tr>
                                    <th>Ship.No.</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Consignor</th>
                                    <th>Consignee</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Status</th>
                                    <!-- <th>Action</th> -->
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
                                    
                                    <td style="vertical-align: middle;">{{ $value->consignee }}</td>
                                    
                                    <td style="vertical-align: middle;">{{ $value->from1 }}</td>
                                    
                                    <td style="vertical-align: middle;">{{ $value->to1 }}</td>
                                    
                                    <td id="{{ $value->shipment_no }}" style="vertical-align: middle;text-align: center;">
                                      @if($value->status == 1) 
                                        <span style="color: blue">Pending</span> 
                                        @elseif($value->status == 2 || $value->status == 4 || $value->status == 5 || $value->status == 6 ||$value->status == 7
							                        ||$value->status == 8 ||$value->status == 9 || $value->status == 10 || $value->status == 11 || $value->status == 12
							                        ||$value->status == 13 ||$value->status == 14 || $value->status == 15 || $value->status == 18) 
                                        <span style="color: orange">Ontheway</span>
                                      @elseif($value->status == 3 || $value->status == 17) 
                                        <span style="color: green">Delivered</span>
                                     
                                      @endif
                                    </td>
                                    
                                   
                                    
                                </tr>


                                @endforeach

                              </tbody>
                          </table>
                      </div>
                  </div>
              </section>
              
              
              

          </section>

          
      </section>
      <!--main content end-->
@endsection

@section('js4')


<script type="text/javascript">
  $(document).ready(function() {
    $('#editable-sample').DataTable( {
      //  "aaSorting": [[ 4, "desc" ]],
        "lengthChange": true,
      "lengthMenu": [ 10, 25, 50, 75, 100 ],
        dom: 'Bfrtip',
        buttons: [      
            'excelHtml5',
            'csvHtml5',     
        ]
    } );
} );
</script>
    
@endsection