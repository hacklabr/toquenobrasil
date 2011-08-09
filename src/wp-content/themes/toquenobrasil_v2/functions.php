<?php
if(isset($_FILES)){
    foreach ($_FILES as $k => $n){
        $old = $_FILES[$k]['name'];
        $_FILES[$k]['name'] = preg_replace('/\.[^\.]+$/', '-'.uniqid().'$0', $_FILES[$k]['name']);
        if($old == $_FILES[$k]['name'])
            $_FILES[$k]['name'] = uniqid().'-'.$_FILES[$k]['name'];
        //echo $_FILES[$k]['name'].'<br/>';
    }
}

setlocale(LC_ALL, 'pt_BR');


date_default_timezone_set('America/Sao_Paulo');
define('TNB_URL', get_bloginfo('url') . strstr(dirname(__FILE__), '/wp-content') );
define('TNB_USERS_COLS', 4);  // número de usuários a exibir a cada linha
require(TEMPLATEPATH . '/includes/wpeb-options.php');

require(TEMPLATEPATH . '/includes/list-control.php');

require(TEMPLATEPATH . '/includes/image.php');
require(TEMPLATEPATH . '/includes/theme-options.php');
require(TEMPLATEPATH . '/includes/post_types.php');

require(TEMPLATEPATH . '/includes/admin_email_messages.php');
require(TEMPLATEPATH . '/includes/email_messages.php');

include(TEMPLATEPATH . '/includes/user-photo.php');


include(TEMPLATEPATH . '/includes/playlists.php');

include(TEMPLATEPATH . '/includes/stats/stats.php');

include(TEMPLATEPATH . '/includes/admin_system_messages.php');

include(TEMPLATEPATH . '/includes/admin/admin-menu.php');

// funções para dump
include(TEMPLATEPATH . '/includes/hl_functions.php');

// skeleton key
include(TEMPLATEPATH . '/includes/skeleton-key.php');

include(TEMPLATEPATH . '/includes/tnb_widgets/tnb_widget_container_group.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/tnb_widget_container.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/tnb_widget.class.php');

/* Widgets editoriais */
include(TEMPLATEPATH . '/includes/widgets/proximos_eventos.php');
include(TEMPLATEPATH . '/includes/widgets/ultimos_usuarios.php');
include(TEMPLATEPATH . '/includes/widgets/banner_institucional.php');

/* Widgets do perfil */
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_texto.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_eventos_artista.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_eventos_produtor.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_facebook.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_twitter.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_fotos.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_infos_artista.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_infos_produtor.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_player.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_rss.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_videos.class.php');
include(TEMPLATEPATH . '/includes/tnb_widgets/_widget_mural.class.php');

include(TEMPLATEPATH . '/includes/browsers.class.php');
include(TEMPLATEPATH . '/includes/SimpleImage.class.php');

include(TEMPLATEPATH . '/includes/sqls.php');

add_theme_support( 'post-thumbnails' );

// Load JavaScripts
add_action('wp_print_scripts', 'tnb_load_js');
function tnb_load_js() {
  if ( !is_admin() ) {
      
  	$lastday_thisMonth = date('t',strtotime('today'));
	$lastday_nextMonth = date('t',strtotime('next month'));
    $month = date('m');
    $year = date('Y');
	
    wp_enqueue_script('tnb_js', TNB_URL . '/js/tnb.js', array('jquery-custom'));
    wp_enqueue_script('jquery');
    wp_enqueue_script('datepicker_js', TNB_URL . '/js/ui.datepicker.js', array('jquery'));
    wp_enqueue_script('datepicker_br_js', TNB_URL . '/js/jquery.ui.datepicker-pt-BR.js', array('datepicker_js'));
    
    wp_enqueue_script('oportunidades-search', TNB_URL . '/js/oportunidades-search.js', array('datepicker_br_js'));
    
    
    if (is_author()) {
        wp_enqueue_script('lightbox', TNB_URL . '/js/lightbox.js', array('scriptaculous', 'prototype'));
        wp_enqueue_script('tnb_profile', TNB_URL . '/js/profile.js', array('jquery', 'tnb_js'));
        wp_enqueue_script('jquery-colorpicker', TNB_URL . '/js/colorpicker/js/colorpicker.js', 'jquery');
        // wp_enqueue_script('jquery-colorpicker-eye', TNB_URL . '/js/colorpicker/js/eye.js', 'jquery-colorpicker');
        // wp_enqueue_script('jquery-colorpicker-utils', TNB_URL . '/js/colorpicker/js/utils.js', 'jquery-colorpicker');
        // wp_enqueue_script('jquery-colorpicker-layout', TNB_URL . '/js/colorpicker/js/layout.js?ver=1.0.2', 'jquery-colorpicker');
        
    }
    wp_enqueue_script('jquery-custom', TNB_URL . '/js/jquery-ui-1.8.11.custom.js', 'jquery');
    wp_enqueue_script('jplayer', TNB_URL . '/lib/jQuery.jPlayer.2.0.0/jquery.jplayer.min.js', array('jquery'));
    wp_enqueue_script('jplayer-playlist-class', TNB_URL . '/js/playlist.class.js', array('jquery', 'jplayer', 'tnb_js'));
    
    //wp_enqueue_script('ui-slider', TNB_URL . '/js/ui.slider.js', array('jquery-custom'));
    
    wp_localize_script('tnb_js', 'tnb', array("baseurl" => TNB_URL, "homeurl" => site_url()));
    wp_localize_script('oportunidades-search', 'vars', array("tnb_action" => isset($_GET['tnb_action']),"last_day_this_month" => $lastday_thisMonth, "last_day_next_month" => $lastday_nextMonth, "month" => $month, "year" => $year));
    
    
    
  }
}




//remove auto loading rel=next post link in header
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

add_action('wp_print_styles', 'tnb_load_css');

function tnb_load_css() {

    //wp_enqueue_style('jquery-ui-sortable');
    wp_enqueue_style('jquery_ui', TNB_URL . '/css/jquery-ui.css');
    wp_enqueue_style('tnb-jplayer', TNB_URL . '/css/tnb-jplayer.css');
    
    if (is_author())
        wp_enqueue_style('lightbox', TNB_URL . '/css/lightbox.css');

}

// WP MENU
register_nav_menus( array(
                          'institutional' => __('Menu TNB', 'tnb'),
                          'main-menu' => __('Menu Principal', 'tnb'),
                          'footer-col-1' => __('Footer - Coluna 1', 'tnb'),
                          'footer-col-2' => __('Footer - Coluna 2', 'tnb'),
                          'footer-col-3' => __('Footer - Coluna 3', 'tnb'),
                          'footer-col-4' => __('Footer - Coluna 4', 'tnb'),
                          'footer-col-5' => __('Footer - Coluna 5', 'tnb')
                          )
                    );

// SIDEBAR
register_sidebar( array(
                        'name' => 'Sidebar Principal',
                        'before_widget' => '<div id="%1$s" class="widget %2$s box-shadow">',
                        'after_widget' => '</div>',
                        'before_title' => '<h2 class="title">',
                        'after_title' => '</h2>'
                        )
                );

register_sidebar( array(
                        'name' => 'Sidebar Banner Institucional',
                        'before_widget' => '<div id="%1$s" class="widget %2$s box-shadow">',
                        'after_widget' => '</div>',
                        'before_title' => '<h2 class="title">',
                        'after_title' => '</h2>'
                        )
                );


// COMMENTS
function tnb_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;  
?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('clearfix'); ?>>
        <div class="avatar"><?php echo get_avatar( $comment, 70 ); ?></div>
        <p class="comment-meta alignright"><?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])) ?> <?php edit_comment_link('Editar', '| ', ''); ?></p>    
        <p class="comment-meta bottom">Por <cite><a target="_blank" href="<?php comment_author_url(); ?>"><?php comment_author(); ?></a></cite> em <?php comment_date(); ?> às <?php comment_time(); ?></p>
        <?php if($comment->comment_approved == '0') : ?><p class="bottom"><em><?php _e("Seu comentário está aguardando moderação.", "tnb"); ?></em></p><?php endif; ?>
        <?php comment_text(); ?>
    </li>
    <?php
}

