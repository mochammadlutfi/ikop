jQuery(function() {
    moment.locale('id');
    var start =  moment().startOf('month');
    var end = moment();

    $('#tgl_range').daterangepicker({
        startDate: start,
        endDate: end,
        alwaysShowCalendars: true,
        opens : "left",
        showDropdowns: true,
        minDate: "01-01-2020",
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

    // $(document).on('change', '#tgl_range', function () {
    //     fetch_data();
    // });

    $(document).on('click', '.sorting', function () {
        var column_name = $(this).data('column_name');
        var order_type = $(this).data('sorting_type');
        var reverse_order = '';
        if (order_type == 'asc') {
            $(this).data('sorting_type', 'desc');
            reverse_order = 'desc';
            clear_icon();
            $('#' + column_name + '_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
        }
        if (order_type == 'desc') {
            $(this).data('sorting_type', 'asc');
            reverse_order = 'asc';
            clear_icon
            $('#' + column_name + '_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);
        var page = $('#hidden_page').val();
        var query = $('#serach').val();
        fetch_data(page, reverse_order, column_name, query);
    });

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var tgl_mulai = $('#tgl_mulai').val();
        var tgl_akhir = $('#tgl_akhir').val();

        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_data(page, tgl_mulai, tgl_akhir);
    });

    $(document).on('click', 'table#list-riwayat tbody.data_transaksi tr', function () {
        var transaksi_id = $('table#list-riwayat tbody tr').data('transaksi_id');
        // alert(transaksi_id);
        document.location = laroute.route('mitra.pos.detail', {transaksi_id : transaksi_id});
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
    $('.periode').html(`Periode  ${ tampil }`)
    fetch_data();
}

function fetch_data(page, tgl_mulai, tgl_akhir) {
    
    var tgl_mulai = $('#tgl_mulai').val();
    var tgl_akhir = $('#tgl_akhir').val();
    
    $.ajax({
        url: laroute.route('laporan.simpanan'),
        type: "GET",
        dataType: "JSON",
        data: {
            tgl_mulai: tgl_mulai,
            tgl_akhir: tgl_akhir,
        },
        beforeSend: function(){
            $('#data-list tbody tr#loading').removeClass('d-none');
        },
        success: function(response) {
            $('#data-list tbody tr').not('#data-list tbody tr#loading').remove();
            if(response.length !== 0){
                var no = 1;
                var debit = 0;
                var kredit = 0;
                var jumlah = 0;
                var total = 0;
                $.each(response, function(k, v) {
                    jumlah = response[k].debit - response[k].kredit;
                    $('#data-list tbody').append(`
                    <tr>
                        <td>${ no++ }</td>
                        <td>${ response[k].simpanan }</td>
                        <td class="currency">${ response[k].debit }</td>
                        <td class="currency">${ response[k].kredit }</td>
                        <td class="currency">${ jumlah }</td>
                    </tr>
                    `);
                    debit += response[k].debit;
                    kredit += response[k].kredit;
                    total += jumlah;
                });
                $('#data-list tfoot').html(`
                <tr>
                    <td colspan="2">Jumlah</td>
                    <td class="currency">${ debit }</td>
                    <td class="currency">${ kredit }</td>
                    <td class="currency">${ total }</td>
                </tr>             
                `);

            }
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