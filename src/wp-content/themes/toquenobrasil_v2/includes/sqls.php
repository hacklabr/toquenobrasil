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




if(!get_option('tnb_sql_31')){ 
	update_option('tnb_sql_31', 1);
	
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
	PRIMARY KEY (`id`),
	UNIQUE KEY `id` (`id`),
	KEY `user_id` (`user_id`)
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
	 $wpdb->usermeta.meta_value LIKE '%produtor%') AND
	$wpdb->users.ID NOT IN (SELECT user_id FROM {$wpdb->prefix}tnb_users_stats)";
	 
	 $users = $wpdb->get_results($query);
	 _pr($query);
	 foreach ($users as $user){
	 	$capability = $user->is_artista ? 'artista' : 'produtor';
	 	if($capability == 'artista'){
	 		$pais = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_pais' AND user_id = $user->ID");
	 		$estado = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_estado' AND user_id = $user->ID");
	 		$cidade = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'banda_cidade' AND user_id = $user->ID");
	 	}else{
	 		$pais = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'origem_pais' AND user_id = $user->ID");
	 		$estado = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'origem_estado' AND user_id = $user->ID");
	 		$cidade = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'origem_cidade' AND user_id = $user->ID");
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

if(!get_option('tnb_sql_32')){ 
	!update_option('tnb_sql_32', 1);
	global $wpdb;
	$produtores = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tnb_users_stats WHERE capability = 'produtor'");
	
	foreach($produtores as $produtor){
		$origem_pais = '';
		$origem_estado = '';
		$origem_cidade = '';
		
		$metas = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE user_id = '$produtor->user_id' AND (meta_key = 'origem_pais' OR meta_key = 'origem_estado' OR meta_key = 'origem_cidade')");
		foreach($metas as $meta){
			$key = $meta->meta_key;
			$$key = $meta->meta_value;
		}
		$wpdb->query("UPDATE {$wpdb->prefix}tnb_users_stats SET pais = '$origem_pais', estado = '$origem_estado', cidade = '$origem_cidade' WHERE user_id = '$produtor->user_id'");
	}
}

if(get_option('tnb_sql_33')){
    global $wpdb;
    update_option('tnb_sql_33', 1);
    
    $q = "
CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}tnb_logs` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_type` varchar(50) NOT NULL,
  `log_data` text NOT NULL,
  PRIMARY KEY (`ID`)
)";
    $wpdb->query($q);
}
?>