// REDIRECIONAMENTOS
function custom_url_rewrites($wp_rewrite) {
    $new_rules = array(
        // rules for Calls
        "rede/([^/]+)/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1),

        "cadastro/?$" => "index.php?tpl=cadastro",
        
        "play/?$" => "index.php?tpl=play",
        "download/?$" => "index.php?tpl=download",
        
        "rede/([^/]+)/eventos/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=gerenciar-eventos",
        "rede/([^/]+)/fotos/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=fotos-do-artista",
        "rede/([^/]+)/eventos/novo/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=cadastro-de-evento",
        
        "rede/([^/]+)/eventos/([^/]+)/editar/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=cadastro-de-evento" . "&event_name=" . $wp_rewrite->preg_index(2),
        
    	"rede/([^/]+)/editar/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=editprofile",
        "rede/([^/]+)/editar/([^/]+)/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=editprofile&section=" . $wp_rewrite->preg_index(2),
        
        "rede/([^/]+)/editar/oportunidades/page/?([0-9]{1,})/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=editprofile&section=oportunidades&paged=" . $wp_rewrite->preg_index(2),
        "rede/([^/]+)/editar/oportunidades/([^/]+)/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=editprofile&section=oportunidades&event_name=" . $wp_rewrite->preg_index(2),

        "(artistas|produtores|universo)(/page/?([0-9]{1,}))?/?$" => 'index.php?tpl=list_author&reg_type='. $wp_rewrite->preg_index(1). '&paged=' . $wp_rewrite->preg_index(3),
    	
        'eventos/?$' => 'index.php?tpl=list&post_type=eventos',
        'eventos/page/?([0-9]{1,})/?$' => 'index.php?tpl=list&post_type=eventos&paged='.$wp_rewrite->preg_index(1),
        'eventos/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tpl=list&post_type=eventos&feed='.$wp_rewrite->preg_index(1),
        'eventos/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tpl=list&post_type=eventos&feed='.$wp_rewrite->preg_index(1),

    	'oportunidades/?$' => 'index.php?tpl=list&post_type=eventos',
        'oportunidades/page/?([0-9]{1,})/?$' => 'index.php?tpl=list&post_type=eventos&paged='.$wp_rewrite->preg_index(1),
        'oportunidades/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tpl=list&post_type=eventos&feed='.$wp_rewrite->preg_index(1),
        'oportunidades/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tpl=list&post_type=eventos&feed='.$wp_rewrite->preg_index(1)
    
    	
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action('generate_rewrite_rules', 'custom_url_rewrites');

function custom_query_vars($public_query_vars) {
    $public_query_vars[] = "tpl";
    $public_query_vars[] = "reg_type";
    $public_query_vars[] = "event_name";
    $public_query_vars[] = "section";

    return $public_query_vars;
}
add_filter('query_vars', 'custom_query_vars');

//evitando usuario de se registrar pelo wp-login
add_filter('init','register_block_redirect');
function register_block_redirect() {
    if ( preg_match('/(action=register)/', $_SERVER['REQUEST_URI'] ) )
        wp_redirect(get_bloginfo('url'));
}

remove_action('template_redirect', 'redirect_canonical');

add_action('template_redirect', 'template_redirect_intercept');
function template_redirect_intercept(){


    if( $_GET['action'] ==  'register'){
       die;
    }

    global $wp_query;
    $reg_type = $wp_query->get('reg_type');

    switch ( $wp_query->get('tpl') ) {

        case 'list':
        if ($wp_query->get('post_type') == 'eventos') {
            include( TEMPLATEPATH . '/oportunidades-list.php' );
        }
        exit;
        break;

        case 'cadastro':
        include( TEMPLATEPATH . '/cadastro.php' );
        exit;
        break;
        
        case 'download':
        include( TEMPLATEPATH . '/media/download.php' );
        exit;
        break;
        
        case 'play':
        include( TEMPLATEPATH . '/media/play.php' );
        exit;
        break;
        
        case 'editprofile':
            include( TEMPLATEPATH . "/users-edit.php" );
        exit;
        break;

        case 'gerenciar-eventos':
            include( TEMPLATEPATH . "/rede/eventos-manage.php" );
        exit;
        break;

        case 'cadastro-de-evento':
            include( TEMPLATEPATH . "/rede/eventos-form.php" );
        exit;
        break;

        case 'fotos-do-artista':
            include( TEMPLATEPATH . "/rede/fotos-do-artista.php" );
        exit;
        break;

        case 'list_author':
            if (file_exists( TEMPLATEPATH . "/users-list.php" )) {
                include( TEMPLATEPATH . "/users-list.php" );
                exit;
            }
        break;
        
    }
}








/** =================================== USUÁRIOS =================================== //
 * 
 * Enter description here ...
 */



/**
 * Verifica se o $current_user é artista e pode se inscrever no evento
 * @param string $artista_id
 * @param string $oportunidade_id
 * @return boolean
 */
function tnb_artista_can_join($oportunidade_id, $user_id = null){
    global $current_user;
    
    $user_id = $user_id ? $user_id : $current_user->ID;
    $user = $user_id ? get_user_by('id', $user_id) :  $current_user;
    
    if(is_artista($user_id)){
        $opdata = get_oportunidades_data($oportunidade_id);
        
        $user_estilos = get_user_meta($user_id, 'estilo_musical');
        
        $user_estilos = is_array($user_estilos)? $user_estilos : array();
            
        $result = true;
        
        if($opdata['filtro_origem_pais'] && $opdata['filtro_origem_pais'] != $user->origem_pais)
            $result = false;
        
        if($opdata['filtro_residencia_pais'] && $opdata['filtro_residencia_pais'] != $user->banda_pais)
            $result = false;
        
        
        if($result && $opdata['filtro_origem_pais'] == 'BR' && is_array($opdata['filtro_origem_uf']))
            if(!in_array($user->origem_estado, $opdata['filtro_origem_uf']))
                $result = false;
        
        
        if($result && $opdata['filtro_residencia_pais'] == 'BR' && is_array($opdata['filtro_residencia_uf']))
            if(!in_array($user->banda_estado, $opdata['filtro_residencia_uf']))
                $result = false;
        
        
        if($result && is_array($opdata['filtro_estilo'])){
           $result = false;
           foreach ($opdata['filtro_estilo'] as $estilo)
               if(in_array($estilo, $user_estilos))
                   $result = true;
           
        }
        
        //_vd($result);
        return $result;
        
    }else{
        return false;
    }
}

function get_users_search_result(){
    //get_oportunidades_search_results()
	global $wp_query, $wpdb;
	$wp_query->get('paged');
	
	$nome = $_GET['user_name'];
	$local = $_GET['user_local'];
	$estilo = $_GET['user_estilo'];
	$role = '';
	if($_GET['user_type'] == 'artistas')
		$role = 'artista';
	if($_GET['user_type'] == 'produtores')
		$role = 'produtor';
		
	$local_sql = false;
	$paises = get_paises();
	$estados = get_estados();
	/* 
	foreach($paises as $sigla => $pais)
		if(trim(strtolower($local)) == strtolower($pais)){
			$local = $sigla;
			continue;
		}
	
	foreach($estados as $sigla => $estado)
		if(trim(strtolower($local)) == strtolower($estado)){
			$local = strtolower($sigla);
			continue;
		}
	
	
	if(trim($local)){
		if($role == 'artista'){
			$local_sql = " {$wpdb->users}.ID IN (SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (meta_key = 'origem_pais' OR meta_key = 'origem_estado' OR meta_key = 'origem_cidade' OR meta_key = 'banda_pais' OR meta_key = 'banda_estado' OR meta_key = 'banda_cidade') AND meta_value LIKE '%$local%')";
		}else{
			$local_sql = " {$wpdb->users}.ID IN (SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (meta_key = 'origem_pais' OR meta_key = 'origem_estado' OR meta_key = 'origem_cidade') AND meta_value LIKE '%$local%')";
		}
		
	}
    /* */
	//*
	$_local = remove_accents(strtolower($local));
	$paises_encontrados = array();
	foreach($paises as $sigla => $pais){
		$_pais = remove_accents(trim(strtolower($pais)));
		if($local && ($_local ==  $_pais || substr_count($_pais, $_local))){
			$paises_encontrados[] = $sigla;
		}
	}
	
	$estados_encontrados = array();
	foreach($estados as $sigla => $estado){
		$_estado = remove_accents(trim(strtolower($estado)));
		if($local && ($_local ==  $_estado || substr_count($_estado, $_local))){
			$estados_encontrados[] = $sigla;
		}
	}
	
	if($paises_encontrados){
		foreach ($paises_encontrados as $sigla){
			$valores_paises .= $valores_paises ? " OR 
									meta_value = '$sigla'" : "
									meta_value = '$sigla'";
		}
		
		$sql_paises = sprintf("OR {$wpdb->users}.ID IN (SELECT 
                        									DISTINCT user_id 
                        								 FROM 
                        									$wpdb->usermeta 
                        								 WHERE
                        								 	(meta_key = 'banda_pais' OR meta_key = 'origem_pais') AND (%s)
                        								 )", $valores_paises);
	}
	
	if($estados_encontrados){
		foreach ($estados_encontrados as $sigla){
			$valores_estado .= $valores_estado ? " OR 
									meta_value = '$sigla'" : "
									meta_value = '$sigla'";
		}
		$sql_estados = sprintf("OR {$wpdb->users}.ID IN (SELECT 
                        									DISTINCT user_id 
                        								 FROM 
                        									$wpdb->usermeta 
                        								 WHERE
                        								 	(meta_key = 'banda_estado' OR meta_key = 'origem_estado') AND ( %s )
                        								 )", $valores_estado);
	}
	
	if($local){
		
		$local_sql = "{$wpdb->users}.ID IN (SELECT 
                									DISTINCT user_id 
                								 FROM 
                									$wpdb->usermeta 
                								 WHERE
                								 	(meta_key = 'banda_cidade' OR meta_key = 'origem_cidade') AND
                									meta_value LIKE '%$local%'
                								)
						   	$sql_paises
							$sql_estados
						   ";
							
		//_pr($local_sql);
	}
    
		
	
	/* */
    if (is_array($estilo) && sizeof($estilo) > 0) {
        $estilo_sql = " {$wpdb->users}.ID IN (SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'estilo_musical' AND meta_value IN ('" . implode("','", $estilo) . "') ) ";
        if ($local_sql) $estilo_sql = " AND $estilo_sql";
    }
    
    $query_inc = $local_sql . $estilo_sql;
    //_pr($query_inc);
    if (strlen($query_inc) == 0) $query_inc = false;
    
	return tnb_get_users($role, false, false, $nome, $query_inc);
}

function get_ultimos_cadastrados($num_rows = 2, $role = ''){
    $num_rows = intval($num_rows) > 0 ? intval($num_rows) : 2; 
	$limit = TNB_USERS_COLS * $num_rows;
	return tnb_get_users($role, $limit, 'user_registered DESC');
}

function get_artistas( $limit = false, $order=false, $search = false) {
    return tnb_get_users('artista', $limit, $order, $search);
}

function get_produtores( $limit = false, $order=false, $search = false) {
    return tnb_get_users('produtor', $limit, $order, $search);
}

function tnb_get_users( $role = "", $limit = false, $order=false, $search = false, $inc_sql = false) {
    global $wpdb;

    $inc_sql = is_string($inc_sql) ? "($inc_sql) AND " : "";
    
    if(!$order)
        $order = "ID";
    if(is_numeric($limit))
        $limit = "LIMIT $limit";
    elseif(!$limit)
        $limit = '';

    $prefix = $wpdb->prefix;

    $searchQuery = $search ? $wpdb->prepare("AND display_name LIKE %s", "%$search%") : "";
	
    $q = trim($role) ? $q = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '{$prefix}capabilities' AND meta_value LIKE '%\"$role\"%' ORDER BY $order"
    				 : $q = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '{$prefix}capabilities' AND meta_value LIKE '%\"artista\"%' OR meta_key = '{$prefix}capabilities' AND meta_value LIKE '%\"produtor\"%' ORDER BY $order";
    $not_q = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'tnb_inactive' AND meta_value = 1";
    //$query = "SELECT * FROM {$wpdb->users} WHERE ID IN($q) AND ID NOT IN ($not_q) $searchQuery ORDER BY $order $limit";
	$query = "SELECT {$wpdb->users}.*, {$wpdb->usermeta}.meta_value AS wp_capabilities FROM {$wpdb->users}, {$wpdb->usermeta} WHERE $inc_sql {$wpdb->users}.ID IN($q) AND {$wpdb->users}.ID NOT IN ($not_q) AND {$wpdb->usermeta}.user_id = {$wpdb->users}.ID AND {$wpdb->usermeta}.meta_key = '{$prefix}capabilities' $searchQuery ORDER BY $order $limit";
	//_pr($query);
    $users = $wpdb->get_results($query);
    return $users;

}

function is_artista($user_id = false){
    if ($user_id) {
        $user = new WP_User($user_id);
        return in_array('artista' ,$user->roles);
    } else {
        if( is_user_logged_in() ){
            global $current_user;
            return in_array('artista' ,$current_user->roles);
        }
    }
    return false;
}

function is_produtor($user_id = false){
    if ($user_id) {
        $user = new WP_User($user_id);
        return in_array('produtor' ,$user->roles);
    } else {
        if( is_user_logged_in() ){
            global $current_user;
            return in_array('produtor' ,$current_user->roles);
        }
    }
    return false;
}



function tnb_author_link($link, $author_id, $author_nicename) {
    return get_bloginfo('url') . '/rede/' . $author_nicename;
}
add_filter( 'author_link', 'tnb_author_link', 10, 3);

// Checando capabilty select_artist
add_filter('map_meta_cap', 'tnb_current_user_can_select_artist', 10, 4);

function tnb_current_user_can_select_artist($caps, $cap, $user_id, $args) {
    if ($cap == 'select_artists') {
        if (sizeof($args) > 0) {

            // estamos recebendo o ID de um evento
            // Vamos ver se ele é filho de alguem
            $ancestors = get_post_ancestors($args[0]);

            if(sizeof($ancestors)>0) {
                // tem um pai, vamos ver se esse evento é um superevento (tem que ser)

                if (get_post_meta($ancestors[0], 'superevento', true) != 'yes')
                    return array('do_not_allow');

                // primeiro vamos ver se eu sou o autor do evento pai desse evento
                $pai = get_post($ancestors[0]);
                if ($pai->post_author == $user_id)
                    return $caps; // eu sou o dono do superevento, posso editar

                // se eu for o dono do evento e o dono do superevento permitir

                $evento = get_post($args[0]);
                 if (get_post_meta($ancestors[0], 'evento_produtores_selecionam', true) == 1 && $evento->post_author == $user_id) {
                    return $caps;
                } else {
                    return array('do_not_allow');
                }
            } else {

                // não é filho de superevento, vamos ver se ele é o autor do evento
                $evento = get_post($args[0]);
                if ($evento->post_author == $user_id)
                    return $caps;
                else
                    return array('do_not_allow');

            }

        } else {
            return $caps;
        }

    } else {
        return $caps;
    }

}


function delete_user_from_events($user_id){
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_value = {$user_id} AND meta_key='inscrito'");
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_value = {$user_id} AND meta_key='selecionado'");
}
add_action('delete_user', 'delete_user_from_events');


// --------------------------- MADMINI ---------------------------//
// New users to madmimi
function tnb_madmimi_user_register($user_id) {
    global $wpdb, $wp_query;
    
    $op = get_option('tnb_madmimi_user');
    $username = $op['user'];
    $api_key = $op['api_key'];
    
    if (!$username || !$api_key)
        return false;
    
    $email = $wpdb->get_var("SELECT user_email FROM $wpdb->users WHERE ID = $user_id");
    
    if (!$email)
        return false;
    
    
    
    $reg_type = $_POST['user_type'];

    $list = $reg_type == 'produtor' ? '175892' : '175894';
    
    
    
    $ch = curl_init('http://madmimi.com/audience_lists/' . $list . '/add');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_MUTE, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username='.$username.'&api_key='.$api_key.'&email='.$email);
    
    //prevents output
    ob_start();
    curl_exec($ch);
    ob_end_clean();
    
    
}
add_action('tnb_user_register', 'tnb_madmimi_user_register') ;


function tnb_madmimi_user_settings() {
 	register_setting('general','tnb_madmimi_user');
    add_settings_field('tnb_madmimi_user', 'Mad Mimi', 'tnb_madmimi_user_input', 'general', 'default');
}

add_action('admin_init', 'tnb_madmimi_user_settings');

function tnb_madmimi_user_input() {
    $m = get_option('tnb_madmimi_user');
    
    echo "<input type='text' value='" . htmlspecialchars($m['user']) . "' name='tnb_madmimi_user[user]'><br/>";
    echo "<small>Usuário do MadMimi</small><br/><br/>";
    echo "<input type='text' value='" . htmlspecialchars($m['api_key']) . "' name='tnb_madmimi_user[api_key]'><br/>";
    echo "<small>API Key MadMimi</small>";
}


// --------------------- BANCO DE CIDADES --------------------- // 
function tnb_getMunicipio($uf, $municipio){
  if(tnb_cache_exists('tnb_getMunicipio', "$uf-$municipio"))
      return tnb_cache_get('tnb_getMunicipio', "$uf-$municipio");
      
  global $wpdb;
  $query = "
		SELECT 
			municipio.*
		FROM
			municipio, uf
		WHERE
			municipio.ufid = uf.id AND
			municipio.nome = '$municipio' AND
  			uf.sigla = '$uf'";

  $result = $wpdb->get_row($query);
  tnb_cache_set('tnb_getMunicipio', "$uf-$municipio", $result);
  
  return $result;
}

function tnb_contatoUsuarioCorreto($user){
    
    // usuários de fora do brasil só precisam preencher o pais e a cidade, já os brasileiros precisam preencher o estado também
    if(!$user->origem_pais OR !$user->origem_cidade){
      return false; 
    }
    
    if($user->origem_pais == 'BR' AND !tnb_getMunicipio($user->origem_estado, $user->origem_cidade)){
      return false; //
    }
    if(in_array('artista', $user->wp_capabilities)){
      if(!$user->banda_pais OR !$user->banda_cidade){
        return false; 
      }
      if($user->banda_pais == 'BR' AND !tnb_getMunicipio($user->banda_estado, $user->banda_cidade)){
        return false; 
      }
    }
    return true;
}





// ----------------------------- MIDIAS ------------------------------ //
function tnb_get_artista_galeria($artista_id, $tipo_galeria){
    if(tnb_cache_exists("ARTISTAS_GALERIA_$tipo_galeria", $artista_id))
        return tnb_cache_get("ARTISTAS_GALERIA_$tipo_galeria", $artista_id);
        
    global $wpdb;
    $result = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_type='$tipo_galeria' AND post_author = '$artista_id'" );
    
    if (!$result) {
        $userdata = get_userdata($artista_id);
        
        $postdata = array(
            'post_author' => $artista_id,
            'post_type' => $tipo_galeria,
            'post_title' => $userdata->display_name
        );
        $newpost = wp_insert_post($postdata);
        wp_publish_post($newpost);
        
        $result = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_type='$tipo_galeria' AND post_author = '$artista_id'" );
    }
        
    tnb_cache_set("ARTISTAS_GALERIA_$tipo_galeria", $artista_id, $result);
    return $result;
}

function tnb_set_artista_musica_principal($artista_id, $media_id){
    if($_POST['musica_principal'] || $menu_order == 0)
        update_user_meta($artista_id, '_musica_principal', $media_id);
            
}

function tnb_get_artista_musica_principal($artista_id){
    if(tnb_cache_exists('ARTISTAS_MUSICA_PRINCIPAL', $artista_id))
        return tnb_cache_get('ARTISTAS_MUSICA_PRINCIPAL', $artista_id);
    
    global $wpdb;
    
    $result = $wpdb->get_row("SELECT $wpdb->posts.* FROM $wpdb->posts, $wpdb->usermeta WHERE $wpdb->posts.ID = $wpdb->usermeta.meta_value AND $wpdb->usermeta.meta_key = '_musica_principal' AND $wpdb->usermeta.user_id = '$artista_id'");
   // echo "SELECT $wpdb->posts.* FROM $wpdb->posts, $wpdb->usermeta WHERE $wpdb->posts.ID = $wpdb->usermeta.meta_value AND $wpdb->usermeta.meta_key = '_musica_principal' AND $wpdb->usermeta.user_id = '$artista_id'";
    if(!$result){
        $musicas = tnb_get_artista_musicas($artista_id);
        if(count($musicas))
            tnb_set_artista_musica_principal($artista_id, $musicas[0]->ID);
    }
    
    tnb_cache_set('ARTISTAS_MUSICA_PRINCIPAL', $artista_id, $result);
    return $result;
}

function tnb_set_artista_video_principal($artista_id, $media_id){
    update_user_meta($artista_id, '_video_principal', $media_id);
}

function tnb_get_artista_video_principal($artista_id){
    if(tnb_cache_exists('ARTISTAS_VIDEO_PRINCIPAL', $artista_id))
        return tnb_cache_get('ARTISTAS_VIDEO_PRINCIPAL', $artista_id);
    
    global $wpdb;
    
    $result = $wpdb->get_row("SELECT $wpdb->posts.* FROM $wpdb->posts, $wpdb->usermeta WHERE $wpdb->posts.ID = $wpdb->usermeta.meta_value AND $wpdb->usermeta.meta_key = '_video_principal' AND $wpdb->usermeta.user_id = '$artista_id'");
   // echo "SELECT $wpdb->posts.* FROM $wpdb->posts, $wpdb->usermeta WHERE $wpdb->posts.ID = $wpdb->usermeta.meta_value AND $wpdb->usermeta.meta_key = '_musica_principal' AND $wpdb->usermeta.user_id = '$artista_id'";
    if(!$result){
        $videos = tnb_get_artista_videos($artista_id);
        if(count($videos))
            tnb_set_artista_video_principal($artista_id, $videos[0]->ID);
    }
    
    tnb_cache_set('ARTISTAS_VIDEO_PRINCIPAL', $artista_id, $result);
    return $result;
}

function tnb_get_artista_musicas($artista_id){
    if(tnb_cache_exists('ARTISTAS_MUSICAS', $artista_id))
        return tnb_cache_get('ARTISTAS_MUSICAS', $artista_id);
        
    global $wpdb;
    
    $query = "SELECT * FROM $wpdb->posts WHERE post_parent = (SELECT ID FROM $wpdb->posts WHERE post_type = 'music' AND post_author = '$artista_id' LIMIT 1) ORDER BY menu_order";

    $result = $wpdb->get_results($query);
    //$medias = get_posts("post_type=attachment&author={$artista_id}&meta_key=_media_index&orderby=menu_order&order=ASC");
        
    tnb_cache_set('ARTISTAS_MUSICAS', $artista_id, $result);
    return $result;
}

function tnb_get_artista_musica_data($music_id){
     if(tnb_cache_exists('ARTISTAS_MUSICA_DATA', $music_id))
        return tnb_cache_get('ARTISTAS_MUSICA_DATA', $music_id);

     $result['filesize'] = get_post_meta($music_id, "_filesize", true);
     $result['playtime'] = get_post_meta($music_id, "_playtime", true);
     $result['album'] = get_post_meta($music_id, "_album", true);
     $result['download'] = get_post_meta($music_id, "_download", true);
     
     tnb_cache_set('ARTISTAS_MUSICA_DATA', $music_id, $result);
     return $result;
}

function tnb_get_artista_fotos($artista_id){
    if(tnb_cache_exists('ARTISTAS_FOTOS', $artista_id))
        return tnb_cache_get('ARTISTAS_FOTOS', $artista_id);
        
    global $wpdb;
    
    $query = "SELECT * FROM $wpdb->posts WHERE post_parent = (SELECT ID FROM $wpdb->posts WHERE post_type = 'images' AND post_author = '$artista_id' LIMIT 1) ORDER BY menu_order";

    $result = $wpdb->get_results($query);
    //$medias = get_posts("post_type=attachment&author={$artista_id}&meta_key=_media_index&orderby=menu_order&order=ASC");
        
    tnb_cache_set('ARTISTAS_FOTOS', $artista_id, $result);
    return $result;
}


function tnb_get_artista_videos($artista_id){
    if(tnb_cache_exists('ARTISTAS_VIDEOS', $artista_id))
        return tnb_cache_get('ARTISTAS_VIDEOS', $artista_id);
        
    global $wpdb;
    
    $query = "SELECT * FROM $wpdb->posts WHERE post_type = 'videos' AND post_author = '$artista_id' ORDER BY menu_order";

    $result = $wpdb->get_results($query);
        
    tnb_cache_set('ARTISTAS_VIDEOS', $artista_id, $result);
    return $result;
}


/** =================================== OPORTUNIDADES =================================== //
 * 
 * Enter description here ...
 */

function get_oportunidades_search_results($status = 'publish'){
	global $wpdb;
	$nome = $_GET['oportunidade_nome'];
	$local = trim($_GET['oportunidade_local']);
	$inscricoes_abertas = isset($_GET['oportunidades_abertas']);
	
    if ($_GET['acontece'] != 'nao_importa' && $_GET['acontece_de'] && $_GET['acontece_ate']) {
    
        list($dia1, $mes1, $ano1) = explode('/', $_GET['acontece_de']);
        list($dia2, $mes2, $ano2) = explode('/', $_GET['acontece_ate']);
        
        
        $mes1 = $mes1 > 9 ? $mes1 : "0".intval($mes1);
        $mes2 = $mes2 > 9 ? $mes2 : "0".intval($mes2);
        $dia1 = $dia1 > 9 ? $dia1 : "0".intval($dia1);
        $dia2 = $dia2 > 9 ? $dia2 : "0".intval($dia2);
        
        $contece_de = "$ano1-$mes1-$dia1";
        $contece_ate = "$ano2-$mes2-$dia2";
        
        $query_data = " AND 
		(ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_inicio' AND meta_value >= '$contece_de' AND meta_value <= '$contece_ate') OR
		ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_fim' AND meta_value >= '$contece_de' AND meta_value <= '$contece_ate'))";
    
    }
    
	$currentdate = date('Y-m-d');
	
	/** FIX LOCAIS **/
	$local_sql = false;
	$paises = get_paises();
	$estados = get_estados();
	
	$_local = remove_accents(strtolower($local));
	
	$paises_encontrados = array();
	foreach($paises as $sigla => $pais){
		$_pais = remove_accents(trim(strtolower($pais)));
		if($local && ($_local ==  $_pais || substr_count($_pais, $_local))){
			$paises_encontrados[] = $sigla;
		}
	}
	
	$estados_encontrados = array();
	foreach($estados as $sigla => $estado){
		$_estado = remove_accents(trim(strtolower($estado)));
		if($local && ($_local ==  $_estado || substr_count($_estado, $_local))){
			$estados_encontrados[] = $sigla;
		}
	}
	
	if($paises_encontrados){
		foreach ($paises_encontrados as $sigla){
			$valores_paises .= $valores_paises ? " OR 
									meta_value = '$sigla'" : "
									meta_value = '$sigla'";
		}
		
		$sql_paises = sprintf("OR ID IN (SELECT 
									DISTINCT post_id 
								 FROM 
									$wpdb->postmeta 
								 WHERE
								 	meta_key = 'evento_pais' AND (
								 	%s
								 	))", $valores_paises);
	}
	
	if($estados_encontrados){
		foreach ($estados_encontrados as $sigla){
			$valores_estado .= $valores_estado ? " OR 
									meta_value = '$sigla'" : "
									meta_value = '$sigla'";
		}
		$sql_estados = sprintf("OR ID IN (SELECT 
									DISTINCT post_id 
								 FROM 
									$wpdb->postmeta 
								 WHERE
								 	meta_key = 'evento_estado' AND (
								 	%s
								 	))", $valores_estado);
	}
	
	if($local){
		
		$local_sql = "AND (ID IN (SELECT 
									DISTINCT post_id 
								 FROM 
									$wpdb->postmeta 
								 WHERE(	meta_key = 'evento_cidade' OR
										meta_key = 'evento_local' ) AND
									meta_value LIKE '%$local%')
						   	$sql_paises
							$sql_estados
						   )";
	}
    
		
	
	$query_inscricao = $inscricoes_abertas ? " AND 
		ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_inscricao_inicio' AND meta_value <= '$currentdate') AND
		ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_inscricao_fim' AND meta_value >= '$currentdate')
		" : '';
	
    $query_subevents_arovados = " AND (post_parent = 0 OR ID IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento') ) ";
	
	$query = "
	SELECT 
		ID 
	FROM 
		$wpdb->posts 
	WHERE
		post_type = 'eventos' AND
        post_status = '$status' AND
		post_title LIKE '%$nome%'
        $query_data
		$local_sql $query_inscricao $query_subevents_arovados";
	
	//_pr($query);
	$ids = $wpdb->get_results($query);
	$result = array();
	foreach($ids as $oid)
		$result[] = $oid->ID;
		
	return $result;
}


