<?php get_header(); ?>

<section id="blog" class="grid_11 box-shadow clearfix">
    <h1 class="title">categoria: <?php echo single_cat_title() ?></h1>
    
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <?php get_template_part( 'loop', 'category' ); ?>
    <?php endwhile; ?>
        <div class="navigation clearfix">
            <?php previous_posts_link('<div class="left-navigation alignleft">Anterior</div>'); ?>
            <?php next_posts_link('<div class="right-navigation alignright">Próxima</div>'); ?>
        </div>        
    <?php endif; ?>
        
</section>

<?php get_sidebar('main-sidebar'); ?>

<?php get_footer(); ?>