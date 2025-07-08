$(document).ready(function() {


    // it shows the loadstatus table

    var notification_info_table = $('#notification_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/notification',
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
                        //specify which column you want to print

                }
            }
        ],
        columnDefs: [{
            "targets": 2,
            "render": function(data, type, row) {
                return row.data.data.customer.customer_name + '<br><small>' + row.data.data.customer.contact_no + '</small> ';
            },
            "orderable": true,
            "searchable": true,
            "className": 'text-center',
        }],
        columns: [{
                "render": function(data, type, row, meta) {
                    // return "<p>" + row.data.data.item.name + "</p>";
                    return '<a href="/item-auction/' + row.data.data.item.id + '" class="view-itemauction  text-decoration-none"> ' + row.data.data.item.name + '</a>'
                },

            },

            {
                "render": function(data, type, row, meta) {

                    if (row.data.data.load_status == 1) {
                        return '<a href="/load/' + row.data.data.id + '" class="view-load   ps-2 text-decoration-none btn btn-success"> ' + "Load" + '</a>'
                    } else if (row.data.data.load_status == 0) {
                        return '<a href="/load/' + row.data.data.id + '" class="view-load   ps-2 text-decoration-none btn btn-warning"> ' + "Unload" + '</a>'

                    }

                    // console.log(row.data)
                },
            },
            {


                "render": function(data, type, row, meta) {
                    return "<p>" + row.data.data.customer.customer_name + "</p>";
                },

            },
            {

                "render": function(data, type, row, meta) {
                    return "<p>" + row.data.data.loaded_date + "</p>";
                },

            },
            {

                "render": function(data, type, row, meta) {
                    return "<p>" + row.data.data.loaded_note + "</p>";
                },

            },

            { data: 'action', name: 'action' }
        ]
    });




    // view item-loading status

    $(document).on('click', 'a.view-load', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) {
                $('#view_load_modal').html(result).modal('show');


            }
        });

    });


    // view item-auction
    $(document).on('click', 'a.view-itemauction', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) {
                $('#view_itemauction_modal').html(result).modal('show');


            }
        });

    });



    // delete notification
    $('table#notification_table tbody').on('click', 'a.delete-notification', function(e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete notification ?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var href = $(this).attr('href');
                // console.log(href)
                $.ajax({
                    method: "DELETE",
                    url: href,
                    dataType: "json",
                    success: function(result) {
                        if (result.success == true) {
                            // toastr.success(result.msg);
                            swal("Deleted!", "Notification is successfully deleted.", "success");
                            notification_info_table.ajax.reload();



                        } else {
                            // toastr.error(result.msg);
                            swal("Cancelled", "Notification is safe :)", "error");

                        }
                    }
                });
            }
        });

    });
















});