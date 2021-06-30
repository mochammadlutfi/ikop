jQuery(function() { 
    if($('#main-container').find('#data-list').length > 0){
        load_content();
    }
    var modal = $('#modalForm');

    $(document).on('click', '#btn-edit', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('kas.edit', { id: id }),
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
                $('#form-kas')[0].reset();
                $('.form-group').removeClass('is-valid');
                $('.form-group').removeClass('is-invalid');

                // modal.find('h5.modal-title').html('Ubah Alamat');
                modal.find('input#field-id').val(data.id);
                modal.find('input#method').val('update');
                modal.find('input[name="nama"]').val(data.nama);
                modal.find('input[name="simpanan"][value=' + data.simpanan + ']').prop('checked', true);
                modal.find('input[name="pengeluaran"][value=' + data.pengeluaran + ']').prop('checked', true);
                modal.find('input[name="status"][value=' + data.status + ']').prop('checked', true);
                modal.find('input[name="transfer"][value=' + data.transfer + ']').prop('checked', true);
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
                url: laroute.route('kas.delete', { id: id }),
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
        $('#form-kas')[0].reset();
        modal.find('input#method').val('create');
        $('#modal_title').text('Tambah Kas Baru');
        modal.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#form-kas").on("submit", function (e) {
        e.preventDefault();
        var fomr = $('form#form-kas')[0];
        var formData = new FormData(fomr);

        if($("#method").val() == 'update')
        {
            url = laroute.route('kas.update');
        }else{
            url = laroute.route('kas.store');
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

    // // Navigation Table
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
        url: laroute.route('kas'),
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
                var no = 1;
                $.each(response.data, function(k, v) {
                    var pengeluaran = response.data[k].pengeluaran === 1 ? '<span class="badge badge-primary">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                    var simpanan = response.data[k].simpanan === 1 ? '<span class="badge badge-primary">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                    var transfer = response.data[k].transfer === 1 ? '<span class="badge badge-primary">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                    var status = response.data[k].status === 1 ? '<span class="badge badge-primary">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>';
                    $('#data-list tbody').append(`
                        <tr>
                            <td>${ no++ }</td>
                            <td>${ response.data[k].nama }</td>
                            <td>${ simpanan }</td>
                            <td>${ pengeluaran }</td>
                            <td>${ transfer }</td>
                            <td>${ status }</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm js-tooltip" id="btn-edit" data-toggle="tooltip" data-placement="top" title="Ubah"  href="javascript:void(0)" data-id="${ response.data[k].id }">
                                    <i class="si si-note"></i>
                                </a>
                                <a class="btn btn-secondary btn-sm js-tooltip" id="btn-delete" data-toggle="tooltip" data-placement="top" title="Hapus" href="javascript:void(0);" data-id="${ response.data[k].id }">
                                    <i class="si si-trash"></i>
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
                var navigasi = 'Menampilkan Data '+ response.from +' - '+ response.to +' Dari '+ response.total;
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
