<div class=" modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Position <strong>({{$position->name}})</strong></h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('PositionController@update', [$position->id]), 'method' => 'POST', 'id' => 'edit_position_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Position Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', $position->name, array('placeholder' => 'Position Name','class' => 'form-control')) !!}
              </div>
            </div>
          </div>
          
          <button type="submit" value="submit" class="btn btn-primary submit_position_form me-2" id="updateBtn">Update</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>