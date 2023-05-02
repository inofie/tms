<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>A simple, clean, and responsive HTML invoice template</title>

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
   



    <div class="invoice-box">
      <table style="width: 100%; text-align:right;">
        <tr>
          <td>
            <img style="width: 100%; text-align: right;" src="{{ asset('public/uploads/33y.jpg')}}">
          </td>
        </tr>
      </table>
      <table style="border-collapse: collapse; margin-bottom: 0.5%">
        <tr>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Invoice No.</td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 33.32%;">H0727/20-21</td>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Dated.</td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 33.32%;">31-Jan-2021</td>
          
        </tr>

      </table>

      <table style="border-collapse: collapse; margin-bottom: 0.5%">
        <tr>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">To.</td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 33.32%;">AHMEDABAD</td>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">RAJKOT</td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 33.32%;">31-Jan-2021</td>
          
        </tr>

      </table>




    <table style="border-collapse: collapse; margin-bottom: 0.5%">
      <tr>
        <td style="text-align: left; border: 1px solid #000 ;  padding:5px; width: 33.36%; font-size: 10px; font-weight: 600; line-height: 1.7; font-family:sans-serif;">
                    <span style="font-size: 13px; font-weight: 700;"><strong>HANSH TRANSPORT</strong></span><br>
                    C-348, Sumel -7 Nr. Soni Chawl, Ahmedabad,<br> Gujarat - 382350, India<br>
                    GSTIN/UIN: 24AAEFK1739J1ZD<br>
                    State Name : Gujarat, Code : 24<br>
                      E-Mail : cakhurdia@gmail.com
                  </td>
        <td style="text-align: left; border: 1px solid #000 ;  padding:5px; width: 33.36%; font-size: 10px; font-weight: 600; line-height: 1.5; font-family:sans-serif;" >
                  <span style="font-size: 13px; font-weight: 700;"><strong>C. H. ROBINSON</strong></span><br>
                  407-408, 4th Floor, Zodiac Plaza H.L. College Road,<br> Next to NABARD Officers Flat<br>
                Navrangpura, Ahmedabad, Gujarat-380009<br>
                GSTIN/UIN: <span style="margin-left: 5.3%">24AACCC9617L1ZG</span><br>
                State Name:<span style="margin-left: 5%">Gujarat, Code : 24</span><br>
                A/C: <span style="margin-left: 13.8%"></span>
                </td>
      </tr>

    </table>

     <table style="border-collapse: collapse; margin-bottom: 0.5%">
        <tr>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Supplier's Ref.</td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 16.66%;">H0727/20-21</td>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Shipping No.</td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 16.66%;">SSI00123</td>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Bill Of Entry. </td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 16.66%;">SSI00123</td>
          
        </tr>

      </table>

      <table style="border-collapse: collapse; margin-bottom: 0.5%">
          <tr>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Mode/Terms of
