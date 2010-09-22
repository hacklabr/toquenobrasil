<?php

class ultimosEventos extends WP_Widget {
	function ultimosEventos () {
	    $widget_ops = array('classname' => 'ultimos_eventos', 'description' => 'Lista últimos eventos publicados');
        $this->WP_Widget('ultimos_eventos', 'Ultimos Eventos Publicados', $widget_ops);
	}
	
	function widget($args, $instance) {
	    extract($args);

		echo $before_widget; ?>
			<div class="clear"></div>		
            <h2><?php echo $instance['titulo'] ?></h2>
    		<div class="content">
        		<div class='down-arrow'></div>
				<ul>			
                <?php
                
                query_posts("post_type=eventos&posts_per_page={$instance['qnt_listado']}&orderby=date&order=DESC");

                //The Loop
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                ?>
                	<li>
				        <h2 class="span-14"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				    </li>
                <?php 
                endwhile; 
                endif;
                
                //Reset Query
                wp_reset_query();
                
                
                ?>
    				
        		</ul>    		  
    		</div>
		<?php echo $after_widget;	
		?> <div class="hr"></div> <?php
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
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>"  name="<?php echo $this->get_field_name('titulo'); ?>"  value="<?php echo $titulo; ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('qnt_listado'); ?>">
                    <?php _e('Quantos eventos serão exibidos:'); ?> 
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('qnt_listado'); ?>"  name="<?php echo $this->get_field_name('qnt_listado'); ?>"  value="<?php echo $qnt_listado; ?>" />
                </label>
            </p>            
                  
    
        <?php
    }
}

register_widget('ultimosEventos');

?>
