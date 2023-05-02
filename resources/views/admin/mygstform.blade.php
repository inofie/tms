

<div class="adv-table" style="padding: 1%;">
							<table class="table table-striped table-hover table-bordered" >
	<tr>
		<th>
			Shipment_no
		</th>
		<th>
			Reason
		</th>
		<th>
			Sub Amount
		</th>
		<th>
			Detention
		</th>
		<th>
			Loading<br>Unloading
		</th>
		<th>
			Total
		</th>
	</tr>
		
	 	@foreach($expense as $key => $value)

		 <tr>
			<td> {{ $value['shipment_no'] }}</td>
			<td> {{ $value['reason'] }}</td>
			<td> {{ $value['sub_amount'] }}</td>
			<td> {{ $value['detention_amount'] }}</td>
			<td> {{ $value['labour_amount'] }}</td>
			<td> {{ $value['amount'] }}</td>
		</tr> 
		@endforeach	
	
</table>
</div>




<div class="adv-table" style="padding: 1%;">
							<table class="table table-striped table-hover table-bordered" >
	<tr>
		<th>
			Truck No.
		</th>
		<th>
			Freight
		</th>
		<th>
			Detention
		</th>
		<th>
			Loading<br>Unloading
		</th>
		<th>
			Others
		</th>
		<th>Ex. Total</th>
	</tr>
		<?php $aa = 0; ?>
		@foreach($trucks as $key2 => $value2)
		<?php $aa = $aa+$value2['freight']; ?>
		<tr>
			<td> 
				<input type="hidden" class="ship_no{{ $key2 }} ship_no" id="ship_no{{ $key2 }}" value="{{ $value2['shipment_no'] }}">
				<input style="width: 150px" type="text" class="trow{{ $key2 }} truck truck{{ $key2 }}" readonly="readonly" value="{{ $value2['truck_no'] }}"></td>
			<td> <input style="width: 150px" type="text" class="trow{{ $key2 }} fright fright{{ $key2 }} edit" id="fright{{ $key2 }}" value="{{ $value2['freight'] }}"></td>
			<td> <input style="width: 150px" type="text" class="trow{{ $key2 }} detention detention{{ $key2 }} edit" id="detention{{ $key2 }}" value="0"></td>
			<td> <input style="width: 150px" type="text" class="trow{{ $key2 }} loading loading{{ $key2 }} edit" id="loading{{ $key2 }}" value="0"></td>
			<td> <input style="width: 150px" type="text" class="trow{{ $key2 }} other other{{ $key2 }} edit" id="other{{ $key2 }}" value="0"></td>
			<td> <input style="width: 150px" type="text" class="trow{{ $key2 }} total total{{ $key2 }}" id="total{{ $key2 }}" readonly="readonly" value="0"></td>
		</tr> 
		@endforeach	
		<tr>
			<td>Total</td>
			<td id="ftotal" style="font-weight: 700;">{{ $aa }}</td>
			
			<td colspan="3"></td>
			<td id="extotal" style="font-weight: 700;">0</td> 
		</tr>


</table>
</div>

<?php 
	if($gst == 1){

		$cgst = (($aa*2.5)/100);
	}

	if($gst == 2){

		$cgst = (($aa*9)/100);
	} 

	if($gst == 3){

		$igst = (($aa*5)/100);
	} 
	if($gst == 4){

		$igst = (($aa*18)/100);
	} 
	if($gst == 5){

		$utgst = (($aa*5)/100);
	} 
	if($gst == 6){

		$utgst = (($aa*18)/100);
	} 

?>

<div class="adv-table" style="padding: 1%;">
							<table class="table table-striped table-hover table-bordered" >
	<tr>
		<th class="center">
			CGST
		</th>
		<th class="center">
			SGST
		</th>
		<th class="center">
			IGST
		</th>
		<th class="center">
			UTGST
		</th>
		<th class="center">
			TOTAL GST
		</th>
		<th class="center">
			<b> Brand Total </b>
		</th>
	</tr>

	<tr>

		@if($gst == 1 || $gst == 2)
		<td id="cgst" class="center">{{ $cgst }}</td><td id="sgst" class="center">{{ $cgst }}</td>
		@else 
		<td id="cgst" class="center">0</td><td id="sgst" class="center">0</td>
		@endif
		@if($gst == 3 || $gst == 4)
		<td id="igst" class="center">{{ $igst }}</td>
		@else
		<td id="igst" class="center">0</td>
		@endif

		@if($gst == 5 || $gst == 6)
		<td id="utgst" class="center">{{ $utgst }}</td>
		@else
		<td id="utgst" class="center">0</td>
		@endif
		<td id="totalgst" class="center">@if($gst == 1 || $gst == 2){{ $tt= $cgst+$cgst }}@endif @if($gst == 3 || $gst == 4){{ $igst }}@endif @if($gst == 5 || $gst == 6){{ $utgst }}@endif</td>
		<td id="grandtotal" class="center" style="font-weight: 700;">{{ $total = $aa}}</td>
	</tr>
	
