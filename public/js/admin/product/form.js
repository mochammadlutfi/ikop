jQuery(function() {

    // Crop Start
    var croppie = null;
    var cropModal = $("#cropModal");
    var el = document.getElementById('resizer');
    var formData = new FormData();
    var category_name = "";
    var subcategory_name = "";
    var subsubcategory_name = "";
    var category_id = null;
    var subcategory_id = null;
    var subsubcategory_id = null;
    var kategori_id = null;

    $.base64ImageToBlob = function(str) {
        var pos = str.indexOf(';base64,');
        var type = str.substring(5, pos);
        var b64 = str.substr(pos + 8);
        var imageContent = atob(b64);
        var buffer = new ArrayBuffer(imageContent.length);
        var view = new Uint8Array(buffer);
        for (var n = 0; n < imageContent.length; n++) {
          view[n] = imageContent.charCodeAt(n);
        }
        var blob = new Blob([buffer], { type: type });
        return blob;
    }

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
                width: 340,
                height: 418,
                type: 'square'
            },
            original : {
                width: 340,
                height: 418,
                type: 'square'
            },
            boundary: {
                width: 360,
                height: 440
            },
            enableOrientation: true
        });
        $.getImage(event.target, croppie);
    });

    $("#upload").on("click", function() {
        croppie.result({
            type: 'base64',
            size: 'original',
			format:'png',
			size: { 
                width: 1200, height: 1393 
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

    $(".post-desc").summernote({
        height: '200px',
        // toolbar: [
        //     ['undo', ['undo',]],
        //     ['redo', ['redo',]],
        //     ['style', ['bold', 'italic', 'underline','clear']],
        //     ['font', ['strikethrough',]],
        //     ['fontsize', ['fontsize']],
        //     ['color', ['color']],
        //     ['para', ['ul', 'ol', 'paragraph']],
        // ]
    });

    $("#form-product").on("submit",function (e) {
        e.preventDefault();
        var fomr = $('form#form-product')[0];
        var formData = new FormData(fomr);

        if($('#method').val() === 'update'){
            link = laroute.route('admin.product.update')
        }else{
            link = laroute.route('admin.product.save')
        }

        $.ajax({
            url: link,
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
                        html: `Data Produk Berhasil Disimpan
                            <br><br>
                            <a href="`+ laroute.route('admin.product') +`" class="btn btn-outline-danger">
                                <i class="si si-close mr-1"></i>Keluar
                            </a> 
                            <a href="`+ laroute.route('admin.product.add') +`" class="btn btn-outline-info">
                                <i class="si si-plus mr-1"></i>Tambah Produk Lain
                            </a>`,
                        showCancelButton: false,
                        showConfirmButton: false,
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

    $('#subcategory_list').hide();
    $('#subsubcategory_list').hide();

});


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