<?php

/* JPEGCam Test Script */
/* Receives JPEG webcam submission and saves to local file. */
/* Make sure your directory has permission to write files as your web server user! */
//$rdir = getcwd();
$cdir = $_SERVER['DOCUMENT_ROOT']."/im/thumbs_sample/";

$filename = $_GET['fn']. '.jpg';
$result = file_put_contents($cdir.$filename, file_get_contents('php://input') );
if (!$result) {
	print "ERROR: Failed to write data to $filename, check permissions\n";
	exit();
}

$url = 'http://' . $_SERVER['HTTP_HOST'] .'/im/thumbs_sample/' . $filename;
print "$url\n";

?>
