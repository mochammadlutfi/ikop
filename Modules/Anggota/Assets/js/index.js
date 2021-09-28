jQuery(function() { 
    load_content();
    var modal = $('#modalForm');
    
    var daerah = $('#field-wilayah').select2({
        placeholder: 'Cari Kelurahan',
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('wilayah.jsonSelect'),
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
        },
        
        minimumInputLength: 3,
        // templateResult: formatResult,
        templateResult: function(response) {
            if(response.loading)
            {
                return "Mencari...";
            }else{
                var selectionText = response.text.split(",");
                var $returnString = $('<span>'+selectionText[0] + ', ' + selectionText[1] + '</br>' + selectionText[2]+ ', ' + selectionText[3] +'</span>');
                return $returnString;
            }
        },
        templateSelection: function(response) {
            return response.text;
        },
    });

    $(document).on('click', '#btn-edit', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('cabang.edit', { id: id }),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('public/media/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function(data) {
                Swal.close();
                $('#form-cabang')[0].reset();
                $('.form-group').removeClass('is-valid');
                $('.form-group').removeClass('is-invalid');

                // modal.find('h5.modal-title').html('Ubah Alamat');
                modal.find('input#field-id').val(data.id);
                modal.find('input#method').val('update');
                modal.find('input#field-nama').val(data.nama);
                modal.find('input#field-kode_pos').val(data.kode_pos);
                modal.find('textarea#field-alamat').val(data.alamat);
                sel_option = new Option(data.daerah, data.wilayah_id, true, true);
                daerah.append(sel_option).trigger('change');
                modal.modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
            }
        });
    });

    $(document).on('click', '#btn-delete', function () {
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
                url: laroute.route('cabang.hapus', { id: id }),
                type: "GET",
                dataType: "JSON",
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: ' ',
                        imageUrl: laroute.url('public/media/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function(response) {
                    if(response.fail === false)
                    {
                        Swal.fire({
                            title: "Berhasil",
                            text: 'Data Berhasil Dihapus!',
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'success'
                        });
                        load_content();
                    }else{
                        Swal.fire({
                            title: "Gagal",
                            text: 'Data Gagal Dihapus!',
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'warning'
                        });
                    }
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

    $('#btn-add').on("click", function(){
        $('#form-cabang')[0].reset();
        modal.find('input#method').val('create');
        $('#modal_title').text('Tambah Cabang Baru');
        modal.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#form-cabang").on("submit", function (e) {
        e.preventDefault();
        var fomr = $('form#form-cabang')[0];
        var formData = new FormData(fomr);

        if($("#method").val() == 'update')
        {
            url = laroute.route('cabang.update');
        }else{
            url = laroute.route('cabang.save');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: 'Data Sedang Diproses!',
                    imageUrl: laroute.url('public/media/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                Swal.close();
                $('.is-invalid').removeClass('is-invalid');
                if (response.fail === false) {
                    modal.modal('hide');
                    Swal.fire({
                        title: `Berhasil!`,
                        icon: 'success',
                        timer: 3000,
                        html: `Data Baru Berhasil Disimpan!`,
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                    load_content();
                } else {
                    Swal.close();
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                        $.notify({
                            // options
                            icon: 'fa fa-times',
                            message: response.errors[control]
                        }, {
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

    // Filter Table
    $('#search-data-list').on('input', function(){
        clearTimeout(this.delay);
        this.delay = setTimeout(function(){
           $(this).trigger('search');
        }.bind(this), 800);
    }).on('search', function(){
        load_content();
        $('#current_page').val(1);
    });

    // Navigation Table
    $('#next-data-list').on('click', function(){
        old = parseInt($('#current_page').val());
        old += 1;
        $('#current_page').val(old);
        load_content();
    });

    $('#prev-data-list').on('click', function(){
        old = parseInt($('#current_page').val());
        old -= 1;
        $('#current_page').val(old);
        load_content();
    });
});


function load_content(){
    var keyword = $('#search-data-list').val();
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');
    
    $.ajax({
        url: laroute.route('anggota'),
        type: "GET",
        dataType: "JSON",
        data: {
            keyword: keyword,
            page: page,
        },
        beforeSend: function(){
            $('#data-list tbody tr#loading').removeClass('d-none');
            navNext.prop('disabled', true);
            navPrev.prop('disabled', true);
        },
        success: function(response) {
            $('#data-list tbody tr').not('#data-list tbody tr#loading').remove();
            if(response.data.length !== 0){
                $.each(response.data, function(k, v) {
                    $('#data-list tbody').append(`
                        <tr>
                            <td>${ response.data[k].anggota_id }</td>
                            <td>${ response.data[k].nama }</td>
                            <td>
                                <div class="font-size-16 font-w600">${ response.data[k].no_hp }</div>
                                <div class="font-size-15">${ response.data[k].email == "" ? "" :  response.data[k].email}</div>
                            </td>
                            <td>${ response.data[k].alamat[0].alamat_lengkap }</td>
                            <td>${ response.data[k].status_badge }</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm js-tooltip" data-toggle="tooltip" data-placement="top" title="Detail Anggota" href="${ laroute.route('anggota.detail', { id: response.data[k].anggota_id }) }">
                                    <i class="si si-magnifier"></i> Detail
                                </a>
                            </td>
                        </tr>              
                    `);
                });
            }else{

                $('#data-list tbody').append(`
                <tr>
                    <td colspan="6">
                        <div class="text-center">
                            <img class="img-fluid" src="`+ laroute.url('public/img/icon/not_found.png', ['']) +`">
                            <div>
                                <h3 class="font-size-24 font-w600 mt-3">Data Tidak Ditemukan</h3>
                            </div>
                        </div>
                    </td>
                </tr>          
                `);
            }

            // Table Navigation
            response.next_page_url !== null ? navNext.prop('disabled', false) : navNext.prop('disabled', true);
            response.prev_page_url !== null ? navPrev.prop('disabled', false) : navPrev.prop('disabled', true);
            if(response.total === 0){
                var navigasi = 'Menampilkan Data 0 - 0 Dari 0';
            }else{
                var navigasi = response.from +' - '+ response.to +' / '+ response.total;
            }
            $('#content-nav span').html(navigasi);
            $('#data-list tbody tr#loading').addClass('d-none');
            // End Table Navigation

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}
