<?php
 if (!defined('LIVE')) {
 	// determina o modo como os erros serao tratados
 	define('LIVE', false);
 }
 
 // endereco de e-mail para qual as mensagens de erros serao enviadas ou email para contato
 define('CONTACT_EMAIL', '');
 
 // As primeiras constantes que o site utilizara 
 define('HTTPS', 'https://');
 define('HTTP', 'http://');
 define('BASE_URI', '/var/www/html/knowledge-is-power/includes/');
 define('BASE_URL', 'localhost/knowledge-is-power/');
 define('PDFS_DIR', '/var/www/html/pdfs/');
 define('MYSQL', BASE_URI . 'mysql.inc.php');
 
 session_start();
 
 /**
  * funcao para tratamento de erro personalizado
  * @param unknown $e_number
  * 	id do erro, represesenta um E_WARNING
  * @param unknown $e_message
  * 	mensagem do erro
  * @param unknown $e_file
  * 	nome do arquivo em que o erro ocorreu
  * @param unknown $e_line
  * 	linha em que o erro ocorreu
  * @param unknown $e_vars
  * 	um array com todas as variaveis que existiam no momento em que erro ocorreu
  */
 function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {
	
 	$message = "An error occurred in scriptdropdown '$e_file' on line $e_line: \n$e_message\n";
 	$message .= "<pre>".print_r(debug_backtrace(), 1). "</pre>\n";
 	
 	// se o site nao estiver ativo
 	if (!LIVE) {
 		// nl2br: transforma o '\n em tags de quebra de linha';
 		echo '<div class="alert alert-danger">'.nl2br($message). '</div>';
 	} else { // se estiver ativo, envie o errro por email
 		error_log($message, 1, CONTACT_EMAIL, 'From:vitorh.brangioni@gmail.com');
 		
 		// Sera mostrado a mensagem generica, somente se erro nao for uma noticia (Script identifica algo que pode ocorrer erro, mas nada que atrapalhe a funcionalidade)
 		if ($e_number != E_NOTICE) {
 			echo '<div class="alert alert-danger"> A system error occurred. We apologize for the inconvenience</div>';
 		}
 	}
 	// deve retornar algo diferente de false, pois se nao sera chamado o handler default do php. Isso seria ruim, caso o site encontra-se ativo
 	return true;
 }
 
 function redirect_invalid_user($check = 'user_id', $destination = 'index.php') {
	$protocol = 'http://';
	
	if (!isset($_SESSION[$check])) {
		$url = $protocol. BASE_URL.$destination;
		header("Location: $url");
		exit();
	}
 }
 