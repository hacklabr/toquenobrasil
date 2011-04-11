<?php
class Widget_Eventos_Artista extends TNB_Widget{
    public function __js_update_form_validation($form_id){
    ?>
        if(jQuery("#<?php echo $form_id?> textarea").val() == ''){
            alert("<?php _e('o texto não pode ficar em vazio.', 'tnb')?>");
            return false;
        }
    <?php 
    }
    
    public static function __js_insert_form_validation($form_id){
    ?>
        if(jQuery("#<?php echo $form_id?> textarea").val() == ''){
            alert("<?php _e('o texto não pode ficar em vazio.', 'tnb')?>");
            return false;
        }
    <?php 
    }
    
    public static function form_filter(){
        //hl_var_dump($_POST);
        
        $_POST['property']['inscritos'] = isset($_POST['property']['inscritos']);
        $_POST['property']['selecionados'] = isset($_POST['property']['selecionados']);
        
        if($_POST['property']['inscritos']){
            if($_POST['property']['quais_inscritos'] == 'todos'){
                unset($_POST['property']['eventos_inscritos']);
            }else{
                if(!$_POST['property']['eventos_inscritos'])
                    $_POST['property']['eventos_inscritos'] = array();
            }
        }else{
            unset($_POST['property']['quais_inscritos']);
            unset($_POST['property']['eventos_inscritos']);
        }
        
        
        if($_POST['property']['selecionados']){
            if($_POST['property']['quais_selecionados'] == 'todos'){
                unset($_POST['property']['eventos_selecionados']);
            }else{
                if(!$_POST['property']['eventos_selecionados'])
                    $_POST['property']['eventos_selecionados'] = array();
            }
        }else{
            unset($_POST['property']['quais_selecionados']);
            unset($_POST['property']['eventos_selecionados']);
        }
        
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['titulo'] ? $this->property['titulo'] : __('oportunidades','tnb');
    }
    
