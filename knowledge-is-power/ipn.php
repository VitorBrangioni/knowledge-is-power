<?php

require 'includes/config.inc.php';

// Verifica se foi uma solicitacao post e o id da transacao existe e se o pagamento foi efetuado
if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['txn_id']) && ($_POST['txn_type'] === 'web_accept')) {
	// inicializa o handler de solicitacao cURL
	$ch = curl_init();
	
	// configurando comportamento da solicitacao cURL
	curl_setopt_array($ch,
			array(
				// define a URL a ser solicitada
				CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
				// fara uma solicitacao post de volta para o paypal				
				CURLOPT_PORT => true,
				// disponibiliza os dados a serem enviado para parte da solicitacao (dados a serem enviados para PayPal)
				// "'cmd' => '_notify-validate'" informa o comando q esta sendo enviado para payPal,
				// nesse caso tem proposito de comunicacao
				// "http_build_query" serve para enviar todos os dados de uma vez ao post, alternativa seria criar um laco
				CURLOPT_POSTFIELDS => http_build_query(array('cmd' => '_notify-validate') + $_POST),
				// resultado da solicitacao deve ser devolvido como string, em vez de ser apresentado
				CURLOPT_RETURNTRANSFER => true,
				// cabecalho n deve ser incluido na saida (no resultado da solicitacao)
				CURLOPT_HEADER => false
			));
	
	// executando a solicitacao cURL
	$response = curl_exec($ch);
	// obtendo o codigo de status da resposta cURL
	$status = curl_getinfo($ch);
	// finalizando a solicitacao cURL
	curl_close($ch);
	
	if ($status === 200 && $response === 'VERIFIED') {
		if (isset($_POST['payment_status']) && ($_POST['payment_status'] === 'Completed')
			&& ($_POST['receiver_email'] === 'vitorh.brangioni-facilitator@gmail.com') && ($_POST['mc_gross'] === 50.00)
			&& ($_POST['mc_currency'] === 'BRA') && (isset($_POST['txn_id']))) {
				
			require MYSQL;
			$txn_id = escape_data($_POST['txt_id'], $dbc);
			$query = "SELECT id FROM orders WHERE transaction_id='$txn_id'";
			$result = mysqli_query($dbc, $query);
			
			if (mysqli_num_rows($result) === 0) {
				$uid = (isset($_POST['custom'])) ? (int) $_POST['custom'] : 0;
				$status = escape_data($_POST['payment_status'], $dbc);
				$amount = (int) ($_POST['mc_gross'] * 100);
				$query = "INSERT INTO orders (user_id, transaction_id, payment_status, payment_amount)
							VALUES ($uid, '$txn_id', '$status', '$amount')";
				$result = mysqli_query($dbc, $query);
				
				if (mysqli_affected_rows($dbc) === 1) {
					if ($uid > 0) {
						$query = "UPDATE users SET date_expires = IF(date_expires > NOW(), ADDDATE(date_expires, INTERVAL 1 YEAR),
										ADDDATE(date_expires, INTERVAL 1 YEAR)), date_modified=NOW()
										WHERE id=$uid";
						$result = mysqli_query($dbc, $query);
						
						if (mysqli_affected_rows($dbc) !== 1) {
							trigger_error('The user\'s expiration date could not be updated!');
						}
					}
				}
			} else {
				trigger_error('The transaction could not be stored in the orders table!');
			}
		}
	} else { // resposta indevida
		// registrar log para investigacao futura
	}
} else {
	echo 'Nothing to do.';
}
?>