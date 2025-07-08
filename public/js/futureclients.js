$(document).ready(function() {
    if (window.location.search.includes('highlight=')) {
        const highlightId = new URLSearchParams(window.location.search).get('highlight');
        setTimeout(function() {
            const row = $(`#future_client_table tbody tr[data-id="${highlightId}"]`);
            if (row.length) {
                row.addClass('highlight-row');
                row[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 1000); // Wait for DataTable to load
    }

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


    $("#submit_date").change(function() {
        let sub_date = $(this).val(); 
        $('#year').val(sub_date);
    });
    $("#submit_date_edit").change(function() {
        let sub_date = $(this).val(); 
        console.log(sub_date);
        console.log($('#year_edit').val());
        $('#year_edit').val(sub_date);
    });


    // it shows the future_client's table

    var future_client_info_table = $('#future_client_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/future-clients',
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
                    columns: [0, 1, 2, 3, 4, 5] 
                }
            }
        ],
        columnDefs: [{ 
            "targets": 4,
            "orderable": true,
            "searchable": true,
            "className": 'text-center',
        }],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'client.company_name', name: 'client.company_name', render: function(data, type, row) { 
                return '<a href="/future-clients?highlight='+row.id+'" class="text-decoration-none">'+ row.client.company_name + '</a>';
            } }, 
            { data: 'comment', name: 'comment' },
            { data: 'respond', name: 'respond' },
            { data: 'files', name: 'files' },
            { data: 'action', name: 'action' }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        },
        drawCallback: function(settings) {
            $('.future_client_response_users').select2({
                width: '100%',
                placeholder: "Assign to users",
                allowClear: true
            });
            $('.dropdown-menu').on('click', function (event) {
                event.stopPropagation(); // Prevent dropdown from closing
            });

            // Handle highlight after table is drawn
            if (window.location.search.includes('highlight=')) {
                const highlightId = new URLSearchParams(window.location.search).get('highlight');
                const row = $(`#future_client_table tbody tr[data-id="${highlightId}"]`);
                if (row.length) {
                    row.addClass('highlight-row');
                    row[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    });

    


    // delete future client
    $('table#future_client_table tbody').on('click', 'a.delete-future-client', function(e) {
        e.preventDefault();
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
                            // toastr.success(result.msg);
                            swal("Deleted!", "Future Client is successfully deleted.", "success");
                            future_client_info_table.ajax.reload(); 
                        } else { 
                            swal("Cancelled", "Future Client is safe :)", "error"); 
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

  



    
    // // delete group
    // $('table#future_client_table tbody').on('click', 'a.delete-future-client', function(e) {
    //     e.preventDefault();
    //     swal({
    //         title: "Do you want to delete future client ?",
    //         icon: "warning",
    //         buttons: true,
    //         dangerMode: true,
    //     }).then((willDelete) => {
    //         if (willDelete) {
    //             var href = $(this).attr('href'); 
    //             $.ajax({
    //                 method: "DELETE",
    //                 url: href,
    //                 dataType: "json",
    //                 success: function(result) {
    //                     if (result.success == true) { 
    //                         toastr.success("Future client is successfully deleted");
    //                         future_client_info_table.ajax.reload(); 
    //                     } else { 
    //                         toastr.error("Something went wrong!"); 
    //                     }
    //                 }
    //             });
    //         }
    //     });

    // });


    

    // view group
    $(document).on('click', 'a.create-future-client', function(e) {
        e.preventDefault();
        $('#future_client_create_modal').modal('show'); 
    });

    $("form#future_client_add_form").submit(function (e) {
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
                        $('#future_client_create_modal').modal('hide');
                        future_client_info_table.ajax.reload();
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });
    $('#future_client_create_modal').on('hidden.bs.modal', function () {
        $('form#future_client_add_form')[0].reset();
    });

    
  // edit future_client
  $(document).on('click', 'a.edit-future-client', function(e) {
    e.preventDefault();  
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) { 
            $('#edit_future_client_modal').html(result).modal('show'); 
        }
    });

});

$(document).on('click', '#updateBtn', function(e) {
    e.preventDefault();  
    var data = $("#edit_future_client_form").serialize();
        $.ajax({
            method: "PUT",
            url: $("#edit_future_client_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#edit_future_client_modal').modal('hide');
                    future_client_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
});
 
$('#edit_future_client_modal').on('hidden.bs.modal', function () {
    $('form#edit_future_client_form')[0].reset();
});




 // commment future_client
 $(document).on('click', 'a.comment-future-client', function(e) {
    e.preventDefault();   
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) { 
            $('#comment_future_client_modal').html(result).modal('show'); 
        }
    });

}); 
$(document).on('click', '#submit_future_client_form', function(e) {
    e.preventDefault();  
    var data = $("#comment_future_client_form").serialize();
        $.ajax({
            method: "POST",
            url: $("#comment_future_client_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    // $('#comment_future_client_modal').modal('hide');
                    future_client_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
}); 
$('#comment_future_client_modal').on('hidden.bs.modal', function () {
    $('form#comment_future_client_form')[0].reset();
});
$(document).on('click', 'a.delete-comment', function(e) {
    e.preventDefault();
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
                        // $('#comment_future_client_modal').modal('hide');
                        toastr.success("Deleted successfully"); 
                        future_client_info_table.ajax.reload();
                    } else { 
                        toastr.error("Something went wrong!"); 
                    }
                }
            });
        }
    });

});


// responds future_client
$(document).on('click', 'a.respond-future-client', function(e) {
    e.preventDefault();   
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {  
            $('#respond_future_client_modal').html(result).modal('show'); 
        }
    });

}); 
$(document).on('click', '#respond_future_client_formbtn', function(e) {
    e.preventDefault();  
    var data = $("#respond_future_client_form").serialize();
        $.ajax({
            method: "POST",
            url: $("#respond_future_client_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    // $('#respond_future_client_modal').modal('hide');
                    future_client_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
}); 
$('#respond_future_client_modal').on('hidden.bs.modal', function () {
    $('form#respond_future_client_form')[0].reset();
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
                        // $('#respond_future_client_modal').modal('hide');
                        toastr.success("Deleted successfully");
                        future_client_info_table.ajax.reload();
                    } else { 
                        toastr.error("Something went wrong!"); 
                    }
                }
            });
        }
    });

});

// files future_client
$(document).on('click', 'a.file-future-client', function(e) {
    e.preventDefault();   
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {  
            // $('#file_future_client_modal').html(result).modal('show'); 
        }
    });

}); 
$(document).on('click', '#file_future_client_formbtn', function(e) {
    e.preventDefault();  
    var data = $("#file_future_client_form")[0];
    let newdata = new FormData(data);  
        $.ajax({
            method: "POST",
            url: $("#file_future_client_form").attr("action"),
            // dataType: "json",
            data: newdata,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.success == true) {  
                    // $('#file_future_client_modal').modal('hide');
                    // future_client_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
}); 
$('#file_future_client_modal').on('hidden.bs.modal', function () {
    $('form#file_future_client_form')[0].reset();
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
                        // $('#file_future_client_modal').modal('hide');
                        toastr.success("Deleted successfully");
                        future_client_info_table.ajax.reload();
                    } else { 
                        toastr.error("Something went wrong!"); 
                    }
                }
            });
        }
    });

});

});