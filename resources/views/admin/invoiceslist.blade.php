@section('css2')

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/jquery-multi-select/css/multi-select.css') }}" />
@endsection


@section('js0')

<script type="text/javascript" language="javascript" src="{{ asset('js/jquery.js')}}"></script>

@endsection
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
<div class="adv-table" style="padding: 1%;">
							<table class="table table-striped table-hover table-bordered" >
								<tr>
								<th class="center">Select</th>
								<th class="center">Invoice No</th>
								<th class="center">Date</th>
								<th class="center">Total Amount</th>
                <th class="center">Unpaid Amount</th>
								<th class="center">TDS</th>
								<th class="center">Half Pay</th>
								</tr>
								<?php $aa = 0; ?>
								@if(count($data) > 0)

									@foreach($data as $value)
									<tr>
									<td class="center"><input type="checkbox" name="invoice[]" value="{{ $value->id }}" class="invoice" data-shipment_id="{{ $value->id }}"></td>
									
									<td class="center">{{ $value->invoice_no }}</td>
									<td class="center">{{ date('d-m-y',strtotime($value->invoice_date)) }}</td>
									<td class="center" style="text-align: right;">{{ $value->grand_total }}</td>
                  @if($value->remaining_amount == null)
                  <td class="center" style="text-align: right;">{{  $value->grand_total }}</td>
                  @else
                  <td class="center" style="text-align: right;">{{ $value->remaining_amount }}</td>
                  @endif
									<td class="text-center"><button type="button" class="open-button" id="invoice2" onclick="openForm()">Click Here</button> </td>
									<!-- <td class="center"><input type="checkbox" name="invoice2[]" value="{{ $value->id }}" class="invoice2" data-shipment_id="{{ $value->id }}"></td> -->
									<td class="center"><input type="checkbox" name="invoice3[]" value="{{ $value->id }}" class="invoice3" data-shipment_id="{{ $value->id }}"></td>
									<?php $aa = $aa+$value->grand_total; ?>
									</tr>
									@endforeach
									<tr>
										<td colspan="5" style="text-align: right;"> Total:</td>
										<td style="text-align: right;">{{ $aa }}</td>
									</tr>

								@else 

									<tr> <td class="center" colspan="5"> <b>Invoice are not available.</b> </td> </tr>

								@endif

								
							</table>
				<div class="form-group" id="myForm" style="display:none">
				
                <label class="col-lg-2 control-label">TDS Amount :</label>
                <div class="col-lg-10">
                <input type="text" name="tds_amount" value="{{ old('tds_amount') }}" class="form-control" id="tds_amount" placeholder="Tds amount"/>
				@error('tds_amount')
                <span class="text-danger"> {{ $message }} </span>
                @enderror
			
			</div>
				
                
				<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
				
				</div>
							</div>
	
				
@section('js3')

<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
@endsection

@section('js4')
<script src="{{ asset('js/advanced-form-components.js')}}"></script>
<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
  if(tds_amount == "" ){
    return false;
  }
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>

<script>
  $('#invoice2').click(function(){

    var tds_amount = $('#tds_amount').val();
    document.getElementById("tds_amount").required = true;
    // if(tds_amount == "" ){
    //   alert('Please select Tds amount');
    //   return false;
    // }
  });

</script>
