<?php
/*
  Template Name: Home do blog
*/
global $in_blog;
$in_blog = true;

?>

<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder clearfix">
  <?php if ( have_posts() ) : the_post(); ?>
    <div class="item green clearfix">
      <div class="title pull-1 clearfix">
        <div class="shadow"></div>
        <h1><?php the_title(); ?></h1>
      </div>
      <?php the_content(); ?>
    </div>
    
    <?php
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $wp_query = new WP_Query();
      $wp_query->query('post_type=post'.'&paged='.$paged);
    ?>
    
    <?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
    
      <div class="post clearfix">
        <div class="post-time span-14 clearfix">
          <div class="shadow"></div>
          <div class="data"><div class="dia"><?php the_time("d"); ?></div><div class="mes-ano"><?php the_time("m/Y");?></div></div>
        </div>
        <div class="span-2 post-comments">
          <span><?php comments_number("0", "1", "%"); ?></span>
        </div>
        <div class="span-12 last clearfix">
          <h2 class="span-10">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <div class="clear"></div>
          <div class="post-categories">
            <?php the_category(' ') ?>
          </div>
          <div class="clear"></div>
          <?php the_excerpt(); ?>
          <div class="clear"></div>
          <div class="post-tags">
            <?php the_tags(" "," "," "); ?>
          </div>
        </div>
        <div class="clear"></div>
      </div><!-- .post -->
      
    <?php endwhile;?>
    
	 	<div id="posts-navigation">
        	<?php previous_posts_link('<span id="anteriores"><span>Próximos posts</span></span>'); ?>
            <?php next_posts_link('<span id="proximos"><span>Posts anteriores</span></span>'); ?>            
        </div><!-- .navigation --> 
	 <?php endif; wp_reset_query(); ?>
  <?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
