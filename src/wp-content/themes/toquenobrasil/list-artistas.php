<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
  <div class="item green">
    <div class="title pull-1">
      <div class="shadow"></div>
      <h1>Artistas</h1>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>

  <p id="intro">Aqui você encontra festivais que estão buscando bandas novas! Inscreva-se clicando em "Quero Tocar!", seus dados serão automaticamente enviados ao produtor do evento.</p>

  <?php 
      global $wp_query;
      $paged = $wp_query->get('paged'); 
//      $paged = ( $paged == 0 ? 1 :$paged ); 
      $per_page  =  2 ;//get_option('posts_per_page');
      $ofset = $per_page*($paged == 0 ? 0 : $paged-1 );
      $artistas = get_artistas( "LIMIT $ofset,$per_page" , 'user_registered DESC') ;
      $found = count(get_artistas());
      $pagination = new ListControl($paged , (int)$per_page , $found);
      if(sizeof($artistas)>0):
          foreach ($artistas as $artista): 
  ?>
        <div class="post">
          <h2 class="span-14"><a href="<?php echo get_author_posts_url($artista->ID)?>"><?php echo get_user_meta($artista->ID, 'banda', true); ?></a></h2>        
          <div class="thumb span-4">
            <?php echo get_avatar($artista->ID, 150); ?>
          </div>
          <div class="clear"></div>
          
        </div>
          
      <?php endforeach; ?>
        <div id="posts-navigation_">
          
          <?php $pagination->previous_link() ;?>
          <?php $pagination->next_link() ;?>
                      
        </div><!-- #posts-navigation --> 
  <?php endif; ?>

</div>
<?php get_sidebar("blog"); ?>

<?php get_footer(); ?>
