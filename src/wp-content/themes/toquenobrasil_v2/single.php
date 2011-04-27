<?php get_header(); ?>

<section id="blog" class="grid_11 clearfix box-shadow">
    <h1 class="title">Blog</h1>

    <?php if ( have_posts() ) : the_post(); ?>
        <article id="<?php echo basename(get_permalink()); ?>" class="clearfix">
            <p class="post-meta date"><?php the_time("d.m.Y"); ?></p>
            <div class="content">
                <h1><a href="<?php the_permalink(); ?>" title="<?php echo basename(get_permalink()); ?>"><?php the_title(); ?></a></h1>
                <iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:30px;" allowTransparency="true"></iframe>
                <ul class="post-categories clearfix">
                    <?php the_category(); ?>
                </ul>
                <?php the_content(); ?>
                <p class="post-tags">
                    <?php the_tags("Tags: ", " ", " "); ?>
                </p>
                <hr/>
            </div>
            <?php comments_template(); ?>
        </article>
    <?php endif; ?>
</section>

<?php get_sidebar('main-sidebar'); ?>

<?php get_footer(); ?>
