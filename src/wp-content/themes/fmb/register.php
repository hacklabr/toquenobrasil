<?php
//error_reporting(E_ALL);
$reg_type = (isset($_POST['type']) ? $_POST['type'] :  $wp_query->get('reg_type'));
$regtister_succes[$reg_type]  = $activated = $msgs=  false;
require_once( ABSPATH . WPINC . '/registration.php' );

$estados = get_estados();

global $wpdb;

// get user by ID to edit values;
$user = new stdClass();


if(isset($_GET['action']) && $_GET['action'] == 'activate'){
    
    $key = preg_replace('/[^a-z0-9]/i', '', $_GET['key']);
    $login = $_GET['login'];
	if ( empty( $key ) || !is_string( $key ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	if ( empty($login) || !is_string($login) )
		return new WP_Error('invalid_key', __('Invalid key'));

	$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login));
	if ( empty( $user ) )
		return new WP_Error('invalid_key', __('Invalid key'));
		
		
	update_user_meta($user->ID, 'tnb_inactive', false); //Set up the Password change nag.

	$activated  = true;
}

if(isset($_POST['action']) && $_POST['action'] == 'register'){
    
    foreach($_POST as $n=>$v)
        $user->{$n} = $v;    
    
    $user_login = sanitize_user($_POST['user_login']);
    $user_email = $_POST['user_email'];
    $errors = array();
    
    if(!isset($_POST['tos_acepted']))
        $errors['tos'] =  'Você precisa aceitar os termos antes de realizar o cadastro.';
        
    if(username_exists($user_login)){
        $errors['user'] =  'Esse usuário já está sendo usado.';
    }
    if(email_exists($user_email)){
        $errors['email'] =  'Este email já está registrado em nosso sistema.';
    }
    if(!filter_var( $user_email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] =  'Email informado é inválido.';
    }
    
    if($_POST['senha'] != $_POST['senha_confirm']){
        $errors['pass_confirm'] =  'As senhas informadas não são iguais.';
    }
    
    if(strlen($user_login)==0)
        $errors['user'] =  'Informe um nome de usuário.';

    if(!preg_match('/^([a-z0-9_-]+)$/', $user_login))
        $errors['user'] =  'Nome de usuário inválido.';        
        
    if(strlen($user_email)==0)
        $errors['email'] =  'Informe o email.';  

    if(strlen($_POST['senha'])==0 || strlen($_POST['senha_confirm'])==0)
        $errors['pass'] =  'Informe a senha.';        

    //  campos obr de banda    
    if($reg_type == 'artista' && strlen($_POST['banda'])==0)
        $errors['banda'] =  'Informe o nome do artista.';    

    if($reg_type == 'artista' && strlen($_POST['responsavel'])==0)
        $errors['responsavel'] =  'Informe o nome do responsável.';

    //  campos obr de produtores        
    if($reg_type == 'produtor' && strlen($_POST['nome'])==0)
        $errors['nome'] =  'Informe o seu nome.';    

        
        
    if(strlen($_POST['site'])>0 && !filter_var($_POST['site'], FILTER_VALIDATE_URL))
        $errors['site'] = __('O site fornecido não é válido.','tnb');     
        
    if(!sizeof($errors)>0){
        $user_pass = $_POST['senha'];
        //$user_pass = wp_generate_password();
       
        $data['user_login'] = $user_login;
        $data['user_pass'] = $user_pass;
        $data['user_email'] =  $user_email;
        $data['role'] = $reg_type ;        
        $user_id = wp_insert_user($data);
        
        if ( ! $user_id ) {
            if ( $errmsg = $user_id->get_error_message('blog_title') )
                echo $errmsg;
    	}
    	
//        update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
        
        if($reg_type == 'artista'){
            update_user_meta( $user_id, 'banda' , $_POST['banda'] );
            update_user_meta( $user_id, 'responsavel' , $_POST['responsavel'] );
        }elseif( $reg_type == 'produtor'){
           update_user_meta( $user_id, 'nome' , $_POST['nome'] );
           update_user_meta( $user_id, 'banda' , $_POST['nome'] );
        }
        update_user_meta( $user_id, 'telefone' , $_POST['telefone'] );
        update_user_meta( $user_id, 'telefone_ddd' , $_POST['ddd'] );
        update_user_meta( $user_id, 'site' , $_POST['site'] );
        
        
        update_user_meta( $user_id, 'origem_estado' , $_POST['origem_estado'] );
        update_user_meta( $user_id, 'origem_cidade' , $_POST['origem_cidade'] );
        
        update_user_meta( $user_id, 'banda_estado' , $_POST['banda_estado'] );
        update_user_meta( $user_id, 'banda_cidade' , $_POST['banda_cidade'] );
        
        update_user_meta( $user_id, 'categoria' , $_POST['categoria'] );
        update_user_meta( $user_id, 'subcategoria' , $_POST['subcategoria'] );
        update_user_meta( $user_id, 'integrantes' , $_POST['integrantes'] );
        
        
        update_user_meta($user_id, 'tnb_inactive', '1');
        
        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
    	if ( empty($key) ) {
    		// Generate something random for a key...
    		$key = wp_generate_password(20, false);
    		do_action('retrieve_password_key', $user_login, $key);
    		// Now insert the new md5 key into the db
    		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
    	}
    	if($reg_type == 'artista'){
        	$message = "Sua conta foi criada com sucesso, atualize seu perfil com mais informações!\r\n\r\n";
        	$message .= "Nome de usuário: " .$user_login . "\r\n";
        	$message .= "Senha:"  . $user_pass . "\r\n\r\n";
        	$message .= "Acesse o link abaixo para ativar a conta\r\n";
        	$message .=  get_bloginfo('url')."/cadastre-se/$reg_type?action=activate&key=$key&login=" . rawurlencode($user_login) . "\r\n\r\n";
            $message .= "Para completar sua inscrição, acesse seu perfil e faça upload do seu material!\r\n";
            $message .= "Atenciosamente\n";
    		$message .= "Feira Música Brasil";
    		
    		$header = 'cc:' . get_bloginfo('admin_email');
    	}elseif( $reg_type == 'produtor'){
            $message = "Sua conta foi criada com sucesso!\r\n";
    	    
    	    $message.= "Atenciosamente\n";
    		$message.= "Feira Música Brasil";
//          $message .= "Nome de usuário: " .$user_login . "\r\n";
//        	$message .= "Senha:"  . $user_pass . "\r\n\r\n";
//        	$header = 'cc:' . get_bloginfo('admin_email');
        	   
        }	
        $title = 'FMB | Confirmação de Cadastro';   
        if ( $message && !wp_mail($user_email, $title, $message, $header) )
		    wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') );
    	
        
        
        //wp_new_user_notification( $user_id, $user_pass );
        
        $msgs['success'] = 'Cadastro efetuado com sucesso';
        $regtister_succes[$reg_type] = true;
    }else{
        foreach($errors as $type=>$msg)
            $msgs['error'][] = $msg;
    }
}



