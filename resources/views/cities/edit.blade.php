<div class=" modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit City <strong>({{$city->name}})</strong></h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('CityController@update', [$city->id]), 'method' => 'POST', 'id' => 'edit_city_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">City Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', $city->name, array('placeholder' => 'City Name','class' => 'form-control')) !!}
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">City Code</label>
              <div class="col-sm-9">
                {!! Form::text('code', $city->code, array('placeholder' => 'City Code','class' => 'form-control')) !!}
              </div>
            </div>
          </div>
          
          <button type="submit" value="submit" class="btn btn-primary submit_city_form me-2" id="updateBtn">Update</button>
        </div>
        
        {!! Form::close() !!}
      </div>
    </div>
  </div>