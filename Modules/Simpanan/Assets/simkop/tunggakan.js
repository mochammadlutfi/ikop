jQuery(function() { 
    load_content();
    moment.locale('id');
    var modal = $('#modalDetail');

    $(document).on('click', '#btn-detail', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: laroute.route('simkop.tunggakan.detail', { id: id }),
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
            success: function(data) {
                Swal.close();
                modal.find('#list-tunggakan').html('');
                modal.find('#modal_title').html('Detail Tunggakan');
                modal.find('#field-anggota_id').html(`: ` + data.anggota_id);
                modal.find('#field-nama').html(`: ` + data.nama);
                modal.find('#field-jumlah').html(`: ` + data.tunggakan_simkop.jumlah + ' Bulan');
                modal.find('#field-nominal').html(`: ` + data.tunggakan_simkop.nominal);
                $.each(data.tunggakan_simkop.list, function(k) {
                    var list = '';

                    $.each(data.tunggakan_simkop.list[k], function(ka) {
                        list += `<span class="badge badge-primary mr-1">${ data.tunggakan_simkop.list[k][ka] }</span>`
                    });

                    modal.find('#list-tunggakan').append(`
                        <div class="block block-rounded block-shadow block-shadow-2 block-themed mb-2">
                            <div class="block-header px-3 py-2">
                                <h3 class="block-title font-w600">${ k }</h3>
                            </div>
                            <div class="block-content px-3 py-2">${ list }</div>
                        </div>
                    `);

                });
                modal.modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error deleting data');
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
});


function load_content(){
    var keyword = $('#search-data-list').val();
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');
    
    $.ajax({
        url: laroute.route('simkop.tunggakan'),
        type: "GET",
        dataType: "JSON",
        data: {
            keyword: keyword,
            page: page,
        },
        beforeSend: function(){
            $('#data-list tbody tr#loading').removeClass('d-none');
            navNext.prop('disabled', true);
            navPrev.prop('disabled', true);
        },
        success: function(response) {
            $('#data-list tbody tr').not('#data-list tbody tr#loading').remove();
            if(response.data.length !== 0){
                $.each(response.data, function(k, v) {
                    $('#data-list tbody').append(`
                        <tr>
                            <td>${ response.data[k].anggota_id }</td>
                            <td>${ response.data[k].nama }</td>
                            <td>${ moment(response.data[k].last_simkop.created_at).format('D MMMM YYYY') }</td>
                            <td>${ response.data[k].tunggakan_simkop.jumlah } Bulan </td>
                            <td><div class="currency">${ response.data[k].tunggakan_simkop.nominal }</div></td>
                            <td class="text-center">
                                <button class="btn btn-secondary btn-sm js-tooltip" id="btn-detail" data-toggle="tooltip" data-placement="top" title="Detail Tunggakan" data-id="${ response.data[k].anggota_id }">
                                    <i class="si si-magnifier"></i> Detail
                                </button>
                            </td>
                        </tr>              
                    `);
                });
            }else{

                $('#data-list tbody').append(`
                <tr>
                    <td colspan="6">
                        <div class="text-center">
                            <img class="img-fluid" src="`+ laroute.url('public/img/icon/not_found.png', ['']) +`">
                            <div>
                                <h3 class="font-size-24 font-w600 mt-3">Data Tidak Ditemukan</h3>
                            </div>
                        </div>
                    </td>
                </tr>          
                `);
            }

            // Table Navigation
            response.next_page_url !== null ? navNext.prop('disabled', false) : navNext.prop('disabled', true);
            response.prev_page_url !== null ? navPrev.prop('disabled', false) : navPrev.prop('disabled', true);
            if(response.total === 0){
                var navigasi = '0 - 0 / 0';
            }else{
                var navigasi = response.from +' - '+ response.to +' / '+ response.total;
            }
            $('#content-nav span').html(navigasi);
            $('#data-list tbody tr#loading').addClass('d-none');
            // End Table Navigation
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}
