<?php 


	get_header(); 
?>

<div class="prepend-top"></div>

<?php if ( have_posts() ) : the_post(); ?>
	
	<?php 
		$selecionados = get_post_meta( get_the_ID(), 'selecionado') ;
		$inscritos = get_post_meta( get_the_ID(), 'inscrito') ;
		
		$num_selecionados = count($selecionados);
		$num_inscritos = count($inscritos);
	?>
	<div id="event-<?php echo the_ID(); ?>" class="event span-14 prepend-1 right-colborder">
		<div id="event-<?php echo the_ID(); ?>-title" class="item green clearfix">
			<div class="title pull-1 clearfix">
				<div class="shadow"></div>
				<h1><?php the_title(); ?></h1>
			</div>
		</div>

		<div id="event-<?php echo the_ID(); ?>-content" class="clearfix">
			<?php get_template_part('type-evento', 'block'); ?>
		</div>

	</div>
<?php endif; ?>

<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("tnb-sidebar");?>
    </div>
</div>
<?php get_footer(); ?>
