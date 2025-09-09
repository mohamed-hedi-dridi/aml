$( "#module" ).on( "change", function() {
    id = $("#module").val();
    route= "/admin/MenuSideBar/ajax/"+id;
    console.log(id);
    $.get(route, function(data){
        $( "#menus" ).html(data['parent']);
        $( "#permission" ).html(data['permission']);
      });
  } );
  $('.checkbox').change(function() {
    ///console.log($(this).prop('checked'));
    id = $(this).attr('data-id');
    var isChecked = $(this).prop('checked');
    $('.module' + id ).prop('checked', isChecked);

    });

$("#email").on('change', function() {
    email = $("#email").val();
    route = "/admin/Agent/exist/"+email;
    $.get(route, function(data){
        if(data == false){
            $('.saveAgent').prop("disabled",false);
            $('.email').removeClass('has-danger');
            $("#email").removeClass('form-control-danger');
            $('.email').addClass('has-success');
            $("#email").addClass("form-control-success");
            $('.form-control-feedback').text("");
        }else{
            $('.saveAgent').prop("disabled",true);
            $('.email').removeClass('has-success');
            $("#email").removeClass("form-control-success");
            $('.email').addClass('has-danger');
            $("#email").addClass("form-control-danger");
            $('.form-control-feedback').text("Email Déja exist");
        }
    });
})
let csrf_token = $('meta[name="csrf-token"]').attr('content');
$(".change").on('click', function() {
    statut = $(this).data('status');
    code = $(this).data('mandat');
    swal({
        title: 'Es-tu sûr?',
        text: "Vous ne pourrez pas revenir en arrière !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        confirmButtonText: 'Oui, mettez-le à jour !!',
        cancelButtonText: 'Annuler'
    }).then((result)=> {
        if (result.value == true) {
            var postData = {
                _token: csrf_token,
                statut: input_identity,
                code: code,
                // Add more key-value pairs as needed
            };
            $.ajax({
                url: '/admin/mandats/updateStatus',  // Replace with your actual delete route
                type: 'POST',
                data: postData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    //console.log(response);
                    if (response.statut == true) {
                        swal(
                            'Succès!',
                            'Statut Modifié avec succès.',
                            'success'
                        ).then(()=>{
                            location.reload();
                        })
                    }else{
                            swal(
                                {
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Quelque chose s\'mal passé !',
                                }
                            )

                    }
                },
                error: function (error) {
                    // Handle error
                    console.error(error);
                }
            })

        }
    })
})


$(".codeMandat").on('input', function(){
    code = $(this).val();
    if(code.length >2){
        input_identity = $('.input_identity').val();
        type = $('#type').val();
        url = '/admin/ajax/mandats?input_identity='+input_identity+'&code='+code+'&type='+type;
        console.log(url);
        /*fetch(url);
        return ;*/
        $.get(url, function(data){
            $('#index').hide();
            $('#result').html(data);
            $('#result').show();
        });
    }else{
        if ($('#result').length>0) {
            $('#result').hide();
            $('#index').show();
        }
    }

})

$(".input_identity").on('input', function(){
    input_identity = $(this).val();
    if(input_identity.length >3){
        code = $('.codeMandat').val();
        type = $('#type').val();
        url = '/admin/ajax/mandats?input_identity='+input_identity+'&code='+code+'&type='+type;
        console.log(url);

        /*fetch(url);
        return ;*/
        $.get(url, function(data){
            $('#index').hide();
            $('#result').html(data);
            $('#result').show();
        });
    }else{
        if ($('#result').length>0) {
            $('#result').hide();
            $('#index').show();
        }
    }
})


function fetch(url){
        $.ajax({
            url: url ,
            type:"GET",
            success: function(data){
                console.log(data);
                return 0 ;
                var i = 1 ;
                $('.data-table-export').DataTable().clear().destroy();;
                $('.data-table-export').DataTable({
                    scrollCollapse: true,
                    autoWidth: false,
                    responsive: true,
                    searching: true,
                    bLengthChange: false,
                    bPaginate: true,
                    bInfo: true,
                    columnDefs: [{
                        targets: "datatable-nosort",
                        orderable: false,
                    }],
                    "lengthMenu": [[25, 50,100, -1], [25, 50,100, "All"]],
                    "language": {
                        "info": "_START_-_END_ of _TOTAL_ entries",
                        searchPlaceholder: "Search",
                        paginate: {
                            next: '<i class="ion-chevron-right"></i>',
                            previous: '<i class="ion-chevron-left"></i>'
                        }
                    },
                    dom: 'Blfrtp',
                    buttons: [
                        'copy', 'csv', 'pdf', 'print'
                    ],
                    "data": data.Easy,
                    "columns" : [
                        {
                            "data" : "id",
                            "render": function(data, type, row, meta) {
                                    return i++;
                                }
                        },
                        {
                            "data" : "code",
                        },
                        {
                            "data" : "DateHeure",
                        },
                        {
                            "data" : "id",
                            "render": function(data, type, row, meta) {
                                    return row.beneficiary_first_name+" "+row.beneficiary_last_name;
                                }
                        },
                        {
                            "data" : "id",
                            "render": function(data, type, row, meta) {
                                    return numberFormat(row.amount, 3, '.', '')+ ' TND';
                                }
                        },
                        {
                            "data" : "agent",
                        },
                        {
                            "data" : "name",
                        },
                        {
                            "data" : "state",
                        },
                        {
                            "data" : "id",
                            "render": function(data, type, row, meta) {
                                    if (row.status == "Cancelled") {
                                        return "<span class='badge badge-danger'> Annulé </span>";
                                    }else if (row.status == "Paid") {
                                        return "<span class='badge badge-success'> Payé </span>"
                                    }else{
                                        return "<span class='badge badge-warning'> En cours </span>"
                                    }
                                }
                        },
                        {
                            "data" : "KYC",
                        },
                        {
                            "data" : "id",
                            "render": function(data, type, row, meta) {
                                    return ZepzAction(row.code);
                                }
                        }
                    ],
                });
            },
            error: function(xhr, status, error) {
                alert ("API error:", error);
                $('.data-table-export').DataTable().clear().destroy();
                $('.data-table-export').DataTable({
                    data: [],
                    columns: [
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" },
                        { data: null, defaultContent: "" }
                    ]
                });

            }
        })
    }
