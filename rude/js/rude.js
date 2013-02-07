$(document).ready(function() {
	var coords = [];
	var icon = new L.Icon({
		iconUrl: '/images/signpost-icon.png',
		iconSize: [32, 37]
	});
	var tiles = new L.StamenTileLayer("toner");
	var map = new L.Map("map", {
		layers: tiles
	});
	
	L.geoJson(places, {
		onEachFeature: function(feature, layer) {
			if (feature.properties && feature.properties.label) {
				layer.bindPopup(feature.properties.label);
				layer.on('mouseover', function(e) {
					e.target.openPopup();
				});
			}
		},
		pointToLayer: function(feature, latlng) {
			coords.push(latlng);
			return new L.Marker(latlng, {icon: icon});
		}
	}).addTo(map);
		
	var bounds = new L.LatLngBounds(coords);
	map.fitBounds(bounds);
});
