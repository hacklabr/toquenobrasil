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
include(TEMPLATEPATH . '/produtores/produtor-actions.php');


// interface para arrumar usuários que não estão com dados de país, estado e cidade corretos
include(TEMPLATEPATH . '/includes/admin_fix_usuarios_cidades.php');


add_filter( 'author_link', 'tnb_author_link', 10, 3);



function tnb_author_link($link, $author_id, $author_nicename) {
    return get_bloginfo('url') . '/rede/' . $author_nicename;
}

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

# JAVASCRIPTS
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

        "rede/([^/]+)/eventos/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=gerenciar-eventos",
        "rede/([^/]+)/fotos/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=fotos-do-artista",
        "rede/([^/]+)/eventos/novo/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=cadastro-de-evento",
        "rede/([^/]+)/eventos/([^/]+)/editar/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=cadastro-de-evento" . "&event_name=" . $wp_rewrite->preg_index(2),
        "rede/([^/]+)/eventos/([^/]+)/inscricoes/?$" => "index.php?author_name=" . $wp_rewrite->preg_index(1) . "&tpl=" . "&event_name=" . $wp_rewrite->preg_index(2),
        "rede/editar/(produtor|artista)$" => 'index.php?tpl=edit&reg_type=' . $wp_rewrite->preg_index(1),

    	"cadastre-se/(produtor|artista)$" => 'index.php?tpl=register&reg_type=' . $wp_rewrite->preg_index(1),

        "(artistas|produtores)(/page/?([0-9]{1,}))?/?$" => 'index.php?tpl=list_author&reg_type='. $wp_rewrite->preg_index(1). '&paged=' . $wp_rewrite->preg_index(3),


        'eventos/?$' => 'index.php?tpl=list&post_type=eventos',
        'eventos/page/?([0-9]{1,})/?$' => 'index.php?tpl=list&post_type=eventos&paged='.$wp_rewrite->preg_index(1),
        'eventos/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tpl=list&post_type=eventos&feed='.$wp_rewrite->preg_index(1),
        'eventos/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tpl=list&post_type=eventos&feed='.$wp_rewrite->preg_index(1)

    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action('generate_rewrite_rules', 'custom_url_rewrites');

function custom_query_vars($public_query_vars) {
    $public_query_vars[] = "tpl";
    $public_query_vars[] = "reg_type";
    $public_query_vars[] = "event_name";

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

        case 'list':
        if ($wp_query->get('post_type') == 'eventos') {
            include( TEMPLATEPATH . '/eventos/list.php' );
        }
        exit;
        break;

        case 'register':
        include( TEMPLATEPATH . '/register.php' );
        exit;
        break;

        case 'edit':
            include( TEMPLATEPATH . "/rede/edit-{$reg_type}.php" );
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
            if (file_exists( TEMPLATEPATH . "/{$reg_type}/list.php" )) {
                include( TEMPLATEPATH . "/{$reg_type}/list.php" );
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


function get_artistas( $limit = false, $order=false, $search = false) {
    return tnb_get_users('artista', $limit, $order, $search);
}

function get_produtores( $limit = false, $order=false, $search = false) {
    return tnb_get_users('produtor', $limit, $order, $search);
}

function tnb_get_users( $role = "", $limit = false, $order=false, $search = false) {
    global $wpdb;

    if(!$order)
        $order = "ID";
    if(is_numeric($limit))
        $limit = "LIMIT $limit";
    elseif(!$limit)
        $limit = '';

    $prefix = $wpdb->prefix;

    $searchQuery = $search ? $wpdb->prepare("AND display_name LIKE %s", "%$search%") : "";

    $q = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '{$prefix}capabilities' AND meta_value LIKE '%\"$role\"%' ORDER BY $order";
    $not_q = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'tnb_inactive' AND meta_value = 1";
    $query = "SELECT * FROM {$wpdb->users} WHERE ID IN($q) AND ID NOT IN ($not_q) $searchQuery ORDER BY $order $limit";

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

function is_produtor(){
    if( is_user_logged_in() ){
        global $current_user;
        return in_array('produtor' ,$current_user->roles);
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
        if($post->post_type == 'post')
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

    if($post->post_parent > 0 && $meta = get_post_meta($post->post_parent, 'forcar_tos', true)) {
        return $meta;
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

    if($post->post_parent > 0 && $meta = get_post_meta($post->post_parent, 'forcar_condicoes', true)) {        
        return $meta;
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

    if($post->post_parent > 0 && $meta = get_post_meta($post->post_parent, 'forcar_restricoes', true)) {
        return $meta;
    } else {
        return get_post_meta($post->ID, 'evento_restricoes', true);
    }

}



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

// Tamanho customizado de imagens
add_image_size('banner-horizontal',550,150,false);


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
    
    
    
    $reg_type = (isset($_POST['type']) ? $_POST['type'] :  $wp_query->get('reg_type'));

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

?>
