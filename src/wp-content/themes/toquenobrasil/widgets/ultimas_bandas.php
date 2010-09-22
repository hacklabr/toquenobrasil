<?php

class ultimosCadastros extends WP_Widget {
	function ultimosCadastros () {
	    $widget_ops = array('classname' => 'ultimos_cadastros', 'description' => 'Lista últimos artistas cadastrados');
        $this->WP_Widget('ultimos_cadastros', 'Ultimas bandas', $widget_ops);
	}
	
	function widget($args, $instance) {
	    extract($args);

		echo $before_widget; ?>
			<div class="clear"></div>		
            <h2><?php echo $instance['titulo'] ?></h2>
    		<div class="content">
        		<div class='down-arrow'></div>
                <p><?php echo $instance['intro'] ?></p>			
                <?php $users = get_artistas($instance['qnt_listado'], 'user_registered DESC'); ?>
                <ul>
                    
                    <?php foreach ($users as $u) : ?>
							<li>
        				        <?php echo get_avatar($u->ID, 32); ?>
        				        <a title='Visitar perfil de <?php echo get_user_meta($u->ID, 'banda', true); ?>' href='<?php echo  get_author_posts_url($u->ID); ?>'><?php echo $u->display_name; ?></a><br/>
            				    <small><?php echo 'Registrado desde ', date('j/m/Y', strtotime($u->user_registered)); ?></small>
            				    <div class='clear'></div>
        				    </li>
        				    
        				<?php endforeach; ?>
    				
        		</ul>    		  
    		</div>
		<?php echo $after_widget;	
		?> <div class="hr"></div> <?php
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
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>"  name="<?php echo $this->get_field_name('titulo'); ?>"  value="<?php echo $titulo; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('intro'); ?>">
                <?php _e('Texto Explicativo:'); ?> 
                <textarea class="widefat" id="<?php echo $this->get_field_id('intro'); ?>" style="width: 98%; height: 100px;" name="<?php echo $this->get_field_name('intro'); ?>"  rows=3  value="<?php echo $intro; ?>"><?php echo $intro; ?></textarea>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('qnt_listado'); ?>">
                <?php _e('Quantos Artistas serão exibidos:'); ?> 
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('qnt_listado'); ?>"  name="<?php echo $this->get_field_name('qnt_listado'); ?>"  value="<?php echo $qnt_listado; ?>" />
            </label>
        </p>            
              

    <?php
    }
}

register_widget('ultimosCadastros');

?>
