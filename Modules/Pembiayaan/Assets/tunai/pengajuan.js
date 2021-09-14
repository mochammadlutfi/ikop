jQuery(function() {
    moment.locale('id');
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

    load_content();

    $('#data-list').on('click', '.btn-detail', function () {
        var id = $(this).data('id');
        var modal = $("#modal-detail");
        // $("#modal-detail").modal('show');
        $.ajax({
            url: laroute.route('pmb_tunai.pengajuan.detail', { id :id}),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: 'Data Sedang Diproses!',
                    imageUrl: laroute.url('public/media/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
                modal.find('#detail').html('');
                modal.find('table tbody').html('');
                modal.find('.modal-footer').html('');
            },
            success: function(response) {
                modal.find('#detail').append(create_elementDetail(response));
                var row = '';

                $.each(response.line, function(k, item) {
                    row += create_elementDetailTable(item);
                });
                modal.find('table tbody').append(row);

                modal.find('.modal-footer').append(`
                <button type="button" class="btn btn-danger btn-sm btn-action" data-val="3" data-id="${response.id}">
                    <i class="si si-close mr-2"></i> Tolak
                </button>
                <button type="button" class="btn btn-success btn-sm btn-action" data-val="1" data-id="${response.id}">
                    <i class="si si-check mr-2"></i> Konfirmasi
                </button>
                `);

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
                Swal.close();
                modal.modal('show');
            },
    
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });
    });

    $('#modal-detail').on('click', '.btn-action', function () {
        val = $(this).data('val');
        id = $(this).data('id');
        
        $.ajax({
            url: laroute.route('pmb_tunai.pengajuan.action', { id : id}),
            type: "POST",
            dataType: "JSON",
            data: {
                status : val
            },
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: 'Data Sedang Diproses!',
                    imageUrl: laroute.url('public/media/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function(response) {
                Swal.fire({
                    title: "Berhasil",
                    text: 'Status Pengajuan Berhasil Dipebaharui!',
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                load_content();
                $("#modal-detail").modal('toggle');
            },
            complete: function(){
                Swal.close();
            },
    
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: "Gagal",
                    text: 'Status Pengajuan Gagal Dipebaharui!',
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'error'
                });
            }
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
        url: laroute.route('pmb_tunai.pengajuan.data'),
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
    let row = `<tr>
        <td>${ moment(item.created_at).format('D MMMM YYYY') }</td>
        <td>${ item.id }</td>
        <td>
            <div class="font-size-16 font-w600">${ item.anggota.anggota_id }</div>
            <div class="font-size-15">${ item.anggota.nama }</div>
        </td>
        <td><div class="currency">${ item.jumlah }</div></td>
        <td>${ item.durasi } Bulan</td>
        <td>${ createStatusBadge(item.status) }</td>
        <td class="text-center">
            <button type="button" class="btn btn-secondary btn-sm btn-detail" data-id="${ item.id }">
                <i class="si si-magnifier"></i>
                Detail
            </button>
        </td>
    </tr>`;
    return row;
}

function create_elementDetail(item){
    var item = `<div class="row">
        <div class="col-md-6">
            <h2 class="h5 mb-0 pt-0">Informasi Anggota</h2>
            <hr class="border-2x">
            <div class="row no-gutter">
                <div class="col-4 font-w600">ID Anggota</div>
                <div class="col-8">: ${ item.anggota_id }</div>
            </div>
            <div class="row no-gutter">
                <div class="col-4 font-w600">Nama Anggota</div>
                <div class="col-8">: ${ item.anggota.nama }</div>
            </div>
            <div class="row no-gutter">
                <div class="col-4 font-w600">No Ponsel</div>
                <div class="col-8">: ${ item.anggota.no_hp }</div>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="h5 mb-0 pt-0">Informasi Pembiayaan</h2>
            <hr class="border-2x">
            <div class="row no-gutter">
                <div class="col-5 font-w600">No Pembiayaan</div>
                <div class="col-7">: ${ item.id }</div>
            </div>
            <div class="row no-gutter">
                <div class="col-5 font-w600">Tanggal Pengajuan</div>
                <div class="col-7">: ${ moment(item.created_at).format('D MMMM YYYY') }</div>
            </div>
            <div class="row no-gutter">
                <div class="col-5 font-w600">Jumlah Pembiayaan</div>
                <div class="col-7">: <span class="currency"> ${ item.jumlah }</span></div>
            </div>
            <div class="row no-gutter">
                <div class="col-5 font-w600">Durasi Pembiayaan</div>
                <div class="col-7">: ${ item.durasi } Bulan</div>
            </div>
            <div class="row no-gutter">
                <div class="col-5 font-w600">Biaya Admin</div>
                <div class="col-7">: <span class="currency"> ${ item.biaya_admin }</span></div>
            </div>
            <div class="row no-gutter">
                <div class="col-5 font-w600">Jumlah Bagi Hasil</div>
                <div class="col-7">: <span class="currency"> ${ item.jumlah_bunga }</span></div>
            </div>
        </div>
    </div>`;

    return item;
}

function create_elementDetailTable(item){
    var row = `<tr>
        <td>${ item.no }</td>
        <td><div class="currency">${ item.angsuran_pokok }</div></td>
        <td><div class="currency">${ item.angsuran_bunga }</div></td>
        <td><div class="currency">${ item.jumlah_angsuran }</div></td>
        <td>${ item.tempo }</td>
    <tr>`;

    return row;
}



function createStatusBadge(status){
    if(status == 0)
    {
        return '<span class="badge badge-warning">Pending</span>';
    }else if(status == 1){
        return '<span class="badge badge-info">Aktif</span>';
    }else if(status == 2)
    {
        return '<span class="badge badge-info">Ditolak</span>';
    }else if(status == 3)
    {
        return '<span class="badge badge-primary">Lunas</span>';
    }
}

