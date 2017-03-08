$(document).ready(function() {
    resetDate();
    $('button#leave-now-btn').click(function() {
        resetDate();
    });
});

var resetDate = function() {
    var now = new Date();
    var minutes = ('0' + now.getMinutes()).slice(-2);
    var hours = now.getHours() + '';
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day)+'T'+(hours)+':'+(minutes);
    $('input#datetime-picker').val(today);
};


var markerSingleton = null;

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        //Epicodus Location
        center: {lat:45.5206223, lng:-122.6795871 },
        zoom: 17
    });
    var infoWindow = new google.maps.InfoWindow({map: map});

    map.addListener('click', function(e) {
        console.log("Clicked");
        placeMarkerAndPanTo(e.latLng, map);
    });

    //    Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            var curLat = pos.lat;
            var curLng = pos.lng;
            document.getElementById("cur_lat").setAttribute("value", curLat);
            document.getElementById("cur_lng").setAttribute("value", curLng);


            var ctaLayer = new google.maps.KmlLayer({
                url: 'https://developer.trimet.org/gis/data/tm_rail_lines.kml',
                map: map
            });

            infoWindow.setPosition(pos);
            infoWindow.setContent('You are here.');
            map.setCenter(pos);
        }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function placeMarkerAndPanTo(latLng, map) {
    if (markerSingleton) {
        markerSingleton.setMap(null);
    } else { // first time clicking on map
        $('select#end').prepend("<option id='pinned-opt' value='pinned'>Pinned Location</option>");
    }
    $('select#end option#pinned-opt').prop('selected', true);

    var marker = new google.maps.Marker({
        position: latLng,
        map: map
    });

    var destLat = latLng.lat();
    var destLng = latLng.lng();
    document.getElementById("dest_lat").setAttribute("value", destLat);
    document.getElementById("dest_lng").setAttribute("value", destLng);

    markerSingleton = marker;
    map.panTo(latLng);
}


function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
    'Error: The Geolocation service failed. ' :
    'Error: Your browser doesn\'t support geolocation.');
}
