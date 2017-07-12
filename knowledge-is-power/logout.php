<?php

require './includes/config.inc.php';
redirect_invalid_user();
// limpando o array $_SESSION
$_SESSION = array();
// remove os dados armazenados no servidor
session_destroy();
//modificando o cookie da dessadao no navegador de usuario, para que ele nao tenha mais o registro do id da sessao
// envia um cookie c/ mesmo nome, porem sem valor (ID da sessao), e um horario de vencedimento igual a 5min atras
setcookie(session_name(), '', time() -300);
require MYSQL;
$page_titile = "Logout";
include './includes/header.html';
echo '<h1>Logged Out</h1><p>Thank you for visiting. You are now logged out. Please come back soon!</p>';
include './includes/footer.html';
?>