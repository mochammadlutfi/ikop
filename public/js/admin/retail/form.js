var map, marker, lat, lng;
jQuery(function () {

    var daerah = $('.find-daerah').select2({
        placeholder: 'Cari Kota/Kabupaten',
        theme: 'bootstrap4',
        language: 'id',
        ajax: {
            url: laroute.route('daerahSelect'),
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
        templateResult: function (response) {
            if (response.loading) {
                return "Mencari...";
            } else {
                var selectionText = response.text.split(",");
                var $returnString = $('<span>' + selectionText[0] + ', ' + selectionText[1] + '</span>');
                return $returnString;
            }
        },
        templateSelection: function (response) {
            return response.text;
        },
    });

    if($('.find-daerah').attr("data-text") && $('.find-daerah').attr("data-id")){

        daerahOption = new Option($('.find-daerah').attr("data-text"), $('.find-daerah').attr("data-id"), true, true);
        daerah.append(daerahOption).trigger('change');
    }


    $("#form-retail").on("submit", function (e) {
        e.preventDefault();
        var fomr = $('form#form-retail')[0];
        var formData = new FormData(fomr);

        if($('#method').val() === 'update'){
            link = laroute.route('admin.store.update')
        }else{
            link = laroute.route('admin.store.save')
        }

        $.ajax({
            url: link,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
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
                        html: `Data Baru Berhasil Disimpan!
                            <br><br>
                            <a href="` + laroute.route('admin.store') + `" class="btn btn-outline-danger">
                                <i class="si si-close mr-1"></i>Keluar
                            </a> 
                            <a href="` + laroute.route('admin.store.add') + `" class="btn btn-outline-info">
                                <i class="si si-plus mr-1"></i>Tambah Toko Lain
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

    if(!$('#lat').val() && !$('#lng').val())
    {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                initMap(parseFloat(position.coords.latitude), parseFloat(position.coords.longitude))
            },
            function errorCallback(error) {
                console.log(error)
            }
        );
    }else{
        initMap(parseFloat($('#lat').val()), parseFloat($('#lng').val()))
    }
});

function initMap(lat, lng) {
    var myLatLng = new google.maps.LatLng(lat, lng);

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: myLatLng
    });

    marker = new google.maps.Marker({
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        position: myLatLng
    });

    google.maps.event.addListener(marker, 'dragend', function (evt) {
        geocodePosition(marker.getPosition());
        $('#lat').val(marker.getPosition().lat());
        $('#lng').val(marker.getPosition().lng());

    });

    const input = document.getElementById("search-box");
    const searchBox = new google.maps.places.SearchBox(input);

    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
    
        if (places.length == 0) {
          return;
        }
        // Clear out the old markers.
        // For each place, get the icon, name and location.
        const bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
          if (!place.geometry || !place.geometry.location) {
            console.log("Returned place contains no geometry");
            return;
          }
          const icon = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25),
          };
          marker.setPosition(place.geometry.location);
          $('#lat').val(place.geometry.location.lat);
          $('#lng').val(place.geometry.location.lng);
    
          if (place.geometry.viewport) {
            bounds.union(place.geometry.viewport);
          } else {
            bounds.extend(place.geometry.location);
          }
        });
        map.fitBounds(bounds);
      });

}

function geocodePosition(pos) {
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({
            latLng: pos
        },
        function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $("#mapSearchInput").val(results[0].formatted_address);
                $("#mapErrorMsg").hide(100);
            } else {
                $("#mapErrorMsg").html('Cannot determine address at this location.' + status).show(100);
            }
        }
    );
}
