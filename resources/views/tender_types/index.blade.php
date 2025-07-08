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
                  <h4>Tender Type Management</h4>
                </div>
                @can('tender-type')
                <div class="">
                  <a class="view-tender_type btn btn-info text-dark btn-sm  btn-rounded  mdi mdi mdi-plus-box" title="Add New Tender Type" href="{{ route('tender-types.create')}} "></a>
                </div>
                @endcan
              </div>
            </div>
            <div class="table-responsive">
              <table id="tender_type_table" class="table table-hover">
                <thead>
                  <tr class="bg-info text-dark">
                    <th>#</th>
                    <th>Tender Type Name</th> 
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


<div class="modal fade" id="view_tender_type_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Tender Type</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('TenderTypeController@store'),'method' => 'POST', 'id' => 'add_tender_type_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Tender Type Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'Tender Type Name','class' => 'form-control' , 'required')) !!}
              </div>
            </div>
          </div>   
          <button type="submit" value="submit" class="btn btn-info text-dark submit_tender_type_form me-2">Submit</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="edit_tender_type_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/tendertypes.js') }}"></script>
@endsection