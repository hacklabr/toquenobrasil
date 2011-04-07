<?php
class Widget_Mural extends TNB_Widget{
    public function __js_update_form_validation($form_id){
    
    }
    
    public static function __js_insert_form_validation($form_id){
    
    }
    
    public static function form_filter(){
        // retornando false, o widget não será alterado / inserido
        if(false){
            // não está funcionando ainda, mas a idéia é que o que for impresso seja apresentado para o usuário 
            // como uma mensagem de erro.
            _e('erro');
            return false;
        }
                
        //$_POST['property']['titulo'] = strtoupper($_POST['property']['titulo']); 
        
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['titulo'] ? $this->property['titulo'] : null;
    }
    
    protected function __output(){
        
        $widget_id = uniqid('mural');
        
        $mural_id = self::get_mural_id();
        
        ?>
        <div id="<?php echo $widget_id; ?>">
            <div class="mural-lista-container">
                <ul class="mural-lista">
                    <?php print_mural_comentarios($mural_id, $this->property['num_posts'], 1, $this->user_id); ?>    
                </ul>
                <div class="loading">Carregando</div>
            </div>
            <?php if (is_user_logged_in()): ?>       
            
            
            <form class="mural-form">
                
                <label for="mensagem">Mensagem</label><br />
                <input type="hidden" name="profile_owner" value="<?php echo $this->user_id; ?>" />
                
                <textarea name="mensagem"></textarea>           
                <button class="config-button bg-yellow alignright">Enviar</button>
            </form>
        
            <?php endif; ?>
        </div>        
        
        
        <script>
        function <?php echo $widget_id; ?>_add_button_events() {
            
            var current_page = jQuery('#<?php echo $widget_id; ?>').find('.current_page').val();
            
            jQuery('#<?php echo $widget_id; ?>').find('a.widget_mural_anterior').click(function() {
                <?php echo $widget_id; ?>tnb_load_comments('', parseInt(current_page) - 1, 0);
            });
            jQuery('#<?php echo $widget_id; ?>').find('a.widget_mural_proximo').click(function() {
                
                <?php echo $widget_id; ?>tnb_load_comments('', parseInt(current_page) + 1, 0);
            });
            
            jQuery('#<?php echo $widget_id; ?>').find('a.widget_mural_apagar').click(function() {
                var comment_id = jQuery(this).attr('id').replace('apagar_', '');
                <?php echo $widget_id; ?>tnb_load_comments('', current_page, comment_id);
            });
        }
        
        <?php echo $widget_id; ?>_add_button_events()
        
        function <?php echo $widget_id; ?>tnb_load_comments(msg, page, deletar) {
            jQuery('#<?php echo $widget_id; ?>').find('.loading').show()
            jQuery('#<?php echo $widget_id; ?> ul.mural-lista').load('<?php echo TNB_URL . '/includes/tnb_widgets/_widget_mural_ajax.php' ?>',
                {
                message: msg,
                deletar: deletar,
                mural_id: <?php echo $mural_id; ?>,
                per_page: <?php echo $this->property['num_posts'] ? $this->property['num_posts'] : 5; ?>,
                profile_owner: <?php echo $this->user_id; ?>,
                page: page
                }, function() {
                    jQuery('#<?php echo $widget_id; ?>').find('.loading').hide();
                    jQuery('#<?php echo $widget_id; ?> form.mural-form textarea').val('');
                    <?php echo $widget_id; ?>_add_button_events();
                }
            );
        }
        
        jQuery('#<?php echo $widget_id; ?> form.mural-form button').click(function() {
        
            if (jQuery('#<?php echo $widget_id; ?> form.mural-form textarea').val() == '') {
                alert('<?php _e('Escreva uma mensagem', 'tnb'); ?>');
                return false;
            } else {
                <?php echo $widget_id; ?>tnb_load_comments(jQuery('#<?php echo $widget_id; ?> form.mural-form textarea').val(), 1, 0);
                return false;
            }
        
        });
        
        
                
        </script>
        
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
        
        ?>
        <h3><?php _e('Mural', 'tnb'); ?></h3>
        <label for="<?php echo $formID; ?>_titulo"><?php _e('título','tnb')?></label>: <input type='text' name='property[titulo]' id="<?php echo $formID; ?>_titulo" value="<?php echo htmlentities(utf8_decode($instance->property['titulo'])); ?>" /><br />
        
        
        <div><label><?php _e('Número de recados para exbir? ', 'tnb')?>: 
            <select name='property[num_posts]'>
            <?php for ($i = 1; $i <= 20; $i ++): ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == $instance->property['num_posts']) echo 'selected'; ?> ><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            </label>
        </div> 
        <?php 
    }
    
    protected static function get_mural_id() {
        global $curauth;
        if(tnb_cache_exists('MURAL_ID', $curauth->ID)) 
            return tnb_cache_get('MURAL_ID', $curauth->ID);
        
        global $wpdb;
        
        $mural_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_author = {$curauth->ID} AND post_type = 'mural' LIMIT 1");
        
        if (!$mural_id) {
            $post = array(
                'post_type' => 'mural',
                'post_author' => $curauth->ID,
                'post_title' => 'Mural de ' . $curauth->user_login,
                'post_status' => 'publish',
                'comment_status' => 'open'
            );
            
            $mural_id = wp_insert_post($post);
            
            $mural_id =  (!is_wp_error($mural_id)) ? $mural_id : false;
                
                
        } 
        
        if ($mural_id)
            tnb_cache_set('MURAL_ID', $curauth->ID, $mural_id);
        
        return $mural_id;
        
        
    }
    
    protected static function form_icon(){
        _e('Mural','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um mural de recados.','tnb');
    }
}
