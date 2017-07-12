<?php

require 'includes/config.inc.php';
// redirect_invalid_user('reg_user_id');
require MYSQL;
$page_title = 'Thanks!';
include 'includes/header.html';

/* if (filter_var($_SESSION['reg_user_id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
	$query = "UPDATE users SET date_expires = ADDDATE(date_expires, INTERVAL 1 YEAR)
				WHERE  id={$_SESSION['reg_user_id']}";
	$result = mysqli_query($dbc, $query);
} */
// excluindo session 
// unset($_SESSION['reg_user_id']);
?>

<h1>Thank You</h1>
<p> Thank you for payment! You may now access all to the site's content for the next year!
	<strong>
		Note: Your access to the site will automatically be renewed via PayPal each year.
		To disable this feature, or to cancel your account, see the "My preapproved purchases"
		section of your PalPal profile page.
	</strong>
</p>

<?php
include 'includes/footer.html';
?>

