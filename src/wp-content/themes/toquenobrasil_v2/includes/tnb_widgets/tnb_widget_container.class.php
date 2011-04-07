<?php
class TNB_WidgetContainer{
    private $group;
    private $user_id;
    private $name;
    private $meta_key;
    
    private $widgets = array();
     
    private $widget_classes;
    
    public function __construct(TNB_WidgetContainerGroup $group, $name, $user_id, $widget_classes, $default_widgets){
        $this->group = $group;
        $this->score = $user_id ? 'user' : 'theme';
        $this->user_id = $user_id;
        
        $this->widget_classes = $widget_classes;
        
        $this->meta_key = "_widget_container_$name";
        $this->name = $name;
        
        $this->load($default_widgets);
    }
    
    public function __get($name){
        switch($name){
            case 'id':
                return "_widget_container_{$this->user_id}_{$this->name}";
            break;
            
            case 'name':
                return $this->name;
            break;
            
            case 'groupId':
                return $this->group->id;
            break;
            
            case 'scope':
                return $this->user_id ? 'user' : 'theme';
            break;
            
            case 'user_id':
                return $this->user_id;
            break;
            
            case 'widgets':
                return $this->widgets;
            break;
        }
    }
    
    public static function current_user_can_edit($scope, $user_id){
        global $current_user;
    
        if($scope == 'user'){
            $itsMe = $current_user->ID == $user_id;
            return (current_user_can('edit_users') || $itsMe);
        }else{
            return is_admin();
        }
        
    }
    
    public function editable(){
        return self::current_user_can_edit($this->scope, $this->user_id);
    }
    
    public function __print(){
        global $current_user;
        
        $class = $this->editable() ? $this->group->id : "";
        
?>
<div>
    <ul id='<?php echo $this->id; ?>_ul' class='<?php echo $class; ?>'>
        <?php foreach($this->widgets as $widget): if(is_object($widget)):?>
        <li id='<?php echo $widget->id?>' class='<?php echo get_class($widget); ?>'>
            <?php $widget->__print(); ?>
        </li>
        <?php endif; endforeach;?>
    </ul>
    
    <?php $this->__print_add_box(); ?>
    
</div>
<?php
    }
    
    public function __print_add_box(){
        if($this->editable()){
?>
<div id='<?php echo $this->id; ?>_add_box' class='tnb_widget_add_box clearfix'>
    <p><?php _e("Adicione conteúdos a esta coluna clicando nos <strong>ícones de aplicativos abaixo</strong>. Você pode abrir <strong>quantos aplicativos do mesmo tipo quiser</strong>. Depois de salvos, você pode <strong>reorganizá-los</strong> arrastando-os para cima e para baixo ou para a coluna ao lado. Você pode também mudas as cores dos aplicativos em \"Configurar Aplicativos\" no topo da página. <strong>Não se esqueça de salvar suas alterações</strong> clicando no botão \"salvar\" <strong>no topo da página</strong>.", "tnb"); ?></p>
    <div id='<?php echo $this->id; ?>_add_box_icons' class="tnb_add_box_icons clearfix">    
    <?php foreach($this->widget_classes as $class):?>
        <div id='<?php echo $this->id.'_'.$class; ?>_form_icon' class='tnb_widget_form_icon <?php echo $class.'_icon'; ?>' onclick='jQuery("#<?php echo $this->id; ?>_add_box_icons").fadeOut("fast", function(){jQuery("#<?php echo $this->id.'_'.$class; ?>_insert_form").fadeIn("fast");});'>
            <img src="<?php bloginfo('stylesheet_directory'); ?>/img/setinha.png" />
            <div class="widget_description"><?php eval('echo '.$class."::getWidgetDescription();"); ?></div>
        </div>
    <?php endforeach;?>
    </div>
    <?php foreach($this->widget_classes as $class):?>
        <div id='<?php echo $this->id.'_'.$class; ?>_insert_form' style='display:none;'>
        <?php
        // 'Widget_Texto', 'Widget_Infos_Artista', 'Widget_Fotos', 'Widget_Facebook', 
        // 'Widget_Eventos_Artista', 'Widget_RSS', 'Widget_Videos', 'Widget_Player', 
        // 'Widget_Twitter', 'Widget_Mural' 
        eval($class.'::__print_insert_form($this);'); 
        ?>
    
            <div class='alignright'>
                <button class="config-button" onclick='jQuery("#<?php echo $this->id.'_'.$class; ?>_insert_form").fadeOut("fast", function(){jQuery("#<?php echo $this->id; ?>_add_box_icons").fadeIn("fast");});'><?php _e('cancelar', 'tnb'); ?></button>
                <button class="config-button" onclick='jQuery("#<?php echo $this->id;?>_<?php echo $class?>_insert_form_form").submit();'><?php _e('inserir','tnb')?></button>
            </div> 
        </div>
    <?php endforeach;?>
    
</div>
<?php 
        }
    }
    
    public function save(){
        if($this->editable()){
            if($this->scope == 'user'){
                $data = array_keys($this->widgets);
                update_user_meta($this->user_id, $this->meta_key, $data);
                 
            }else{
                // theme scope
                
            }
        }
    }
    
    public function load($default_widgets = array()){
        global $wpdb;
        $this->widgets = array();
        if($this->scope == 'user'){
            // user scope
            $data = get_user_meta($this->user_id, $this->meta_key, true);
            
            if(is_array($data)){
                
                foreach($data as $widget_id)
                    $this->widgets[$widget_id] = null;
                    
                $widgets_ids = implode("','",$data);
                $widgets_ids = "'$widgets_ids'";
                $widgets_rows = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE user_id = $this->user_id AND meta_key IN ($widgets_ids)");
                //var_dump($widgets_rows);
                foreach($widgets_rows as $row)
                    if(is_serialized($row->meta_value)){
                        //_pr($row->meta_value);
                        $this->widgets[$row->meta_key] = unserialize($row->meta_value);
                    }else{
                        $this->widgets[$row->meta_key] = unserialize(base64_decode($row->meta_value));
                       // _pr($row->meta_value);
                    }

                
            }else{
                if(is_array($default_widgets))
                    foreach ($default_widgets as $widget_class){
                        eval('$widget = new '.$widget_class.'('.$this->user_id.');');
                        $widget->save();
                        $this->widgets[$widget->id] = $widget;
                    }
                else
                     $this->widgets = array();
                
                add_user_meta($this->user_id, $this->meta_key, array_keys($this->widgets));
                //$this->save();
            }
        }else{
            // theme scope
        }   
    }
    
    public function setWidgets(array $widgets){
        $this->widgets = $widgets;
    }
    
    public function addWidget(TNB_Widget $widget){
        $this->widgets[$widget->id] = $widget;
    }
}

?>
