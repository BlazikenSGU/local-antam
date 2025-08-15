//map.js

//Set up some of our variables.
var map; //Will contain map object.
var marker = false; ////Has the user plotted their location marker?

//Function called to initialize / create the map.
//This is called when the page has loaded.
function initMap() {

    //The center location of our map.
    var lat = $('input[name=location_lat]').length > 0 ? $('input[name=location_lat]').val() : '';
    var long = $('input[name=location_long]').length > 0 ? $('input[name=location_long]').val() : '';

    lat = lat !== null && lat !== '' ? lat : 10.790311;
    long = long !== null && long !== '' ? long : 106.697449;

    var centerOfMap = new google.maps.LatLng(lat, long);

    //Map options.
    var options = {
        center: centerOfMap, //Set center.
        zoom: 16 //The zoom value.
    };

    //Create the map object.
    map = new google.maps.Map(document.getElementById('gmaps-simple'), options);

    //Create the marker.
    marker = new google.maps.Marker({
        position: centerOfMap,
        map: map,
        draggable: true //make it draggable
    });

    //Listen for drag events!
    google.maps.event.addListener(marker, 'dragend', function (event) {
        markerLocation();
    });

    //Listen for any clicks on the map.
    google.maps.event.addListener(map, 'click', function (event) {
        //Get the location that the user clicked.
        var clickedLocation = event.latLng;
        //Marker has already been added, so just change its location.
        marker.setPosition(clickedLocation);
        //Get the marker's location.
        markerLocation();
    });
}

//This function will get the marker's current location and then add the lat/long
//values to our textfields so that we can save the location.
function markerLocation() {
    //Get location.
    var currentLocation = marker.getPosition();
    //Add lat and lng values to a field that we can save.
    document.getElementById('lat').value = currentLocation.lat(); //latitude
    document.getElementById('lng').value = currentLocation.lng(); //longitude
}

function moveMarker(lat, lng) {
    marker.setPosition(new google.maps.LatLng(lat, lng));
    map.panTo(new google.maps.LatLng(lat, lng));

};
//Load the map when the page has finished loading.
google.maps.event.addDomListener(window, 'load', initMap);