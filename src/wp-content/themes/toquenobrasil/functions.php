<?php

define('TNB_URL', get_bloginfo('url') . strstr(dirname(__FILE__), '/wp-content') );

# Includes
include(TEMPLATEPATH . '/includes/image.php');

# Javascripts
add_action('wp_print_scripts', 'tnb_load_js');
function tnb_load_js() {
  wp_enqueue_script('cufon_yui', TNB_URL . '/js/cufon-yui.js');
  wp_enqueue_script('arista20-font', TNB_URL . '/js/arista20.font.js');
  wp_enqueue_script('tnb_js', TNB_URL . '/js/tnb.js', array('jquery'));
}

# Registering menus
register_nav_menus( array(
                          'main' => __('Menu Principal', 'tnb'),
                          )
                    );

?>