Payment. </td>
          <td style="border: 1px solid #000 ; text-align: center; font-size: 10px;    padding: 5px; line-height: 1.2; width: 16.66%;">30 Days</td>
          <td style="border: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;  background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 16.66%;">Shipment Type.</td>

          <td style="border: 1px solid #000 ;text-align: center; font-size: 10px; line-height: 1.2; padding: 5px; border-bottom: 1px solid #000; width: 16.66%;" >
                  <table>
                    <tr>
                      <td style="width:4.165%;">
                          <input type="checkbox" checked="checked" id="vehicle1" name="vehicle1" value="Bike" >
                      </td>
                      <td style="width:4.165%;">
                        LCL
                      </td>
                      <td style="width:4.165%;">
                      <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                      </td>
                      <td style="width:4.165%;">
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
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 11.51%; line-height: 1.2;  padding: 5px;">Truck No</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 11.51%; line-height: 1.2;  padding: 5px;">L.R. No</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 13.66%; line-height: 1.2;  padding: 5px;">Shipment No </td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 6.66%; line-height: 1.2;  padding: 5px;">Per</td>
                <td style="border: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  width: 17.66%; line-height: 1.2;  padding: 5px;">Amount</td>

              </tr>



     

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 5px;">1.</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 5px;">Transportation Service Account </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 5px;"></td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 5px;">996791 </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 5px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 5px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 5px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 5px;">27,500.00</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">2.</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 1px;">Other Transport Service </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 1px;"></td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">996791 </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 1px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 1px;">1,500.00</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 1px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 1px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 1px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 1px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 1px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>

              </tr>

              <tr>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px;   width:5%; line-height: 1.2; padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 24%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 10%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 11.51%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 13.66%; line-height: 1.2;  padding: 1px;">&nbsp; </td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 6.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px;   width: 17.66%; line-height: 1.2;  padding: 1px;">&nbsp;</td>

              </tr>

        
             
               <tr>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px; font-weight: 700;   width:5%; line-height: 1.2; padding: 3px;">&nbsp;</td>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px; font-weight: 700;   width: 24%; line-height: 1.2;  padding: 3px; background-color: #e6e6e6;" >Total</td>
                 <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px; font-weight: 700;   width: 10%; line-height: 1.2;  padding: 3px; background-color: #e6e6e6;" ></td>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px; font-weight: 700;   width: 11.51%; line-height: 1.2;  padding: 3px;">&nbsp;</td>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px; font-weight: 700;   width: 11.51%; line-height: 1.2;  padding: 3px;">&nbsp;</td>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px; font-weight: 700;   width: 13.66%; line-height: 1.2;  padding: 3px;">&nbsp; </td>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px; font-weight: 700;   width: 6.66%; line-height: 1.2;  padding: 3px;">&nbsp;</td>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ;text-align: center; font-size: 10px; font-weight: 700;   width: 17.66%; line-height: 1.2;  padding: 3px;">₹ 29,000.00</td>

              </tr>

              

      </table>
      

        <table  style="border-collapse: collapse; margin-bottom: 0.5% ;">
          <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px; font-weight: 700;   width:39%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Amount Chargeable (in words)</td>

              <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 10px; font-weight: 700;   width:51%; line-height: 1.2; padding: 5px; border-right: none;">Indian Rupees Twenty Nine Thousand Only</td>

               <td style=" border: 1px solid #000 ; border-left: none; text-align: center; font-size: 10px; font-weight: 700;   width:10%; line-height: 1.2; padding: 5px;">E. & O.E</td>

            
          </tr>
        
      </table>

      <table  style="border-collapse: collapse; margin-bottom: 0.5%;">
          <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700;   width:75.68%; line-height: 1.2; padding: 8px; background-color: #e6e6e6;">HSN/SAC</td>

              <td style=" border: 1px solid #000 ;  text-align: center; font-size: 12px; font-weight: 700;   width:24.32%; line-height: 1.2; padding: 8px;background-color: #e6e6e6; ">Taxable Value</td>
            
          </tr>

          <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: left; font-size: 10px; font-weight: 700;   width:75.68%; line-height: 1.2; padding: 5px; ">996791</td>

              <td style=" border: 1px solid #000 ;  text-align: center; font-size: 10px; font-weight: 700;   width:24.32%; line-height: 1.2; padding: 5px; ">27,500.00</td>
            
          </tr>

           <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: right; font-size: 10px; font-weight: 700;   width:75.68%; line-height: 1.2; padding: 5px; ">Total</td>

              <td style=" border: 1px solid #000 ;  text-align: center; font-size: 10px; font-weight: 700;   width:24.32%; line-height: 1.2; padding: 5px; ">27,500.00</td>
            
          </tr>
        
      </table>

      <table style="border-collapse: collapse; border: 1px solid #000; margin-bottom: 0.5%;" >
        <tr>
          
          <td style="text-align: center; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  padding: 5px; line-height: 1.2; width: 50%; border:  1px solid #000;">TAX PAYABLE ON REVERSE CHARGES :<span style="font-weight: 700;"> YES</span></td>

        </tr>

      </table>


      <table style="border-collapse: collapse; border: 1px solid #000; margin-bottom: 0.5%;">
            <tr>
            <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700;   width:37.84%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Container No.</td>
            <td style=" border: 1px solid #000; text-align: left; font-size: 13px; width:37.84%; line-height: 1.2; padding: 5px;"></td> 
            <td style=" border-left: 1px solid #000; text-align: left; font-size: 13px; width:24.32%; line-height: 1.2; padding: 5px;"></td>
            </tr>
            <tr>
               <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700;   width:37.84%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Seat No.</td>
                
                <td style="border: 1px solid #000; text-align: center; font-size: 13px; width:37.84%; line-height: 1.2; padding: 5px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000;text-align: center; font-size: 13px; width:24.32%; line-height: 2; padding: 5px;"></td>

            </tr>
            
            <tr>
                <td style=" border: 1px solid #000 ; border-right: 1px solid #000 ; text-align: center; font-size: 12px; font-weight: 700;   width:37.84%; line-height: 1.2; padding: 5px; background-color: #e6e6e6;">Shipping No.</td>
                <td style="border: 1px solid #000; text-align: center; font-size: 13px; width:37.84%; line-height: 1.2; padding: 5px;">&nbsp;</td>
                <td style=" border-left: 1px solid #000;text-align: center; font-size: 13px; width:24.32%; line-height: 1.2; padding: 5px;">for HANSH TRANSPORT</span></td>

            </tr>
      </table>


            <table style="border-collapse: collapse; border: 1px solid #000; margin-bottom: 0.5%;">
        <tr >
                <td style="text-align: left; font-size: 12px; font-weight: 700; background-color: #e6e6e6;  padding: 5px; line-height: 1.2; width: 50%; border-bottom:  1px solid #000;">Remarks:</td>
                <td style=" text-align: left; font-size: 12px; font-weight: 700; background-color: #e6e6e6; padding: 5px; line-height: 1.2; width: 50%; border-bottom:  1px solid #000;">Company's Bank Details:</td>
                
              </tr>

         <tr >
                <td style="text-align: left; font-size: 10px; font-weight: 700; padding: 3px 5px; padding-top: 10px; line-height: 1.2; width: 50%;">Being Bill Issued against shipment</td>
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; padding-top: 10px; line-height: 1.2; width: 50%;">Bank Name :<span style="margin-left: 14.3%"> Axis Bank</span></td>
                
              </tr>

               <tr >
                <td style="text-align: left; font-size: 10px; font-weight: 700; padding: 3px 5px; line-height: 1.2; width: 50%;">Company's PAN
                  :<span style="margin-left: 5.3%">AAEFK1739J</span></td>
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; line-height: 1.2; width: 50%;">A/c No :<span style="margin-left: 20.3%"> 920020000972924</span></td>
                
              </tr>

              <tr >
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; line-height: 1.2; width: 50%; padding-bottom: 10px;">Subject:<span style="margin-left: 15.8%; "> Ahmedabad Junction</span></td>
                <td style=" text-align: left; font-size: 10px; font-weight: 700;padding: 3px 5px; line-height: 1.2; width: 50%; padding-bottom: 10px;">Branch & IFS Code :<span style="margin-left: 5.3% " > UTIB0000080</span></td>
                
              </tr>



      </table>
      <table style="border-collapse: collapse;">
        <tr>
          <td style="text-align: center; font-size: 12px; line-height: 1.2; font-weight: 700; padding:5px; background-color: #b1333f; color: #fff;">Note:If Any Changes Please Inform us to within 6 days of invoice date.
</td>

        </tr>
      </table>

      


     
    </div>
  </body>
</html>