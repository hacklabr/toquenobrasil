<?php

class ultimosCadastros extends WP_Widget {
	
  function ultimosCadastros () {
    $widget_ops = array('classname' => 'ultimos_cadastros', 'description' => 'Lista últimos cadastrados');
    $this->WP_Widget('ultimos_cadastros', 'Ultimos Usuários', $widget_ops);
  }
	
  function widget($args, $instance) {
    extract($args);

    echo $before_widget; ?>      
        <h2 class="title"><?php echo $instance['titulo'] ?></h2>
        <div class="content">
            <p><?php echo $instance['intro'] ?></p>
            <?php $users = tnb_get_users('', $instance['qnt_listado'], 'user_registered DESC'); ?>
            <?php foreach ($users as $u) : ?>
            <div id="artist-<?php echo $u->ID; ?>" class="ultimo-usuario">
                <a href="<?php echo get_author_posts_url($u->ID); ?>" class="avatar" title="<?php _e('Ver o perfil do usuário ','tnb'); echo $u->display_name; ?>">
                    <?php echo get_avatar($u->ID, 70); ?>
                </a>
                <a href='<?php echo  get_author_posts_url($u->ID); ?>' title="<?php _e('Ver o perfil do usuário ','tnb'); echo $u->display_name; ?>">
                    <?php echo $u->display_name; ?>
                </a>
                <br/>
                <small><?php _e('Registrado desde ','tnb'); echo date('j/m/Y', strtotime($u->user_registered)); ?></small>
            </div>
            <?php endforeach; ?>
        </div>
    <?php echo $after_widget;	
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['qnt_listado'] = strip_tags($new_instance['qnt_listado']);
    $instance['titulo'] = strip_tags($new_instance['titulo']);
    $instance['intro'] = strip_tags($new_instance['intro']);
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
      <label for="<?php echo $this->get_field_id('intro'); ?>">
        <?php _e('Texto Explicativo:'); ?> 
      </label>
      <textarea class="widefat" id="<?php echo $this->get_field_id('intro'); ?>" style="width: 98%; height: 100px;" name="<?php echo $this->get_field_name('intro'); ?>"  rows=3  value="<?php echo $intro; ?>"><?php echo $intro; ?></textarea>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('qnt_listado'); ?>">
        <?php _e('Quantos Artistas serão exibidos:'); ?> 
      </label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('qnt_listado'); ?>"  name="<?php echo $this->get_field_name('qnt_listado'); ?>"  value="<?php echo $qnt_listado; ?>" />
    </p>

    <?php
  }
}

register_widget('ultimosCadastros');

?>
