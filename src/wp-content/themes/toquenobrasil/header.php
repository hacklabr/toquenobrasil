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
          
		<div id="login">
        	<form>
            	<h5>Login | <a href="<?php bloginfo('url');?>/cadastre-se">Cadastre-se!</a></h5>
                <input type="text" name="" value="E-mail" id="user" class="text" />
                <input type="password" name="senha" value="senha" id="senha" class="text" />
                <a id="reset-pass" href="#">Perdi a senha</a><input type="image" name="ok" src="<?php echo get_theme_image("ok.png"); ?>" id="ok" />
            </form>
        </div>
        <div class="clear"></div>    
