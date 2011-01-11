<?php
/* Template Name: Listagem dos eventos */
if (!is_search()) {
    global $query_string;
    query_posts($query_string . '&post_parent=0');
}
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
        <?php if (is_search()): ?>
            Resultado da busca por "<?php echo $_GET['s']; ?>"
        <?php else : ?>
            <?php echo get_page_by_path('eventos')->post_content; ?>
        <?php endif; ?>
        
    </p>

	<?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>
		
        <div id="event-<?php echo the_ID(); ?>" class="event">
			<h2 class="span-14"><a href="<?php the_permalink(); ?>" title='<?php _e('Visitar página do evento', 'tnb'); ?>'><?php the_title(); ?></a></h2>        
			<?php include('evento-list-item.php'); ?>
            
            <?php
            // sub eventos
            if (get_post_meta(get_the_ID(), 'superevento', true) == 'yes') : 
                
                $query_args = array(
                    'post_type' => 'eventos',
                    'post_parent' => get_the_ID(),
                    'meta_key' => 'aprovado_para_superevento',
                    'meta_value' => get_the_ID(),
                    'orderby' => 'rand'
                );
                
                $subevents = get_posts($query_args);
                $len_subevents = 0;
                foreach ($subevents as $sub): $len_subevents++;?>
                <div class="prepend-1">
                    <h3><a href="<?php echo get_permalink($sub->ID); ?>" title="<?php echo $sub->post_title;?>"><?php echo $sub->post_title;?></a></h3>
                    <?php $evento_list_item_id = $sub->ID;                 
                    include('evento-list-item.php'); ?>
                </div>
                <?php endforeach;
                if($len_subevents >= 5):?>
                    <a href="<?php the_permalink(); ?>" title='<?php _e('Mostrar mais...', 'tnb'); ?>'><?php _e('Mostrar mais');?>...</a>
                <?php endif;
            endif;
            ?>
		</div>

	<?php endwhile; ?>
	
	<div id="posts-navigation">
		<?php previous_posts_link('<span id="anteriores"><span>Próximos eventos</span></span>'); ?>
		<?php next_posts_link('<span id="proximos"><span>Eventos anteriores</span></span>'); ?>            
	</div> 
	
    <?php else: ?>
        
        <div class="span-12 last">
            <h2 class="span-10">
            Nenhum evento encontrado
            </h2>
        </div>
    
	<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
