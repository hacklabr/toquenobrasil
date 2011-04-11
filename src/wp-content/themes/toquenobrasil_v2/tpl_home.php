<?php
/*
    Template Name: Home do site
*/
?>

<?php get_header(); ?>

    <div class="clear"></div>

    <div class="home-buttons">
        <a href="<?php echo get_theme_option('iam-artist'); ?>" class="btn-home-blue" title="<?php _e("artista", "tnb"); ?>"><?php _e("artista", "tnb"); ?></a>
        <a href="<?php echo get_theme_option('iam-producer'); ?>" class="btn-home-blue" title="<?php _e("produtor", "tnb"); ?>"><?php _e("produtor", "tnb"); ?></a>
        <a href="<?php echo get_theme_option('iam-groupie'); ?>" class="btn-home-blue" title="<?php _e("fã", "tnb"); ?>"><?php _e("fã", "tnb"); ?></a>
        <a href="<?php echo get_theme_option('iam-brand'); ?>" class="btn-home-blue" title="<?php _e("marca", "tnb"); ?>"><?php _e("marca", "tnb"); ?></a>
        <a href="<?php echo get_theme_option('signup'); ?>" class="btn-home-yellow" title="<?php _e("cadastro", "tnb"); ?>"><?php _e("cadastro", "tnb"); ?></a>
    </div>

    <?php if(function_exists("insert_post_highlights")) insert_post_highlights(); ?>
    
    <article class="grid_7">
        <div id="player" class="box box-shadow">
            <?php
                $musicasRecentes = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_parent IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'music') ORDER BY RAND() LIMIT 10");
            
                printCompactPlayer(ids2playlist($musicasRecentes));
            ?>
            
            <h3 class="text-center">
                <?php echo get_theme_option('slogan'); ?>
            </h3>
        </div>
    </article>

    <div class="grid_9">
        <div class="home-banner home-banner-top box box-shadow">
            <?php WPEB_printBanner('home superior')?>
        </div>
    </div>

    <div class="grid_9">
        <div class="home-banner home-banner-bottom box box-shadow">
            <?php WPEB_printBanner('home inferior')?>
        </div>
    </div>
    

<?php get_footer(); ?>
