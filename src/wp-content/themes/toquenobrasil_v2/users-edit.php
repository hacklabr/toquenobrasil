<?php
$section = get_query_var('section') ? get_query_var('section') : 'login';
$currentSection = array($section => 'class="current"');
global $current_user, $profileuser; 

$profileuser = get_user_by( 'slug', get_query_var('author_name') );

$itsMe = $current_user->ID == $profileuser->ID;

if(!current_user_can('edit_users') && !$itsMe )
    wp_redirect(get_bloginfo('url'));

if ( $_GET['delete_profile'] && wp_verify_nonce($_GET['delete_profile'], 'delete_profile_' . $profileuser->ID ) ) {
    include(ABSPATH . '/wp-admin/includes/user.php');
    
    $to = get_bloginfo('admin_email');
    wp_mail($to, '[Toquenobrasil] Perfil apagado', "O usuário $profileuser->display_name acabou de apagar seu perfil", 'Content-Type: text/html');
    
    wp_delete_user($profileuser->ID);
    wp_redirect(get_bloginfo('siteurl'));
    exit;

}
$msg = array('notice' => false, 'error' => false);

wp_enqueue_script('users-edit', get_stylesheet_directory_uri(). '/js/users-edit.js',array('jquery-ui-sortable'));
wp_enqueue_style('users-edit', get_stylesheet_directory_uri(). '/css/users-edit.css');

include "includes/users-actions.php";
include "includes/users-actions-$section.php";
?>

<?php get_header(); ?>

<section id="<?php echo $profileuser->user_login; ?>" class="profile grid_11 box-shadow clearfix">
    <header class="clearfix">
        <h1 class="profile-name">
            <span class="bg-yellow"><?php echo $profileuser->display_name; ?></span>
        </h1>

        <nav class="user-nav clearfix">
            <ul class="clearfix">
                <li><a <?php echo $currentSection['login']; ?> href="<?php echo get_author_posts_url($profileuser->ID); ?>/editar/login/" ><?php _e("Login", "tnb"); ?></a></li>
                <?php if (is_artista($profileuser->ID)): ?>
                    <li><a <?php echo $currentSection['banda']; ?> href="<?php echo get_author_posts_url($profileuser->ID); ?>/editar/banda/" ><?php _e("Kit", "tnb"); ?></a></li>
                <?php else: ?>
                    <li><a <?php echo $currentSection['produtor']; ?> href="<?php echo get_author_posts_url($profileuser->ID); ?>/editar/produtor/" ><?php _e("Informações", "tnb"); ?></a></li>
                <?php endif; ?>
                <li><a <?php echo $currentSection['musicas']; ?> href="<?php echo get_author_posts_url($profileuser->ID); ?>/editar/musicas/" ><?php _e("Músicas", "tnb"); ?></a></li>
                <li><a <?php echo $currentSection['fotos']; ?> href="<?php echo get_author_posts_url($profileuser->ID); ?>/editar/fotos/" ><?php _e("Fotos", "tnb"); ?></a></li>
                <li><a <?php echo $currentSection['videos']; ?> href="<?php echo get_author_posts_url($profileuser->ID); ?>/editar/videos/" ><?php _e("Vídeos", "tnb"); ?></a></li>
                <li><a <?php echo $currentSection['oportunidades']; ?> href="<?php echo get_author_posts_url($profileuser->ID); ?>/editar/oportunidades/" ><?php _e("Oportunidades", "tnb"); ?></a></li>
            </ul>
        </nav>
    </header>
    <!-- .clearfix -->

    <?php print_msgs($msg); ?>

    <?php include "users-edit-$section.php"; ?>
</section>
<!-- #profile -->

<?php get_sidebar('main-sidebar'); ?>

<?php get_footer(); ?>
