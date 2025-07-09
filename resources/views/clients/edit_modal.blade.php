<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Client <strong>({{$client->company_name}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">


            {!! Form::open(['url' => route('clients.update', $client->id),'method' => 'PUT',
            'id' => 'edit_client_form','class' => '', 'enctype' => 'multipart/form-data']) !!}
            <input type="hidden" id="client_id" value="{{ $client->id }}">


            <div class="row"> 
            <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Company Name</label>
                        <div class="col-sm-8">
                            {!! Form::text('company_name',$client->company_name, array('placeholder' => 'Company name','class' => 'form-control')) !!}

                            @if ($errors->has('company_name'))
                            <div class="alert  alert-danger mt-3">{{ $errors->first('company_name') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Website URL</label>
                        <div class="col-sm-8">
                            {!! Form::text('web_address',$client->web_address, array('placeholder' => 'Website URL','class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

            <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Address</label>
                        <div class="col-sm-8">
                            {!! Form::text('address',$client->address, array('placeholder' => 'Address','class' => 'form-control')) !!}

                            @if ($errors->has('address'))
                            <div class="alert  alert-danger mt-3">{{ $errors->first('address') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Contact Number</label>
                        <div class="col-sm-8">
                            {!! Form::text('contact_no', $client->contact_no, array('placeholder' => 'Contact Number','class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Email Address</label>
                        <div class="col-sm-8">
                            {!! Form::text('email',$client->email, array('placeholder' => 'Email Address','class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">City</label>
                        <div class="col-sm-8">
                            <select name="city_id" id="" class="form-control" required>
                                <option value="">Select a city</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}" @if($client->city_id == $city->id) selected @endif>{{ $city->name }}</option>
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

                  <select name="group_id" id="" class="form-control" required>
                    <option value="">Select a group</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" @if($client->group_id == $group->id) selected @endif>{{ $group->name }}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('group_id'))
                  <div class="alert  alert-danger mt-3">{{ $errors->first('group_id') }}
                  </div>
                  @endif
                </div>
              </div>
            </div> 
            </div>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="col-md-8 offset-md-3">
                        <input type="hidden" name="submit_type" id="submit_type">
                        <button type="submit" value="submit" class="btn btn-primary submit_client_form me-2 float-end">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
{!! Form::close() !!}

</div>
</div>
</div>