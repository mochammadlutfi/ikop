jQuery(document).ready(function () {
    load_content();
    var modal = $('#modalAlbum');
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
                height: 181,
                type: 'square'
            },
            original : {
                width: 440,
                height: 181,
                type: 'square'
            },
            boundary: {
                width: 460,
                height: 190
            },
            enableOrientation: true
        });
        $.getImage(event.target, croppie);
    });

    $("#field-type").on("change", function() {
       var type = $(this).val();
        if(type === '1' || type == '2')
        {
            $('#slug').removeClass('d-none');
            $('#link').addClass('d-none');
            if(type === '1')
            {
                link = laroute.route('slider.post_json');
                placeholder = 'Pilih Berita';
            }else if(type === '2')
            {     
                link = laroute.route('slider.pages_json');
                placeholder = 'Pilih Halaman';
            }
            $("#field-slug").select2({
                placeholder: placeholder,
                theme : 'bootstrap4',
                ajax: {
                    url: link,
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term
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
        
        }else if(type === '3')
        {
            $('#slug').addClass('d-none');
            $('#link').removeClass('d-none');
        }
    });

    $("#upload").on("click", function() {
        croppie.result({
            type: 'base64',
            size: 'original',
			format:'jpeg',
			size: { 
                width: 1200, height: 496 
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

    $(document).on('click', '#btn_tambah', function () {
        $('#form-album')[0].reset();
        modal.find('input#metode').val('tambah');
        $('#modal_title').text('Tambah Album Baru');
        modal.modal({
            backdrop: 'static',
            keyboard: false
        })
    });

    $("#form-slide").validate({
        onfocusout: function(element) {
            $(element).valid()
            if ($(element).valid()) {
                $('#form-slide').find('button:submit').prop('disabled', false);  
            } else {
                $('#form-slide').find('button:submit').prop('disabled', 'disabled');
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
            type: {
                required: true,
            },
            
        },
        messages: {
            type: {
                required: "Jenis Slide Wajib Diisi!",
            },
        },
        submitHandler: function (form) {
            var fomr = $('form#form-slide')[0];
            var formData = new FormData(fomr);
            if($('#metode').val() == 'tambah')
            {
                url = laroute.route('slider.simpan');
            }else{
                url = laroute.route('slider.update');
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
                            html: response.pesan +`<br><br>
                                <a href="`+ laroute.route('slider') +`" class="btn btn-keluar btn-danger">
                                    <i class="si si-close mr-1"></i>Keluar
                                </a> 
                                <a href="`+ laroute.route('slider.tambah') +`" class="btn btn-tambah_baru btn-primary">
                                    <i class="si si-plus mr-1"></i>Tambahkan Slide Lain!
                                </a>`,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        $('.form-group').removeClass('is-invalid');
                        $('.form-group').removeClass('is-valid');
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

    $(document).on('click', '.btn-delete', function () { 
        var id = $(this).attr('data-id');
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
                url: laroute.route('slider.hapus', { id: id }),
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
                    Swal.fire({
                        title: "Berhasil",
                        text: 'Data Berhasil Dihapus!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    load_content();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    alert('Error deleting data');
                }
            });
            }else{
                Swal.close();
            }
        });
    });
});

function load_content()
{
    var page = $('#current_page').val();
    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');

    $.ajax({
        url: laroute.route('slider'),
        type: "GET",
        dataType: "JSON",
        data: {
            page: page,
        },
        beforeSend: function(){
            $('#loading').removeClass('d-none');
            $('#data-list').html('');
        },
        complete: function(){
            $('#loading').addClass('d-none');
        },
        success: function(response) {
            $('#data-list').html('');
            dataList = ``;
            if(response.data.length !== 0){
                $.each(response.data, function(k, v) {
                    dataList += create_element(response.data[k]);
                });
            }
            $('#data-list').append(dataList);

            response.next_page_url !== null ? navNext.prop('disabled', false) : navNext.prop('disabled', true);
            response.prev_page_url !== null ? navPrev.prop('disabled', false) : navPrev.prop('disabled', true);
            if(response.total === 0){
                var navigasi = '0 - 0 / 0';
            }else{
                var navigasi = response.from +' - '+ response.to +' / '+ response.total;
            }
            $('#content-nav span').html(navigasi);
            // Table Navigation
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}
function create_element(item){
    let row = `<div class="col-lg-4">
    <div class="block block-shadow block-rounded block-link-pop" href="{{ route('galeri.detail', $g->slug) }}">
        <div class="block-content flex-grow-1 p-0">
            <img src="${ item.img_url }" class="img-fluid"/>
        </div>
        <div class="block-content p-1">
            <div class="row">
                <div class="col-lg-8">
                ${ item.status_badge }
                </div>
                <div class="col-lg-4 text-right">
                    <a class="btn btn-secondary btn-sm js-tooltip" data-toggle="tooltip" data-placement="top" title="Ubah"  href="${ laroute.route('slider.edit', {id : item.id }) }">
                        <i class="si si-note"></i>
                    </a>
                    <button class="btn btn-secondary btn-sm js-tooltip btn-delete" data-toggle="tooltip" data-placement="top" title="Hapus" type="button" data-id="${ item.id }">
                        <i class="si si-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>`;
    return row;
}


function hapus(id) {
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
            url: laroute.route('slider.hapus', { id: id }),
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
                Swal.fire({
                    title: "Berhasil",
                    text: 'Data Berhasil Dihapus!',
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                load_content();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
            }
        });
        }else{
            Swal.close();
        }
    });
}
