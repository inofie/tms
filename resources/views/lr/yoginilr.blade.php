<!DOCTYPE html>
<!-- saved from url=(0041)https://development.ssiwebsql.com/pdfview -->
<html class="js-focus-visible" data-js-focus-visible="">
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>Yogini LR | TMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <style type="text/css">
    
   </style>
 </head>
<body style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">

<div style="padding: 2%;">
        
        <table border="0" style="width: 100%">
        <tbody><tr style="height: 20px;">
        <td style="width: 25%;padding: 0px 10px 0px 0px"> 
            <img src="{{asset('public/uploads/yogi.png')}}" alt="" style="width: 80%">
        </td> 
       <td style="width: 35%;font-size: 10px;font-family:Helvetica Neue;">
             <p>L/22,Swatantra Senaninagar,Opp.Nava Vadaj Bus Stop,Nava Vadaj,Ahmedabad-380013</p>
        </td>
       <td style="width: 20%;font-size: 10px;">
             <p>yoginitransport@gmail.com<br>info@yoginitransport.com</p>
        </td>
         <td style="width: 20%;font-size: 10px;">
             <p>+91 9925235184<br>+91 9714714903</p>

        </td>

        </tr>    
        </tbody></table>
        <hr style="border:1.3px solid #ef7f1a;background:#ef7f1a;width: 100%;">

        <table border="0" width="97%" style="">
        <tbody><tr style="padding: 0px;margin: 0px;">
       <td style="width: 30%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
            <p style="padding: 0px;margin: 0;font-size: 10px;">L.R. No. : {{$data->lr_no}}</p>
        </td> 
       <td style="width:5%;">
             &nbsp;&nbsp;
        </td>
     <td style="width: 30%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
            <p style="padding: 0px;margin: 0;font-size: 10px;">TYPE : @if($data->imports == 1) Import @else Export @endif</p>
        </td>
         <td style="width: 5%;">
             &nbsp;&nbsp;
        </td>
         <td style="width: 30%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
            <p style="padding: 0px;margin: 0;font-size: 10px;">Date. : {{ date('d-m-Y',strtotime($data->date)) }}</p>
        </td>

        </tr>    
        </tbody>
        </table>
        <table border="0" width="100%" style="">
        <tbody><tr style="padding: 0px;margin: 0px;">
        <td style="width: 100%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
            <p style="padding: 0px;margin: 0;font-size: 10px;">Truck No. : {{ $data->truck_no }}</p>  
        </td>
        </tr>    
        </tbody>
        </table>

         <table border="0"  style="width: 100%;padding-right: 2%">
        <tbody>
            <tr style="padding: 0px;margin: 0px;">
        <td style="width: 40%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
            <p style="padding: 0px;margin: 0;font-size: 10px;">From. : {{ $data->from1 }}</p>  
        </td>

        <td style="width: 4%;padding: 5px 2%;"> 
            &nbsp;&nbsp;
        </td>

         <td style="width: 40%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
            <p style="padding: 0px;margin: 0;font-size: 10px;">To. : {{ $data->to1 }}</p>  
        </td>
        </tr>    
        </tbody>
        </table>

        <table border="0"  style="width: 100%;">
        <tbody>
            <tr style="padding: 0px;margin: 0px;">
        <td style="width: 45.5%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 

            <p style="padding: 0px;margin: 0;font-size: 10px;padding: 5px 4%;">Consigner. : {{ $data->consignor }}</p>
            <p style="padding: 0px;margin: 0;font-size: 10px;padding: 5px 4%;"><b>Address :</b><br>{{ $data->consignor_address }}</p>  
        </td>

        <td style="width: 2.5%;padding: 5px 2%;"> 
            &nbsp;&nbsp;
        </td>

         <td style="width: 45.5%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
            <p style="padding: 0px;margin: 0;font-size: 10px;padding: 5px 4%;">Consignee. : {{ $data->consignee }}</p>
            <p style="padding: 0px;margin: 0;font-size: 10px;padding: 5px 4%;"><b>Address :</b><br>{{ $data->consignee_address }}</p> 
        </td>
        </tr>    
        </tbody>
        </table>
        <table border="0" style="width: 100%;margin-bottom: 1%">
            <tr>
                <td style="padding:0.5%;border: 1px solid #e2e2e2;width: 100%">
                    
                    <table  style="width: 100%; border-collapse: collapse;">
                        
                            <tr>
                                <td style="text-align: center;width: 5%;border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;">Nos. of <br>Articals</p>
                                </td>
                                <td style="text-align: center;width: 30%;border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;">Description</p>
                                </td>
                                <td style="text-align: center;width: 5%;border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;">Actual<br>Weight</p>
                                </td>
                                <td style="text-align: center;width: 15%;border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;">Weight<br>Charged M.T.</p>
                                </td>
                                <td style="text-align: center;width: 15%;border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;">Rate<br>per M.T.</p>
                                </td>
                                <td style="text-align: center;width: 30%;border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;">Fright</p>
                                </td>

                            </tr>


                            <tr style="height: 150px;vertical-align: baseline;">
                                <td style="border: 1px solid #e2e2e2;min-height: 150px;text-align: center;">
                                        <p style="font-size: 10px;padding: 0px 0px 0px 3%;">{{ $data->package }}</p>
                                </td>
                                <td style="border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;padding: 0px 0px 0px 3%;"><?php echo  html_entity_decode($data->description); ?></p>
                                </td>
                                <td style="border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;padding: 0px 0px 0px 3%;">{{ $data->weight }}KG</p>
                                </td>
                                <td style="border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;padding: 0px 0px 0px 3%;"></p>
                                </td>
                                <td style="border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;padding: 0px 0px 0px 3%;"></p>
                                </td>
                                <td style="border: 1px solid #e2e2e2;">
                                        <p style="font-size: 10px;padding: 0px 0px 0px 3%;">To Bill At Ahmedabad</p>
                                </td>

                            </tr>

                            </table>


                             <table  style="width: 100%;margin-bottom: 1%;">
                        

                            <tr style="padding: 0px;margin: 0px;">
                                    <td style="width: 40%;padding: 0.5% 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;"> 
                                        <p style="padding: 0px;margin: 0;font-size: 10px;">Total : {{ $data->package }} Package Only</p>  
                                    </td>

                                     <td style="width: 60%;padding: 0.5% 2%;"> 
                                        <p style="padding: 0px;margin: 0;text-align: center;font-size: 12px;"><b>GST / EWAY BILL ID : {{ $data->gst }}</b></p>  
                                    </td>
                            </tr>   


                    </table>

                    <table border="0" width="97%" style="">
                    <tbody><tr style="padding: 0px;margin: 0px;">
                   <td style="width: 30%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;text-align: center;"> 
                        <p style="padding: 0px;margin: 0;font-size: 10px;">Ser. Tax . : -</p>
                    </td> 
                   <td style="width:5%;">
                         &nbsp;&nbsp;
                    </td>
                 <td style="width: 30%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;text-align: center;"> 
                        <p style="padding: 0px;margin: 0;font-size: 10px;">Surcharge. : -</p>
                    </td>
                     <td style="width: 5%;">
                         &nbsp;&nbsp;
                    </td>
                     <td style="width: 30%;padding: 5px 2%;border: 1px solid #e2e2e2;border-radius: 5px;margin: 0px;text-align: center;"> 
                        <p style="padding: 0px;margin: 0;font-size: 10px;">Total. : -</p>
                    </td>

                    </tr>    
                    </tbody>
                    </table>



                </td>
            </tr>
        </table>

        <table border="1" style="width: 100%;border-collapse: collapse;">
            <tr style="">
                <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Consigner:</p>

                   </td>
                   <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Consignee:</p>

                   </td>
                   <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Truck Type: {{ $data->trucktype_name }}</p>

                   </td>
                   <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px;vertical-align:baseline;text-align: center;" rowspan="3">
                    
                    <!--- <p style="padding: 0px;margin: 0;font-size: 10px;">Authorised</p>
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Yogini Transport</p> --->
					  	@if($data->qr_code)
							<img src="{{ asset('public/Qr_code') }}/{{ $data->qr_code }}" width="100%">
						@endif
                   </td>
                   

            </tr>
            <tr style="">
                <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Private Mark:</p>

                   </td>
                   <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Invoice No: {{ $data->shipper_invoice }}</p>

                   </td>
                   <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Driver Name: {{ $data->driver_name }}</p>

                   </td>
                   
                   

            </tr>
            <tr style="">
                <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Enclosures:</p>

                   </td>
                   <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">B / E: {{ $data->b_e_no }}</p>

                   </td>
                   <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">
                    
                    <p style="padding: 0px;margin: 0;font-size: 10px;">Licence No: {{ $data->licence_no }}</p>

                   </td>
                
                </tr>

                <tr style="">

                <td style="border: 1px solid #e2e2e2;width: 24%;padding: 5px">   

                </td>

                </tr>

            </tr>
        </table>

        <p style="padding: 0px;margin:1% 0px 0px 0px;font-size: 10px;"> N.B. (1.)This G.C. Note Issued Under Terms &amp; Condition Printed Overleaf. (2.)We are not Responsible For Leakages.Breakages DamagesCondition Goods Accepted On Owner Risk. (3.)Subject to Ahmedabad Jurisdiction<br>
                     <b>Terms and Condition :</b><br>
               
              :1.) Condition contents and value of consignment are acknowledged to yogini transport(nereinafer called the company).The company carries the goods as packed of owners of consignor's risk.
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
copy of the GC of not any person who products the same 20 incase of dispute jurisdiction of court of ahmedabad.</p>


</div>















</body></html>