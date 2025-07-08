<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Person <strong>({{$person->name}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">


            {!! Form::open(['url' => action('ClientController@update_person',[$person->id]),'method' => 'POST',
            'id' => 'edit_person_form']) !!}
            @csrf

            <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label ">Profile Image</label>
                                <div class="col-sm-8"> 
                                    <input type="file" name="image" placeholder="Select Image" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Name</label>
                        <div class="col-sm-8">
                            {!! Form::text('name',$person->name, array('placeholder' => 'Name','class' => 'form-control')) !!}

                            @if ($errors->has('name'))
                            <div class="alert  alert-danger mt-3">{{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">position</label>
                        <div class="col-sm-8">
                            <select name="position_id" id="" class="form-control" required>
                                <option value="">Select a position</option>
                                @foreach($positions as $position)
                                <option value="{{ $position->id }}" @if($position->id == $person->position_id) selected @endif>{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Contact Number</label>
                        <div class="col-sm-8">
                            {!! Form::text('contact_no', $person->contact_no, array('placeholder' => 'Contact Number','class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Email Address</label>
                        <div class="col-sm-8">
                            {!! Form::text('email',$person->email, array('placeholder' => 'Email Address','class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label ">Linkedin Profile</label>
                                <div class="col-sm-8">
                                    {!! Form::text('linkedin', $person->linkedin, array('placeholder' => 'Linkedin Profile Link','class' => 'form-control')) !!}

                                    @if ($errors->has('linkedin'))
                                    <div class="alert  alert-danger mt-3">Linkedin Profile Link is required!</div>
                                    @endif

                                </div>
                            </div>
                        </div> 
                    </div>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="col-md-8 offset-md-3"> 
                        <button type="submit" value="submit" class="btn btn-primary me-2 float-end" id="updatePersonBtn">Update</button>
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