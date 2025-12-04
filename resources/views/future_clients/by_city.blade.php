@extends('layouts.admin')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        @if (Session::get('success'))
            <div class="alert alert-{{ Session::get('success') ? 'success' : 'danger' }}">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        @can('future-client')
        <div class="mb-3 d-flex justify-content-end">
            <a class="create-future-client btn btn-primary btn-sm btn-rounded" title="Add New Future Client" href="{{ route('future-clients.create') }}">
                <i class="mdi mdi-plus-box"></i>
            </a>
        </div>
        @endcan

        <!-- City Based Table Section -->
        <x-city-based-table 
            title="Future Clients"
            tableId="future_clients_by_city_table"
            route="{{ route('future-clients-by-city') }}"
            type="future-clients"
        />
        
        <!-- Edit Future Client Modal -->
<div class="modal fade" id="edit_future_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

<!-- Future Client Details Datatable Section -->
<div id="future_client_details_section" style="display: none; position: relative;">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="space">
                                    <div class="card-title">
                                        <h4 id="future_client_details_title">Future Client Details</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" style="min-height: 200px;">
                                <table id="future_client_details_table" class="table table-hover table-bordered w-100">
                                    <thead>
                                        <tr class="bg-success text-white">
                                            <th>#</th>
                                            <th>Company Name</th>
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
            <!-- Close button positioned at top right corner -->
            <button type="button" class="btn btn-sm btn-secondary" id="close_future_client_details" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
                <i class="mdi mdi-close"></i> Close
            </button>
        </div>
    </div>
</div>

