<?php

//define('SHORTINIT', true);

require('../../../../../wp-load.php');

$mural_id = $_REQUEST['mural_id'];
$msg = $_REQUEST['message'];

$current_user = wp_get_current_user();

if (is_numeric($_REQUEST['deletar']) && (int) $_REQUEST['deletar'] > 0) {

    $comment_to_delete = (int) $_REQUEST['deletar'];
    
    $comment = get_comment($comment_to_delete);
    
    if (current_user_can('edit_post', $mural_id) || $current_user->ID == $comment->user_id)
        wp_delete_comment($comment_to_delete);

}

if (strlen($msg) > 0) {
    
    // novo comentário
    $msg = strip_tags($msg);
    $msg = substr($msg, 0, 1000);
	
    
    
    if (is_object($current_user)) {
    
        
        
        $commentdata = array(
            'comment_post_ID' => $mural_id,
            'comment_author' => $current_user->display_name,
            'comment_author_email' => $current_user->user_email,
            'user_ID' => $current_user->ID,
            'comment_author_url' => get_author_posts_url($current_user->ID),
            'comment_content' => $msg
            
        );
        
        $comment_id = wp_new_comment( $commentdata );
        // check if the comment is approved
        $comment = get_comment($comment_id);
        
        if ($comment->comment_approved==0)
            _e('Seu comentário está aguardando moderação', 'tnb');
        }

}


print_mural_comentarios($mural_id, $_REQUEST['per_page'], $_REQUEST['page'], $_REQUEST['profile_owner']);
