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
            <script src="<?php bloginfo('stylesheet_directory'); ?>/js/html5.js" type="text/javascript" charset="utf-8"></script>
        <![endif]-->
        <!--[if IE 7]>
            <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie7.css" />
        <![endif]-->

        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link type="image/x-icon" href="<?php echo get_theme_image('favicon.ico'); ?>" rel="shortcut icon" />

        <?php
            if ( is_singular() && get_option( 'thread_comments' ) )
                wp_enqueue_script( 'comment-reply' );
            wp_head();
        ?>

    </head>

    <body <?php body_class(); ?>>
        <div id="wrapper" class="container_16 clearfix">
            <?php get_template_part('header-nav'); ?>
            <?php if ( WPEB_countBanners('top') ) : ?>
                <div class="header-banner grid_16 box-shadow">
                    <div class="box">
                        <?php WPEB_printBanner('top');?>
                    </div>
                </div>
                <!-- #header-banner -->
            <?php endif; ?>        
            
