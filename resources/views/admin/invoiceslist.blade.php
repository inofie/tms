<div class="adv-table" style="padding: 1%;">
							<table class="table table-striped table-hover table-bordered" >
								<tr>
								<th class="center">Select</th>
								<th class="center">Invoice No</th>
								<th class="center">Date</th>
								<th class="center" style="text-align: right;">Amount</th>
								</tr>
								<?php $aa = 0; ?>
								@if(count($data) > 0)

									@foreach($data as $value)
									<tr>
									<td class="center"><input type="checkbox" name="invoice[]" value="{{ $value->id }}" class="invoice" data-shipment_id="{{ $value->id }}"></td>
									<td class="center">{{ $value->invoice_no }}</td>
									<td class="center">{{ date('d-m-y',strtotime($value->invoice_date)) }}</td>
									<td class="center" style="text-align: right;">{{ $value->grand_total }}</td>
									<?php $aa = $aa+$value->grand_total; ?>
									</tr>
									@endforeach
									<tr>
										<td colspan="3" style="text-align: right;"> Total:</td>
										<td style="text-align: right;">{{ $aa }}</td>
									</tr>

								@else 

									<tr> <td class="center" colspan="5"> <b>Invoice are not available.</b> </td> </tr>

								@endif

								
							</table>
							</div>