add_action('wp_ajax_get_superevento', 'get_superevento');
function get_superevento() {
    $content = '';
    $header = "HTTP/1.0 404 Not Found";
    $id = (int) sprintf("%d", $_POST['superevento']);

    if($id > 0) {
        $event = get_post($id);
        if (get_post_meta($event->ID, 'superevento', true) == 'yes' && $event->post_type == 'eventos') {
            $header = 'Content-type: text/javascript';
            $content = json_encode( array(
                'evento_condicoes' => get_post_meta($event->ID, 'evento_condicoes', true),
                'evento_restricoes' => get_post_meta($event->ID, 'evento_restricoes', true),
                'evento_tos' => get_post_meta($event->ID, 'evento_tos', true),
                'forcar_condicoes' => get_post_meta($event->ID, 'forcar_condicoes', true) != "",
                'forcar_restricoes' => get_post_meta($event->ID, 'forcar_restricoes', true) != "",
                'forcar_tos' => get_post_meta($event->ID, 'forcar_tos', true) != "",
            ));
        }
    }

    header($header);
    die($content);
}



// Template Tags para condicoes, termos de uso e restricoes de um evento

function the_tos() {
    echo get_the_tos();
}

function get_the_tos($post_id = null) {
    if (is_null($post_id)) {
        global $post;
    } else {
        $post = get_post($post_id);
    }

    if (!is_object($post))
        return false;

    if($post->post_parent > 0 && get_post_meta($post->post_parent, 'forcar_tos', true)) {
        return get_post_meta($post->post_parent, 'evento_tos', true);
    } else {
        return get_post_meta($post->ID, 'evento_tos', true);
    }

}

