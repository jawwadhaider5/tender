@extends('layouts.admin')
@section('content')
<!-- partial -->
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
                  <h4>Role Management</h4>
                </div>
                @can('role-create')
                <div class="">
                  <a class="btn btn-info text-dark  btn-rounded  btn-sm  mdi mdi mdi-plus-box" title="Add New Role" href="{{ route('roles.create')}} "></a>
                </div>
                @endcan
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr class="bg-info text-dark">
                    <th style="width: 5%;">#</th>
                    <th style="width: 80%;">Name</th>
                    <th style="width: 15%;" width="280px">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($roles as $key => $role)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                      @can('role-view')
                      <a class="btn btn-info text-dark btn-sm mdi mdi-eye view-roles" title="Show" href="{{ route('roles.show',$role->id) }}"></a>
                      @endcan
                      @can('role-edit')
                      <a class="btn btn-dark btn-sm mdi mdi-table-edit" title="Edit" href="{{ route('roles.edit',$role->id) }}"></a>
                      @endcan 
                      @can('role-delete')
                      {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                      {!! Form::button('', ['class' => 'btn btn-danger btn-sm mdi mdi-delete','title'=> 'Delete', 'type' => 'submit']) !!}
                      {!! Form::close() !!}
                      @endcan
                    </td>
                  </tr>
                  @endforeach

                </tbody>
              </table>


              <!-- {!! $roles->render() !!} -->

            </div>
          </div>
        </div>

        <div class="modal fade" id="view_roles_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>
      </div>
    </div>
  </div>
  <!-- main-panel ends -->
</div>
@endsection
@section('javascript')
<script src="{{ asset('js/roles.js') }}"></script>
@endsection