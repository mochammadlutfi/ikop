
jQuery(document).ready(function () {
    
    oTable = $('#list-pengguna').DataTable({
        processing: true,
        serverSide: true,
        ajax: laroute.route('admin.user'),
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'jenis',
                name: 'jenis'
            },
            {
                data: 'last_login_at',
                name: 'last_login_at',
            },
        ]
    });

    $('#search_box').keyup(function () {
        oTable.search($(this).val()).draw();
    });

    $(document).on('click', '#btn_tambah', function () {
        $('#form-pengguna')[0].reset();
        modal.find('input#metode').val('tambah');
        $('#modal_title').text('Tambah Pengguna Baru');
        modal.find('#field-password').parent().removeClass('d-none');
        modal.find('#field-konf_password').parent().removeClass('d-none');
        modal.modal({
            backdrop: 'static',
            keyboard: false
        })
    });

    $(document).on('click', '.btn-edit', function () { 
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('admin.pengguna.edit', { id: id }),
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
            success: function(data) {
                Swal.close();
                $('#form-pengguna')[0].reset();
                $('.form-group').removeClass('is-valid');
                $('.form-group').removeClass('is-invalid');
                modal.modal({
                    backdrop: 'static',
                    keyboard: false
                });

                // modal.find('h5.modal-title').html('Ubah Alamat');
                modal.find('input#field-id').val(data.id);
                modal.find('input#metode').val('update');
                modal.find('input#field-user_id').val(data.user_id);
                modal.find('input#field-nama').val(data.nama);
                modal.find('input#field-username').val(data.username);
                modal.find('input#field-email').val(data.email);
                modal.find('select#field-jabatan').val(data.jabatan);
                modal.find('#field-password').parent().addClass('d-none');
                modal.find('#field-konf_password').parent().addClass('d-none');
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
                    $('#list-pengguna').DataTable().ajax.reload();
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

    $("#form-pengguna").validate({
        onfocusout: function(element) {
            $(element).valid()
            if ($(element).valid()) {
                $('#form-pengguna').find('button:submit').prop('disabled', false);  
            } else {
                $('#form-pengguna').find('button:submit').prop('disabled', 'disabled');
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
            nama: {
                required: true,
            },
            username: {
                required: true,
            },
            email: {
                required: true,
            },
            jabatan: {
                required: true,
            },
            
        },
        messages: {
            nama: {
                required: "Nama Lengkap Wajib Diisi!",
            },
            username: {
                required: "Username Wajib Diisi!",
            },
            email: {
                required: "Alamat Email Wajib Diisi!",
            },
            jabatan: {
                required: "Jabatan Pengguna Wajib Diisi!",
            },
        },
        submitHandler: function (form) {
            var fomr = $('form#form-pengguna')[0];
            var formData = new FormData(fomr);
            if($('#metode').val() == 'tambah')
            {
                url = laroute.route('admin.pengguna.simpan');
            }else{
                url = laroute.route('admin.pengguna.update');
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
                        imageUrl: laroute.url('assets/img/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function (response) {
                    if (response.fail == false) {
                        Swal.fire({
                            title: "Berhasil",
                            text: response.pesan,
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'success'
                        });
                        $('.form-group').removeClass('is-invalid');
                        $('.form-group').removeClass('is-valid');
                        $('#list-pengguna').DataTable().ajax.reload();
                        modal.modal('toggle');
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
});