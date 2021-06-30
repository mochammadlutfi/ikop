
var roleSelect, oTable;
jQuery(function() {

    $(".input-password a").on('click', function(event) {
        event.preventDefault();
        if($('.input-password input').attr("type") == "text"){
            $('.input-password input').attr('type', 'password');
            $('.input-password i').addClass( "fa-eye-slash" );
            $('.input-password i').removeClass( "fa-eye" );
        }else if($('.input-password input').attr("type") == "password"){
            $('.input-password input').attr('type', 'text');
            $('.input-password i').removeClass( "fa-eye-slash" );
            $('.input-password i').addClass( "fa-eye" );
        }
    });

    roleSelect = $("#field-role").select2({
        allowClear: true,
        theme : 'bootstrap4',
        ajax: {
            url: laroute.route('admin.userRole.json'),
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

    $("#form-user").on("submit",function (e) {
        e.preventDefault();
        var fomr = $('form#form-user')[0];
        var formData = new FormData(fomr);

        $.ajax({
            url: laroute.route('admin.user.save'),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: 'Data Sedang Diproses!',
                    imageUrl: laroute.url('public/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                Swal.close();
                $('.is-invalid').removeClass('is-invalid');
                if (response.fail == false) {
                    $('#modal_embed').modal('hide');
                    Swal.fire({
                        title: `Berhasil!`,
                        showConfirmButton: false,
                        icon: 'success',
                        html: `Pengguna Baru Berhasil Disimpan!
                            <br><br>
                            <a href="`+ laroute.route('admin.user') +`" class="btn btn-alt-danger">
                                <i class="si si-close mr-1"></i>Keluar
                            </a> 
                            <a href="`+ laroute.route('admin.user.tambah') +`" class="btn btn-alt-primary">
                                <i class="si si-plus mr-1"></i>Tambah Pengguna Lain
                            </a>`,
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
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
                        },{
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
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});


function load_content(){

    var kategori = $('#kategori').val();
    var keyword = $('#search-data-list').val();
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');
    
    $.ajax({
        url: laroute.route('admin.posts'),
        type: "GET",
        dataType: "JSON",
        data: {
            keyword: keyword,
            kategori_id : kategori,
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
                            <td>
                                <div class="custom-control custom-checkbox mb-5">
                                    <input class="custom-control-input" type="checkbox" name="example-checkbox1" id="example-checkbox1" value="option1" >
                                    <label class="custom-control-label" for="example-checkbox1"></label>
                                </div>
                            </td>
                            <td>`+ response.data[k].name +`</td>
                            <td>`+ response.data[k].email +`</td>
                            <td>`+ response.data[k].username +`</td>
                            <td>`+ response.data[k].role +`</td>
                            <td>
                                <a class="btn btn-secondary btn-sm js-tooltip" data-toggle="tooltip" data-placement="top" title="Ubah" href="`+ laroute.route('admin.user.edit', { id : response.data[k].id }) +`">
                                    <i class="si si-note"></i>
                                </a>
                                <a class="btn btn-secondary btn-sm js-tooltip" data-toggle="tooltip" data-placement="top" title="Hapus" href="javascript:void(0);" onclick="hapus(`+ response.data[k].id +`)">
                                    <i class="si si-trash"></i>
                                </a>
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
                var navigasi = 'Menampilkan Data 0 - 0 Dari 0';
            }else{
                var navigasi = 'Menampilkan Data '+ response.from +' - '+ response.to +' Dari '+ response.total;
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