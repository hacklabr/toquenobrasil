<?php
/* Template Name: Listagem dos artistas */
?>

<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div id="artists" class="span-14 prepend-1 right-colborder">
  <div id="artists-title" class="item green clearfix">
    <div class="title pull-1 clearfix">
      <div class="shadow"></div>
      <h1><?php _e('Artistas','tnb'); ?></h1>
    </div>
  </div>

  <p id="intro">
    
    <?php if (is_search()): ?>
        Resultado da busca por "<?php echo $_GET['s']; ?>"
    <?php else : ?>
        <?php echo get_page_by_path('artistas')->post_content; ?>
    <?php endif; ?>
  </p>

  <?php 
    global $wp_query;
		
    $paged = $wp_query->get('paged'); 
    $per_page  = get_option('posts_per_page');
    $ofset = $per_page*($paged == 0 ? 0 : $paged-1 );
    $artistas = get_artistas( "LIMIT $ofset,$per_page" , 'user_registered DESC', get_query_var('s')) ;
    $found = count(get_artistas( false , 'user_registered DESC', get_query_var('s')));
    $pagination = new ListControl($paged , (int)$per_page , $found);

    if(sizeof($artistas)>0):
      foreach ($artistas as $artista): 
  ?>

        <div id="artist-<?php echo $artista->ID; ?>" class="artist span-6">
          <div id="artist-<?php echo $artista->ID; ?>-content" class="content clearfix">
            <div id="artist-<?php echo $artista->ID; ?>-avatar" class="avatar span-2">
              <a href="<?php echo get_author_posts_url($artista->ID)?>" class="avatar" title=" <?php _e('Ver o perfil do artista/banda','tnb'); echo get_user_meta($artista->ID, 'banda', true); ?>">
                <?php echo get_avatar($artista->ID, 70); ?>
              </a>
            </div>
            <div id="artist-<?php echo $artista->ID; ?>-name" class=" span-3">
              <a href="<?php echo get_author_posts_url($artista->ID)?>" class="name" title="<?php _e('Ver o perfil do artista/banda','tnb'); echo get_user_meta($artista->ID, 'banda', true); ?>">
                <?php echo get_user_meta($artista->ID, 'banda', true); ?>
              </a>
            </div>
          </div>
        </div>

      <?php endforeach; ?>

    <?php else: ?>
        
        <div class="span-12 last">
            <h2 class="span-10">
            Nenhum artista encontrado
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
