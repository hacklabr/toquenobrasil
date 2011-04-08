<?php
/*
    Template Name: Home do site
*/
?>

<?php get_header(); ?>



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
            <a href="<?php echo get_theme_option('iam-groupie'); ?>" title="<?php _e("Sou Fã", "tnb"); ?>">
                <?php theme_image("sou-fa.png", array("alt" => "Sou Fã")); ?>
            </a>
            <a href="<?php echo bloginfo('siteurl'); ?>/cadastro" title="<?php _e("Cadastre-se", "tnb"); ?>">
                <?php theme_image("cadastre-se.png", array("alt" => "Cadastre-se")); ?>
            </a>
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
