jQuery(function() { 

     // Crop Start
     var croppie = null;
     var cropModal = $("#cropModal");
     var el = document.getElementById('resizer');
     var formData = new FormData();
 
     $.getImage = function(input, croppie) {
         if (input.files && input.files[0]) {
             var reader = new FileReader();
             reader.onload = function(e) {
                 croppie.bind({
                     url: e.target.result,
                 });
             }
             reader.readAsDataURL(input.files[0]);
         }
     }
 
     $("#file-upload").on("change", function(event) {
         cropModal.modal();
         croppie = new Croppie(el, {
             viewport: {
                 width: 440,
                 height: 440,
                 type: 'square'
             },
             original : {
                 width: 440,
                 height: 440,
                 type: 'square'
             },
             boundary: {
                 width: 460,
                 height: 260
             },
             enableOrientation: true
         });
         $.getImage(event.target, croppie);
     });
 
     $("#upload").on("click", function() {
         croppie.result({
             type: 'base64',
             size: 'original',
             format:'jpeg',
             size: { 
                 width: 1200, height: 1200 
             }
         }).then(function(base64) {
             cropModal.modal("hide");
             $("#img_preview").attr("src", base64);
             $("#featured_img").val(base64);
         });
     });
 
     $(".rotate").on("click", function() {
         croppie.rotate(parseInt($(this).data('deg')));
     });
 
     cropModal.on('hidden.bs.modal', function (e) {
         setTimeout(function() { 
             croppie.destroy(); 
         }, 100);
     });
     
     // Crop End

    $(".custom-file-input").on("change",function(e) {
        var fileName = e.target.files[0].name;
        $("#file").val(fileName);
    });

    $('#field-tgl_payment').datetimepicker({
        "format": "DD-MM-YYYY",
        "dayViewHeaderFormat": "MMMM YYYY",
        "locale": moment.locale('id'),
        "sideBySide": true,
        "widgetPositioning": {
            "horizontal": "auto",
            "vertical": "auto"
        },
    });

    // $('#field-simla').maskMoney({
    //     prefix:'Rp ',
    //     thousands:'.',
    //     precision : 0,
    // });
    // anElement = new AutoNumeric(".currency");
    
    

    // $('.currency').autoNumeric('init');

    // $('#field-simla').on("change", function(){
    //     var num = $(this).maskMoney({
    //         // 'unmasked'
    //     })[0]; 
    //     alert('type: '+ typeof(num) + ', value: ' + num);
    // });

    $('#field-tgl_lahir').datetimepicker({
        "format": "DD-MM-YYYY",
        "dayViewHeaderFormat": "MMMM YYYY",
        "locale": moment.locale('id'),
        "sideBySide": true,
        "daysOfWeekDisabled": false,
        "calendarWeeks": false,
        "viewMode": "years",
        "widgetPositioning": {
            "horizontal": "auto",
            "vertical": "auto"
        },
    });

    

    $('.phone').on('keyup', function(){
        val = $(this).val();
        val = val.replace(/^0+/, '');
        $(this).val(val);
    });

    var daerah = $('#field-wilayah_id').select2({
        placeholder: 'Cari Kelurahan',
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('wilayah.jsonSelect'),
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
        
        minimumInputLength: 3,
        // templateResult: formatResult,
        templateResult: function(response) {
            if(response.loading)
            {
                return "Mencari...";
            }else{
                var selectionText = response.text.split(",");
                var $returnString = $('<span>'+selectionText[0] + ', ' + selectionText[1] + '</br>' + selectionText[2]+ ', ' + selectionText[3] +'</span>');
                return $returnString;
            }
        },
        templateSelection: function(response) {
            return response.text;
        },
    });

    if( $("#field-wilayah_id").attr("data-id") && $("#field-wilayah_id").attr("data-text"))
    {
        sel_option = new Option($("#field-wilayah_id").attr("data-text"), $("#field-wilayah_id").attr("data-id"), true, true);
        daerah.append(sel_option).trigger('change');
    }

    var daerah2 = $('#field-wilayah_id2').select2({
        placeholder: 'Cari Kelurahan',
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('wilayah.jsonSelect'),
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
        
        minimumInputLength: 3,
        // templateResult: formatResult,
        templateResult: function(response) {
            if(response.loading)
            {
                return "Mencari...";
            }else{
                var selectionText = response.text.split(",");
                var $returnString = $('<span>'+selectionText[0] + ', ' + selectionText[1] + '</br>' + selectionText[2]+ ', ' + selectionText[3] +'</span>');
                return $returnString;
            }
        },
        templateSelection: function(response) {
            return response.text;
        },
    });

    if( $("#field-wilayah_id2").attr("data-id") !== '' && $("#field-wilayah_id2").attr("data-text")  !== '')
    {
        sel_option = new Option($("#field-wilayah_id2").attr("data-text"), $("#field-wilayah_id2").attr("data-id"), true, true);
        daerah2.append(sel_option).trigger('change');
    }

    var a = $("#form-step1");
    var b = $("#form-step2");
    var c = $("#form-step3");
    var d = $("#form-step4");

    a.validate({
        onfocusout: function(element) {
            $(element).valid()
            if ($(element).valid()) {
                a.find('button:submit').prop('disabled', false);  
            } else {
                a.find('button:submit').prop('disabled', 'disabled');
            }
        },
        errorClass: "invalid-feedback animated fadeInDown",
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').find('div.invalid-feedback').html(error);
            console.log(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        success: function (element) {
            $(element).removeClass('is-invalid').addClass("is-valid");
            $(element).closest(".form-group").removeClass("is-invalid"), jQuery(element).remove();
        },
        rules: {
            no_ktp: {
                required: true,
            },
            nama: {
                required: true,
            },
            tmp_lahir: {
                required: true,
            },
            tgl_lahir: {
                required: true,
            },
            no_hp: {
                required: true,
            },
            pendidikan: {
                required: true,
            },
            pekerjaan: {
                required: true,
            },
            nama_ibu: {
                required: true,
            },
        },
        messages: {
            no_ktp: {
                required: "Nomor KTP Wajib Diisi!",
            },
            nama: {
                required: 'Nama Lengkap Wajib Diisi!',
            },
            tmp_lahir: {
                required: "Tempat Lahir Wajib Diisi!",
            },
            tgl_lahir: {
                required: 'Tanggal Lahir Wajib Diisi!',
            },
            no_hp: {
                required: "Nomor HP Wajib Diisi!",
            },
            pendidikan: {
                required: 'Pendidikan Terkahir Wajib Diisi!',
            },
            pekerjaan: {
                required: 'Pekerjaan Wajib Diisi!'
            },
            nama_ibu: {
                required: 'Nama Ibu Wajib Diisi!',
            },
        },
        submitHandler: function () {
            var fomr = $('form#form-step1')[0];
            var formData = new FormData(fomr);
            $.ajax({
                type: 'POST',
                url: laroute.route('anggota.tambah.step1.store'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: 'Data Sedang Di Proses!',
                        imageUrl: laroute.url('public/media/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function (response) {
                    if (response.fail == false) {
                        window.location.href = laroute.route('anggota.tambah.step2');
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: "Periksa Form Input!",
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'error'
                        });
                        for (control in response.errors) {
                            $('#login-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    alert('Error adding / update data');
                }
            });
            return false;
        }
    });

    b.validate({
        onfocusout: function(element) {
            $(element).valid()
            if ($(element).valid()) {
                b.find('button:submit').prop('disabled', false);  
            } else {
                b.find('button:submit').prop('disabled', 'disabled');
            }
        },
        errorClass: "invalid-feedback animated fadeInDown",
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').find('div.invalid-feedback').html(error);
            console.log(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        success: function (element) {
            $(element).removeClass('is-invalid').addClass("is-valid");
            $(element).closest(".form-group").removeClass("is-invalid"), jQuery(element).remove();
        },
        rules: {
            alamat : {
                required: true,
            },
            wilayah_id : {
                required: true,
            },
            kode_pos: {
                required: true,
            },
        },
        messages: {
            alamat: {
                required: "Alamat Wajib Diisi!",
            },
            wilayah_id: {
                required: 'Wilayah Wajib Diisi!',
            },
            kode_pos: {
                required: "Kode POS Wajib Diisi!",
            },
        },
        submitHandler: function () {
            var fomr = $('form#form-step2')[0];
            var formData = new FormData(fomr);
            $.ajax({
                type: 'POST',
                url: laroute.route('anggota.tambah.step2.store'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: 'Data Sedang Di Proses!',
                        imageUrl: laroute.url('public/media/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function (response) {
                    if (response.fail == false) {
                        window.location.href = laroute.route('anggota.tambah.step3');

                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: "Periksa Form Input!",
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'error'
                        });
                        for (control in response.errors) {
                            $('#login-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    alert('Error adding / update data');
                }
            });
            return false;
        }
    });

    if($('input').hasClass('input-currency')){
        new AutoNumeric(".input-currency", {
            allowDecimalPadding: false,
            alwaysAllowDecimalCharacter: true,
            caretPositionOnFocus: "start",
            currencySymbol: "Rp ",
            decimalCharacter: ",",
            decimalPlaces: 0,
            digitGroupSeparator: ".",
            unformatOnSubmit: true
        });
    }

    $("#field-simla").on("keyup change", function(){
        var simla = AutoNumeric.getNumber('#field-simla');
        var total = 330000 + simla;
        $("#total_setoran").html(total);
        new AutoNumeric.multiple("#total_setoran", showOptionCurrency);
    });
    var showOptionCurrency = {
        allowDecimalPadding: false,
        alwaysAllowDecimalCharacter: true,
        caretPositionOnFocus: "start",
        currencySymbol: "Rp ",
        decimalCharacter: ",",
        decimalPlaces: 0,
        digitGroupSeparator: ".",
        unformatOnSubmit: true,
    };
    if($('div').hasClass('currency')){
        new AutoNumeric.multiple(".currency", showOptionCurrency);
    }


    c.validate({
        onfocusout: function(element) {
            $(element).valid()
            if ($(element).valid()) {
                c.find('button:submit').prop('disabled', false);  
            } else {
                c.find('button:submit').prop('disabled', 'disabled');
            }
        },
        errorClass: "invalid-feedback animated fadeInDown",
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').find('div.invalid-feedback').html(error);
            console.log(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        success: function (element) {
            $(element).removeClass('is-invalid').addClass("is-valid");
            $(element).closest(".form-group").removeClass("is-invalid"), jQuery(element).remove();
        },
        rules: {
            // alamat : {
            //     required: true,
            // },
            // wilayah_id : {
            //     required: true,
            // },
            // kode_pos: {
            //     required: true,
            // },
        },
        messages: {
            // alamat: {
            //     required: "Alamat Wajib Diisi!",
            // },
            // wilayah_id: {
            //     required: 'Wilayah Wajib Diisi!',
            // },
            // kode_pos: {
            //     required: "Kode POS Wajib Diisi!",
            // },
        },
        submitHandler: function () {
            var fomr = $('form#form-step3')[0];
            var formData = new FormData(fomr);
            $.ajax({
                type: 'POST',
                url: laroute.route('anggota.tambah.step3.store'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: 'Data Sedang Di Proses!',
                        imageUrl: laroute.url('public/media/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function (response) {
                    if (response.fail == false) {
                        Swal.close();
                        Swal.fire({
                            title: `Berhasil!`,
                            icon: 'success',
                            html: `Pendaftaran Anggota Baru Berhasil!
                                <br><br>
                                <a href="`+ laroute.route('simkop.setoran') +`" class="btn btn-outline-primary">
                                    <i class="si si-plus mr-1"></i>Tambah Anggota Lain
                                </a> 
                                <a href="`+ laroute.route('admin.post.tambah') +`" class="btn btn-primary">
                                    <i class="si si-magnifier mr-1"></i>Detail Setoran
                                </a>`,
                            showCancelButton: false,
                            showConfirmButton: false,
                            // allowOutsideClick: false
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: "Periksa Form Input!",
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'error'
                        });
                        for (control in response.errors) {
                            $('#login-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    alert('Error adding / update data');
                }
            });
            return false;
        }
    });



});