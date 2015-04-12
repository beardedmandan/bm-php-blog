<?php
	include_once 'common.inc.php';
	if (!isset($_GET['id'])) {header('Location: index.php');}
	$handle = opendir($articleDir);
	$articleXML;
	$found = FALSE;
	while (($file = readdir($handle)) !== FALSE && $found == FALSE) {
		if (is_dir($articleDir . $file)) continue; 
		if (($articleId = preg_replace('/^(\d)*(_)?/', '', $file)) == $_GET['id'].'.xml'){
			$articleXML = simplexml_load_file($articleDir . $file);
			$found = TRUE;
		}
	}
	if ($found == FALSE) {header('Location: index.php');}
	$self = $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'] . '&';
?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- ========================= HEADER ========================== -->
<!-- ============ SITE CREATED BY DAN HARRIS - 2015 ============ -->
<head>
<title>
	<?php
	if (count($articleXML->xpath('headline'))) {echo htmlentities($articleXML->headline) . ' - BEARDEDMAN.info';}
	else echo 'ARTICLES - BEARDEDMAN.info'
	?>
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
			if ($siteType == 'mobile') {echo '<a href="' . $self . 'site=full">desktop</a> / mobile';}
			else {echo 'desktop / <a href="' . $self . 'site=mobile">mobile</a>';}
		?>
	</div>
	<div class="content-main-article">
		<?php
			// prints article to html page
			echo '<h2>' . strtoupper(htmlentities($articleXML->headline)) . '</h2>';
			echo '<h3>' . strtoupper(htmlentities($articleXML->pubdatelong)) . ' (' . strtoupper(htmlentities($articleXML->type)) . ')</h3>';
			if ((string)$articleXML->script !== '') {
				echo '<div class="content-main-script">';
				include_once $projectDir . (string)$articleXML->script;
				echo '</div>';
			} else if ((string)$articleXML->image !== '') {
				echo '<div class="content-main-article-image"><img src="' . htmlentities($articleXML->image) . '"/></div>';
			}  
			echo $articleXML->body;
		?>
		<br/>
		<hr/>
	</div>
	<div id="layout-main-page-select">
		<div id="layout-main-page-link">
			<div class="page-link">
				<a href="index.php" title="Return to Blog">< RETURN TO BLOG</a>
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