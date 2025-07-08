@extends('layouts.admin')

@section('content')

<style>
.highlight-row {
    background-color: #fff3cd;
    animation: highlight 2s ease-out;
}

@keyframes highlight {
    0% {
        background-color: #fff3cd;
    }
    100% {
        background-color: transparent;
    }
}

/* Add this to ensure the highlight is visible */
#future_client_table tbody tr.highlight-row td {
    background-color: #fff3cd;
}
</style>

<div class="main-panel">
  <div class="content-wrapper">

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
      <p>{{ $message }}</p>
    </div>
    @endif

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <div class="row">
              <div class="space">
                <div class="card-title">
                  <h4>Future Client Management</h4>
                </div>

                @can('future-client')
                <div class="">
                  <a class="create-future-client btn btn-primary btn-sm  btn-rounded" title="Add New Future Client" href="{{ route('future-clients.create')}} "><i class="mdi mdi mdi-plus-box"></i></a>
                </div>
                @endcan

              </div>
            </div>


            <div class="table-responsive">
              <table id="future_client_table" id="example1" class="table table-hover table-bordered">
                <thead>
                  <tr class="bg-success text-white">
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Comments</th>
                    <th>Responds</th>
                    <th>Files</th>
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

  <div class="modal fade" id="edit_future_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="comment_future_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="respond_future_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="file_future_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>


  <div class="modal fade" id="future_client_create_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class=" modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Future Client</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">


          {!! Form::open(['url' => action('FutureClientController@store'),'method' => 'POST',
          'id' => 'future_client_add_form','class' => '', 'enctype' => 'multipart/form-data']) !!}

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label required">Select Client</label>
                <div class="col-sm-8">

                  <select name="client_id" id="client_id" class="form-control">
                    <option value="">Select a Client</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                    @endforeach
                  </select>

                  @if ($errors->has('client_id'))
                  <div class="alert  alert-danger mt-3">{{ $errors->first('client_id') }}
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
 

          <div class="row">
          <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label required">Tender Type</label>
                <div class="col-sm-8">

                  <select name="tender_type_id" id="tender_type_id" class="form-control" required>
                    <option value="">Select a tender type</option>
                    <!-- @foreach($tender_types as $tender_type)
                    <option value="{{ $tender_type->id }}">{{ $tender_type->name }}</option>
                    @endforeach -->
                  </select>
                  @if ($errors->has('tender_type_id'))
                  <div class="alert  alert-danger mt-3">{{ $errors->first('tender_type_id') }}
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label">Assigned No#</label>
                <div class="col-sm-8">
                  <select name="assigned_number" class="form-control">
                    <option value="">Assigned Number</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>

                  </select>
                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label required">Description</label>
                <div class="col-sm-8">
                  <textarea name="description" id="" class="form-control" rows="3" required></textarea>
                </div>
              </div>
            </div>
          </div> 
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Start Date</label>
                <div class="col-sm-8">
                  {!! Form::date('start_date', null, array('placeholder' => 'Start Date','class' => 'form-control')) !!}

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Coming Date</label>
                <div class="col-sm-8">
                  {!! Form::date('coming_date', null, array('placeholder' => 'Coming Date','class' => 'form-control')) !!}

                </div>
              </div>
            </div> 
          </div> 
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Time Period</label>
                <div class="col-sm-8">
                  {!! Form::text('period', null, array('placeholder' => 'Time Period','class' => 'form-control')) !!}

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Period Term</label>
                <div class="col-sm-8">
                  <select name="term" id="term" class="form-control">
                    <option value="">Select a term</option>
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                    <option value="years">Years</option>
                  </select>

                </div>
              </div>
            </div> 
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Amount</label>
                <div class="col-sm-8">
                  {!! Form::text('amount', null, array('placeholder' => 'Amount','class' => 'form-control')) !!}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Select User</label>
                <div class="col-sm-8">
                  <select name="user_id" id="user_id" class="form-control">
                    <option value="">Select User</option>
                    {!! $userss !!}
                  </select>

                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 offset-md-6">
              <div class="col-md-8 offset-md-3">
                <input type="hidden" name="submit_type" id="submit_type">
                <button type="submit" value="submit" class="btn btn-primary  me-2 float-end">Submit</button>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="add_tender_type_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Tender Type</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('TenderTypeController@create_ajax'),'method' => 'POST', 'id' => 'add_tender_type_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Tender Type Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'Tender Type Name','class' => 'form-control' , 'required', 'id'=>'tender_type_name_box')) !!}
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




</div>
<!-- main-panel ends -->

@endsection

@section('javascript')
<script src="{{ asset('js/futureclients.js') }}"></script>
@endsection