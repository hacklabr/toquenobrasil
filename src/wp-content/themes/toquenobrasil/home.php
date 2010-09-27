<?php get_header(); ?>

<div class="span-9 prepend-1">
  <div class="item green">
    <div class="title">
      <div class="shadow"></div>
      <h1>Artistas</h1>
      <div class="clear"></div>
    </div>
    <div class="background">
      <div class="signup">        
        <a href="<?php bloginfo('url');?>/cadastre-se">Cadastre-se!</a>
        <div class="shadow"></div>       
      </div>
      
        <p><?php echo get_theme_option('home_artists_text'); ?></p>        
      
      <div class="clear"></div>
    </div>
  </div>

  <div class="item blue">
    <div class="title">
      <div class="shadow"></div>
      <h1>Produtores</h1>
      <div class="clear"></div>
    </div>
    <div class="background">
      <div class="signup">        
        <a href="<?php bloginfo('url');?>/cadastre-se">Cadastre-se!</a>
        <div class="shadow"></div>       
      </div>
      <p><?php echo get_theme_option('home_producers_text'); ?></p>
    </div>
  </div>
</div>

<div class="prepend-1 span-12 append-1 last">
  <div class="item yellow">
    <div class="title">
      <div class="shadow"></div>
      <h1>Bem-vindo</h1>
      <div class="clear"></div>
    </div>
    <div class="background">
      <p><?php echo get_theme_option('home_welcome_text'); ?></p>
      <?php echo get_theme_option('home_welcome_video'); ?>
      <div class="prepend-top"></div>
      
    </div>
  </div>
</div>

<?php get_footer(); ?>