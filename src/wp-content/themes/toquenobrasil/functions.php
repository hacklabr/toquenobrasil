<?php

define('TNB_URL', get_bloginfo('url') . strstr(dirname(__FILE__), '/wp-content') );

# INCLUDES
include(TEMPLATEPATH . '/includes/image.php');
include(TEMPLATEPATH . '/includes/tnb_comment.php');

# JAVASCRIPTS
add_action('wp_print_scripts', 'tnb_load_js');
function tnb_load_js() {
  if ( !is_admin() ) {
    wp_enqueue_script('cufon_yui', TNB_URL . '/js/cufon-yui.js');
    wp_enqueue_script('arista20-font', TNB_URL . '/js/arista20.font.js');
    wp_enqueue_script('tnb_js', TNB_URL . '/js/tnb.js', array('jquery'));    
  }
}


# REGISTERING MENUS
register_nav_menus( array(
                          'main' => __('Menu Principal', 'tnb'),
                          'bottom' => __('Menu Inferior', 'tnb'),
                          )
                    );

// REGISTERING WIDGETS
add_action( 'widgets_init', 'tnb_widgets_init' );

function tnb_widgets_init() {
  register_sidebar( array(
                          'name' => __('Sidebar', 'tnb'),
                          'id' => 'blog',
                          'description' => __('Sidebar das páginas internas'),
                          'before_title'  => '<div class="title"><div class="shadow"></div><h2 class="widgettitle">',
                          'after_title'   => '</h2><div class="clear"></div></div>'
  ) );
  register_sidebar( array(
                          'name' => __('Rodapé', 'tnb'),
                          'id' => 'rodape',
                          'description' => __('Sidebar do rodapé'),
						  'before_widget' => '',
						  'after_widget' => ''                         
  ) );
}

?>