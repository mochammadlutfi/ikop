jQuery(function() {
    var croppie = null;
    load_tree();

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
        $("#cropModal").modal();
        el = document.getElementById('resizer');
        croppie = new Croppie(el, {
                viewport: {
                    width: 350,
                    height: 350,
                    type: 'square'
                },
                original : {
                    width: 350,
                    height: 350,
                    type: 'square'
                },
                boundary: {
                    width: 360,
                    height: 360
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
                width: 700, height: 700 
            }
        }).then(function(base64) {
            $("#cropModal").modal("hide");
            $("#thumbPrev").attr("src", base64);
            $("#thumbPrev").parent().find('input[name=thumbnail]').val(base64)
        });
    });

    $(".rotate").on("click", function() {
        croppie.rotate(parseInt($(this).data('deg')));
    });

    $('#cropModal').on('hidden.bs.modal', function (e) {
        setTimeout(function() { croppie.destroy(); }, 100);
    });

    $("#form-kategori").on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-kategori')[0]);

        if($('#metode').val() === 'update'){
            link = laroute.route('admin.productCategory.update')
        }else{
            link = laroute.route('admin.productCategory.save')
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
                    text: ' ',
                    imageUrl: laroute.url('public/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                if (response.fail == false) {
                    Swal.fire({
                        title: "Berhasil",
                        text: "Kategori Baru Berhasil Ditambahkan",
                        timer: 1500,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    $('#kategori-tree').jstree().refresh();
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

    $('#kategori-tree').on("changed.jstree", function (e, data) {
        $(".add-sub-category").removeClass("disabled");
        $('#form-kategori')[0].reset();
        $('input[name="thumbnail"]').val('');
        category_id = data.instance.get_node(data.selected[0]).id;
        $('#field-parent_id').val(category_id);
        $.ajax({
            url : laroute.route('admin.productCategory.edit', {id : category_id}),
            type: "GET",
            dataType: "JSON",
            success: function(response)
            {
                $("#hapus-kategori").removeClass("hide");
                $('#hapus-kategori').attr('data-id', response.id);
                $('[name="kategori_id"]').val(response.id);
                $('[name="title"]').val(response.title);
                $('[name="en_title"]').val(response.en_title);
                $('#nama_kategori').val(response.title);
                $("#thumbPrev").attr("src", response.thumbnail);

                $('#metode').val('update');
                if(response.is_active)
                {
                    $( "#field-is_active" ).prop("checked", true);
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
            }
        });
    });

    $(".add-root-category").on("click", function() {
        $('#metode').val('tambah');
        $('#form-title').html('Tambah Kategori');
        $('#field-title').val('');
        $('#field-en_title').val('');
        $("#thumbPrev").attr("src", laroute.url('public/img/placeholder/product.png', ['']));
    });

    $(".add-sub-category").on("click", function() {
        $('#metode').val('tambah');
        parent_nama_kategori = $('#nama_kategori').val();
        $('#form-title').html('Tambah sub kategori ' + parent_nama_kategori);
        $('#field-title').val('');
        $('#field-en_title').val('');
        $("#thumbPrev").attr("src", laroute.url('public/img/placeholder/product.png', ['']));
    });

    $("#hapus-kategori").on("click", function() {

        kategori_id = $(this).data("id");
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
                url: laroute.route('admin.productCategory.hapus', { id: kategori_id }),
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
                        text: 'Kategori Berhasil Dihapus!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    window.setTimeout(function(){
                        location.reload();
                    } ,1500);
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
    
});
function load_tree()
{
    $('#kategori-tree').jstree({
        "core" : {
            "data" : {
                "url" : laroute.route('admin.productCategory.tree'),
                "dataType" : "json"
            }
        }
    });
}