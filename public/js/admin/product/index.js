
var category_name = "";
var subcategory_name = "";
var subsubcategory_name = "";
var category_id = null;
var subcategory_id = null;
var subsubcategory_id = null;
var kategori_id = null;
jQuery(function() {
    load_content();

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

function load_content()
{

    var kategori = $('#kategori').val();
    var keyword = $('#search-data-list').val();
    var page = $('#current_page').val();

    var navNext = $('#next-data-list');
    var navPrev = $('#prev-data-list');
    
    $.ajax({
        url: laroute.route('admin.product'),
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
                            <td width="5%">
                                <img src="`+ response.data[k].image_url +`" width="40px">
                            </td>
                            <td>
                                <div class="font-size-16 font-w600">`+ response.data[k].title +`</div>
                                <div class="font-size-15">`+ response.data[k].category.title +`</div>
                            </td>
                            <td>`+ response.data[k].views +`x </td>
                            <td>`+ response.data[k].dibuat +`</td>
                            <td>
                                <a class="btn btn-secondary btn-sm js-tooltip" data-toggle="tooltip" data-placement="top" title="Ubah" href="`+ laroute.route('admin.product.edit', { id : response.data[k].id }) +`">
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
            
            if(response.total === 0 || response.total == null){
                var navigasi = 'Menampilkan Data 0 - 0 Dari 0';
            }else{
                var navigasi = 'Menampilkan Data '+response.from +' - '+ response.to +' Dari '+ response.total;
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
            url: laroute.route('admin.product.hapus', { id: id }),
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
                Swal.fire({
                    title: "Berhasil",
                    text: 'Berita Berhasil Dihapus!',
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

function list_item_highlight(el){
    $(el).parent().children().each(function(){
        $(this).removeClass('selected');
    });
    $(el).addClass('selected');
}

function get_subcategories_by_category(el, cat_id){
    list_item_highlight(el);
    category_id = cat_id;
    kategori_id = cat_id;
    subcategory_id = null;
    subsubcategory_id = null;
    category_name = $(el).html();
    $('#cat_select').html(category_name);
    $('#subcategories').html(null);
    $('#subsubcategory_list').hide();
    $.ajax({
        type:"POST",
        url: laroute.route('admin.productCategory.sub'),
        data: {
            category_id:category_id
        },
        success: function(data){
            for (var i = 0; i < data.length; i++) {
                $('#subcategories').append('<li onclick="get_subsubcategories_by_subcategory(this, '+data[i].id+')">'+data[i].title+'</li>');
            }
            $('#subcategory_list').show();
        }
    });

}

function get_subsubcategories_by_subcategory(el, subcat_id){
    list_item_highlight(el);
    subcategory_id = subcat_id;
    kategori_id = subcat_id;
    subsubcategory_id = null;
    subcategory_name = $(el).html();
    $('#subsubcategories').html(null);
    $.ajax({
        type:"POST",
        url: laroute.route('admin.productCategory.sub'),
        data: {
            category_id:subcategory_id
        },
        success: function(data){
            for (var i = 0; i < data.length; i++) {
                $('#subsubcategories').append('<li onclick="confirm_subsubcategory(this, '+data[i].id+')">'+data[i].title+'</li>');
            }
            $('#subsubcategory_list').show();
        }
    });
}

function confirm_subsubcategory(el, subsubcat_id){
    list_item_highlight(el);
    subsubcategory_id = subsubcat_id;
    kategori_id = subsubcat_id;
    subsubcategory_name = $(el).html();
}

function filterListItems(el, list){
    filter = el.value.toUpperCase();
    li = $('#'+list).children();
    for (i = 0; i < li.length; i++) {
        if ($(li[i]).html().toUpperCase().indexOf(filter) > -1) {
            $(li[i]).show();
        } else {
            $(li[i]).hide();
        }
    }
    
}

function closeModal(){
    if(category_id > 0){
        $('#kategori_id').val(kategori_id);
        $('#category_id').val(category_id);
        $('#subcategory_id').val(subcategory_id);
        $('#subsubcategory_id').val(subsubcategory_id);
        if(category_id != null)
        {
            kategorinya = category_name;
        }
        if(subcategory_id != null)
        {
            kategorinya += ' > '+ subcategory_name;
        }
        if(subsubcategory_id != null)
        {
            kategorinya += ' > '+ subsubcategory_name;
        }
        $('#field-kategori').html(kategorinya);
        
        $('#categorySelectModal').modal('hide');
    }
    else{
        alert('Please choose categories...');
    }
}