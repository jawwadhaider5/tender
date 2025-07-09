@extends('layouts.admin')

@section('content')
<div class="main-panel">
        <div class="content-wrapper">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('tenders-by-city') }}" class="btn btn-secondary btn-sm d-inline-flex align-items-center mb-3">
                    <i class="mdi mdi-arrow-left me-1"></i>
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Create New Tender</h4>
                        @if (Session::get('success'))
                            <div class="alert alert-{{ Session::get('success') ? 'success' : 'danger' }}">
                                <p>{{ Session::get('success') }}</p>
                            </div>
                        @endif
                        {!! Form::open(['url' => action('TenderController@store'),'method' => 'POST',
                        'id' => 'tender_add_form','class' => '', 'enctype' => 'multipart/form-data']) !!}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">City</label>
                                    <div class="col-sm-8">
                                        <select name="city_id" id="citysearch" class="form-control" required>
                                            <option value="">Select a city</option>
                                        </select>
                                        @if ($errors->has('city_id'))
                                        <div class="alert alert-danger mt-3">{{ $errors->first('city_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">Select Client</label>
                                    <div class="col-sm-8">
                                        <select name="client_id" id="client_id" class="form-control" required>
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
                                    <label class="col-sm-3 col-form-label required">Tender Number</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('tender_number', null, array('placeholder' => 'Tender Number','class' => 'form-control', 'required')) !!}
                                        @if ($errors->has('tender_number'))
                                        <div class="alert alert-danger mt-3">{{ $errors->first('tender_number') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Year</label>
                                    <div class="col-sm-8">
                                        {!! Form::date('year', null, array('placeholder' => 'Year','class' => 'form-control', 'id'=>'year')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">Description</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" rows="3" placeholder="Enter tender description" required></textarea>
                                        @if ($errors->has('description'))
                                        <div class="alert alert-danger mt-3">{{ $errors->first('description') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">Status</label>
                                    <div class="col-sm-8">
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="">Select Status</option>
                                            <option value="approved">Approved</option>
                                            <option value="not approved">Not Approved</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                        @if ($errors->has('status'))
                                        <div class="alert alert-danger mt-3">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-4">
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
                                    <label class="col-sm-3 col-form-label">Start Date</label>
                                    <div class="col-sm-8">
                                        {!! Form::date('start_date', null, array('placeholder' => 'Start Date','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Close Date</label>
                                    <div class="col-sm-8">
                                        {!! Form::date('close_date', null, array('placeholder' => 'Close Date','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Announce Date</label>
                                    <div class="col-sm-8">
                                        {!! Form::date('announce_date', null, array('placeholder' => 'Announce Date','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Submit Date</label>
                                    <div class="col-sm-8">
                                        {!! Form::date('submit_date', null, array('placeholder' => 'Submit Date','class' => 'form-control', 'id'=>'submit_date')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Time Period</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('period', null, array('placeholder' => 'Time Period','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-4">
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
                                        {!! Form::text('amount', null, array('placeholder' => 'Amount','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-4">
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

<!-- Add City Modal -->
<div class="modal fade" id="add_city_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New City</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => action('CityController@create_ajax'),'method' => 'POST', 'id' => 'add_city_form']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label required">City Name</label>
                            <div class="col-sm-8">
                                {!! Form::text('name', null, array('placeholder' => 'City Name','class' => 'form-control' , 'required', 'id'=>'city_name_box')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label required">City Code</label>
                            <div class="col-sm-8">
                                {!! Form::text('code', null, array('placeholder' => 'City Code','class' => 'form-control' , 'required', 'id'=>'city_code_box')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" value="submit" class="btn btn-primary submit_city_form me-2">Submit</button>
                    </div>
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
    
    // City search functionality with Select2
    $('#citysearch').select2({
        width: '100%',
        ajax: {
            url: '/cities/search',
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
        placeholder: "Enter city name",
        language: {
            noResults: function () {
                return 'No city found!  <button type="button" class="btn btn-sm btn-primary mt-2" id="create_city">+ Add</button>';
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    // Handle city creation button click
    $(document).on('click', '#create_city', function () { 
        $('#add_city_modal').modal('show'); 
        $("#city_name_box").val(""); 
        $("#city_code_box").val(""); 
    });

    // Handle city creation form submission
    $('#add_city_form').submit(function (e) { 
        e.preventDefault(); 
        var data = $("#add_city_form").serialize();  
        
        $.ajax({
            method: "POST",
            url: $("#add_city_form").attr("action"), 
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {
                    toastr.success("City Created successfully");
                    $('#add_city_modal').modal('hide');  
                    
                    // Add new city to select2
                    var newOption = new Option(result.city.name, result.city.id, true, true);
                    $('#citysearch').append(newOption).trigger('change');
                } else {
                    toastr.error("Something went wrong!");
                    $('#add_city_modal').modal('hide'); 
                }
            },
            error: function(xhr, status, error) {
                toastr.error("Error creating city: " + error);
                $('#add_city_modal').modal('hide'); 
            }
        }); 
    });

    // Handle tender form submission
    $("form#tender_add_form").submit(function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.text('Creating...').prop('disabled', true);
        
        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    toastr.success(result.message);
                    // Redirect back to tenders by city page
                    window.location.href = '/tenders-by-city';
                } else {
                    toastr.error(result.message);
                    submitBtn.text(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                toastr.error("Error creating tender: " + error);
                submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });
});
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-group {
    margin-bottom: 1rem;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #495057;
}
</style>
@endsection 