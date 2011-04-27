<?php get_header(); ?>

<section id="blog" class="grid_11 clearfix box-shadow">
    <?php if ( have_posts() ) : the_post(); ?>
        <h1 class="title"><?php the_title(); ?></h1>
        <article id="<?php echo basename(get_permalink()); ?>">
            <div class="content">
                <iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:30px;" allowTransparency="true"></iframe>
                <?php the_content(); ?>
            </div>
        </article>
    <?php endif; ?>
</section>

<?php get_sidebar('main-sidebar'); ?>

<?php get_footer(); ?>
