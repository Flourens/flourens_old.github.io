var myCenter = new google.maps.LatLng(45.6131016,-122.5048753);

function initialize() {
    var mapProp = {
        center: myCenter,
        scrollwheel: false,
        zoom:15,
        zoomControl: false,
        mapTypeControl: false,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        
			styles: [{ 
				"featureType": "landscape", 
				"stylers": [{ "color": "#fff" }, { "visibility": "on" }] },
							 
				{ "featureType": "poi", "stylers": [{ "saturation": -50 }, { "lightness": 21 }, { "visibility": "simplified" }] },
			
				{ "featureType": "road.highway", "stylers": [{ "color": "#f0ede5" }, { "visibility": "on" }] },
							 
				{ "featureType": "road.arterial", "stylers": [{ "color": "#f0ede5" }, { "visibility": "on" }] },
				
				{ "featureType": "road.local", "stylers": [{ "color": "#14adf4" }, { "visibility": "off" }] },
				
				{ "featureType": "transit", "stylers": [{ "saturation": 0 }, { "visibility": "simplified" }] },
						
				{ "featureType": "administrative.province", "stylers": [{ "visibility": "off" }] },
							
//				{ "featureType": "water", "elementType": "labels", "stylers": [{ "visibility": "on" }, { "color": "#ed734e" }] },
//							 
//				{ "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#fff" }, { "visibility": "on" }] }
			]
		};

    var map = new google.maps.Map(document.getElementById("gmap"), mapProp);

    var marker = new google.maps.Marker({
        position: {lat: 45.6131016, lng: -122.5048753},
				map: map,
				title: 'Sushi',
				icon: {
					url: "assets/img/marker.png",
					scaledSize: new google.maps.Size(66, 66)
				}
    });
	
		var marker2 = new google.maps.Marker({
        position: {lat: 45.6100099, lng: -122.5059908},
				map: map,
				title: 'Sushi',
				icon: {
					url: "assets/img/marker2.png",
					scaledSize: new google.maps.Size(66, 66)
				}
    });

    marker.setMap(map);
    marker2.setMap(map);


}

google.maps.event.addDomListener(window, 'load', initialize);