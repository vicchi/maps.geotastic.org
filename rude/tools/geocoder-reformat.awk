NR < 1 { next }
BEGIN {
	FS="|";
}
{
	print "{";
	print "\"type\": \"Feature\",";
	print "\"geometry\": {";
	print "\"type\": \"Point\",";
	printf "\"coordinates\": [%s, %s]\n", $4, $3;
	print "},";
	print "\"properties\": {";
	printf "\"label\": \"%s\",\n", $2;
	printf "\"detail\": \"%s\"\n", $2;
	print "}";
	print "},";
}