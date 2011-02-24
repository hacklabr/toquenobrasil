<?php
require_once('../../../wp-load.php');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$cidades = $wpdb->get_results("SELECT municipio.* FROM municipio, uf WHERE municipio.ufid = uf.id AND uf.sigla = '$_REQUEST[uf]' ORDER BY municipio.nome");


foreach($cidades as $cidade){
  $selected = $cidade->nome == $_REQUEST['selected'] ? ' selected="selected"' : '';
  echo "<option value='$cidade->nome'$selected>$cidade->nome</option>";
}
?>
