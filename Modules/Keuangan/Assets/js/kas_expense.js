jQuery(function() { 
    if($('#main-container').find('#data-list').length > 0){
        load_content();
    }
    var modal = $('#modalForm');

    var akun = $("#field-akun_id").select2({
        placeholder: 'Pilih Akun',
        allowClear: true,
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('akun.select2'),
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
    }).on('select2:unselecting', function(e) {
        $(this).val(null).trigger('change');
        e.preventDefault();
    });

    var kas = $("#field-kas_id").select2({
        placeholder: 'Pilih Kas',
        allowClear: true,
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('kas.select2'),
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
    }).on('select2:unselecting', function(e) {
        $(this).val(null).trigger('change');
        e.preventDefault();
    });

    $(document).on('click', '#btn-edit', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('kas.expense.edit', { id: id }),
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

                modal.find('#modal_title').text('Ubah Pengeluaran Kas');
                modal.find('input#field-id').val(data.kd_trans_kas);
                modal.find('input#method').val('update');
                modal.find('input[name="tgl"]').val(data.tgl);
                modal.find('input[name="nominal"]').val(data.jumlah);
                modal.find('input[name="keterangan"]').val(data.keterangan);
                
                akun_sel_option = new Option(data.akun.nama, data.akun_id, true, true);
                akun.append(akun_sel_option).trigger('change');

                kas_sel_option = new Option(data.kas.nama, data.kas_id, true, true);
                kas.append(kas_sel_option).trigger('change');

                if($('input').hasClass('input-currency')){
                    new AutoNumeric(".input-currency", {
                        allowDecimalPadding: false,
                        alwaysAllowDecimalCharacter: true,
                        caretPositionOnFocus: "start",
                        currencySymbol: "Rp ",
                        decimalCharacter: ",",
                        decimalPlaces: 0,
                        digitGroupSeparator: ".",
                        unformatOnSubmit: true
                    });
                }
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
        $('#modal_title').text('Tambah Pengeluaran Kas');
        modal.modal({
            backdrop: 'static',
            keyboard: false
        });

        
        if($('input').hasClass('input-currency')){
            new AutoNumeric(".input-currency", {
                allowDecimalPadding: false,
                alwaysAllowDecimalCharacter: true,
                caretPositionOnFocus: "start",
                currencySymbol: "Rp ",
                decimalCharacter: ",",
                decimalPlaces: 0,
                digitGroupSeparator: ".",
                unformatOnSubmit: true
            });
        }

    });

    $("#form-kas").on("submit", function (e) {
        e.preventDefault();
        var fomr = $('form#form-kas')[0];
        var formData = new FormData(fomr);

        if($("#method").val() == 'update')
        {
            url = laroute.route('kas.expense.update');
        }else{
            url = laroute.route('kas.expense.store');
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

    var showOptionCurrency = {
        allowDecimalPadding: false,
        alwaysAllowDecimalCharacter: true,
        caretPositionOnFocus: "start",
        currencySymbol: "Rp ",
        decimalCharacter: ",",
        decimalPlaces: 0,
        digitGroupSeparator: ".",
        unformatOnSubmit: true,
    };

    if($('div').hasClass('currency')){
        new AutoNumeric.multiple(".currency", showOptionCurrency);
    
    }

    $('#field-tgl').datetimepicker({
        "format": "DD-MM-YYYY",
        "dayViewHeaderFormat": "MMMM YYYY",
        "locale": moment.locale('id'),
        "sideBySide": true,
        "widgetPositioning": {
            "horizontal": "auto",
            "vertical": "auto"
        },
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
        url: laroute.route('kas.expense'),
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
                        <tr class="c-pointer" onclick="detail('${ response.data[k].kd_trans_kas }')">
                            <td>${ response.data[k].kd_trans_kas }</td>
                            <td>${ moment(response.data[k].created_at).format('DD-MM-YYYY') }</td>
                            <td>${ response.data[k].keterangan }</td>
                            <td>${ response.data[k].kas.nama }</td>
                            <td>${ response.data[k].akun.nama }</td>
                            <td><div class="currency">${ response.data[k].jumlah }</div></td>
                            <td>${ response.data[k].user.anggota.nama }</td>
                        </tr>              
                    `);
                });
            }else{

                $('#data-list tbody').append(`
                <tr>
                    <td colspan="7">
                        <div class="text-center">
                            <img class="img-fluid" src="`+ laroute.url('public/media/placeholder/empty.png', ['']) +`">
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
            
            new AutoNumeric.multiple(".currency", {
                allowDecimalPadding: false,
                alwaysAllowDecimalCharacter: true,
                caretPositionOnFocus: "start",
                currencySymbol: "Rp ",
                decimalCharacter: ",",
                decimalPlaces: 0,
                digitGroupSeparator: ".",
                unformatOnSubmit: true,
            });

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}


function detail(id){
    window.document.location = laroute.route('kas.expense.detail', { id: id });
}