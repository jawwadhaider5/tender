$(document).ready(function() {


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
                    var newOption = new Option(result.city.name + " - " + result.city.code, result.city.id, true, true);
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
                            text: item.text + " - " + item.code,
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


 
    $(document).on("click", ".groupItem",  function() {
        let groupId = $(this).data("group_id");
        let target = $("#tenderGroup-" + groupId);
        
        $(".TenderGroupBox").not(target).slideUp().addClass("d-none"); 
        // target.slideToggle().toggleClass("d-none");  
        target.removeClass('d-none');
    });

    // $(document).on('click', '.groupItem', function () {
    //     var groupid = $(this).data('group_id');
    //     console.log(groupid);

    //     $(`#tenderGroup-${groupid}`).removeClass('d-none');
    // })
 

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


    // it shows the tender's table

    var tender_info_table = $('#tender_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/tenders',
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
                    columns: [0, 1, 2, 3, 4, 5, 6, 7,8,9,10,11,12,13,14,15,16,17,18] 
                }
            }
        ],
        columnDefs: [{ 
            "targets": 18,
            "orderable": true,
            "searchable": true,
            "className": 'text-center',
        }],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'city_name', name: 'city_name' },
            { data: 'city_code', name: 'city_code' },
            { data: 'company_name', name: 'company_name' },
            { data: 'tender_number', name: 'tender_number' },
            { data: 'description', name: 'description' },
            { data: 'assigned_number', name: 'assigned_number' },
            { data: 'status', name: 'status' },
            { data: 'year', name: 'year' },
            { data: 'start_date', name: 'start_date' },
            { data: 'close_date', name: 'close_date' },
            { data: 'announce_date', name: 'announce_date' },
            { data: 'submit_date', name: 'submit_date' },
            { data: 'period', name: 'period' },
            { data: 'term', name: 'term' },
            { data: 'amount', name: 'amount' },
            { data: 'comments', name: 'comments' },
            { data: 'responds', name: 'responds' },
            { data: 'files', name: 'files' },
            { data: 'action', name: 'action' }
        ]
    });

    //  

    // it shows the all bills  related to tender

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
    $('table#tender_table2 tbody').on('click', 'a.delete-tender', function(e) {
        e.preventDefault();
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
                            // toastr.success(result.msg);
                            swal("Deleted!", "Tender is successfully deleted.", "success");
                            location.reload();
                            // tender_info_table.ajax.reload(); 
                        } else { 
                            swal("Cancelled", "Tender is safe :)", "error"); 
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
    $('table#tender_table tbody').on('click', 'a.delete-tender', function(e) {
        e.preventDefault();
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
                            toastr.success("Tender is successfully deleted");
                            tender_info_table.ajax.reload(); 
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });


    

    // view group
    $(document).on('click', 'a.create-tender', function(e) {
        e.preventDefault();
        $('#tender_create_modal').modal('show'); 
    });

    $("form#tender_add_form").submit(function (e) {
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
                        $('#tender_create_modal').modal('hide');
                        // tender_info_table.ajax.reload();
                        toastr.success(result.message);
                        location.reload();
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        }
    });
    $('#tender_create_modal').on('hidden.bs.modal', function () {
        $('form#tender_add_form')[0].reset();
    });

    
  // edit tender
//   $('table#tender_table2 tbody').on('click', 'a.edit-tender', function(e) {
//     e.preventDefault();  
//     $.ajax({
//         url: $(this).attr("href"),
//         dataType: "html",
//         success: function(result) { 
//             $('#edit_tender_modal').html(result).modal('show'); 
//         }
//     });

// });

// $('table#tender_table2 tbody').on('click', 'a.edit-tender', function(e) {
//     e.preventDefault();  
//     console.log("called1");
//     var url = $(this).attr("href");

//     $.ajax({
//         url: url,
//         dataType: "html",
//         success: function(result) { 
//             console.log("called2");
//             $('#edit_tender_modal').html(result).modal('show'); 
//         },
//         error: function(xhr, status, error) {
//             console.log("called3");
//             console.error("AJAX Error: ", status, error);
//         }
//     });
// });

$(document).on('click', '#updateBtn', function(e) {
    e.preventDefault();  
    var data = $("#edit_tender_form").serialize();
        $.ajax({
            method: "PUT",
            url: $("#edit_tender_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#edit_tender_modal').modal('hide');
                    // tender_info_table.ajax.reload();
                    toastr.success(result.msg);
                    location.reload();
                } else {
                    toastr.error(result.msg);
                }
            }
        });
});
 
$('#edit_tender_modal').on('hidden.bs.modal', function () {
    $('form#edit_tender_form')[0].reset();
});




 // commment tender
 $(document).on('click', 'a.comment-tender', function(e) {
    e.preventDefault();   
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) { 
            $('#comment_tender_modal').html(result).modal('show'); 
        }
    });

}); 
$(document).on('click', '#submit_tender_form', function(e) {
    e.preventDefault();  
    var data = $("#comment_tender_form").serialize();
        $.ajax({
            method: "POST",
            url: $("#comment_tender_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#comment_tender_modal').modal('hide');
                    // tender_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
}); 
$('#comment_tender_modal').on('hidden.bs.modal', function () {
    $('form#comment_tender_form')[0].reset();
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
                        $('#comment_tender_modal').modal('hide');
                    } else { 
                        toastr.error("Something went wrong!"); 
                    }
                }
            });
        }
    });

});


// responds tender
$(document).on('click', 'a.respond-tender', function(e) {
    e.preventDefault();   
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {  
            $('#respond_tender_modal').html(result).modal('show'); 
        }
    });

}); 
$(document).on('click', '#respond_tender_formbtn', function(e) {
    e.preventDefault();  
    var data = $("#respond_tender_form").serialize();
        $.ajax({
            method: "POST",
            url: $("#respond_tender_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#respond_tender_modal').modal('hide');
                    // tender_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
}); 
$('#respond_tender_modal').on('hidden.bs.modal', function () {
    $('form#respond_tender_form')[0].reset();
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
                        $('#respond_tender_modal').modal('hide');
                    } else { 
                        toastr.error("Something went wrong!"); 
                    }
                }
            });
        }
    });

});

// files tender
$(document).on('click', 'a.file-tender', function(e) {
    e.preventDefault();   
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {  
            $('#file_tender_modal').html(result).modal('show'); 
        }
    });

}); 
$(document).on('click', '#file_tender_formbtn', function(e) {
    e.preventDefault();  
    var data = $("#file_tender_form")[0];
    let newdata = new FormData(data);  
        $.ajax({
            method: "POST",
            url: $("#file_tender_form").attr("action"),
            // dataType: "json",
            data: newdata,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.success == true) {  
                    $('#file_tender_modal').modal('hide');
                    // tender_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
}); 
$('#file_tender_modal').on('hidden.bs.modal', function () {
    $('form#file_tender_form')[0].reset();
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
                        $('#file_tender_modal').modal('hide');
                    } else { 
                        toastr.error("Something went wrong!"); 
                    }
                }
            });
        }
    });

});

});