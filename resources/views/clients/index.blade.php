@extends('layouts.admin')


@section('content')



<div class="main-panel">
  <div class="content-wrapper">
 
  @if (Session::get('success'))
    <div class="alert alert-{{ Session::get('success') ? 'success' : 'danger' }}">
        <p>{{ Session::get('success') }}</p>
    </div>
@endif

    <!-- @if (Session::get('success') == true)
    <div class="alert alert-success">
      <p>{{ Session::get('message') }}</p>
    </div>
    @endif

    @if (Session::get('success') == false)
    <div class="alert alert-error">
      <p>{{ Session::get('message') }}</p>
    </div>
    @endif -->

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <div class="row">
              <div class="space">
                <div class="card-title">
                  <h4>Client Management</h4> 
                </div>

                @can('client')
                <div class="">
                  <a class="create-client btn btn-primary btn-sm  btn-rounded" title="Add New Client" href="{{ route('clients.create')}} "><i class="mdi mdi mdi-plus-box"></i></a>
                </div>
                @endcan

              </div>
            </div>


            <div class="table-responsive" style="min-height: 500px;">
              <table id="client_table" id="example1" class="table table-hover table-bordered w-100">
                <thead>
                  <tr class="bg-success text-white">
                    <th>#</th>
                    <th>Company Name</th>
                    <!-- <th>Website</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Description</th> -->
                    <th>Comments</th>
                    <th>Responses</th>
                    <th>Files</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div> 
        </div> 
      </div> 
    </div>
  </div>
  <!-- content-wrapper ends -->

  <div class="modal fade" id="edit_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="comment_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="respond_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="file_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>


  <div class="modal fade" id="client_create_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class=" modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create client</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {!! Form::open(['url' => action('ClientController@store'),'method' => 'POST',
          'id' => 'client_add_form','class' => '', 'enctype' => 'multipart/form-data']) !!}
          <div class="row"> 
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label required">Company Name</label>
                <div class="col-sm-8">
                  {!! Form::text('company_name', null, array('placeholder' => 'Company Name','class' => 'form-control', 'required')) !!}

                  @if ($errors->has('company_name'))
                  <div class="alert  alert-danger mt-3">{{ $errors->first('company_name') }}
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Web Address</label>
                <div class="col-sm-8">
                  {!! Form::text('web_address', null, array('placeholder' => 'Website URL','class' => 'form-control', 'required')) !!}

                </div>
              </div>
            </div>

          </div>
 
          <div class="row">
          <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label required">Address</label>
                <div class="col-sm-8">
                  {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control', 'required')) !!} 
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
                  {!! Form::text('contact_no', null, array('placeholder' => 'Phone Number','class' => 'form-control', 'required')) !!}

                  @if ($errors->has('contact_no'))
                  <div class="alert  alert-danger mt-3">Phone Number is required!</div>
                  @endif

                </div>
              </div>
            </div> 
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Email Address</label>
                <div class="col-sm-8">
                  {!! Form::text('email', null, array('placeholder' => 'Email Address','class' => 'form-control', 'required')) !!}

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">City</label>
                <div class="col-sm-8"> 
                  <select name="city_id" id="citysearch" class="form-control" required>
                    <option value="">Select a city</option>
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
                <select name="group_id" id="groupsearch" class="form-control" required>
                    <option value="">Select a group</option> 
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
                <button type="submit" value="submit" class="btn btn-primary submit_client_form me-2 float-end">Submit</button>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="add_group_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Group</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('GroupController@create_ajax'),'method' => 'POST', 'id' => 'add_group_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Group Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'Enter Group Name','class' => 'form-control' , 'required', 'id'=>'group_name_box')) !!}
              </div>
            </div>
          </div> 
          <button type="submit" value="submit" class="btn btn-info text-dark submit_group_form me-2">Submit</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="add_city_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
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
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'City Name','class' => 'form-control' , 'required', 'id'=>'city_name_box')) !!}
              </div>
            </div>
          </div> 
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">City Code</label>
              <div class="col-sm-9">
                {!! Form::text('code', null, array('placeholder' => 'City Code','class' => 'form-control' , 'required', 'id'=>'city_code_box')) !!}
              </div>
            </div>
          </div> 
          <button type="submit" value="submit" class="btn btn-info text-dark submit_city_form me-2">Submit</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

 



</div>
<!-- main-panel ends -->

@endsection

@section('javascript')
<script>
$(document).ready(function() {
    // Get the highlight parameter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const highlightId = urlParams.get('highlight');
    
    // Initialize DataTable
    var table = $('#client_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/clients',
        "ordering": false,
        "pagingType": "full_numbers",
        "pageLength": 25,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel',
            {
                extend: 'print',
                exportOptions: {
                    stripHtml: false,
                    columns: [0, 1, 2, 3] 
                }
            }
        ],
        columnDefs: [{ 
            "targets": 3,
            "orderable": true,
            "searchable": true,
            "className": 'text-center',
        }],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'company_name', name: 'company_name', render: function(data, type, row) { 
                return '<a href="/clients/'+row.id+'" class="text-decoration-none">'+ row.company_name + '</a>';
            } }, 
            { data: 'comment', name: 'comment' }, 
            { data: 'respond', name: 'respond' },
            { data: 'files', name: 'files' },
            { data: 'action', name: 'action' }
        ],
        drawCallback: function(settings) {
            // Add data-client-id attribute to each row
            $(this).find('tr').each(function() {
                const rowData = table.row(this).data();
                if (rowData) {
                    $(this).attr('data-client-id', rowData.id);
                }
            });

            // Highlight the row if highlightId matches
            if (highlightId) {
                const row = $(`tr[data-client-id="${highlightId}"]`);
                if (row.length) {
                    row.addClass('highlighted-row');
                    // Scroll to the highlighted row
                    $('html, body').animate({
                        scrollTop: row.offset().top - 100
                    }, 500);
                }
            }
        }
    });
});
</script>

<style>
.highlighted-row {
    background-color: #fff3cd !important;
    transition: background-color 0.5s ease;
}
</style>
@endsection