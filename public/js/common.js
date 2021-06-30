//This file contains all common functionality for the application

$(document).ready(function() {

    //Set global currency to be used in the application
    __currency_symbol = $('input#__symbol').val();
    __currency_thousand_separator = $('input#__thousand').val();
    __currency_decimal_separator = $('input#__decimal').val();
    __currency_symbol_placement = $('input#__symbol_placement').val();
    if ($('input#__precision').length > 0) {
        __currency_precision = $('input#__precision').val();
    } else {
        __currency_precision = 2;
    }

    if ($('input#__quantity_precision').length > 0) {
        __quantity_precision = $('input#__quantity_precision').val();
    } else {
        __quantity_precision = 2;
    }

    //Set page level currency to be used for some pages. (Purchase page)
    if ($('input#p_symbol').length > 0) {
        __p_currency_symbol = $('input#p_symbol').val();
        __p_currency_thousand_separator = $('input#p_thousand').val();
        __p_currency_decimal_separator = $('input#p_decimal').val();
    }

    __currency_convert_recursively($(document), $('input#p_symbol').length);

    if ($('input#iraqi_selling_price_adjustment').length > 0) {
        iraqi_selling_price_adjustment = true;
    } else {
        iraqi_selling_price_adjustment = false;
    }

    //Input number
    $(document).on('click', '.input-number .quantity-up, .input-number .quantity-down', function() {
        var input = $(this)
            .closest('.input-number')
            .find('input');
        var qty = __read_number(input);
        var step = 1;
        if (input.data('step')) {
            step = input.data('step');
        }
        var min = parseFloat(input.data('min'));
        var max = parseFloat(input.data('max'));

        if ($(this).hasClass('quantity-up')) {
            //if max reached return false
            if (typeof max != 'undefined' && qty + step > max) {
                return false;
            }

            __write_number(input, qty + step);
            input.change();
        } else if ($(this).hasClass('quantity-down')) {
            //if max reached return false
            if (typeof min != 'undefined' && qty - step < min) {
                return false;
            }

            __write_number(input, qty - step);
            input.change();
        }
    });

    $('div.pos-tab-menu>div.list-group>a').click(function(e) {
        e.preventDefault();
        $(this)
            .siblings('a.active')
            .removeClass('active');
        $(this).addClass('active');
        var index = $(this).index();
        $('div.pos-tab>div.pos-tab-content').removeClass('active');
        $('div.pos-tab>div.pos-tab-content')
            .eq(index)
            .addClass('active');
    });
});


//Check for number string in input field, if data-decimal is 0 then don't allow decimal symbol
$(document).on('keypress', 'input.input_number', function(event) {
    var is_decimal = $(this).data('decimal');

    if (is_decimal == 0) {
        if (__currency_decimal_separator == '.') {
            var regex = new RegExp(/^[0-9,-]+$/);
        } else {
            var regex = new RegExp(/^[0-9.-]+$/);
        }
    } else {
        var regex = new RegExp(/^[0-9.,-]+$/);
    }

    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});

//Select all input values on click
$(document).on('click', 'input, textarea', function(event) {
    $(this).select();
});


//Change max quantity rule if lot number changes
$('table#stock_adjustment_product_table tbody').on('change', 'select.lot_number', function() {
    var qty_element = $(this)
        .closest('tr')
        .find('input.product_quantity');
    if ($(this).val()) {
        var lot_qty = $('option:selected', $(this)).data('qty_available');
        var max_err_msg = $('option:selected', $(this)).data('msg-max');
        qty_element.attr('data-rule-max-value', lot_qty);
        qty_element.attr('data-msg-max-value', max_err_msg);

        qty_element.rules('add', {
            'max-value': lot_qty,
            messages: {
                'max-value': max_err_msg,
            },
        });
    } else {
        var default_qty = qty_element.data('qty_available');
        var default_err_msg = qty_element.data('msg_max_default');
        qty_element.attr('data-rule-max-value', default_qty);
        qty_element.attr('data-msg-max-value', default_err_msg);

        qty_element.rules('add', {
            'max-value': default_qty,
            messages: {
                'max-value': default_err_msg,
            },
        });
    }
    qty_element.trigger('change');
});
