
    <div class="col-lg-12">                    
    <section class="panel">
       <header class="panel-heading" style="line-height: 30px;">
         <label  style="padding: 0px 0.3%;">Credit List  </label>
        <div class="btn-group" style="padding: 0px 0.3%;">

        <form target="_blank" action="{{ route('accountspdf') }}" method="POST" id="pdfform">
          @csrf
          <input type="hidden" name="from" value="{{ $myfrom}}">
          <input type="hidden" name="to" value="{{ $myto}}">
          
        </form></div>

       <!--  <div class="btn-group pull-right" style="padding: 0px 0.3%;"><button style="min-width: 20%; width: auto;" class="btn btn-danger "><b>Debit : </b><i class="fa fa-inr"></i> {{ number_format($dd,2) }}</button></div>
      <div class="btn-group pull-right" style="padding: 0px 0.3%;"><button style="min-width: 20%; width: auto;" class="btn btn-primary "><b>Credit : </b><i class="fa fa-inr"></i> {{ number_format($cc,2) }}</button></div>
 --> 
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
                                  <th class="center" style="width: 20%;">Date</th>
                                  <th class="center" style="width: 45%;">Detail</th>
                                  <th class="center" style="width: 5%;">V.Type</th>
                                  <th class="center" style="width: 15%;">Credit</th>
                                  <th class="center" style="width: 15%;">Debit</th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php $aa = 0; ?>
                            @foreach($nyllist as $values)

                                
                                    <tr>
                                      <td> <?php echo $aa = $aa+1; ?></td>
                                      <td>{{ $values->datess }}</td>
                                      <td>{{ $values->detailss }}</td>
                                      <td>{{ $values->type }}</td>
                                      <td style="text-align: right;">{{ $values->creditt }}</td>
                                      <td style="text-align: right;">{{ $values->debitst }}</td>
                                    </tr>
                                   
                                
                              @endforeach 

                             {{--   <tr class="table_space"> 
                                <td style="border: 0;"></td>
                                  <td style="border: 0;"></td>
                                  <td style="border: 0;"></td>
                                  <td style="text-align: right;"> <b>Total</b></td>
                                  <td  style="vertical-align: middle;text-align: right;"><b>{{ number_format($cc,2) }}</b></td>
                                  <td  style="vertical-align: middle;text-align: right;"><b>{{ number_format($dd,2) }}</b></td>
                                  
                               </tr>  --}}

                             </tbody>

                          </table>
                      </div>

            </div>
          </div>
    </section>
  </div>