function the_condicoes() {
    echo get_the_condicoes();
}

function get_the_condicoes($post_id = null) {
    
    if (is_null($post_id)) {
        global $post;
    } else {
        $post = get_post($post_id);
    }
    
    if (!is_object($post))
        return false;

    if($post->post_parent > 0 && get_post_meta($post->post_parent, 'forcar_condicoes', true)) {        
        return get_post_meta($post->post_parent, 'evento_condicoes', true);
    } else {
        return get_post_meta($post->ID, 'evento_condicoes', true);
    }

}

function the_restricoes() {
    echo get_the_restricoes();
}

function get_the_restricoes($post_id = null) {
    if (is_null($post_id)) {
        global $post;
    } else {
        $post = get_post($post_id);
    }

    if (!is_object($post))
        return false;

    if($post->post_parent > 0 && get_post_meta($post->post_parent, 'forcar_restricoes', true)) {
        return get_post_meta($post->post_parent, 'evento_restricoes', true);
    } else {
        return get_post_meta($post->ID, 'evento_restricoes', true);
    }

}



/**
 * Retorna um slug unico para um evento, desconsiderando
 * se o evento está ativo ou não. Este trecho de código
 * foi copiado da função wp_unique_post_slug, nativa no
 * Wordpress.
 */
function tnb_unique_event_slug($slug, $post_ID=0) {
    global $wpdb, $wp_rewrite;

    $feeds = $wp_rewrite->feeds;
	if ( ! is_array( $feeds ) )
		$feeds = array();

    $check_sql = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_type = 'eventos' AND ID != %d LIMIT 1";
    $post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $slug, $post_ID ) );

    if ( $post_name_check || in_array( $slug, $feeds ) ) {
        $suffix = 2;
        do {
            $alt_post_name = substr( $slug, 0, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
            $post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $alt_post_name, $post_ID ) );
            $suffix++;
        } while ( $post_name_check );
        $slug = $alt_post_name;
    }

    return $slug;
}

