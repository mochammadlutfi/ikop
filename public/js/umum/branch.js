var map, marker, myLatLng;
var markerArr = [];
jQuery(function () {

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
        initMap(parseFloat($('#lat').val()), parseFloat($('#lng').val()));
    }

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

    daerah.on("change", function(){
        $('#page').val(1)
        load_content();
    });
    
    load_content();

    $("#btn-loadMore").on("click", function(e) {
        var page_old = parseInt($('#page').val());
        var page = page_old + 1;
        $('#page').val(page);
        load_content();
    });

    $("#btn-direction").on("click", function(e){
    });
});

function direction()
{
    alert('coba');
}

function load_content()
{
    $.ajax({
        url: laroute.route('branch.data'),
        type: "GET",
        dataType: "JSON",
        data: {
            daerah: $('#filter-daerah').val(),
            keyword : $('#filter-keyword').val(),
            page: $('#page').val(),
        },
        beforeSend: function(){
            $('#loadingContent').removeClass('d-none');
        },
        success: function(response) {       
            if(response.current_page == 1)
            {
                $('#dataContent').html('');
                if(response.next_page_url !== null)
                {
                    $('#btn-loadMore').removeClass('d-none');
                }
            } 
            if(response.data.length !== 0)
            {
                var i = 0;
                $.each(response.data, function(k, v) {
                    $('#dataContent').append(`
                    <div class="block block-rounded block-bordered block-shadow mb-2 block-link-pop c-pointer" data-toggle="collapse" data-parent="#dataContent" 
                    href="#toko-`+response.data[k].id +`" aria-expanded="true" aria-controls="toko-`+response.data[k].id +`"
                    onClick="jumpToMarker(`+ i++ +`)">
                        <div class="block-content bg-body-light py-10">
                        <h3 class="block-title"><i class="fa fa-building mr-1"></i>`+ response.data[k].name +`</h3>
                        </div>
                        <div class="block-content py-15">
                            <p>`+ response.data[k].address +`</p>
                            <div id="toko-`+response.data[k].id +`" class="collapse" role="tabpanel" aria-labelledby="toko-h`+response.data[k].id +`" data-parent="#dataContent">
                                <a href="https://www.google.com/maps/dir/?api=1&origin=&destination=`+ response.data[k].lat +`,`+ response.data[k].lng +`" target="_blank" class="btn btn-outline-primary btn-block">Navigasi</a>
                            </div>
                        </div>
                    </div>`
                    );
    
                    var marker = new google.maps.Marker({
                        map: map,
                        animation: google.maps.Animation.DROP,
                        position: new google.maps.LatLng(parseFloat(response.data[k].lat), parseFloat(response.data[k].lng)),
                        title: response.data[k].name,
                        branch_id :  response.data[k].id,
                    });
                    marker.addListener("click", (tab) => {
                        map.setZoom(15);
                        map.setCenter(marker.getPosition());
                        $('#dataContent').find('.collapse').removeClass('show');
                        $(`#toko-`+response.data[k].id).addClass('show');
                      });
                    markerArr.push(marker);
                });
            }else{
                $('#btn-loadMore').addClass('d-none');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}

function jumpToMarker(cnt){
    map.panTo(markerArr[cnt].getPosition());
    map.setZoom(15);
    map.setCenter(markerArr[cnt].getPosition());
}

function initMap(lat, lng) {
    myLatLng = new google.maps.LatLng(lat, lng);

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: myLatLng
    });

    var marker = new google.maps.Marker({
        map: map,
        animation: google.maps.Animation.DROP,
        position: myLatLng
    });

    google.maps.event.addListener(marker, 'dragend', function (evt) {
        geocodePosition(marker.getPosition());
        $('#lat').val(marker.getPosition().lat());
        $('#lng').val(marker.getPosition().lng());
    });

    var markerCluster = new MarkerClusterer(map, markerArr, {
        imagePath:
        'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
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

