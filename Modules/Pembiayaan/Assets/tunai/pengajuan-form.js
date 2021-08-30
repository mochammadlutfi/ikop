jQuery(function () {
    var start = moment().startOf('month');
    var end = moment();
    var form = $("#form-pengajuan");


    var anggota = $('#field-anggota_id').select2({
        placeholder: 'Masukan ID Anggota / Nama',
        theme: 'bootstrap4',
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

    anggota.on("change", function () {
        var id = $(this).val();
        $.ajax({
            url: laroute.route('anggota.get_info', {
                id: id
            }),
            type: "GET",
            dataType: "JSON",
            beforeSend: function () {
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('public/media/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                Swal.close();
                form.find('input[name="no_ktp"]').val(response.no_ktp);
                form.find('input[name="nama"]').val(response.nama);
                form.find('input[name="no_hp"]').val(response.no_hp);
                form.find('textarea[name="alamat"]').val(response.alamat_full);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
            }
        });
    });



    form.on("submit", function (e) {
        e.preventDefault();
        var fomr = form[0];
        var formData = new FormData(fomr);

        url = laroute.route('pmb_tunai.pengajuan.store');

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
                                <a href="` + laroute.route('simla.penarikan') + `" class="btn btn-outline-primary">
                                    <i class="si si-plus mr-1"></i>Tambah Penarikan Lain
                                </a> 
                                <a href="` + laroute.route('simla.invoice', response.no_invoice) + `" class="btn btn-primary">
                                    <i class="si si-magnifier mr-1"></i>Detail Penarikan
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
                            icon: 'fa fa-times',
                            message: response.errors[control]
                        }, {
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
    _hitung();
});


$(document).on('change', 'input#field-jumlah', function () {
    jumlah = getRawCurrency($(this).val());
    min = parseInt($(this).attr('min'));
    max = parseInt($(this).attr('max'));
    if (jumlah < min) {
        AutoNumeric.set(this, min);
        // $(this).val(min);
    } else if (jumlah > max) {
        AutoNumeric.set(this, max);
        // $(this).val(max);
    }
    _hitung();
});

$(document).on('change', 'select#field-tenor', function () {
    _hitung();
});

function _hitung() {
    var jumlah = getRawCurrency($("#field-jumlah").val());
    var bunga = 3.95;
    var tenor = $("#field-tenor").val();
    var total = jumlah * bunga / 100 * tenor;
    var admin = jumlah * 1 / 100;

    AutoNumeric.set('#field-biaya_admin', admin);
    AutoNumeric.set('#field-diterima', jumlah - admin);

    $('#field-jumlah_bunga').val(total);

    AutoNumeric.set('#field-angsuran_pokok', jumlah / tenor);
    AutoNumeric.set('#field-angsuran_bunga', (total + jumlah) / tenor);
}
