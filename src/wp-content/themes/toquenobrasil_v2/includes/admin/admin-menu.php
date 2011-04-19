<?php


add_action('admin_menu', 'tnb_admin_menu');

function tnb_admin_menu() {
    add_submenu_page('tnb_admin', __('Oportunidades', 'tnb'), __('Oportunidades', 'tnb'), 'manage_options', 'tnb_admin', 'tnb_admin_oportunidades');
    add_menu_page('Relatorios', 'Relatorios', 'manage_options', 'tnb_admin', 'tnb_admin_oportunidades');
    add_submenu_page('tnb_admin', __('Top 10', 'tnb'), __('Top 10', 'tnb'), 'manage_options', 'tnb_admin_top10', 'tnb_admin_top10');
    add_submenu_page('tnb_admin', __('Cadastros', 'tnb'), __('Cadastros', 'tnb'), 'manage_options', 'tnb_admin_registros', 'tnb_admin_registros');
    add_submenu_page('tnb_admin', __('Usuários', 'tnb'), __('Usuários', 'tnb'), 'manage_options', 'tnb_admin_usuarios', 'tnb_admin_usuarios');
}

function tnb_admin_oportunidades() { 

	echo 'bla';

} 

function tnb_admin_top10() { 

	include 'relatorio-top10.php';

} 

function tnb_admin_registros() { 

	echo 'reg';

} 

function tnb_admin_usuarios() { 

	echo 'usu';

} 

?>
