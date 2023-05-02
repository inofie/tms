<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS LR</title>

    <style>

      .invoice-box {
        /*max-width: 800px; */
        max-width: 100%; 
        margin: auto;
        
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        /*line-height: 24px;*/
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
    <div class="invoice-box">
      <table style="width: 100%">
        <tr>
          <td>
            <img style="width: 100%;" src="{{ asset('public/uploads/11y.jpg')}}">
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <td style="width: 50%">
             <table>
                <tr>
                  <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px">Consigner. : <span>{{ $data->consignor }}</span></td>
                 
                </tr>
                <tr>
                  <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px;  padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px" >Address :</strong><br>{{ $data->consignor_address }}</td>
                </tr>
                <tr>
                  <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px">Consignee. : <span>{{ $data->consignee }}</span></td>
                </tr>

                 <tr>
          <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px;  padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px" >Address :</strong><br>{{ $data->consignee_address }}</td>
               </table>
          </td>
          <td style="width: 50%">
              <table>
                  <tr>
                    
                    <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px">Invoice No.</td>
                
                            <td style="border: 1px solid #dedede ;text-align: center; font-size: 12px; line-height: 1.2; padding: 5px 8px; width: 30%; border-radius: 5px">{{$data->lr_no}}</td>

                  </tr>
                  
                  <tr>
                    <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px">Date.</td>
                 
                            <td style="border: 1px solid #dedede ;text-align: center; font-size: 12px; line-height: 1.2; padding: 5px 8px; width: 30%; border-radius: 5px">{{ date('d-M-Y',strtotime($data->date)) }}</td>
                  </tr>
                   <tr> 
                    <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px">From.</td>
                  <td style="border: 1px solid #dedede ;text-align: center; font-size: 12px; line-height: 1.2; padding: 5px 8px; width: 30%; border-radius: 5px">{{ $data->from1 }}</td>
                   </tr>
                  <tr>
                    <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px">To.</td>
                  <td style="border: 1px solid #dedede ;text-align: center; font-size: 12px; line-height: 1.2; padding: 5px 8px; width: 30%; border-radius: 5px">{{ $data->to1 }}</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px">Type.</td>
                  
                            <td style="border: 1px solid #dedede ;text-align: center; font-size: 12px; line-height: 1.2; padding: 5px 8px; width: 30%; border-radius: 5px">@if($data->imports == 1) Import @else Export @endif</td>
                  </tr>
                  
                
                </table>
          </td>
        </tr>
      </table>

      <table>
        <tr>
          <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 15%; border-radius: 5px">Truck No.</td>

          <td style="border: 1px solid #dedede ; font-size: 12px; text-align: left;  padding: 5px 8px; line-height: 1.2; width: 85%; border-radius: 5px" >{{ $data->truck_no }}</td>       
        </tr>
      </table>

      

 <table>

              <tr>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width:8%; line-height: 1.2; padding: 5px; border-radius: 5px;">No.of Articals.</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 25%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Description</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 14%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Actual Weight</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 14%; line-height: 1.2;  padding: 5px;border-radius: 5px;">Weight<br>Charged M.T.</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 14%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Rate per M.T.</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 25%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Fright</td>
              </tr>

              <tr style="height: 150px;vertical-align: baseline;">
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width:8%; line-height: 1.2; padding: 5px; border-radius: 5px; min-height: 200px">{{ $data->package }}</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width: 25%; line-height: 1.2;  padding: 5px; border-radius: 5px; min-height: 200px"><?php echo  html_entity_decode($data->description); ?></td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width: 14%; line-height: 1.2;  padding: 5px; border-radius: 5px; min-height: 200px">{{ $data->weight }}KG</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width: 14%; line-height: 1.2;  padding: 5px;border-radius: 5px; min-height: 200px">&nbsp;</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width: 14%; line-height: 1.2;  padding: 5px; border-radius: 5px; min-height: 200px">&nbsp;</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width: 25%; line-height: 1.2;  padding: 5px; border-radius: 5px; min-height: 200px">&nbsp;</td>
              </tr>

              <tr>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width:8%; line-height: 1.2; padding: 5px; border-radius: 5px;">Total. :</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; width: 39%; line-height: 1.2;  padding: 5px; border-radius: 5px;">{{ $data->package }} Package Only</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; width: 53%; line-height: 1.2;  padding: 5px;border-radius: 5px; background-color: #e6e6e6;" colspan="4"><b>GST / EWAY BILL ID : {{ $data->gst }}</b></td>
                
              </tr>
       </table>

       <table>
           <tr>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; width: 33.33%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Ser. Tax . : -<td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; width: 33.33%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Surcharge. : -</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; width: 33.33%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Total. : -</td>

           </tr>
       </table>

       <table>
           <tr>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 10%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Consignee:</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">&nbsp;</td>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 10%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Consignee:</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">&nbsp;</td>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Truck Type:</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;"> {{ $data->trucktype_name }}</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 20%; line-height: 1.2;  padding: 5px; border-radius: 5px;" rowspan="3">
			   		@if($data->qr_code)
					<img src="{{ asset('public/Qr_code') }}/{{ $data->qr_code }}" width="100%">
				@endif
			   </td>

           </tr>
           <tr>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 10%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Private Mark: </td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">&nbsp;</td>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 10%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Invoice No: </td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">{{ $data->shipper_invoice }}</td>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;"> Driver Name:</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">{{ $data->driver_name }}</td>
               

           </tr>
           <tr>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 10%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Enclosures: </td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">&nbsp;</td>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 10%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">B / E:</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">{{ $data->b_e_no }}</td>
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Licence No:</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 15%; line-height: 1.2;  padding: 5px; border-radius: 5px;">{{ $data->licence_no }}</td>
              <!-- <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 20%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Authorised SSI Transport</td> -->

           </tr>
       </table>
    <p style="padding: 0px;margin:1% 0px 0px 0px;font-size: 12px; margin-bottom: 5px;"> N.B. (1.)This G.C. Note Issued Under Terms &amp; Condition Printed Overleaf. (2.)We are not Responsible For Leakages.Breakages DamagesCondition Goods Accepted On Owner Risk. (3.)Subject to Ahmedabad Jurisdiction<br><br>
                     <b >Terms and Condition :</b><br>
               
              :1.) Condition contents and value of consignment are acknowledged to ssi transway(nereinafer called the company).The company carries the goods as packed of owners of consignor's risk.
