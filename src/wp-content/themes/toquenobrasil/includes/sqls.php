<?php

$opt  = get_option("role_defined", false);

if( ( (!$opt  || $opt == 0 ) && current_user_can('add_users'))){
    global $wp_roles;

    $wp_roles->add_role( 'artista', 'Artista', array('read','publish_posts'));
    $wp_roles->add_role( 'produtor', 'Produtor', array('read','publish_posts', 'create_event', 'publish_event', 'select_artists'));

    $adm = get_role('administrator');

    $adm->add_cap( 'select_other_artists' );

    update_option('default_role', 'artista');

    update_option('role_defined','1');

}

if (!get_option('tnb_sql_1')) {

    update_option('tnb_sql_1', 1);
    global $wpdb;
    $eventos = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type='eventos'");
    foreach ($eventos as $evento) {
        update_post_meta($evento, 'superevento', 'no');
    }
}

if (!get_option('tnb_sql_7')) {
    update_option('tnb_sql_7', 1);
    $adm = get_role('produtor');
    $adm->add_cap( 'edit_posts' );
    $adm->add_cap( 'edit_published_posts' );
    $adm->add_cap( 'publish_posts' );
    $adm->add_cap( 'read' );
    $adm->add_cap( 'read_private_posts' );
    $adm->add_cap( 'delete_posts' );
}

if (!get_option('tnb_sql_9')) {
    update_option('tnb_sql_9', 1);
    
    global $wpdb;
    
    $metas_datas = array('evento_inicio', 'evento_inscricao_inicio', 'evento_fim', 'evento_inscricao_fim');
    
    foreach ($metas_datas as $m) {
        $metas = $wpdb->get_results("SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '$m'");
        
        foreach ($metas as $me) {
        
            if (preg_match('|\d\d/\d\d/\d\d\d\d|', $me->meta_value)) {
                $newValue = preg_replace('|(\d\d)/(\d\d)/(\d{4})|', "$3-$2-$1", $me->meta_value);
                $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '$newValue' WHERE meta_id = $me->meta_id");
            }
        
        }
        
    }
}

if (!get_option('tnb_sql_10')) {
    update_option('tnb_sql_10', 1);
    
    global $wpdb;
    
    $metas = $wpdb->get_results("SELECT umeta_id, user_id, meta_value FROM $wpdb->usermeta WHERE meta_key = 'telefone'");
    
    foreach ($metas as $me) {
    
        $ddd = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE user_id = $me->user_id AND meta_key = 'telefone_ddd'");
        $wpdb->query("UPDATE $wpdb->usermeta SET meta_value = '$ddd $me->meta_value' WHERE umeta_id = $me->umeta_id");
    
    }
}

// eventos que não tem um pais marcado ficam 'BR'
if(!get_option('tnb_sql_11')) {
    update_option('tnb_sql_11', 1);

    global $wpdb;
    $eventos = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type='eventos'");

    foreach ($eventos as $evento) {
        update_post_meta($evento, 'evento_pais', 'BR');
    }
}

// coloca 'http://' em links que não tem
if(!get_option('tnb_sql_12')) {
    update_option('tnb_sql_12', 1);

    global $wpdb;
    $wpdb->query("UPDATE $wpdb->postmeta SET meta_value=concat('http://', meta_value) WHERE meta_key='evento_site' AND NOT meta_value LIKE 'http://%' AND NOT meta_value='';");
}
?>
