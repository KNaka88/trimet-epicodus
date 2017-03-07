
// function initMap() {
//   map = new google.maps.Map(document.getElementById('map'), {
//     center: {lat: 45.519125, lng: -122.678982},
//     zoom: 8
//   });
// }



function initMap() {
       var map = new google.maps.Map(document.getElementById('map'), {
         //Epicodus Location
         center: {lat:45.5206223, lng:-122.6795871 },
         zoom: 17
       });
       var infoWindow = new google.maps.InfoWindow({map: map});
       // Try HTML5 geolocation.
       if (navigator.geolocation) {
         navigator.geolocation.getCurrentPosition(function(position) {
           var ctaLayer = new google.maps.KmlLayer({
             url: 'https://developer.trimet.org/gis/data/tm_rail_lines.kml',
             map: map
           });
           var pos = {
             lat: position.coords.latitude,
             lng: position.coords.longitude
           };
           console.log(pos);

           var marker = new google.maps.Marker({
              position: pos,
              map: map,
              title: "Hi!"
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
     function handleLocationError(browserHasGeolocation, infoWindow, pos) {
       infoWindow.setPosition(pos);
       infoWindow.setContent(browserHasGeolocation ?
          'Error: The Geolocation service failed. ' :
          'Error: Your browser doesn\'t support geolocation.');
     }
