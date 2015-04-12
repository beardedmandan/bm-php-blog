<?php
//declare and initiate all common page variables
	include_once 'common.inc.php';
	$content = 'all';
	$contentLink = '';
	$page = 1;
	$numArticles = -1;
	$numProjects = 0;
	$self = $_SERVER['PHP_SELF'];
	if(isset($_GET['page'])) {$page = $_GET['page'];}
	$self .= '?page=' . $page;
	if(isset($_GET['content'])) {
		$content = $_GET['content'];
		$self .= '&content=' . $content;
	}
?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- ========================= HEADER ========================== -->
<!-- ============ SITE CREATED BY DAN HARRIS - 2015 ============ -->
<head>
<title>
	BLOG - BEARDEDMAN.info
</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Dan Harris - Personal Blog and Project Repository">
<meta name="keywords" content="beardedman, blog, personal project, dan harris">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.8, maximum-scale=3.0, user-scalable=yes" />
<link rel="icon" href="favicon.ico" />
<?php
	//detect if displaying page on mobile device, style appropriately
	include_once 'detectmobile.inc.php';
?>
</head>


<!-- ========================== BODY ========================== -->
<body>
	
	<!-- NAVIGATION BAR -->
	<?php
		//use mobile or desktop navbar depending on viewing device
		if ($siteType == 'mobile') {include_once 'navtop_mobile.inc.php';}
		else {include_once 'navtop_full.inc.php';}	
	?>

	<!-- MAIN CONTENT -->
	<div id="layout-main">
	<div id="content-main-sitetype">
		display site as:  
		<?php
			//Shows link to mobile/desktop version of site, depending on current view version
			if ($siteType == 'mobile') {echo '<a href="' . $self . '&site=full">desktop</a> / mobile';}
			else {echo 'desktop / <a href="' . $self . '&site=mobile">mobile</a>';}
		?>
	</div>
		<?php
			$handle = opendir($articleDir);
			$items = array();
			$fileList = array();
			$displayArticles = 0;

			 
			while (($file = readdir($handle)) !== FALSE) {
				if (is_dir($articleDir . $file)) continue;  
				if (!preg_match("/^.*\.xml$/", $file)) continue;
				$fileList[] = $file;
				$numArticles += 1;	
			}

			sort($fileList);
			$fileList = array_reverse($fileList); //show most recent files
			$typePattern = '/' . (string)$content . '/';
			if($content == 'all' || $content == ''){
				$typePattern = '/\\w/';
			}

			// Reads files {article, news, brews} and enters their details into $items[] array for display.
			// Only records a maximum of $articlesPerPage items in $items[] to display
			foreach ($fileList as $file) {
				$xmlItem = simplexml_load_file($articleDir . $file);
				if ((string)$xmlItem->status == 'live' && (preg_match($typePattern, (string)$xmlItem->type))) {
					if((string)$xmlItem->type == 'project') {$numProjects += 1;}
					$displayArticles += 1;
					if(($displayArticles > (($page - 1) * $articlesPerPage) || $page == 0) && $displayArticles <= ($page * $articlesPerPage)){
						$item = array();
						$item['id'] = (string)$xmlItem['id'];
						$item['headline'] = strtoupper((string)$xmlItem->headline);
						$item['type'] = strtoupper((string)$xmlItem->type);
						$item['image'] = (string)$xmlItem->image;
						$item['body'] = (string)$xmlItem->body;
						$item['pubdatelong'] = strtoupper((string)$xmlItem->pubdatelong);
						$items[] = $item;
					}
				}
			}

			// used for page content navigation if seeking to display projects only
			if($content == 'project') {
				$numArticles = $numProjects;
				$contentLink = '&content=project';
			}

			// prints articles to html page from details in $items[]		
			if (count($items) > 0) {		
				foreach ($items as $item) {
					echo '<div class="content-main-article">';
					echo '<h2><a href="article.php?id=' . $item['id'] . '">';
					echo htmlentities($item['headline']) . '</a></h2>';
					echo '<h3>' . htmlentities($item['pubdatelong']) . ' (' . htmlentities($item['type']) . ')</h3>'; // prints publish date (long version)
					if(strlen($item['image']) > 0){
						echo '<div class="content-main-article-image-preview"><a href="article.php?id=' . $item['id'] . '"><img src="' . htmlentities($item['image']) . '"/></a></div>';
					}
					if(strlen($item['body']) > 1200){
						echo substr($item['body'],0,1200) . '....</p>'; // prints article preview (up to a maximum of 1200 chars)
					} else {
						echo $item['body'];
					}
					echo '<br/><a href="article.php?id=' . $item['id'] . '">Read More</a>';
					echo '</div>';
					echo '<hr/>';
					
				}
			} else {
				//if no article is set to load, redirects to index page
				echo "<script language=\"javascript\" type=\"text/javascript\">
     				<!--
     					window.setTimeout('window.location=\"./index.php\";',100);
     				-->
 				</script>";	
			}
		?>
	<div id="layout-main-page-select">
		<div id="layout-main-page-link">
			<div class="page-link">
				<?php
					if($page > 1){
						echo '<a href="index.php?' . $contentLink . '" title="First Page">&lt; FIRST</a>';
					} else {
						echo '<h3>&lt; FIRST</h3>';
					}
				?>
			</div>
			<div class="page-link">
				<?php
					if($page > 1){
						$prevPage = $page - 1;
						echo '<a href="index.php?page=' . $prevPage . $contentLink . '" title="Previous Page">&lt; PREV</a>';
					} else {
						echo '<h3>&lt; PREV</h3>';
					}
				?>
			</div>
			<div class="page-link">
				<?php
					if(($page * $articlesPerPage) < $numArticles){
						$nextPage = $page + 1;
						echo '<a href="index.php?page=' . $nextPage . $contentLink .'" title="Next Page">NEXT &gt;</a>';
					} else {
						echo '<h3>NEXT &gt;</h3>';
					}
				?>
			</div>
			<div class="page-link">
				<?php
					if(($page * $articlesPerPage) < $numArticles){
						$lastPage = (int)($numArticles / $articlesPerPage) + 1;
						echo '<a href="index.php?page=' . $lastPage . $contentLink .'" title="Last Page">LAST &gt;</a>';
					} else {
						echo '<h3>LAST &gt;</h3>';
					}
				?>
			</div>
		</div>

	</div>

	</div>

<!-- ========================= FOOTER ========================= -->
	<br/>
	<br/>
	<?php
		include 'footer.inc.php';
	?>
</body>

</html>