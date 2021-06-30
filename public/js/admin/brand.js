jQuery(function() {
    load_content();
    var modal = $('#modalBrand');

    $(document).on('click', '#btnAdd', function () {
        $('#form-brand')[0].reset();
        modal.find('input#metode').val('tambah');
        $('#modal_title').text('Tambah Album Baru');
        modal.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(document).on('change', '#field-logo', function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              $('#brandPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
          } else {
            $('#brandPreview').attr('src', laroute.url('public/img/placeholder/brand.png', ['']));
          }
    });

    $("#form-brand").validate({
        onfocusout: function(element) {
            $(element).valid()
            if ($(element).valid()) {
                $('#form-brand').find('button:submit').prop('disabled', false);  
            } else {
                $('#form-brand').find('button:submit').prop('disabled', 'disabled');
            }
        },    
        errorClass: "invalid-feedback font-size-sm animated fadeInDown",
        errorElement: "div",
        errorPlacement: function (e, n) {
            jQuery(n).parents(".form-group").find('div.invalid-feedback').html(e);
        },
        highlight: function (e) {
            jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid");
        },
        success: function (e) {
            jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-valid");
            jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove();
        },
        rules: {
            name: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Name Brand Wajib Diisi!",
            },
        },
        submitHandler: function (form) {
            var fomr = $('form#form-brand')[0];
            var formData = new FormData(fomr);
            if($('#method').val() === "add")
            {
                url = laroute.route('admin.productBrand.save');
            }else{
                url = laroute.route('admin.productBrand.update');
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: '',
                        imageUrl: laroute.url('public/img/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function (response) {
                    if (response.fail == false) {
                        Swal.fire({
                            title: `Berhasil!`,
                            showConfirmButton: false,
                            icon: 'success',
                            html: response.pesan,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        $('.form-group').removeClass('is-invalid');
                        $('.form-group').removeClass('is-valid');
                        $('#form-brand')[0].reset();
                        modal.modal('hide');
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: "Periksa Form Input!",
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'error'
                        });
                        for (control in response.errors) {
                            $('#field-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                }
            });
            return false;
        }
    });

    $(document).on('click', '#btnEdit', function () { 
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('admin.productBrand.edit', { id: id }),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('public/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function(data) {
                Swal.close();
                $('#form-brand')[0].reset();
                $('.form-group').removeClass('is-valid');
                $('.form-group').removeClass('is-invalid');
                modal.modal({
                    backdrop: 'static',
                    keyboard: false
                });

                modal.find('h5.modal-title').html('Edit Brand');
                modal.find('input#field-id').val(data.id);
                modal.find('input#method').val('update');
                modal.find('input#field-name').val(data.name);
                modal.find('img#img_preview').attr("src", laroute.url(data.logo, ['']));
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
            }
        });
    });
    
    $(document).on('click', '.btn-hapus', function () { 
        id = $(this).attr('data-id');
        Swal.fire({
            title: "Anda Yakin?",
            text: "Data Yang Dihapus Tidak Akan Bisa Dikembalikan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak, Batalkan!',
            reverseButtons: true,
            allowOutsideClick: false,
            confirmButtonColor: '#af1310',
            cancelButtonColor: '#fffff',
        })
        .then((result) => {
            if (result.value) {
            $.ajax({
                url: laroute.route('admin.pengguna.hapus', { id: id }),
                type: "GET",
                dataType: "JSON",
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: ' ',
                        imageUrl: laroute.url('assets/img/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function() {
                    Swal.fire({
                        title: "Berhasil",
                        text: 'Pengguna Berhasil Dihapus!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    load_content();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
            } else {
                window.setTimeout(function(){
                    location.reload();
                } ,1500);
            }
        });
    });

});


function load_content()
{
    var page = $('#current_page').val();

    $.ajax({
        url: laroute.route('admin.productBrand'),
        type: "GET",
        dataType: "JSON",
        data: {
            page: page,
        },
        beforeSend: function(){
            $('#loading').removeClass('d-none');
        },
        success: function(response) {
            if(response.data.length !== 0){
                $.each(response.data, function(k, v) {
                    $('#data-list').append(`<div class="col-lg-3">
                    <div class="block block-shadow-2 block-bordered block-link-pop">
                        <div class="block-content flex-grow-1 p-0">
                            <img src="${ laroute.url('public/' + response.data[k].logo, ['']) }" class="img-fluid w-100"/>
                        </div>
                        <hr/ class="border-3x my-1">
                        <div class="block-content p-1">
                            <div class="row no-gutters">
                                <div class="col-lg-8 pl-2">
                                    <span class="font-size-24 font-weight-bold">${ response.data[k].name  }</span>
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-secondary btn-sm js-tooltip mx-1" id="btnEdit" data-toggle="tooltip" data-placement="top" title="Ubah"  data-id="${ response.data[k].id }">
                                            <i class="si si-note"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm js-tooltip mx-1" id="btnDelete" data-toggle="tooltip" data-placement="top" title="Hapus" data-id="${ response.data[k].id }">
                                            <i class="si si-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`);
                });
            }

            // Table Navigation
            $('#loading').addClass('d-none');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}


