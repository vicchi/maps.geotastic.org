# Where You've Been According To Foursquare

## Gentle Introduction

This is a simple set of PHP scripts to visualise your Foursquare checkin history. You'll need a source of all of your checkins; you can either get these via the Foursquare API or you can run your own instance of [privatesquare](https://github.com/straup/privatesquare) to keep an up-to-date copy of them. These scripts assume you're using privatesquare on the same web server that you'll be using to serve the map up.

## Installation

You'll need to make a copy of the config.php.sample file and name it `config.php`. You'll need to edit this file with specifics for your privatesquare database configuration.

	# Edit the database hostname, username, password and database name
	# to match your privatesquare instance.

	$config['hostname'] = 'localhost';
	$config['user'] = 'user';
	$config['password'] = 'password';
	$config['database'] = 'privatesquare';
	
	# Edit to match the user id your Foursquare account has in privatesquare. If you're the
	# sole user, you can probably leave this unchanged (see the users table)

	$config['userid'] = 1;
	
Now change to the `tools` directory and run the `extract-checkins.php` script.

	$ cd tools
	$ php ./extract-checkins.php
	
If all goes well and there's no problems accessing the database, this script will create a Javascript file, containing all your checkins in GeoJSON format as `js/checkins-geojson.js`. It's probably a good idea to check that this file exists and isn't empty.

The GeoJSON will contain all of your checkins from your privatesquare instance at the time that you run `extract-checkins.php`; you might want to add this to your `crontab`, if your web host permits this, to run regularly and create a (relatively) up-to-date set of checkin information.

Meanwhile, here's a [blog post](http://www.vicchi.org/2013/02/26/you-were-here-mapping-the-places-ive-been-to-according-to-foursquare/), which may or may not explain this.