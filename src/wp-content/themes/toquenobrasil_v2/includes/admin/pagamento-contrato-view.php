<?php
require_once('../../../../../wp-load.php');
if (!current_user_can('manage_options'))
  die ('sem permissao');
  
if(!isset($_GET['evento_id']))
	die('contrato não encontrado.');

$contrato = get_contrato_inscricao($_GET['evento_id']);

echo nl2br(get_contrato_inscricao_substituido($_GET['evento_id'], $contrato['valor'], $contrato['porcentagem'], $contrato['contrato']));