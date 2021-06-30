jQuery(document).ready(function () {
    load_content();
    $("#field-kategori").select2({
        placeholder: 'Pilih Kategori',
        allowClear: true,
        ajax: {
            url: laroute.route('admin.postKategori.json'),
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

    $(document).on('click', 'button#nextProduk', function() {     
        current_page = parseInt($('#current_page').val());
        current_page += 1;
        load_content(null, current_page);
    });
    
    // Navigasi Prev Page Modal Produk
    $(document).on('click', 'button#prevProduk', function() {
        current_page = parseInt($('#current_page').val());
        current_page -= 1;
        load_content(null, current_page);
    });
});

function load_content(keyword, page = 1)
{
    $.ajax({
        url: laroute.route('admin.pages'),
        type: "GET",
        dataType: "JSON",
        data: {
            keyword: keyword,
            page: page,
        },
        beforeSend: function(){
            $('#post-list tbody').html(`
            <tr>
                <td colspan="5">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 text-center py-50">
                            <div class="spinner-border text-primary wh-50" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>`);
        },
        success: function(response) {
            if(!response.next_page)
            {
                $('#nextProduk').prop('disabled', true);
            }else{
                $('#nextProduk').prop('disabled', false);
            }
            if(!response.prev_page)
            {
                $('#prevProduk').prop('disabled', true);
            }else{
                $('#prevProduk').prop('disabled', false);
            }
            $('#content-nav span').html(response.navigasi);
            $('#post-list tbody').html(response.html);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}

function hapus(id) {
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
            url: laroute.route('admin.page.hapus', { id: id }),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('assets/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function(data) {
                Swal.fire({
                    title: "Berhasil",
                    text: 'Halaman Berhasil Dihapus!',
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                load_content();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
            }
        });
        }else{
            Swal.close();
        }
    });
}