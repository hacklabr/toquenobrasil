<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
  <?php if ( have_posts() ) : the_post(); ?>
    <div class="item green">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h1><?php the_title(); ?></h1>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>

    <div class="post">
      <div class="post-time span-14">
        <div class="shadow"></div>
        <span><?php the_time("d/m/y"); ?></span>
      </div>
      <div class="span-2 post-comments">
        <span><?php comments_number("0", "1", "%"); ?></span>
      </div>
      <div class="span-12 last">
        <div class="post-categories">
          <?php the_category(' ') ?>
        </div>
        <div class="clear"></div>
        <?php the_content(); ?>
        <div class="clear"></div>
        <div class="post-tags">
          <?php the_tags(" "," "," "); ?>
        </div>
      </div>
      <div class="clear"></div>
    </div>
  <?php endif; ?>
</div>

<?php get_sidebar("blog"); ?>

<?php get_footer(); ?>