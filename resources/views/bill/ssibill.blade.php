<?php 
$number = $data->grand_total;
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    " & " . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  $word_amount =  $result . "Rupees Only";




?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>SSI Invoice - {{ $data->invoice_no }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

      .invoice-box {
        max-width: 800px;
        margin: auto;
        
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
      }

      .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
      }

      .invoice-box table td {
        padding: 3px;
        /*vertical-align: top;*/
      }

      .invoice-box table tr td:nth-child(2) {
        text-align: right;
      }

      .invoice-box table tr.top table td {
        padding-bottom: 20px;
      }

      .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
      }

      .invoice-box table tr.information table td {
        padding-bottom: 15px;
      }
      

      .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        border-collapse: collapse;
      }

      .invoice-box table tr.details td {
        padding-bottom: 0px;
        height: 17px;

      }

      .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
      }

      .invoice-box table tr.item.last td {
        border-bottom: none;
      }

      .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
      }

      @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
          width: 100%;
          display: block;
          text-align: center;
        }

        .invoice-box table tr.information table td {
          width: 100%;
          display: block;
          text-align: center;
        }
      }

      /** RTL **/
      .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
      }

      .rtl table {
        text-align: right;
      }

      .rtl table tr td:nth-child(2) {
        text-align: left;
      }
    </style>
  </head>

  <body>
   

@if($data->is_download != 1)
	  <div style="width: 100%; min-height:150px;border-collapse: collapse;">&nbsp;&nbsp;&nbsp;&nbsp;</div>
@endif 
    <div class="invoice-box">
		@if($data->is_download == 1)
      <table style="width: 100%">
        <tr>
          <td>
            <img style="width: 99%; text-align: center;" src="{{ asset('public/uploads/11y.jpg')}}">
          </td>
        </tr>
      </table>
	@endif
      <table style="border-collapse: collapse; margin-bottom: 0.5%">
        <tr class="details">
                  
                  <td style="border: 1px solid #000 ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding:3px 10px; line-height: 1.2; width: 20%;">To.</td>
                <td style="border: 1px solid #000 ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 10px; line-height: 1.2; width: 20%;">Invoice No.</td>
        
                  
        </tr>
        <tr>

