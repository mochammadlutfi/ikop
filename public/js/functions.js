//This file contains all functions used in the app.
function getRawCurrency(value){
    rawValue = value.replace(/[^\d,-]/g, '');
        
    // Replace comma with decimal point
    rawValue = rawValue.replace(",", '.');

    return rawValue;
}

