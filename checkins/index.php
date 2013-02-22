<html>
<head>
	<title>Maps | Foursquare Checkins</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
	<!--[if lte IE 8]>
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css" />
	<![endif]-->
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<script src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
	<script type="text/javascript" src="http://maps.stamen.com/js/tile.stamen.js?v1.2.1"></script>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://d3js.org/d3.v3.min.js"></script>
	<script type="text/javascript" src="js/checkins-geojson.js"></script>
	<script type="text/javascript" src="js/checkins.js"></script>
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-10486552-10']);
	_gaq.push(['_setDomainName', 'geotastic.org']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
</head>
<body>
	<a href="https://github.com/vicchi/maps.geotastic.org"><img style="position: absolute; top: 0; right: 0; border: 0; z-index:100;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png" alt="Fork me on GitHub"></a>
	<div id="header"><a href="/checkins/index.php">You Were Here; Places I've Been To According To Foursquare</a></div>
	<div id="map"></div>
	<div id="footer">
		<div id="credits">
			Last updated on <span id="timestamp"></span>. This is a code thing by <a href="http://www.garygale.com/" target="_blank">Gary Gale</a>, made out of PHP, HTML, CSS, jQuery and D3.
		</div>
		<div id="attribution">
			<a href="http://maps.stamen.com/" target="_blank">Map tiles</a> by <a href="http://stamen.com/" target="_blank">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0" target="_blank">CC BY 3.0</a>. &copy; <a href="http://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors.
		</div>
	</div>
</body>



