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
  <div id="toquenobrasil" class="container prepend-top">
    <?php theme_image("toquenobrasil2.png"); ?>
  </div>
  
  <div id="content" class="container">
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
    
    <div class="span-9 prepend-1">
      <div class="item green">
        <div class="title">
          <div class="shadow"></div>
          <h1 class="blue">Artistas</h1>
          <div class="clear"></div>
        </div>
        <div class="background">
          <p>Ac rhoncus aliquam amet quis pulvinar augue pulvinar augue velit! In? Integer pid auctor enim a nunc ridiculus et massa scelerisque ultricies dictumst arcu porta, ut, etiam pellentesque vut integer, pellentesque amet nisi, ut tincidunt scelerisque, purus mattis porta dictumst penatibus, pid, aliquam et? In, proin dictumst ac? Augue porttitor facilisis elementum, dolor mid et egestas magna urna? Aenean tincidunt lorem ac risus porta sit et, augue quis facilisis sit turpis! A? Integer placerat ultricies dignissim, in facilisis enim! Vel.</p>          
        </div>
      </div>

      <div class="item blue">
        <div class="title">
          <div class="shadow"></div>
          <h1>Produtores</h1>
          <div class="clear"></div>
        </div>
        <div class="background">
          <p>Ac rhoncus aliquam amet quis pulvinar augue pulvinar augue velit! In? Integer pid auctor enim a nunc ridiculus et massa scelerisque ultricies dictumst arcu porta, ut, etiam pellentesque vut integer, pellentesque amet nisi, ut tincidunt scelerisque, purus mattis porta dictumst penatibus, pid, aliquam et? In, proin dictumst ac? Augue porttitor facilisis elementum, dolor mid et egestas magna urna? Aenean tincidunt lorem ac risus porta sit et, augue quis facilisis sit turpis! A? Integer placerat ultricies dignissim, in facilisis enim! Vel.</p>
        </div>
      </div>
    </div>
    
    <div class="prepend-1 span-13 last">
      <div class="item yellow">
        <div class="title">
          <div class="shadow"></div>
          <h1>Assista</h1>
          <div class="clear"></div>
        </div>
        <div class="background">
          <object width="469" height="264"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=10280242&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=10280242&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="469" height="264"></embed></object>
          <div class="prepend-top"></div>
          <p>Ac rhoncus aliquam amet quis pulvinar augue pulvinar augue velit! In? Integer pid auctor enim a nunc ridiculus et massa scelerisque ultricies dictumst arcu porta, ut, etiam pellentesque vut integer, pellentesque amet nisi, ut tincidunt scelerisque, purus mattis porta dictumst penatibus, pid, aliquam et? In, proin dictumst ac? Augue porttitor facilisis elementum, dolor mid et egestas magna urna? Aenean tincidunt lorem ac risus porta sit et, augue quis facilisis sit turpis! A? Integer placerat ultricies dignissim, in facilisis enim! Vel.</p>
        </div>
      </div>
    </div>
    
    
    <div class="clear"></div>
    <div class="arrow"></div>