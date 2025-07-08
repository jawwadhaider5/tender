$(document).ready(function() {


    // it shows the item table

    var bill_info_table = $('#bill_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/bill',
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
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        //specify which column you want to print

                }
            }
        ],
        columnDefs: [{

            "targets": 7,
            "orderable": true,
            "searchable": true,
            "className": 'text-center',
        }],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'cname', name: 'cname' },
            { data: 'contact', name: 'contact' },
            { data: 'trstatus', name: 'trstatus' },
            { data: 'recamount', name: 'recamount' },
            { data: 'dueamount', name: 'dueamount' },
            { data: 'bill_date', name: 'bill_date' },
            { data: 'action', name: 'action' }
        ]
    });






    // it shows the item table
    $('#quickitem_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/bill/quickitem' + '/' + 0 + '/' + 0,
        "order": [
            [0, 'desc']
        ],
        "pagingType": "full_numbers",
        "pageLength": 25,
        columnDefs: [{
            "targets": 7,
            "orderable": true,
            "searchable": true,
            "className": 'text-center',
        }],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'tag', name: 'tag' },
            { data: 'squantity', name: 'squantity' },
            { data: 'icname', name: 'icname' },
            { data: 'cname', name: 'cname' },
            { data: 'sellstatus', name: 'sellstatus' },
            { data: 'action', name: 'action' },
            //  {
            //     'data': null,
            //     'render': function(data, type, row) {
            //         return '<button id="' + row.id + '" onclick="shiftClick(this)">+</button>'
            //     }
            // }
        ]

    });

    // $.ajax({

    //     type: 'get',
    //     url: '/bill/quickitem' + '/' + 0 + '/' + 0,
    //     success: function(data) {
    //         console.log(data);
    //     }


    // });


    // var customer_info_table = $('#quickitem_table').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     url: '/bill/quickitem' + '/' + 0 + '/' + 0,
    //     "order": [
    //         [0, 'desc']
    //     ],
    //     "pagingType": "full_numbers",
    //     "pageLength": 5,
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel',
    //         {
    //             extend: 'print',
    //             exportOptions: {
    //                 stripHtml: false,
    //                 columns: [0, 1, 2, 3, 4, 5, 6, 7]
    //                     //specify which column you want to print

    //             }
    //         }
    //     ],
    //     columnDefs: [{


    //         "targets": 7,
    //         "orderable": true,
    //         "searchable": true,
    //         "className": 'text-center',
    //     }],
    //     columns: [
    //         { data: 'id', name: 'id' },
    //         { data: 'tname', name: 'tname' },
    //         { data: 'tag', name: 'tag' },
    //         { data: 'squantity', name: 'squantity' },
    //         { data: 'icname', name: 'icname' },
    //         { data: 'cname', name: 'cname' },
    //         { data: 'sellstatus', name: 'sellstatus' },
    //         { data: 'action', name: 'action' }
    //     ]
    // });







    // sender search drop down 
    $('#customer_search').select2({
        ajax: {
            url: '/item-auction/customersearch',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data) {
                console.log(data)
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.text,
                            id: item.id,
                            value: item.id
                        }
                    })
                };
            }
        },
        minimumInputLength: 1,
        allowClear: true,
        placeholder: "Name, Company Name , Email, Contact No  etc...",
        language: {
            noResults: function() {
                var name = $("#customer_search").data("select2").dropdown.$search.val();
                return 'No Customer Found!';
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });


    // 



    // 


    //add new list items for bill
    // $(document).on('click', '#add_new_item', function() {
    //     var row_index = $('#list_counter').val();
    //     $.ajax({
    //         method: "POST",
    //         url: '/bill/get_item_row',
    //         data: { 'row_index': row_index, 'action': 'add' },
    //         dataType: "html",
    //         success: function(result) {
    //             if (result) {
    //                 $('#list_counter').val(parseInt(row_index) + 1);
    //                 $('#list_item_form_part  > tbody').append(result);
    //             }
    //         }
    //     });
    // });

    //remove items
    $(document).on('click', '.remove_list_item_row', function() {
        swal({
            title: "Delete item",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var count = $(this).closest('table').find('.remove_list_item_row').length;
                if (count === 1) {
                    $(this).closest('.item_row').remove();
                } else {
                    $(this).closest('tr').remove();
                }
                var counter = $('#list_counter').val();
                $('#list_counter').val(counter - 1);
            }
        });
    });


    // select payment type
    $('#select').on('change', function() {
        var option = $('#select option:selected').val();

        alert(option)

        if (option == 'card') {
            $("#cardOption").show();
        } else {
            $("#cardOption").hide();
        }
        if (option == 'cheque') {
            $("#chequeOption").show();
        } else {
            $("#chequeOption").hide();

        }
        if (option == 'cash') {
            $("#cashOption").show();
        } else {
            $("#cashOption").hide();

        }
        if (option == 'bank_account') {
            $("#bankOption").show();
        } else {
            $("#bankOption").hide();

        }
        if (option == 'other') {
            $("#otherOption").show();
        } else {
            $("#otherOption").hide();

        }


    })

    // make container's input searchable

    $('.container_change').select2({

        // tags: true,
        // placeholder: 'Select Container ',
        ajax: {
            url: '/container-search-bill',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
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

            // cache: true
        },
        minimumInputLength: 1,
        minimumResultsForSearch: 10,
        allowClear: true,
        placeholder: "Container",
        language: {
            noResults: function() {
                var name = $(".container_change").data("select2").dropdown.$search.val();
                return 'No Container Found!';
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }





    });


    // sender search drop down 
    // $('#customer_search').select2({
    //     ajax: {
    //         url: '/item-auction/customersearch',
    //         dataType: 'json',
    //         delay: 250,
    //         data: function(params) {
    //             return {
    //                 q: params.term, // search term
    //                 page: params.page
    //             };
    //         },
    //         processResults: function(data) {
    //             console.log(data)
    //             return {
    //                 results: $.map(data, function(item) {
    //                     return {
    //                         text: item.text,
    //                         id: item.id,
    //                         value: item.id
    //                     }
    //                 })
    //             };
    //         }
    //     },
    //     minimumInputLength: 1,
    //     allowClear: true,
    //     placeholder: "Name, Company Name , Email, Contact No  etc...",
    //     language: {
    //         noResults: function() {
    //             var name = $("#customer_search").data("select2").dropdown.$search.val();
    //             return 'No Customer Found!';
    //         }
    //     },
    //     escapeMarkup: function(markup) {
    //         return markup;
    //     }
    // });


    // 

    //  make itemcategory's input searchable
    $('.category_change').select2({
        // tags: true,
        placeholder: 'Select ItemCategory',
        ajax: {
            url: '/itemcategory-search-bill',
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

    //  
    //  $('.category_change').select2();
    // $('.category_change').on('change', function(e) {
    //     var data = $('.category_change').select2("val");
    //     @this.set('ottPlatform', data);
    // });



    //  make item's and sell's input searchable
    $('.livesearchtwo').select2({
        tags: true,
        placeholder: 'Select Item',
        ajax: {
            url: '/item-search-bill',
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

    fetch_item_data();

    function fetch_item_data(query = '') {

        $.ajax({
            url: "{{route('live_search.action')}}",
            method: 'GET',
            data: { query: query },
            dataType: 'json',
            success: function(data) {
                $('tbody').html(data.table_data);
                $('#total_records').text(data.total_data);
            }
        })
    }


    // 
    $('#search').on('keyup', function() {
        // alert('hello');
        $value = $(this).val();
        // alert($value);

        $.ajax({

            type: 'get',
            url: '/bill/search',
            data: { 'search': $value },

            success: function(data) {
                console.log(data);
                $('#content').html(data);
            }


        });
    });


    // 


    //  make itemcategory's input searchable
    $('.livesearchfive').select2({
        // tags: true,
        placeholder: 'Select Item',
        ajax: {
            url: '/item-search',
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

    // empty the selected dropdown

    $('.livesearchfive').val('').trigger('change');

    //  on basis of selected item from livesearch the detail of selected itm will be added in a row (appended) in the mentioned table's tbody

    $('.livesearchfive').on('change', function() {

        var item_id = this.value;

        var row_index = $('#list_counter').val();


        // var row_index = (this.value);


        $.ajax({
            method: "POST",
            url: '/bill/get_item_row',
            // below we have passed item_id, row_index and action to function present in controller , similarly, we can receive the same variables which come from function present in controller through json, return array or view.
            data: { 'item_id': item_id, 'row_index': row_index, 'action': 'add' },
            dataType: "html",
            success: function(result) {
                if (result) {

                    // we use result to show that any output which comes from controller's function is stored in variable result.

                    //  how to parse json data with jquery javascript:
                    var parsedJson = $.parseJSON(result);

                    if (parsedJson.data == false) {
                        // alert("no item found!")
                        swal("Not Found!", "No item is found :)", "error");
                    } else {
                        $('#list_counter').val(parseInt(row_index) + 1);
                        $('#list_item_form_part  > tbody').append(parsedJson.content);

                        post_total_row();

                        // swal("Found!", "Item is successfully added.", "success");
                    }

                }
            }
        });




    });

    // put check (if else ) to check if the number of quantity of item which is increased is more than the real quantity in database or not, if the number put here in quantity is more than the real number of quantity than give alert message that it is more than the real number of quantity.
    // if the same id of item is selected in select2 then do not add new row , just add the number for the row which is alreaady entered.


    //Update input balance's value  if received_amount's value is changed and check for received_amount  not to be greater than subtotal
    $('table#list_item_form_part tbody').on('change', 'input.receivedamount', function() {

        // input.receivedamount here .receivedamount is name of class which is given in input box
        // if input.received's value is changed than go to the table of #list_item_form_part tbody and add the final result .



        var tr = $(this).parents('tr');
        // here you find out the same row where receivedamount is changed ($(this).parents('tr')) and put it in the variable of tr.

        var rec_amount = $(this).val();
        //  here you put the changed value of receivedamount ($(this).val()) in the variable of rec_amount .




        // var qty = tr.find('input.quantity').val();
        // here you find out the same row you found in above code and put the value of input.quantity(there is an editable input in the row with calss name of quantity) and put its value in the variable of qty.
        // var price = tr.find('input.price').val();
        // here you find out the same row you found in above code and put the value of input.price(there is an editable input in the row with calss name of price) and put its value in the variable of price.


        // alert(qty + " " + price)
        // the above code alerts the value of variables qty and price

        // var total = (qty * price) - rec_amount;
        // here you multiply the value of variable "qty" and "price" and decrease it from rec_amount,finally, put its value in variable total.

        var subtotal = tr.find('input.subtotal').val();

        var balance = tr.find('input.balance').val();

        var item_id = tr.find('input.item_id').val();


        // here you find out the class name for input box subtotal which is subtotal and get its value.
        // $success = true;
        // $msg = '';
        $.ajax({
            type: "GET",
            url: '/bill/get_receivedamount',
            data: { 'id': item_id },
            success: function(result) {
                if (result) {
                    var parsedJson = $.parseJSON(result);

                    if (parseInt(rec_amount) > parseInt(subtotal)) {

                        // $success = false; //if not successfull
                        swal("Impossible!", "Received-amount  can not  be  grater than  Subtotal:)", "error");
                        // $msg = 'Received amount can not be greater than subtotal';
                        rec_amount = tr.find('input.receivedamount').val(parsedJson.sreceived);

                        balance = tr.find('input.balance').val(parsedJson.sbalance);
                        // alert(rec_amount)

                        post_total_row();


                    } else {
                        balance = parseInt(subtotal) - parseInt(rec_amount);
                        tr.find('input.balance').val(balance);


                        post_total_row();


                    }

                }
            }

            // 
        });




        // $success = true;
        // $msg = '';
        // if (rec_amount > subtotal) {

        //     $success = false; //if not successfull
        //     swal("Impossible!", "Received-amount  can not  be  grater than  Subtotal:)", "error");
        //     // $msg = 'Received amount can not be greater than subtotal';
        //     rec_amount = tr.find('input.received').val(parsedJson.sreceived);
        //     post_total_row();

        // } else {
        //     var balance = subtotal - rec_amount;
        //     $msg = 'Balance is successfully changed';
        // }
        // tr.find('input.subtotal').val(total);
        // here you find the same row as you found in the above codes and find the input box of subtotal(the input box has a class and its name is subtotal) and put the value of variable total that you operated in above code.






    });


    //Update input subtotal's value and input balance's value  if quantity's value is changed and check for "quantity"  not to be greater than the real number of  "quantity" which is in table of sell
    $('table#list_item_form_part tbody').on('change', 'input.quantityitem', function() {

        // input.quantity here .quantity is name of class which is given in input box
        // if input.quantity's value is changed than go to the table of #list_item_form_part tbody and add the final result .

        var tr = $(this).parents('tr');
        // here you find out the same row where receivedamount is changed ($(this).parents('tr')) and put it in the variable of tr.


        var qty = $(this).val();
        //  here you put the changed value of quantity ($(this).val()) in the variable of quantity .

        alert(qty)



        // var qty = tr.find('input.quantity').val();
        // here you find out the same row you found in above code and put the value of input.quantity(there is an editable input in the row with calss name of quantity) and put its value in the variable of qty.
        var price = tr.find('input.price').val();
        // here you find out the same row you found in above code and put the value of input.price(there is an editable input in the row with calss name of price) and put its value in the variable of price.

        var discount = tr.find('input.discount').val();

        // alert(qty + " " + price)
        // the above code alerts the value of variables qty and price

        // var total = (qty * price) - rec_amount;
        // here you multiply the value of variable "qty" and "price" and decrease it from rec_amount,finally, put its value in variable total.


        // var quantity = tr.find('input.quantity').val();
        var item_id = tr.find('input.item_id').val();


        var subtotal = tr.find('input.subtotal').val();
        // here you find out the class name for input box subtotal which is subtotal and get its value.
        var balan = tr.find('input.balance').val();


        var received = tr.find('input.receivedamount').val();

        // 

        $.ajax({
            type: "GET",
            url: '/bill/get_quantity',
            data: { 'id': item_id },
            success: function(result) {
                if (result) {
                    var parsedJson = $.parseJSON(result);

                    // var oldqty = tr.find('input.quantityitem').val(parsedJson.squantity);


                    if (parseInt(parsedJson.squantity) < parseInt(qty)) {
                        // here 'parsedjson.squantity' is the same '$item->squantity' 
                        swal("Impossible!", "Entered Quantity is greater than original quantity :)", "error");
                        tr.find('input.quantityitem').val(parsedJson.squantity);


                        post_total_row();


                    } else {
                        subtotal = (qty * price) - discount;
                        balan = subtotal - received;


                        tr.find('input.subtotal').val(subtotal);
                        tr.find('input.balance').val(balan);

                        post_total_row();
                    }

                }
            }

            // 
        });


        // var subtotal = tr.find('input.subtotal').val(total);
        // here you find the same row as you found in the above codes and find the input box of subtotal(the input box has a class and its name is subtotal) and put the value of variable total that you operated in above code.

        // post_total_row();





    });


    //Update input subtotal's value and input balance's value  if price's value is changed.
    $('table#list_item_form_part tbody').on('change', 'input.price', function() {

        var tr = $(this).parents('tr');


        var price = $(this).val();
        //  here you put the changed value of price ($(this).val()) in the variable of price .


        var qty = tr.find('input.quantityitem').val();
        var discount = tr.find('input.discount').val();
        var received = tr.find('input.receivedamount').val();



        var subt = (qty * price) - discount;
        var balan = subt - received;


        var finalsubtotal = tr.find('input.subtotal').val(subt);
        var finalbalance = tr.find('input.balance').val(balan);

        post_total_row();









    });

    //Update input subtotal's value and input balance's value  if discount's value is changed.
    $('table#list_item_form_part tbody').on('change', 'input.discount', function() {


        var discount = $(this).val();
        //  here you put the changed value of discount ($(this).val()) in the variable of discount .

        var tr = $(this).parents('tr');

        var qty = tr.find('input.quantityitem').val();
        var price = tr.find('input.price').val();
        var received = tr.find('input.receivedamount').val();

        var subt = (qty * price) - discount;
        var balan = subt - received;


        var finalsubtotal = tr.find('input.subtotal').val(subt);
        var finalbalance = tr.find('input.balance').val(balan);

        post_total_row();


        // window.alert("sdfsdffsd");
        // here alert is a method because it is connected to window 

        // alert("abc");
        // here alert is a function because it is not connected to any object and independently it is called and independently it functions.

    });





    function post_total_row() {
        // 

        var totalitem = 0;
        var totalamount = 0;
        var totaldiscount = 0;
        var totalreceived = 0;
        var totalbalance = 0;


        $('table#list_item_form_part tbody tr').each(function() {
            // here you find the table's id #list_item_form_part plus tbody (table body) and tr (table row) and apply loop to select all rows and calculate all values all rows which have the specific class of input box in a row

            // var qty = $(this).find('input.quantity').val();

            totalitem = totalitem + parseInt($(this).find('input.quantityitem').val());

            // alert(totalitem);

            totalamount = totalamount + parseInt($(this).find('input.subtotal').val());
            totaldiscount = totaldiscount + parseInt($(this).find('input.discount').val());
            totalreceived = totalreceived + parseInt($(this).find('input.receivedamount').val());
            totalbalance = totalbalance + parseInt($(this).find('input.balance').val());
        });



        $('input.totalitem').val(totalitem);
        $('input.totalamount').val(totalamount);
        $('input.totaldiscount').val(totaldiscount);
        $('input.totalreceived').val(totalreceived);
        $('input.totalbalnce').val(totalbalance);

    }


    // search();

    // function search() {
    //     var keyword = $('#search').val();
    //     $.post('{{ route("items.search") }}', {
    //             _token: $('meta[name="csrf-token"]').attr('content'),
    //             keyword: keyword
    //         },
    //         function(data) {
    //             table_post_row(data);
    //             console.log(data);
    //         });
    // }


    // // table row with ajax

    // function table_post_row(res) {
    //     let htmlView = '';
    //     if (res.items.length <= 0) {
    //         htmlView += `
    //        <tr>
    //           <td colspan="4">No Item Found.</td>
    //       </tr>`;
    //     }
    //     for (let i = 0; i < res.items.length; i++) {
    //         htmlView += `
    //         <tr>
    //            <td>` + (i + 1) + `</td>
    //               <td>` + res.items[i].cname + `</td>
    //                <td>` + res.items[i].name + `</td>
    //                <td>` + res.items[i].tag + `</td>
    //                <td>` + res.items[i].squantity + `</td>
    //                <td>` + res.items[i].sprice + `</td>
    //                <td>` + res.items[i].sdiscount + `</td>
    //                <td>` + res.items[i].sreceived + `</td>
    //                <td>` + res.items[i].sbalance + `</td>

    //         </tr>`;
    //     }
    //     $('tbody').html(htmlView);
    // }

    // 



    // click on a row of  and transfer the data of item_bill_table's row to row of next  #list_item_form_part's table

    // 

    // delete container
    $(document).on('click', 'p.shift-item', function() {
        var item_id = $(this).data("id")
        var row_index = $('#list_counter').val();
        item_id = parseInt(item_id)

        alert(item_id)

        $.ajax({
            method: "POST",
            url: '/bill/get_item_row',
            // below we have passed item_id, row_index and action to function present in controller , similarly, we can receive the same variables which come from function present in controller through json, return array or view.
            data: { 'item_id': item_id, 'row_index': row_index, 'action': 'add' },
            // dataType: "html",
            success: function(result) {
                if (result) {

                    // we use result to show that any output which comes from controller's function is stored in variable result.

                    //  how to parse json data with jquery javascript:
                    var parsedJson = $.parseJSON(result);

                    if (parsedJson.data == false) {
                        // alert("no item found!")
                        swal("Not Found!", "No item is found :)", "error");
                    } else {
                        $('#list_counter').val(parseInt(row_index) + 1);
                        $('#list_item_form_part  > tbody').append(parsedJson.content);

                        post_total_row();

                        // swal("Found!", "Item is successfully added.", "success");
                    }

                }
            }
        });
    });


    // filter item on basis of container_id

    $('.container_change').on('change', function() {

        var container_id = $(this).val();
        var cat_id = $('.category_change').val();

        if (!cat_id) {
            cat_id = 0
        }

        if (!container_id) {
            container_id = 0
        }



        // alert("container id: " + container_id + " | cat id: " + cat_id);


        $('#quickitem_table').DataTable().destroy();
        $('#quickitem_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/bill/quickitem' + '/' + container_id + '/' + cat_id,

            "order": [
                [0, 'desc']
            ],
            "pagingType": "full_numbers",
            "pageLength": 25,
            columnDefs: [{

                "targets": 7,
                "orderable": true,
                "searchable": true,
                "className": 'text-center',
            }],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'tag', name: 'tag' },
                { data: 'squantity', name: 'squantity' },
                { data: 'icname', name: 'icname' },
                { data: 'cname', name: 'cname' },
                { data: 'sellstatus', name: 'sellstatus' },
                { data: 'action', name: 'action' }
            ]
        });




    });


    // filter item on basis of itemcategory_id

    $('.category_change').on('change', function() {

        var cat_id = $(this).val();
        var container_id = $(".container_change").val();

        if (!cat_id) {
            cat_id = 0
        }

        if (!container_id) {
            container_id = 0
        }

        // alert("container id: " + container_id + " | cat id: " + cat_id);

        $('#quickitem_table').DataTable().destroy();

        $('#quickitem_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/bill/quickitem' + '/' + container_id + '/' + cat_id,

            "order": [
                [0, 'desc']
            ],
            "pagingType": "full_numbers",
            "pageLength": 25,
            columnDefs: [{

                "targets": 7,
                "orderable": true,
                "searchable": true,
                "className": 'text-center',
            }],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'tag', name: 'tag' },
                { data: 'squantity', name: 'squantity' },
                { data: 'icname', name: 'icname' },
                { data: 'cname', name: 'cname' },
                { data: 'sellstatus', name: 'sellstatus' },
                { data: 'action', name: 'action' }
            ]
        });

        // }



    });


    // 




    // date restriction

    var date = new Date();
    date.setDate(date.getDate() - 0);

    $('#reservationDate').datepicker({
        format: "yyyy/mm/dd",
        endtDate: date
    });

    // restricts date icon
    $("#reservationDate").datepicker();
    $('.fa-calendar').click(function() {
        $("#reservationDate").focus();
    });



});