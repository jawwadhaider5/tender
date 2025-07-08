

$(document).ready(function() {

    $('.dropdown-menu').on('click', function (event) {
        event.stopPropagation(); // Prevent dropdown from closing
      });


    $(document).on('click', 'a.view-register', function (e) {
        e.preventDefault(); 

        $.ajax({
            method: "get",
            url: $(this).attr("href"), 
            success: function(result){
                if (result.success == 1) {
                    // console.log(result.view);
                    // toastr.error(result.view);
                    $('#view_register_modal').html(result.view).modal('show');
               } else {
                    toastr.error(result.msg); 
                    window.location.href = '/register';
               }
            }
        });
    })  

    $(document).on('click', '#close_register', function (e) {
        e.preventDefault();
        
        var id = $("#current_register_id").val() 

        $.ajax({
            method: "get",
            url: $(this).attr("href"), 
            data: {"register_id": id},
            success: function(result){
                if (result.success == 1) { 
                    toastr.success(result.msg);
                    $('#view_register_modal').modal('hide'); ss
               } else {
                    toastr.error(result.msg);
               }
            }
        });
    })


});



