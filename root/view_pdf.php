<?php
require 'includes/config.inc.php';
require MYSQL;
$valid = false;

if (isset($_GET['id']) && (strlen($_GET['id']) === 63) && (substr($_GET['id'], 0, 1) !== '.')) {
	$file = PDFS_DIR . $_GET['id'];
	
	if (file_exists($file) && is_file($file)) {
		$query = 'SELECT id, title, description, file_name
					FROM pdfs WHERE tmp_name="' .escape_data($_GET['id'], $dbc). '"';
		$result = mysqli_query($dbc, $query);
		
		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$valid = true;
			
			if (isset($_SESSION['user_not_expired'])) {
				header('Content-type:application/pdf');
				header('Cotent-Disposition:inline;filename="' .$row['file_name']. '"');
				$file_size = filesize($file);
				header("Content-Length:$file_size\n");
				readfile($file);
				exit();
			} else {
				$page_title = $row['title'];
				include 'includes/header.html';
				
				echo "<h1>$page_title</h1>";
				
				if (isset($_SESSION['user_id'])) {
					echo '<div class="alert">
							<h4>Expired Account</h4>
							Thank you for your interest in this content, but your account is no longer current.
							Please <a href="reniew.php">reniew your account</a> in order to access this file.
						</div>';
				} else {
					echo '<div class="alert">
							Thank you for your interest in this content.
							You must be logged in sa a registered user to access this file.
						</div>';
				}
				echo '<div>' .htmlspecialchars($row['description']). '</div>';
				include 'includes/footer.html';
			}
		}
	}
}

if (!$valid) {
	$page_title = 'Error!';
	include 'includes/header.html';
	
	echo '<div class="alert alert-danger">
			This page has been accessed in error.
		</div>';
	
	include 'includes/footer.html';
}
?>