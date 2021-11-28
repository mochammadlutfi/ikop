jQuery(function() {
    moment.locale('id');
    var start =  moment().startOf('month');
    var end = moment();
    // var filterDate = jQuery("#date_filter");
    $("#date_filter").daterangepicker({
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
    }, filterSetDate);
    filterSetDate(start, end);

});

function filterSetDate(start, end){
    var filterDate = jQuery("#date_filter");
    if(start.format('D MMMM, YYYY') == end.format('D MMMM, YYYY'))
    {
        filter = start.format('D MMM YYYY');
    }else{
        filter = start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY');
    }
    filterDate.data("start_date", start.format('YYYY-MM-DD'));
    filterDate.data("end_date", end.format('YYYY-MM-DD'));
    filterDate.find('span').html(filter);
    updateStatistics(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
}

function createElementWidget(data){
    var el = '';
    $.each(data, function(k, v) {
        el +=`<div class="row no-gutters">
            <div class="col-lg-5">
                <span class="currency font-size-md font-w600">${ v }</span>
            </div>
            <div class="col-lg-6">
                ${ k }
            </div>
        </div>`
    });

    return el;
}

function updateStatistics(start_date, end_date){
    
    let simpanan = jQuery("#dashboard-simpanan");
    let kas = jQuery("#dashboard-kas");
    let pembiayaan = jQuery("#dashboard-pembiayaan");

    $.ajax({
        url: laroute.route('dashboard.data'),
        type: "GET",
        dataType: "JSON",
        data: {
            start_date: start_date,
            end_date: end_date,
        },
        beforeSend: function(){

        },
        success: function(response) {

            simpanan.find(".block-content").html(createElementWidget(response.simpanan));
            kas.find(".block-content").html(createElementWidget(response.kas));
            pembiayaan.find(".block-content").html(createElementWidget(response.kas));

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
            Swal.close();
            alert('Error deleting data');
        }
    });
}