$(document).ready(function() {
	var coords = [];
	var markers = new Array();
	var icon = new L.Icon({
		iconUrl: '/images/signpost-icon.png',
		iconSize: [32, 37]
	});
	var tiles = new L.StamenTileLayer("toner");
	var options = {
		layers: tiles
	};

	if (RudePlacesMap.hasOwnProperty('mobile') && RudePlacesMap.mobile) {
		options.zoomControl = false;
	}

	var map = new L.Map("map", options);
	
	L.geoJson(places, {
		onEachFeature: function(feature, layer) {
			if (feature.properties && feature.properties.label) {
				var permalink = RudePlacesMap.server_name + '?id=' + feature.properties.id;
				var popup = '<div class="rude-place-popup">';
				popup += '<p>' + feature.properties.label + '</p>';
				popup += '<p><a href="' + permalink + '">Permalink to this place ...</a></p>';
				popup += '</div>';
				layer.bindPopup(popup);
				layer.on('mouseover', function(e) {
					e.target.openPopup();
				});
			}
		},
		pointToLayer: function(feature, latlng) {
			var marker = new L.Marker(latlng, { icon: icon });
			coords.push(latlng);
			markers[feature.properties.id] = marker;
			return marker;
		}
	}).addTo(map);
		
	if (RudePlacesMap.hasOwnProperty('place_id')) {
		if (markers.hasOwnProperty(RudePlacesMap.place_id)) {
			map.setView(markers[RudePlacesMap.place_id].getLatLng(), 7);
			markers[RudePlacesMap.place_id].openPopup();
		}
		
		else {
			var bounds = new L.LatLngBounds(coords);
			map.fitBounds(bounds);
		}
	}

	else {
		var bounds = new L.LatLngBounds(coords);
		map.fitBounds(bounds);
		if (RudePlacesMap.hasOwnProperty('mobile') && RudePlacesMap.mobile) {
			map.zoomIn();
		}
	}
});
