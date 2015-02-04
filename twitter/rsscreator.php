<?php
	$file = fopen('rss.xml', 'w');
	$url = 'index.php';

	ob_start();
	require $url;
	$htmldata = ob_get_contents();
	ob_end_clean();

	
	fwrite($file, $htmldata);
	fclose($file);
	echo "Tutorial Index Updated!"; 
?>