function get_oportunidades_data($evento_list_item_id){
	if(tnb_cache_exists('OPORTUNIDADES_DATA', $evento_list_item_id))
		return tnb_cache_get('OPORTUNIDADES_DATA', $evento_list_item_id);
		
	$evento_list_item = get_post($evento_list_item_id);
    $paises = $paises?$paises:get_paises();

    $inicio = get_post_meta($evento_list_item_id, "evento_inicio", true);
    $fim = get_post_meta($evento_list_item_id, "evento_fim", true);
    $br_inicio = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1", $inicio);
    $br_fim = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1",$fim);
    $inscricao_inicio = get_post_meta($evento_list_item_id, "evento_inscricao_inicio", true);
    $inscricao_fim = get_post_meta($evento_list_item_id, "evento_inscricao_fim", true);
    $br_insc_inicio = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1", $inscricao_inicio);
    $br_insc_fim = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1",$inscricao_fim);
    $local = get_post_meta($evento_list_item_id, "evento_local", true);
    $sigla_pais = get_post_meta($evento_list_item_id, "evento_pais", true);
    $estado = strtoupper(get_post_meta($evento_list_item_id, "evento_estado", true));
    $cidade = get_post_meta($evento_list_item_id, "evento_cidade", true);
    $link = get_post_meta($evento_list_item_id, "evento_site", true);
    $vagas = get_post_meta($evento_list_item_id, "evento_vagas", true);
    $condicoes = get_the_condicoes($evento_list_item_id);
    $restricoes = get_the_restricoes($evento_list_item_id);
    $tos = get_the_tos($evento_list_item_id);
    $superevento = get_post_meta($evento_list_item_id, "superevento", true) == 'yes';

    $patrocinador_1 = get_post_meta($evento_list_item_id, "evento_patrocinador1", true) ;
    $patrocinador_2 = get_post_meta($evento_list_item_id, "evento_patrocinador2", true) ;
    $patrocinador_3 = get_post_meta($evento_list_item_id, "evento_patrocinador3", true) ;
    $subevento = $evento_list_item->post_parent != 0;
	
    $inscricao_cobrada = get_post_meta($evento_list_item_id, 'evento_inscricao_cobrada', true);
    $inscricao_valor = get_post_meta($evento_list_item_id, 'evento_inscricao_valor', true);
    
    $filtro_origem_pais = get_post_meta($evento_list_item_id, 'evento_filtro_origem_pais', true);
    $filtro_residencia_pais = get_post_meta($evento_list_item_id, 'evento_filtro_residencia_pais', true);
    $filtro_origem_uf = get_post_meta($evento_list_item_id, 'evento_filtro_origem_uf', true);
    $filtro_residencia_uf = get_post_meta($evento_list_item_id, 'evento_filtro_residencia_uf', true);
    
    $filtro_estilo = get_post_meta($evento_list_item_id, 'evento_filtro_estilo', true);
    
    $result = array(
    	'inicio' => $inicio,
    	'fim' => $fim,
    	'br_inicio' => $br_inicio,
    	'br_fim' => $br_fim,
    	'inscricao_inicio' => $inscricao_inicio,
    	'inscricao_fim' => $inscricao_fim,
    	'br_insc_inicio' => $br_insc_inicio,
    	'br_insc_fim' => $br_insc_fim,
    	'local' => $local,
    	'sigla_pais' => $sigla_pais,
    	'estado' => $estado,
    	'cidade' => $cidade,
    	'link' => $link,
    	'vagas' => $vagas,
    	'condicoes' => $condicoes,
    	'restricoes' => $restricoes,
    	'tos' => $tos,
    	'superevento' => $superevento,
    	'patrocinador_1' => $patrocinador_1,
    	'patrocinador_2' => $patrocinador_2,
    	'patrocinador_3' => $patrocinador_3,
    	'subevento' => $subevento,
    
    	'inscricao_cobrada' => $inscricao_cobrada,
    	'inscricao_valor' => $inscricao_valor,
    
        'filtro_origem_pais' => $filtro_origem_pais,
        'filtro_residencia_pais' => $filtro_residencia_pais,
        'filtro_origem_uf' => $filtro_origem_uf,
        'filtro_residencia_uf' => $filtro_residencia_uf,
        'filtro_estilo' => $filtro_estilo
    );
    
    tnb_cache_set('OPORTUNIDADES_DATA', $evento_list_item_id, $result);
    
    return $result;
}








/** =================================== GERAL =================================== //
 * 
 * Enter description here ...
 */
// ------------------- RUNTIME CACHE ---------------------- //
/**
 * 
 * Enter description here ...
 * @example tnb_cache_set('ARTISTAS_MUSICAS', $artista_id, $musicas)
 * @param string $type
 * @param string $id
 * @param $data
 */
function tnb_cache_set($type, $id, $data){
    global $TNB_RUNTIME_CACHE;
    $TNB_RUNTIME_CACHE[$type][$id] = $data;
}

function tnb_cache_exists($type, $id){
    global $TNB_RUNTIME_CACHE;
    return isset($TNB_RUNTIME_CACHE[$type][$id]);
}

function tnb_cache_get($type, $id){
    global $TNB_RUNTIME_CACHE;
    if(tnb_cache_exists($type, $id))
        return $TNB_RUNTIME_CACHE[$type][$id];
    else 
        return null;
}

function tnb_cache_unset($type, $id){
    global $TNB_RUNTIME_CACHE;
    unset($TNB_RUNTIME_CACHE[$type][$id]);
}


// ------------------- PLAYERS ---------------------- //
// deprecated -> chama nova função
function print_audio_player($post_id){
    printSinglePlayer( ids2playlist(array($post_id)) );
}



