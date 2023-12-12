<?php
$number = abs($acc->credit);
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
  $word_amount =  strtoupper($result) . "ONLY";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Note</title>
    <style>
      .left{
        text-align : left;
      }
      .right{
        text-align : right;
      }
      .headerClass
      {
        font-size: 13px;
        font-weight: 400;
      }
      .item_tr
      {
        border:1px solid black;
      }
      .item_td
      {
        text-align: center;font-size: 13px;padding:3px;
        border:1px solid black;
      }
      .item_th
      {
        text-align: center;font-size: 15px;font-weight: 700;
        border:1px solid black;
      }
      table {
        border-collapse: collapse;
      }
    </style>
</head>
<body style="margin: 0px;">
  <div class="page">
    <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 100%;">
                <table style="width: 100%;padding-bottom: 25px;" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <!-- <td class="left">
                        <img src="{{ asset('public/uploads/bmfmail.jpg')}}" height="100px" alt="">
                        </td> -->
                      <td style="text-align:center;">
                        <b style="font-size:36 px;">Credit Note</b>
                      </td>
                    </tr>
                </table>
                <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="width: 60%;">
                      <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="left headerClass">
                              <span>&nbsp;</span><br/>
                              <span style="font-size: 13px;">{{ $data->forwarder_name }}</span>
                              <br>{{ $data->forwarder_address }}<br>
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass" style="padding-top : 30 px;">
                            <b>GSTIN/UIN : </b><span >{{ $data->forwarder_gst }}</span><br>
                            <b>A/C : </b><span>@if($data->imports == 1) {{$data->consignee}} @else {{$data->consignor}} @endif  {{-- @if($data->consignee_address) <br> {{$data->consignee_address}}  @endif --}}</span>
                            </td>
                        </tr>
                      </table>
                    </td>

                    <td style="width: 40%;">
                      <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="left headerClass">
                            <b>Invoice No : </b> <span>{{ $data->invoice_no }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                            <b>Date :</b> <span>{{ date('d-M-Y',strtotime($data->invoice_date)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                              <b>From :</b> <span>{{ $data->from}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                              <b>To :</b> <span>{{ $data->to}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                              <b>Truck No :</b>
                              @foreach($data->trucklist as $key1 => $value1)
                                @if($key1 == 0)
                                    <span>{{ $value1}}</span>
                                @else
                                    <span>{{ $value1}}</span>
                                @endif
                              @endforeach
                            </td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
                <br/><br/>
                <table style="width: 100%;padding-bottom:15px;">
                  <tr class="item_tr">
                    <th class="item_th">Quantity</th>
                    <th class="item_th">Description</th>
                    <th class="item_th">If Detention</th>
                    <th class="item_th">Amount (Rs.)</th>
                  </tr>
                  <tr class="item_tr">
                    <td class="item_td" style="height:200px;vertical-align:top">1</td>
                    <td class="item_td" style="height:200px;vertical-align:top"><?php echo  html_entity_decode($data->descriptions); ?></td>
                    <td class="item_td" style="height:200px;vertical-align:top"> {{ number_format($data->detention,2) }}</td>
                    <td class="item_td" style="height:200px;vertical-align:top">{{ number_format($acc->credit,2) }}</td>
                  </tr>
                  <tr class="item_tr">
                    <td colspan="2" rowspan="3"  class="item_td" style="text-align:right;vertical-align:top;">Reverse changes in tax payable apply</td>
                    <td class="item_td">Subtotal</td>
                    <td class="item_td"><b>{{ number_format($acc->credit,2) }}</b></td>
                  </tr>

                  <tr class="item_tr">
                    <td class="item_td">Other</td>
                    <td class="item_td">-</td>
                  </tr>

                  <tr class="item_tr">
                    <td class="item_td"><b>Total</b></td>
                    <td class="item_td"><b>{{ number_format($acc->credit,2) }}</b></td>
                  </tr>
                </table>

                <table style="width: 100%;padding-bottom: 15px;" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="width: 60%;">
                        <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                              <td class="left headerClass">
                              <b>Pan No :</b> CWYPP5053N
                              </td>
                          </tr>
                          <tr>
                              <td class="left headerClass">
                              <b>Address :</b> <br/>
                              <b>BRAHM MAHESH FREIGHT</b><br/>
                              Privilion Building,&nbsp;B Wing,&nbsp;14th Floor<br/>
                              Nr.Iscon Cross Road,&nbsp;Ahmedabad-380059
                              </td>
                          </tr>
                        </table>
                    </td>

                    <td style="width: 40%;">
                      <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="left headerClass">
                            <b>BANK DETAILS : </b>
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                            <b> Bank Name :</b> KOTAK BANK
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                            <b> Name :</b> BMF
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                            <b> A/C No :</b> [6945839606]
                            </td>
                        </tr>
                        <tr>
                            <td class="left headerClass">
                            <b> Ifsc code :</b> KKBK0000811
                            </td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
                <table style="width: 100%;padding-bottom: 35px;" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="text-align:center;">
                      <span style="font-size:10px;">*If any change in invoice tell us to max.7 days of invoice date.</span>
                    </td>
                  </tr>
                </table>

                <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="width:30%;padding-left: 70px;padding-right: 50px;">
                        <table>
                          <tr style="line-height: 0px;">
                            <td style="text-align:center;">
                              <img src="{{ asset('public/uploads/email.png')}}" height="15px" alt="">
                            </td>
                          </tr>
                          <tr style="line-height: 0px;">
                            <td style="text-align:center;padding-top: 15px;">
                              <span style="font-size:8px;">info@bmfreight.com</span>
                            </td>
                          </tr>
                        </table>
                    </td>
                    <td>
                    <img src="{{ asset('public/uploads/borderLine.png')}}" height="35px" alt="">
                    </td>
                    <td style="width:30%;padding-left: 70px;padding-right: 50px;">
                      <table>
                            <tr style="line-height: 0px;">
                              <td style="text-align:center;">
                                <img src="{{ asset('public/uploads/call.png')}}" height="15px" alt="">
                              </td>
                            </tr>
                            <tr style="line-height: 0px;">
                              <td style="text-align:center;padding-top: 15px;">
                                <span style="font-size:8px;">+91 7229043087</span>
                              </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                    <img src="{{ asset('public/uploads/borderLine.png')}}" height="35px" alt="">
                  </td>
                    <td style="width:40%;padding-left: 30px;padding-right: 40px;">
                      <table>
                            <tr>
                              <td style="text-align:center;">
                                <img src="{{ asset('public/uploads/location.jpg')}}" height="25px" alt="">
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align:center;padding-top: 10px;line-height: 8px;">
                                <span style="font-size:8px;">Privilion Building, B Wing, 14th Floor,<br/>
                              Nr.Iscon Cross Road,Ahmedabad-380059</span>
                              </td>
                            </tr>
                        </table>

                    </td>
                  </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>