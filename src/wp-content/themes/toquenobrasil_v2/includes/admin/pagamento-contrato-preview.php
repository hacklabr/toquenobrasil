<?php
require_once('../../../../../wp-load.php');
if (!current_user_can('manage_options'))
  die ('sem permissao');
  
if(!isset($_POST['evento_id']))
	die('contrato não encontrado.');


echo nl2br(get_contrato_inscricao_substituido($_POST['evento_id'], $_POST['valor'], $_POST['porcentagem'], $_POST['contrato']));