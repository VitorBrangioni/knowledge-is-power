<?php
require './includes/config.inc.php';
require MYSQL;
$page_title = "Register";
include './includes/header.html';
?>

<h1>Register</h1>

<p>Access to the site's content is available to registered users
    at a cost of $10.00(US) per year. Use the form below to begin
    the registration process. <strong>Note: All fields are required.</strong>
    After completing this form, you'll be repesented with the oportunity
    to securely pay yearly subscription via <a href="http://www.paypal.com">PayPal</a>.
</p>

<?php require_once './includes/form_functions.inc.php'; ?>

<form action="register.php" method="post" accept-charset="utf-8">
    <?php
    $reg_errors = array();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	if (preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['first_name'])) {
    		$firstName = escape_data($_POST['first_name'], $dbc);
    	} else {
    		$reg_errors['first_name'] = 'Please enter your first name!';
    	}
    	
    	if (preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['last_name'])) {
    		$lastName= escape_data($_POST['last_name'], $dbc);
    	} else {
    		$reg_errors['last_name'] = 'Please enter your last name!';
    	}
    	
    	if (preg_match('/^[A-Z0-9]{2,45}$/i', $_POST['username'])) {
    		$username = escape_data($_POST['username'], $dbc);
    	} else {
    		$reg_errors['username'] = 'Please enter a desired name using only letters and numbers!';
    	}
    	
    	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    		$email = escape_data($_POST['email'], $dbc);
    	} else {
    		$reg_errors['email'] = 'Please enter a valid email address!';
    	}
    	
    	if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']) ) {
    		if ($_POST['pass1'] === $_POST['pass2']) {
    			$p = $_POST['pass1'];
    		} else {
    			$reg_errors['pass2'] = 'Your password did not match the confirmed password!';
    		}
    	} else {
    		$reg_errors['pass1'] = 'Please enter a valid password!';
    	}
    	
    	if (empty($reg_errors)) {
    		$sql = "SELECT email, username FROM users WHERE email='$email' OR username='$username'";
    		$result = mysqli_query($dbc, $sql);
    		$rows = mysqli_num_rows($result);
    		
    		if ($rows === 0) {
    			$sql = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires) VALUES ('$username', '$email',
				'".password_hash($_POST['pass1'], PASSWORD_BCRYPT)."', '$firstName', '$lastName', SUBDATE(NOW(), INTERVAL 1 DAY) )";
    			$result = mysqli_query($dbc, $sql);
    			
    			if (mysqli_affected_rows($dbc) === 1) {
    				$uid = mysqli_insert_id($dbc);
    				
    				echo '<div class="alert alert-success"><h3>Thanks my friend!</h3>
							<p>
								Thanks you for registering! To complete the process, please now click the button below so that you may pay for your site access via PayPal.
								The coat is R$50,00 (BRA) per year. <strong>Note: When you complete your payment at PayPal,
								lease click the button to return to this site.</strong>
							</p>
						</div>';
    				echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="custom" value="' .$uid. '">
							<input type="hidden" name="email" value="' .$email. '">
							<input type="hidden" name="hosted_button_id" value="VQY2CUSF5ZCPC">
							<input type="submit" name="submit" value="Subscribe &rarr;" id="submit" class="btn btn-success btn-block">
						</form>';
    				
    				$body = "Thank you for registering at Brangioni\'s ecommerce. Lorem lorem, ipson ipson..\n\n";
    				mail($_POST['email'], 'Registration Confirmation', $body, 'From: vitorh.brangioni@gmail.com');
    				include './includes/footer.html';
    				
    				exit();
    				
    			} else {
    				// gerando erro, q sera tratado no handler de erros do arquivo config
    				trigger_error('You cold not be registered due to a system error.
						Whe apologize for any inconvenience, We will correct the error ASAP');
    			}
    			
    			if ($rows === 2) {
    				$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at left to have your sent to you.';
    				$reg_errors['username'] = 'This username has already been registered. Please try another.';
    			} else {
    				$row = mysqli_fetch_array($result, MYSQLI_NUM);
    				
    				if (($row[0] === $_POST['email']) && ($row[1] === $_POST['username'])) {
    					$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at left to have your sent to you.';
    					$reg_errors['username'] = 'This username has already been registered with this email address. If you have forgotten your password, use the link at left to have your sent to you.';
    				} elseif ($row[0] === $_POST['email']) {
    					$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at left to have your sent to you.';
    				} elseif ($row[1] === $_POST['username']) {
    					$reg_errors['username'] = 'This username has already been registered. Please try another.';
    				}
    			}
    			
    		} 
    		
    	}
    	
    }
    
	create_form_input('first_name', 'text', 'First Name', $reg_errors); 
	create_form_input('last_name', 'text', 'Last Name', $reg_errors); 
	create_form_input('username', 'text', 'Desired username', $reg_errors);
	echo '<span class="help-block">Only letters and numbers are allowed.</span>';
	create_form_input('email', 'email', 'Email Address', $reg_errors);
	create_form_input('pass1', 'password', 'Password', $reg_errors);
	echo '<span class="help-block">Must be at least 6 characters long, with at least one lowercase letter, one uppercase letter, and one number.</span>';
	
	
	create_form_input('pass2', 'password', 'Confirm Password', $reg_errors);
    ?>
    
	<input type="submit" name="submit_button" value="Next &rarr;" id="submit_button" class="btn btn-default">

</form>

<?php include './includes/footer.html'; ?>


