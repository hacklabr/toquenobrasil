<?php

if(isset($_POST['action']) && $_POST['action'] == 'register'){
    
    require_once( ABSPATH . WPINC . '/registration.php' );
    
    $user = new stdClass();
    foreach($_POST as $n=>$v)
        $user->{$n} = $v;

    $user_login = sanitize_user($_POST['user_login']);
    $user_email = $_POST['user_email'];
    $user_pass = $_POST['user_pass'];
    $errors = array();

    if(username_exists($user_login)){
        $errors['user'] =  __('Já existe um usário com este nome no nosso sistema. Por favor, escolha outro nome.', 'tnb');
    }

    if(email_exists($user_email)){
        $errors['email'] =  __('Este e-mail já está registrado em nosso sistema. Por favor, cadastre-se com outro e-mail.', 'tnb');
    }
    if(!filter_var( $user_email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] =  __('O e-mail informado é inválido.', 'tnb');
    }
    /*
    if($_POST['senha'] != $_POST['senha_confirm']){
        $errors['pass_confirm'] =  'As senhas informadas não são iguais.';
    }
    */

    if(strlen($user_login)==0)
        $errors['user'] =  __('O nome de usuário é obrigatório para o cadastro no site.', 'tnb');

    if(strlen($user_login) > 0 && strlen($user_login) < 3)
        $errors['user'] =  __('Nome de usuário muito curto. Escolha um com 3 letras ou mais.', 'tnb');


    if(!preg_match('/^([a-z0-9-]+)$/', $user_login))
        $errors['user'] =  __('O nome de usuário escolhido é inválido. Por favor, escolha outro nome de usuário.', 'tnb');

    if(strlen($user_email)==0)
        $errors['email'] =  __('O e-mail é obrigatório para o cadastro no site.', 'tnb');

    if(strlen($user_pass)==0 )
        $errors['pass'] =  'A senha é obrigatória para o cadastro no site.';

    
    if(!sizeof($errors)>0){
        
        //$user_pass = wp_generate_password();

        $data['user_login'] = $user_login;
        $data['user_pass'] = $user_pass;
        $data['user_email'] =  $user_email;
        
        $data['role'] = $_POST['user_type'] ;
        $user_id = wp_insert_user($data);

        if ( ! $user_id ) {
            if ( $errmsg = $user_id->get_error_message('blog_title') )
                echo $errmsg;
        }

        //update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.

        $options = get_option('custom_email_notices');
        
        /*
        update_user_meta($user_id, 'tnb_inactive', '1');
        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
        if (empty($key)) {
            // Generate something random for a key...
            $key = wp_generate_password(20, false);
            do_action('retrieve_password_key', $user_login, $key);

            // Now insert the new md5 key into the db
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }
        */
        
    
        // Mensagem inserida via admin do WP
        $message = $options['msg_novo_'.$_POST['user_type']]?$options['msg_novo_'.$_POST['user_type']]:'';

        $info  = "Nome de usuário: {$user_login}\r\n";
        $info .= "Senha: {$user_pass}\r\n\r\n";
        //$info .= "Acesse o link abaixo para ativar a conta\r\n";
        //$info .=  get_bloginfo('url')."/cadastre-se/$reg_type?action=activate&key=$key&login=" . rawurlencode($user_login) . "\r\n\r\n";

        $message = str_replace('{{INFORMACOES}}', $info, $message);

        $header = 'cc:' . get_bloginfo('admin_email');

        
        $title = 'TNB | Confirmação de Cadastro';
        if ( $message && !wp_mail($user_email, $title, $message, $header) )
            wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') );

        //wp_new_user_notification( $user_id, $user_pass );

        $msgs['success'] = 'Cadastro efetuado com sucesso';
        
        
        do_action('tnb_user_register', $user_id);
        
        
        // depois de fazer o registro, faz login
        
        if ( is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
            $secure_cookie = false;
        else
            $secure_cookie = '';

        $user = wp_signon(array('user_login' => $user_login, 'user_password' => $user_pass), $secure_cookie);

		add_user_meta($user_id, 'email_publico', $user_email);
		
        if ( !is_wp_error($user) && !$reauth ) {
            wp_safe_redirect(get_author_posts_url($user_id));
            exit();
        }
        
    }else{
        foreach($errors as $type=>$msg)
            $msgs['error'][] = $msg;
    }
}

?>

<?php get_header(); ?>

<section id="signup" class="grid_16 clearfix box-shadow text-center">
    <?php theme_image("tnb-big.png", array("id" => "tnb", "alt" => "TNB")); ?>
    <form method="post">
        <p>
            <?php if ($_POST['tipo_usuario'] == 'produtor'): ?>
                <?php theme_image("sou-artista-off.png", array("alt" => "Sou Artista", "class" => "i-am-artist")); ?>
                <?php theme_image("sou-produtor.png", array("alt" => "Sou Produtor", "class" => "i-am-producer")); ?>
                <input type="hidden" name="user_type" id="tipo_usuario" value="produtor" />
            <?php else: ?>
                <?php theme_image("sou-artista.png", array("alt" => "Sou Artista", "class" => "i-am-artist")); ?>
                <?php theme_image("sou-produtor-off.png", array("alt" => "Sou Produtor", "class" => "i-am-producer")); ?>
                <input type="hidden" name="user_type" id="tipo_usuario" value="artista" />
            <?php endif; ?>
        </p>
        
        <p id="user-explanation">
            <span id="i-am-artist" class="bottom">
                <?php echo get_theme_option('iam_artist_explanation'); ?>
            </span>
            &nbsp;
            <span id="i-am-producer" class="bottom">
                <?php echo get_theme_option('iam_producer_explanation'); ?>
            </span>
        </p>
        
        <?php print_msgs($msgs);?>
        
        <p class="inputs clearfix">
            <input type="text" id="user_login" name="user_login" value="usuario" title="<?php _e('usuario', 'tnb'); ?>" />
            
            <input type="hidden" name="action" value="register" />
            <input type="text" id="user_email" name="user_email" value="e-mail" title="<?php _e('e-mail', 'tnb'); ?>" />
            <input type="text" id="_user_pass" name="user_pass" value="senha" title="<?php _e('senha', 'tnb'); ?>" />
            <input type="password" id="user_pass" name="user_pass" value="" title="<?php _e('senha', 'tnb'); ?>" style="display:none"/>
        </p>

        <p><?php _e("O seu perfil é para <strong>divulgação</strong>, então caso for um artista, banda ou DJ, <strong>coloque seu nome artístico em usuário</strong>! <br/> Esse nome <strong>não poderá ser trocado</strong> e só deverá possuir <strong>letras minúsculas, números e hífens (traços)</strong>.", "tnb"); ?></p>

        <p>
            <span id="check_username_loading" class="check_username loading" style="display:none"><?php _e('Verificando disponibilidade', 'tnb'); ?></span>
            <span id="check_username_true" class="check_username disponivel" style="display:none"><?php _e('Disponível', 'tnb'); ?></span>
            <span id="check_username_false" class="check_username indisponivel" style="display:none"><?php _e('Indisponível. Por favor, escolha outro nome de usuário.', 'tnb'); ?></span>
            <span id="check_username_short" class="check_username indisponivel" style="display:none"><?php _e('Nome de usuário muito curto. Escolha um com 3 letras ou mais.', 'tnb'); ?></span>
            <br/>
            <span class="url" id="url_preview">http://<span>usuario</span>.tnb.art.br</span>
        </p>
        
        <p>
            <?php if(!is_user_logged_in()): ?>
                <input type="submit" value="Cadastrar" class="green" />
            <?php else: ?>
                <?php _e('Você já está cadastrado!', 'tnb'); ?>
            <?php endif; ?>
        </p>
    </form>
</section>

<?php get_footer(); ?>
