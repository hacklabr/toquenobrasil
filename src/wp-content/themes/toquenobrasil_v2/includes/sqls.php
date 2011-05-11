<?php


if (!get_option('tnb_sql_21')) {
    update_option('tnb_sql_21', 1);
    $adm = get_role('artista');
    $adm->add_cap( 'edit_posts' );
    $adm->add_cap( 'edit_published_posts' );
    $adm->add_cap( 'publish_posts' );
    $adm->add_cap( 'read' );
    $adm->add_cap( 'read_private_posts' );
    $adm->add_cap( 'delete_posts' );
    $adm->add_cap( 'delete_published_posts' );
}



if (!get_option('tnb_sql_25')) {
    update_option('tnb_sql_25', 1);
 
    global $wpdb;
    
    $metas = $wpdb->get_results("SELECT * from $wpdb->usermeta WHERE meta_key LIKE '_widget_Widget_%'");
    
    foreach ( $metas as $m ) {
        
        $mm = get_user_meta($m->user_id, $m->meta_key, true);
        
        if (is_object($mm)) {
        
            update_user_meta($m->user_id, $m->meta_key, base64_encode(serialize($mm)));
        
        }
        
    }
    
    
}

if(!get_option('tnb_sql_27')){
	update_option('tnb_sql_27', 1);
	
	global $wpdb;
	$sql = "
CREATE TABLE IF NOT EXISTS `pagseguro_transacoes` (
  `insert_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TransacaoID` varchar(255) NOT NULL,
  `StatusTransacao` varchar(255) NOT NULL,
  `DataTransacao` varchar(255) NOT NULL,
  `Referencia` varchar(255) NOT NULL,
  `ProdID` varchar(255) NOT NULL,
  `ProdValor` varchar(255) NOT NULL,
  `ProdDescricao` varchar(255) NOT NULL,
  `CliNome` varchar(255) NOT NULL,
  `CliEmail` varchar(255) NOT NULL,
  `CliTelefone` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	$wpdb->query($sql);
}

?>
