<?php
abstract class TNB_Widget{
    protected $id;
    protected $user_id;
    protected $property = array();
    
    public static function do_action(){
        
        if(isset($_POST['tnb_widget_action'])){
            switch ($_POST['tnb_widget_action']){
                case 'add_widget':
                    eval('$instance = new '.$_POST['tnb_widget_class'].'('.$_POST['tnb_widget_user_id'].');');
                    eval('$ok = '.$_POST['tnb_widget_class'].'::form_filter($instance);');
                   
                    $instance->property = $_POST['property'];
                    
                    
                    if($ok && $instance->editable()){
                        $instance->save();
                        return $instance;
                    }else{
                        return false;
                    }
                break; 
                
                case 'update_widget':
                    global $curauth;
                    $widget_data = get_user_meta($curauth->ID, $_POST['tnb_widget_id'], true);
                    if(is_serialized($widget_data))
                        $widget = unserialize($widget_data);
                    else
                        $widget = unserialize(base64_decode($widget_data));
                        
                    if(is_object($widget)){
                        eval('$ok = '.get_class($widget).'::form_filter($instance);');
                        if(!$ok)
                            return false;
                            
                        $widget->property = $_POST['property'];
                        $widget->save();
                        return $widget;
                    }else{
                        return false;
                    }
                break;
            }
        }
    
    }
    
    
    protected static function form_filter(TNB_Widget $instance){
        return true;
    }
    
    
    public static function __print_insert_form(TNB_WidgetContainer $container){
        $class = get_called_class();
        
?>
<form id='<?php echo $container->id?>_<?php echo get_called_class()?>_insert_form_form' method="post" enctype="multipart/form-data">
    <input type='hidden' id='tnb_widget_action' name='tnb_widget_action' value='add_widget' />
    <input type='hidden' id='tnb_widget_container_name' name='tnb_widget_container_name' value='<?php echo  htmlentities($container->name); ?>' />
    <input type='hidden' id='tnb_widget_group_id' name='tnb_widget_group_id' value='<?php echo  htmlentities($container->groupId); ?>' />
    <input type='hidden' id='tnb_widget_user_id' name='tnb_widget_user_id' value='<?php echo htmlentities($container->user_id); ?>' />
    <input type='hidden' id='tnb_widget_class' name='tnb_widget_class' value='<?php echo htmlentities($class); ?>' />
    <?php eval($class.'::insert_form();'); ?>
</form>
<script type="text/javascript">
<!--
jQuery('#<?php echo $container->id?>_<?php echo get_called_class()?>_insert_form_form').submit(function(){
   <?php eval(get_called_class().'::__js_insert_form_validation($container->id."_".get_called_class()."_insert_form_form");'); ?>
   return true; 
});
//-->
</script>
<?php 
    } 
    
    
    
    public function __construct($user_id){
        $this->user_id = $user_id;
        $class = get_class($this);
        $this->id = uniqid('_widget_'.$class.'_');
    }
    
    
    
    public function __get($name){
        switch($name){
            case 'id':
                return $this->id;
            break;
            
            case 'meta_key':
                return $this->id;
            break;
            
            case 'scope':
                return $this->user_id ? 'user' : 'theme';
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
    
    public final function save(){
        
            if($this->scope == 'user'){
                // user scope
                if(!update_user_meta($this->user_id, $this->meta_key, base64_encode(serialize($this))))
                    add_user_meta($this->user_id, $this->meta_key, base64_encode(serialize($this)));
            }else{
                // theme scope
            }
        
                
    }
    
    public static function __print_form_icon(){
        $class = get_called_class();
        eval($class.'::form_icon();');
        
    }
    
    public function __print_update_form(){
        if($this->editable()){
?>
<form id='<?php echo $this->id?>_update_form_form' method="post" enctype="multipart/form-data">
    <input type='hidden' id='tnb_widget_action' name='tnb_widget_action' value='update_widget' />
    <input type='hidden' id='tnb_widget_id' name='tnb_widget_id' value='<?php echo $this->id; ?>' />

    <?php eval(get_class($this).'::update_form();'); ?>
</form>
<script type="text/javascript">
<!--
jQuery('#<?php echo $this->id?>_update_form_form').submit(function(){
   <?php $this->__js_update_form_validation($this->id.'_update_form_form'); ?> 
   return true;
});
//-->
</script>
<?php 
        }
    } 
    
    public function __print(){
        
 ?>
 <div class='tnb_widget clearfix'>
    <?php if($this->editable()): ?>
    <div class='tnb_widget_update_form clearfix' id="<?php echo $this->id?>_update_form" style="display:none;">
        <?php $this->__print_update_form(); ?>
        <div class='alignright'>
            <button class="config-button" onclick='jQuery("#<?php echo $this->id; ?>_update_form").fadeOut("fast", function(){jQuery("#<?php echo $this->id?>_body").fadeIn("fast");});'><?php _e('cancelar', 'tnb'); ?></button>
            <button class="config-button" onclick='jQuery("#<?php echo $this->id;?>_update_form_form").submit();'><?php _e('salvar','tnb')?></button>
        </div>
    </div> <!-- update form -->
    <?php endif; ?>
   
   <div id="<?php echo $this->id?>_body">
        <div class='tnb_widget_header clearfix'>
            <?php if($this->editable()): ?>
            <div class="alignright">
                <button class="config-button alignleft" onclick='jQuery("#<?php echo $this->id?>_body").fadeOut("fast", function(){jQuery("#<?php echo $this->id?>_update_form").fadeIn("fast"); })'><?php _e('editar', 'tnb')?></button>
                <form class='alignleft' method="post" onsubmit="return confirm('<?php echo sprintf(__("Você deseja apagar o widget \'%s\'?"), $this->getTitle()); ?>')"> <!-- delete icon -->
                    <input type='hidden' id='tnb_widget_action' name='tnb_widget_action' value='delete_widget' />
                    <input type='hidden' id='widget_id' name='widget_id' value='<?php echo $this->id; ?>'/>
                    <input type="image" src="<?php bloginfo('stylesheet_directory');?>/img/fechar.png" class="fechar"/>
                </form>
            </div>
            <?php endif; ?>
        
            <?php if(!is_null($this->getTitle())): ?>
                <h3><?php echo $this->getTitle(); ?></h3>
            <?php endif; ?>
        </div>
        <div class='tnb_widget_body clearfix' >
            <?php $this->__output(); ?>
        </div>
   </div>
</div>

<?php 
    }
    
    
    protected abstract function update_form();
    
    protected abstract function __output();
    
    protected abstract static function insert_form();
    
    protected abstract static function form_icon();
    
    public abstract static function getWidgetDescription();
    
    public abstract function getTitle();
     
    protected static function __js_insert_form_validation($form_id){}
    
    protected function __js_update_form_validation($form_id){}
}
