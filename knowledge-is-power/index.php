<?php
require './includes/config.inc.php';
// $_SESSION['user_id'] = 1;
// $_SESSION['user_admin'] = true;
require MYSQL;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	include './includes/login.inc.php';
}
include './includes/header.html';
echo 'hello world!';
?>

<h1>Welcome</h1>
 <p class="lead">Welcome to Knowledge is Power, a site dedicated to keeping you up-to-date on the Web security and programming information you need to know. Blah, blah, blah. Yadda, yadda, yadda.</p>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent consectetur volutpat nunc, eget vulputate quam tristique sit amet. Donec suscipit mollis erat in egestas. Morbi id risus quam. Sed vitae erat eu tortor tempus consequat. Morbi quam massa, viverra sed ullamcorper sit amet, ultrices ullamcorper eros. Mauris ultricies rhoncus leo, ac vehicula sem condimentum vel. Morbi varius rutrum laoreet. Maecenas vitae turpis turpis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce leo turpis, faucibus et consequat eget, adipiscing ut turpis. Donec lacinia sodales nulla nec pellentesque. Fusce fringilla dictum purus in imperdiet. Vivamus at nulla diam, sagittis rutrum diam. Integer porta imperdiet euismod.</p>

<?php

include './includes/footer.html';

?>
	