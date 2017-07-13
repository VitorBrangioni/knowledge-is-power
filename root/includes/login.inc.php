<?php

$login_errors = array();

if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	$email = escape_data($_POST['email'], $dbc);
} else {
	$login_errors['email'] = 'Please enter a valid email address!';
}

if (!empty($_POST['pass'])) {
	$pwd = $_POST['pass'];
} else {
	$login_errors['pass'] = 'Please enter your password!';
}

if (empty($login_errors)) {
// 	$sql = "SELECT id, username, type, pass, IF(date_expires >= NOW(), true, false)
// 		AS expired FROM users WHERE email = '$email'";
	$sql = "SELECT id, first_name, last_name, username, email, type, pass, IF(date_expires >= NOW(), 'true', 'false') AS expired FROM users WHERE email='$email'";  
	$result = mysqli_query($dbc, $sql);
	
	if (mysqli_num_rows($result) === 1) {
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if (password_verify($_POST['pass'], $row['pass'])) {
			if ($row['type'] === 'admin') {
				// o id de sessao deve ser alterado por medida de seguranca (evitar ataque 'session fixation')
				session_regenerate_id(true);
				$_SESSION['user_admin'] = true;
			}
			
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['first_name'] = $row['first_name'];
			$_SESSION['last_name'] = $row['last_name'];
			
			if ($row['expired'] === 'true') {
				$_SESSION['user_not_expired'] = true;
			}
			
		} else {
			$login_errors['login'] = 'The email address and password do not match those on file';
		}
	} else {
		$login_errors['login'] = 'The email address and password do not match those on file';
	}
}