<td style="text-align: left; border: 1px solid #000 ;  padding:3px 10px; width: 50%; font-size: 10px; font-weight: 600; line-height: 1.5; font-family:sans-serif;" >
                  <span style="font-size: 13px; font-weight: 700;"><strong>{{ $data->forwarder_name }}</strong></span><br>{{ $data->forwarder_address }}<br>
                GSTIN/UIN: <span >{{ $data->forwarder_gst }}</span><br>
               {{--  State Name:<span style="margin-left: 5%">Gujarat, Code : 24</span><br> --}}
                A/C: <span>@if($data->imports == 1) {{$data->consignee}} @else {{$data->consignor}} @endif  {{-- @if($data->consignee_address) <br> {{$data->consignee_address}}  @endif --}}</span>
                </td>
          <td style="border: 1px solid #000 ;text-align: center; font-size: 25px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 30%; font-weight: 700;">{{ $data->invoice_no }}</td></tr>

      </table>

      <table style="border-collapse: collapse; margin-bottom: 0.5%">

        <tr>

        
        <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 20%;">Other Reference(s). </td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2;padding: 5px; border-bottom: 1px solid #000;  width: 30%;" colspan="2"></td>
        <td style="border: 1px solid #000 ;text-align: left; font-size: 10px; line-height: 1.2; font-weight: 700;  padding: 5px; border-bottom: 1px solid #000;  width: 20%; background-color: #e6e6e6;">Dated.</td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000;  width: 30%;"  colspan="2">{{ date('d-m-Y',strtotime($data->invoice_date)) }}</td>

        </tr>

        <tr>

        <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Supplier's Ref.</td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 16.66%;"></td>
        <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Shipping No.</td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2;padding: 5px; border-bottom: 1px solid #000;  width: 16.66%;"></td>
        <td style="border: 1px solid #000 ;text-align: left; font-size: 10px; line-height: 1.2; font-weight: 700;  padding: 5px; border-bottom: 1px solid #000;  width: 16.66%; background-color: #e6e6e6;">Bill Of Entry.</td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000;  width: 16.66%;"></td>

        </tr>
        
        <tr>


        <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">From.</td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 16.66%;" colspan="2">{{ $data->from}}</td>
        
        <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">To.</td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 16.66%;" colspan="2">{{ $data->to}} </td>

        </tr>

        <tr>


        <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Mode/Terms of Payment.</td>
        <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 16.66%;" > 30 Days</td>
		<td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;" > Weight.</td>
		<td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 16.66%;" > @if($data->weight) {{ $data->weight}}KG @endif</td>
        
        <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Shipment Type.</td>
        
                <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 16.66%;" colspan="2">
                  <table style="width: 100%">
                    <tr>
                      <td style="width:10%;text-align: right;">
                          @if($data->lcl == 1)
						  	<input type="checkbox" checked="checked" name="vehicle1" >
						  @else 
						  	<input type="checkbox"   name="vehicle1" >
						  @endif
                      </td>
                      <td style="width:40%;text-align:left;">
                        LCL
                      </td>
                      <td style="width:10%;text-align: right;">
                      	@if($data->fcl == 1)
							<input type="checkbox" checked="checked" name="vehicle1" >
						@else 
							<input type="checkbox"   name="vehicle1" >
						@endif
                      </td>
                      <td style="width:40%;text-align:left;">
                        FCL
                      </td>
                       </tr>
                  </table>

        </td>
                  </tr>

      </table>

        <table style="border-collapse: collapse; margin-bottom: 0.5%; border: 1px solid #000 ;">

              <tr>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width:5%; line-height: 1.2; padding: 5px;">Sr. No.</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 24%; line-height: 1.2;  padding: 5px;">Particulars</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 10%; line-height: 1.2;  padding: 5px;">HSN/SAC</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 10.66%; line-height: 1.2;  padding: 5px;">Date</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 11.66%; line-height: 1.2;  padding: 5px;">Shipment No </td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 23.02%; line-height: 1.2;  padding: 5px;">Truck No</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 15.66%; line-height: 1.2;  padding: 5px;">Amount</td>

              </tr>



     

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">1</td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: left; font-size: 10px;   width:24%; line-height: 1.2; padding: 1px;" >Forwarder Ref No <?php /*echo  html_entity_decode($data->description);*/ if($data->forwarder_ref_no) {echo $data->forwarder_ref_no;} ?></td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:10%; line-height: 1.2; padding: 1px;" >996791</td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:10.66%; line-height: 1.2; padding: 1px;" >
				  @foreach($data->alldates as $key3 => $value3)
                          @if($key3 == 0)
                              {{ $value3}}
                          @else 
                              <br>{{ $value3}}
                          @endif

                    @endforeach
				  </td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:11.66%; line-height: 1.2; padding: 1px;" >
				  @foreach($data->shipment_list as $key => $value)
                          @if($key == 0)
                              {{ $value}}
                          @else 
                              <br>{{ $value}}
                          @endif

                    @endforeach
				  </td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:23.66%; line-height: 1.2; padding: 1px;" >
				  @foreach($data->trucklist as $key1 => $value1)
                          @if($key1 == 0)
                              {{ $value1}}
                          @else 
                              <br>{{ $value1}}
                          @endif

                    @endforeach
				  </td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: right; font-size: 10px;   width:15.66%; line-height: 1.2; padding: 1px;" > {{ number_format($data->sub_total,2) }}</td>

              </tr>

             {{--  <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;"></td>
              </tr> --}}
               <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">2</td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: left; font-size: 10px;   width:24%; line-height: 1.2; padding: 1px;" rowspan="3" >Extra</td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:10%; line-height: 1.2; padding: 1px;"rowspan="3" ></td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:10.66%; line-height: 1.2; padding: 1px;" rowspan="3"></td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:11.66%; line-height: 1.2; padding: 1px;" rowspan="3"> </td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:23.66%; line-height: 1.2; padding: 1px;" rowspan="3"></td>
                <td style=" vertical-align: baseline;border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: right; font-size: 10px;   width:15.66%; line-height: 1.2; padding: 1px;"rowspan="3" > {{ number_format($data->extra_amount,2) }}</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
              </tr>
              
             
               <tr>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: right; font-size: 10px; font-weight: 700;   width:84.34%; line-height: 1.2; padding: 3px;" colspan="6" >Total : </td>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: right; font-size: 10px; font-weight: 700;   width: 15.66%; line-height: 1.2;  padding: 3px;"><i class="fa fa-inr"></i> {{ number_format($data->grand_total,2) }}</td>
              </tr>
      </table>
      

        <table  style="border-collapse: collapse; margin-bottom: 0.5% ;">
          <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px; font-weight: 700;   width:39%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Amount Chargeable (in words)</td>

              <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px; font-weight: 700;   width:51%; line-height: 1.2; padding: 5px; border-right: none;">Indian Rupees {{ $word_amount }}</td>

               <td style=" border: 1px solid #000 ; border-left: none; text-align: center; font-size: 10px; font-weight: 700;   width:10%; line-height: 1.2; padding: 5px;">E. & O.E</td>

            
          </tr>
        
      </table>

      <table  style="border-collapse: collapse; margin-bottom: 0.5%;">
          <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700;   width:75.68%; line-height: 1.2; padding: 8px; background-color: #e6e6e6;">HSN/SAC</td>

              <td style=" border: 1px solid #000 ;  text-align: center; font-size: 12px; font-weight: 700;   width:24.32%; line-height: 1.2; padding: 8px;background-color: #e6e6e6; ">Taxable Value</td>
            
          </tr>

          <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;   width:75.68%; line-height: 1.2; padding: 5px; ">&nbsp;</td>

              <td style=" border: 1px solid #000 ;  text-align: center; font-size: 10px; font-weight: 700;   width:24.32%; line-height: 1.2; padding: 5px; "></td>
            
          </tr>

           <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: right; font-size: 10px; font-weight: 700;   width:75.68%; line-height: 1.2; padding: 5px; ">Total</td>

              <td style=" border: 1px solid #000 ;  text-align: center; font-size: 10px; font-weight: 700;   width:24.32%; line-height: 1.2; padding: 5px; "></td>
            
          </tr>
        
      </table>

      <table style="border-collapse: collapse; border: 1px solid #000; margin-bottom: 0.5%;" >
        <tr>
          
          <td style="text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  padding: 5px; line-height: 1.2; width: 50%; border:  1px solid #000;">TAX PAYABLE ON REVERSE CHARGES :<span style="font-weight: 700;"> YES</span></td>

        </tr>

      </table>


      <table style="border-collapse: collapse; border: 1px solid #000; margin-bottom: 0.5%;">
            <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: left; font-size: 12px; font-weight: 700;   width:37.84%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Container No.</td>
            <td style=" border: 1px solid #000; text-align: left; font-size: 13px; width:37.84%; line-height: 1.2; padding: 5px;">
				{{ $data->container }}
			</td> 
            <td style=" border-left: 1px solid #000; text-align: left; font-size: 13px; width:24.32%; line-height: 1.2; padding: 5px;" rowspan="3">
				@if($data->qr_code)
					<img src="{{ asset('public/Qr_code') }}/{{ $data->qr_code }}" width="100%">
				@endif
			</td>
            </tr>
            <tr>
               <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: left; font-size: 12px; font-weight: 700;   width:37.84%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Seal No.</td>
                
                <td style=" border: 1px solid #000; text-align: left; font-size: 13px; width:37.84%; line-height: 1.2; padding: 5px;">
				{{ $data->seal }}
			</td>
          <!---<td style=" border-left: 1px solid #000;text-align: center; font-size: 13px; width:24.32%; line-height: 2; padding: 5px;"></td> -->

            </tr>
            
            <tr>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: left; font-size: 12px; font-weight: 700;   width:37.84%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Shipping Line.</td>
                <td style=" border: 1px solid #000; text-align: left; font-size: 13px; width:37.84%; line-height: 1.2; padding: 5px;">
				{{ $data->shipping }}
			</td>
                <!--- <td style=" border-left: 1px solid #000;text-align: center; font-size: 13px; width:24.32%; line-height: 1.2; padding: 5px;">for SSI TRANSPORT</span></td> -->

            </tr>
      </table>


            <table style="border-collapse: collapse; border: 1px solid #000; margin-bottom: 0.5%;">
        <tr >
                <td style="text-align: left; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  padding: 5px; line-height: 1.2; width: 50%; border-bottom:  1px solid #000;">Remarks:</td>
                <td style=" text-align: left; font-size: 12px; font-weight: 700; background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 50%; border-bottom:  1px solid #000;">Company's Bank Details:</td>
                
              </tr>

         <tr >
                <td style="text-align: left; font-size: 10px; font-weight: 700; padding: 3px 5px; padding-top: 10px; line-height: 1.2; width: 50%;">Being Bill Issued against shipment</td>
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; padding-top: 10px; line-height: 1.2; width: 50%;">Bank Name :<span style="margin-left: 14.3%"> KADI NAGRIK BANK</span></td>
                
              </tr>

               <tr >
                <td style="text-align: left; font-size: 10px; font-weight: 700; padding: 3px 5px; line-height: 1.2; width: 50%;">Company's PAN
                  :<span style="margin-left: 5.3%">ARTPG4275F</span></td>
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; line-height: 1.2; width: 50%;">A/c No :<span style="margin-left: 20.3%"> 802004101000182</span></td>
                
              </tr>

              <tr >
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; line-height: 1.2; width: 50%; padding-bottom: 10px;">Subject:<span style="margin-left: 15.8%; "> Ahmedabad Junction</span></td>
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; line-height: 1.2; width: 50%; padding-bottom: 10px;">Branch & IFS Code :<span style="margin-left: 5.3% " > YESB0KNB003</span></td>
                
              </tr>



      </table>
      <table style="border-collapse: collapse;">
        <tr>
          <td style="text-align: center; font-size: 12px; line-height: 1.2; font-weight: 700; padding:5px; background-color: #477a2d; color: #fff;">Note:If Any Changes Please Inform us to within 6 days of invoice date.
</td>

        </tr>
      </table>

      


     
    </div>
  </body>
</html>