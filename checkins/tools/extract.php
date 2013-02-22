<?php

require_once('./config.php');

$sqli = new mysqli($config['hostname'], $config['user'], $config['password'], $config['database']);
if ($sqli->connect_errno) {
	echo 'Failed to connect to MySQL: (' . $sqli->connect_errno . ') ' . $sqli->connect_error;
	exit;
}

$sql = 'SELECT id, venue_id FROM PrivatesquareCheckins WHERE user_id = 1';

$res = $sqli->query($sql);
if ($sqli->connect_errno) {
	echo 'Failed on query ' . $sql . ': (' . $sqli->connect_errno . ') ' . $sqli->connect_error;
	exit;
}

$venues = array ();

while ($row = $res->fetch_assoc()) {
	if (array_key_exists($row['venue_id'], $venues)) {
		$venues[$row['venue_id']]++;
	}
	else {
		$venues[$row['venue_id']] = 1;
	}
}

$res->free();

$geo = array();
$geo['type'] = 'FeatureCollection';
$features = array ();
$maxvcount = 0;

foreach ($venues as $vid => $vcount) {
	if ($vcount > $maxvcount) {
		$maxvcount = $vcount;
	}
	$sql = 'SELECT data FROM FoursquareVenues WHERE venue_id = \'' . $vid . '\';';
	$res = $sqli->query($sql);
	if ($sqli->connect_errno) {
		echo 'Failed on query ' . $sql . ': (' . $sqli->connect_errno . ') ' . $sqli->connect_error;
		exit;
	}
	$row = $res->fetch_assoc();
	$json = json_decode($row['data']);

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
$geojson = json_encode($geo, JSON_PRETTY_PRINT);

$contents = array();
$contents[] = 'var checkins = ';
$contents[] = $geojson;
$contents[] = ';';
file_put_contents('../js/checkins-geojson.js', implode('', $contents));

?>