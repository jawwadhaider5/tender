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

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="space">
                                <div class="card-title">
                                    <h4>Edit User</h4>
                                </div>
                            </div>
                        </div>


                        {!! Form::model($users, ['method' => 'PATCH', 'enctype' => 'multipart/form-data','route' => ['users.update', $users->id]]) !!}

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">
                                        {!! Form::label('image', "Image". '') !!}
                                    </label>
                                    <div class="col-sm-6">
                                        <div class="image-upload form-group clearfix ">
                                            <input id="file-input" name="image" type="file" onchange="previewFile(this) " />
                                            <label for="file-input">
                                                <div class="upload-icon image ">

                                                    @if (!empty($userdetails))
                                                    <img id="image" src="/{{$userdetails->image}}">

                                                    @else
                                                    <img id="image" src="/">

                                                    @endif




                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">
                                        {!! Form::label('business_detail_id' , "Business" . '') !!}

                                    </label>
                                    <div class="col-sm-6">
                                        @if (!empty($userdetails))
                                        {!! Form::select('business_detail_id',$business,$userdetails->business_detail_id, ['placeholder' => 'Select Business','style' => 'width: 100%' , 'class' => 'select2 form-control livesearch ']) !!}

                                        @else

                                        {!! Form::select('business_detail_id',$business,null, ['placeholder' => 'Select Business','style' => 'width: 100%' , 'class' => 'select2 form-control livesearch ']) !!}


                                        @endif
                                        @if ($errors->has('business_detail_id'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('business_detail_id') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-6">
                                        {!! Form::text('name',null, array('placeholder' => 'Name','class' => 'form-control')) !!}

                                        @if ($errors->has('name'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('name') }}
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Email</label>


                                    <div class="col-sm-6">
                                        {!! Form::text('email',null, array('placeholder' => 'Email','class' => 'form-control')) !!}

                                        @if ($errors->has('email'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('email') }}
                                        </div>
                                        @endif

                                    </div>


                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <label class="col-sm-3 col-form-label"> Password </label>
                                    <div class="col-sm-6">
                                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}

                                        @if ($errors->has('password'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('password') }}
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label"> Confirm-P</label>
                                    <div class="col-sm-6">
                                        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <label class="col-sm-3 col-form-label">Role</label>

                                    <div class="col-sm-6">
                                        {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control select2')) !!}

                                        @if ($errors->has('roles'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('roles') }}
                                        </div>
                                        @endif


                                    </div>

                                </div>
                            </div>



                        </div>


                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">
                                        {!! Form::label('gender', "Gender" . ':') !!}
                                    </label>




                                    <div class="col-sm-6">

                                        <div class="form-group row ">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        @if (!empty($userdetail))

                                                        <input type="radio" class="form-check-input" name="gender" id="membershipRadios1" @if ($userdetails->gender == 'male') checked @endif value="male">
                                                        M
                                                        @else
                                                        <input type="radio" class="form-check-input" name="gender" id="membershipRadios1" value="male">M

                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        @if (!empty($userdetail))

                                                        <input type="radio" class="form-check-input" name="gender" id="membershipRadios2" @if ($userdetails->gender == 'female') checked @endif value="female">
                                                        F
                                                        @else
                                                        <input type="radio" class="form-check-input" name="gender" id="membershipRadios1" value="female">
                                                        F
                                                        @endif

                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        @if (!empty($userdetail))
                                                        <input type="radio" class="form-check-input" name="gender" id="membershipRadios3" @if ($userdetails->gender == 'other') checked @endif value="other">
                                                        O
                                                        @else

                                                        <input type="radio" class="form-check-input" name="gender" id="membershipRadios3" value="other"> O

                                                        @endif

                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($errors->has('gender'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('gender') }}
                                        </div>
                                        @endif

                                    </div>


                                </div>
                            </div>




                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <div class="col-sm-3 col-form-label required">

                                        {!! Form::label('date_of_birth', "D B" . ':') !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker"> --}}
                                        <div class="datepicker date input-group p-0 shadow-sm ">
                                            {{-- <span class="input-group-addon input-group-prepend border-right">
                                                <span class="icon-calendar input-group-text calendar-icon"></span>
                                              </span> --}}
                                            @if (!empty($userdetails))

                                            {!! Form::text('date_of_birth',$userdetails->date_of_birth, ['class' => 'form-control ', 'placeholder' => "Select date of birth", 'id'=>"reservationDate"]); !!}

                                            @else

                                            {!! Form::text('date_of_birth',null, ['class' => 'form-control ', 'placeholder' => "Select date of birth", 'id'=>"reservationDate"]); !!}


                                            @endif
                                        </div>
                                        {{-- </div> --}}


                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('cnic_number' , " CNIC " . ':') !!}

                                    </label>
                                    <div class="col-sm-6">

                                        @if (!empty($userdetails))
                                        {!! Form::text('cnic_number',$userdetails->cnic_number , ['class' => 'form-control',
                                        'placeholder' => "CNIC Number"]); !!}

                                        @else

                                        {!! Form::text('cnic_number',null, ['class' => 'form-control',
                                        'placeholder' => "CNIC Number"]); !!}

                                        @endif


                                    </div>
                                </div>
                            </div>






                        </div>


                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('passport_number', " Passport " . ':') !!}

                                    </label>
                                    <div class="col-sm-6">

                                        @if (!empty($userdetails))


                                        {!! Form::text('passport_number',$userdetails->passport_number, ['class' => 'form-control',
                                        'placeholder' => "Passport Number"]); !!}

                                        @else


                                        {!! Form::text('passport_number',null, ['class' => 'form-control',
                                        'placeholder' => "Passport Number"]); !!}

                                        @endif



                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">
                                        {!! Form::label('phone_no_one', "P-One" . ':') !!}
                                    </label>


                                    <div class="col-sm-6">

                                        @if (!empty($userdetails))

                                        {!! Form::text('phone_no_one', $userdetails->phone_no_one, ['class' => 'form-control',
                                        'placeholder' => "Phone No One"]); !!}

                                        @else

                                        {!! Form::text('phone_no_one',null, ['class' => 'form-control',
                                        'placeholder' => "Phone No One"]); !!}

                                        @endif

                                        @if ($errors->has('phone_no_one'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('phone_no_one') }}
                                        </div>
                                        @endif



                                    </div>


                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <div class="col-sm-3 col-form-label required">

                                        {!! Form::label('phone_no_two', "P-Two" . ':') !!}
                                    </div>

                                    <div class="col-sm-6">
                                        @if (!empty($userdetails))
                                        {!! Form::text('phone_no_two',$userdetails->phone_no_two, ['class' => 'form-control',
                                        'placeholder' => "Phone No Two"]); !!}

                                        @else

                                        {!! Form::text('phone_no_two',null, ['class' => 'form-control',
                                        'placeholder' => "Phone No Two"]); !!}

                                        @endif

                                    </div>
                                </div>
                            </div>


                        </div>


                        <div class="row">


                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('address_one', "Ad-One" . ':') !!}

                                    </label>
                                    <div class="col-sm-6">

                                        @if (!empty($userdetails))

                                        {!! Form::text('address_one',$userdetails->address_one, ['class' => 'form-control',
                                        'placeholder' => "Address One"]); !!}

                                        @else

                                        {!! Form::text('address_one',null, ['class' => 'form-control',
                                        'placeholder' => "Address One"]); !!}

                                        @endif

                                        @if ($errors->has('address_one'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('address_one') }}
                                        </div>
                                        @endif


                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('address_two', "Ad-Two" . ':') !!}

                                    </label>
                                    <div class="col-sm-6">
                                        @if (!empty($userdetails))

                                        {!! Form::text('address_two',$userdetails->address_two, ['class' => 'form-control',
                                        'placeholder' => "Address Two"]); !!}

                                        @else

                                        {!! Form::text('address_two',null, ['class' => 'form-control',
                                        'placeholder' => "Address Two"]); !!}

                                        @endif


                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('account_type', "A-Type" . ':') !!}

                                    </label>

                                    <div class="col-sm-6">
                                        @if (!empty($userdetails))

                                        {!! Form::text('account_type',$userdetails->account_type, ['class' => 'form-control',
                                        'placeholder' => "Account Type"]); !!}

                                        @else

                                        {!! Form::text('account_type',null, ['class' => 'form-control',
                                        'placeholder' => "Account Type"]); !!}

                                        @endif




                                        @if ($errors->has('account_type'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('account_type') }}
                                        </div>
                                        @endif




                                    </div>

                                </div>
                            </div>





                        </div>


                        <div class="row">


                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('joining_date', "J-Type" . ':') !!}
                                    </label>

                                    <div class="col-sm-6">

                                        <div class="datepicker date input-group p-0 shadow-sm ">
                                            @if (!empty($userdetails))
                                            {!! Form::text('joining_date',$userdetails->joining_date, ['class' => 'form-control ', 'placeholder' => "Select joining date", 'id'=>"reservationDateOne"]); !!}
                                            @else
                                            {!! Form::text('joining_date',null, ['class' => 'form-control ', 'placeholder' => "Select joining date", 'id'=>"reservationDateOne"]); !!}
                                            @endif
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('leaving_date', "L-Date" . ':') !!}
                                    </label>

                                    <div class="col-sm-6">


                                        <div class="datepicker date input-group p-0 shadow-sm ">
                                            @if (!empty($userdetails))
                                            {!! Form::text('leaving_date',$userdetails->leaving_date, ['class' => 'form-control ', 'placeholder' => "Select leaving date", 'id'=>"reservationDateTwo"]); !!}
                                            @else
                                            {!! Form::text('leaving_date',null, ['class' => 'form-control ', 'placeholder' => "Select leaving date", 'id'=>"reservationDateTwo"]); !!}
                                            @endif
                                        </div>

                                    </div>

                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <label class="col-sm-3 col-form-label required">

                                        {!! Form::label('salary_per_month', "Salary" . ':') !!}
                                    </label>

                                    <div class="col-sm-6">
                                        @if (!empty($userdetails))

                                        {!! Form::text('salary_per_month',$userdetails->salary_per_month, ['class' => 'form-control',
                                        'placeholder' => "Salary Per Month"]); !!}

                                        @else


                                        {!! Form::text('salary_per_month',null, ['class' => 'form-control',
                                        'placeholder' => "Salary Per Month"]); !!}

                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info me-2">Update</button>

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