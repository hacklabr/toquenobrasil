<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
  <?php
    global $page, $paged;
	wp_title( '|', true, 'right' );
    bloginfo( 'name' );
	
    
  	if ( $paged >= 2 || $page >= 2 )
 	echo ' | ' . sprintf( __( 'Page %s', 'tnb' ), max( $paged, $page ) );
  ?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie.css" />
<![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!-- Icon -->
<link type="image/x-icon" href="<?php echo get_theme_image('favicon.ico'); ?>" rel="shortcut icon" />

<?php
  if ( is_singular() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );
  wp_head();
?>
</head>

<body <?php body_class(); ?>>
  <div class="stripes-header">
    <div class="stripes-content">
      <div id="content" class="container">
        
          <a href="<?php bloginfo('url'); ?>" title='Página inicial''>
            <?php theme_image("toquenobrasil2.png", array("id" => "toquenobrasil", "style" => "height:auto;width:340px;")); ?>
          </a>
        
        
          <?php wp_nav_menu(array("theme_location" => "main",
                                  "container" => "div",
                                  "container_id" => "nav-main",
                                  "before" => "<span class='title'>",
                                  "after" => "<span class='shadow'></span></span>",
                                  "link_before" => "<h1>",
                                  "link_after" => "</h1>"
                                 )
          ) ?>
          
        <?php if( is_user_logged_in()):
                global $current_user;
                $cap = get_user_option('wp_capabilities', $current_user->ID);
                $edit_url  = get_bloginfo('url');
                if(isset($cap['administrator'])){
                    $edit_url.= '/wp-admin/profile.php';  
                }elseif(isset($cap['produtor'])){
                    $edit_url.= '/editar/produtor';
                }elseif(isset($cap['artista'])){
                    $edit_url.= '/editar/artista';
                }
        ?>
        	<div id="login">
        		<a href='<?php echo get_author_posts_url($current_user->ID)?>'><?php echo get_avatar($current_user->ID, 120); ?><span>Ver Perfil</span></a><br />
        		<?php if(current_user_can('delete_users')):?>
                	<a href="<?php echo get_bloginfo('url')?>/wp-admin"><span>Painel Admin</span></a><br />
                <?php endif;?>
                <?php if(!current_user_can('delete_users')):?>
                    <a href="<?php echo $edit_url; ?>"><span>Editar Perfil</a></span><br />
                <?php endif; ?>
                <a href="<?php  echo wp_logout_url(get_bloginfo('url')) ; ?>"><span>Sair</span></a>
        	</div> 
        	 
        <?php else: ?>  
    		<div id="login">
                <?php
            	    if($_GET['login_error']){
            	        echo "<div class='error'>" ;
                            _e('Nome de usuário ou senha inválidos','tnb');
                        echo "</div>" ;
            	    }
            	    if($_GET['email_confirm']){
            	        echo "<div class='error'>" ;
                            _e('Confirme seu e-mail para acessar sua conta.','tnb');
                        echo "</div>" ;
            	    }
            	    if($_GET['new_pass']){
            	        echo "<div class='notice'>" ;
                            _e('Acesse seu email para o link de ativação.','tnb');
                        echo "</div>" ;
            	    }
            	?>
            	<form method="post" action="<?php bloginfo('url'); ?>/wp-login.php" id="signinform">
            		<input type="hidden" value="<?php echo preg_replace("/(login_error=1)?/", "", $_SERVER['REQUEST_URI']); ?>" name="redirect_to">
                	<h5>Login | <a href="<?php bloginfo('url');?>/cadastre-se/artista">Cadastre-se!</a></h5>
                    <input type="text" name="log" value="" id="user_login" class="text" />
                    <input type="password" name="pwd" value="" id="senha" class="text" />
                    <a id="lost-pass" href="#"><span>Perdi a senha</span></a><input type="image" name="ok" src="<?php echo get_theme_image("ok.png"); ?>" id="signin_btn" />
                </form>
                <form method="post" action="<?php bloginfo('url'); ?>/wp-login.php?action=lostpassword"  name="lostpasswordform" id="lostpassform">
                	<h5>recuperar senha</h5>
                	<a>insira seu nome de usuário</a>
                    <input type="text" class="text" id="user_login" name="user_login">
    	        	<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>?new_pass=1" name="redirect_to">
    	        	<a id="cancel-lost-pass" href="#"><span>Cancelar</span></a><input type="image" name="ok" src="<?php echo get_theme_image("ok.png"); ?>" id="ok" />
                </form>
            </div>
        <?php endif;?>  
        <div class="clear"></div>    
