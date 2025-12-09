@extends('layouts.admin')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        @if (Session::get('success'))
            <div class="alert alert-{{ Session::get('success') ? 'success' : 'danger' }}">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        @can('client')
        <div class="mb-3 d-flex justify-content-end">
            <a class="create-client btn btn-primary btn-sm btn-rounded" title="Add New Client" href="{{ route('clients.create') }}">
                <i class="mdi mdi-plus-box"></i>
            </a>
        </div>
        @endcan

        <!-- City Based Table Section -->
        <x-city-based-table
            title="Clients"
            tableId="clients_by_city_table"
            route="{{ route('clients-by-city') }}"
            type="clients"
        />

        <!-- Edit Client Modal -->
<div class="modal fade" id="edit_client_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

<!-- Client Details Datatable Section -->
<div id="client_details_section" style="display: none; position: relative;">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="space">
                                    <div class="card-title">
                                        <h4 id="client_details_title">Client Details</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" style="min-height: 200px;">
                                <table id="client_details_table" class="table table-hover table-bordered w-100">
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
            <button type="button" class="btn btn-sm btn-secondary" id="close_client_details" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
                <i class="mdi mdi-close"></i> Close
            </button>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    @stack('javascript')

    <script>
    $(document).ready(function() {
        var clientDetailsTable = null;
        var mainCityTable = null;
        
        // Store reference to main table after it's initialized
        setTimeout(function() {
            mainCityTable = $('#clients_by_city_table').DataTable();
        }, 1000);

        // Handle client click from city-based table
        $(document).on('client-clicked', function(e, link) {
            var clientId = link.split('=')[1];
            loadClientDetails(clientId);
        });

        // Handle close button click
        $(document).on('click', '#close_client_details', function() {
            $('#client_details_section').hide();
            if (clientDetailsTable) {
                clientDetailsTable.destroy();
                clientDetailsTable = null;
            }
        });

        function loadClientDetails(clientId) {
            // Show the client details section
            $('#client_details_section').show();

            // Update title to show loading
            $('#client_details_title').text('Loading Client Details...');

            // Destroy existing table if it exists
            if (clientDetailsTable) {
                clientDetailsTable.destroy();
            }

            // Initialize new datatable
            clientDetailsTable = $('#client_details_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/client-details/' + clientId,
                    type: "GET",
                    dataSrc: function(json) {
                        // Update title with client name if available
                        if (json.data && json.data.length > 0) {
                            $('#client_details_title').text('Client Details: ' + json.data[0].company_name);
                        } else {
                            $('#client_details_title').text('Client Details');
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
                    { data: 'company_name', name: 'company_name', render: function(data, type, row) { 
                        return '<a href="/clients/'+row.id+'" class="text-decoration-none">'+ row.company_name + '</a>';
                    } },
                    { data: 'comment', name: 'comment' },
                    { data: 'respond', name: 'respond' },
                    { data: 'files', name: 'files' },
                    { data: 'action', name: 'action' }
                ],
                drawCallback: function(settings) {
                    // Initialize Select2 with dropdown parent set to body to avoid clipping
                    $('.client_response_users').each(function() {
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
                    $('.dropdown-menu').on('click', function (event) {
                        // Allow delete and edit button clicks to work
                        if (!$(event.target).hasClass('delete-client') && 
                            !$(event.target).closest('.delete-client').length &&
                            !$(event.target).hasClass('edit-client') && 
                            !$(event.target).closest('.edit-client').length &&
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

            // Scroll to the client details section
            $('html, body').animate({
                scrollTop: $('#client_details_section').offset().top - 100
            }, 500);
        }

        // Handle form submissions for comments, responses, and files
        $(document).on('submit', 'form[action*="post-client-comments"]', function(e) {
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
                        clientDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        $(document).on('submit', 'form[action*="post-client-responds"]', function(e) {
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
                        clientDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        $(document).on('submit', 'form[action*="post-client-files"]', function(e) {
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
                        clientDetailsTable.ajax.reload();
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
                                clientDetailsTable.ajax.reload();
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
                                clientDetailsTable.ajax.reload();
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
                                clientDetailsTable.ajax.reload();
                                toastr.success(result.message);
                            } else {
                                toastr.error("Something went wrong!");
                            }
                        }
                    });
                }
            });
        });

        // Handle edit client modal
        $(document).on('click', 'a.edit-client', function(e) {
            e.preventDefault();   
            var url = $(this).attr("href");

            $.ajax({
                url: url,
                dataType: "html",
                success: function(result) {  
                    $('#edit_client_modal').html(result).modal('show'); 
                },
                error: function(xhr, status, error) { 
                    console.error("AJAX Error: ", status, error);
                }
            });
        });

        // Handle edit client form submission
        $(document).on('submit', '#edit_client_form', function(e) {
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
                        $('#edit_client_modal').modal('hide');
                        clientDetailsTable.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        });

        // Delete client handler (for the Action column delete button)
        $(document).on('click', 'a.delete-client', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            swal({
                title: "Do you want to delete client ?",
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
                                swal("Deleted!", "Client is successfully deleted.", "success");
                                
                                // Hide the details section
                                $('#client_details_section').hide();
                                if (clientDetailsTable) {
                                    clientDetailsTable.destroy();
                                    clientDetailsTable = null;
                                }
                                
                                // Refresh the main city table
                                if (mainCityTable) {
                                    mainCityTable.ajax.reload();
                                } else {
                                    // Fallback: reload the page if table reference is not available
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            } else {
                                swal("Error!", result.message || "Something went wrong!", "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            swal("Error!", "Error deleting client: " + error, "error");
                        }
                    });
                }
            });
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
    #client_details_section {
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
    
    /* Make comment textareas larger */
    #comment-text,
    textarea[name="text"][id="comment-text"],
    textarea[placeholder*="Comment"] {
        min-height: 60px !important;
        height: 60px !important;
    }
    </style>
@endsection
