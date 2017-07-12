<?php
require './includes/config.inc.php';
require MYSQL;

if (filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
	$category_id = $_GET['id'];
	
	$query = 'SELECT category FROM categories WHERE id ='.$category_id;
	$result = mysqli_query($dbc, $query);
	
	if (mysqli_num_rows($result) !== 1) {
		$page_title = 'Error!';
		include './includes/header.html';
		echo '<div class="alert alert-danger">This page as been accessed in error.</div>';
		include './includes/footer.html';
		exit();
	}
	
	list($page_title) = mysqli_fetch_array($result, MYSQLI_NUM);
	include './includes/header.html';
	echo '<h1>' .htmlspecialchars($page_title). '</h1>';
	
	if (isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired'])) {
		echo '<div class="alert"><h4>Expired Account</h4>Thank you for interest in the content
				Unfortunately your account has expired.
				Please <a href="renew.php">renew your account</a> in order to access site content.
			</div>';
	} elseif (!isset($_SESSION['user_id'])) {
		echo '<div class="alert">Thank you for your interest in this content.
				You must be logged in as a registed user to view site content.
			</div>';
	}
	$query = 'SELECT id, title, description FROM pages WHERE categories_id='.$category_id.
			' ORDER BY date_created DESC';
	$result = mysqli_query($dbc, $query);

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '<div>
					<h4>
						<a href="page.php?id=' .$row['id']. '">'
							.htmlspecialchars($row['title']).
						'</a>
					</h4>
					<p>' .htmlspecialchars($row['description']). '</p>
				</div>';
		}
	} else {
		echo '<p>There are corrently no pages of content associated with this category.
				Please chack back again!</p>';
	}
} else {
	$page_title = 'Error!';
	include './includes/header.html';
	echo '<div class="alert alert-danger">This page as been accessed in error.</div>';
}
include './includes/footer.html';
?>