<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>BMF LR | TMS</title>

    <style>

      .invoice-box {
        max-width: 800px;
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
            <img style="width: 100%;" src="{{ asset('uploads/33y.jpg')}}">
          </td>
        </tr>
      </table>



      <table>
        <tr>
         <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 13.33%; border-radius: 5px">Date.</td>
         <td style="border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px; width: 13.33%; border-radius: 5px">{{ date('d-M-Y',strtotime($data->date)) }}</td>
         <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 13.33%; border-radius: 5px">De-stuffing Date:</td>
         <td style="border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px; width: 13.33%; border-radius: 5px">{{ date('d-M-Y',strtotime($data->destuffing_date)) }}</td>
         <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 13.33%; border-radius: 5px">L.R. no.</td>
         <td style="border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px; width: 13.33%; border-radius: 5px">{{$data->lr_no}}</td>
         <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px" colspan="2">Invoice No.</td>
        </tr>
        <tr>
         <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 13.33%; border-radius: 5px">from.</td>
         <td style="border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px; width: 13.33%; border-radius: 5px">{{ $data->from1 }}</td>
         <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 13.33%; border-radius: 5px">To.</td>
         <td style="border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px; width: 13.33%; border-radius: 5px">{{ $data->to1 }}</td>
         <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 13.33%; border-radius: 5px">To.</td>
         <td style="border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px; width: 13.33%; border-radius: 5px">{{ $data->to2 }}</td>
         <td style="border: 1px solid #dedede ; text-align: center; font-size: 15px; font-weight: 700;  padding: 5px 8px; line-height: 1.2; width: 20%; border-radius: 5px"></td>
        </tr>
      </table>

      <table>
        <tr>
         <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; padding: 5px 8px; line-height: 1.2; width: 15%; border-radius: 5px">Truck No.</td>
          <td style="border: 1px solid #dedede ; text-align: left; font-size: 12px;  padding: 5px 8px; line-height: 1.2; width: 85%; border-radius: 5px">{{ $data->truck_no }}</td>
        </tr>
      </table>

      <table style=" margin-bottom: 0.5%; border: 1px solid #dedede ; border-radius: 5px;">
            <tr>
                <td style=" text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; line-height: 1.2; width: 50%; padding: 3px 3px;">Consigner. : <span>{{ $data->consignor }}</span></td>
                <td style=" text-align: left; font-size: 12px; font-weight: 700;  background-color: #e6e6e6; line-height: 1.2; width: 50%; padding: 3px 3px;">Consignee. : <span>{{ $data->consignee }}</span></td>
            </tr>    

            <tr>
                <td style=" text-align: left; font-size: 10px; padding: 5px 8px; line-height: 1.2; width: 50%; border-radius: 5px"><strong style="font-size: 12px">Address :</strong><br>{{ $data->consignor_address }}</td>
                <td style=" text-align: left; font-size: 10px; padding: 5px 8px; line-height: 1.2; width: 50%; border-radius: 5px"><strong style="font-size: 12px">Address :</strong><br>{{ $data->consignee_address }}</td>
            </tr>

      </table>

      <table>

              <tr>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width:8%; line-height: 1.2; padding: 5px; border-radius: 5px;">No.of Articals.</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 47%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Description</td>
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 20%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Actual Weight</td>
                
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 25%; line-height: 1.2;  padding: 5px; border-radius: 5px;">Fright</td>
              </tr>

              <tr style="height: 150px;vertical-align: baseline;">
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width:8%; line-height: 1.2; padding: 5px; border-radius: 5px;">{{ $data->container_type }}</td> {{-- $data->package --}}
                <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; width: 47%; line-height: 1.2;  padding: 5px; border-radius: 5px;">
                               <table>
                                 <tr>
                                   <td style="width: 40%; border: 1px solid #dedede ;text-align: left; font-size: 10px; line-height: 1.2; padding: 5px 8px; background-color: #e6e6e6; font-weight: 700;    ">Import Or Export.</td>
                                   <td style="width: 60%; border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px;  ">@if($data->imports == 1) IMPORT @else EXPORT @endif</td>
                                 </tr>
                                 <tr>
                                   <td style="width: 40%; border: 1px solid #dedede ;text-align: left; font-size: 10px; line-height: 1.2; padding: 5px 8px; background-color: #e6e6e6; font-weight: 700;   ">Cont. No./Cargo.</td>
                                   <td style="width: 60%; border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px;  ">{{ $data->container_no }}</td>
                                 </tr>
                                 <tr>
                                   <td style="width: 40%; border: 1px solid #dedede ;text-align: left; font-size: 10px; line-height: 1.2; padding: 5px 8px; background-color: #e6e6e6; font-weight: 700;   ">Shipping Line.</td>
                                   <td style="width: 60%; border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px;  ">{{ $data->shipping_line }}</td>
                                 </tr>
                                 <tr>
                                   <td style="width: 40%; border: 1px solid #dedede ;text-align: left; font-size: 10px; line-height: 1.2; padding: 5px 8px; background-color: #e6e6e6; font-weight: 700;   ">CHA .</td>
                                   <td style="width: 60%; border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px;  ">{{ $data->cha }}</td>
                                 </tr>
                                 <tr>
                                   <td style="width: 40%; border: 1px solid #dedede ;text-align: left; font-size: 10px; line-height: 1.2; padding: 5px 8px; background-color: #e6e6e6; font-weight: 700;    ">Seal No.</td>
                                   <td style="width: 60%; border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px;  ">{{ $data->seal_no }}</td>
                                 </tr>
                                 <tr>
                                   <td style="width: 40%; border: 1px solid #dedede ;text-align: left; font-size: 10px; line-height: 1.2; padding: 5px 8px; background-color: #e6e6e6;  font-weight: 700;    ">POD.</td>
                                   <td style="width: 60%;border: 1px solid #dedede ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px 8px;  ">{{ $data->pod }}</td>
                                 </tr>



                               </table>
                </td>
                <td td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; width: 20%; line-height: 1.2;  padding: 5px; border-radius: 5px;">FIXED</td>
               
                <td style="border: 1px solid #dedede ; text-align: center; font-size:10px; width: 25%; line-height: 1.2;  padding: 5px; border-radius: 5px;">TO BE FILLED</td>
              </tr>

              
       </table>

       <table>

        <tr>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; width: 100%; line-height: 1.2; padding: 5px;border-radius: 5px; background-color: #e6e6e6;"><b>FACTORY REPORTING</b></td>
        </tr>
      </table>
      <table>
        <tr>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; background-color: #e6e6e6;">Arrival At</td>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; ">&nbsp;</td>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; background-color: #e6e6e6;">Dt.</td>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; ">&nbsp;</td>
        </tr>
        <tr>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; background-color: #e6e6e6;">Enclosures</td>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; ">&nbsp;</td>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; background-color: #e6e6e6;">Dt.</td>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 25%; line-height: 1.2; padding: 5px;border-radius: 5px; ">&nbsp;</td>
        </tr>
      </table>

         <table>
          
           
           <tr>
            <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 16.67%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">F.O.B Rs. </td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 16.67%; line-height: 1.2;  padding: 5px; border-radius: 5px;"></td>
               
               <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 16.67%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">To Pay Rs.</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 16.67%; line-height: 1.2;  padding: 5px; border-radius: 5px;"></td>
               
                <td style="border: 1px solid #dedede ; text-align: left; font-size: 10px; font-weight: 700; width: 16.67%; line-height: 1.2;  padding: 5px; border-radius: 5px; background-color: #e6e6e6;">Sig. of Booking Clerk.</td>
               <td style="border: 1px solid #dedede ; text-align: center; font-size: 10px; font-weight: 700; width: 16.67%; line-height: 1.2;  padding: 5px; border-radius: 5px;"></td>
               
               

           </tr>
           
       </table>
       <table>

        <tr>
          <td style="border: 1px solid #dedede ; text-align: center; font-size: 12px; font-weight: 700; width: 100%; line-height: 1.2; padding: 5px;border-radius: 5px; background-color: #e6e6e6;"><b>GST/EWAY BILL ID : 24AACFY4389E1Z0
</b></td>
        </tr>
      </table>



       <table style="text-align: left;">
  <tr>
        <td style=" text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width:5%; line-height: 1; padding: 5px; border-radius: 5px;" rowspan="3">N.B.</td>
        <td style=" text-align: left; font-size: 10px; font-weight: 700; width: 82%; line-height: 1;  padding: 5px; border-radius: 5px;"> • This G.C. Note Issued Under Terms &amp; Condition Printed Overleaf.</td>
	  @if($data->qr_code)
					<td style="width:13%; line-height: 1;" rowspan="3"><img src="{{ asset('Qr_code') }}/{{ $data->qr_code }}" width="100%"></td>
				@endif
        </tr>
        <tr>
          <td style=" text-align: left; font-size: 10px; font-weight: 700; width: 82%; line-height: 1;  padding: 5px; border-radius: 5px;">• We are not Responsible For Leakages.Breakages DamagesCondition Goods Accepted On Owner Risk.</td>
        </tr>
        <tr>
          <td style=" text-align: left; font-size: 10px; font-weight: 700; width: 82%; line-height: 1;  padding: 5px; border-radius: 5px;">• Subject to Ahmedabad Jurisdiction</td>
        </tr>
        </table>
      

<div  style="width: 100%;">

        
       
 <p  style="padding: 0px; margin:0% 0px 0px 0px; font-size: 12px; margin-bottom: 5px; font-weight: 700" >Terms and Condition :</p>
  

  <div style="width: 50%; float: left;">
    
    <p style="padding: 0px; margin:0% 0px 0px 0px;font-size: 8px; margin-bottom: 5px; text-align: justify;padding: 1%; ">• Condition contents and value of consignment are acknowledged to bmf transport(nereinafer called the company).The company carries the goods as packed of owners of consignor's risk.
• The company does not guarantee delivery within any specified time and the CO. shall not be label for any delay in transport of delivery. • The co.shall not be liable for any loss or damage due to wheather conditions strikes,riotes
disturbance.Fire explosion or accidents. • Delivery should be takenn from our godown for consignment less than a lorry load likewisw for collection of goods. • The consignor is responsible for all consequences of and incorrecrt of
falls declaration. • Delivery of goods should be taken from company's godown within days of their arrival falling which a godown rent at rates in force will be charged. The consigning of consignment shall ascreiain the time and date
arrival. • The consignee copy shall be surrendered duty discharged at the time of taking delivery and once the goods are delivered the company is absolved from all liability. • The company had the right tore weight re measure and re
calculate the rates before delivery and of collection any commision or undercharges. • The company reserved the right to refuse goods for transport without assigning any reason. • The company shall have right to dispose of
perishable lying indelivered after 48 hrs. of arrival without any notice any other goods after 30day of arrival after due notice to </p>
  </div>


  <div style="width: 50%; float: left;">
    <p style="padding: 0px; margin:1% 0px 0px 0px;font-size: 8px; margin-bottom: 5px; text-align: justify; padding: 1%;">the consignee and the claimants shall be entertained to the proceeds fright &amp; demurrage. • The company
shall not be responsible if the goods are detained sized or confiscated by govt. • The consignor shall primary be lible to pay the transport charged and all other incidental charges at the head office of the company. • The company
shall have right to interest the goods for transport to any other lorry of service for trnsport and the consignment here in shall apply even such case. • No enquiry will be entertained relating to any consignment after the expired of
30days from date of delivery. • Where a bank is intersted in the goods coverd by this noted to extend of either advance mailed by it against plodes of the not company declare and agrees that company shalles heliiinseff liable to pay
the bank full extent to the bankers interest under the security for lost damage to the good rising from and cause whatsoever if the consigner shall sand the consignee copy of the GC note the consignee by registered post with
acknowledgment due to safeguard against miscrrange the company shall not be liable for any claim be the consigner or the consignee for non delivery or wrong delivery as long as delivery made by the company against the consignee
copy of the GC of not any person who products the same 20 incase of dispute jurisdiction of court of ahmedabad.</p>
  </div>

</div>




    </div>

  </body>
</html>