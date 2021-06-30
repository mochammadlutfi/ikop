jQuery(function() {
    moment.locale('id');
    $('[data-toggle="tabajax"]').on("click", function(e) {
        var $this = $(this),
            loadurl = $this.attr('href'),
            targ = $this.attr('data-target');
            anggota_id = $this.attr('data-id');
    
            if(targ == '#simpanan'){
                load_simpanan();
            }else if(targ == '#transaksi'){
                // load_transaksi(loadurl);
            }
    
        $this.tab('show');
        return false;
    });

    $(document).on('click', '#ubahProfile', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('anggota.get_info', { id: id }),
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
                Swal.close();
                $('#form-profil')[0].reset();
                $('.form-group').removeClass('is-valid');
                $('.form-group').removeClass('is-invalid');

                var modal = $('#modalProfil');
                modal.find('input#field-anggota_id').val(response.anggota_id);
                modal.find('input#field-no_ktp').val(response.no_ktp);
                modal.find('input#field-nama').val(response.nama);
                modal.find('input#field-tmp_lahir').val(response.tmp_lahir);
                modal.find("input:radio[name=jk][value=" + response.jk + "]").attr("checked", "checked");
                modal.find('input#field-tgl_lahir').val(moment(response.tgl_lahir).format('D-M-Y'));
                modal.find("input:radio[name=status_pernikahan][value=" + response.status_pernikahan + "]").attr("checked", "checked");
                modal.find('input#field-no_hp').val(response.no_hp);
                modal.find('input#field-no_telp').val(response.no_telp);
                modal.find('input#field-email').val(response.email);
                modal.find('select#field-pendidikan').val(response.pendidikan);
                modal.find('select#field-pekerjaan').val(response.pekerjaan);
                modal.find('input#field-nama_ibu').val(response.nama_ibu);
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

    $("#form-profil").on("submit", function (e) {
        e.preventDefault();
        var fomr = $('form#form-profil')[0];
        var formData = new FormData(fomr);

        $.ajax({
            url: laroute.route('anggota.updateProfil'),
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
                    $("#modalProfil").modal('hide');
                    Swal.fire({
                        title: `Berhasil!`,
                        icon: 'success',
                        timer: 3000,
                        html: `Data Berhasil Disimpan!`,
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

    
    // Navigation Table
    $('#next-data-list').on('click', function(){
        old = parseInt($('#current_page').val());
        old += 1;
        $('#current_page').val(old);
        load_simpanan();
    });

    $('#prev-data-list').on('click', function(){
        old = parseInt($('#current_page').val());
        old -= 1;
        $('#current_page').val(old);
        load_simpanan();
    });
});

// function load_simpanan(loadurl){
//     $.ajax({
//         url: loadurl,
//         type: "GET",
//         dataType: "JSON",
//         success: function(response) {
//             $('#simpanan-list').html('')
//             if(response.simpanan.length !== 0){
//                 $.each(response.simpanan, function(k, v) {
//                     $('#simpanan-list').append(`
//                     <div class="col-md-6 col-xl-3">
//                         <a class="block text-center block-rounded block-shadow block-bordered" href="javascript:void(0)">
//                             <div class="block-content bg-body-light py-10">
//                                 <p class="font-size-sm font-w600 text-uppercase text-muted mb-0">${ response.simpanan[k].program }</p>
//                             </div>
//                             <div class="block-content block-content-full">
//                                 <div class="font-size-h2 font-w700 currency">${ response.simpanan[k].saldo }</div>
//                             </div>
//                         </a>
//                     </div>
//                     `);
//                 });
//             }

//             new AutoNumeric.multiple(".currency", {
//                 allowDecimalPadding: false,
//                 alwaysAllowDecimalCharacter: true,
//                 caretPositionOnFocus: "start",
//                 currencySymbol: "Rp ",
//                 decimalCharacter: ",",
//                 decimalPlaces: 0,
//                 digitGroupSeparator: ".",
//                 unformatOnSubmit: true,
//             });
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             Swal.close();
//             alert('Error deleting data');
//         }
//     });
// }


function load_simpanan(loadurl){
    var keyword = $('#search-data-list').val();
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');
    var anggota_id = $('#anggota_id').val();
    $.ajax({
        url: laroute.route('anggota.detail.simpanan', { id: anggota_id}),
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
            $('#simpanan-list').html('')
            if(response.simpanan.length !== 0){
                $.each(response.simpanan, function(k, v) {
                    $('#simpanan-list').append(`
                    <div class="col-md-6 col-xl-3">
                        <a class="block text-center block-rounded block-shadow block-bordered" href="javascript:void(0)">
                            <div class="block-content bg-body-light py-10">
                                <p class="font-size-sm font-w600 text-uppercase text-muted mb-0">${ response.simpanan[k].program }</p>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="font-size-h2 font-w700 currency">${ response.simpanan[k].saldo }</div>
                            </div>
                        </a>
                    </div>
                    `);
                });
            }

            if(response.riwayat.data.length !== 0){
                $.each(response.riwayat.data, function(k, v) {
                    $('#data-list tbody').append(`
                        <tr>
                            <td>${ moment(response.riwayat.data[k].created_at).format('D MMMM YYYY') }</td>
                            <td>${ response.riwayat.data[k].no_transaksi }</td>
                            <td>
                                ${ response.riwayat.data[k].jenis.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                    return letter.toUpperCase();
                                }) }
                            </td>
                            <td><div class="currency">${ response.riwayat.data[k].total }</div></td>
                            <td>${ response.riwayat.data[k].teller.anggota.nama }</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm js-tooltip" data-toggle="tooltip" data-placement="top" title="Detail Transaksi" href="${ laroute.route('simkop.invoice', { id: response.riwayat.data[k].no_transaksi }) }">
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
            response.riwayat.next_page_url !== null ? navNext.prop('disabled', false) : navNext.prop('disabled', true);
            response.riwayat.prev_page_url !== null ? navPrev.prop('disabled', false) : navPrev.prop('disabled', true);
            if(response.riwayat.total === 0){
                var navigasi = 'Menampilkan Data 0 - 0 Dari 0';
            }else{
                var navigasi = 'Menampilkan Data '+ response.riwayat.from +' - '+ response.riwayat.to +' Dari '+ response.riwayat.total;
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