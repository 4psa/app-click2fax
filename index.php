<!--
 * 4PSA VoipNow App Click2Fax
 *  
 * This is the index file of the Click2Fax application. It will
 * read the configuration file variables and display a button that,
 * when clicked, will open a pop-up window with the actual form
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
-->

<?php
require_once('config/config.php');
require_once('language/en.php');
@date_default_timezone_set(@date('e'));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>
			<?php echo $msgArr['app_title']; ?>
		</title>
		<link rel="stylesheet" type="text/css" href="skin/main.css" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.bpopup.min.js"></script>
		<script type="text/javascript">
			$().ready(function() {
				$('#click2fax').click(function() {
					$('#popup').bPopup({
						content:'iframe',
						loadUrl:'sendFax.php',
						amsl: 0,
						modalClose: false
					});
					
					var ifr = $('iframe')[0];
					ifr.style.height = "260px";
				});
			});
		</script>
	</head>
	<body>
		<div class="button">
			<button id="click2fax" type="button" title="Click2Fax"><?php echo $msgArr['btn_click2fax']; ?></button>
		</div>
		<div id="popup"></div>
	</body>
</html>