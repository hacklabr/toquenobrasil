<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
  <div class="item green">
    <div class="title pull-1">
      <div class="shadow"></div>
      <h1>Eventos</h1>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>

  <p id="intro">Aqui você encontra festivais que estão buscando bandas novas! Inscreva-se clicando em "Quero Tocar!", seus dados serão automaticamente enviados ao produtor do evento.</p>

  <?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>
    <div class="post">
      <h2 class="span-14"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>        
      
      <?php get_template_part('type-evento', 'block'); ?>
      
    </div>

  <?php endwhile; ?>
    <div id="posts-navigation">
      <?php previous_posts_link('<span id="anteriores"><span>Próximos eventos</span></span>'); ?>
      <?php next_posts_link('<span id="proximos"><span>Eventos anteriores</span></span>'); ?>            
    </div><!-- #posts-navigation --> 
  <?php endif; ?>

</div>
<?php get_sidebar("blog"); ?>

<?php get_footer(); ?>
