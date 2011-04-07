<article id="<?php echo basename(get_permalink()); ?>" class="clearfix">
    <p class="post-meta date"><?php the_time("d.m.Y"); ?></p>
    <div class="content">
        <h1><a href="<?php the_permalink(); ?>" title="<?php echo basename(get_permalink()); ?>"><?php the_title(); ?></a></h1>
        <ul class="post-categories clearfix">
            <?php the_category(); ?>
        </ul>
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail('thumb'); ?>
            </div>
        <?php endif; ?>
        <?php the_excerpt(); ?>
        <p class="post-tags">
            <?php the_tags("Tags: ", " ", " "); ?>
        </p>
        <hr/>
    </div>
</article>