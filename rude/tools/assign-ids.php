<?php

$file = '../js/rude.json';
$raw = file_get_contents($file);
$json = json_decode($raw);
if ($json !== NULL) {
	$id = 0;
	if (isset($json->last_id)) {
		$id = intval($json->last_id);
	}
	$features = $json->features;
	for ($i=0; $i<count($features); $i++) {
		$entry = $features[$i];
		$props = $entry->properties;
		if (!isset($props->id)) {
			$id++;
			$props->id = sprintf("%u", $id);
		}
	}
	$json->last_id = sprintf("%u", $id);
	$newjson = json_encode($json, JSON_PRETTY_PRINT);
	file_put_contents('rude.json.new', $newjson);
}
