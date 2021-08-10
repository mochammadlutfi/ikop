
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

