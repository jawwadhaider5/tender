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
                                    <h4>Edit Role</h4>
                                </div>
                                @can('role-list')
                                <div class="">
                                    <a class="btn btn-info text-dark btn-sm  btn-rounded mdi mdi-arrow-left-bold" title="Back" href="{{ route('roles.index') }} "></a>
                                </div>
                                @endcan
                            </div>
                        </div>
                        {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}

                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Name</label>
                                    <div class="">
                                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">Peremission</label> 
                                    <div class="col-md-12"> 
                                        <div class="row">
                                        @foreach($permission as $value)
                                        <div class="col-md-3">
                                        <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            {{ $value->name }}</label>
                                        <br />
                                        </div>
                                        @endforeach
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>


                        <button type="submit" class="btn btn-info text-dark me-2">Update</button></button>

                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>



@endsection