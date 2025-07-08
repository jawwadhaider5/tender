$(document).ready(function() {

    var position_info_table = $('#position_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/positions',
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

 
    // delete position
    $('table#position_table tbody').on('click', 'a.delete-position', function(e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete position ?",
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
                            toastr.success("Position is successfully deleted");
                            position_info_table.ajax.reload(); 
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });


    

    // view position
    $(document).on('click', 'a.view-position', function(e) {
        e.preventDefault();
        $('#view_position_modal').modal('show'); 
    });

    $("form#add_position_form").submit(function (e) {
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
                        $('#view_position_modal').modal('hide');
                        position_info_table.ajax.reload();
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });
    $('#view_position_modal').on('hidden.bs.modal', function () {
        $('form#add_position_form')[0].reset();
    });

    
  // edit position
  $(document).on('click', 'a.edit-position', function(e) {
    e.preventDefault();  
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {
            $('#edit_position_modal').html(result).modal('show'); 
        }
    });

});

$(document).on('click', '#updateBtn', function(e) {
    e.preventDefault();  
    var data = $("#edit_position_form").serialize();
        $.ajax({
            method: "PUT",
            url: $("#edit_position_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#edit_position_modal').modal('hide');
                    position_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
});
 
$('#edit_position_modal').on('hidden.bs.modal', function () {
    $('form#edit_position_form')[0].reset();
});

    
});