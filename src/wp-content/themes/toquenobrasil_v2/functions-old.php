<?php

//var_dump(current_user_can('select_other_artists'));

define('TNB_URL', get_bloginfo('url') . strstr(dirname(__FILE__), '/wp-content') );

# INCLUDES
include(TEMPLATEPATH . '/includes/theme-options.php');
include(TEMPLATEPATH . '/includes/user-photo.php');
include(TEMPLATEPATH . '/includes/image.php');
include(TEMPLATEPATH . '/includes/list-control.php');
include(TEMPLATEPATH . '/includes/tnb_comment.php');
include(TEMPLATEPATH . '/includes/post_types.php');
include(TEMPLATEPATH . '/widgets/ultimas_bandas.php');
include(TEMPLATEPATH . '/widgets/ultimos_eventos.php');
include(TEMPLATEPATH . '/widgets/busca.php');
include(TEMPLATEPATH . '/includes/sqls.php');
include(TEMPLATEPATH . '/includes/admin_export_users.php');
include(TEMPLATEPATH . '/includes/admin_email_messages.php');
include(TEMPLATEPATH . '/includes/admin_system_messages.php');
include(TEMPLATEPATH . '/includes/email_messages.php');
include(TEMPLATEPATH . '/includes/admin_help_videos.php');



// interface para arrumar usuários que não estão com dados de país, estado e cidade corretos
include(TEMPLATEPATH . '/includes/admin_fix_usuarios_cidades.php');






# JAVASCRIPTS

add_action('wp_print_scripts', 'tnb_load_js');
function tnb_load_js() {
  if ( !is_admin() ) {
    wp_enqueue_script('scrollTo_js', TNB_URL . '/js/jquery.scrollTo-min.js', array('jquery'));
    wp_enqueue_script('tnb_js', TNB_URL . '/js/tnb.js', array('jquery', 'jquery-ui-dialog'));
    wp_localize_script('tnb_js', 'params', array('base_url' => get_bloginfo('stylesheet_directory')));
  }
}

function add_adm_js(){
    wp_enqueue_script('jquery');
    wp_enqueue_script('datepicker_js', TNB_URL . '/js/ui.datepicker.js', array('jquery'));
    wp_enqueue_script('datepicker_br_js', TNB_URL . '/js/jquery.ui.datepicker-pt-BR.js', array('datepicker_js'));
    wp_enqueue_style('jquery_ui', TNB_URL . '/css/jquery-ui-css/ui-lightness/jquery-ui-1.7.2.custom.css');
    wp_enqueue_script('tnb_adm_js', TNB_URL . '/js/tnb_adm.js', array('jquery','datepicker_br_js'), 12);
    wp_localize_script('tnb_adm_js', 'params', array('base_url' => get_bloginfo('stylesheet_directory')));
}
add_action('admin_init', 'add_adm_js');
add_action('wp_print_styles', 'custom_load_css');

function custom_load_css() {
    wp_enqueue_style('jquery-ui', TNB_URL . '/css/jquery-ui-css/ui-lightness/jquery-ui-1.7.2.custom.css');
}




# REGISTERING MENUS
register_nav_menus( array(
                          'main' => __('Menu Principal', 'tnb'),
                          'bottom' => __('Menu Inferior', 'tnb'),
                          )
                    );











/*
print_video_thumbnail("http://www.youtube.com/watch?v=5f-MYl-HzNw");
print_video_thumbnail('http://vimeo.com/8572290');

print_video_player("http://www.youtube.com/watch?v=5f-MYl-HzNw");
print_video_player("http://vimeo.com/8572290");
*/






// Tamanho customizado de imagens
add_image_size('banner-horizontal',550,150,false);






//include(TEMPLATEPATH . '/includes/update-actions.php');
?>
