jQuery(function() { 
    moment.locale('id');
    var form = $("#form-simla");
    var anggota = $('#field-anggota_id').select2({
        placeholder: 'Masukan ID Anggota / Nama',
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('anggota.select2'),
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
        },
        
        // minimumInputLength: 3,
        // // templateResult: formatResult,
        // templateResult: function(response) {
        //     if(response.loading)
        //     {
        //         return "Mencari...";
        //     }else{
        //         var selectionText = response.text.split("-");
        //         var $returnString = $('<span>'+selectionText[0] + '</br>' + selectionText[1] + '</span>');
        //         return $returnString;
        //     }
        // },
        // templateSelection: function(response) {
        //     return response.text;
        // },
    });
    
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

    
    if($('#method').val() == 'update'){
        anggota_opt = new Option($('#field-anggota_id').data("text"), $('#field-anggota_id').data("id"), true, true);
        anggota.append(anggota_opt).trigger('change');

        
        kas_opt = new Option($('#field-kas_id').data("text"), $('#field-kas_id').data("id"), true, true);
        kas.append(kas_opt).trigger('change');
    }
    
   anggota.on("change", function(){
        var id = $(this).val();
        $.ajax({
            url: laroute.route('anggota.get_info', { id: id }),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('public/media/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function(response) {
                Swal.close();
                form.find('input[name="no_ktp"]').val(response.no_ktp);
                form.find('input[name="nama"]').val(response.nama);
                form.find('input[name="no_hp"]').val(response.no_hp);
                form.find('textarea[name="alamat"]').val(response.alamat_full);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
            }
        });
    });

    $('#field-tgl').datetimepicker({
        "format": "DD-MM-YYYY",
        "dayViewHeaderFormat": "MMMM YYYY",
        "locale": moment.locale('id'),
        "sideBySide": true,
        "widgetPositioning": {
            "horizontal": "auto",
            "vertical": "auto"
        },
    });

    $("#form-simla").on("submit", function (e) {
        e.preventDefault();
        var fomr = $('form#form-simla')[0];
        var formData = new FormData(fomr);

        if($("#method").val() == 'update'){
            url = laroute.route('simla.update');
        }else{
            url = laroute.route('simla.store');
        }

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
                            <a href="`+ laroute.route('simla.setoran') +`" class="btn btn-outline-primary">
                                <i class="si si-plus mr-1"></i>Tambah Setoran Lain
                            </a> 
                            <a href="`+ laroute.route('simla.invoice', {id : response.invoice}) +`" class="btn btn-primary">
                                <i class="si si-magnifier mr-1"></i>Detail Setoran
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
                            // options
                            icon: 'fa fa-times',
                            message: response.errors[control]
                        }, {
                            // settings
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