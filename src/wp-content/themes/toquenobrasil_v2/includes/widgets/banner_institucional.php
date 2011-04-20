<?php

class BannerInstitucional extends WP_Widget {
	
  function bannerInstitucional () {
    $widget_ops = array('classname' => 'banner_institucional', 'description' => 'Banner institucional TNB');
    $this->WP_Widget('banner_institucional', 'Banner Institucional', $widget_ops);
  }
	
  function widget($args, $instance) {
    extract($args);

    echo $before_widget; ?>
        <a href="<?php echo $instance['link'] ?>" title="<?php echo $instance['titulo'] ?>">
            <img src="<?php echo $instance['imagem'] ?>" alt="<?php echo $instance['titulo'] ?>" title="<?php echo $instance['titulo'] ?>">
            <h2 class="title"><?php echo $instance['titulo'] ?></h2>
        </a>
    <?php echo $after_widget;	
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['titulo'] = strip_tags($new_instance['titulo']);
    $instance['imagem'] = strip_tags($new_instance['imagem']);
    $instance['link'] = strip_tags($new_instance['link']);
    return $instance;
  }

  function form($instance) {
    $titulo = esc_attr($instance['titulo']);
    $imagem = esc_attr($instance['imagem']);
    $link = esc_attr($instance['link']);
  ?>
  
    <p>
      <label for="<?php echo $this->get_field_id('titulo'); ?>">
        <?php _e('Título:'); ?> 
      </label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>"  name="<?php echo $this->get_field_name('titulo'); ?>"  value="<?php echo $titulo; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('qnt_listado'); ?>">
        <?php _e('Link da imagem:'); ?> 
      </label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('imagem'); ?>"  name="<?php echo $this->get_field_name('imagem'); ?>"  value="<?php echo $imagem; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('link'); ?>">
        <?php _e('Link do banner:'); ?> 
      </label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('link'); ?>"  name="<?php echo $this->get_field_name('link'); ?>"  value="<?php echo $imagem; ?>" />
    </p>
    <?php
  }
}

register_widget('bannerInstitucional');

?>
