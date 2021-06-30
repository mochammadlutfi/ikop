jQuery(document).ready(function () {
    // Crop Start
    var croppie = null;
    var cropModal = $("#cropModal");
    var el = document.getElementById('resizer');
    var formData = new FormData();

    $.base64ImageToBlob = function(str) {
        var pos = str.indexOf(';base64,');
        var type = str.substring(5, pos);
        var b64 = str.substr(pos + 8);
        var imageContent = atob(b64);
        var buffer = new ArrayBuffer(imageContent.length);
        var view = new Uint8Array(buffer);
        for (var n = 0; n < imageContent.length; n++) {
          view[n] = imageContent.charCodeAt(n);
        }
        var blob = new Blob([buffer], { type: type });
        return blob;
    }

    $.getImage = function(input, croppie) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                croppie.bind({
                    url: e.target.result,
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#file-upload").on("change", function(event) {
        cropModal.modal();
        croppie = new Croppie(el, {
            viewport: {
                width: 440,
                height: 240,
                type: 'square'
            },
            original : {
                width: 440,
                height: 240,
                type: 'square'
            },
            boundary: {
                width: 460,
                height: 260
            },
            enableOrientation: true
        });
        $.getImage(event.target, croppie);
    });

    $("#upload").on("click", function() {
        croppie.result({
            type: 'base64',
            size: 'original',
			format:'jpeg',
			size: { 
                width: 1200, height: 675 
            }
        }).then(function(base64) {
            cropModal.modal("hide");
            $("#img_preview").attr("src", base64);
            $("#featured_img").val(base64);
        });
    });

    $(".rotate").on("click", function() {
        croppie.rotate(parseInt($(this).data('deg')));
    });

    cropModal.on('hidden.bs.modal', function (e) {
        setTimeout(function() { 
            croppie.destroy(); 
        }, 100);
    });
    
    // Crop End
    kategoriSelect = $("#field-kategori").select2({
        placeholder: 'Pilih Kategori',
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('admin.postKategori.json'),
            type: 'POST',
            dataType: 'JSON',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    kategoriOption = new Option($('#field-kategori').attr("data-text"), $('#field-kategori').attr("data-id"), true, true);
    kategoriSelect.append(kategoriOption).trigger('change');

    $('#field-deskripsi').summernote({
        height: '350px',
        callbacks: {
            onMediaDelete : function(target) {
                hapusFile(target[0].src);
            }
        }
    });

    $("#form-post").submit(function (e) {
        e.preventDefault();
        var fomr = $('form#form-post')[0];
        var formData = new FormData(fomr);
        $.ajax({
            url: laroute.route('admin.post.update'),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: 'Data Sedang Diproses!',
                    imageUrl: laroute.url('assets/img/loading.gif', ['']),
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
                        icon: 'success',
                        html: `Berita Berhasil Diperbaharui!
                            <br><br>
                            <a type="button" class="btn btn-keluar btn-alt-danger" href="`+laroute.route('admin.post')+`"><i class="si si-close mr-1"></i>Keluar</a> 
                            <a type="button" class="btn btn-tambah_baru btn-alt-primary" href="`+laroute.route('admin.post.tambah')+`"><i class="si si-plus mr-1"></i>Tambah Berita Lain</a>`,
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                } else {
                    Swal.close();
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                        $.notify({
                            // options
                            icon: 'fa fa-times',
                            message: response.errors[control]
                        },{
                            // settings
                            delay: 7000,
                            type: 'danger'
                        });
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

function hapusFile(src){
    $.ajax({
        data: {
            src : src,
            post_id : $('#post_id').val(),
            content : $('#field-deskripsi').val()
        },
        type: "POST",
        url: laroute.route('admin.post.hapusFile'),
        cache: false,
        beforeSend: function(){
            Swal.fire({
                title: 'Tunggu Sebentar...',
                text: 'Data Sedang Diproses!',
                imageUrl: laroute.url('assets/img/loading.gif', ['']),
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
                    title: "Berhasil",
                    text: 'File Berhasil Dihapus!',
                    timer: 1500,
                    showConfirmButton: false,
                    icon: 'success'
                });
            } else {
                Swal.close();
                for (control in response.errors) {
                    $('#field-' + control).addClass('is-invalid');
                    $('#error-' + control).html(response.errors[control]);
                    $.notify({
                        // options
                        icon: 'fa fa-times',
                        message: response.errors[control]
                    },{
                        // settings
                        delay: 7000,
                        type: 'danger'
                    });
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.close();
            alert('Error adding / update data');
        }
    });
}