jQuery(function() { 
    moment.locale('id');
    var start =  moment().startOf('month');
    var end = moment();

    $(document).on('click', 'a.filter-status', function () {
        status = $(this).attr("data-status");
        $("#filter-status").val(status);
        $('a.filter-status').each(function() {
            if($(this).attr("data-status") !== status)
            {
                $(this).removeClass('active');
                $('#current_page').val(1);
            }else
            {
                $(this).addClass('active');
            }
        });
        $(this).addClass('active');
        load_content();
    });

    $('#tgl_range').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
            'Kuartal 1': [ moment().quarter(1).startOf('quarter'), moment().quarter(1).endOf('quarter') ],
            'Kuartal 2': [ moment().quarter(2).startOf('quarter'), moment().quarter(2).endOf('quarter') ],
            'Kuartal 3': [ moment().quarter(3).startOf('quarter'), moment().quarter(3).endOf('quarter') ],
            'Kuartal 4': [ moment().quarter(4).startOf('quarter'), moment().quarter(4).endOf('quarter') ],
            'Triwulan': [ moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month') ],
            'Tahun Lalu': [ moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year') ],
        },
        locale : {
            "customRangeLabel": "Custom Tanggal",
        }
    }, formatTgl);

    formatTgl(start, end);

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

    $('#data-list').on('click', '.btn-detail', function () {
        var id = $(this).data('id');
        var modal = $("#modal-detail");

        $.ajax({
            url: laroute.route('pembayaran.detail', { id :id}),
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
                if(response.status !== 'confirm'){
                    modal.find('.modal-footer').append(`
                    <button type="button" class="btn btn-danger btn-sm btn-action" data-val="cancel" data-id="${response.id}">
                    <i class="si si-close mr-2"></i> Tolak
                    </button>
                    <button type="button" class="btn btn-success btn-sm btn-action" data-val="confirm" data-id="${response.id}">
                        <i class="si si-check mr-2"></i> Terima
                    </button>
                    `);
                }

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
            url: laroute.route('pembayaran.action'),
            type: "POST",
            dataType: "JSON",
            data: {
                id : id,
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
                    text: 'Status Pembayaran Berhasil Dipebaharui!',
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
function formatTgl(start, end) {
    if(start.format('D MMMM, YYYY') == end.format('D MMMM, YYYY'))
    {
        tampil = start.format('D MMMM, YYYY');
    }else{
        tampil = start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY');
    }
    $('input#tgl_mulai').val(start.format('YYYY-MM-DD'));
    $('input#tgl_akhir').val(end.format('YYYY-MM-DD'));
    $('#tgl_range span').html(tampil);
    load_content();
}


function load_content(){
    var dataList =$("#data-list");
    var dataLength = dataList.find('thead tr th').length;
    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');

    var parameter = {};
    parameter['page'] = $('#current_page').val();

    var status = $('#filter-status').val();
    if(status) parameter['status'] = status;

    var keyword = $('#search-data-list').val();
    if(keyword) parameter['keyword'] = keyword;
    
    
    $.ajax({
        url: laroute.route('pembayaran'),
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
            
            window.history.pushState({}, null, this.url);
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

function create_element(item){
    let row = `<tr>
        <td>${ moment(item.tgl_bayar).format('D MMMM YYYY') }</td>
        <td>${ item.nomor }</td>
        <td>
            <div class="font-size-16 font-w600">${ item.anggota_id }</div>
            <div class="font-size-15">${ item.anggota_nama }</div>
        </td>
        <td>
        ${ getmethod(item.method, item.bank_logo) }
        </td>
        <td><div class="currency">${ item.jumlah }</div></td>
        <td>${ createStatusBadge(item.status) }</td>
        <td class="text-center">
            <button class="btn btn-secondary btn-sm js-tooltip btn-detail" type="button" data-toggle="tooltip" data-placement="top" title="Detail Transaksi" data-id="${ item.id }">
                <i class="si si-magnifier"></i> Detail
            </button>
        </td>
    </tr>`;
    return row;
}

function createStatusBadge(status){
    if(status == 'pending')
    {
        return '<span class="badge badge-warning">Pending</span>';
    }else if(status == 'draft'){
        return '<span class="badge badge-info">Verifikasi</span>';
    }else if(status == 'confirm')
    {
        return '<span class="badge badge-success">Selesai</span>';
    }else if(status == 'cancel')
    {
        return '<span class="badge badge-danger">Batal</span>';
    }
}


function getmethod(method, bank_logo){
    if(method == 'Tunai'){
        return 'Tunai';
    }else if(method == 'Transfer'){
        return '<img src="'+laroute.url('public/'+bank_logo, []) +'" height="25px"/>';
    }else{
        return 'Simpanan Sukarela'
    }
}

function create_elementDetail(item){
    var item = `<div class="row">
        <div class="col-md-6">
            <div class="row mb-1">
                <div class="col-4 font-w600">No Transaksi</div>
                <div class="col-8">: ${ item.nomor }</div>
            </div>
            <div class="row mb-1">
                <div class="col-4 font-w600">ID Anggota</div>
                <div class="col-8">: ${ item.anggota_id }</div>
            </div>
            <div class="row mb-1">
                <div class="col-4 font-w600">Nama Anggota</div>
                <div class="col-8">: ${ item.anggota_nama }</div>
            </div>
            <div class="row mb-1">
                <div class="col-4 font-w600">Status</div>
                <div class="col-8">: ${ createStatusBadge(item.status) }</div>
            </div>
            <div class="row mb-1">
                <div class="col-4 font-w600">Metode</div>
                <div class="col-8">: ${ getmethod(item.method, item.bank_logo) }</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row mb-1">
                <div class="col-5 font-w600">Jumlah</div>
                <div class="col-7">: <span class="currency"> ${ item.jumlah }</span></div>
            </div>
            <div class="row mb-1">
                <div class="col-5 font-w600">Tanggal Bayar</div>
                <div class="col-7">: ${ moment(item.tgl_bayar).format('D MMMM YYYY') }</div>
            </div>
            <div class="row mb-1">
                <div class="col-5 font-w600">Kode</div>
                <div class="col-7">: <span class="currency"> ${ item.code }</span></div>
            </div>
            <div class="row mb-1">
                <div class="col-5 font-w600">Biaya Admin</div>
                <div class="col-7">: <span class="currency"> ${ item.admin_fee }</span></div>
            </div>
        </div>
    </div>`;

    return item;
}
