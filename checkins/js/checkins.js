$(document).ready(function() {
	$('#timestamp').text(checkins['time-stamp']);
	var coords = [];
	var markers = new Array();
	var icon = new L.Icon({
		iconUrl: '/images/signpost-icon.png',
		iconSize: [32, 37]
	});
	var tiles = new L.StamenTileLayer("watercolor");
	var options = {
		layers: tiles
	};
	var scale = d3.scale.linear();
	scale.domain([1, checkins['max-checkins']]);
	scale.range([10, 30]);
	var map = new L.Map("map", options);
	
	L.geoJson(checkins, {
		onEachFeature: function(feature, layer) {
			var popup = '<div class="checkin-popup">';
			popup += feature.properties.name;
			popup += '&nbsp;('
			if (feature.properties.checkins > 1) {
				popup += feature.properties.checkins + ' checkins)';
			}
			else {
				popup += '1 checkin)';
			}
			popup += '</div>';
			layer.bindPopup(popup);
			layer.on('click', function(e) {
				e.target.openPopup();
			});
		},
		pointToLayer: function(feature, latlng) {
			coords.push(latlng);
			return L.circleMarker(latlng, {
				radius: scale(feature.properties.checkins),
				color: '#FFF',
				fillColor: '#000',
				weight: 1,
				opacity: 1,
				fillOpacity: 0.8
			});
		}
	}).addTo(map);
	
	var bounds = new L.LatLngBounds(coords);
	map.fitBounds(bounds);
});
