<?php
$path = "/var/www/domains/common/mobile-detect";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once('Mobile_Detect.php');
?>
<html>
<head>
	<title>Maps | Vaguely Rude</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<script src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
	<script type="text/javascript" src="http://maps.stamen.com/js/tile.stamen.js?v1.2.1"></script>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/rude-geojson.js"></script>
	<script type="text/javascript" src="js/rude.js"></script>

<?php
	$detect = new Mobile_Detect();
	$protocol = 'http';
	$id = NULL;
	if (isset($_SERVER['HTTPS'])) {
		$protocol = filter_var($_SERVER['HTTPS'], FILTER_SANITIZE_URL);
	}
	$server = $protocol . '://' . filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL);
	$port = filter_var($_SERVER['SERVER_PORT'], FILTER_SANITIZE_NUMBER_INT);
	if ($port != 80) {
		$server .= ':' . $port;
	}
	$server .= filter_var($_SERVER['SCRIPT_NAME'], FILTER_SANITIZE_URL);
	if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
		$id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
	}

	$script = array();
	$script[] = '<script type="text/javascript">';
	$script[] = 'var RudePlacesMap = {';
	$script[] = '	server_name: ' . "'" . $server . "',";

	if (isset($id) && !empty($id)) {
		$script[] = '	place_id: ' . "'" . $id . "',";
	}

	if ($detect->isMobile() || $detect->isTablet() || $detect->isiOS()) {
		$script[] = '	mobile: true'; 
	}

	else {
		$script[] = '	mobile: false';
	}

	$script[] = '}';
	$script[] = '</script>';
	
	echo implode(PHP_EOL, $script);
?>

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
<?php
if (!$detect->isMobile() && !$detect->isTablet() && !$detect->isiOS()) {
?>
	<a href="https://github.com/vicchi/maps.geotastic.org"><img style="position: absolute; top: 0; right: 0; border: 0; z-index:100;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png" alt="Fork me on GitHub"></a>
<?php
}
?>
	<div id="header"><a href="/rude/index.php">Vaguely Rude Place Names Of The World</a></div>
	<div id="map"></div>
	<div id="footer">
		<div id="credits">
			This is a code thing by <a href="http://www.garygale.com/" target="_blank">Gary Gale</a>, made out of PHP, HTML, CSS and jQuery. <a href="/images/signpost-icon.png" target="_blank">Signpost icon</a> <a href="http://creativecommons.org/licenses/by-sa/3.0" target="_blank">CC BY SA 3.0</a>; based on an original by <a href="http://mapicons.nicolasmollet.com/" target="_blank">Nicolas Mollet</a>.
		</div>
		<div id="attribution">
			<a href="http://maps.stamen.com/" target="_blank">Map tiles</a> by <a href="http://stamen.com/" target="_blank">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0" target="_blank">CC BY 3.0</a>. &copy; <a href="http://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors.
		</div>
	</div>
</body>



