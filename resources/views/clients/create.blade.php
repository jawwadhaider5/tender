@extends('layouts.admin')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('clients-by-city') }}" class="btn btn-secondary btn-sm d-inline-flex align-items-center mb-3">
                    <i class="mdi mdi-arrow-left me-1"></i>
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Create New Client</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {!! Form::open(['url' => action('ClientController@store'),'method' => 'POST', 'id' => 'client_add_form', 'enctype' => 'multipart/form-data']) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">Company Name</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('company_name', null, ['placeholder' => 'Company Name','class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Web Address</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('web_address', null, ['placeholder' => 'Website URL','class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">Address</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('address', null, ['placeholder' => 'Address','class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">Contact Number</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('contact_no', null, ['placeholder' => 'Phone Number','class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Email Address</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('email', null, ['placeholder' => 'Email Address','class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">City</label>
                                    <div class="col-sm-8">
                                        <select name="city_id" id="citysearch" class="form-control">
                                            <option value="">Select a city</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Group</label>
                                    <div class="col-sm-8">
                                        <select name="group_id" id="groupsearch" class="form-control">
                                            <option value="">Select a group</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
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
@endsection

@section('javascript')
<script>
$(document).ready(function() {
    $('#citysearch').select2({
        width: '100%',
        placeholder: 'Select a city',
        allowClear: true
    });
    $('#groupsearch').select2({
        width: '100%',
        placeholder: 'Select a group',
        allowClear: true
    });
});
</script>
@endsection 