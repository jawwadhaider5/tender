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
                  <h4>City Management</h4>
                </div>
                @can('city')
                <div class="">
                  <a class="view-city btn btn-info text-dark btn-sm  btn-rounded  mdi mdi mdi-plus-box" title="Add New City" href="{{ route('cities.create')}} "></a>
                </div>
                @endcan
              </div>
            </div>
            <div class="table-responsive">
              <table id="city_table" class="table table-hover">
                <thead>
                  <tr class="bg-info text-dark">
                    <th>#</th>
                    <th>City Name</th>
                    <th>City Code</th>
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


<div class="modal fade" id="view_city_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New City</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('CityController@store'),'method' => 'POST', 'id' => 'add_city_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">City Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'City Name','class' => 'form-control' , 'required')) !!}
              </div>
            </div>
          </div> 
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">City Code</label>
              <div class="col-sm-9">
                {!! Form::text('code', null, array('placeholder' => 'City Code','class' => 'form-control' , 'required')) !!}
              </div>
            </div>
          </div> 
          <button type="submit" value="submit" class="btn btn-info text-dark submit_city_form me-2">Submit</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="edit_city_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/cities.js') }}"></script>
@endsection