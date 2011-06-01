<?php
/*
Plugin Name: EU
Plugin URI: 
Description: Exportador de usuários
Author: hacklab
Version: 0.1 
Text Domain: 
*/
define('EU_PREFIX', 'eu-');

add_action('init', 'eu_init');
register_activation_hook(__FILE__, 'eu_activate');
register_deactivation_hook(__FILE__, 'eu_deactivate');

function eu_init(){
    
    require_once dirname(__FILE__).'/EU_Exportador.class.php';
    
    
    $dir = opendir(dirname(__FILE__).'/exportadores/');
    while (false !== ($d = readdir($dir))){
        if(strpos($d,'class.php')){
    	    require_once dirname(__FILE__).'/exportadores/'.$d;
    	}
    }
    add_action('admin_menu', 'eu_admin_menu');
    
    if(isset($_POST[EU_PREFIX.'action'])){
        switch($_POST[EU_PREFIX.'action']){
            case 'export-users':
                if(isset($_POST['roles']) && isset($_POST['exportador'])){
                    $result = eu_getUsers();
                    eval('$exportador = new '.$_POST['exportador'].'($result);');
                    $exportador->export();
                    die;
                }
            break;
            
            case 'save-config':
                $euoptions = eu_getDefaultConfig();
                $euconfig = new stdClass();
                
                foreach ($euoptions->metadata as $k=>$n){
                    if(isset($_POST['metadata'][$k])) 
                        $euconfig->metadata[$k] = $_POST['metadata_desc'][$k];
                }
                
                foreach ($euoptions->userdata as $k=>$n){
                    if(isset($_POST['userdata'][$k])) 
                        $euconfig->userdata[$k] = $_POST['userdata_desc'][$k];
                }
                
                foreach ($euoptions->roles as $k=>$n){
                    if(isset($_POST['roles'][$k]))
                        $euconfig->roles[$k] = $_POST['roles_desc'][$k];
                }
                
                foreach ($euoptions->exportadores as $e){
                    if(isset($_POST['exportadores'][$e])){ 
                        $euconfig->exportadores[] = $e;
                        eval("{$e}::saveOptions();");
                    }
                }
                
                eu_setConfig($euconfig);
            break;
        }
    }
}

function eu_admin_menu(){
    add_submenu_page("users.php", __("Exportador de Usuário",'exportador-usuarios'), __("Exportador de Usuário",'exportador-usuarios'), 'manage_options', EU_PREFIX."eu-main", 'eu_page');
    add_options_page(__('Exportador de Usuários - Configurações','exportador-usuarios'), __('Exportador de Usuários', 'exportador-usuarios'), 'manage_options', 'eu-config', 'eu_config_page');    
}

function eu_config_page(){
    include dirname(__FILE__).'/config.php';
}
    
function eu_page(){
    include dirname(__FILE__).'/page.php';
}

function eu_activate(){
    $euconfig = eu_getDefaultConfig();
    $euconfig->metadata = array();
    if(get_option('exportador-usuario-config'))
        delete_option('exportador-usuario-config');
    
    add_option('exportador-usuario-config', $euconfig);
    
    $exportadores = eu_getExportadores();
    foreach ($exportadores as $exportador)
        eval("{$exportador}::activate();");
}

function eu_deactivate(){
    $exportadores = eu_getExportadores();
    foreach ($exportadores as $exportador)
        eval("{$exportador}::deactivate();"); 
    delete_option('exportador-usuario-config');
}

function eu_getExportadores(){
    require_once dirname(__FILE__).'/EU_Exportador.class.php';
    $exportadores = array();
    $dir = opendir(dirname(__FILE__).'/exportadores/');
    while (false !== ($d = readdir($dir))){
        if(strpos($d,'class.php')){
    	    require_once dirname(__FILE__).'/exportadores/'.$d;
    		$exportadores[] = substr($d, 0, -10);
    	}
    }	
    return $exportadores;
}

function eu_getConfig(){
    $euconfig = get_option('exportador-usuario-config');
    $ok = true;
    foreach ($euconfig->exportadores as $exportador)
        if(!class_exists($exportador))
            $ok = false;

    if(!$ok){
        $euconfig->exportadores = eu_getExportadores();
        eu_setConfig($euconfig);
    }
    return $euconfig;
}

function eu_setConfig($config){
    if(!$config->metadata)
        $config->metadata = array();

    if(!$config->userdata)
        $config->userdata = array();
    
    if(!$config->exportadores)
        $config->exportadores = array();
    
    update_option('exportador-usuario-config', $config);
}

