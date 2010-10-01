<?php
/* Template Name: Listagem dos eventos */
?>

<?php get_header(); ?>

<div class="prepend-top"></div>

<div id="events" class="span-14 prepend-1 right-colborder">
	<div class="item green clearfix">
		<div class="title pull-1 clearfix">
			<div class="shadow"></div>
			<h1>Eventos</h1>
		</div>
	</div>

	<p id="intro">
        <?php 
          echo get_page_by_path('eventos')->post_content;
        ?>
    </p>

	<?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>
		<div id="event-<?php echo the_ID(); ?>" class="event">
			<h2 class="span-14"><a href="<?php the_permalink(); ?>" title='<?php _e('Visitar página do evento', 'tnb'); ?>'><?php the_title(); ?></a></h2>        
			<?php get_template_part('type-evento', 'block'); ?>
		</div>

	<?php endwhile; ?>
	
	<div id="posts-navigation">
		<?php previous_posts_link('<span id="anteriores"><span>Próximos eventos</span></span>'); ?>
		<?php next_posts_link('<span id="proximos"><span>Eventos anteriores</span></span>'); ?>            
	</div> 
	
	<?php endif; ?>

</div>

<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("tnb-sidebar");?>
    </div>
</div>

<?php get_footer(); ?>
