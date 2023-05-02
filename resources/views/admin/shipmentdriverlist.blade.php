<div class="form-group driverdiv">
  <label for="cars" class="control-label col-lg-2">Driver :</label>
    <div class="col-lg-10">
      <select class="form-control mydriver" name="driver_id" id="mydriver" >
        <option value="">Choose Driver</option>
          @foreach($drivers as $value)
            @if(old('driver_id') == $value->id)
              <option selected="selected" data-number="{{ $value->truck_no }}" value="{{ $value->id }}">{{ $value->name }}</option>
            @else  
              <option data-number="{{ $value->truck_no }}"  value="{{ $value->id }}">{{ $value->name }}</option>
            @endif
          @endforeach
      </select>
    @error('driver_id')
      <span class="text-danger"> {{ $message }} </span>
    @enderror
  </div>
</div>
