@extends('layouts.admin')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="space">
                <div class="card-title">
                  <h4>Position Management</h4>
                </div>
                @can('position')
                <div class="">
                  <a class="view-position btn btn-info text-dark btn-sm  btn-rounded  mdi mdi mdi-plus-box" title="Add New Position" href="{{ route('positions.create')}} "></a>
                </div>
                @endcan
              </div>
            </div>
            <div class="table-responsive">
              <table id="position_table" class="table table-hover">
                <thead>
                  <tr class="bg-info text-dark">
                    <th>#</th>
                    <th>Position Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->
</div>
<!-- main-panel ends -->


<div class="modal fade" id="view_position_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Position</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('PositionController@store'),'method' => 'POST', 'id' => 'add_position_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Position Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'Position Name','class' => 'form-control' , 'required')) !!}
              </div>
            </div>
          </div> 
          <button type="submit" value="submit" class="btn btn-info text-dark submit_position_form me-2">Submit</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="edit_position_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/positions.js') }}"></script>
@endsection