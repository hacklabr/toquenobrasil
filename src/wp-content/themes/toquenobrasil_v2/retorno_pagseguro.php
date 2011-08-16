<?php
require '../../../wp-load.php';
global $wpdb;
$path = dirname(__FILE__).'/retornos-pagseguro/';


if(count($_POST) > 0){
	$file_content = serialize($_POST);
}


header('Content-Type: text/html; charset=ISO-8859-1');

$token = get_theme_option('tnb_pagseguro_token');
define('TOKEN', $token);

class PagSeguroNpi {
	
	private $timeout = 20; // Timeout em segundos
	
	public function notificationPost() {
		$postdata = 'Comando=validar&Token='.TOKEN;
		foreach ($_POST as $key => $value) {
			$valued    = $this->clearStr($value);
			$postdata .= "&$key=$valued";
		}
		return $this->verify($postdata);
	}
	
	private function clearStr($str) {
		if (!get_magic_quotes_gpc()) {
			$str = addslashes($str);
		}
		return $str;
	}
	
	private function verify($data) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = trim(curl_exec($curl));
		curl_close($curl);
		return $result;
	}

}

if (count($_POST) > 0) {
	
	// POST recebido, indica que é a requisição do NPI.
	$npi = new PagSeguroNpi();
	$result = $npi->notificationPost();
	
	
	
	ob_start();
	echo "
	========================
	";
	print_r($_POST);
	echo "
	- - - - - - - - - - - - - - - - - - - - - - - - - 
	";
	print_r($result);
	
	echo "
	- - - - - - - - - - - - - - - - - - - - - - - - -
	";
	
	$fc = ob_get_clean();
	$log_file = $path.'retornos-'.date('Ymd').'.serial';
	if(is_writable($log_file) || (!file_exists($log_file) && is_writable(dirname($log_file))))
		file_put_contents($log_file, $fc, FILE_APPEND);
	
	
	
	$transacaoID = isset($_POST['TransacaoID']) ? $_POST['TransacaoID'] : '';
	
	if ($result == "VERIFICADO") {
		$wpdb->query("
INSERT INTO pagseguro_transacoes (
		`TransacaoID`, 
		`StatusTransacao`, 
		`DataTransacao`, 
		`TipoPagamento`,
		`Referencia`, 
		`ProdID`, 
		`ProdValor`, 
		`ProdDescricao`, 
		`CliNome`, 
		`CliEmail`, 
		`CliTelefone`)
VALUES (
		'".$_POST['TransacaoID']."',
		'".$_POST['StatusTransacao']."',
		'".$_POST['DataTransacao']."',
		'".$_POST['TipoPagamento']."',
		'".$_POST['Referencia']."',
		'".$_POST['ProdID_1']."',
		'".$_POST['ProdValor_1']."',
		'".$_POST['ProdDescricao_1']."',
		'".$_POST['CliNome']."',
		'".$_POST['CliEmail']."',
		'".$_POST['CliTelefone']."')");
		
		if($_POST['StatusTransacao'] == 'Aprovado'){
			$wpdb->query("UPDATE $wpdb->postmeta SET meta_key='inscrito' WHERE meta_id = '".$_POST['Referencia']."'");
			add_post_meta($_POST['ProdID_1'], 'transacao_inscricao-'.$_POST['Referencia'], $_POST['TransacaoID']);
			do_action('tnb_artista_inscricao_confirmada_em_evento_pago',$_POST['Referencia']);
		}
	} else if ($result == "FALSO") {
		file_put_contents($path.uniqid('RESULT_FALSO-'), $file_content);
	} else {
		file_put_contents($path.uniqid('RESULT_ERROR-'), $file_content);
	}
	
} else {
	// POST não recebido, indica que a requisição é o retorno do Checkout PagSeguro.
	// No término do checkout o usuário é redirecionado para este bloco.
	?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TNB</title>
        <link rel="stylesheet" href="http://tnb.art.br/wp-content/themes/toquenobrasil_v2/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
    </head>
    <body>
        <div class="container_16 text-center" style="margin-top:15%;">
            <a href="http://tnb.art.br" title="TNB" style="border:none;">
                <img src="http://tnb.art.br/wp-content/themes/toquenobrasil_v2/img/tnb-big.png" alt="TNB" style="margin-bottom:36px;" />
            </a>
            <h2>Obrigado por se inscrever</h2>
        </div>
    </body>
</html>
    <?php
    
}