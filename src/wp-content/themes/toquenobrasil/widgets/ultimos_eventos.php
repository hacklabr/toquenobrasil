<?php

class ultimosEventos extends WP_Widget {
	
  function ultimosEventos () {
    $widget_ops = array('classname' => 'ultimos_eventos', 'description' => 'Lista últimos eventos publicados');
    $this->WP_Widget('ultimos_eventos', 'Ultimos Eventos Publicados', $widget_ops);
  }
	
  function widget($args, $instance) {
    extract($args);

    echo $before_widget; ?>
      <div class="title clearfix">
        <div class="shadow"></div>
        <h2><?php echo $instance['titulo'] ?></h2>
      </div>
			
      <div id="last-events" class="content">
        <?php
	  query_posts("post_type=eventos&posts_per_page={$instance['qnt_listado']}&orderby=date&post_parent=0&order=DESC");
          //The Loop
          if ( have_posts() ) : while ( have_posts() ) : the_post();
	?>
        <div id="event-<?php the_ID(); ?>" class="clearfix">
          <div id="thumb" class="span-2">
            <?php if ( has_post_thumbnail() ) : ?>
              <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_post_thumbnail(array(70,70), 'eventos'); ?>
              </a>
            <?php else : ?>
              <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php theme_image("thumb-widget.png") ?>
              </a>
            <?php endif; ?>
          </div><!-- .thumb -->

          <p class="span-5 last">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
          </p>
        </div>
        <?php endwhile; endif;
          //Reset Query
          wp_reset_query();
	?>
      </div>
    <?php echo $after_widget;	
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['qnt_listado'] = strip_tags($new_instance['qnt_listado']);
    $instance['titulo'] = strip_tags($new_instance['titulo']);
    return $instance;
  }

  function form($instance) {
    $intro = esc_attr($instance['intro']);
    $titulo = esc_attr($instance['titulo']);
    $qnt_listado = esc_attr($instance['qnt_listado']);
  ?>
  
    <p>
      <label for="<?php echo $this->get_field_id('titulo'); ?>">
        <?php _e('Título:'); ?> 
      </label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>"  name="<?php echo $this->get_field_name('titulo'); ?>"  value="<?php echo $titulo; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('qnt_listado'); ?>">
        <?php _e('Quantos eventos serão exibidos:'); ?> 
      </label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('qnt_listado'); ?>"  name="<?php echo $this->get_field_name('qnt_listado'); ?>"  value="<?php echo $qnt_listado; ?>" />
    </p>
    <?php
  }
}

register_widget('ultimosEventos');

?>
