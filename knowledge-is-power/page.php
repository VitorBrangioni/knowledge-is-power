<?php

require './includes/config.inc.php';
require MYSQL;

if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
	$page_id = $_GET['id'];
} else {
	$page_title = 'Error!';
	include 'includes/header.html';
	echo '<div class="alert alert-danger">
			This page has been accessed in error.
		</div>';
}

$query = 'SELECT title, description, content FROM pages WHERE id=' .$page_id;
$result = mysqli_query($dbc, $query);

if (mysqli_num_rows($result) !== 1) {
	$page_title = 'Error!';
	include './includes/header.html';
	echo '<div class="alert alert-danger">This page has been accessed in error.</div>';
	include './includes/footer.html';
	exit();
}

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$page_title = $row['title'];
include 'includes/header.html';
echo '<h1>' .htmlspecialchars($page_title). '</h1>';

if (isset($_SESSION['user_not_expired'])) {
	echo "<div>{$row['content']}</div>";
} elseif (isset($_SESSION['user_id'])) {
	echo '<div class="alert">
			<h4>Expired Account</h4>
			Thank you for your interest in this content,
			but your account is no longer current.
			Please <a href="renew.php">renew your account</a>
			in order to view this page in its entirety.
		</div>';
	echo '<div>' .htmlspecialchars($row['description']). '</div>';
} else {
	echo '<div class="alert">
			Thank you for your interest in this content.
			You must be logged in as a registered user to view this page in its entirety.
		</div>';
	echo '<div>' .htmlspecialchars($row['description']). '</div>';
}

include 'includes/footer.html';
