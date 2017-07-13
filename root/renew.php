<?php

require 'includes/config.inc.php';
redirect_invalid_user();
$page_title = 'Renew Your Account';
require MYSQL;
include 'includes/header.html';
?>

<h1>Thanks!</h1>
<p>
	Thanks you for your interest in renewing your account! To complete the process,
	please now click the button so that you may pay for your renewal via PayPal.
	The cost is R$50,00 (BRA) per year.
	<strong>
		Note: After renewing your membership at PayPal,
		you must logout and log back in at this site in order process the renewal
	</strong>
</p>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="custom" value="<?php echo $_SESSION['user_id']; ?>">
	<input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
	<input type="hidden" name="first_name" value="<?php echo $_SESSION['first_name']; ?>">
	<input type="hidden" name="last_name" value="<?php echo $_SESSION['last_name']; ?>">
	<input type="hidden" name="hosted_button_id" value="VQY2CUSF5ZCPC">
	<input type="submit" name="submit" value="Renew &rarr;" id="submit" class="btn btn-success btn-block">
</form>

<?php 
include 'includes/footer.html';
?>