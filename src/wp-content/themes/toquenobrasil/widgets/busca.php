<?php

class buscaTNB extends WP_Widget {
	
  function buscaTNB () {
    $widget_ops = array('classname' => 'busca_tnb', 'description' => 'Busca do Toque no Brasil');
    $this->WP_Widget('busca_tnb', 'Busca TNB', $widget_ops);
  }
	
  function widget($args, $instance) {
    extract($args);

    echo $before_widget; ?>
  
    <div class="title clearfix">
        <div class="shadow"></div>
        <h2 class="widgettitle"><?php _e('Pesquisa','tnb');?></h2>
    </div>	
    <form action="<?php bloginfo('url') ?>" id="searchform" method="get" role="search">
        <div>
            <label for="s" class="screen-reader-text"><?php _e('Pesquisar por','tnb');?></label>
            <input type="text" id="s" name="s" value="<?php echo htmlspecialchars($_GET['s']); ?>">
            <input type="submit" value="Pesquisar" id="tnbsearchsubmit">
            <span class="">
                <input type="radio" name="tipo_busca" value="artistas" <?php if(!$_GET['tipo_busca'] || $_GET['tipo_busca'] == 'artistas') echo 'checked'; ?> /> <?php _e('Artistas','tnb');?>
                <!-- <input type="radio" name="tipo_busca" value="produtores" <?php if($_GET['tipo_busca'] == 'produtores') echo 'checked'; ?> /> <?php _e('Produtores','tnb');?> -->
            
                <input type="radio" name="tipo_busca" value="eventos" <?php if($_GET['tipo_busca'] == 'eventos') echo 'checked'; ?> /> <?php _e('Eventos','tnb');?>
                <input type="radio" name="tipo_busca" value="blog" <?php if($_GET['tipo_busca'] == 'blog') echo 'checked'; ?> /> <?php _e('Blog','tnb');?>
            </span>
        </div>
    </form>
    <script>
    jQuery('#tnbsearchsubmit').click(function() {
        if (jQuery(this).prev().val() == '') {
            alert('Digite alguma coisa para buscar');
            return false;
        }
    });
    </script>

    <?php echo $after_widget;	
  }

  function update($new_instance, $old_instance) {
    
    return $instance;
  }

}

register_widget('buscaTNB');

?>
