
    <div class="col-lg-12">                    
    <section class="panel">
      <header class="panel-heading" style="line-height: 30px;">Invoice List
      </header>
      <div class="panel-body">
            <div class="form">
              <div class="adv-table editable-table ">
                        
                          <table class="table table-striped table-hover table-bordered" id="editable-sample">
                              <thead>
                              <tr>
                                <td> Sr.No</td>
                                  <th class="center" style="width: 10%;">Invoice No</th>
                                  <th class="center" style="width: 10%;">Invoice Date</th>
                                  <th class="center" style="width: 25%;">Company</th>
                                  <th class="center" style="width: 15%;">Total Amount</th>
                                  <th class="center" style="width: 15%;">Status</th>
                                  <th class="center" style="width: 35%;">Action</th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php $aa = 0; ?>
                            @foreach($all_data as $values)
                                    <tr>
                                      <td> <?php echo $aa = $aa+1; ?></td>
                                      <td>{{ $values->invoice_no }}</td>
                                      <td>{{ date('d-m-Y',strtotime($values->invoice_date)) }}</td>
                                      <td>{{ $values->company }}</td>
                                      <td style="text-align: right;">{{ $values->grand_total }}</td>
                                      <td class="center">
                                      @if($values->status == 1)
                                      <b style="color:green;">Paid</b>
                                      @else
                                      <b style="color:red;">Unpaid</b>
                                      @endif
                                      </td>
                                      <td style="text-align: center;">
                                        <a href="{{ route('f-invoices-download',['id'=>$values->myid]) }}" style="margin-top: 2%;width: auto; margin:1%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn expense "><i class="fa fa-download "></i> Download</a>
                                          <a target="_blank" style="margin-top: 2%;width: auto; margin:1%;" href="{{ route('f-invoices-view',['id'=>$values->myid]) }}" class="btn btn-warning "><i class="fa fa-eye"></i> View</a>
                                      </td>
                                    </tr>
                                   
                                
                              @endforeach 

                             </tbody>

                          </table>
                      </div>

            </div>
          </div>
    </section>
  </div>