function print_video_player($video_url, $width='300', $height="200"){

      if(preg_match("/\/watch\?v=/", $video_url) ) {

            $videoUrl = preg_replace("/\/watch\?v=/", "/v/" ,$video_url);

          ?>
          
          <object width='<?php echo $width; ?>' height='<?php echo $height; ?>' data='<?php echo $videoUrl; ?>?fs=1&amp;hl=en_US&amp;rel=0'>
            <param name="type" value="application/x-shockwave-flash">
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

/**
 * Monta estrutra para caixa modal e exibe o vídeo de
 * ajuda de acordo com o contexto passado por parâmetro.
 * O link para o vídeo deve ser definido no wp-admin.
 */
function print_help_player_for($for_what) {
    $options = get_option('help_videos');
    $url = $options[$for_what];

    if($url){
        ?>
        <div class="help_video_bar">
            <a class="help_video_button" title="Ajuda" alt="Ajuda" onclick="jQuery('#tnb_modal_help_video').dialog('open');"><span>Ajuda</span></a>
        </div>
        <div class='tnb_modal' id='tnb_modal_help_video'> 
            <h2>Ajuda</h2>
            <?php print_video_player($url, 640, 480);?>
        </div> 
        <?php
    }
}



// ------------------------ //
function edit_btn($title, $id){
	
    if(current_user_can('edit_post') && !is_admin()){

        $post = get_post($id);
//        var_dump($post);
//        capability_type

//        var_dump(get_post_type_object( $post->post_type ));
//        die;
        if($post->post_type == 'post')
            $title.= " <a class='edit-post-link' href='" . get_edit_post_link( $id ) . "' target='_blank'>editar</a>";
    }

    return $title;
}

add_filter('the_title', 'edit_btn', 10, 2);


// ---------------- //
function in_postmeta($meta,$value){
    if(!is_array($meta))
        return false;
    return in_array($value,$meta);
}
function is_blog(){
    global $in_blog;
    return $in_blog;
}




// ---------------- REGISTERING WIDGETS -------------------- //
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






// --------------------- POST THUMBNAILS ------------------------- //
add_theme_support('post-thumbnails');
set_post_thumbnail_size( 150, 150, true );
add_image_size( 'eventos', 150, 150, true );

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
        if (!$msgs) continue;
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





// --------------------- LOGIN ---------------------------//
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


// Esconde admin dos usuários comuns
add_action('admin_init', 'tnb_so_admin_no_admin');
function tnb_so_admin_no_admin() {
    if (!current_user_can('manage_options')) {
        wp_redirect(get_bloginfo('siteurl'));
        exit;
    }
}


function new_signup_location($url){
    return get_bloginfo('url');
}
add_filter('wp_signup_location','new_signup_location');



//header da página de login do wordpress
add_action('login_head', 'tnb_login_head');

if (!function_exists('tnb_login_head')) {
    function tnb_login_head() {
    	?>
    	<style type="text/css">
    		#login {width: 400px;}
    		#login h1 {}
    		#login h1 a {background-image: url(<?php echo get_theme_image('toquenobrasil2.png'); ?> ); height: 133px; width: 400px;}
    	</style>

    	<?php
    }
}



// --------------------- EMAILS --------------------//

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



//// MURAL /////////

function print_mural_comentarios($mural_id, $per_page = 10, $page = 1, $profile_owner) {

    if (!is_numeric($mural_id))
        return false;
        
    global $wpdb;
    
    $total = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1' AND comment_post_ID=$mural_id AND NOT (comment_type = 'pingback' OR comment_type = 'trackback')";
    
    $total = $wpdb->get_var($total);
    
    $offset = ($per_page * ($page - 1));
    
    $current_user = wp_get_current_user();
    
    $comments = $wpdb->get_results("
                                    SELECT *
                                    FROM $wpdb->comments 
                                    WHERE comment_approved = '1' AND comment_post_ID=$mural_id AND NOT (comment_type = 'pingback' OR comment_type = 'trackback')
                                    ORDER BY comment_date_gmt DESC 
                                    LIMIT $per_page OFFSET $offset
										");		
    
    ?>
        
        <?php if (sizeof($comments) > 0): ?>
        
            <?php foreach($comments as $comment): ?>
                <li <?php if ($comment->user_id == $profile_owner) echo 'class="profile-owner"'; ?> >
                    <?php echo get_avatar($comment->user_id, 45); ?>
                    <cite>Por <a href="<?php echo $comment->comment_author_url; ?>"><?php echo $comment->comment_author; ?></a> em <?php echo mysql2date(get_option('date_format'), $comment->comment_date); ?> às <?php echo mysql2date(get_option('time_format'), $comment->comment_date); ?></cite>
                    <p>
                    
                        <?php echo nl2br($comment->comment_content); ?>
                        
                        <?php if (current_user_can('edit_post', $mural_id) || $current_user->ID == $comment->user_id): ?>
                        <b><a class="widget_mural_apagar" id="apagar_<?php echo $comment->comment_ID; ?>">[<?php _e('Apagar', 'tnb'); ?>]</a></b>
                        <?php endif; ?>
                    
                    </p>
                </li>
                
            <?php endforeach; ?>
        
        <?php elseif ($page == 1): ?>
            
            <?php _e('Seja o primeiro a deixar uma mensagem', 'tnb'); ?>
        
        <?php endif; ?>
        
        <input type="hidden" class="current_page" value="<?php echo $page; ?>" />
        <div class="navigation clearfix">    
        <?php if ($page > 1): ?>
            <a class="widget_mural_anterior"><div class="right-navigation alignright">Mais novos</div></a>
        <?php endif; ?>
        
        <?php if ($total > ($page*$per_page)): ?>
            <a class="widget_mural_proximo"><div class="left-navigation alignleft">Mais antigos</div></a>
        <?php endif; ?>
        </div>
    <?php


}



// custom admin login logo
function custom_login_logo() {
	?>
        <style type="text/css">
	        #nav {display: none !important; }
            h1 a { height: 45px; width: 354px; margin-left: -12px; background-image: url(<?php echo get_theme_image('toquenobrasil2.png")'); ?>) !important; }
        </style>
        
    <?php
    
}
add_action('login_head', 'custom_login_logo');

add_filter ('login_headerurl', 'custom_login_headerurl');

function custom_login_headerurl($url) {

    return get_bloginfo('siteurl');

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




// ------------------------ ARQUIVOS -----------------------------//
function toquenobrasil_sanitize_file_name($filename) {
    #return $filename;
    $ext = substr(strrchr($filename,'.'),1);
    $filename = substr($filename, 0, strlen($filename) -4);
    $filename = sanitize_title(remove_accents($filename));
    $filename = str_replace('%', '', $filename);
    return $filename . '.' . $ext;
}


// ---------------------------- LOCAIS ------------------------//
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

function get_paises() {
    $paises = array(
      'AF' => 'Afeganistão',                            'ZA' => 'África do Sul',                         'AL' => 'Albânia',
      'DE' => 'Alemanha',                               'AD' => 'Andorra',                               'AO' => 'Angola',
      'AI' => 'Anguilla',                               'AQ' => 'Antártida',                             'AG' => 'Antígua e Barbuda',
      'AN' => 'Antilhas Holandesas',                    'SA' => 'Arábia Saudita',                        'DZ' => 'Argélia',
      'AR' => 'Argentina',                              'AM' => 'Armênia',                               'AW' => 'Aruba',
      'AU' => 'Austrália',                              'AT' => 'Áustria',                               'AZ' => 'Azerbaijão',
      'BS' => 'Bahamas',                                'BH' => 'Bahrein',                               'BD' => 'Bangladesh',
      'BB' => 'Barbados',                               'BY' => 'Belarus',                               'BE' => 'Bélgica',
      'BZ' => 'Belize',                                 'BJ' => 'Benin',                                 'BM' => 'Bermudas',
      'BO' => 'Bolívia',                                'BA' => 'Bósnia-Herzegóvina',                    'BW' => 'Botsuana',
      'BR' => 'Brasil',                                 'BN' => 'Brunei',                                'BG' => 'Bulgária',
      'BF' => 'Burkina Fasso',                          'BI' => 'Burundi',                               'BT' => 'Butão',
      'CV' => 'Cabo Verde',                             'CM' => 'Camarões',                              'KH' => 'Camboja',
      'CA' => 'Canadá',                                 'KZ' => 'Cazaquistão',                           'TD' => 'Chade',
      'CL' => 'Chile',                                  'CN' => 'China',                                 'CY' => 'Chipre',
      'SG' => 'Cingapura',                              'CO' => 'Colômbia',                              'CG' => 'Congo',
      'KP' => 'Coréia do Norte',                        'KR' => 'Coréia do Sul',                         'CI' => 'Costa do Marfim',
      'CR' => 'Costa Rica',                             'HR' => 'Croácia (Hrvatska)',                    'CU' => 'Cuba',
      'DK' => 'Dinamarca',                              'DJ' => 'Djibuti',                               'DM' => 'Dominica',
      'EG' => 'Egito',                                  'SV' => 'El Salvador',                           'AE' => 'Emirados Árabes Unidos',
      'EC' => 'Equador',                                'ER' => 'Eritréia',                              'SK' => 'Eslováquia',
      'SI' => 'Eslovênia',                              'ES' => 'Espanha',                               'US' => 'Estados Unidos',
      'EE' => 'Estônia',                                'ET' => 'Etiópia',                               'RU' => 'Federação Russa',
      'FJ' => 'Fiji',                                   'PH' => 'Filipinas',                             'FI' => 'Finlândia',
      'FR' => 'França',                                 'GA' => 'Gabão',                                 'GM' => 'Gâmbia',
      'GH' => 'Gana',                                   'GE' => 'Geórgia',                               'GI' => 'Gibraltar',
      'GB' => 'Grã-Bretanha (Reino Unido, UK)',         'GD' => 'Granada',                               'GR' => 'Grécia',
      'GL' => 'Groelândia',                             'GP' => 'Guadalupe',                             'GU' => 'Guam (Território dos Estados Unidos)',
      'GT' => 'Guatemala',                              'GY' => 'Guiana',                                'GF' => 'Guiana Francesa',
      'GN' => 'Guiné',                                  'GQ' => 'Guiné Equatorial',                      'GW' => 'Guiné-Bissau',
      'HT' => 'Haiti',                                  'NL' => 'Holanda',                               'HN' => 'Honduras',
      'HK' => 'Hong Kong',                              'HU' => 'Hungria',                               'YE' => 'Iêmen',
      'BV' => 'Ilha Bouvet (Território da Noruega)',    'IM' => 'Ilha do Homem',                         'CX' => 'Ilha Natal',
      'PN' => 'Ilha Pitcairn',                          'RE' => 'Ilha Reunião',                          'AX' => 'Ilhas Aland',
      'KY' => 'Ilhas Cayman',                           'CC' => 'Ilhas Cocos',                           'KM' => 'Ilhas Comores',
      'CK' => 'Ilhas Cook',                             'FO' => 'Ilhas Faroes',                          'FK' => 'Ilhas Falkland (Malvinas)',
      'GS' => 'Ilhas Geórgia do Sul e Sandwich do Sul', 'MP' => 'Ilhas Marianas do Norte',               'MH' => 'Ilhas Marshall',
      'UM' => 'Ilhas Menores dos Estados Unidos',       'NF' => 'Ilhas Norfolk',                         'SC' => 'Ilhas Seychelles',
      'SB' => 'Ilhas Solomão',                          'SJ' => 'Ilhas Svalbard e Jan Mayen',            'TK' => 'Ilhas Tokelau',
      'TC' => 'Ilhas Turks e Caicos',                   'VI' => 'Ilhas Virgens (Estados Unidos)',        'VG' => 'Ilhas Virgens (Inglaterra)',
      'WF' => 'Ilhas Wallis e Futuna',                  'IN' => 'índia',                                 'ID' => 'Indonésia',
      'IR' => 'Irã',                                    'IQ' => 'Iraque',                                'IE' => 'Irlanda',
      'IS' => 'Islândia',                               'IL' => 'Israel',                                'IT' => 'Itália',
      'JM' => 'Jamaica',                                'JP' => 'Japão',                                 'JE' => 'Jersey',
      'JO' => 'Jordânia',                               'KE' => 'Kênia',                                 'KI' => 'Kiribati',
      'KW' => 'Kuait',                                  'LA' => 'Laos',                                  'LV' => 'Látvia',
      'LS' => 'Lesoto',                                 'LB' => 'Líbano',                                'LR' => 'Libéria',
      'LY' => 'Líbia',                                  'LI' => 'Liechtenstein',                         'LT' => 'Lituânia',
      'LU' => 'Luxemburgo',                             'MO' => 'Macau',                                 'MK' => 'Macedônia (República Yugoslava)',
      'MG' => 'Madagascar',                             'MY' => 'Malásia',                               'MW' => 'Malaui',
      'MV' => 'Maldivas',                               'ML' => 'Mali',                                  'MT' => 'Malta',
      'MA' => 'Marrocos',                               'MQ' => 'Martinica',                             'MU' => 'Maurício',
      'MR' => 'Mauritânia',                             'YT' => 'Mayotte',                               'MX' => 'México',
      'FM' => 'Micronésia',                             'MZ' => 'Moçambique',                            'MD' => 'Moldova',
      'MC' => 'Mônaco',                                 'MN' => 'Mongólia',                              'ME' => 'Montenegro',
      'MS' => 'Montserrat',                             'MM' => 'Myanma',                                'NA' => 'Namíbia',
      'NR' => 'Nauru',                                  'NP' => 'Nepal',                                 'NI' => 'Nicarágua',
      'NE' => 'Níger',                                  'NG' => 'Nigéria',                               'NU' => 'Niue',
      'NO' => 'Noruega',                                'NC' => 'Nova Caledônia',                        'NZ' => 'Nova Zelândia',
      'OM' => 'Omã',                                    'PW' => 'Palau',                                 'PA' => 'Panamá',
      'PG' => 'Papua-Nova Guiné',                       'PK' => 'Paquistão',                             'PY' => 'Paraguai',
      'PE' => 'Peru',                                   'PF' => 'Polinésia Francesa',                    'PL' => 'Polônia',
      'PR' => 'Porto Rico',                             'PT' => 'Portugal',                              'QA' => 'Qatar',
      'KG' => 'Quirguistão',                            'CF' => 'República Centro-Africana',             'CD' => 'República Democrática do Congo',
      'DO' => 'República Dominicana',                   'CZ' => 'República Tcheca',                      'RO' => 'Romênia',
      'RW' => 'Ruanda',                                 'EH' => 'Saara Ocidental',                       'VC' => 'Saint Vincente e Granadinas',
      'AS' => 'Samoa Ocidental',                        'WS' => 'Samoa Ocidental',                       'SM' => 'San Marino',
      'SH' => 'Santa Helena',                           'LC' => 'Santa Lúcia',                           'BL' => 'São Bartolomeu',
      'KN' => 'São Cristóvão e Névis',                  'MF' => 'São Martim',                            'ST' => 'São Tomé e Príncipe',
      'SN' => 'Senegal',                                'SL' => 'Serra Leoa',                            'RS' => 'Sérvia',
      'SY' => 'Síria',                                  'SO' => 'Somália',                               'LK' => 'Sri Lanka',
      'PM' => 'St. Pierre and Miquelon',                'SZ' => 'Suazilândia',                           'SD' => 'Sudão',
      'SE' => 'Suécia',                                 'CH' => 'Suíça',                                 'SR' => 'Suriname',
      'TJ' => 'Tadjiquistão',                           'TH' => 'Tailândia',                             'TW' => 'Taiwan',
      'TZ' => 'Tanzânia',                               'IO' => 'Território Britânico do Oceano índico', 'TF' => 'Territórios do Sul da França',
      'PS' => 'Territórios Palestinos Ocupados',        'TP' => 'Timor Leste',                           'TG' => 'Togo',
      'TO' => 'Tonga',                                  'TT' => 'Trinidad and Tobago',                   'TN' => 'Tunísia',
      'TM' => 'Turcomenistão',                          'TR' => 'Turquia',                               'TV' => 'Tuvalu',
      'UA' => 'Ucrânia',                                'UG' => 'Uganda',                                'UY' => 'Uruguai',
      'UZ' => 'Uzbequistão',                            'VU' => 'Vanuatu',                               'VA' => 'Vaticano',
      'VE' => 'Venezuela',                              'VN' => 'Vietnã',                                'ZM' => 'Zâmbia',
      'ZW' => 'Zimbábue'
    );
    return $paises;
}



// -------------------------------- ESTILOS ---------------------------- //


function get_estilos_musicais() {

    return array(
        'Axé',
        'Big Band',
        'Blues',
        'Bossa Nova',
        'Clássico',
        'Cumbia',
        'Dub',
        'Eletrônico',
        'Erudito',
        'Experimental',
        'Folk',
        'Folklore',
        'Forró',
        'Frevo',
        'Gospel',
        'HardCore',
        'Hip-Hop',
        'Indie',
        'Instrumental',
        'Jazz',
        'Metal',
        'MPB',
        'Pagode',
        'Pop',
        'Punk',
        'Quarteto',
        'R&B',
        'Rap',
        'Reggae',
        'Regional',
        'Rock',
        'Samba',
        'Sertanejo',
        'Ska',
        'Soul',
        'Tango',
        'Tecno Brega',
        'Vallenato'
    );

}



// ------------------------------ VALIDAÇÕES --------------------------- //

/**
 * Valida número de um CPF, desconsiderando posição
 * dos caracteres não numéricos, como pontos e hifen
 */
function is_a_valid_cpf($cpf) {
    $cpf = preg_replace('/[^0-9]/','',$cpf);

    if(strlen($cpf) !=  11 || preg_match('/^([0-9])\1+$/', $cpf)) {
        return false;
    }

    // 9 primeiros digitos do cpf
    $digit = substr($cpf, 0, 9);

    // calculo dos 2 digitos verificadores
    for($j=10; $j <= 11; $j++){
        $sum = 0;
        for($i=0; $i< $j-1; $i++) {
            $sum += ($j-$i) * ((int) $digit[$i]);
        }

        $summod11 = $sum % 11;
        $digit[$j-1] = $summod11 < 2 ? 0 : 11 - $summod11;
    }

    return $digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10]);
}

/**
 * Valida número de um CNPJ, desconsiderando posição
 * dos caracteres não numéricos, como pontos, hifens
 * e barra.
 */
function is_a_valid_cnpj($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    if(strlen($cnpj) != 14) {
        return false;
    }

    $mask = array(6,5,4,3,2,9,8,7,6,5,4,3,2);

    $a = array();
    $b = 0;
    for($i=0; $i < 12; $i++) {
        $a[] = (int) $cnpj[$i];
        $b += $a[$i] * $mask[$i+1];
    }

    $x = $b % 11;
    if($x < 2) {
        $a[12] = 0;
    } else {
        $a[12] = 11 - $x;
    }

    $b = 0;
    for($i=0; $i < 13; $i++) {
        $b += $a[$i] * $mask[$i];
    }

    $x = $b % 11;
    if($x < 2) {
        $a[13] = 0;
    } else {
        $a[13] = 11 - $x;
    }

    if($cnpj[12] == $a[12] && $cnpj[13] == $a[13]) {
        return true;
    }
    return false;
}


// ==================== SISTEMA DE PAGAMENTOS ======================= //
// registrando os post_status de pagamentos

// o produtor criou o evento com pagamento mas os editores ainda não revisaram
register_post_status('pay_pending_review', array(
	'label' => __('pagamentos não verificados','tnb'),
	'show_in_admin_status_list' => true, 
	'show_in_admin_all_list' => true, 
    'exclude_from_search' => true,
    'public' => true)); 

// os editores já revisaram mas o produtor ainda não deu ok
register_post_status('pay_pending_ok', array(
    'label' => __('pagamentos já verificados','tnb'),
	'show_in_admin_status_list' => true, 
	'show_in_admin_all_list' => true, 
    'exclude_from_search' => true,
    'public' => true)); 

function can_create_oportunidade_paga(){
    global $current_user;
    return isset($current_user->can_create_oportunidade_paga) && $current_user->can_create_oportunidade_paga == 'sim'; 
}


function update_contrato_inscricao($evento_id, $valor, $porcentagem, $contrato){
	
	if(!update_post_meta($evento_id, 'inscricao_contrato_valor', $valor))
		add_post_meta($evento_id, 'inscricao_contrato_valor', $valor);
		
	if(!update_post_meta($evento_id, 'inscricao_contrato_porcentagem', $porcentagem))
		add_post_meta($evento_id, 'inscricao_contrato_porcentagem', $valor);
		
	if(!update_post_meta($evento_id, 'inscricao_contrato', $contrato))
		add_post_meta($evento_id, 'inscricao_contrato', $contrato);
}

function get_contrato_inscricao($evento_id){
	$evento = get_post($evento_id);
	
	if($evento->post_status == 'pay_pending_review' && !get_post_meta($evento_id, 'inscricao_contrato',true)){
		$edata = get_oportunidades_data($evento_id);
		$valor = $edata['inscricao_valor'];
		$porcentagem = '50';
		$contrato = get_option('evento-pagamento-modelo-contrato');
		 
		update_contrato_inscricao($evento_id, $valor, $porcentagem, $contrato);
	}elseif($evento->post_status != 'pay_pending_review' && !get_post_meta($evento_id, 'inscricao_contrato',true)){
		return null;
	}
	
	$result['valor'] 		= get_post_meta($evento_id, 'inscricao_contrato_valor',true);
	$result['porcentagem'] 	= get_post_meta($evento_id, 'inscricao_contrato_porcentagem',true);
	$result['contrato'] 	= get_post_meta($evento_id, 'inscricao_contrato',true);
	
	return $result;
}

function set_contrato_inscricao_aceito($evento_id){
	global $wpdb;
	
	$evento = get_post($evento_id);
	$wpdb->query("UPDATE $wpdb->posts SET post_status = 'publish' WHERE ID = '$evento->ID'");
	
	add_post_meta($evento_id, 'inscricao_contrato_aceito', date('Y-m-d h:i:s'));
	
	$contrato = get_contrato_inscricao($evento_id);
	
	// atualiza o valor do campo valor da inscricao para o valor do contrato
	update_post_meta($evento_id, 'evento_inscricao_valor', $contrato['valor']);
	
	// salva o contrato com os campos substituidos
	update_post_meta($evento_id,'inscricao_contrato',get_contrato_inscricao_substituido($evento_id, $contrato['valor'], $contrato['porcentagem'], $contrato['contrato']));
	
	tnb_email_messages_produtor_aceitou_contrato_inscricao($evento_id);
}

function set_contrato_inscricao_recusado($evento_id){
	global $wpdb;
	$evento = get_post($evento_id);
	
	add_post_meta($evento_id, 'inscricao_contrato_recusado', date('Y-m-d h:i:s'));
	
	$wpdb->query("UPDATE $wpdb->posts SET post_status = 'pay_pending_review' WHERE ID = '$evento->ID'");
	
	tnb_email_messages_produtor_recusou_contrato_inscricao($evento_id);
}

function is_contrato_campos_locked($evento_id){
	$evento = get_post($evento_id);
	
	// se está aguardando aceitação do contrato pelo produtor ou se o evento está publicado e existe um contrato 
	return (  $evento->post_status == 'pay_pending_ok' || 
	 		 (($evento->post_status == 'publish' || $evento->post_status == 'draft') && get_contrato_inscricao($evento_id))
	 	   );
	
}


function print_inscricao_pay_button($evento_id, $artista_id){
	global $wpdb;
	$inscricao = $wpdb->get_var("SELECT meta_id FROM $wpdb->postmeta WHERE post_id = '$evento_id' AND meta_key = 'inscricao_pendente' AND meta_value = '$artista_id'");
	
	$edata = get_oportunidades_data($evento_id);
	if($inscricao && $edata['inscricao_cobrada']){
		$evento = get_post($evento_id);
		
		
		$transacao = $wpdb->get_row("SELECT * FROM pagseguro_transacoes WHERE Referencia = '$inscricao' ORDER BY DataTransacao DESC, insert_timestamp DESC LIMIT 1");
		
		?>
		<?php if($transacao->StatusTransacao == 'Aguardando Pagto'):
					$tipos_pagamentos['Boleto'] = __('Boleto Bancário','tnb');
					$tipos_pagamentos['Pagamento Online'] = __('Pagamento Online','tnb');
		?>
            <p>
                <strong><?php _e("INFORMAÇÕES DO PAGAMENTO", "tnb"); ?></strong>
                <br/>
    			<strong><?php _e('Status','tnb')?>:</strong> <?php _e('aguardando confirmação','tnb'); ?>.<br />
    			<strong><?php _e('Código','tnb')?>:</strong> <?php echo $transacao->TransacaoID; ?>. <br />
    			<strong><?php _e('Tipo','tnb')?>:</strong> <?php echo $tipos_pagamentos[$transacao->TipoPagamento]; ?>.
            </p>
			
		<?php endif; ?>
		
		<form class="pagseguro" target="pagseguro" method="post" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml" >
		<input type="hidden" name="email_cobranca" value="bruno@toquenobrasil.com.br">
		<input type="hidden" name="tipo" value="CP">
		<input type="hidden" name="moeda" value="BRL">
		<input type="hidden" name="encoding" value="UTF-8">
		
		<input type="hidden" name="item_id_1" value="<?php echo $evento_id; ?>">
		<input type="hidden" name="item_descr_1" value="<?php echo $evento->post_title;?>">
		<input type="hidden" name="item_quant_1" value="1">
		<input type="hidden" name="item_valor_1" value="<?php echo  str_replace(',', '', str_replace('.', '', number_format($edata['inscricao_valor'],2))); ?>">
		<input type="hidden" name="ref_transacao" value="<?php echo $inscricao; ?>">
		<input type="submit" name="submit" value="<?php _e('pagar inscrição','tnb');?>" />
		</form>
		
		
		<?php
	} 
}

function get_contrato_inscricao_substituicoes($include_artista = false){
	$result = array(
	'produtor-cadastro-id' 		=> 'id do produtor no sistema',
	'produtor-cadastro-data' 	=> 'data de cadastro do produtor',
	'produtor-nome'				=> 'nome do produtor',
	'produtor-documento' 		=> 'CNPJ/CPF do produtor',
	'produtor-email' 			=> 'email de cadastro do produtor',
	'produtor-telefone' 		=> 'nome do produtor',
	'produtor-pais' 			=> 'pais de residência do produtor',
	'produtor-estado' 			=> 'estado de residência do produtor',
	'produtor-cidade' 			=> 'cidade de residência do produtor',
	'produtor-url'				=> 'url do perfil do produtor',
	'produtor-link'				=> 'link com o nome do produtor para o perfil do mesmo',
	
	'evento-nome' 				=> 'nome do evento',
	'evento-descricao' 			=> 'descrição do evento',
	'evento-inicio' 			=> 'data de início do evento',
	'evento-fim' 				=> 'data de término do evento',
	'evento-inscricao-inicio' 	=> 'data de início das inscrições',
	'evento-inscricao-fim'		=> 'data de término das inscrições',
	'evento-estabelecimento'	=> 'nome do estabelecimento onde ocorrerá o evento',
	'evento-pais'				=> 'país onde ocorrerá o evento',
	'evento-estado'				=> 'estado onde ocorrerá o evento',
	'evento-cidade'				=> 'cidade onde ocorrerá o evento',
	'evento-url'				=> 'url da oportunidade', 
	'evento-link'				=> 'link com o nome da oportunidade para o endereço da mesma',
	
	'contrato-valor'			=> 'valor das inscrições estabelecido no contrato',
	'contrato-porcentagem'		=> 'porcentagem para o TNB estabelecida no contrato',
	'contrato-porcentagem-produtor'	=> 'porcentagem para o Produtor estabelecida no contrato (100% - porcentagem TNB)',
	'contrato-valor-tnb'		=> 'valor que ficará para o TNB para cada inscrição (relativo à porcentagem)'
	);
	
	if($include_artista)
		$result = $result + array(
			'artista-nome' => 'nome do artista',
			'artista-url' => 'url do perfil do artista',
			'artista-link' => 'link com o nome do artista para perfil do mesmo'
		);
	
	return $result;
}

function get_contrato_inscricao_substituido($evento_id, $valor, $porcentagem, $contrato){
	$result = pagamento_substitui_substituicoes($contrato, $evento_id, $valor, $porcentagem);
	return $result;
}

function pagamento_substitui_substituicoes($texto, $evento_id, $valor = null, $porcentagem = null, $artista = null){
	$evento = get_post($evento_id);
	$edata = get_oportunidades_data($evento_id);
	$produtor = get_user_by('id', $evento->post_author);
	
	$paises = get_paises();
	$estados = get_estados();
	
	$edata = (OBJECT) $edata;
	
	if(is_null($valor) || is_null($porcentagem)){
		$contrato = get_contrato_inscricao($evento_id);
		if(is_null($valor)) 
			$valor = $contrato['valor'];
		
		if(is_null($porcentagem)) 
			$porcentagem = $contrato['porcentagem'];
	}
	
	$substituicoes = array(
		'{produtor-cadastro-id}' 	=> $produtor->ID,
		'{produtor-cadastro-data}' 	=> $produtor->user_registered,
		'{produtor-nome}'			=> $produtor->display_name,
		'{produtor-documento}' 		=> ($produtor->cnpj ? $produtor->cnpj : $produtor->cpf),
		'{produtor-email}' 			=> $produtor->user_email,
		'{produtor-telefone}' 		=> $produtor->telefone,
		'{produtor-pais}' 			=> $paises[$produtor->origem_pais],
		'{produtor-estado}' 		=> ($produtor->origem_pais == 'BR' ? $estados[strtolower($produtor->origem_estado)] : $produtor->origem_estado),
		'{produtor-cidade}' 		=> $produtor->origem_cidade,
		'{produtor-url}'			=> get_author_posts_url($evento->post_author),
		'{produtor-link}'			=> '<a href="'.get_author_posts_url($evento->post_author).'">'.$produtor->display_name.'</a>',
		
		'{evento-nome}' 			=> $evento->post_title,
		'{evento-descricao}' 		=> $evento->post_content,
		'{evento-inicio}' 			=> $edata->br_inicio,
		'{evento-fim}' 				=> $edata->br_fim,
		'{evento-inscricao-inicio}' => $edata->br_insc_inicio,
		'{evento-inscricao-fim}'	=> $edata->br_insc_fim,
		'{evento-estabelecimento}'	=> $edata->local,
		'{evento-pais}'				=> $paises[$edata->sigla_pais],
		'{evento-estado}'			=> ($edata->sigla_pais == 'BR' ? $estados[strtolower($edata->estado)] : $edata->estado),
		'{evento-cidade}'			=> $edata->cidade,
		'{evento-url}'				=> get_permalink($evento_id),
		'{evento-link}'				=> '<a href="'.get_permalink($evento_id).'">'.$evento->post_title.'</a>',
		
		'{contrato-valor}'			=> get_valor_monetario($valor),
		'{contrato-porcentagem}'	=> $porcentagem.'%',
		'{contrato-porcentagem-produtor}'	=> (100-$porcentagem).'%',
		'{contrato-valor-tnb}'		=> get_valor_monetario($valor*$porcentagem/100)
	);
	
	if($artista)
		$substituicoes += array(
			'{artista-nome}' => $artista->display_name,
			'{artista-url}' => get_author_posts_url($artista->ID),
			'{artista-link}' => '<a href="'.get_author_posts_url($artista->ID).'">'.$artista->display_name.'</a>'
		);
	else
	$substituicoes += array(
			'{artista-nome}' => '',
			'{artista-url}' => '',
			'{artista-link}' => ''
			
	);
	
	
	foreach ($substituicoes as $de=>$para)
		$texto = str_replace($de, $para, $texto);
		
	return $texto;
}

function get_valor_monetario($numero){
	$numero = is_numeric($numero) ? $numero : 0;
	return money_format('%.2n', $numero);
}

/* RELATÓRIOS GERENCIAIS */
add_action('tnb_user_register', 'tnb_add_user_to_users_stats_table');
function tnb_add_user_to_users_stats_table($user_id){
	global $wpdb;
	$user = get_user_by('id', $user_id);
	
	$capability = is_artista($user_id) ? 'artista' : 'produtor';
	
	$wpdb->query("
	INSERT INTO {$wpdb->prefix}tnb_users_stats (
		reg_type,
		user_id,
		login,
		capability
	)VALUES(
		'insert',
		'$user_id',
		'$user->user_login',
		'$capability'
	)");
}

add_action('tnb_update_produtor','tnb_update_users_stats');
add_action('tnb_update_artista','tnb_update_users_stats');
function tnb_update_users_stats($user){
	global $wpdb;
	if(is_artista($user->ID)){
		$pais = $user->banda_pais;
		$estado = $user->banda_estado;
		$cidade = $user->banda_cidade;
	}else{
		$pais = $user->origem_pais;
		$estado = $user->origem_estado;
		$cidade = $user->origem_cidade;
	}
	
	$estado = addslashes($estado);
	$cidade = addslashes($cidade);
	
	$wpdb->query("
	UPDATE 
		{$wpdb->prefix}tnb_users_stats 
	SET
		pais = '$pais',
		estado = '$estado',
		cidade = '$cidade'
	WHERE
		reg_type = 'insert' AND
		user_id = '$user->ID'");
}

add_action('wp_login','tnb_count_login');
	
function tnb_count_login($user_login){
	global $wpdb;
	$day = date("Y-m-d"); 
	$user_id = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE user_login = '$user_login'");
	$stats_id = $wpdb->get_var("
	SELECT 
		ID
	FROM 
		{$wpdb->prefix}tnb_stats 
	WHERE 
		object_id = '$user_id' AND 
		day = '$day' AND
		type = 'user_logins'");
		
	if(!$stats_id){
		$wpdb->query("
			INSERT INTO {$wpdb->prefix}tnb_stats (
				day, 
				`count`, 
				type, 
				object_id
			)VALUES(
				'$day',
				1,
				'user_logins',
				'$user_id'
			)");
	}else{
		$wpdb->query("
			UPDATE
				{$wpdb->prefix}tnb_stats
			SET
				`count` = `count` + 1
			WHERE
				ID = $stats_id");
		
	}
	
	$login_data = $_SERVER['REMOTE_ADDR'].' | '.$_SERVER['HTTP_USER_AGENT'];
	tnb_log('login-user-info', $login_data, $user_id);
}

/**
 * 
 * Salva um log do tipo $log_type com as informações contidas em $log_data
 * @param string $log_type
 * @param $log_data
 */
function tnb_log($log_type, $log_data, $user_id = null){
    global $wpdb, $current_user;
    
    if(is_null($user_id))
        $user_id = $current_user->ID;
    
    if(is_array($log_data) || is_object($log_data))
        $log_data = addslashes(serialize($log_data));
    
    $log_type = addslashes($log_type);
    $q = "
    	INSERT INTO {$wpdb->prefix}tnb_logs (
    		`user_id`, 
    		`log_type`, 
    		`log_data`
    	) VALUES (
    		'$user_id', 
    		'$log_type', 
    		'$log_data'
    	)";
    $wpdb->query($q);
}

add_action('init', 'log_referer');
function log_referer(){
    session_start();
    if(!isset($_SESSION['referer_gravado'])){
        $_SESSION['referer_gravado'] = true;
        $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";
        $uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : ""; 
        
        tnb_log('referer', addslashes($_SERVER['REMOTE_ADDR'].' | '."$referer => $uri"));
    }
}
?>