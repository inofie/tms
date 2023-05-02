<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>LR | TMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" type="text/css" href="{{asset('pdf/bootstrap/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('pdf/font-awesome/css/font-awesome.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('pdf/css/style.css')}}" />

    <script type="text/javascript" src="{{asset('pdf/js/jquery-1.10.2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('pdf/bootstrap/js/bootstrap.min.js')}}"></script>
</head>
<body>

<div class="container">


<!-- Simple Invoice - START -->
<div class="container">
    <div class="row">
        <div class="col-xs-12 lr_heading">
 
            <div class=" row top-section col-lg-12 lr_heading_1 ">
             <div class=" corporate-id col-lg-3 lr_heading_2">
                <img src="{{asset('pdf/img/vector-lab.png')}}" alt="">
             </div>

             <div class="col-lg-9 address_style">
                <div class="col-lg-3 left" >
                 <p  class="font_size_width">
                                      L/22, Swatantra Senani Nagar,
                                      Nava Vadaj, Ahmedabad - 3800013
                  </p>
                  </div>
                   <div class="col-lg-3 left" >
                 <p  class="font_size_width">
                                     yoginitransport@gmail.com
                                     <br>info@yoginitransport.com
                  </p>
                  </div>
                   <div class="col-lg-3 left" >
                 <p class="font_size_width">
                                      +91 9925235184<br>+91 9714714903
                  </p>
                  </div>
             </div>

             </div>
        
        </div>
    </div>
      

      <div class="row lr_top">
                  <div class="lr_top_section"></div>
             </div>

             <div class="row">
                <div class="col-xs-4 col-md-4 col-lg-4 pull-left ">
                    <div class="panel panel-default margin-bottom-5" >
                        <div class="panel-heading font_size_width"><b>L.R. No. :</b> <span>{{ $data->lr_no }}</span></div>
                    </div>
                </div>

                <div class="col-xs-4 col-md-4 col-lg-4 pull-left ">
                    <div class="panel panel-default margin-bottom-5 ">
                         <div class="panel-heading font_size_width"><b>TYPE :</b> @if($data->imports == 1) Import @else Export @endif </div>
                    </div>
                </div>

                

                <div class="col-xs-4 col-md-4 col-lg-4 pull-left ">
                    <div class="panel panel-default margin-bottom-5" >
                        <div class="panel-heading font_size_width"><b>Date. :</b> <span>{{ date('d-m-Y',strtotime($data->date)) }}</span></div>
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12 pull-left">
                    <div class="panel panel-default margin-bottom-5" >
                        <div class="panel-heading font_size_width"><b>Truck No. :</b> <span>{{ $data->truck_no }}</span></div>
                    </div>
                </div>
            </div>

                 <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6 pull-left ">
                    <div class="panel panel-default margin-bottom-5 " >
                        <div class="panel-heading font_size_width"><b>From. :</b> <span>{{ $data->from1 }}</span></div>
                    </div>
                </div>

                <div class="col-xs-6 col-md-6 col-lg-6 pull-left ">
                    <div class="panel panel-default margin-bottom-5" >
                         <div class="panel-heading font_size_width"><b>To. :</b> <span>{{ $data->to1 }}</span></div>
                    </div>
                </div>
                </div>
                

                <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="panel panel-default height">
                        <div class="panel-heading font_size_width"><b>Consigner. :</b> <span>{{ $data->consignor }}</span></div>
                        <div class="panel-body font_size_width" >
                            <strong class="font_12">Address</strong><br>{{ $data->consignor_address }}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6 pull-right">
                    <div class="panel panel-default height">
                       <div class="panel-heading font_size_width"><b>Consignee. :</b> <span>{{ $data->consignee }}</span></div>
                        <div class="panel-body font_size_width">
                            <strong class="font_12">Address</strong><br>{{ $data->consignee_address }}</div>
                    </div>
                </div>
                </div>





    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead  class="description">
                                <tr>
                                    <td><strong>Nos. of<br>Articals</strong></td>
                                    <td><strong>Description</strong></td>
                                    <td><strong>Actual<br>Weight</strong></td>
                                    <td><strong>Weight<br>Charged M.T. </strong></td>
                                    <td><strong>Rate<br>per M.T.</strong></td>
                                    <td><strong>Fright</strong></td>
                                </tr>
                            </thead>
                            <tbody  class="description">
                                <tr>
                                    <td>{{ $data->package }}</td>
                                    <td><?php echo  html_entity_decode($data->description); ?></td>
                                    <td>{{ $data->weight }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>To Bill At Ahmedabad</td>
                                </tr>
                                
                           
                                <tr></tr>
                                
                            
                            </tbody>
                        </table>
                        <p  >
                    <div class=" pull-left text-center width100" >
                    <div class=" ">
                           <div class="col-xs-7 col-md-7 col-lg-7" > 
                            <div class="panel panel-default col-xs-6 col-md-6 col-lg-6">
                         <div class="panel-heading font_size_width" >
                            <b>Total :</b> <span>{{ $data->package }} Package Only</span>
                        </div>
                    </div>
                        
                        </div>
                        <div class="col-xs-5 col-md-5 col-lg-5 gst_section">
                            <p>GST / EWAY BILL ID : 24AACFY4389E1Z0</p>
                        </div> 
                           
                         

                    </div>
                     </div>
               

                        </p>

                        </div>
                        <div class="col-xs-4 col-md-4 col-lg-4" >
                            <div class="panel panel-default margin-bottom-5" >
                            <div class="panel-heading font_size_width height23"><b>Ser. Tax . :</b> <span>-</span></div>
                            </div>
                            </div>
                            <div class="col-xs-4 col-md-4 col-lg-4">
                            <div class="panel panel-default margin-bottom-5" >
                            <div class="panel-heading font_size_width height23"><b>Surcharge. :</b> <span>-</span></div>
                            </div>
                            </div>
                            <div class="col-xs-4 col-md-4 col-lg-4">
                            <div class="panel panel-default margin-bottom-5" >
                            <div class="panel-heading font_size_width height23" ><b>Total. :</b> <span>-</span></div>
                            </div>
                            </div>

                        
            </div>
        </div>
    </div>




</div>

                <div class="row">
                    <div class="col-md-12 lt_bottom_section">
                        <div class="panel panel-default lt_bottom_iner ">
                            <div class="panel-heading lt_bottom_iner">
                                <table class="table table-condensed table_style ">
                            <thead>
                                <tr class="lr_bottom">
                                    <td><strong>Consigner:</strong></td>
                                    <td><strong> </strong></td>
                                    <td><strong>Consignee:</strong></td>
                                    <td><strong> </strong></td>
                                    <td><strong>Truck Type:</strong></td>
                                    <td><strong> {{ $data->trucktype_name }}</strong></td>
                                    <td class="lr_bottom_border text-center"><strong>Authorised</strong></td>
                                </tr>
                                <tr class=" lr_bottom">
                                    <td ><strong>Private Mark:</strong></td>
                                    <td><strong> </strong></td>
                                    <td><strong>Invoice No:</strong></td>
                                    <td><strong> {{ $data->shipper_invoice }}</strong></td>
                                    <td><strong>Driver Name:</strong></td>
                                    <td><strong> {{ $data->driver_name }}</strong></td>
                                    <td class="lr_bottom_border_top text-center"><strong></strong></td>
                                </tr>
                                 <tr class="lr_bottom">
                                    <td><strong>Enclosures:</strong></td>
                                    <td><strong> </strong></td>
                                    <td><strong>B / E:</strong></td>
                                    <td><strong> {{ $data->b_e_no }}</strong></td>
                                    <td><strong>Licence No:</strong></td>
                                    <td><strong> {{ $data->licence_no }}</strong></td>
                                    <td class=" lr_bottom_border_top1 text-center"><strong>Yogini Transport</strong></td>
                                </tr>
                            </thead>
                            
                        </table>
  
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                         
                <p class="font-size9 text-justify"> N.B. (1.)This G.C. Note Issued Under Terms &amp; Condition Printed Overleaf. (2.)We are not Responsible For Leakages.Breakages DamagesCondition Goods Accepted On Owner Risk. (3.)Subject to Ahmedabad Jurisdiction<br>
                     <span class="font-weight900">Terms and Condition :</span><br>
               
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
            </div>

            
    </div>



<!-- Simple Invoice - END -->

</div>

</body>
</html>