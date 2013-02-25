<?php

require_once('./config.php');

$CHECKIN_SQL = sprintf('SELECT id, venue_id FROM PrivatesquareCheckins WHERE user_id = %u;', $config['userid']);
$VENUE_SQL = "SELECT data FROM FoursquareVenues WHERE venue_id = '%s';";

$venues = array ();
$geo = array ();
$features = array ();

$sqli = new mysqli(
	$config['hostname'],
	$config['user'],
	$config['password'],
	$config['database']);
if ($sqli->connect_errno) {
	echo 'Failed to connect to MySQL: (' . $sqli->connect_errno . ') ' . $sqli->connect_error;
	exit;
}

$res = $sqli->query($CHECKIN_SQL);
if (FALSE === $res) {
	echo 'Query failed' . PHP_EOL;
	if ($sqli->errno) {
		echo 'Failed on query ' . $sql . ': (' . $sqli->errno . ') ' . $sqli->error . PHP_EOL;
		exit;
	}
}

while ($row = $res->fetch_assoc()) {
	$venue_id = $row['venue_id'];
	if (!empty($venue_id)) {
		if (array_key_exists($venue_id, $venues)) {
			$venues[$venue_id]++;
		}
		else {
			$venues[$venue_id] = 1;
		}
	}
}

$res->free();

$geo['type'] = 'FeatureCollection';
$maxvcount = 0;

foreach ($venues as $vid => $vcount) {
	if ($vcount > $maxvcount) {
		$maxvcount = $vcount;
	}
	
	$sql = sprintf($VENUE_SQL, $vid);
	$res = $sqli->query($sql);
	if ($sqli->errno) {
		echo 'Failed on query ' . $sql . ': (' . $sqli->errno . ') ' . $sqli->error;
		exit;
	}

	$row = $res->fetch_assoc();
	$json = json_decode($row['data']);
	if (NULL === $json) {
		echo 'Failed to decode JSON (' . json_last_error() . ')';
		switch (json_last_error()) {
	        case JSON_ERROR_NONE:
	            echo ' - No errors';
	        	break;
	        case JSON_ERROR_DEPTH:
	            echo ' - Maximum stack depth exceeded';
	        	break;
	        case JSON_ERROR_STATE_MISMATCH:
	            echo ' - Underflow or the modes mismatch';
	        	break;
	        case JSON_ERROR_CTRL_CHAR:
	            echo ' - Unexpected control character found';
	        	break;
	        case JSON_ERROR_SYNTAX:
	            echo ' - Syntax error, malformed JSON';
	        	break;
	        case JSON_ERROR_UTF8:
	            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
	        	break;
	        default:
	            echo ' - Unknown error';
	        	break;
	    }
		echo PHP_EOL;
		exit;
	}
	$skip = false;
	if (count($json->categories) == 0) {
		$skip = true;
	}
	else {
		$pos = strpos($json->categories[0]->name, '(private)');
		if ($pos !== false) {
			$skip = true;
		}
	}

	if (!$skip) {
		$geometry = array(
			'type' => 'Point',
			'coordinates' => array($json->location->lng, $json->location->lat)
		);

		$properties = array(
			'name' => $json->name,
			'venue' => $vid,
			'checkins' => $vcount
		);

		$feature = array(
			'type' => 'Feature',
			'geometry' => $geometry,
			'properties' => $properties
		);

		$features[] = $feature;
	}

	$res->free();
}

$sqli->close();

$geo['max-checkins'] = $maxvcount;
$geo['features'] = $features;
$geo['time-stamp'] = date(DATE_COOKIE, time());

$pos = strpos(phpversion(), '5.4');
if (0 === $pos) {
	$geojson = json_encode($geo, JSON_PRETTY_PRINT);
}
else {
	$geojson = json_encode($geo);
}

$contents = array();
$contents[] = 'var checkins = ';
$contents[] = $geojson;
$contents[] = ';';
file_put_contents('../js/checkins-geojson.js', implode('', $contents));

?>