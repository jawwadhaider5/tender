@extends('layouts.admin')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        @if (Session::get('success'))
            <div class="alert alert-{{ Session::get('success') ? 'success' : 'danger' }}">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        @can('tender')
        <div class="mb-3 d-flex justify-content-end">
            <a class="create-tender btn btn-primary btn-sm btn-rounded" title="Add New Tender" href="{{ route('tenders.create') }}">
                <i class="mdi mdi-plus-box"></i>
            </a>
        </div>
        @endcan

        <!-- City Based Table Section -->
        <x-city-based-table
            title="Tenders"
            tableId="tenders_by_city_table"
            route="{{ route('tenders-by-city') }}"
            type="tenders"
        />

        <!-- Tender Details Datatable Section -->
        <div id="tender_details_section" style="display: none; position: relative;">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="space">
                                    <div class="card-title">
                                        <h4 id="tender_details_title">Tender Details</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" style="min-height: 200px;">
                                <table id="tender_details_table" class="table table-hover table-bordered w-100">
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
            <button type="button" class="btn btn-sm btn-secondary" id="close_tender_details" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
                <i class="mdi mdi-close"></i> Close
            </button>
        </div>
    </div>
</div>

<!-- Edit Tender Modal -->
<div class="modal fade" id="edit_tender_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

