<?php 

global $wp_query, $current_user, $wpdb;
$curauth = $wp_query->get_queried_object();
//_pr($current_user, true);
do_action('profile_view', $curauth->ID);

get_header('perfil-publico');
$capabiltyPropertyName = $wpdb->prefix . 'capabilities';

if(array_key_exists('artista', $curauth->$capabiltyPropertyName)) {
    include("user-profile-artista.php");
} else if(array_key_exists('produtor', $curauth->$capabiltyPropertyName)) {
    include("user-profile-produtor.php");
}

// Produtores enxergam o Kit do artista
if (current_user_can('select_artists') && array_key_exists('artista', $curauth->$capabiltyPropertyName)) {
    include('user-profile-kit.php');
}

?>


<?php
    $vendo_perfil = true;
    get_template_part('header-nav');
?>
        </div>
        <!-- #wrapper -->
    </body>
</html>
