<?php #global $wp_query; print_r($wp_query); die; 
get_header(); ?>

<section id="blog" class="grid_11 clearfix box-shadow">
    <h1 class="title">Blog</h1>
    
    <?php
        //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        //$wp_query = new WP_Query();
        //$wp_query->query('post_type=post&paged='.$paged);
        //var_dump(is_search());
        //print_r($wp_query);
    ?>
    
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php get_template_part('loop', 'blog'); ?>
        <?php endwhile; ?> 
        <div class="navigation clearfix">
            <?php previous_posts_link('<div class="left-navigation alignleft">Anterior</div>'); ?>
            <?php next_posts_link('<div class="right-navigation alignright">Próxima</div>'); ?>
        </div>        
    <?php else :  ?>
        <p class="text-center"><?php _e("Nenhum resultado encontrado.", "tnb"); ?></p>
    <?php endif; ?>
</section>

<?php get_sidebar('blog'); ?>

<?php get_footer(); ?>
