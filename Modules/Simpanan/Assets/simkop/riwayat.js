jQuery(function() { 
    moment.locale('id');
    var start =  moment().startOf('month');
    var end = moment();

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
    var keyword = $('#search-data-list').val();
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');
    var tgl_mulai = $('#tgl_mulai').val();
    var tgl_akhir = $('#tgl_akhir').val();
    
    $.ajax({
        url: laroute.route('simkop.riwayat'),
        type: "GET",
        dataType: "JSON",
        data: {
            keyword: keyword,
            page: page,
            tgl_mulai: tgl_mulai,
            tgl_akhir: tgl_akhir,
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
                            <td>${ moment(response.data[k].tgl_transaksi).format('D MMMM YYYY') }</td>
                            <td>${ response.data[k].no_transaksi }</td>
                            <td>
                                <div class="font-size-16 font-w600">${ response.data[k].anggota.anggota_id }</div>
                                <div class="font-size-15">${ response.data[k].anggota.nama }</div>
                            </td>
                            <td>${ moment(response.data[k].simkop.periode).format('MMMM YYYY') }</td>
                            <td>
                                ${ response.data[k].jenis.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                    return letter.toUpperCase();
                                }) }
                            </td>
                            <td><div class="currency">${ response.data[k].total }</div></td>
                            <td>${ response.data[k].teller.nama }</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm js-tooltip" data-toggle="tooltip" data-placement="top" title="Detail Transaksi" href="${ laroute.route('simkop.invoice', { id: response.data[k].no_transaksi }) }">
                                    <i class="si si-magnifier"></i> Detail
                                </a>
                            </td>
                        </tr>
                    `);
                });
            }else{
                $('#data-list tbody').append(`
                <tr>
                    <td colspan="8">
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
                var navigasi = '0 - 0 / 0';
            }else{
                var navigasi = response.from +' - '+ response.to +' / '+ response.total;
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
