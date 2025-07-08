@extends('layouts.admin')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12  grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <div class="row">
                                <div class="space">
                                    <div class="card-title">
                                        <h4>Create User</h4>
                                    </div>
                                    @can('user-list')
                                    <div class="">
                                        <a class="btn btn-info text-dark btn-sm  btn-rounded mdi mdi-arrow-left-bold" title="Back" href="{{ route('users.index') }} "></a>
                                    </div>
                                    @endcan
                                </div>
                            </div>

                            {!! Form::open(array('route' => 'users.store','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}


                            <div class="row ">
                                <div class="col-md-4">
                                     

                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">Name:</label>
                                        <div class="col-sm-7">
                                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                            @if ($errors->has('name'))
                                            <div class="alert  alert-danger ">{{ $errors->first('name') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">Email:</label>
                                        <div class="col-sm-7">
                                            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}

                                            @if ($errors->has('email'))
                                            <div class="alert  alert-danger ">{{ $errors->first('email') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">
                                            {!! Form::label('gender', "Gender" . ':') !!}
                                        </label>
                                        <div class="col-sm-7">
                                            <div class="form-group row ">
                                                <div class="col-sm-3 col-form-label">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="gender" id="membershipRadios1" value="male" title="Male">
                                                            M
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="gender" id="membershipRadios2" value="female" title="Female">
                                                            F
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="gender" id="membershipRadios3" value="other" title="Other">
                                                            O
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('gender'))
                                            <div class="alert  alert-danger ">{{ $errors->first('gender') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">
                                            {!! Form::label('phone_no_one', "Phone#1" . ':') !!}
                                        </label>
                                        <div class="col-sm-7">
                                            {!! Form::text('phone_no_one', '' , ['class' => 'form-control',
                                            'placeholder' => "Phone No One"]); !!}
                                            @if ($errors->has('phone_no_one'))
                                            <div class="alert  alert-danger ">{{ $errors->first('phone_no_one') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div> 
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">
                                            {!! Form::label('address_one', "Address#1" . ':') !!} </label>
                                        <div class="col-sm-7">
                                            {!! Form::text('address_one','' , ['class' => 'form-control',
                                            'placeholder' => "Address One"]); !!}
                                            @if ($errors->has('address_one'))
                                            <div class="alert  alert-danger ">{{ $errors->first('address_one') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label ">
                                            {!! Form::label('image', "Image". '') !!}
                                        </label>
                                        <div class="col-sm-6">
                                            <div class="image-upload form-group clearfix ">
                                                <input id="file-input" name="image" type="file" onchange="previewFile(this) " />
                                                <label for="file-input">
                                                    <div class="upload-icon image ">
                                                        <img id="image" class="" src="/open/images/fixed-images/user.jpg">
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="form-group row mb-1">
                                        <div class="col-sm-3 col-form-label ">
                                            {!! Form::label('date_of_birth', "Date of Birth" . ':') !!}
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group p-0 shadow-sm ">
                                                {!! Form::text('date_of_birth',null, ['class' => 'form-control ', 'placeholder' => "Select date of birth", 'id'=>"reservationDate"]); !!}
                                            </div>
                                        </div>
                                    </div> 

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">Role:</label>
                                        <div class="col-sm-7">
                                            {!! Form::select('roles[]', $roles,[], array('class' => 'select2 form-control')) !!}
                                            @if ($errors->has('roles'))
                                            <div class="alert  alert-danger ">{{ $errors->first('roles') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>    
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">Password:</label>
                                        <div class="col-sm-7">
                                            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}

                                            @if ($errors->has('password'))
                                            <div class="alert  alert-danger ">{{ $errors->first('password') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label required">Confirm:</label>
                                        <div class="col-sm-7">
                                            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 offset-md-8" style="text-align: right;">
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-3 col-form-label"></label>
                                        <div class="col-sm-7">
                                            <button type="submit" class="btn btn-info text-dark">Submit</button>
                                        </div>
                                    </div>
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


@endsection



@section('javascript')
<script src="{{ asset('js/users.js') }}"></script>
@endsection