<?php
	if (isset($_POST['text'])) {$text = $_POST['text'];}
?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- ========================= HEADER ========================== -->
<!-- ============ SITE CREATED BY DAN HARRIS - 2015 ============ -->
<head>
<title>
	ADMIN - BEARDEDMAN.info
</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="bm_full.css" type="text/css" />
</head>

<!-- ========================== BODY ========================== -->
<body>
	<!-- NAVIGATION BAR -->
	<?php
		include_once 'navtop_full.inc.php';	
	?> 

	<!-- MAIN CONTENT -->
	<div id="layout-main">
	<div class="content-main-article">
	<?php
		include_once '\admin\wymeditor.html';
	?>
	<hr/>
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