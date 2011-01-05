<?php 

global $wp_query, $current_user, $wpdb;
$curauth = $wp_query->get_queried_object();

get_header();

$capabiltyPropertyName = $wpdb->prefix . 'capabilities';

if(array_key_exists('artista', $curauth->$capabiltyPropertyName)) {
    include("rede/profile-artista.php");
} else if(array_key_exists('produtor', $curauth->$capabiltyPropertyName)) {
    include("rede/profile-produtor.php");
}

?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
