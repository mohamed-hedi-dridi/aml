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
