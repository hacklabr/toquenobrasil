<?php
global $join_success, $unjoin_success, $join_success_evento_pago;
$join_success = $join_success_evento_pago = $unjoin_success = false;

switch($_POST['action']) {
	case 'confirmar-inscricao':
		if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'confirmar-inscricao' ) ) {
			if(!in_postmeta(get_post_meta($_POST['evento_id'] , 'inscrito'), $_POST['banda_id']) &&
            	in_postmeta(get_post_meta($_POST['evento_id'] , 'inscricao_pendente'), $_POST['banda_id'])) {
                global $wpdb;
                
                $wpdb->query("
                	UPDATE 
                		$wpdb->postmeta 
                	SET 
                		meta_key = 'inscrito' 
                	WHERE 
                		post_id = '$_POST[evento_id]' AND 
                		meta_key = 'inscricao_pendente' AND 
                		meta_value = '$_POST[banda_id]'
                ");
                		
                wp_cache_delete($_POST[evento_id],'post_meta');
            }
		}
	break;
	
    case 'select_band':
        
        if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'select_band' ) ) {
            
            if(!in_postmeta(get_post_meta($_POST['evento_id'] , 'selecionado'), $_POST['banda_id']) &&
            	in_postmeta(get_post_meta($_POST['evento_id'] , 'inscrito'), $_POST['banda_id'])) {
                global $wpdb;
                
                $wpdb->query("
                	UPDATE 
                		$wpdb->postmeta 
                	SET 
                		meta_key = 'selecionado' 
                	WHERE 
                		post_id = '$_POST[evento_id]' AND 
                		meta_key = 'inscrito' AND 
                		meta_value = '$_POST[banda_id]'
                ");
                		
                wp_cache_delete($_POST[evento_id],'post_meta');
            }
        }
        break;

    case 'unselect_band':
        if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'unselect_band' ) ) {
            if(!in_postmeta(get_post_meta($_POST['evento_id'] , 'inscrito'), $_POST['banda_id']) &&
            	in_postmeta(get_post_meta($_POST['evento_id'] , 'selecionado'), $_POST['banda_id'])) {
                 global $wpdb;
                
                $wpdb->query("
                	UPDATE 
                		$wpdb->postmeta 
                	SET 
                		meta_key = 'inscrito' 
                	WHERE 
                		post_id = '$_POST[evento_id]' AND 
                		meta_key = 'selecionado' AND 
                		meta_value = '$_POST[banda_id]'
                ");
                wp_cache_delete($_POST[evento_id],'post_meta');
            }
        }
        break;

    case 'mail_signed_artists':
        global $wpdb;
        $post_id = sprintf("%d", $_POST['post_id']);
        if($post_id) {
            $emails = $wpdb->get_col("SELECT user_email FROM wp_users INNER JOIN wp_postmeta ".
                                     "ON wp_postmeta.meta_value=wp_users.ID ".
                                     "AND wp_postmeta.post_id = $post_id ".
                                     "AND wp_postmeta.meta_key='inscrito';");
            if($emails){
                $user = wp_get_current_user();
                if(send_mail_to_artists($user->user_email,$emails,$_POST['subject'],$_POST['message'])) {
                    wp_redirect($_SERVER["REDIRECT_URL"].'?message=sentforsigned');
                    exit();
                } else {
                    $GLOBALS['tnb_errors'] = array(__('Seu e-mail não pode ser enviado. Entre em contato com o administrador do site.'));
                }
            } else {
                $GLOBALS['tnb_errors'] = array(__('Não existe artista inscrito.'));
            }
        }
        break;
        
    case 'mail_pending_artists':
        global $wpdb;
        $post_id = sprintf("%d", $_POST['post_id']);
        if($post_id) {
            $emails = $wpdb->get_col("SELECT user_email FROM wp_users INNER JOIN wp_postmeta ".
                                     "ON wp_postmeta.meta_value=wp_users.ID ".
                                     "AND wp_postmeta.post_id = $post_id ".
                                     "AND wp_postmeta.meta_key='inscricao_pendente';");
            if($emails){
                $user = wp_get_current_user();
                if(send_mail_to_artists($user->user_email,$emails,$_POST['subject'],$_POST['message'])) {
                    wp_redirect($_SERVER["REDIRECT_URL"].'?message=sentforsigned');
                    exit();
                } else {
                    $GLOBALS['tnb_errors'] = array(__('Seu e-mail não pode ser enviado. Entre em contato com o administrador do site.'));
                }
            } else {
                $GLOBALS['tnb_errors'] = array(__('Não existe artista com inscrição pendente.'));
            }
        }
        break;

    case 'mail_selected_artists':
        global $wpdb;
        $post_id = sprintf("%d", $_POST['post_id']);
        if($post_id) {
            $emails = $wpdb->get_col("SELECT user_email FROM wp_users INNER JOIN wp_postmeta ".
                                     "ON wp_postmeta.meta_value=wp_users.ID ".
                                     "AND wp_postmeta.post_id = $post_id ".
                                     "AND wp_postmeta.meta_key='selecionado';");
            if($emails){
                $user = wp_get_current_user();
                if(send_mail_to_artists($user->user_email,$emails,$_POST['subject'],$_POST['message'])) {
                    wp_redirect($_SERVER["REDIRECT_URL"].'?message=sentforselected');
                    exit();
                } else {
                    $GLOBALS['tnb_errors'] = array(__('Seu e-mail não pode ser enviado. Entre em contato com o administrador do site.'));
                }
            } else {
                $GLOBALS['tnb_errors'] = array(__('Não existe artista selecionado.'));
            }
        }
        break;
        
        case 'join':
            
            if(tnb_artista_can_join($_POST['evento_id']) && isset($_POST['_wpnonce']) &&  wp_verify_nonce($_POST['_wpnonce'], 'join_event' )){
            	if(get_post_meta($_POST['evento_id'], 'evento_inscricao_cobrada', true)){
                        if(in_postmeta(get_post_meta($_POST['evento_id'], 'inscricao_cancelada'), $_POST['banda_id'])){
                             $meta_id = $wpdb->get_var("
                                 SELECT 
                                     meta_id 
                                 FROM 
                                     $wpdb->postmeta 
                                 WHERE 
                                    post_id = '$_POST[evento_id]' AND 
                                    meta_key = 'inscricao_cancelada' AND 
                                    meta_value = '$_POST[banda_id]'");
                             
                             if(get_post_meta($_POST['evento_id'], 'transacao_inscricao-'.$meta_id,true)){
                                 $meta_key = 'inscrito';
                                 $join_success = true;
                             }else{
                                 $meta_key = 'inscricao_pendente';
                                 $join_success_evento_pago = true;
                             }
                             $wpdb->query("
                                UPDATE 
                                        $wpdb->postmeta 
                                SET 
                                        meta_key = '$meta_key' 
                                WHERE 
                                        post_id = '$_POST[evento_id]' AND 
                                        meta_key = 'inscricao_cancelada' AND 
                                        meta_value = '$_POST[banda_id]'
                             ");
                            
                        }elseif(!in_postmeta(get_post_meta($_POST['evento_id'], 'inscricao_pendente'), $_POST['banda_id']) && !in_postmeta(get_post_meta($_POST['evento_id'], 'inscrito'), $_POST['banda_id']) ){
	                    add_post_meta($_POST['evento_id'], 'inscricao_pendente', $_POST['banda_id']);
	                    $join_success_evento_pago = true;
	                    do_action('tnb_artista_inscreveu_em_um_evento_pago', $_POST['evento_id'], $_POST['banda_id']);
	                }
            	}else{
	                if(!in_postmeta(get_post_meta($_POST['evento_id'], 'inscrito'), $_POST['banda_id'])){
	                    add_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);
	                    $join_success = true;
	                    do_action('tnb_artista_inscreveu_em_um_evento', $_POST['evento_id'], $_POST['banda_id']);
	                }
            	}
            }
        
        break;
        
        case 'unjoin':
            //die(get_post_meta($_POST['evento_id'], 'evento_inscricao_cobrada', true));
            if(isset($_POST['_wpnonce']) &&  wp_verify_nonce($_POST['_wpnonce'], 'unjoin_event' )){
                if(in_postmeta(get_post_meta($_POST['evento_id'], 'inscrito'), $_POST['banda_id'])){
                    if(get_post_meta($_POST['evento_id'], 'evento_inscricao_cobrada', true)){
                        $wpdb->query("
                                UPDATE 
                                        $wpdb->postmeta 
                                SET 
                                        meta_key = 'inscricao_cancelada' 
                                WHERE 
                                        post_id = '$_POST[evento_id]' AND 
                                        meta_key = 'inscrito' AND 
                                        meta_value = '$_POST[banda_id]'
                        ");
                        $unjoin_success = true;
                        do_action('tnb_artista_desinscreveu_em_um_evento', $_POST['evento_id'], $_POST['banda_id']);
                    }else{
                        delete_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);
                        $unjoin_success = true;
                        do_action('tnb_artista_desinscreveu_em_um_evento', $_POST['evento_id'], $_POST['banda_id']);
                    }
                    
                }elseif(in_postmeta(get_post_meta($_POST['evento_id'], 'inscricao_pendente'), $_POST['banda_id'])){
                    delete_post_meta($_POST['evento_id'], 'inscricao_pendente', $_POST['banda_id']);
                    $unjoin_success = true;
                    do_action('tnb_artista_desinscreveu_em_um_evento_em_que_estava_pendente', $_POST['evento_id'], $_POST['banda_id']);
                }
                
            }
        
        break;
}

the_post();

// saida csv
if ($_GET['exportar']) {
    
    if ($_GET['exportar_tipo'] == 'superevento') {
    
        if ($_GET['exportar'] == 'produtor') {
            include('includes/export-produtores-superevento.php');
        } else {
            include('includes/export-bandas-superevento.php');
        }
    
    } else {
        include('includes/export-bandas.php');
    }
    
    die();
}

if($post->post_parent > 0) {
    $super_oportunidade = get_post($post->post_parent);

    if(!is_array(get_post_meta($post->ID, 'aprovado_para_superevento')) || !in_array($post->post_parent, get_post_meta($post->ID, 'aprovado_para_superevento'))) {
        if($current_user->ID != $post->post_author && $current_user->ID != $super_oportunidade->post_author) {
            wp_redirect(get_bloginfo('siteurl').'/oportunidades/');
        }
    }
}

function send_mail_to_artists($replyto, $emails, $subject, $message) {
    $header = "Reply-To: {$replyto}\r\n";
    $header .= "bcc: ".implode(',', $emails)."\r\n";
    return wp_mail($replyto, $subject, $message, $header);
}



do_action('eventos_view', $post->ID);

get_header();
    
if($post->post_parent != 0){
	get_template_part('oportunidades-single-sub');
}else{
	$num_subeventos = $wpdb->get_var("SELECT count(ID) AS num FROM $wpdb->posts WHERE post_type = 'eventos' AND post_parent = '$post->ID'");
	if($num_subeventos)
		get_template_part('oportunidades-single-super');
	else
		get_template_part('oportunidades-single');
}
get_sidebar();
get_footer();
?>
