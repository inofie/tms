
    <div class="col-lg-12">                    
    <section class="panel">
      <header class="panel-heading" style="line-height: 30px;">
         <label  style="padding: 0px 0.3%;">Account Data  </label>
        <div class="btn-group" style="padding: 0px 0.3%;">

        <form target="_blank" action="{{ route('accountspdf') }}" method="POST" id="pdfform">
          @csrf
          <input type="hidden" name="from" value="{{ $myfrom}}">
          <input type="hidden" name="to" value="{{ $myto}}">
          <input type="hidden" name="type" value="{{ $mytype}}">
          <input type="hidden" name="id" value="{{ $myid}}">
        </form>

          {{-- <a href="{{ route('downloadlr',['id'=>'6278c2959bb37']) }}"> --}}<button onclick="pdfbtn()" style="min-width: 20%; width: auto;" class="btn btn-primary"><b>PDF</b></button>{{-- </a> --}}</div>

        <div class="btn-group pull-right" style="padding: 0px 0.3%;"><button style="min-width: 20%; width: auto;" class="btn btn-danger "><b>Debit : </b><i class="fa fa-inr"></i> {{ number_format($dd,2) }}</button></div>
      <div class="btn-group pull-right" style="padding: 0px 0.3%;"><button style="min-width: 20%; width: auto;" class="btn btn-primary "><b>Credit : </b><i class="fa fa-inr"></i> {{ number_format($cc,2) }}</button></div>

      </header>
      <div class="panel-body">
            <div class="form">
              <div class="adv-table editable-table ">
                        
                          <table class="table table-striped table-hover table-bordered" id="editable-sample">
                              <thead>
                              <tr>
                                <td> Sr.No</td>
                                  <th class="text-center" style="width: 20%;">Date</th>
                                  <th class="text-center" style="width: 30%;">ForwarderName / TransporterName / CompanyName</th>
                                  <th class="text-center" style="width: 5%;">Invoice Number</th>
                                  <th class="text-center" style="width: 5%;">Forwarder Ref Number</th>
                                  <!-- <th class="text-center" style="width: 45%;">Detail</th> -->
                                  <th class="text-center" style="width: 5%;">V.Type</th>
                                  <th class="text-center" style="width: 5%;">V.No</th>
                                  <th class="text-center" style="width: 15%;">Credit</th>
                                  <th class="text-center" style="width: 15%;">Debit</th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php $aa = 0; ?>
                            @foreach($nyllist as $values)

                                
                                    <tr>
                                      <td> <?php echo $aa = $aa+1; ?></td>
                                      <td>{{ $values->datess }}</td>
                                      <td>{{ $values->name }}</td>
                                      <td>{{ $values->invoice_number }}</td>
                                      <td>{{ $values->forwarder_ref_no }}</td>
                                      <!-- <td>{{ $values->detailss }}</td> -->
                                      <td>{{ $values->v_type }}</td>
                                      <td class="text-center">{{ $values->id }}</td>
                                      <td style="text-align: right;">{{ $values->creditt }}</td>
                                      <td style="text-align: right;">{{ $values->debitst }}</td>
                                    </tr>
                                   
                                
                              @endforeach 

                          

                             </tbody>

                          </table>
                      </div>

            </div>
          </div>
    </section>
  </div>