2.)The company does not guarantee delivery within any specified time and the CO. shall not be label for any delay in transport of delivery. 3.)The co.shall not be liable for any loss or damage due to wheather conditions strikes,riotes
disturbance.Fire explosion or accidents. 4.)Delivery should be takenn from our godown for consignment less than a lorry load likewisw for collection of goods. 5.)The consignor is responsible for all consequences of and incorrecrt of
falls declaration. 6.)Delivery of goods should be taken from company's godown within days of their arrival falling which a godown rent at rates in force will be charged. The consigning of consignment shall ascreiain the time and date
arrival. 7.)The consignee copy shall be surrendered duty discharged at the time of taking delivery and once the goods are delivered the company is absolved from all liability. 8.)The company had the right tore weight re measure and re
calculate the rates before delivery and of collection any commision or undercharges. 9.)The company reserved the right to refuse goods for transport without assigning any reason. 10.)The company shall have right to dispose of
perishable lying indelivered after 48 hrs. of arrival without any notice any other goods after 30day of arrival after due notice to the consignee and the claimants shall be entertained to the proceeds fright &amp; demurrage. 11.)The company
shall not be responsible if the goods are detained sized or confiscated by govt. 12.)The consignor shall primary be lible to pay the transport charged and all other incidental charges at the head office of the company. 13.)The company
shall have right to interest the goods for transport to any other lorry of service for trnsport and the consignment here in shall apply even such case. 14.)No enquiry will be entertained relating to any consignment after the expired of
30days from date of delivery. 15.)Where a bank is intersted in the goods coverd by this noted to extend of either advance mailed by it against plodes of the not company declare and agrees that company shalles heliiinseff liable to pay
the bank full extent to the bankers interest under the security for lost damage to the good rising from and cause whatsoever if the consigner shall sand the consignee copy of the GC note the consignee by registered post with
acknowledgment due to safeguard against miscrrange the company shall not be liable for any claim be the consigner or the consignee for non delivery or wrong delivery as long as delivery made by the company against the consignee
copy of the GC of not any person who products the same 20 incase of dispute jurisdiction of court of ahmedabad.</p> </div>
  </body>
</html>