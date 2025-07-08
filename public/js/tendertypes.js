$(document).ready(function() {

    var tender_type_info_table = $('#tender_type_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/tender-types',
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
                    columns: [0, 1]
                }
            }
        ],
        columnDefs: [{ 
            "targets": 2,
            "orderable": true,
            "searchable": true,
            "className": 'text-center',
        }],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' }, 
            { data: 'action', name: 'action' }
        ]
    });

 
    // delete tender_type
    $('table#tender_type_table tbody').on('click', 'a.delete-tender_type', function(e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete tender type ?",
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
                            toastr.success("Tender Type is successfully deleted");
                            tender_type_info_table.ajax.reload(); 
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });


    

    // view tender_type
    $(document).on('click', 'a.view-tender_type', function(e) {
        e.preventDefault();
        $('#view_tender_type_modal').modal('show'); 
    });

    $("form#add_tender_type_form").submit(function (e) {
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
                        $('#view_tender_type_modal').modal('hide');
                        tender_type_info_table.ajax.reload();
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });
    $('#view_tender_type_modal').on('hidden.bs.modal', function () {
        $('form#add_tender_type_form')[0].reset();
    });

    
  // edit tender_type
  $(document).on('click', 'a.edit-tender_type', function(e) {
    e.preventDefault();  
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {
            $('#edit_tender_type_modal').html(result).modal('show'); 
        }
    });

});

$(document).on('click', '#updateBtn', function(e) {
    e.preventDefault();  
    var data = $("#edit_tender_type_form").serialize();
        $.ajax({
            method: "PUT",
            url: $("#edit_tender_type_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#edit_tender_type_modal').modal('hide');
                    tender_type_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
});
 
$('#edit_tender_type_modal').on('hidden.bs.modal', function () {
    $('form#edit_tender_type_form')[0].reset();
});

    
});