<!-- Future Client Create Modal (copied from index.blade.php) -->
<div class="modal fade" id="future_client_create_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog modal-lg ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Future Client</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('FutureClientController@store'),'method' => 'POST',
        'id' => 'future_client_add_form','class' => '', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Select Client</label>
              <div class="col-sm-8">
                <select name="client_id" id="client_id" class="form-control">
                  <option value="">Select a Client</option>
                  @foreach($clients as $client)
                  <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('client_id'))
                <div class="alert  alert-danger mt-3">{{ $errors->first('client_id') }}
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Tender Type</label>
              <div class="col-sm-8">
                <select name="tender_type_id" id="tender_type_id" class="form-control" required>
                  <option value="">Select a tender type</option>
                  @foreach($tender_types as $tender_type)
                  <option value="{{ $tender_type->id }}">{{ $tender_type->name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('tender_type_id'))
                <div class="alert  alert-danger mt-3">{{ $errors->first('tender_type_id') }}
                </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label">Assigned No#</label>
              <div class="col-sm-8">
                <select name="assigned_number" class="form-control">
                  <option value="">Assigned Number</option>
                  @for($i=1;$i<=20;$i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Description</label>
              <div class="col-sm-8">
                <textarea name="description" id="" class="form-control" rows="3" required></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label ">Start Date</label>
              <div class="col-sm-8">
                {!! Form::date('start_date', null, array('placeholder' => 'Start Date','class' => 'form-control')) !!}
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label ">Coming Date</label>
              <div class="col-sm-8">
                {!! Form::date('coming_date', null, array('placeholder' => 'Coming Date','class' => 'form-control')) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label ">Time Period</label>
              <div class="col-sm-8">
                {!! Form::text('period', null, array('placeholder' => 'Time Period','class' => 'form-control')) !!}
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label ">Period Term</label>
              <div class="col-sm-8">
                <select name="term" id="term" class="form-control">
                  <option value="">Select a term</option>
                  <option value="days">Days</option>
                  <option value="months">Months</option>
                  <option value="years">Years</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label ">Amount</label>
              <div class="col-sm-8">
                {!! Form::text('amount', null, array('placeholder' => 'Amount','class' => 'form-control')) !!}
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label ">Select User</label>
              <div class="col-sm-8">
                <select name="user_id" id="user_id" class="form-control">
                  <option value="">Select User</option>
                  {!! $userss !!}
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 offset-md-6">
            <div class="col-md-8 offset-md-3">
              <input type="hidden" name="submit_type" id="submit_type">
              <button type="submit" value="submit" class="btn btn-primary  me-2 float-end">Submit</button>
            </div>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

<!-- Add Tender Type Modal (copied from index.blade.php) -->
<div class="modal fade" id="add_tender_type_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Tender Type</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('TenderTypeController@create_ajax'),'method' => 'POST', 'id' => 'add_tender_type_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">Tender Type Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'Tender Type Name','class' => 'form-control' , 'required', 'id'=>'tender_type_name_box')) !!}
              </div>
            </div>
          </div>   
          <button type="submit" value="submit" class="btn btn-info text-dark submit_tender_type_form me-2">Submit</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
    @stack('javascript')
    
    <script>
    $(document).ready(function() {
        var futureClientDetailsTable = null;
        
        // Handle future client click from city-based table
        $(document).on('client-clicked', function(e, link) {
            var futureClientId = link.split('=')[1];
            loadFutureClientDetails(futureClientId);
        });
        
        // Handle close button click
        $(document).on('click', '#close_future_client_details', function() {
            $('#future_client_details_section').hide();
            if (futureClientDetailsTable) {
                futureClientDetailsTable.destroy();
                futureClientDetailsTable = null;
            }
        });
        
        function loadFutureClientDetails(futureClientId) {
            // Show the future client details section
            $('#future_client_details_section').show();
            
            // Update title to show loading
            $('#future_client_details_title').text('Loading Future Client Details...');
            
            // Destroy existing table if it exists
            if (futureClientDetailsTable) {
                futureClientDetailsTable.destroy();
            }
            
            // Initialize new datatable
            futureClientDetailsTable = $('#future_client_details_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/future-client-details/' + futureClientId,
                    type: "GET",
                    dataSrc: function(json) {
                        // Update title with future client name if available
                        if (json.data && json.data.length > 0) {
                            $('#future_client_details_title').text('Future Client Details: ' + json.data[0].client.company_name);
                        } else {
                            $('#future_client_details_title').text('Future Client Details');
                        }
                        return json.data || [];
                    }
                },
                "ordering": false,
                "pagingType": "full_numbers",
                "pageLength": 10,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel',
                    {
                        extend: 'print',
                        exportOptions: {
                            stripHtml: false,
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ],
                columnDefs: [{ 
                    "targets": 5,
                    "orderable": true,
                    "searchable": true,
                    "className": 'text-center',
                }],
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'client.company_name', name: 'client.company_name', render: function(data, type, row) { 
                        return '<a href="/future-clients/'+row.id+'" class="text-decoration-none">'+ row.client.company_name + '</a>';
                    } },
                    { data: 'comment', name: 'comment' },
                    { data: 'respond', name: 'respond' },
                    { data: 'files', name: 'files' },
                    { data: 'action', name: 'action' }
                ],
                drawCallback: function(settings) {
                    // Initialize Select2 with dropdown parent set to the dropdown container
                    $('.future_client_response_users').each(function() {
                        var $select = $(this);
                        // Find the closest dropdown container (parent of dropdown-menu)
                        var $dropdownContainer = $select.closest('.dropdown');
                        if ($dropdownContainer.length === 0) {
                            $dropdownContainer = $select.closest('.dropdown-menu').parent();
                        }
                        // Destroy existing Select2 instance if any
                        if ($select.hasClass('select2-hidden-accessible')) {
                            $select.select2('destroy');
                        }
                        $select.select2({
                            width: '100%',
                            placeholder: "Assign to users",
                            allowClear: true,
                            dropdownParent: $dropdownContainer.length ? $dropdownContainer : $('#future_client_details_section')
                        });
                    });
                    $('.dropdown-menu').on('click', function (event) {
                        // Allow delete and edit button clicks to work
                        if (!$(event.target).hasClass('delete-future-client') &&
                            !$(event.target).closest('.delete-future-client').length &&
                            !$(event.target).hasClass('edit-future-client') &&
                            !$(event.target).closest('.edit-future-client').length &&
                            !$(event.target).hasClass('delete-comment') &&
                            !$(event.target).closest('.delete-comment').length &&
                            !$(event.target).hasClass('delete-respond') &&
                            !$(event.target).closest('.delete-respond').length &&
                            !$(event.target).hasClass('delete-file') &&
                            !$(event.target).closest('.delete-file').length) {
                            event.stopPropagation();
                        }
                    });
                    
                }
            });

            // Scroll to the future client details section
            $('html, body').animate({
                scrollTop: $('#future_client_details_section').offset().top - 100
            }, 500);
        }

        // Handle form submissions for comments, responses, and files
        $(document).on('submit', 'form[action*="post-future-client-comments"]', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: "POST",
                url: form.attr("action"),
                dataType: "json",
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        futureClientDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        $(document).on('submit', 'form[action*="post-future-client-responds"]', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: "POST",
                url: form.attr("action"),
                dataType: "json",
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        futureClientDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        $(document).on('submit', 'form[action*="post-future-client-files"]', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(form[0]);

            $.ajax({
                method: "POST",
                url: form.attr("action"),
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success == true) {
                        futureClientDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        // Handle delete actions
        $(document).on('click', 'a.delete-comment', function(e) {
            e.preventDefault();
            e.stopPropagation();
            swal({
                title: "Do you want to delete comment ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).attr('href'); 
                    $.ajax({
                        method: "GET",
                        url: href,
                        dataType: "json",
                        success: function(result) {
                            if (result.success == true) {
                                futureClientDetailsTable.ajax.reload();
                                toastr.success(result.message);
                            } else {
                                toastr.error("Something went wrong!");
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', 'a.delete-respond', function(e) {
            e.preventDefault();
            e.stopPropagation();
            swal({
                title: "Do you want to delete respond ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).attr('href'); 
                    $.ajax({
                        method: "GET",
                        url: href,
                        dataType: "json",
                        success: function(result) {
                            if (result.success == true) {
                                futureClientDetailsTable.ajax.reload();
                                toastr.success(result.message);
                            } else {
                                toastr.error("Something went wrong!");
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', 'a.delete-file', function(e) {
            e.preventDefault();
            e.stopPropagation();
            swal({
                title: "Do you want to delete file ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).attr('href'); 
                    $.ajax({
                        method: "GET",
                        url: href,
                        dataType: "json",
                        success: function(result) {
                            if (result.success == true) {
                                futureClientDetailsTable.ajax.reload();
                                toastr.success(result.message);
                            } else {
                                toastr.error("Something went wrong!");
                            }
                        }
                    });
                }
            });
        });

        // Handle edit future client modal
        $(document).on('click', 'a.edit-future-client', function(e) {
            e.preventDefault();
            var url = $(this).attr("href");

            $.ajax({
                url: url,
                dataType: "html",
                success: function(result) {
                    $('#edit_future_client_modal').html(result).modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                }
            });
        });

        // Handle edit future client form submission
        $(document).on('submit', '#edit_future_client_form', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: "PUT",
                url: form.attr("action"),
                dataType: "json",
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        $('#edit_future_client_modal').modal('hide');
                        futureClientDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        // Delete future client handler (for the Action column delete button)
        $(document).on('click', 'a.delete-future-client', function(e) {
            e.preventDefault();
            e.stopPropagation();

            swal({
                title: "Do you want to delete future client ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).attr('href');
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        success: function(result) {
                            if (result.success == true) {
                                swal("Deleted!", "Future client is successfully deleted.", "success");

                                // Hide the details section
                                $('#future_client_details_section').hide();
                                if (futureClientDetailsTable) {
                                    futureClientDetailsTable.destroy();
                                    futureClientDetailsTable = null;
                                }

                                // Refresh the main city table
                                try {
                                    var mainTable = $('#future_clients_by_city_table').DataTable();
                                    mainTable.ajax.reload();
                                } catch (e) {
                                    console.log('Error reloading table:', e);
                                    // Fallback: full page reload
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            } else {
                                swal("Error!", result.message || "Something went wrong!", "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            swal("Error!", "Error deleting future client: " + error, "error");
                        }
                    });
                }
            });
        });

        $(document).on('click', '#create_tender_type', function () {
            $('#add_tender_type_modal').modal('show');
            $("#tender_type_name_box").val("");
        });
        $('#add_tender_type_form').submit(function (e) {
            e.preventDefault();
            var data = $("#add_tender_type_form").serialize();
            $.ajax({
                method: "POST",
                url: $("#add_tender_type_form").attr("action"),
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success("Tender Type Created successfully");
                        $('#add_tender_type_modal').modal('hide');
                        var newOption = new Option(result.tender_type.name, result.tender_type.id, true, true);
                        $('#tender_type_id').append(newOption).trigger('change');
                    } else {
                        toastr.error("Something went wrong!");
                        $('#add_tender_type_modal').modal('hide');
                    }
                }
            });
        });
        $('#tender_type_id').select2({
            width: '100%',
            dropdownParent: $('#future_client_create_modal'),
            ajax: {
                url: '/tender-types/search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id,
                                value: item.id
                            }
                        })
                    };
                }
            },
            minimumInputLength: 2,
            allowClear: true,
            placeholder: "Enter Tender Type name",
            language: {
                noResults: function () {
                    return 'No Tender Type found!  <button type="button" class="btn btn-sm btn-primary mt-2" id="create_tender_type">+ Add</button>';
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });
    });
    </script>

    <style>
    .code-link, .city-link, .data-link {
        color: #007bff;
        text-decoration: none;
        cursor: pointer;
    }
    .code-link:hover, .city-link:hover, .data-link:hover {
        text-decoration: underline;
    }
    .city-links {
        line-height: 1.8;
    }
    .data-item {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
    .data-item:last-child {
        border-bottom: none;
    }
    .data-container {
        min-height: 50px;
    }

    /* Ensure tables take full width */
    .table-responsive {
        width: 100%;
    }

    .table {
        width: 100% !important;
    }

    /* Ensure proper spacing between sections */
    #future_client_details_section {
        margin-top: 20px;
    }

    /* Ensure Select2 dropdown stays within dropdown menu */
    .dropdown-menu .select2-container {
        position: relative !important;
    }

    .dropdown-menu .select2-dropdown {
        position: absolute !important;
        z-index: 9999 !important;
    }
    
    /* Ensure dropdown menu stays anchored to its container */
    #future_client_details_table .dropdown {
        position: relative !important;
    }
    
    /* Prevent dropdown from shifting when scrolling */
    #future_client_details_table .dropdown-menu {
        position: absolute !important;
        transform: none !important;
        will-change: auto !important;
    }
    
    /* Ensure table cells provide proper positioning context */
    #future_client_details_table td {
        position: relative;
        overflow: visible;
    }
    
    /* Prevent dropdown menu from moving when scrolling within responses */
    #future_client_details_table .dropdown-menu .px-2 {
        position: relative;
    }
    
    /* Ensure dropdown menu container doesn't move */
    #future_client_details_table .dropdown.show .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        transform: translateX(0) translateY(0) !important;
    }
    </style>
@endsection
