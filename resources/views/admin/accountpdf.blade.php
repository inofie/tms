<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Account Data</title>
</head>
<body style="width: 100%;">
<h3 style="text-align: center;">
	{{ $maindata->name }}
	@if($mytype == "company" || $mytype == "")
<br><span style="font-size:16px">{{ $maindata->address }}</span>
@endif
<br><span style="font-size:16px">Contact Number:{{ $maindata->phone }}</span>
</h3>


<h4 style="text-align:center;"> From: {{ date('d-m-Y', strtotime($myfrom)) }} &nbsp;&nbsp; To: {{date('d-m-Y', strtotime($myto)) }}</h4>
<table width="100%" style="padding: 0% 2%;" style="text-align: center;" >
	<thead style="text-align:center;background: #000;color: #fff;">
		<th style="text-align:center;">Date</th>
		<th class="text-align:center;">ForwarderName / TransporterName / CompanyName</th>
        <th class="text-align:center;">Invoice Number</th>
    	<th class="text-align:center;">Forwarder Ref Number</th>
		<!-- <th style="text-align:center;">Detail</th> -->
		<th style="text-align:center;">V.Type</th>
		<th style="text-align:center;">V.No</th>
		<th style="text-align:center;">Credit</th>
		<th style="text-align:center;">Debit</th>
	</thead>
	<tbody>
          @foreach($nyllist as $key => $values)
			 <tr>
               <td style="text-align:center;">{{ date('d-m-y',strtotime($values->datess)) }}</td>
			   <td style="text-align:center;">{{ $values->name }}</td>
            	<td style="text-align:center;">{{ $values->invoice_number }}</td>
                <td style="text-align:center;">{{ $values->forwarder_ref_no }}</td>
               <!-- <td style="text-align:left;">{{ $values->detailss }}</td> -->
               <td style="text-align:center;">{{ $values->type }}</td>
               <td style="text-align:center;">{{ $values->id }}</td>
               <td style="text-align: right;">{{ $values->creditt }}</td>
               <td style="text-align: right;">{{ $values->debitst }}</td>
              </tr>
         @endforeach 
         <tr style="border: 1px solid #000;" >
               <td colspan="6" style="text-align:right;border-top: 1px solid #000;"></td>
              
               <td style="text-align:right;border-top: 1px solid #000;">{{ $dd }}</td>
			   <td style="text-align:right;border-top: 1px solid #000;">{{ $cc }}</td>
              </tr>	

           <tr >
               <td style="text-align:center;"></td>
               <td style="text-align:left;">By: Clearing</td>
               <td style="text-align:center;"></td>
               <td style="text-align:center;"></td>
			   <td style="text-align:center;"></td>
               <!-- <td style="text-align:center;"></td> -->
               <td style="text-align: right;">@if($cc<$dd) {{ $dd-$cc }} @endif</td>
               <td style="text-align: right;">@if($cc>$dd) {{ $cc-$dd }} @endif</td>
              </tr>

	</tbody>
	<tfoot style="text-align:center;background: #000;color: #fff;">
		
			<th colspan="6" style="text-align:right;">Total:</th>
				<th style="text-align:right;">@if($cc<$dd) {{ $dd }} @endif @if($cc>$dd) {{ $cc }} @endif</th>
			<th style="text-align:right;">@if($cc<$dd) {{ $dd }} @endif @if($cc>$dd) {{ $cc }} @endif</th>
		
	</tfoot>
</table>
@if($qr_code)
	<table width="100%" style="padding: 0% 2%;" style="text-align: right;" >
         <tr style="border: 1px solid #000;" >
			<td style=" width: 100%; text-align: right;"><img src="{{ asset('public/Qr_code') }}/{{ $qr_code }}" width="75px"></td>
		</tr>	
		//qr_code
	</table>
@endif
</body>
</html>