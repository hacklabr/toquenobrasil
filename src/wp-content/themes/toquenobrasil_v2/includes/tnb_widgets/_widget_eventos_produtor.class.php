<?php
class Widget_Eventos_Produtor extends TNB_Widget{
    protected $property = array('titulo' => 'Oportunidades', 'exibir' => 'todos');
    
    public function __js_update_form_validation($form_id){
        
    }
    
    public static function __js_insert_form_validation($form_id){
     
    }
    
    public static function form_filter(){
        //hl_var_dump($_POST);
        
        
            if(!$_POST['property']['eventos_selecionados'])
                $_POST['property']['eventos_selecionados'] = array();
        
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['titulo'] ? $this->property['titulo'] : null;
    }
    
    protected function __output(){
        //hl_print_r($this->property);
        
        $eventos_selecionados = $this->property['exibir'] == 'abertos' ? self::getOportunidadesAbertas() : self::getOportunidades();
        
         ?>
         
         
         <?php foreach ($eventos_selecionados as $evento): ?>
             
             <?php if($this->property['exibir'] != 'selecionados' || in_array($evento->ID, $this->property['eventos_selecionados'])): ?>
             
                 <?php
                 global $evento_list_item_id;
                 $evento_list_item_id = $evento->ID; 
                 get_template_part('oportunidades-list-item');
                 ?>
                 
             <?php endif; ?>
             
        <?php endforeach; ?>
        <?php  
         
    }
    
    protected static function insert_form(){
        self::form();
    }
    
    protected function update_form(){
        self::form($this);
    }
    
    protected static function form($instance = null){
        $formID = uniqid();
        if(is_null($instance)){
            $instance = new stdClass();
            $instance->property = array();
        }
        
        $eventos_selecionado = self::getOportunidades();
        
        ?>
        <h3><?php _e('Oportunidades', 'tnb'); ?></h3>
        <label for="<?php echo $formID; ?>_titulo"><?php _e('título','tnb')?></label>: <input type='text' name='property[titulo]' id="<?php echo $formID; ?>_titulo" value="<?php echo htmlentities(utf8_decode($instance->property['titulo'])); ?>" /><br />
        
        <div id="<?php echo $formID; ?>_form">
        
        <label><input type="radio" id="<?php echo $formID; ?>_radio_todos" class="radio_oportunidades" name="property[exibir]" value="todos" <?php if($instance->property['exibir'] == 'todos') echo 'checked="checked" '; ?>/><?php _e('Todas', 'tnb'); ?></label><br />
        <label><input type="radio" id="<?php echo $formID; ?>_radio_abertos" class="radio_oportunidades" name="property[exibir]" value="abertos" <?php if($instance->property['exibir'] == 'abertos') echo 'checked="checked" '; ?>/><?php _e('Com inscrições abertas', 'tnb'); ?></label><br />
        <label><input type="radio" id="<?php echo $formID; ?>_radio_selecionados" class="radio_oportunidades" name="property[exibir]" value="selecionados" <?php if($instance->property['exibir'] == 'selecionados') echo 'checked="checked" '; ?>/><?php _e('Apenas selecionadas', 'tnb'); ?></label><br />
        
    
        <div id='<?php echo $formID?>_selecionados_lista' class='<?php if($instance->property['exibir'] != 'selecionados') echo 'hide'; ?>'>
            <?php foreach($eventos_selecionado as $evento): ?>
                <label><input type="checkbox" name='property[eventos_selecionados][]' value='<?php echo $evento->ID?>' <?php if(in_array($evento->ID, $instance->property['eventos_selecionados'])) echo 'checked="checked"'; ?> /> <?php echo $evento->post_title; ?></label><br/>
            <?php endforeach; ?>
        </div>
    
        
        </div>
        
        <script type="text/javascript">
            jQuery("#<?php echo $formID; ?>_form").find('.radio_oportunidades').click(function(){
                
                if(jQuery(this).val() == 'selecionados'){
                    jQuery("#<?php echo $formID?>_selecionados_lista").slideDown();
                }else{
                	jQuery("#<?php echo $formID?>_selecionados_lista").slideUp();
                }
            });
        </script>
        <?php 
    }
    
    protected static function getOportunidades(){
        global $wpdb, $curauth;
        
        if(tnb_cache_exists('PRODUTOR_EVENTOS', $curauth->ID)) 
            return tnb_cache_get('PRODUTOR_EVENTOS', $curauth->ID);
            
        $query_subevents_arovados = " AND (post_parent = 0 OR ID IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento') ) ";
    
        $query = "
        SELECT 
            ID 
        FROM 
            $wpdb->posts 
        WHERE
            post_type = 'eventos' AND
            post_status = 'publish' AND
            post_author = {$curauth->ID} 
            $query_subevents_arovados";
    
        $oportunidadesID = $wpdb->get_col($query);
        
        if (sizeof($oportunidadesID) == 0) {
            // se não vier nada, temos que colocar alguma coisa que impeça a query de trazer todos
            $oportunidadesID = array(0);
        }
        $query_args = array(
            'post_type' => 'eventos',
            'post__in' => $oportunidadesID,
            'meta_key' => 'evento_inicio', 
            'orderby' => 'meta_value',
            'order' => 'DESC'
        );
        
        $result = get_posts($query_args);
        tnb_cache_set('PRODUTOR_EVENTOS', $curauth->ID, $result);
        return $result;
    }
    
    protected static function getOportunidadesAbertas(){
        global $wpdb, $curauth;
        
        if(tnb_cache_exists('PRODUTOR_EVENTOS_ABERTOS', $curauth->ID))
            return tnb_cache_get('PRODUTOR_EVENTOS_ABERTOS', $curauth->ID);
        
        $query_subevents_arovados = " AND (post_parent = 0 OR ID IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento') ) ";
        
        $currentdate = date('Y-m-d');
        
        $inscricoes_abertas = " AND 
            ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_inscricao_inicio' AND meta_value <= '$currentdate') AND
            ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_inscricao_fim' AND meta_value >= '$currentdate')
            " ;
        
        $query = "
        SELECT 
            ID 
        FROM 
            $wpdb->posts 
        WHERE
            post_type = 'eventos' AND
            post_status = 'publish' AND
            post_author = {$curauth->ID} 
            $query_subevents_arovados
            $inscricoes_abertas";
    
        //echo " QUERY { $query } ";
        $oportunidadesID = $wpdb->get_col($query);
        if (sizeof($oportunidadesID) == 0) {
            // se não vier nada, temos que colocar alguma coisa que impeça a query de trazer todos
            $oportunidadesID = array(0);
        }
        $query_args = array(
            'post_type' => 'eventos',
            'post__in' => $oportunidadesID,
            'meta_key' => 'evento_inicio', 
            'orderby' => 'meta_value',
            'order' => 'DESC'
        );
        
        $result = get_posts($query_args);
        tnb_cache_set('PRODUTOR_EVENTOS_ABERTOS', $curauth->ID, $result);
        return $result;
    }
    
    protected static function form_icon(){
        _e('Eventos','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique para inserir uma lista com suas oportunidades','tnb');
    }
}
