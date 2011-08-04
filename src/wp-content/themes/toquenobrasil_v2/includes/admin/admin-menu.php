<?php

add_action('admin_menu', 'tnb_admin_menu');

function tnb_admin_menu() {
	
    add_submenu_page('tnb_admin_pagamentos', 'Eventos com contratos não revisados', 'Contratos não revisados', 'manage_options', 'tnb_admin_pagamentos', 'tnb_admin_contrato_nao_revisado');
    add_menu_page('Pagamentos', 'Pagamentos', 'manage_options', 'tnb_admin_pagamentos','tnb_admin_contrato_nao_revisado',null,0);
    add_submenu_page('tnb_admin_pagamentos', 'Eventos com contrato pendente', 'Contratos pendentes', 'manage_options', 'tnb_admin_contratos_pendentes', 'tnb_admin_contratos_pendentes');
    add_submenu_page('tnb_admin_pagamentos', 'Eventos com contrato aceito', 'Contratos aceitos', 'manage_options', 'tnb_admin_contratos_aceitos', 'tnb_admin_contratos_aceitos');
    add_submenu_page('tnb_admin_pagamentos', 'Eventos com contrato recusado', 'Contratos recusados', 'manage_options', 'tnb_admin_contrato_recusado', 'tnb_admin_contratos_recusados');
    add_submenu_page('tnb_admin_pagamentos', 'Pagamentos pendentes', 'Pagamentos pendentes', 'manage_options', 'tnb_admin_contrato_pagamentos_pendentes', 'tnb_admin_contrato_pagamentos_pendentes');
    add_submenu_page('tnb_admin_pagamentos', 'Pagamentos efetuados', 'Pagamentos efetuados', 'manage_options', 'tnb_admin_contrato_pagamentos_efetuados', 'tnb_admin_contrato_pagamentos_efetuados');
    add_submenu_page('tnb_admin_pagamentos', 'Modelo de contrato', 'Modelo de contrato', 'manage_options', 'tnb_admin_modelo_contrato', 'tnb_admin_modelo_contrato');
    
    
    add_submenu_page('tnb_admin_pagamentos', 'revisão de contrato', null, 'manage_options', 'tnb_admin_vesisao_contrato','tnb_admin_vesisao_contrato');
    
    add_submenu_page('tnb_admin', __('Oportunidades', 'tnb'), __('Oportunidades', 'tnb'), 'manage_options', 'tnb_admin', 'tnb_admin_oportunidades');
    add_menu_page('Relatorios', 'Relatorios', 'manage_options', 'tnb_admin', 'tnb_admin_oportunidades',null,1);
    add_submenu_page('tnb_admin', __('Top 10', 'tnb'), __('Top 10', 'tnb'), 'manage_options', 'tnb_admin_top10', 'tnb_admin_top10');
    add_submenu_page('tnb_admin', __('Cadastros', 'tnb'), __('Cadastros', 'tnb'), 'manage_options', 'tnb_admin_registros', 'tnb_admin_registros');
    add_submenu_page('tnb_admin', __('Usuários', 'tnb'), __('Usuários', 'tnb'), 'manage_options', 'tnb_admin_usuarios', 'tnb_admin_usuarios');
    
    
    // JS INCLUDES
    
    wp_enqueue_script('datepicker_js', TNB_URL . '/js/ui.datepicker.js', array('jquery'));
   	wp_enqueue_script('datepicker_br_js', TNB_URL . '/js/jquery.ui.datepicker-pt-BR.js', array('datepicker_js'));
    wp_enqueue_style('jquery_ui', TNB_URL . '/css/jquery-ui.css');
    
    wp_enqueue_script('jquery-flot',TNB_URL.'/js/flot/jquery.flot.js', array('jquery'));
    wp_enqueue_script('jquery-flot-pie',TNB_URL.'/js/flot/jquery.flot.pie.js', array('jquery-flot'));
}

function tnb_admin_oportunidades() { 
	
	include 'relatorio-oportunidades.php';

} 

function tnb_admin_top10() { 

	include 'relatorio-top10.php';

} 

function tnb_admin_registros() { 
	include 'relatorio-registros.php';
} 

function tnb_admin_usuarios() { 

	echo 'usu';

} 

// ======== SISTEMA DE PAGAMENTO ======== //
function tnb_admin_contrato_nao_revisado(){
	include 'pagamento-actions.php';
	include 'pagamento-contrato-nao-revisado.php';
}

function tnb_admin_contratos_pendentes(){
	include 'pagamento-actions.php';
	include 'pagamento-contrato-pendente.php';
}

function tnb_admin_vesisao_contrato(){
	include 'pagamento-actions.php';
	include 'pagamento-revisao-contrato.php';
}

function tnb_admin_contratos_aceitos(){
	include 'pagamento-actions.php';
	include 'pagamento-contratos-aceitos.php';
}

function tnb_admin_contratos_recusados(){
	include 'pagamento-actions.php';
	include 'pagamento-contratos-recusados.php';
}

function tnb_admin_contrato_pagamentos_pendentes(){
	include 'pagamento-actions.php';
	include 'pagamento-pagamentos-pendentes.php';
}

function tnb_admin_contrato_pagamentos_efetuados(){
	include 'pagamento-actions.php';
	include 'pagamento-pagamentos-efetuados.php';
}
?>
