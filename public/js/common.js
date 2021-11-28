
jQuery('input.input-currency').each((index, element) => {
    // let el = $(element);

    new AutoNumeric(element, {
        allowDecimalPadding: false,
        alwaysAllowDecimalCharacter: true,
        caretPositionOnFocus: "start",
        currencySymbol: "Rp",
        decimalCharacter: ",",
        decimalPlaces: 0,
        digitGroupSeparator: ".",
        unformatOnSubmit: true
    });
});



jQuery('.currency').each((index, element) => {
    new AutoNumeric(element, {
        allowDecimalPadding: false,
        alwaysAllowDecimalCharacter: true,
        caretPositionOnFocus: "start",
        currencySymbol: "Rp",
        decimalCharacter: ",",
        decimalPlaces: 0,
        digitGroupSeparator: ".",
        unformatOnSubmit: true
    });
});

jQuery(function() { 
    transaksiCount();
});

function transaksiCount(){
    $.ajax({
        url: laroute.route('transaksi.countAktif'),
        type: "GET",
        dataType: "JSON",
        success: function(response) {
            jQuery('.transaksi-notif-count').html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            return;
        }
    });
}

