<?php

	/**************************************************************
	NAME: 	uaHeader.php
	NOTES: 	Header for pages user sees when not logged in (reset password, new player, login, etc)
	**************************************************************/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>
		<?php 
			if (isset($html['title'])) {
				echo $html['title'];
			} else {
				echo $title; 
			}
		?>
	</title>


	<link href='http://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="js/ui/css/theme/ui.min.css" />
	<link rel="stylesheet" type="text/css" href="js/chosen/chosen.css" />
	<link rel="stylesheet/less" type="text/css" href="theme/<?php echo THEME; ?>/main.less" />

	<?php 
		if (isset($styleLink)) {
			echo '<link rel="stylesheet/less" type="text/css" href="theme/' . THEME . '/' . $styleLink . '" />'; // Include custom stylesheet for this page, if there is one. 
		}
	?>

	<script type="text/javascript">
	    // Set LESS parameters
		less = {
	        env:"development",
	        dumpLineNumbers: "all", // or "mediaQuery" or "all"
			relativeUrls: true // whether to adjust url's to be relative
	                            // if false, url's are already relative to the
	                            // entry less file
	    };
	    // alert(less.relativeUrls);
		
	</script>

	<script type="text/javascript" src="js/less-1.3.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/ui/js/jquery-ui-1.10.3.min.js"></script>
	<script type="text/javascript" src="js/chosen/chosen.js"></script>
	<script type="text/javascript" src="js/library.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<?php 
		if (isset($scriptLink)) {
			echo '<script type="text/javascript" src="js/' . $scriptLink . '"></script>'; // Include custom JS for this page, if there is one. 
		}
	?>
</head>
