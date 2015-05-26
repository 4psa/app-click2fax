<?php
/**
 * 4PSA VoipNow App: Click2Fax
 *  
 * This page contains the form that will send the fax
 * This page is loaded in an iframe
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/

/* Require file containing the configuration parameters */
require_once('config/config.php');
require_once('language/en.php');

/* init session, we will keep tokens in the session */
session_start();

@date_default_timezone_set(@date('e'));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
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
				
				/*
				 * Whenever the user presses the Add attachment button, another attachment input field
				 * will appear. 
				 */
				$('#add_attachment').click(function() {

					var new_attachment_input = '<tr class="attachments">' +
										'<td class="form_label">&nbsp;</td>' +
										'<td class="form_field">' +
											'<input type="file" name="attachments[]" class="form_field" />' +
										'</td>' +
									'</tr>';
					$('.attachments:last').after(new_attachment_input);

					var ifr = $('iframe', parent.document)[0];
					ifr.style.height = parseInt(ifr.style.height)+30+"px";
				});

				/*
				 * Whenever the user presses the Remove attachment button, the last attachment input field
				 * is removed
				 */
				$('#remove_attachment').click(function() {
					var attachment_to_be_removed = $('.attachments').length;
					if(attachment_to_be_removed > 1) {
						$('.attachments:last').remove();
					}

					var ifr = $('iframe', parent.document)[0];
					if (parseInt(ifr.style.height) != 260) {
						ifr.style.height = parseInt(ifr.style.height)-30+"px";
					}
				});
			
				/*
				 * When the user presses the cancel or close buttons, the form window closes
				 */
				$('#cancel_button, #close_button').click(function() {
					var p = parent;
					p.$('#popup').bPopup().close();
				});
			});
		</script>
	</head>
	<body>
		<!-- the close button -->
		<img id="close_button" alt="close" width="17" height="17" src="skin/images/close.png" />
		<?php
		
		if (!empty($_POST['submit'])) {
			/* Analyze the form data submitted */
			$errMsg = null;
			$infoMsg = null;
			if (empty($_POST['to'])) {
				$errMsg = $msgArr['err_to_invalid'];
			} 
			
			if (empty($errMsg)) {
				require_once('plib/cURLRequest.php');
                require_once('plib/lib.php');

                $infoMsg = sendFaxRequest();

			}				
		} 
		/* Display the form */
		?>
		
		<div class="header">
			<?php echo $msgArr['app_title']; ?>
		</div>
		<?php if (empty($infoMsg)) { ?>
			<?php if (!empty($errMsg)) { ?>
				<div class="warning">
					<?php echo $errMsg; ?>
				</div>
			<?php } ?>
			<form enctype="multipart/form-data" action="sendFax.php" method="post" id="send_fax_form">
				<div class="help_area">
					<?php echo $msgArr['help_msg']; ?>
				</div>
				
				<table id="form_table">
					<tbody>
						<tr>
							<td class="form_label"><?php echo $msgArr['label_to']; ?> </td>
							<td class="form_input"><input type="text" name=to class="form_field" /></td>
						</tr>
						<tr class="attachments">
							<td class="form_label"><?php echo $msgArr['label_attach']; ?> </td>
							<td class="form_input">
								<input type="hidden" name="MAX_FILE_SIZE" value="25000000">
								<input type="file" name="attachments[]" class="form_field" />
								<img id="add_attachment" alt="add" src="skin/images/add.png" title="add" />
								<img id="remove_attachment" alt="remove" src="skin/images/delete.png" title="remove" />
							</td>
						</tr>
					</tbody>
				</table>
				<table id="form_table">
					<tr>
						<td class="left_btn">	
							<div class="submit">
								<input type="submit" name="submit" value="<?php echo $msgArr['btn_send_fax']; ?>" />
							</div>
						</td>
						<td class="right_btn">
							<div class="button">
								<button type="button" id="cancel_button" name="cancel" value="<?php echo $msgArr['btn_cancel']; ?>"><?php echo $msgArr['btn_cancel']; ?></button>
							</div>
						</td>
					</tr>
				</table>
			</form>
		<?php } else { ?>
			<div class='info'>
				<?php echo $infoMsg; ?>
			</div>
		<?php } ?>
	</body>
</html>