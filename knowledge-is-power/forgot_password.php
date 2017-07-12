<?php
require './includes/config.inc.php';
require MYSQL;
$page_title = "Forgot Your Password";
include './includes/header.html';

$pass_errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$sql = 'SELECT id FROM users WHERE email = "'.escape_data($_POST['email'], $dbc) . '"';
		$result = mysqli_query($dbc, $sql);
		
		if (mysqli_num_rows($result) === 1) {
			list($uid) = mysqli_fetch_array($result, MYSQLI_NUM);
		} else {
			$pass_errors['email'] = 'The submitted email address does not match those on file!';
		}
	} else {
		$pass_errors['email'] = 'Please enter a valid email address!';
	}
	
	if (empty($pass_errors)) {
		$pwd = substr(md5(uniqid(rand(), true)), 10, 15);
		
		$sql = "UPDATE users SET pass='".password_hash($pwd, PASSWORD_BCRYPT)."' WHERE id=$uid LIMIT 1";
		$result = mysqli_query($dbc, $sql);
		
		if (mysqli_affected_rows($dbc) === 1) 	{
			$body = "Your password to log into VitorBrangioni.com.br has been temporarily changed to '$pwd'.
			Please log in using that password and this email address. Then you may change your password
			to something more familiar.";
			mail($_POST['email'], 'Your temporary password', $body, 'From: vitorh.brangioni@gmail.com');
			
			echo '<h1>Your passworc has been changed</h1>
					<p>You will receibe the new, temporary passsword via email. Once you have logged in new passowrd,
						you may change it by clicking on the "Change password" link.</p>';
			
			include './includes/footer.html';
			exit();
		} else {
			trigger_error('Your password could not be changed due to a system error.
							We apologize for any inconvenecience.');
			
		}
	}
}

require_once './includes/form_functions.inc.php';
?>

<h1>Reset Your Password</h1>
<p>Enter your email address below to reset your password.</p>

<form action="forgot_password.php" method="post" accept-charset="utf-8">
<?php 
create_form_input('email', 'email', 'Email Address', $pass_errors);
?>
<input type="submit" name="submit_button" value="Reset &rarr;" id="submit_button" class="btn btn-default">
</form>

<?php 
include './includes/footer.html';
?>


