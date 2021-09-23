jQuery(function() {
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

jQuery('#data-list').each((index, element) => {
    let el = $(element);

    load_content();
});

$(document).on('change', '#select-all', function () {
    $('.angsuran-item').find('input[type=checkbox]').not('.angsuran-item input[type=checkbox]:disabled').prop('checked', this.checked);
    if($('.angsuran-item input[type=checkbox]:checked').length > 0){
        $("button.btn-bayar").removeClass('d-none');
    }else{
        $("button.btn-bayar").addClass('d-none');
    }
});

$(document).on('change', '.angsuran-item input[type=checkbox]', function () {
    if($('.angsuran-item input[type=checkbox]:checked').length > 0){
        $("button.btn-bayar").removeClass('d-none');
    }else{
        $("button.btn-bayar").addClass('d-none');
    }
});


jQuery('button.btn-bayar').each((index, element) => {
    let el = $(element);
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

    $('#datetimepicker3').datetimepicker({
        "format": "DD-MM-YYYY",
        "dayViewHeaderFormat": "MMMM YYYY",
        "locale": moment.locale('id'),
        "sideBySide": true,
        "widgetPositioning": {
            "horizontal": "auto",
            "vertical": "auto"
        },
    });
    var modal = $("#modal-bayar");

    el.on("click", function(){
        modal.modal('show');
        
        var total = 0;
        var list_angsuran = $("#angsuran-list");
        list_angsuran.html('');
        $('tr.angsuran-item').each(function () {
            if($(this).find('input[type=checkbox]').is(":checked")){
                id = parseInt($(this).find('input.angsuran_id').val());
                angsuran = parseInt($(this).find('input.jumlah').val());
                angsuran_ke = $(this).find('input.angsuran_ke').val();
                tempo = $(this).find('input.tempo').val();
                total += angsuran;
                list_angsuran.append(`
                <input type="hidden" class="ad" name="angsuran_id[]" value="${ id }">
                <div class="d-flex justify-content-between py-2">
                    <div>
                        <div>Angsuran Ke ${ angsuran_ke }</div>
                        <div>${ tempo }</div>
                    </div>
                    <div class="font-size-20 font-weight-bold my-auto harga">${ angsuran }</div>
                </div>
                `);
            }
        });
        modal.find('.total_bayar').html(total);
        new AutoNumeric.multiple(".harga", {
            allowDecimalPadding: false,
            alwaysAllowDecimalCharacter: true,
            caretPositionOnFocus: "start",
            currencySymbol: "Rp ",
            decimalCharacter: ",",
            decimalPlaces: 0,
            digitGroupSeparator: ".",
            unformatOnSubmit: true,
        });

        $("form#bayar").on("submit", function (e) {
            e.preventDefault();
            var fomr = $(this)[0];
            var formData = new FormData(fomr);
    
            url = laroute.route('pmb_tunai.bayar');
    
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
                        Swal.fire({
                            title: `Berhasil!`,
                            icon: 'success',
                            html: `Setoran Berhasil Disimpan!
                                    <br><br>
                                    <a href="` + laroute.route('simla.penarikan') + `" class="btn btn-outline-primary">
                                        <i class="si si-plus mr-1"></i>Tambah Penarikan Lain
                                    </a> 
                                    <a href="` + laroute.route('simla.invoice', response.no_invoice) + `" class="btn btn-primary">
                                        <i class="si si-magnifier mr-1"></i>Detail Penarikan
                                    </a>`,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    } else {
                        Swal.close();
                        for (control in response.errors) {
                            $('#field-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                            $.notify({
                                icon: 'fa fa-times',
                                message: response.errors[control]
                            }, {
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
});


function load_content(){
    var dataList =$("#data-list");
    var dataLength = dataList.find('thead tr th').length;
    
    var parameter = {};
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');

    var keyword = $('#search-data-list').val();
    if(keyword) parameter['keyword'] = keyword;
    

    $.ajax({
        url: laroute.route('pmb_tunai.data'),
        type: "GET",
        dataType: "JSON",
        data: parameter,
        beforeSend: function(){
            dataList.find('tbody').html('');
            dataList.find('tbody').append(`<tr>
                <td colspan="${ dataLength }">
                    <div class="text-center py-50">
                        <div class="spinner-border text-primary wh-50" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </td>
            </tr>`);
            navNext.prop('disabled', true);
            navPrev.prop('disabled', true);
        },
        success: function(response) {
            $('#data-list tbody tr').not('#data-list tbody tr#loading').remove();

            var row = "";
            if(response.data.length !== 0){
                $.each(response.data, function(k, item) {
                    row += create_element(item);
                });
            }else{
               row = `<tr>
                    <td colspan="${dataLength}">
                        <div class="text-center">
                            <img class="img-fluid" src="`+ laroute.url('public/media/placeholder/empty.png', ['']) +`">
                            <div>
                                <h3 class="font-size-24 font-w600 mt-3">Data Tidak Ditemukan</h3>
                            </div>
                        </div>
                    </td>
                </tr>`;
            }
            
            dataList.find('tbody').append(row);

            // Table Navigation
            response.next_page_url !== null ? navNext.prop('disabled', false) : navNext.prop('disabled', true);
            response.prev_page_url !== null ? navPrev.prop('disabled', false) : navPrev.prop('disabled', true);
            if(response.total === 0){
                var navigasi = '0 - 0 / 0';
            }else{
                var navigasi = response.from +' - '+ response.to +' / '+ response.total;
            }
            $('#content-nav span').html(navigasi);
            
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
        complete: function(){
            $('#data-list tbody tr#loading').addClass('d-none');
        },

        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}

function create_element(item){
    
    moment.locale('id');
    let row = `<tr>
        <td>${ moment(item.created_at).format('D MMMM YYYY') }</td>
        <td>${ item.no_pembiayaan }</td>
        <td>
            <div class="font-size-16 font-w600">${ item.anggota.anggota_id }</div>
            <div class="font-size-15">${ item.anggota.nama }</div>
        </td>
        <td><div class="currency">${ item.jumlah }</div></td>
        <td>${ item.durasi } Bulan</td>
        <td>${ createStatusBadge(item.status) }</td>
        <td class="text-center">
            <a class="btn btn-secondary btn-sm" href="${ laroute.route('pmb_tunai.detail', { id : item.id}) }">
                <i class="si si-magnifier"></i>
                Detail
            </a>
        </td>
    </tr>`;
    return row;
}


function createStatusBadge(status){
    if(status == "pending")
    {
        return '<span class="badge badge-warning">Pending</span>';
    }else if(status == "confirm"){
        return '<span class="badge badge-info">Aktif</span>';
    }else if(status == "cancel")
    {
        return '<span class="badge badge-danger">Ditolak</span>';
    }else if(status == "finish")
    {
        return '<span class="badge badge-primary">Lunas</span>';
    }
}

