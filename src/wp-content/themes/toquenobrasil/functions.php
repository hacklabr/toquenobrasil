<?php



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


add_filter( 'author_link', 'tnb_author_link', 10, 3);

function tnb_author_link($link, $author_id, $author_nicename) {
    return get_bloginfo('url') . '/rede/' . $author_nicename;
}

//header da página de login do wordpress
add_action('login_head', 'tnb_login_head');

if (!function_exists('tnb_login_head')) {
	function tnb_login_head() {
		?>
		<style>
			#login {width: 400px;}
			#login h1 {}
			#login h1 a {background-image: url(<?php echo get_theme_image('toquenobrasil2.png'); ?> ); height: 133px; width: 400px;}
		</style>
		
		<?php
	}
}

# JAVASCRIPTS
add_action('wp_print_scripts', 'tnb_load_js');
function tnb_load_js() {
  if ( !is_admin() ) {
    wp_enqueue_script('scrollTo_js', TNB_URL . '/js/jquery.scrollTo-min.js', array('jquery'));
    wp_enqueue_script('tnb_js', TNB_URL . '/js/tnb.js', array('jquery', 'jquery-ui-dialog'));
  }    
}
function add_adm_js(){
    wp_enqueue_script('jquery');
    wp_enqueue_script('datepicker_js', TNB_URL . '/js/ui.datepicker.js', array('jquery'));
    wp_enqueue_script('datepicker_br_js', TNB_URL . '/js/jquery.ui.datepicker-pt-BR.js', array('datepicker_js'));
    wp_enqueue_style('jquery_ui', TNB_URL . '/css/jquery-ui-css/ui-lightness/jquery-ui-1.7.2.custom.css');
    wp_enqueue_script('tnb_adm_js', TNB_URL . '/js/tnb_adm.js', array('jquery','datepicker_br_js'), 12);
}
add_action('admin_init', 'add_adm_js');
add_action('wp_print_styles', 'custom_load_css');

function custom_load_css() {
    wp_enqueue_style('jquery-ui', TNB_URL . '/css/jquery-ui-css/ui-lightness/jquery-ui-1.7.2.custom.css');
}

if (!function_exists('tnb_mail_name')) {
    function tnb_mail_name($from_name){
        return __('Toque no Brasil', 'tnb');
    }
}
    
    
function tnb_mail_email($from_email){
    return get_bloginfo('admin_email');
}
add_filter( 'wp_mail_from' , 'tnb_mail_email');
add_filter( 'wp_mail_from_name', 'tnb_mail_name'  );


# REGISTERING MENUS
register_nav_menus( array(
                          'main' => __('Menu Principal', 'tnb'),
                          'bottom' => __('Menu Inferior', 'tnb'),
                          )
                    );

// REGISTERING WIDGETS
add_action( 'widgets_init', 'tnb_widgets_init' );

