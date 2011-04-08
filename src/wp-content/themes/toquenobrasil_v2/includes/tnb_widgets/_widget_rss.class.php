<?php
class Widget_RSS extends TNB_Widget{
    public function __js_update_form_validation($form_id){
    ?>
        if(jQuery("#<?php echo $form_id?> #_rss_url").val() == ''){
            alert("<?php _e('Por favor, preencha a URL da fonte RSS.', 'tnb')?>");
            return false;
        }
    <?php 
    }
    
    public static function __js_insert_form_validation($form_id){
    ?>
        if(jQuery("#<?php echo $form_id?> #_rss_url").val() == ''){
            alert("<?php _e('Por favor, preencha a URL da fonte RSS.', 'tnb')?>");
            return false;
        }
    <?php 
    }
    
    public static function form_filter(){
        if(!$_POST['property']['url'])
            return false;
            
        if(strtolower(substr(trim($_POST['property']['url']),0,7)) != 'http://')
            $_POST['property']['url'] = 'http://'.$_POST['property']['url'];
            
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['titulo'] ? $this->property['titulo'] : null;
    }
    
    protected function __output(){
        
     
        $num = $this->property['num_posts'] ? $this->property['num_posts'] : 4;
        $url = $this->property['url'];
        
        while ( stristr($url, 'http') != $url )
			$url = substr($url, 1);

		if ( empty($url) )
			return;

		$rss = fetch_feed($url);
		$desc = '';
		$link = '';

		if ( ! is_wp_error($rss) ) {
			$desc = esc_attr(strip_tags(@html_entity_decode($rss->get_description(), ENT_QUOTES, get_option('blog_charset'))));
			if ( empty($title) )
				$title = esc_html(strip_tags($rss->get_title()));
			$link = esc_url(strip_tags($rss->get_permalink()));
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);
		}
        
        $icon = includes_url('images/rss.png');
		if ( $title )
			$title = "<a class='rsswidget' href='$url' title='" . esc_attr__( 'Assinar feed', 'tnb' ) ."'><img style='border:0' width='14' height='14' src='$icon' alt='RSS' /></a> <a class='rsswidget' href='$link' title='$desc'>$title</a>";
        
        echo $title;
        $show_author = $this->property['exibir_autor'] == 1 ? 1 : 0;
        $show_date = $this->property['exibir_data'] == 1 ? 1 : 0;
        $show_summary = $this->property['exibir_resumo'] == 1 ? 1 : 0;
        $items = $this->property['num_posts'] ? $this->property['num_posts'] : 5;
        
        $default_args = array( 'show_author' => $show_author, 'show_date' => $show_date,  'show_summary' => $show_summary, 'items' => $items );
        wp_widget_rss_output( $rss, $default_args );
    }
    
    protected static function insert_form(){
        self::form();
    }
    
    protected function update_form(){
        self::form($this);
    }
    
    protected static function form($object = null) {
    
        if (is_null($object)) {
            $object = new stdClass();
            $object->property = array();
        }
        
        ?>
        <h3><?php _e('Fonte de RSS','tnb');?></h3>
        <div><label><?php _e('Título', 'tnb')?>: <input type='text' name='property[titulo]' value='<?php echo htmlentities(utf8_decode($object->property['titulo'])); ?>'/></label></div> 
        
        <div><label><?php _e('URL de uma fonte RSS', 'tnb')?>: <input type='text' id='_rss_url' name='property[url]' value='<?php echo htmlentities(utf8_decode($object->property['url'])); ?>'/></label></div> 
        
        <div><label><input type='checkbox' name='property[exibir_autor]' value='1' <?php if($object->property['exibir_autor']) echo 'checked="checked"'; ?>/> <?php _e('Exibir nome do autor dos posts', 'tnb')?></label></div>
        <div><label><input type='checkbox' name='property[exibir_data]' value='1' <?php if($object->property['exibir_data']) echo 'checked="checked"'; ?>/> <?php _e('Exibir data dos posts', 'tnb')?></label></div>
        <div><label><input type='checkbox' name='property[exibir_resumo]' value='1' <?php if($object->property['exibir_resumo']) echo 'checked="checked"'; ?>/> <?php _e('Exibir resumo dos posts', 'tnb')?></label></div>
        
        <div>
            <label><?php _e('Número de posts', 'tnb')?>: 
            <select name='property[num_posts]'>
                <?php for ($i = 1; $i < 11; $i ++): ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == $object->property['num_posts']) echo 'selected'; ?> ><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            </label>
        </div> 

        
        <?php
        
        
    
    }
    
    
    protected static function form_icon(){
        _e('RSS','tnb');
    }
    
    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um TNBox com feed de sites externos.','tnb');
    }
}