    protected function __output(){
        //hl_print_r($this->property);
        
        if($this->property['inscritos']):
             $eventos_inscritos = self::getOportunidadesInscrito();
             
             ?>
             
             <?php if($this->property['selecionados']): ?>
             
                <ul class="widget_oportunidades_tabs">
                    <li><a class="inscrito"><?php _e('Estou Inscrito', 'tnb'); ?></a></li>
                    <li><a class="selecionado"><?php _e('Fui Selecionado', 'tnb'); ?></a></li>
                </ul>
             
             <?php endif; ?>
             
             <div class="inscrito oportunidades_tab">
             
             <?php foreach ($eventos_inscritos as $evento): ?>
             
                 <?php if($this->property['quais_inscritos'] == 'todos' || in_array($evento->ID, $this->property['eventos_inscritos'])): ?>
                 
                     <?php
                     global $evento_list_item_id;
                     $evento_list_item_id = $evento->ID; 
                     get_template_part('oportunidades-list-item');
                     ?>
                     
                 <?php endif; ?>
                 
            <?php endforeach; ?>
            
            </div>
            <!-- .inscritos -->
            
        <?php 
        endif; 
        
        if($this->property['selecionados']):
             $eventos_selecionados = self::getOportunidadesSelecionado();
             ?>
             
             <div class="selecionado oportunidades_tab">
             
             <?php foreach ($eventos_selecionados as $evento): ?>
                 
                 <?php if($this->property['quais_selecionados'] == 'todos' || in_array($evento->ID, $this->property['eventos_selecionados'])): ?>
                 
                     <?php
                     global $evento_list_item_id;
                     $evento_list_item_id = $evento->ID; 
                     get_template_part('oportunidades-list-item');
                     ?>
                     
                 <?php endif; ?>
                 
            <?php endforeach; ?>
            </div>
            
        <?php  
        endif; 
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
        $eventos_inscritos = self::getOportunidadesInscrito();
        $eventos_selecionado = self::getOportunidadesSelecionado();
        
        ?>
        <h3><?php _e('Oportunidades', 'tnb'); ?></h3>
        <label for="<?php echo $formID; ?>_titulo"><?php _e('título','tnb')?></label>: <input type='text' name='property[titulo]' id="<?php echo $formID; ?>_titulo" value="<?php echo htmlentities(utf8_decode($instance->property['titulo'])); ?>" /><br />
        
        <label><input type="checkbox" id="<?php echo $formID; ?>_inscritos" name="property[inscritos]" <?php if($instance->property['inscritos']) echo 'checked="checked" '; ?>/><?php _e('exibir as oportunidades em que estou inscrito', 'tnb'); ?></label><br />
        
        <div id='<?php echo $formID?>_inscritos_div' class='<?php if(!$instance->property['inscritos']) echo 'hide'; ?>'>
            <label><input type='radio' id='<?php echo $formID?>_inscritos_todos' name='property[quais_inscritos]' value='todos' <?php if(!$instance->property['quais_inscritos'] || $instance->property['quais_inscritos'] == 'todos') echo 'checked="checked"'?> /> <?php _e('todos', 'tnb');?></label>
            <label><input type='radio' id='<?php echo $formID?>_inscritos_selecionados' name='property[quais_inscritos]' value='selecionados' <?php if($instance->property['quais_inscritos'] == 'selecionados') echo 'checked="checked"'?> /> <?php _e('somente os selecionados', 'tnb');?></label>
            
            <div id='<?php echo $formID?>_inscritos_lista' class='<?php if($instance->property['quais_inscritos'] != 'selecionados') echo 'hide';?>' >
                <?php foreach($eventos_inscritos as $evento):?>
                    <label><input type="checkbox" name='property[eventos_inscritos][]' value='<?php echo $evento->ID?>'  <?php if(in_array($evento->ID, $instance->property['eventos_inscritos'])) echo 'checked="checked"'; ?>/> <?php echo $evento->post_title; ?></label><br/> 
                <?php endforeach; ?>
            </div>
        </div>
         
        <hr />
        
        <label><input type="checkbox" id="<?php echo $formID; ?>_selecionados" name="property[selecionados]" <?php if($instance->property['selecionados']) echo 'checked="checked" '; ?>/><?php _e('exibir as oportunidades para as quais fui selecionado', 'tnb'); ?></label><br />
        
        <div id='<?php echo $formID?>_selecionados_div' class='<?php if(!$instance->property['selecionados']) echo 'hide'; ?>'>
            <label><input type='radio' id='<?php echo $formID?>_selecionados_todos' name='property[quais_selecionados]' value='todos' <?php if(!$instance->property['quais_selecionados'] || $instance->property['quais_selecionados'] == 'todos') echo 'checked="checked"'?> /> <?php _e('todos', 'tnb');?></label>
            <label><input type='radio' id='<?php echo $formID?>_selecionados_selecionados' name='property[quais_selecionados]' value='selecionados' <?php if($instance->property['quais_selecionados'] == 'selecionados') echo 'checked="checked"'?> /> <?php _e('somente os selecionados', 'tnb');?></label>
        
            <div id='<?php echo $formID?>_selecionados_lista' class='<?php if($instance->property['quais_selecionados'] != 'selecionados') echo 'hide'; ?>'>
                <?php foreach($eventos_selecionado as $evento): ?>
                    <label><input type="checkbox" name='property[eventos_selecionados][]' value='<?php echo $evento->ID?>' <?php if(in_array($evento->ID, $instance->property['eventos_selecionados'])) echo 'checked="checked"'; ?> /> <?php echo $evento->post_title; ?></label><br/>
                <?php endforeach; ?>
            </div>
        </div>
        
        <script type="text/javascript">
            jQuery("#<?php echo $formID; ?>_inscritos").change(function(){
                if(jQuery(this).attr('checked')){
                    jQuery("#<?php echo $formID?>_inscritos_div").slideDown();
                }else{
                	jQuery("#<?php echo $formID?>_inscritos_div").slideUp();
                }
            });

            jQuery("#<?php echo $formID; ?>_selecionados").change(function(){
                if(jQuery(this).attr('checked')){
                    jQuery("#<?php echo $formID?>_selecionados_div").slideDown();
                }else{
                	jQuery("#<?php echo $formID?>_selecionados_div").slideUp();
                }
            });

            jQuery("#<?php echo $formID?>_inscritos_div input:radio").change(function (){
                if(jQuery(this).val() == 'todos'){
                	jQuery("#<?php echo $formID?>_inscritos_lista").slideUp();
                }else{
                    jQuery("#<?php echo $formID?>_inscritos_lista").slideDown();
                }
            });

            jQuery("#<?php echo $formID?>_selecionados_div input:radio").change(function (){
                if(jQuery(this).val() == 'todos'){
                	jQuery("#<?php echo $formID?>_selecionados_lista").slideUp();
                }else{
                    jQuery("#<?php echo $formID?>_selecionados_lista").slideDown();
                }
            });
        </script>
        <?php 
    }
    
    
    protected static function form_icon(){
        _e('Oportunidades','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um TNBox com as oportunidades em que estou inscrito e/ou selecionado.','tnb');
    }
    
    
    // ===================== //
    
    protected static function getOportunidadesInscrito(){
        global $wpdb, $curauth;
        
        if(tnb_cache_exists('ARTISTA_EVENTOS_INSCRITOS', $curauth->ID))
            return tnb_cache_get('ARTISTA_EVENTOS_INSCRITOS', $curauth->ID);
        
        $query_subevents_arovados = " AND (post_parent = 0 OR ID IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento') ) ";
    
        $query = "
        SELECT 
            ID 
        FROM 
            $wpdb->posts 
        WHERE
            post_type = 'eventos' AND
            post_status = 'publish' AND
            ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'inscrito' AND meta_value = '{$curauth->ID}' ) AND
            ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_fim' AND meta_value >= CURRENT_TIMESTAMP )
            $query_subevents_arovados";
    
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
        tnb_cache_set('ARTISTA_EVENTOS_INSCRITOS', $curauth->ID, $result);
        return $result;
    }
    
    protected static function getOportunidadesSelecionado(){
        global $wpdb, $curauth;
        
        if(tnb_cache_exists('ARTISTA_EVENTOS_SELECIONADOS', $curauth->ID))
            return tnb_cache_get('ARTISTA_EVENTOS_SELECIONADOS', $curauth->ID);
        
        $query_subevents_arovados = " AND (post_parent = 0 OR ID IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento') ) ";
    
        $query = "
        SELECT 
            ID 
        FROM 
            $wpdb->posts 
        WHERE
            post_type = 'eventos' AND
            post_status = 'publish' AND
            ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'selecionado' AND meta_value = '{$curauth->ID}' )
            $query_subevents_arovados";
    
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
        tnb_cache_set('ARTISTA_EVENTOS_SELECIONADOS', $curauth->ID, $result);
        return $result;
    }
}