function eu_getDefaultConfig(){
    global $wpdb, $wp_roles;
    $euoptions = new stdClass();

    $metakeys = $wpdb->get_col("SELECT DISTINCT meta_key FROM $wpdb->usermeta");
    
    $euoptions->userdata = array(
    	'ID' => __('ID','exportador-usuarios'),
        'user_login' => __('Login','exportador-usuarios'),
        'user_nicename' => __('Nice Name','exportador-usuarios'),
        'user_email' => __('E-Mail','exportador-usuarios'),
    	'user_url' => __('URL','exportador-usuarios'),
    	'user_registered' => __('Data de Registro','exportador-usuarios'),
    	'user_status' => __('Status do Usuário','exportador-usuarios'),
    	'display_name' => __('Nome','exportador-usuarios')
    );
    foreach ($metakeys as $metakey)
        $euoptions->metadata[$metakey] = $metakey;
    
    foreach ($wp_roles->roles as $role => $r)
        $euoptions->roles[$role] = $r['name'];
        
    $euoptions->exportadores = eu_getExportadores();
    
    return $euoptions;
}

function eu_getUsers(){
    global $wpdb;
    $roles = $_POST['roles'];
    
    foreach ($roles as $k=>$role)
        $roles[$k] = "meta_value LIKE '%\"$role\"%'";
        
    $metakeys = implode(' OR ', $roles);
    $udata = $_POST['userdata'];
    if(!in_array('ID', $udata))
        $udata[] = 'ID';
        
        
    
    $cols = implode(',' , $udata);
    $orderby = $_POST['order'];
    $oby = $_POST['oby'] == 'ASC' ? 'ASC' : 'DESC';
    
    // filtro
    if(isset($_POST['filter']) && trim($_POST['filter']) != ''){
        $field = $_POST['filter'];
        $value = $_POST['filter_value'];
        if($field[0] == '_'){
            $field = substr($field, 1);
            switch($_POST['operator']){
                case 'eq':
                    $filter = "AND ID IN (SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$field' AND meta_value = '$value')";
                break;
                case 'dif':
                    $filter = "AND ID NOT IN (SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$field' AND meta_value = '$value')";
                break;
                case 'like':
                    $filter = "AND ID IN (SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$field' AND meta_value LIKE '%$value%')";
                break;
                case 'not-like':
                    $filter = "AND ID NOT IN (SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$field' AND meta_value LIKE '%$value%')";
                break;
            }
        }else {
            switch($_POST['operator']){
                case 'eq':
                    $filter = "AND $field = '$value')";
                break;
                case 'dif':
                    $filter = "AND $field <> '$value')";
                break;
                case 'like':
                    $filter = "AND $field LIKE '%$value%')";
                break;
                case 'not-like':
                    $filter = "AND $field NOT LIKE '%$value%')";
                break;
            }
        }
        
        
    }else{
        $filter = '';
    }
    
    
    // seleciona os usuários
    $q = "
    	SELECT 
    		$cols
    	FROM 
            $wpdb->users 
        WHERE 
        	ID IN (	SELECT 
        				user_id 
        			FROM 
                        $wpdb->usermeta 
                    WHERE 
                    	meta_key = '{$wpdb->prefix}capabilities' AND 
                    	($metakeys)
                   )
            $filter
        ORDER BY $orderby $oby";
                        
                        
    
    $users = $wpdb->get_results($q);
    
    $euconfig = eu_getConfig();
    $user_ids = array();
    // limpa o usuário, removendo as propriedades que não foram selecionadas no formulario
    // de exportação
    $result = array();
    foreach($users as $user){
        $user_ids[] = $user->ID;
        $result[$user->ID] = $user;
    }
    
    unset($users);
    
    // seleciona os metadados to usuário
    $user_ids = implode(',', $user_ids);
    
    $metakeys = array();
    $metakeys = array_keys($euconfig->metadata);
    $metakeys = "'".implode("','", $metakeys)."'";
    
    $qm = "
    	SELECT
    		user_id,
    		meta_key,
    		meta_value
    	FROM
    		$wpdb->usermeta
    	WHERE
    		meta_key IN ($metakeys) AND
    		user_id IN ($user_ids)";
    
    $metadatas = $wpdb->get_results($qm);
    
    foreach ($metadatas as $metadata){
        $meta_key = $metadata->meta_key;
        $meta_value = $metadata->meta_value;
        $user_id = $metadata->user_id;
        
        $result[$user_id]->$meta_key = isset($result[$user_id]->$meta_key) ? ($result[$user_id]->$meta_key).", ".$meta_value : $meta_value;
        
    }
    
    return $result;             
}