<?php

require 'includes/config.inc.php';
require MYSQL;
$page_title = 'Oops';
include 'includes/header.html';
?>

<h1>Oops!</h1>
<p>
	The payment through PayPal was not completed. You now have a vaid membership at this site,
	but you will not be able to view any content until you complete the PayPal transaction.
	You can do so by clicking on the Renew link after logging in.
</p>

<?php
include 'includes/footer.html';
?>