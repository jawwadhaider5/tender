$(document).ready(function () {
  

    
    $(document).on('click', '#create_group', function () { 
        $('#add_group_modal').modal('show'); 
        $("#group_name_box").val("");
    });
    $('#add_group_form').submit(function (e) { 
        e.preventDefault(); 
        var data = $("#add_group_form").serialize();  
        $.ajax({
            method: "POST",
            url: $("#add_group_form").attr("action"), 
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {
                    toastr.success("Group Created successfully");
                    $('#add_group_modal').modal('hide'); 
                    console.log(result.group);
                    var newOption = new Option(result.group.name, result.group.id, true, true);
                    $('#groupsearch').append(newOption).trigger('change');
                } else {
                    toastr.error("Something went wrong!");
                    $('#add_group_modal').modal('hide'); 
                }
            }
        }); 
    }); 
    $('#groupsearch').select2({
        width: '100%',
        dropdownParent: $('#client_create_modal'),
        ajax: {
            url: '/groups/search',
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
        placeholder: "Enter group name",
        language: {
            noResults: function () {
                return 'No group found!  <button type="button" class="btn btn-sm btn-primary mt-2" id="create_group">+ Add</button>';
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
 



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
    $('#citysearch').select2({
        width: '100%',
        dropdownParent: $('#client_create_modal'),
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






    $(document).on('click', '#create_position', function () { 
        $('#add_position_modal').modal('show'); 
        $("#position_name_box").val(""); 
    });
    $('#add_position_form').submit(function (e) { 
        e.preventDefault(); 
        var data = $("#add_position_form").serialize();  
        $.ajax({
            method: "POST",
            url: $("#add_position_form").attr("action"), 
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {
                    toastr.success("position Created successfully");
                    $('#add_position_modal').modal('hide');  
                    var newOption = new Option(result.position.name, result.position.id, true, true);
                    $('#positionsearch').append(newOption).trigger('change');
                } else {
                    toastr.error("Something went wrong!");
                    $('#add_position_modal').modal('hide'); 
                }
            }
        }); 
    }); 
    $('#positionsearch').select2({
        width: '100%',
        dropdownParent: $('#person_create_modal'),
        ajax: {
            url: '/positions/search',
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
        placeholder: "Enter position name",
        language: {
            noResults: function () {
                return 'No position found!  <button type="button" class="btn btn-sm btn-primary mt-2" id="create_position">+ Add</button>';
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });


    $('a.delete-person').on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete Person ?",
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
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success("Deleted successfully");
                            location.reload();
                        } else {
                            toastr.error("Something went wrong!");
                        }
                    }
                });
            }
        });

    });

    $('a.edit-person-detail').on('click', function (e) {
        e.preventDefault();
        console.log("edit clicked");
        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function (result) {
                $('#edit_person_modal').html(result).modal('show');
            }
        });
    });


        $('.dropdown-menu').on('click', function (event) {
            event.stopPropagation(); // Prevent dropdown from closing
          });


        // it shows the client's table

        var client_info_table = $('#client_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/clients',
            "ordering": false,
            "pagingType": "full_numbers",
            "pageLength": 25,
            dom: 'json',
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
                { data: 'response', name: 'response' },
                { data: 'files', name: 'files' },
                { data: 'action', name: 'action' }
            ],
            drawCallback: function(settings) {
                $('.client_response_users').select2({
                    width: '100%',
                    placeholder: "Assign to users",
                    allowClear: true
                });
                $('.dropdown-menu').on('click', function (event) {
                    event.stopPropagation(); // Prevent dropdown from closing
                  });
            }
        }); 

        var bill_info_table = $('#bill_related_table').DataTable({
            "pagingType": "full_numbers",
            "pageLength": 25, 
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel',
                {
                    extend: 'print',
                    exportOptions: {
                        stripHtml: false,
                        columns: [0, 1, 2, 3, 4, 5, 6] 
                    }
                }
            ],
            columnDefs: [{ 
                "targets": [1,2,3,4,5,6],
                "orderable": false,
                "searchable": false,
                "className": 'text-center',
            }]
        });


        // delete container
        $('table#client_table tbody').on('click', 'a.delete-client', function(e) {
            e.preventDefault();
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
                                // toastr.success(result.message);
                                swal("Deleted!", "Client is successfully deleted.", "success");
                                client_info_table.ajax.reload(); 
                            } else { 
                                swal("Cancelled", "Client is safe :)", "error"); 
                            }
                        }
                    });
                }
            }); 
        });




        // upload image 


        $("#file-input").change(function() {
            readURL(this, 'image');
            $('.upload-icon').css('border-style', 'none');
        });

        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + id).attr('src', e.target.result); 

                }

                reader.readAsDataURL(input.files[0]);

            }
        }






        // delete group
        $('table#client_table tbody').on('click', 'a.delete-client', function(e) {
            e.preventDefault();
            swal({
                title: "Do you want to delete Client ?",
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
                                toastr.success("Client is successfully deleted");
                                client_info_table.ajax.reload(); 
                            } else { 
                                toastr.error("Something went wrong!"); 
                            }
                        }
                    });
                }
            });

        });


            // edit person


    $(document).on('click', '#updatePersonBtn', function(e) {
        e.preventDefault();  
        var data = $("#edit_person_form").serialize();
            $.ajax({
                method: "POST",
                url: $("#edit_person_form").attr("action"),
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success == true) {  
                        $('#edit_person_modal').modal('hide');
                        toastr.success(result.message);
                            setTimeout(function(){
                                location.reload();
                            },1000)
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
    });

    $('#edit_person_modal').on('hidden.bs.modal', function () {
        $('form#edit_person_form')[0].reset();
    });


         // view person
         $(document).on('click', 'a.create-person', function(e) {
            e.preventDefault();
            console.log('clicked');
            $('#person_create_modal').modal('show'); 
        });

        $("form#person_add_form").submit(function (e) {
            e.preventDefault();
        }).validate({
            submitHandler: function (form) {
                var data = $(form).serialize();
                $.ajax({
                    method: "POST",
                    url: $(form).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function (result) {
                        if (result.success == true) {  
                            $('#person_create_modal').modal('hide'); 
                            toastr.success(result.message);
                            setTimeout(function(){
                                location.reload();
                            },1000)
                        } else {
                            toastr.error(result.message);
                        }
                    }
                });
            }
        });
        $('#person_create_modal').on('hidden.bs.modal', function () {
            $('form#person_add_form')[0].reset();
        });










        // view group
        $(document).on('click', 'a.create-client', function(e) {
            // Allow normal navigation to the create page
            // No need to prevent default or show modal
        });

        $("form#client_add_form").submit(function (e) {
            // Only prevent default and use AJAX if the modal exists (for modal forms)
            // Allow normal submission for standalone pages
            if ($('#client_create_modal').length > 0) {
                e.preventDefault();
            }
        }).validate({
            submitHandler: function (form) {
                // Only use AJAX if the modal exists
                if ($('#client_create_modal').length > 0) {
                    var data = $(form).serialize();
                    $.ajax({
                        method: "POST",
                        url: $(form).attr("action"),
                        dataType: "json",
                        data: data,
                        success: function (result) {
                            if (result.success == true) {  
                                $('#client_create_modal').modal('hide');
                                client_info_table.ajax.reload();
                                toastr.success(result.message);
                            } else {
                                toastr.error(result.message);
                            }
                        }
                    });
                } else {
                    // Allow normal form submission for standalone pages
                    form.submit();
                }
            }
        });
        $('#client_create_modal').on('hidden.bs.modal', function () {
            $('form#client_add_form')[0].reset();
        });


      // edit client
      $(document).on('click', 'a.edit-client', function(e) {
        e.preventDefault();  
        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) { 
                $('#edit_client_modal').html(result).modal('show'); 
            }
        });

    });

    $(document).on('click', '#updateBtn', function(e) {
        e.preventDefault();  
        var data = $("#edit_client_form").serialize();
            $.ajax({
                method: "PUT",
                url: $("#edit_client_form").attr("action"),
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success == true) {  
                        $('#edit_client_modal').modal('hide');
                        client_info_table.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
    });

    $('#edit_client_modal').on('hidden.bs.modal', function () {
        $('form#edit_client_form')[0].reset();
    });




     // commment client
     $(document).on('click', 'a.comment-client', function(e) {
        e.preventDefault();   
        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) { 
                $('#comment_client_modal').html(result).modal('show'); 
            }
        });

    }); 
    $(document).on('click', '#submit_client_form', function(e) {
        e.preventDefault();  
        var data = $("#comment_client_form").serialize();
            $.ajax({
                method: "POST",
                url: $("#comment_client_form").attr("action"),
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success == true) {  
                        $('#comment_client_modal').modal('hide');
                        // client_info_table.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
    }); 
    $('#comment_client_modal').on('hidden.bs.modal', function () {
        $('form#comment_client_form')[0].reset();
    });
    $(document).on('click', 'a.delete-comment', function(e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete Comment ?",
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
                            // $('#comment_client_modal').modal('hide');
                            toastr.success("Deleted successfully"); 
                            client_info_table.ajax.reload();
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });


    // responds client
    $(document).on('click', 'a.respond-client', function(e) {
        e.preventDefault();   
        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) {  
                $('#respond_client_modal').html(result).modal('show'); 
            }
        });

    }); 
    $(document).on('click', '#respond_client_formbtn', function(e) {
        e.preventDefault();  
        var data = $("#respond_client_form").serialize();
            $.ajax({
                method: "POST",
                url: $("#respond_client_form").attr("action"),
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success == true) {  
                        $('#respond_client_modal').modal('hide');
                        // client_info_table.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
    }); 
    $('#respond_client_modal').on('hidden.bs.modal', function () {
        $('form#respond_client_form')[0].reset();
    });
    $(document).on('click', 'a.delete-respond', function(e) {
        e.preventDefault();
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
                            // $('#respond_client_modal').modal('hide');
                            client_info_table.ajax.reload();
                             toastr.success(result.message);

                            // $('#respond_client_modal').modal('hide');
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });

    // files client
    $(document).on('click', 'a.file-client', function(e) {
        e.preventDefault();   
        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) {  
                $('#file_client_modal').html(result).modal('show'); 
            }
        });

    }); 
    $(document).on('click', '#file_client_formbtn', function(e) {
        e.preventDefault();  
        var data = $("#file_client_form")[0];
        let newdata = new FormData(data);  
            $.ajax({
                method: "POST",
                url: $("#file_client_form").attr("action"),
                // dataType: "json",
                data: newdata,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.success == true) {  
                        $('#file_client_modal').modal('hide');
                        // client_info_table.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
    }); 
    $('#file_client_modal').on('hidden.bs.modal', function () {
        $('form#file_client_form')[0].reset();
    });
    $(document).on('click', 'a.delete-file', function(e) {
        e.preventDefault();
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
                            client_info_table.ajax.reload();
                            toastr.success(result.message);
                            // $('#file_client_modal').modal('hide');
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });

});