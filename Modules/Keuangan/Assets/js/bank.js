
jQuery(function() {
    var croppie = null;
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

    $("#uploadThumb").on("change", function(event) {
        readURL(this);  
    });

    function readURL(input) {    
        if (input.files && input.files[0]) {   
            var reader = new FileReader();
            var filename = $("#uploadThumb").val();
            filename = filename.substring(filename.lastIndexOf('\\')+1);
            reader.onload = function(e) {
            $('#thumbPrev').attr('src', e.target.result);
            $('.custom-file-label').text(filename);
            }
            reader.readAsDataURL(input.files[0]);    
        }
    }

    $(".rotate").on("click", function() {
        croppie.rotate(parseInt($(this).data('deg')));
    });

    $('#cropModal').on('hidden.bs.modal', function (e) {
        setTimeout(function() { croppie.destroy(); }, 100);
    });

    $('#cari_produk').keyup(function () {
        oTable.search($(this).val()).draw();
    });
    
    var modal = $('#modalRekening');

    $(document).on('click', '#btn-add_rekening', function () {  
        modal.find('h3.modal-title').html('Tambah Rekening Baru');
        modal.modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#form-bank')[0].reset();
        $('#form-bank').find('.form-control').removeClass('is-invalid');
    
        
    });

    $(document).on('submit', '#form-bank', function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-bank')[0]);

        if($('#method').val == 'update'){
            url = laroute.route('bank.update')
        }else{
            url = laroute.route('bank.store')
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('public/media/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                $('.is-invalid').removeClass('is-invalid');
                if (response.fail == false) {
                    Swal.fire({
                        title: "Berhasil",
                        text: "Rekening Baru Berhasil Ditambahkan",
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    $('#modalRekening').modal('hide');
                    $('#list-etlase').DataTable().ajax.reload();
                } else {
                    Swal.close();
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error adding / update data');
            }
        });
    });

    $(document).on('click', '.btn-edit', function () { 
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('admin.ppdb.rekening.edit', { id: id }),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('public/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function(data) {
                Swal.close();
                modal.find('h3.modal-title').html('Ubah Rekening');
                modal.modal({
                    backdrop: 'static',
                    keyboard: false
                });

                modal.find('input#field-id').val(data.id);
                modal.find('input#field-bank').val(data.bank);
                modal.find('input#field-kode').val(data.kode);
                modal.find('input#field-rekening').val(data.no_rek);
                modal.find('input#field-nama').val(data.nama);

                if(data.icon === null)
                {
                    $("#thumbPrev").attr("src", "https://via.placeholder.com/128x64.png?text=ICON+BANK");
                }else{
                    $("#thumbPrev").attr("src", data.icon_url);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
            }
        });

        $("#form-rekening").submit(function (e) {
            e.preventDefault();
            var formData = new FormData($('#form-rekening')[0]);
            $.ajax({
                url: laroute.route('admin.ppdb.rekening.update'),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: ' ',
                        imageUrl: laroute.url('public/img/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function (response) {
                    $('.is-invalid').removeClass('is-invalid');
                    if (response.fail == false) {
                        Swal.fire({
                            title: "Berhasil",
                            text: "Data Berhasil Diperbaharui!",
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'success'
                        });
                        $('#modalRekening').modal('hide');
                        oTable.ajax.reload();
                    } else {
                        Swal.close();
                        for (control in response.errors) {
                            $('#field-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
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

    $(document).on('click', '.btn-hapus', function () { 
        id = $(this).attr('data-id');
        Swal.fire({
            title: "Anda Yakin?",
            text: "Data Yang Dihapus Tidak Akan Bisa Dikembalikan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak, Batalkan!',
            reverseButtons: true,
            allowOutsideClick: false,
            confirmButtonColor: '#af1310',
            cancelButtonColor: '#fffff',
        })
        .then((result) => {
            if (result.value) {
            $.ajax({
                url: laroute.route('admin.ppdb.rekening.hapus', { id: id }),
                type: "GET",
                dataType: "JSON",
                beforeSend: function(){
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: ' ',
                        imageUrl: laroute.url('public/img/loading.gif', ['']),
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                success: function() {
                    Swal.fire({
                        title: "Berhasil",
                        text: 'Data Berhasil Dihapus!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    $('#list-rekening').DataTable().ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
            } else {
                window.setTimeout(function(){
                    location.reload();
                } ,1500);
            }
        });
    });

    
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

    load_content();
    
});


function load_content(){
    var dataList =$("#data-list");
    var dataLength = dataList.find('thead tr th').length;
    
    var parameter = {};
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');

    var keyword = $('#search-data-list').val();
    if(keyword) parameter['keyword'] = keyword;
    

    $.ajax({
        url: laroute.route('bank'),
        type: "GET",
        dataType: "JSON",
        data: parameter,
        beforeSend: function(){
            dataList.find('tbody').html('');
            dataList.find('tbody').append(`<tr>
                <td colspan="${ dataLength }">
                    <div class="text-center py-50">
                        <div class="spinner-border text-primary wh-50" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </td>
            </tr>`);
            navNext.prop('disabled', true);
            navPrev.prop('disabled', true);
        },
        success: function(response) {
            $('#data-list tbody tr').not('#data-list tbody tr#loading').remove();

            var row = "";
            if(response.data.length !== 0){
                $.each(response.data, function(k, item) {
                    row += create_element(item);
                });
            }else{
               row = `<tr>
                    <td colspan="${dataLength}">
                        <div class="text-center">
                            <img class="img-fluid" src="`+ laroute.url('public/media/placeholder/empty.png', ['']) +`">
                            <div>
                                <h3 class="font-size-24 font-w600 mt-3">Data Tidak Ditemukan</h3>
                            </div>
                        </div>
                    </td>
                </tr>`;
            }
            
            dataList.find('tbody').append(row);

            // Table Navigation
            response.next_page_url !== null ? navNext.prop('disabled', false) : navNext.prop('disabled', true);
            response.prev_page_url !== null ? navPrev.prop('disabled', false) : navPrev.prop('disabled', true);
            if(response.total === 0){
                var navigasi = '0 - 0 / 0';
            }else{
                var navigasi = response.from +' - '+ response.to +' / '+ response.total;
            }
            $('#content-nav span').html(navigasi);
        },
        complete: function(){
            $('#data-list tbody tr#loading').addClass('d-none');
        },

        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}

function create_element(item){
    let row = `<tr>
        <td><img height="40px" src="${ laroute.url('public/' + item.logo, []) }"/></td>
        <td>${ item.nama }</td>
        <td>${ item.kode }</td>
        <td>${ item.no_rekening }</td>
        <td>${ item.atas_nama }</td>
        <td class="text-center">
            <button type="button" class="btn btn-secondary btn-sm btn-detail" data-id="${ item.id }">
                <i class="si si-magnifier"></i>
                Detail
            </button>
        </td>
    </tr>`;
    return row;
}
