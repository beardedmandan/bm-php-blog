<div id="xkcd">
	<?php
		$link = 'http://xkcd.com/';
		$img = '<img src="images/xkcd.png" />';
		$file = 'http://xkcd.com/rss.xml';	
		$file_headers = @get_headers($file);

		if($file_headers[0] != 'HTTP/1.0 404 Not Found') {
			$xkcdFeed = simplexml_load_file($file);
			$link = $xkcdFeed->channel->item[0]->link;
			$img = $xkcdFeed->channel->item[0]->description;
		}

		echo '<a href="' . htmlentities($link) . '">';
		echo $img;
		echo '</a>';
	?>
</div>