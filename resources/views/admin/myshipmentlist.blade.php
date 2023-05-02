							 <div class="adv-table" style="padding: 1%;">
							<table class="table table-striped table-hover table-bordered" >
								<tr>
								<th class="center">Select</th>
								<th class="center">Shipment_no</th>
								<th class="center">From</th>
								<th class="center">To</th>
								<th class="center">Date</th>
								</tr>

								@if(count($data) > 0)

									@foreach($data as $value)
									<tr>
									<td class="center"><input type="checkbox" name="shipment" value="{{ $value->shipment_no }}" class="shipment" data-shipment_id="{{ $value->shipment_no }}"></td>
									<td class="center">{{ $value->shipment_no }}</td>
									<td class="center">{{ $value->from1 }}</td>
									<td class="center">{{ $value->to1 }}</td>
									<td class="center">{{ date('d-m-y',strtotime($value->date)) }}</td>
									</tr>
									@endforeach

								@else 

									<tr> <td class="center" colspan="5"> <b>Shipments are not available.</b> </td> </tr>

								@endif

								
							</table>
							</div>
							<?php $gst = array(); 

							$gst[] = array('id'=>'1','name'=>'5% GST');
							
							$gst[] = array('id'=>'2','name'=>'18% GST');
							
							$gst[] = array('id'=>'3','name'=>'5% IGST');
							
							$gst[] = array('id'=>'4','name'=>'18% IGST'); 
							
							$gst[] = array('id'=>'5','name'=>'5% UTGST'); 
							
							$gst[] = array('id'=>'6','name'=>'18% UTGST'); 
							
							
							?>

     						 <div class="form-group" style="width: 80%; padding-left: 20%">
                                            <label for="gst" class="control-label col-lg-2">GST :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" id="gst" name="gst" required="required"> 
                                                 
                                                      @foreach($gst as $value)
                                                      <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                                      @endforeach 
                                                </select>
                                            </div>
                                        </div>

                                 

                                         <div class="form-group save_cancle btn2">
                                            <div class="col-lg-12 center">
                                            	<button class="btn btn-primary" id="btnback2" type="button"> < Back</button>
                                                <button class="btn btn-success" id="btn2" type="button">Next ></button>
                                                
                                            </div>
                                        </div>
                                       <script type="text/javascript">

                                       	$('#btnback2').click(function(){

            $('#section2').css('display','none');
             $('#section2').html('');
             $('#section1').css('display','block');
    });
                                       	$('#btn2').click(function(){
                                       		var _token   = $('meta[name="csrf-token"]').attr('content');


  

   var sList = "";

   var total = $('input[type=checkbox]:checked').length;
   //alert(total);

$('input[type=checkbox]:checked').each(function(index) {

//alert(index);
    
	if (index === total - 1) {

		if ($(this).is(":checked"))
		{
			sList +=  $(this).val() ;
		}
       
    } else {

    	if ($(this).is(":checked"))
		{
			sList +=  $(this).val() + ',';
		}

    }





    
});


if(sList == ""){
	alert("Please select at least 1 shipment");
	return false;
}

var gst = $('#gst').val();


 $.ajax({
            url: "{{ route('invoiceshipmentgst') }}",
            type:"POST",
            data:{
              shipment_nos:sList,
              gst:gst,
               _token: _token
            },
            success:function(response){
              console.log(response);
             $('#section2').css('display','none');
             $('#section3').html(response);
             $('#section3').css('display','block');

            },
       });





    });
                                       </script>