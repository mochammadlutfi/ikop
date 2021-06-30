$("#field-app_logo").change(function (event) {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        var filename = $("#field-app_logo").val();
        filename = filename.substring(filename.lastIndexOf('\\') + 1);
        reader.onload = function (e) {
            // debugger;
            $('#logo_preview').attr('src', e.target.result);
            $('.label-logo').text(filename);
        }
        reader.readAsDataURL(this.files[0]);
    }
});

$("#field-app_favicon").change(function (event) {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        var filename = $("#field-app_favicon").val();
        filename = filename.substring(filename.lastIndexOf('\\') + 1);
        reader.onload = function (e) {
            // debugger;
            $('#icon_preview').attr('src', e.target.result);
            $('.label-favicon').text(filename);
        }
        reader.readAsDataURL(this.files[0]);
    }
});


$("#form_general").submit(function(e) {
    e.preventDefault();
    var formData = new FormData($('#form_general')[0]);
    $.ajax({
        url : laroute.route('admin.pengaturan.umum'),
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
            Swal.fire({
                title: 'Tunggu Sebentar...',
                text: ' ',
                imageUrl: laroute.url('assets/img/loading.gif', ['']),
                showConfirmButton: false,
                allowOutsideClick: false,
            });
        },
        success: function (response){
            $('.is-invalid').removeClass('is-invalid');
            if (response.fail == false) {
                $('#import_modal').modal('hide');
                Swal.fire({
                    title: "Berhasil",
                    text: "Pengaturan Berhasil Diperbaharui!",
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                window.setTimeout(function () {
                    location.reload();
                }, 1500);
            }else{
                Swal.close();
                for (control in response.errors) {
                    $('#field-' + control).addClass('is-invalid');
                    $('#error-' + control).html(response.errors[control]);
                    $.notify({
                        icon: 'fa fa-times',
                        message: response.errors[control]
                    },{
                        delay: 7000,
                        type: 'danger'
                    });
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Swal.close();
            alert('Error adding / update data');
        }
    });
});

$("#form-kontak").submit(function(e) {
    e.preventDefault();
    var formData = new FormData($('#form-kontak')[0]);
    $.ajax({
        url : laroute.route('admin.pengaturan.kontak'),
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
            Swal.fire({
                title: 'Tunggu Sebentar...',
                text: ' ',
                imageUrl: laroute.url('assets/img/loading.gif', ['']),
                showConfirmButton: false,
                allowOutsideClick: false,
            });
        },
        success: function (response){
            $('.is-invalid').removeClass('is-invalid');
            if (response.fail == false) {
                $('#import_modal').modal('hide');
                Swal.fire({
                    title: "Berhasil",
                    text: "Pengaturan Berhasil Diperbaharui!",
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                window.setTimeout(function () {
                    location.reload();
                }, 1500);
            }else{
                Swal.close();
                for (control in response.errors) {
                    $('#field-' + control).addClass('is-invalid');
                    $('#error-' + control).html(response.errors[control]);
                    $.notify({
                        icon: 'fa fa-times',
                        message: response.errors[control]
                    },{
                        delay: 7000,
                        type: 'danger'
                    });
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Swal.close();
            alert('Error adding / update data');
        }
    });
});

$("#form-sosmed").submit(function(e) {
    e.preventDefault();
    var formData = new FormData($('#form-sosmed')[0]);
    $.ajax({
        url : laroute.route('admin.pengaturan.sosmed'),
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
            Swal.fire({
                title: 'Tunggu Sebentar...',
                text: ' ',
                imageUrl: laroute.url('assets/img/loading.gif', ['']),
                showConfirmButton: false,
                allowOutsideClick: false,
            });
        },
        success: function (response){
            $('.is-invalid').removeClass('is-invalid');
            if (response.fail == false) {
                $('#import_modal').modal('hide');
                Swal.fire({
                    title: "Berhasil",
                    text: "Pengaturan Berhasil Diperbaharui!",
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                window.setTimeout(function () {
                    location.reload();
                }, 1500);
            }else{
                Swal.close();
                for (control in response.errors) {
                    $('#field-' + control).addClass('is-invalid');
                    $('#error-' + control).html(response.errors[control]);
                    $.notify({
                        icon: 'fa fa-times',
                        message: response.errors[control]
                    },{
                        delay: 7000,
                        type: 'danger'
                    });
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Swal.close();
            alert('Error adding / update data');
        }
    });
});


$("#form-smtp").submit(function(e) {
    e.preventDefault();
    var formData = new FormData($('#form-smtp')[0]);
    $.ajax({
        url : laroute.route('admin.pengaturan.smtp'),
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
            Swal.fire({
                title: 'Tunggu Sebentar...',
                text: ' ',
                imageUrl: laroute.url('assets/img/loading.gif', ['']),
                showConfirmButton: false,
                allowOutsideClick: false,
            });
        },
        success: function (response){
            $('.is-invalid').removeClass('is-invalid');
            if (response.fail == false) {
                $('#import_modal').modal('hide');
                Swal.fire({
                    title: "Berhasil",
                    text: "Pengaturan Berhasil Diperbaharui!",
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                window.setTimeout(function () {
                    location.reload();
                }, 1500);
            }else{
                Swal.close();
                for (control in response.errors) {
                    $('#field-' + control).addClass('is-invalid');
                    $('#error-' + control).html(response.errors[control]);
                    $.notify({
                        icon: 'fa fa-times',
                        message: response.errors[control]
                    },{
                        delay: 7000,
                        type: 'danger'
                    });
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Swal.close();
            alert('Error adding / update data');
        }
    });
});