<!-- Tender Create Modal -->
<div class="modal fade" id="tender_create_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class=" modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Tender</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => action('TenderController@store'),'method' => 'POST',
                'id' => 'tender_add_form','class' => '', 'enctype' => 'multipart/form-data']) !!}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label required">City</label>
                            <div class="col-sm-8">
                                <select name="city_id" id="citysearch" class="form-control" required>
                                    <option value="">Select a city</option>
                                </select>
                                @if ($errors->has('city_id'))
                                <div class="alert  alert-danger mt-3">{{ $errors->first('city_id') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

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
                            <label class="col-sm-3 col-form-label required">Tender Number</label>
                            <div class="col-sm-8">
                                {!! Form::text('tender_number', null, array('placeholder' => 'Tender Number','class' => 'form-control', 'required')) !!}
                                @if ($errors->has('tender_number'))
                                <div class="alert  alert-danger mt-3">{{ $errors->first('tender_number') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Year</label>
                            <div class="col-sm-8">
                                {!! Form::date('year', null, array('placeholder' => 'Year','class' => 'form-control', 'readonly', 'id'=>'year')) !!}
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
                            <label class="col-sm-3 col-form-label required">Status</label>
                            <div class="col-sm-8">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="approved">Approved</option>
                                    <option value="not approved">Not Approved</option>
                                    <option value="pending">Pending</option>
                                </select>
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
                            <label class="col-sm-3 col-form-label ">Start Date</label>
                            <div class="col-sm-8">
                                {!! Form::date('start_date', null, array('placeholder' => 'Start Date','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label ">Close Date</label>
                            <div class="col-sm-8">
                                {!! Form::date('close_date', null, array('placeholder' => 'Close Date','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label ">Announce Date</label>
                            <div class="col-sm-8">
                                {!! Form::date('announce_date', null, array('placeholder' => 'Announce Date','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label ">Submit Date</label>
                            <div class="col-sm-8">
                                {!! Form::date('submit_date', null, array('placeholder' => 'Submit Date','class' => 'form-control', 'id'=>'submit_date')) !!}
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

<!-- Add City Modal -->
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
@endsection

@section('javascript')
    @stack('javascript')

    <script>
    $(document).ready(function() {
        var tenderDetailsTable = null;

        // Handle tender click from city-based table
        $(document).on('client-clicked', function(e, link) {
            var tenderId = link.split('=')[1];
            loadTenderDetails(tenderId);
        });

        // Handle close button click
        $(document).on('click', '#close_tender_details', function() {
            $('#tender_details_section').hide();
            if (tenderDetailsTable) {
                tenderDetailsTable.destroy();
                tenderDetailsTable = null;
            }
        });

        // Delete comment handler with SweetAlert
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
                                if (typeof tenderDetailsTable !== 'undefined' && tenderDetailsTable) {
                                    tenderDetailsTable.ajax.reload();
                                }
                                toastr.success(result.message);
                            } else {
                                toastr.error(result.message || "Something went wrong!");
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error("Error deleting comment: " + error);
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
                                if (typeof tenderDetailsTable !== 'undefined' && tenderDetailsTable) {
                                    tenderDetailsTable.ajax.reload();
                                }
                                toastr.success(result.message);
                            } else {
                                toastr.error(result.message || "Something went wrong!");
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error("Error deleting respond: " + error);
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
                                if (typeof tenderDetailsTable !== 'undefined' && tenderDetailsTable) {
                                    tenderDetailsTable.ajax.reload();
                                }
                                toastr.success(result.message);
                            } else {
                                toastr.error(result.message || "Something went wrong!");
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error("Error deleting file: " + error);
                        }
                    });
                }
            });
        });

        function loadTenderDetails(tenderId) {
            // Show the tender details section
            $('#tender_details_section').show();

            // Update title to show loading
            $('#tender_details_title').text('Loading Tender Details...');

            // Destroy existing table if it exists
            if (tenderDetailsTable) {
                tenderDetailsTable.destroy();
            }

            // Initialize new datatable
            tenderDetailsTable = $('#tender_details_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/tender-details/' + tenderId,
                    type: "GET",
                    dataSrc: function(json) {
                        // Update title with tender info if available
                        if (json.data && json.data.length > 0) {
                            $('#tender_details_title').text('Tender Details: ' + json.data[0].client_company_name);
                        } else {
                            $('#tender_details_title').text('Tender Details');
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
                    { data: 'tender_id', name: 'tender_id' },
                    { data: 'client_company_name', name: 'client_company_name', render: function(data, type, row) { 
                        return '<a href="/tenders/'+row.tender_id+'" class="text-decoration-none">'+ row.client_company_name + '</a>';
                    } },
                    { data: 'comment', name: 'comment' },
                    { data: 'respond', name: 'respond' },
                    { data: 'files', name: 'files' },
                    { data: 'action', name: 'action' }
                ],
                drawCallback: function(settings) {
                    // Initialize Select2 with dropdown parent set to body to avoid clipping
                    $('.tender_response_users').each(function() {
                        var $select = $(this);
                        // Destroy existing Select2 instance if any
                        if ($select.hasClass('select2-hidden-accessible')) {
                            $select.select2('destroy');
                        }
                        $select.select2({
                            width: '100%',
                            placeholder: "Assign to users",
                            allowClear: true,
                            dropdownParent: $('body')
                        });
                    });
                    
                    // Set up dropdown event handler to prevent closing on form interactions
                    $('.dropdown-menu').off('click.dropdown-handler');
                    $('.dropdown-menu').on('click.dropdown-handler', function (event) {
                        // Allow delete button clicks to bubble up, prevent others from closing dropdown
                        if (!$(event.target).hasClass('delete-comment') && 
                            !$(event.target).hasClass('delete-respond') && 
                            !$(event.target).hasClass('delete-file')) {
                            event.stopPropagation();
                        }
                    });
                }
            });

            // Scroll to the tender details section
            $('html, body').animate({
                scrollTop: $('#tender_details_section').offset().top - 100
            }, 500);
        }

        // Handle form submissions for comments, responses, and files
        $(document).on('submit', 'form[action*="post-tender-comments"]', function(e) {
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
                        tenderDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        $(document).on('submit', 'form[action*="post-tender-responds"]', function(e) {
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
                        tenderDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        $(document).on('submit', 'form[action*="post-tender-files"]', function(e) {
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
                        tenderDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        // Delete tender handler (for the Action column delete button)
        $(document).on('click', 'a.delete-tender', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            swal({
                title: "Do you want to delete tender ?",
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
                                swal("Deleted!", "Tender is successfully deleted.", "success");
                                location.reload(); // Reload the whole page since the tender is deleted
                            } else {
                                swal("Error!", result.message || "Something went wrong!", "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            swal("Error!", "Error deleting tender: " + error, "error");
                        }
                    });
                }
            });
        });

        // Delete handlers are now set up directly above

        // Handle edit tender modal
        $(document).on('click', 'a.edit-tender2', function(e) {
            e.preventDefault();   
            var url = $(this).attr("href");

            $.ajax({
                url: url,
                dataType: "html",
                success: function(result) {  
                    $('#edit_tender_modal').html(result).modal('show'); 
                },
                error: function(xhr, status, error) { 
                    console.error("AJAX Error: ", status, error);
                }
            });
        });

        // Handle edit tender form submission
        $(document).on('submit', '#edit_tender_form', function(e) {
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
                        $('#edit_tender_modal').modal('hide');
                        tenderDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        // City search functionality
        $('#citysearch').select2({
            width: '100%',
            dropdownParent: $('#tender_create_modal'),
            ajax: {
                url: '/cities/search',
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
            placeholder: "Enter city name",
            language: {
                noResults: function () {
                    return 'No city found!  <button type="button" class="btn btn-sm btn-primary mt-2" id="create_city">+ Add</button>';
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });

        // Handle city creation
        $(document).on('click', '#create_city', function () { 
            $('#add_city_modal').modal('show'); 
            $("#city_name_box").val(""); 
            $("#city_code_box").val(""); 
        });

        $('#add_city_form').submit(function (e) { 
            e.preventDefault(); 
            var data = $("#add_city_form").serialize();  
            $.ajax({
                method: "POST",
                url: $("#add_city_form").attr("action"), 
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success("City Created successfully");
                        $('#add_city_modal').modal('hide');  
                        var newOption = new Option(result.city.name, result.city.id, true, true);
                        $('#citysearch').append(newOption).trigger('change');
                    } else {
                        toastr.error("Something went wrong!");
                        $('#add_city_modal').modal('hide'); 
                    }
                }
            }); 
        });

        // Handle tender form submission
        $("form#tender_add_form").submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                method: "POST",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success == true) {  
                        $('#tender_create_modal').modal('hide');
                        toastr.success(result.message);
                        location.reload();
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        $('#tender_create_modal').on('hidden.bs.modal', function () {
            $('form#tender_add_form')[0].reset();
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
    #tender_details_section {
        margin-top: 20px;
    }
    
    /* Make Select2 dropdown results scrollable */
    .select2-results__options {
        max-height: 200px !important;
        overflow-y: auto !important;
    }
    
    /* Ensure Select2 dropdown has proper z-index when opened */
    .select2-container--open .select2-dropdown {
        z-index: 10000 !important;
    }
    
    /* Ensure dropdown menu stays anchored to its container */
    #tender_details_table .dropdown {
        position: relative !important;
    }
    
    /* Prevent dropdown from shifting when scrolling */
    #tender_details_table .dropdown-menu {
        position: absolute !important;
        transform: none !important;
        will-change: auto !important;
    }
    
    /* Ensure table cells provide proper positioning context */
    #tender_details_table td {
        position: relative;
        overflow: visible;
    }
    
    /* Prevent dropdown menu from moving when scrolling within responses */
    #tender_details_table .dropdown-menu .px-2 {
        position: relative;
    }
    
    /* Ensure dropdown menu container doesn't move */
    #tender_details_table .dropdown.show .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        transform: translateX(0) translateY(0) !important;
    }
    
    /* Ensure Select2 dropdown can extend beyond all parent containers */
    #tender_details_table .select2-container--open .select2-dropdown {
        position: absolute !important;
        z-index: 10000 !important;
    }
    
    /* Make comment textareas larger */
    #comment-text,
    textarea[name="text"][id="comment-text"],
    textarea[placeholder*="Comment"] {
        min-height: 60px !important;
        height: 60px !important;
    }
    </style>
@endsection 