</table>
</div>

	<div class="form-group save_cancle btn3">
	    <div class="col-lg-12 center">
	    <button class="btn btn-primary" id="btnback3" type="button"> < Back</button>
	    <button class="btn btn-success" id="btn3" type="button">Finish </button>
	    </div>
      </div>


            <script type="text/javascript">
           $('#btnback3').click(function(){

            $('#section3').css('display','none');
             $('#section3').html('');
             $('#section2').css('display','block');
    			});

           $('.edit').focusout(function(){

           	var tt= $('.fright').length - 1;	
    
           	var tfright = 0;

           	var bt = 0;

				           	$('.fright').each(function(index) {

				           		tfright = parseInt($(this).val()) + parseInt(tfright) ;
				           		bf = parseInt($(this).val());
				           		bd = parseInt($(".detention"+index).val());
				           		bl = parseInt($(".loading"+index).val());
				           		bo = parseInt($(".other"+index).val());
				           		
				           		$(".total"+index).val(bd+bl+bo);
				           		bt = parseInt(bt) + parseInt($(".total"+index).val()) ;




				           		if(index === tt){
									$('#extotal').html(bt);
									$('#ftotal').html(tfright);
									var mytotal = tfright + bt;
									$('#grandtotal').html(mytotal);
								}
				           

				           	});




				           	
				           	var mygst = $('#gst').val();

				           	if(mygst == 1){
				           		var cgst = parseInt((tfright*2.5)/100);
				           		//alert(cgst);
				           		$('#cgst').html(cgst);
				           		$('#sgst').html(cgst);
				           		$('#totalgst').html(cgst*2);
				           		$('#ftotal').html(tfright);   


				           		console.log("ftotal = "+tfright);  

				           	}

				           	if(mygst == 2){
				           		var cgst = parseInt((tfright*9)/100);
				           		//alert(cgst);
				           		$('#cgst').html(cgst);
				           		$('#sgst').html(cgst);
				           		$('#totalgst').html(cgst*2);
				           		$('#ftotal').html(tfright);

				           		console.log("ftotal = "+tfright);  
				           	}

				           	

				           	if(mygst == 3){
				           		var igst = parseInt((tfright*5)/100);
				           		//alert(cgst);
				           		$('#igst').html(igst);
				           		$('#totalgst').html(igst);
				           		$('#ftotal').html(tfright);  
				           		console.log("ftotal = "+tfright);           		
				           	}

				           	if(mygst == 4){
				           		var igst = parseInt((tfright*18)/100);
				           		//alert(cgst);
				           		$('#igst').html(igst);
				           		$('#totalgst').html(igst);
				           		$('#ftotal').html(tfright);     
				           		console.log("ftotal = "+tfright);        		
				           	}

				           	if(mygst == 5){
				           		var utgst = parseInt((tfright*5)/100);
				           		//alert(cgst);
				           		$('#utgst').html(utgst);
				           		$('#totalgst').html(utgst);
				           		$('#ftotal').html(tfright);   
				           		console.log("ftotal = "+tfright);          		
				           	}

				           	if(mygst == 6){
				           		var utgst = parseInt((tfright*18)/100);
				           		//alert(cgst);
				           		$('#utgst').html(utgst);
				           		$('#totalgst').html(utgst);
				           		$('#ftotal').html(tfright);  
				           		console.log("ftotal = "+tfright);           		
				           	}




				           	ftotal


           });





           $("#btn3").click(function(){

	          
	           	 var all_trucks = {}; 

	           	  var ship = '';

	           	 var total_ship = $('.ship_no').length;

	           	  $('.ship_no').each(function(index) {

	           	  	if (index === total_ship - 1) {

	           	  		ship +=  $(this).val() ;

	           	  	}else {

						ship +=  $(this).val() + ',';

	           	  	}

	           	  });
	           	  console.log("ship = " + ship);


	           	 var trucks = '';

	           	 var total_trucks = $('.truck').length;

	           	  $('.truck').each(function(index) {

	           	  	if (index === total_trucks - 1) {

	           	  		trucks +=  $(this).val() ;

	           	  	}else {

						trucks +=  $(this).val() + ',';

	           	  	}

	           	  });
	           	  
	           	 
	           	  console.log("trucks = " + trucks);



	           	  var freight = '';

	           	 var total_trucks = $('.fright').length;

	           	  $('.fright').each(function(index) {

	           	  	if (index === total_trucks - 1) {

	           	  		freight +=  $(this).val() ;

	           	  	}else {

						freight +=  $(this).val() + ',';

	           	  	}

	           	  });

	           	  
	           	  console.log("freight = " + freight);


	           	 var detention = '';

	           	 var total_trucks = $('.detention').length;

	           	  $('.detention').each(function(index) {

	           	  	if (index === total_trucks - 1) {

	           	  		detention +=  $(this).val() ;

	           	  	}else {

						detention +=  $(this).val() + ',';

	           	  	}

	           	  });
	           
	           	  console.log("detention = " + detention);


	           	  var loading = '';

	           	 var total_trucks = $('.loading').length;

	           	  $('.loading').each(function(index) {

	           	  	if (index === total_trucks - 1) {

	           	  		loading +=  $(this).val() ;

	           	  	}else {

						loading +=  $(this).val() + ',';

	           	  	}

	           	  });
	        
	           	  console.log("loading = " + loading);



	           	  var other = '';

	           	 var total_trucks = $('.other').length;

	           	  $('.other').each(function(index) {

	           	  	if (index === total_trucks - 1) {

	           	  		other +=  $(this).val() ;

	           	  	}else {

						other +=  $(this).val() + ',';

	           	  	}

	           	  });
	           	
	           	  console.log("other = " + other);


	           	  var total = '';

	           	 var total_trucks = $('.total').length;

	           	  $('.total').each(function(index) {

	           	  	if (index === total_trucks - 1) {

	           	  		total +=  $(this).val() ;

	           	  	}else {

						total +=  $(this).val() + ',';

	           	  	}

	           	  });
	           	 
	           	  console.log("total = " + total);
	          

	           	 var invoice_date = $("#ship_date").val();
	           	 console.log("invoice_date = " + invoice_date);

	           	 var invoice_no = $("#invoice_no").val();
	           	 console.log("invoice_no = "+invoice_no);

	           //shipemtn_no
	           var shipments = "";
	           var totalshipment = $('input[type=checkbox]:checked').length;

	           console.log("totalshipment = "+ totalshipment)
  
				$('input[type=checkbox]:checked').each(function(index) {
    
					if (index === totalshipment - 1) {

						if ($(this).is(":checked"))
						{
							shipments +=  $(this).val() ;
						}
				       
				    } else {

				    	if ($(this).is(":checked"))
						{
							shipments +=  $(this).val() + ',';
						}

				    }
	   
					});
	           	
	           	 console.log("shipments = "+shipments);


	           	 var gst = $("#gst option:selected" ).text();
				 console.log("gst = "+gst);

	           	 var cgst = $('#cgst').html();
	           	 console.log("cgst = "+cgst);

				 var sgst = $('#sgst').html();
				 console.log("sgst = "+sgst);

				 var igst =$('#igst').html();
				 console.log("igst = "+igst);

				 var utgst =$('#utgst').html();
				 console.log("utgst = "+utgst);

				 var totalgst =$('#totalgst').html();
				 console.log("totalgst = "+totalgst);

				 var ftotal = $('#ftotal').html();
				 console.log("ftotal = "+ftotal);

				 var extotal =$('#extotal').html();
				 console.log("extotal = "+extotal);

				 var grandtotal =$('#grandtotal').html();
				 console.log("grandtotal = "+grandtotal);

				 console.log("grandtotal length = "+grandtotal.length);
				
				var company = $("#company").val();
	           	console.log("company = " + company);

	           	var forwarder = $("#forwarder").val();
	           	console.log("forwarder = " + forwarder);

				 if(grandtotal == "" || grandtotal == null || grandtotal =="undefined" ){
					alert("Please Enter Correct Value in All Fields");
					return false;
				}

				var gstoption =  $("#gst").val();
				console.log("gstoption = " + gstoption);
				var _token   = $('meta[name="csrf-token"]').attr('content');

				console.log("_token = " + _token);
				$.ajax({
		            url: "{{ route('invoicesave') }}",
		            type:"POST",
		            data:{
		              ship:ship,
		              trucks:trucks,
		              gstoption:gstoption,
	           	      freight:freight,
	           	      detention:detention,
	           	      loading:loading,
	           	      other:other,
	           	  	  total:total,
		              invoice_date:invoice_date,
		              invoice_no:invoice_no,
		              totalshipment:totalshipment,
		              shipments:shipments,
		              gst:gst,
		              cgst:cgst,
		              sgst:sgst,
		              igst:igst,
		              utgst:utgst,
		              totalgst:totalgst,
		              ftotal:ftotal,
		              extotal:extotal,
		              grandtotal:grandtotal,
		              company:company,
		              forwarder:forwarder,
		              _token: _token
		            },
		            success:function(response){
		            	console.log(response);
		              if(response == 1){

		              	window.location.replace("{{ route('unpaidshipmentlist') }}");

		              } else {

		              console.log(response);

		              }

		            },
       		});



           });




            </script>	