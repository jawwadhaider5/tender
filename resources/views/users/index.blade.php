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
                  <h4>Users Management</h4>
                </div>
                @can('user-create')
                <div class="">
                  <a class="btn btn-info text-dark btn-sm  btn-rounded  mdi mdi-account-multiple-plus" title="Add New User" href="{{ route('users.create')}} "></a>
                </div>
                @endcan
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover" id="user_table">
                <thead>
                  <tr class="bg-info text-dark">
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Name</th>
                    <th style="width: 30%;">Email</th>
                    <th style="width: 20%;">Roles</th>
                    <th style="width: 10%;">Action</th>
                  </tr>
                </thead>
                <tbody id="content">
                  @foreach ($data as $key => $user)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                      @if(!empty($user->getRoleNames()))
                      @foreach($user->getRoleNames() as $v)
                      <label class="badge badge-success">{{ $v }}</label>
                      @endforeach
                      @endif
                    </td>
                    <td>
                      @can('user-view')
                      <a class="btn btn-info btn-sm mdi mdi-eye view-users" title="Show" href="{{ route('users.show',$user->id) }}"></a>
                      @endcan
                      @can('user-edit')
                      <a class="btn btn-dark btn-sm mdi mdi-table-edit" title="Edit" href="{{ route('users.edit',$user->id) }}"></a>
                      @endcan
                      @can('user-delete')
                      <a class="btn btn-danger btn-sm mdi mdi-delete delete-users" title="Delete" href="{{action('UserController@destroy',$user->id)}}"></a>
                      @endcan
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="modal fade" id="view_users_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>
      </div>






    </div>
  </div>
  <!-- content-wrapper ends -->

</div>
<!-- main-panel ends --> 

@endsection

@section('javascript')
<script src="{{ asset('js/users.js') }}"></script>
@endsection