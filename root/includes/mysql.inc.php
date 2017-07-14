<?php
define('DB_USER', 'admin');
define('DB_PASSWORD', 'Admin@Site123');
define('DB_HOST', 'localhost');
define('DB_NAME', 'larryullman_ecommerce1');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

mysqli_set_charset($dbc, 'utf8');

/**
 * Remove as barras extras quando o Magic Quotos estiver habilitado
 * Remove os espacos extras dos dados
 * Passa os dados pela funcao de escape do mysql
 * 
 * @param unknown $data
 * @param unknown $dbc
 */
function escape_data($data, $dbc) {
		
	if (get_magic_quotes_gpc()) {
		$data = stripcslashes($data);
	}
	return mysqli_real_escape_string($dbc, trim($data));
	
}