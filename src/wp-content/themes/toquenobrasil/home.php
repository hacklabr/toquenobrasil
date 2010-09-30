<?php get_header(); ?>

<div class="span-9 prepend-1">
  <div class="item green clearfix">
    <div class="title clearfix">
      <div class="shadow"></div>
      <h1><?php _e('Artistas/Bandas','tnb'); ?></h1>
    </div>
    <div class="background clearfix">
      <div class="signup">        
        <a href="<?php bloginfo('url');?>/cadastre-se/artista">
          <?php _e('Cadastre-se!','tnb'); ?>
        </a>
        <div class="shadow"></div>       
      </div>
        <p><?php echo get_theme_option('home_artists_text'); ?></p>        
    </div>
  </div>

  <div class="item blue clearfix">
    <div class="title clearfix">
      <div class="shadow"></div>
      <h1><?php _e('Produtores','tnb'); ?></h1>
    </div>
    <div class="background clearfix">
      <div class="signup">        
        <a href="<?php bloginfo('url');?>/cadastre-se/artista">
          <?php _e('Cadastre-se!','tnb'); ?>
        </a>
        <div class="shadow"></div>       
      </div>
      <p><?php echo get_theme_option('home_producers_text'); ?></p>
    </div>
  </div>
</div>

<div class="prepend-1 span-12 append-1 last">
  <div class="item yellow clearfix">
    <div class="title clearfix">
      <div class="shadow"></div>
      <h1><?php _e('Bem-vindo','tnb'); ?></h1>
    </div>
    <div class="background clearfix">
      <p><?php echo get_theme_option('home_welcome_text'); ?></p>
      <?php echo get_theme_option('home_welcome_video'); ?>
      <div class="prepend-top"></div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
