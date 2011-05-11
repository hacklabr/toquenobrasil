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




if(!get_option('tnb_sql_28')){ 
	update_option('tnb_sql_28', 1);
	
	global $wpdb;
	$sql = "
CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}tnb_users_stats` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	`reg_type` VARCHAR( 10 ) NOT NULL ,
	`user_id` INT NOT NULL ,
	`login` VARCHAR( 255 ) NOT NULL ,
	`capability` VARCHAR( 25 ) NOT NULL ,
	`pais` VARCHAR( 50 ) NOT NULL ,
	`estado` VARCHAR( 50 ) NOT NULL ,
	`cidade` VARCHAR( 50 ) NOT NULL ,
	PRIMARY KEY ( `id` ) ,
	UNIQUE (
		`id`
	)
)";
	_pr($sql);
	$wpdb->query($sql); 
	
	$query = "
SELECT 
	$wpdb->users.ID,
	$wpdb->users.user_login,
	$wpdb->users.user_registered,
	(INSTR(wp_usermeta.meta_value,'artista') > 0) AS is_artista
FROM
	$wpdb->users,
	$wpdb->usermeta
WHERE
	$wpdb->users.ID = $wpdb->usermeta.user_id AND
	$wpdb->usermeta.meta_key = '{$wpdb->prefix}capabilities' AND
	($wpdb->usermeta.meta_value LIKE '%artista%' OR
	 $wpdb->usermeta.meta_value LIKE '%produtor%')";
	 
	 $users = $wpdb->get_results($query);
	 
	 foreach ($users as $user){
	 	$capability = $user->is_artista ? 'artista' : 'produtor';
	 	if($capability == 'artista'){
	 		$pais = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_pais' AND user_id = $user->ID");
	 		$estado = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_estado' AND user_id = $user->ID");
	 		$cidade = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_cidade' AND user_id = $user->ID");
	 	}else{
	 		$pais = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'origem_pais' AND user_id = $user->ID");
	 		$estado = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_estado' AND user_id = $user->ID");
	 		$cidade = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_cidade' AND user_id = $user->ID");
	 	} 
	 	$q = "
INSERT INTO {$wpdb->prefix}tnb_users_stats(
	`data`,
	`reg_type`,
	`user_id`,
	`login`,
	`capability`,
	`pais`,
	`estado`,
	`cidade`
)VALUES(
	'$user->user_registered',
	'insert',
	'$user->ID',
	'$user->user_login',
	'$capability',
	'$pais',
	'$estado',
	'$cidade'
)";
	 	$wpdb->query($q);
	 }
}
?>
