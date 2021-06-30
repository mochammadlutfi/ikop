
jQuery(function() {
    var frm = $('#form-contact');

    $("#form-contact").on("submit", function (e) {
        e.preventDefault();
        var fomr = $('form#form-contact')[0];
        var formData = new FormData(fomr);

        $.ajax({
            url: laroute.route('contact.send'),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: 'Data Sedang Diproses!',
                    imageUrl: laroute.url('public/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                Swal.close();
                $('.is-invalid').removeClass('is-invalid');
                if (response.fail == false) {
                    $('#modal_embed').modal('hide');
                    Swal.fire({
                        title: `Berhasil!`,
                        showConfirmButton: false,
                        icon: 'success',
                        html: `Pesan Kamu Berhasil Terkirim!`,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                } else {
                    Swal.close();
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                        // $.notify({
                        //     icon: 'fa fa-times',
                        //     message: response.errors[control]
                        // }, {
                        //     delay: 7000,
                        //     type: 'danger'
                        // });
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error adding / update data');
            }
        });
    });
});