function tnb_widgets_init() {
    // Blog
  register_sidebar( array(
                          'name' => __( "Blog's sidebar", "tnb" ),
                          'id' => "blog-sidebar",
                          'description' => __( "Sidebar do blog" ),
                          'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                          'after_widget' => '</div>',
                          'before_title'  => '<div class="title clearfix"><div class="shadow"></div><h2 class="widgettitle">',
                          'after_title'   => '</h2></div>'
  ) );
  register_sidebar( array(
                          'name' => __('Sidebar', 'tnb'),
                          'id' => 'tnb-sidebar',
                          'description' => __('Sidebar das páginas internas'),
                          'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                          'after_widget' => '</div>',
                          'before_title'  => '<div class="title clearfix"><div class="shadow"></div><h2 class="widgettitle">',
                          'after_title'   => '</h2></div>'
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
set_post_thumbnail_size( 150, 150, true );
add_image_size( 'eventos', 150, 150, true );

function custom_url_rewrites($wp_rewrite) {
    $new_rules = array(
        // rules for Calls
        "rede/([^/]+)/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1),
    	"cadastre-se/(produtor|artista)$" => 'index.php?tpl=register&reg_type=' . $wp_rewrite->preg_index(1),
    	"editar/(produtor|artista)$" => 'index.php?tpl=edit&reg_type=' . $wp_rewrite->preg_index(1),
        "(artistas|produtores)(/page/?([0-9]{1,}))?/?$" => 'index.php?tpl=list_author&reg_type='. $wp_rewrite->preg_index(1). '&paged=' . $wp_rewrite->preg_index(3),
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action('generate_rewrite_rules', 'custom_url_rewrites');

function custom_query_vars($public_query_vars) {
	$public_query_vars[] = "tpl";
	$public_query_vars[] = "reg_type";
	
	return $public_query_vars;
}
add_filter('query_vars', 'custom_query_vars');

//evitando usuario de se registrar pelo wp-login
add_filter('init','register_block_redirect');
function register_block_redirect() {
    if ( preg_match('/(action=register)/', $_SERVER['REQUEST_URI'] ) )
        wp_redirect(get_bloginfo('url'));
}


add_action('template_redirect', 'template_redirect_intercept');
function template_redirect_intercept(){
    
    
    if( $_GET['action'] ==  'register'){
       die; 
    }
    
    global $wp_query;
    $reg_type = $wp_query->get('reg_type');
    switch ( $wp_query->get('tpl') ) {
        case 'register':
            if (file_exists( STYLESHEETPATH . '/register.php' )) {
                include( STYLESHEETPATH . '/register.php' );
                exit;
            } elseif (file_exists( TEMPLATEPATH . '/register.php' )) {
                include( TEMPLATEPATH . '/register.php' );
                exit;
            }
        break;
        case 'edit':
            if (file_exists( STYLESHEETPATH . '/profile.php' )) {
                include( STYLESHEETPATH . '/profile.php' );
                exit;
            } elseif (file_exists( TEMPLATEPATH . '/profile.php' )) {
                include( TEMPLATEPATH . '/profile.php' );
                exit;
            }

        break;
        case 'list_author':
            if (file_exists( TEMPLATEPATH . "/list-{$reg_type}.php" )) {
                include( TEMPLATEPATH . "/list-{$reg_type}.php" );
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

function print_msgs($msg, $extra_class='', $id=''){
    if(!is_array($msg))
        return false;
        
        
    foreach($msg as $type=>$msgs){
        echo "<div class='$type $extra_class' id='$id'><ul>";
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
         $wp_roles->add_role( 'produtor', 'Produtor', array('read','publish_posts', 'create_event', 'publish_event', 'select_artists'));
         
         $adm = get_role('administrator');
        
		 $adm->add_cap( 'select_other_artists' );
         
         update_option('default_role', 'artista');
         
         update_option('role_defined','1');
          
     }
}
add_action('init', 'add_tnb_roles', 2);



function login_error_redirect($url, $redirect_to, $user){
//    var_dump($_POST, $url, $redirect_to, $user);
    if(strpos($redirect_to, 'wp-admin') > 0)
        return $url;
    
    if( ($_POST['user_login']  ||  $_POST['log'] ) &&  is_wp_error($user) ){
        $er_flag = ( strpos($redirect_to,'?')===FALSE ? "?" : "&" ) . 'login_error=1';
        
        // Aqui achei um erro. Alan, me diga se tem necessidade do get_bloginfo('url');
        // $site_url = get_bloginfo('url') . $redirect_to . $er_flag;
        // Esta é a linha nova:
        $site_url = $redirect_to . $er_flag;
        
        
        wp_safe_redirect($site_url);
        die;    
    }
    return $url;
}
add_filter('login_redirect', 'login_error_redirect', 10, 3);

function check_email_confirm($user_login){
    $user = get_user_by('login', $user_login);
    if( get_usermeta($user->ID, 'tnb_inactive', true) && !isset($user->wp_capabilities['administrator'])){
        $type =  isset($user->wp_capabilities['produtor']) ? 'produtor' : 'artista'; 
        $er_flag = ( strpos($redirect_to,'?')===FALSE ? "?" : "&" ) . 'email_confirm=' . $type ;
        wp_logout();
        $site_url = get_bloginfo('url') . $redirect_to . $er_flag;
        wp_safe_redirect($site_url);
        die;
    }
}
add_action('wp_login', 'check_email_confirm');

function new_signup_location($url){
    return get_bloginfo('url');
}
add_filter('wp_signup_location','new_signup_location');


function send_mail_contact_us(){
    extract($_POST);
    
    
    $to = ( !filter_var($v = get_theme_option('email_contato'), FILTER_VALIDATE_EMAIL) ? get_bloginfo('admin_email') : $v);
    
    $subject = __('Contact from site','tnb');
    
    if(!filter_var( $contact_email, FILTER_VALIDATE_EMAIL))
        $msg['error'][] = __('E-mail informado inválido.','tnb');    

    if(sizeof($msg['error'])==0){    
        $message = __(sprintf("%s enviou uma mensagem para você através do " . get_bloginfo('name') . ":
        Nome: %s
        e-mail: %s
        site: %s
        menssagem: %s \r\r", $contact_name, $contact_name, $contact_email, $contact_site, $contact_message) ,'tnb');    
        
        if(is_user_logged_in()){
            global $current_user;
            $link = get_author_posts_url($current_user->ID); 
$message.=" - Usuário que enviou esta mensagem -
	Banda: {$current_user->banda}
	email: {$current_user->user_email}
	Responsável: {$current_user->responsavel}
	link: {$link}            
\r\r";
        }
        
        $message.="Página de onde foi enviada a mensagem: \r\r" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "\n";
        
        
        wp_mail($to, $subject, $message);
        
        return array('success'=>__('Sua mensagem foi enviada com sucesso','tnb'));
        
    }else{
        return $msg;
    }
}
function contact_us(){
    global $contact_us_return;
    if(isset($_POST['contact_us']))
        $contact_us_return = send_mail_contact_us();
}
add_action('wp', 'contact_us');
    
function get_estados(){
    
    $estados = array(
        ""=>"Selecione",
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

function print_audio_player($post_id){
        global $TanTanWordPressS3Plugin;
        
        $fileURL = get_option('siteurl') . '/wp-content/uploads/';
        $fileURL .= get_post_meta($post_id, "_wp_attached_file", true );
        
        if (is_object($TanTanWordPressS3Plugin))
            $fileURL = $TanTanWordPressS3Plugin->wp_get_attachment_url($fileURL, $post_id);
        
        $playerURL = get_option('siteurl').'/wp-content/plugins/audio-player/assets';

    ?>
    
    <!-- //PLAYER de áudio-->
        <script
        	language="JavaScript" src="<?php echo $playerURL;?>/audio-player.js"></script>
        <object type="application/x-shockwave-flash"
        	data="<?php echo $playerURL;?>/player.swf" id="audioplayer1"
        	class="audioplayer" height="24" width="140" style="visibility: visible">
        	<param name="movie" value="<?php echo $playerURL;?>/player.swf">
        	<param name="FlashVars"
        		value="playerID=1&amp;soundFile=<?php echo $fileURL; ?>">
        	<param name="quality" value="high">
        	<param name="menu" value="false">
        	<param name="wmode" value="transparent">
        </object>
    
    <?php 
}


function get_artistas( $limit = false, $order=false) {
        global $wpdb;
        
        if(!$order)
        	$order = "ID";
        if(is_numeric($limit))
            $limit = "LIMIT $limit";
        elseif(!$limit)
            $limit = '';
            	
        $prefix = $wpdb->prefix;
        $role = 'artista';
        
        $q = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '{$prefix}capabilities' AND meta_value LIKE '%\"$role\"%' ORDER BY $order";
        $not_q = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'tnb_inactive' AND meta_value = 1";
        $query = "SELECT * FROM {$wpdb->users} WHERE ID IN($q) AND ID NOT IN ($not_q) ORDER BY $order $limit";
//        echo $query ;
        $users = $wpdb->get_results($query);
        return $users;
}


function is_artista(){
    if( is_user_logged_in() ){
        global $current_user;
        return in_array('artista' ,$current_user->roles);
    }
    return false;
}

function in_postmeta($meta,$value){
    if(!is_array($meta))
        return false;
    return in_array($value,$meta);
}
function is_blog(){
    global $in_blog;
    return $in_blog;
}

/////////////  Alow admin chage e-mail verification flag
function email_confirm_display_selector_fieldset(){
    global $profileuser;
    
    $inactive = get_usermeta($profileuser->ID, 'tnb_inactive');
    $checked = (!$inactive ? 'checked' : '' ); 
    ?> 
    <h3>Status do usuário</h3>
    <p>
    Ativo: <input type="checkbox"  name='active' <?php echo $checked; ?> />
    </p>
    <br /><br /><br />
    
    <?php 
    
}
add_action('show_user_profile', 'email_confirm_display_selector_fieldset', 2);
add_action('edit_user_profile', 'email_confirm_display_selector_fieldset', 2);
function email_confirm_profile_update($userID){
    
    if(is_admin()){
        if(!isset($_POST['active'])){
            add_user_meta($userID, 'tnb_inactive' , true);
        }else{
            delete_user_meta($userID, 'tnb_inactive');    
        }
    }
}
add_action('profile_update', 'email_confirm_profile_update');

function edit_btn($title, $id){
    
    if(current_user_can('edit_post') && !is_admin()){
        
        $post = get_post($id); 
//        var_dump($post); 
//        capability_type
        
//        var_dump(get_post_type_object( $post->post_type ));
//        die;
        if($post->post_type == 'post' || $post->post_type == 'eventos')
            $title.= " <a class='edit-post-link' href='" . get_edit_post_link( $id ) . "' target='_blank'>editar</a>"; 
    }    

    return $title;    
}

add_filter('the_title', 'edit_btn', 10, 2);
function delete_user_from_events($user_id){
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_value = {$user_id} AND meta_key='inscrito'");
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_value = {$user_id} AND meta_key='selecionado'");
}
add_action('delete_user', 'delete_user_from_events');





function toquenobrasil_sanitize_file_name($filename) {
    #return $filename;
    $ext = substr(strrchr($filename,'.'),1);
    $filename = substr($filename, 0, strlen($filename) -4);
	$filename = sanitize_title(remove_accents($filename));
    $filename = str_replace('%', '', $filename);
    return $filename . '.' . $ext;    
}


function toquenobrasil_delete_item($itemId, $postType) {
	global $wpdb;

	// Preparamos o post para a função wp_delete_post()
	// Nela, se um post tiver o type attachment, ela apaga também o arquivo no sistema de arquivos
	// não adianta chamar a função wp_delete_attachment direto porque ela verifica o post_type
	if ($postType == 'music' || $postType == 'rider' || $postType == 'images' || $postType == 'mapa_palco') {

		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->posts} SET post_type = 'attachment' WHERE ID = %d", $itemId));
	}
    
	wp_delete_post((int) $itemId);
}


/*
print_video_thumbnail("http://www.youtube.com/watch?v=5f-MYl-HzNw");
print_video_thumbnail('http://vimeo.com/8572290');

print_video_player("http://www.youtube.com/watch?v=5f-MYl-HzNw");
print_video_player("http://vimeo.com/8572290");
*/

function print_video_player($video_url, $width='300', $height="200"){
    
      if(preg_match("/\/watch\?v=/", $video_url) ) {
            
            $videoUrl = preg_replace("/\/watch\?v=/", "/v/" ,$video_url);
                
          ?>
          <object width='<?php echo $width; ?>' height='<?php echo $height; ?>' data='<?php echo $videoUrl; ?>?fs=1&amp;hl=en_US&amp;rel=0'>
            <param name='allowScriptAccess' value='always'/>
            <param name='allowFullScreen' value='True'/>
            <param name='movie' value='<?php echo $videoUrl; ?>&autoplay=0&border=0&showsearch=0&enablejsapi=1&playerapiid=ytplayer&fs=1'></param>
            <param name='wmode' value='transparent'></param>
            <embed src='<?php echo $videoUrl; ?>&autoplay=0&border=0&showsearch=0&fs=1' type='application/x-shockwave-flash' wmode='transparent' width='<?php echo $width; ?>' height='<?php echo $height; ?>' allowfullscreen='1'></embed>
          </object>
          <?php 
      }elseif(preg_match("|(vimeo.com)|",$video_url)){
          preg_match("/http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/",  $video_url, $out);
          $vimeo_id = $out[2];
          ?>
            <iframe src="http://player.vimeo.com/video/<?php echo $vimeo_id;?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0"></iframe>
            
            
          <?php 
      }
    
}

function print_video_thumbnail($video_url, $size = 'small'){
    parse_str(parse_url($video_url,PHP_URL_QUERY), $vars);
    if( preg_match("|(youtube.com)|",$video_url ) &&  isset($vars['v'])){
        $y_sizes['large'] =  0;
        $y_sizes['medium'] =  0;
        $y_sizes['small'] =  'default';
        $src = "http://img.youtube.com/vi/{$vars['v']}/{$y_sizes[$size]}.jpg";
    }elseif(preg_match("|(vimeo.com)|",$video_url ) ){
        preg_match("/http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/",  $video_url, $out);
        $vimeo_id = $out[2];

        $api_endpoint = 'http://www.vimeo.com/api/v2/video/'.$vimeo_id.'.xml';
        
        $curl = curl_init($api_endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $str_xml = curl_exec($curl);
        curl_close($curl);

        
        $xml  = simplexml_load_string($str_xml);
        $src = $xml->video->{'thumbnail_' . $size};
    }
    echo "<img src='$src' />";
}

?>
