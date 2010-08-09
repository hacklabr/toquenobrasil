<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
  <?php
    global $page, $paged;
    wp_title( '|', true, 'right' );
    bloginfo( 'name' );

    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
      echo " | $site_description";

    if ( $paged >= 2 || $page >= 2 )
      echo ' | ' . sprintf( __( 'Page %s', 'tnb' ), max( $paged, $page ) );
  ?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
  if ( is_singular() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );
  wp_head();
?>
</head>

<body <?php body_class(); ?>>
  <div id="content" class="container">
    <div class="span-9">
      <?php theme_image("toquenobrasil2.png", array("id" => "toquenobrasil", "class" => "prepend-1")); ?>
    </div>
    <div class="prepend-1 span-14 last">
      <?php wp_nav_menu(array("theme_location" => "main",
                              "container" => "div",
                              "container_id" => "nav-main",
                              "before" => "<span class='title'>",
                              "after" => "<span class='shadow'></span></span>",
                              "link_before" => "<h1>",
                              "link_after" => "</h1>"
                             )
      ) ?>
      <div class="clear"></div>
    </div>

    <div class="clear"></div>