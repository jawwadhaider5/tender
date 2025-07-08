$(document).ready(function() {

    $('#user_table').DataTable({
        "ordering": false
    });

    $(document).on('click', 'a.view-users', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) { 
                $('#view_users_modal').html(result).modal('show');
            }
        });

    });

    // delete user
    $('table#user_table tbody').on('click', 'a.delete-users', function(e) {
        e.preventDefault();
        swal({
            title: "Do you want to delete the user?",
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
                            swal("Deleted!", "User Detail is successfully deleted.", "success");
                            user_table.ajax.reload(); 
                        } else { 
                            swal("Cancelled", "User Detail is safe :)", "error");

                        }
                    }
                });
            }
        });

    });



  

    // date restriction
    $("#reservationDate").datepicker({
        format: "yyyy/mm/dd", 
    });

    // endDate: new Date()

    var date = new Date();
    date.setDate(date.getDate() - 0);


    // restricts date icon
    // $("#reservationDate").datepicker();
    // $('.fa-calendar').click(function() {
    //     $("#reservationDate").focus();
    // });

    // joining date restriction

    $("#reservationDateOne").datepicker({
        format: "yyyy/mm/dd",
        endDate: new Date()
    });

    var date = new Date();
    date.setDate(date.getDate() - 0);


    // restricts date icon 
    // $("#reservationDateOne").datepicker();
    // $('.fa-calendar').click(function() {
    //     $("#reservationDateOne").focus();
    // });


    // leaving date restriction

    $("#reservationDateTwo").datepicker({
        format: "yyyy/mm/dd",
        endDate: new Date()
    });

    var date = new Date();
    date.setDate(date.getDate() - 0);


    // restricts date icon
    $("#reservationDateTwo").datepicker();
    $('.fa-calendar').click(function() {
        $("#reservationDateTwo").focus();
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
                console.log(src);

            }

            reader.readAsDataURL(input.files[0]);

        }
    }



    // make business's input searchable

    $('.livesearch').select2({
        placeholder: 'Select Business',
        ajax: {
            url: '/business-search',
            dataType: 'json',
            delay: 150,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.id,
                            value: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });

    // user live search

    $('#search').on('keyup', function() {
        // alert('hello');
        $value = $(this).val();
        // alert($value);

        $.ajax({

            type: 'get',
            url: '/supplier-search',
            data: { 'search': $value },

            success: function(data) {
                console.log(data);
                $('#content').html(data);
            }


        });
    });





});