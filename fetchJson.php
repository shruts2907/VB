<?php
	//echo "fet5ch sjon ";
	$json = file_get_contents('http://graph.facebook.com/vanderbilt/photos/uploaded');
	$obj = json_decode($json);
?>