$(document).ready(function() {

    var city_info_table = $('#city_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/cities',
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
                    columns: [0, 1, 2]
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
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'action', name: 'action' }
        ]
    });

 
    // delete city
    $('table#city_table tbody').on('click', 'a.delete-city', function(e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete city ?",
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
                            toastr.success("City is successfully deleted");
                            city_info_table.ajax.reload(); 
                        } else { 
                            toastr.error("Something went wrong!"); 
                        }
                    }
                });
            }
        });

    });


    

    // view city
    $(document).on('click', 'a.view-city', function(e) {
        e.preventDefault();
        $('#view_city_modal').modal('show'); 
    });

    $("form#add_city_form").submit(function (e) {
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
                        $('#view_city_modal').modal('hide');
                        city_info_table.ajax.reload();
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });
    $('#view_city_modal').on('hidden.bs.modal', function () {
        $('form#add_city_form')[0].reset();
    });

    
  // edit city
  $(document).on('click', 'a.edit-city', function(e) {
    e.preventDefault();  
    $.ajax({
        url: $(this).attr("href"),
        dataType: "html",
        success: function(result) {
            $('#edit_city_modal').html(result).modal('show'); 
        }
    });

});

$(document).on('click', '#updateBtn', function(e) {
    e.preventDefault();  
    var data = $("#edit_city_form").serialize();
        $.ajax({
            method: "PUT",
            url: $("#edit_city_form").attr("action"),
            dataType: "json",
            data: data,
            success: function (result) {
                if (result.success == true) {  
                    $('#edit_city_modal').modal('hide');
                    city_info_table.ajax.reload();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            }
        });
});
 
$('#edit_city_modal').on('hidden.bs.modal', function () {
    $('form#edit_city_form')[0].reset();
});

    
});