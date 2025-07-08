$(document).ready(function() {

    var group_info_table = $('#group_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/groups',
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

 
    // delete group
    $('table#group_table tbody').on('click', 'a.delete-group', function(e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete Group ?",
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
                            toastr.success("Group is successfully deleted");
                            group_info_table.ajax.reload(); 
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });


    

    // view group
    $(document).on('click', 'a.view-group', function(e) {
        e.preventDefault();
        $('#view_group_modal').modal('show'); 
    });

    $("form#add_group_form").submit(function (e) {
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
                        $('#view_group_modal').modal('hide');
                        group_info_table.ajax.reload();
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });
    $('#view_group_modal').on('hidden.bs.modal', function () {
        $('form#ad_group_form')[0].reset();
    });

    
  // edit group
  $(document).on('click', 'a.edit-group', function(e) {
    e.preventDefault();  
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {
            $('#edit_group_modal').html(result).modal('show'); 
        }
    });

});

$(document).on('click', '#updateBtn', function(e) {
    e.preventDefault();  
    var data = $("#edit_group_form").serialize();
        $.ajax({
            method: "PUT",
            url: $("#edit_group_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#edit_group_modal').modal('hide');
                    group_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
});
 
$('#edit_group_modal').on('hidden.bs.modal', function () {
    $('form#edit_group_form')[0].reset();
});

    
});