if (ADDRESS.length) {
    var myCenter = new google.maps.LatLng(ADDRESS[0].lat_long.split(',')[0], ADDRESS[0].lat_long.split(',')[1]);

    function initialize() {
        var mapProp = {
            center: myCenter,
            scrollwheel: true,
            zoom: 18,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        $.each(ADDRESS, function (index, value) {

            const infowindow = new google.maps.InfoWindow({
                content: value.address,
            });

            var mk = new google.maps.Marker({
                position: new google.maps.LatLng(value.lat_long.split(',')[0], value.lat_long.split(',')[1]),
                map: map
            });

            mk.setMap(map);

            mk.addListener("click", () => {
                infowindow.open(map, mk);
            });
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
}
