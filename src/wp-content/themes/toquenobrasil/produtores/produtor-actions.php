<?php

add_action('init','run_produtor_actions');
/**
 * Trata ações que um usuário do tipo produtor pode tratar no site
 */
function run_produtor_actions() {

    switch($_POST['action']) {
    case 'select_band':
        if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'select_band' ) ) {
            delete_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);
            if(!in_postmeta(get_post_meta($_POST['evento_id'] , 'selecionado'), $_POST['banda_id']))
                add_post_meta($_POST['evento_id'], 'selecionado', $_POST['banda_id']);
        }
        break;

    case 'unselect_band':
        if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'unselect_band' ) ) {
            delete_post_meta($_POST['evento_id'], 'selecionado', $_POST['banda_id']);
            if(!in_postmeta(get_post_meta($_POST['evento_id'] , 'inscrito'), $_POST['banda_id']))
                add_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);    
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
                $user = get_currentuserinfo();
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

    case 'mail_selected_artists':
        global $wpdb;
        $post_id = sprintf("%d", $_POST['post_id']);
        if($post_id) {
            $emails = $wpdb->get_col("SELECT user_email FROM wp_users INNER JOIN wp_postmeta ".
                                     "ON wp_postmeta.meta_value=wp_users.ID ".
                                     "AND wp_postmeta.post_id = $post_id ".
                                     "AND wp_postmeta.meta_key='selecionado';");
            if($emails){
                $user = get_currentuserinfo();
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
    }
}

function send_mail_to_artists($replyto, $emails, $subject, $message) {
    $header = "Reply-To: {$replyto}\r\n";
    $header .= "bcc: ".implode(',', $emails)."\r\n";
    return wp_mail($replyto, $subject, $message, $header);
}
