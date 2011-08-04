<?php
require_once('../../../../wp-load.php');

if (!current_user_can('manage_options'))
  die ('sem permissao');
else
  echo 'iniciando script<hr/>';
/* */

if(!get_option('script-recuperacao-perfil')){
    update_option('script-recuperacao-perfil', date());
    
    $users = $wpdb->get_results("
    	SELECT
    		DISTINCT $wpdb->usermeta.user_id,
    		$wpdb->users.user_login
    	FROM
    		$wpdb->usermeta,
    		$wpdb->users
    	WHERE
    		($wpdb->usermeta.meta_key = '_widget_container_right' OR $wpdb->usermeta.meta_key = '_widget_container_left') AND
    		$wpdb->usermeta.meta_value LIKE '%[object Object]%'  AND
    		$wpdb->users.ID = $wpdb->usermeta.user_id
    ");
    		
    foreach ($users as $user){
        $user_id = $user->user_id;
        $widgets = $wpdb->get_col("
        	SELECT
        		meta_key
        	FROM
        		$wpdb->usermeta
        	WHERE
        		meta_key LIKE '_widget_Widget_%' AND
        		user_id = '$user_id'
        ");

        $container_left = array();
        $container_right = array();
        
        for($i=0; $i < count($widgets); $i++){
            if($i < count($widgets)/2)
                $container_left[] = $widgets[$i];
            else
                $container_right[] = $widgets[$i];
        }
        $left = addslashes(serialize($container_left));
        $right = addslashes(serialize($container_right));
        
        $result['login'] = $user->user_login;
        $result['left'] = $container_left;
        $result['right'] = $container_right;
        
        $result['left_value'] = $left;
        $result['right_value'] = $right;
        
        
        _pr($result);
        
        $wpdb->query("
        	UPDATE 
                $wpdb->usermeta 
            SET
            	meta_value = '$left'
            WHERE
            	meta_key = '_widget_container_left' AND
            	user_id = '$user_id'");
                
        $wpdb->query("
        	UPDATE 
                $wpdb->usermeta 
            SET
            	meta_value = '$right'
            WHERE
            	meta_key = '_widget_container_right' AND
            	user_id = '$user_id'");
                
        /* */
        
    }
}