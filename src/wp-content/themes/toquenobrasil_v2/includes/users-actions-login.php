<?php 

if(isset($_REQUEST['tnb_user_action']) && $_REQUEST['tnb_user_action'] == 'edit-login') {

    if(!filter_var( $_POST['user_email'], FILTER_VALIDATE_EMAIL))
        $msg['error'][] = __('O email informado é inválido.','tnb');    
    
    if( $_POST['user_email'] != $profileuser->user_email && email_exists($_POST['user_email']))
        $msg['error'][] =  __('Esse email já está sendo utilizado. Por favor verifique se digitou os dados corretamente.', 'tnb');
    
    if (!$msg['error']) {
        
        $userdata = array(
            'ID' => $profileuser->ID,
            'user_email' => $_POST['user_email']
        );

        if ( strlen($_POST['user_pass'])>0){
            if( $_POST['user_pass'] !=  $_POST['user_pass_confirm'] )
               $msg['error'][] = __('A senhas fornecidas não conferem.','tnb');
               
            if(!$msg['error']){
                $userdata['user_pass'] = $_POST['user_pass'];
                
                $msg['notice'][] = __("Sua senha foi alterada.", 'tnb');
            }

        }
        
        //$updated = wp_insert_user($userdata);
        $updated = wp_update_user($userdata);
    
        if ($updated)
            $profileuser->user_email = $_POST['user_email'];
            
        $msg['notice'][] = __("Dados alterados.", 'tnb');
    }
    
    // evita mensagem de erro do user photo
    //var_dump($_FILES['userphoto_image_file']);
    if (isset($_FILES['userphoto_image_file']['error']) && $_FILES['userphoto_image_file']['error'] == 4) {
        unset($_FILES['userphoto_image_file']);
    }
    //var_dump($_FILES['userphoto_image_file']);
    
}


if ($_FILES['userphoto_image_file']) {
    do_action('custom_profile_update', $profileuser->ID);   
}

?>
