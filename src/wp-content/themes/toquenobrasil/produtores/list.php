<?php
/* Template Name: Listagem dos produtores */
?>

<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div id="producers" class="span-14 prepend-1 right-colborder">
	<div id="producers-title" class="item green clearfix">
		<div class="title pull-1">
			<div class="shadow"></div>
			<h1>Produtores</h1>
			<div class="clear"></div>
		</div>
	</div>

    <?php 
    global $wp_query;
		
    $paged = $wp_query->get('paged'); 
    $per_page  = get_option('posts_per_page');
    $ofset = $per_page*($paged == 0 ? 0 : $paged-1 );
    $artistas = get_produtores( "LIMIT $ofset,$per_page" , 'user_registered DESC', get_query_var('s')) ;
    $found = count(get_produtores( false , 'user_registered DESC', get_query_var('s')));
    $pagination = new ListControl($paged , (int)$per_page , $found);

    if(sizeof($artistas)>0):
      foreach ($artistas as $artista): 
  ?>

        <div id="artist-<?php echo $artista->ID; ?>" class="artist span-6">
          <div id="artist-<?php echo $artista->ID; ?>-content" class="content clearfix">
            <div id="artist-<?php echo $artista->ID; ?>-avatar" class="avatar span-2">
              <a href="<?php echo get_author_posts_url($artista->ID)?>" class="avatar" title=" <?php _e('Ver o perfil do produtor','tnb');  ?>">
                <?php echo get_avatar($artista->ID, 70); ?>
              </a>
            </div>
            <div id="artist-<?php echo $artista->ID; ?>-name" class=" span-3">
              <a href="<?php echo get_author_posts_url($artista->ID)?>" class="name" title="<?php _e('Ver o perfil do produtor','tnb');  ?>">
                <?php echo $artista->display_name; ?>
              </a>
            </div>
          </div>
        </div>

      <?php endforeach; ?>

    <?php else: ?>
        
        <div class="span-12 last">
            <h2 class="span-10">
            Nenhum produtor encontrado
            </h2>
        </div>
        
    <?php endif; ?>

  <div id="posts-navigation">
    <?php $pagination->next_link('<span id="anteriores"><span>Próximos</span></span>'); ?>            
    <?php $pagination->previous_link('<span id="proximos"><span>Anteriores</span></span>'); ?>
  </div><!-- #posts-navigation -->
    
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
