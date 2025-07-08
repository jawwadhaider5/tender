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
                                @can('user-list')
                                <div class="">
                                    <a class="btn btn-info text-dark btn-sm  btn-rounded mdi mdi-arrow-left-bold" title="Back" href="{{ route('users.index') }} "></a>
                                </div>
                                @endcan
                            </div>
                        </div>
                        {!! Form::model($users, ['method' => 'PATCH', 'enctype' => 'multipart/form-data','route' => ['users.update', $userdetails->user_id]]) !!}

                        <div class="row">
                            <div class="col-md-4">

                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Name:</label>
                                    <div class="col-sm-7">
                                        {!! Form::text('name',null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                        @if ($errors->has('name'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('name') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Email:</label>
                                    <div class="col-sm-7">
                                        {!! Form::text('email',null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                                        @if ($errors->has('email'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('email') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">
                                        {!! Form::label('gender', "Gender" . ':') !!}</label>
                                    <div class="col-sm-7">
                                        <div class="form-group row ">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        @if (!empty($userdetails))

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
                                                        @if (!empty($userdetails))

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
                                                        @if (!empty($userdetails))
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

                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">
                                        {!! Form::label('phone_no_one', "Phone#1" . ':') !!}</label>
                                    <div class="col-sm-7">
                                        <!-- if (!empty($userdetails))  -->
                                        {!! Form::text('phone_no_one', $userdetails->phone_no_one, ['class' => 'form-control',
                                        'placeholder' => "Phone No One"]); !!}
                                        <!-- else 
                                        {!! Form::text('phone_no_one',null, ['class' => 'form-control',
                                        'placeholder' => "Phone No One"]); !!} 
                                        endif  -->
                                        @if ($errors->has('phone_no_one'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('phone_no_one') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required">
                                        {!! Form::label('address_one', "Address#1" . ':') !!} </label>
                                    <div class="col-sm-7">
                                        {!! Form::text('address_one',$userdetails->address_one, ['class' => 'form-control',
                                        'placeholder' => "Address One"]); !!}
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
                                        {!! Form::label('image', "Image". ':') !!}
                                    </label>
                                    <div class="col-sm-7">
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
                                <div class="form-group row mb-1">
                                    <div class="col-sm-3 col-form-label">
                                        {!! Form::label('date_of_birth', "Date of Birth" . ':') !!}
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="datepicker date input-group p-0 shadow-sm ">
                                            {!! Form::text('date_of_birth',$userdetails->date_of_birth, ['class' => 'form-control ', 'placeholder' => "Select date of birth", 'id'=>"reservationDate"]); !!}
                                        </div>
                                    </div>
                                </div>

                            </div>



                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">Role:</label>
                                    <div class="col-sm-7">
                                        {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control select2')) !!}
                                        @if ($errors->has('roles'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('roles') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>


                        <button type="submit" class="btn btn-info text-dark me-2">Update</button>

                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}



        {!! Form::model($user, [
        'method' => 'PUT',
        'enctype' => 'multipart/form-data',
        'route' => ['userprofile.password',$userdetails->user_id],
        ]) !!}
        <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="space">
                                <div class="card-title">
                                    <h4>Edit Password</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group row mb-1">

                                    <label class="col-sm-3 col-form-label required"> Password </label>
                                    <div class="col-sm-7">
                                        {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}

                                        @if ($errors->has('password'))
                                        <div class="alert  alert-danger mt-3">{{ $errors->first('password') }}
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label required"> Confirm-P</label>
                                    <div class="col-sm-7">
                                        {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info text-dark me-2">Update</button>

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