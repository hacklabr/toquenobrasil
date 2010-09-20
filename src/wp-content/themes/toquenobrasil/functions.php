<?php

define('TNB_URL', get_bloginfo('url') . strstr(dirname(__FILE__), '/wp-content') );

# INCLUDES
include(TEMPLATEPATH . '/includes/image.php');
include(TEMPLATEPATH . '/includes/tnb_comment.php');
include(TEMPLATEPATH . '/includes/post_types.php');

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

// POST THUMBNAILS
add_theme_support('post-thumbnails');
set_post_thumbnail_size( 150, 130, true );
add_image_size( 'eventos', 150, 130, true );

function itsnoon_url_rewirtes($wp_rewrite) {
    $new_rules = array(
        // rules for Calls
		"cadastre-se/(produtor|artista)$" => 'index.php?tpl=register&reg_type=' . $wp_rewrite->preg_index(1),
    	"editar/(produtor|artista)$" => 'index.php?tpl=edit&reg_type=' . $wp_rewrite->preg_index(1),
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action('generate_rewrite_rules', 'itsnoon_url_rewirtes');

function itsnoon_query_vars($public_query_vars) {
	$public_query_vars[] = "tpl";
	$public_query_vars[] = "reg_type";
	
	return $public_query_vars;
}
add_filter('query_vars', 'itsnoon_query_vars');

add_action('template_redirect', 'template_redirect_intercept');
function template_redirect_intercept(){
    global $wp_query;
    
    switch ( $wp_query->get('tpl') ) {
        case 'register':
            if (file_exists( TEMPLATEPATH . '/register.php' )) {
                include( TEMPLATEPATH . '/register.php' );
                exit;
            }
        break;
        case 'edit':
            if (file_exists( TEMPLATEPATH . '/profile.php' )) {
                include( TEMPLATEPATH . '/profile.php' );
                exit;
            }
        break;
    }
}

function get_media_file_sizes($filename) {
	// include getID3() library (can be in a different directory if full path is specified)

	require_once(TEMPLATEPATH.'/lib/getid3/getid3.php');

	// Initialize getID3 engine
	$getID3 = new getID3;

	// Analyze file and store returned data in $ThisFileInfo
	$ThisFileInfo = $getID3->analyze($filename);

	#print_r ($ThisFileInfo); die;
	return array('playtime' => $ThisFileInfo['playtime_string'], 'filesize' => $ThisFileInfo['filesize']);
}

function print_msgs($msg){
    if(!is_array($msg))
        return false;
        
        
    foreach($msg as $type=>$msgs){
        echo "<div class='$type'><ul>";
            if(!is_array($msgs)){
                echo "<li>$msgs</li>";
            }else{    
                foreach ($msgs as $m){
                    echo "<li>$m</li>";
                }
             }    
        echo "</ul></div>";
    }
    
}

function add_tnb_roles(){
     $opt  = get_option("role_defined", false);
    
     if( ( (!$opt  || $opt == 0 ) && current_user_can('add_users'))){
         global $wp_roles;
         
         $wp_roles->add_role( 'artista', 'Artista', array('read','publish_posts'));
         $wp_roles->add_role( 'produtor', 'Produtor', array('read','publish_posts', 'create_event', 'publish_event'));
         
         update_option('default_role', 'artista');
         
         update_option('role_defined','1');
          
     }
}
add_action('init', 'add_tnb_roles', 2);



function login_error_redirect($url, $redirect_to, $user){
//    var_dump($_POST, $url, $redirect_to, $user);
    if( ($_POST['user_login']  ||  $_POST['log'] ) &&  is_wp_error($user)){
        $er_flag = ( strpos($redirect_to,'?')===FALSE ? "?" : "&" ) . 'login_error=1';
        $site_url = get_bloginfo('url') . $redirect_to . $er_flag;
        wp_safe_redirect($site_url);
        die;    
    }
    return $url;
}
add_filter('login_redirect', 'login_error_redirect', 10, 3);

function new_signup_location($url){
    return get_bloginfo('url');
}
add_filter('wp_signup_location','new_signup_location');


function send_mail_contact_us(){
    extract($_POST);
    
    $to = get_bloginfo('admin_email');
    $subject = __('Contact from site','itsnoon');
    
    $message = __(sprintf("%s sent a message to you from Itsnoon potal:
    Name: %s
    email: %s
    site: %s
    message: %s
    ", $contact_name, $contact_name, $contact_email, $contact_site, $contact_message) ,'itsnoon');    
    
    wp_mail($to, $subject, $message);    

    
}
if($_POST['contact_us'])
    send_mail_contact_us();

    
function get_estados(){
    
    $estados = array(
        "ac"=>"Acre",
        "al"=>"Alagoas",
        "ap"=>"Amapá",
        "am"=>"Amazonas",
        "ba"=>"Bahia",
        "ce"=>"Ceará",
        "df"=>"Distrito Federal",
        "es"=>"Espirito Santo",
        "go"=>"Goiás",
        "ma"=>"Maranhão",
        "ms"=>"Mato Grosso do Sul",
        "mt"=>"Mato Grosso",
        "mg"=>"Minas Gerais",
        "pa"=>"Pará",
        "pb"=>"Paraíba",
        "pr"=>"Paraná",
        "pe"=>"Pernambuco",
        "pi"=>"Piauí",
        "rj"=>"Rio de Janeiro",
        "rn"=>"Rio Grande do Norte",
        "rs"=>"Rio Grande do Sul",
        "ro"=>"Rondônia",
        "rr"=>"Roraima",
        "sc"=>"Santa Catarina",
        "sp"=>"São Paulo",
        "se"=>"Sergipe",
        "to"=>"Tocantins",
    );
    return $estados;   
}
?>