wp_enqueue_script('cadastre-se', get_stylesheet_directory_uri(). '/js/cadastro_perfil.js',array('jquery')); 

get_header();


?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
    
  <div class="item green clearfix">
    <div class="title pull-1 clearfix">
      <div class="shadow"></div>
      <h1>Cadastre-se</h1>
    </div>
  </div>    

  <?php print_msgs($msgs);?>

    <?php global $atividadesEncerradas; if (!$atividadesEncerradas): ?>

      <?php //the_content(); ?>
            <div id="formularios-de-cadastro">
                <div id="abas" class="clearfix">                        
                   
                    <div id="aba-artistas" class="title <?php echo ($reg_type == 'artista' ? 'current' : '');?>">                    	
                        <a href="#"><?php _e('Artistas', 'tnb');?><span class="shadow"></span></a>
                    </div>
                </div><!-- #abas -->
                <div id="conteudo">
                    <div id="artistas" class="item green">
                    	
                        <form class="background clearfix" method="POST">
                        	<?php if($regtister_succes['artista']):?>
                        		<?php _e('Seu cadastro foi realizado com sucesso. <br />
                        		Você receberá um link de ativação do seu perfil no e-mail que nos forneceu no cadastro. Caso não o encontre em sua caixa de entrada, verifique em sua caixa de spam ou lixeira. <br />
                        		Acessando a sua conta você poderá postar imagens, músicas e os demais dados para completar a sua inscrição.', 'tnb');?>
                        	<?php elseif($activated):?>
                        		<?php _e('Seu cadastro foi ativado. Para incrementar o seu perfil, faça o login.', 'tnb');?>	
                        	<?php else:?>
                                <input type="hidden" name="action" value="register" />
                                <input type="hidden" name="type" value="artista" />
                                <i>Campos marcardos com <?php theme_image('lock.png', array('title' => 'teste')); ?> não serão exibidos publicamente no site. Apenas os produtores do evento terão acesso a estes dados</i>
                                <br/><br/>
                                <div class="span-12">
                                    <h3 class='no-margin'><?php _e('Dados cadastrais', 'tnb');?></h3>
                                </div>
                                
                                <div class="span-6 clear">
                                    <label for=user_login><?php _e('Nome de usuário:', 'tnb');?></label>
                                    <br/>
                                    <input class="span-6 text user_login" type="text" id="user_login" name="user_login" value="<?php echo htmlspecialchars($user->user_login); ?>" />
                                    <small><?php _e('Este nome será utilizado para se conectar ao FMB e não poderá ser modificado. Usar apenas minúsculas, números, - ou _. ', 'tnb'); ?></small>
                                </div>
                                <div class="span-6">
                                    <label for="user_email"><?php _e('E-mail:', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
                                    <br />
                                    <input class="span-6 text" type="text" id="user_email" name="user_email" value="<?php echo htmlspecialchars($user->user_email); ?>" />
                                    <small><?php _e('Email do responsável pela inscrição', 'tnb'); ?></small>
                                </div>
                                <div class='clear'></div>
                                <div class="span-6">
                                    <label for="senha"><?php _e('Senha:', 'tnb');?></label>
                                    <br />
                                    <input class="span-6 text" type="password" id="senha" name="senha" />
                                </div>
                                <div class="span-6">
                                    <label for="senha_confirm"><?php _e('Confirmar Senha:', 'tnb');?></label>
                                    <br />
                                    <input class="span-6 text" type="password" id="senha_confirm" name="senha_confirm" />
                                </div>
                                <div class="span-12">
                                    <h3 class='no-margin'><?php _e('Dados do Artista/Grupo/Dj/Conjunto', 'tnb');?></h3>
                                </div>
                                <div class="span-12">
                                    <label for="banda"><?php _e('Nome do Artista:', 'tnb');?></label>
                                    <br />
                                    <input class="span-12 text" type="text" id="banda" name="banda" value="<?php echo htmlspecialchars($user->banda); ?>" />
                                </div>
                                <div class="span-6">
                                    <label for="responsavel"><?php _e('Responsável:', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
                                    <br />
                                    <input class="span-6 text" type="text" id="responsavel" name="responsavel" value="<?php echo htmlspecialchars($user->responsavel); ?>" />
                                    <small><?php _e('Nome do responsável pela inscrição', 'tnb'); ?></small>
                                </div>    
                                 <div class="span-6">
                                    <label for="telefone"><?php _e('Telefone:', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
                                    <br />
                                    <input class="span-1 text" type="text" id="ddd" name="ddd" value="<?php echo htmlspecialchars($user->ddd); ?>" />
                                    <input class="span-5 text" type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($user->telefone); ?>" />
                                    <small><?php _e('Número do responsável pela inscrição', 'tnb'); ?></small>
                                </div>                   
                                
                                 <div class="span-12">
                                    <h5 class='no-margin'><?php _e('Local de origem do artista:', 'tnb');?></h5>
                                 </div>
                                
                                <div class="span-6">
                                    <label for="origem_estado"><?php _e('Estado:', 'tnb');?></label>
                                    <br />
                                    <select class="span-6 text" name="origem_estado" id='origem_estado'>
                                        <?php 
                                            foreach($estados as $uf=>$name){
                                                echo "<option " . ($user->origem_estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";    
                                            }
                                        ?>
                                    </select>
                                 </div>
                                <div class="span-6">   
                                    <label for="origem_cidade"><?php _e('Cidade:', 'tnb');?></label>
                                    <br />
                                    <input class="span-6 text" type="text" id="origem_cidade" name="origem_cidade" value="<?php echo htmlspecialchars($user->origem_cidade); ?>" />
                                </div>
                                
                                
                                <div class="span-12">
                                    <h5 class='no-margin'><?php _e('Local de residência do artista', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></h5>
                                 </div>
                                
                                <div class="span-6">
                                    <label for="banda_estado"><?php _e('Estado:', 'tnb');?></label>
                                    <br />
                                    <select class="span-6 text" name="banda_estado" id='banda_estado'>
                                        <?php 
                                            foreach($estados as $uf=>$name){
                                                echo "<option " . ($user->banda_estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";    
                                            }
                                        ?>
                                    </select>
                                 </div>
                                <div class="span-6">   
                                    <label for="banda_cidade"><?php _e('Cidade:', 'tnb');?></label>
                                    <br />
                                    <input class="span-6 text" type="text" id="banda_cidade" name="banda_cidade" value="<?php echo htmlspecialchars($user->banda_cidade); ?>" />
                                </div>
                                
                                <div class="span-12">
                                    <h5 class='no-margin'><?php _e('Integrantes', 'tnb');?> </h5>
                                 </div>
                                
                                <div class="span-12">
                                    <textarea class="span-12 text" id="integrantes" name="integrantes" /><?php echo htmlspecialchars($user->integrantes); ?></textarea>
                                </div>
                                
                                
                                
                                <div class="span-12">
                                    <h5 class='no-margin'><?php _e('Categoria', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></h5>
                                 </div>
                                
                                <div class="span-12">
                                    <?php 
                                    
                                    global $fmb_categorias, $fmb_subcategorias;
                                    
                                    $selectedCat = array();
                                    $selectedSubCat = array();
                                    $selectedCat[$user->categoria] = 'checked';
                                    $selectedSubCat[$user->subcategoria] = 'checked';
                                    ?>
                                    
                                    <?php foreach($fmb_categorias as $cat_value => $cat_name) : ?>
                                        
                                        <input type="radio" class="categoria" name="categoria" id="categoria_<?php echo $cat_value; ?>" value="<?php echo $cat_value; ?>" <?php echo $selectedCat[$cat_value]; ?>> <label for="categoria_<?php echo $cat_value; ?>"><?php echo $cat_name; ?></label>
                                        &nbsp;&nbsp;&nbsp;
                                        
                                    <?php endforeach; ?>
                                    
                                    <br/><br/>
                                    <div style="display:none" id="subcategorias_div">
                                    <?php foreach($fmb_subcategorias as $cat_value => $cat_name) : ?>
                                        
                                        <input type="radio" class="subcategorias" name="subcategoria" id="subcategoria_<?php echo $cat_value; ?>" value="<?php echo $cat_value; ?>" <?php echo $selectedSubCat[$cat_value]; ?>> <label for="subcategoria_<?php echo $cat_value; ?>"><?php echo $cat_name; ?></label>
                                        <br/>
                                        
                                    <?php endforeach; ?>
                                    </div>
                                    
                                </div>
                                
								<div class="span-12" id='register_tos_unique'>
									<?php 
									  query_posts('post_type=eventos');
									  if ( have_posts() ) : the_post(); 
                                          echo get_post_meta(get_the_ID(),'evento_tos', true);
                                      endif;
									?>
								</div>
                                
                                <div class="span-12 last">
                                    <input type="checkbox" name='tos_acepted' value="1" id='tos_acepted'/>
                                    <label for='tos_acepted'> Li e aceito os termos do edital</label>
                                </div>
                                
                                <div class="span-2 prepend-10 last">
                                    <input type="image" src="<?php echo get_theme_image("submit-green.png"); ?>" value="Enviar" class="submit" />
                                </div>
                                
                                
                                
                            <?php endif;?>
                        </form>
                    </div><!-- #artistas -->
                    
                </div><!-- #content -->
            </div>
            <!-- #formularios-de-cadastro -->     
    <?php else: // atividades encerradas ?>
    
        Inscrições encerradas
    
    <?php endif; ?>
</div>
<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("tnb-sidebar");?>
    </div>
</div>
<?php get_footer(); ?>
