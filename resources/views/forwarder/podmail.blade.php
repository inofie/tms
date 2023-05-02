
<h3 style="text-align:center;">Shipment {{ $shipment_no }} POD Details</h3>

<table  width="100%">	
               @foreach($data as $values)
                <tr>
                    <td style="text-align: center;">
                        <p style="text-align: center;">Truck No:<b>{{ $values->truck_no }}</b></p>
                	@if($values->load_time != Null && $values->load_time != NULL && $values->load_time !='')	
                		<div style="width:50%; float: left;" >
                		<h4>Loaded Photo</h4>
                		<img src="{{ getenv('APP_URL') }}/public/uploads/{{ $values->loaded_photo }}" width="100px"><br>
                		<p> {{ date('d-m-Y H:i A',strtotime($values->load_time)) }}</p>
                		</div>
                    @else
                        <div style="width:50%; float: left;" >
                        <h4>Loaded Photo</h4>
                        <img src="{{ getenv('APP_URL') }}/public/uploads/noimage.png" width="100px"><br>
                       </div>

                	@endif

					@if($values->unload_time != Null && $values->unload_time != NULL && $values->unload_time !='')
						<div style="width:50%; float:left;">
						<h4>Unloaded Photo</h4>
                		<img src="{{ getenv('APP_URL') }}/public/uploads/{{ $values->unloaded_photo }}" width="100px"><br>
                		<p> {{ date('d-m-Y H:i A',strtotime($values->unload_time)) }}</p>
                		</div>
                        @else
                        <div style="width:50%; float: left;" >
                        <h4>Unloaded Photo</h4>
                        <img src="{{ getenv('APP_URL') }}/public/uploads/noimage.png" width="100px"><br>
                       </div>
                	@endif
                	</td>
               </tr>
        
              @endforeach 
              
</table>