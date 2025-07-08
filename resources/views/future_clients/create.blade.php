@extends('layouts.admin')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('future-clients-by-city') }}" class="btn btn-secondary btn-sm d-inline-flex align-items-center mb-3">
                    <i class="mdi mdi-arrow-left me-1"></i>
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Create Future Client</h4>
                        {!! Form::open(['url' => action('FutureClientController@store'),'method' => 'POST', 'id' => 'future_client_add_form', 'enctype' => 'multipart/form-data']) !!}
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
                                        <div class="alert alert-danger mt-3">{{ $errors->first('client_id') }}</div>
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
                                        </select>
                                        @if ($errors->has('tender_type_id'))
                                        <div class="alert alert-danger mt-3">{{ $errors->first('tender_type_id') }}</div>
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
                                            @for($i=1;$i<=20;$i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
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
                                        <textarea name="description" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Start Date</label>
                                    <div class="col-sm-8">
                                        {!! Form::date('start_date', null, ['placeholder' => 'Start Date', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Coming Date</label>
                                    <div class="col-sm-8">
                                        {!! Form::date('coming_date', null, ['placeholder' => 'Coming Date', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Time Period</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('period', null, ['placeholder' => 'Time Period', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Period Term</label>
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
                                    <label class="col-sm-3 col-form-label">Amount</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('amount', null, ['placeholder' => 'Amount', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Select User</label>
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
                                    <button type="submit" value="submit" class="btn btn-primary me-2 float-end">Submit</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Tender Type Modal (copied from index.blade.php) -->
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
@endsection

@section('javascript')
<script>
$(document).ready(function() {
    $(document).on('click', '#create_tender_type', function () { 
        $('#add_tender_type_modal').modal('show'); 
        $("#tender_type_name_box").val(""); 
    });
    $('#add_tender_type_form').submit(function (e) { 
        e.preventDefault(); 
        var data = $("#add_tender_type_form").serialize();  
        $.ajax({
            method: "POST",
            url: $("#add_tender_type_form").attr("action"), 
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {
                    toastr.success("Tender Type Created successfully");
                    $('#add_tender_type_modal').modal('hide');  
                    var newOption = new Option(result.tender_type.name, result.tender_type.id, true, true);
                    $('#tender_type_id').append(newOption).trigger('change');
                } else {
                    toastr.error("Something went wrong!");
                    $('#add_tender_type_modal').modal('hide'); 
                }
            }
        }); 
    }); 
    $('#tender_type_id').select2({
        width: '100%',
        ajax: {
            url: '/tender-types/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                            value: item.id
                        }
                    })
                };
            }
        },
        minimumInputLength: 2,
        allowClear: true,
        placeholder: "Enter Tender Type name",
        language: {
            noResults: function () {
                return 'No Tender Type found!  <button type="button" class="btn btn-sm btn-primary mt-2" id="create_tender_type">+ Add</button>';
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
});
</script>
@endsection 