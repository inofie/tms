<h2 style="text-align:center;">{{ $ff->name }}</h2>
<p style="text-align:center;">{{ $ff->address }}</p>
<p style="text-align:center;">Ledger Account Details</p>
<p style="text-align:center;">{{ $date_from }} &nbsp;&nbsp;To&nbsp;&nbsp; {{ $date_to }}</p>
<table style="border-collapse:collapse;" border="1" width="100%">	
		<thead>
			<tr style="border:2px solid #000;">
				<th>Sr.No</th>
				<th>Date</th>
				<th>Details</th>
				<th>V.Type</th>
				<th  style="text-align: right;">Credit</th>
				<th  style="text-align: right;">Debit</th>
			</tr>
		</thead>

	 		<?php $aa = 0; ?>
               @foreach($nyllist as $values)
               <tr style="border-bottom: 1px solid #000;">
                	<td style="text-align: center;"> <?php echo $aa = $aa+1; ?></td>
					<td>{{ $values->datess }}</td>
					<td>{{ $values->detailss }}</td>
					<td style="text-align: center;">{{ $values->type }}</td>
					<td style="text-align: right;">{{ $values->creditt }}</td>
					<td style="text-align: right;">{{ $values->debitst }}</td>
               </tr>
              @endforeach 
              <tr>
              	<td colspan="4" style="text-align:right;"><b>Total:</b></td>
              	<td style="text-align: right;"><b>{{ $cc }}</b></td>
              	<td style="text-align: right;"><b>{{ $dd }}</b></td>
              </tr>
</table>