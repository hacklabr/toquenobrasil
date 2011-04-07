<?php
class Widget_Container extends TNB_Widget{
    protected static function form_filter($instance){
        $_POST['property']['titulo'] = stripslashes($_POST['property']['titulo']);
        return true;
    }
    
    protected static function form_validation(){
        return true;
    }
    
    protected static function insert_form(){
?>
<label>titulo: <input type='text' name='property[titulo]' /></label>
<label>num_cols: <input type='text' name='property[num_cols]' /></label>
<input type='submit' name='save' value='save' />
<?php 
    }
    
    protected function update_form(){
?>
<label>text: <textarea name='property[text]'><?php echo $this->property['text']; ?></textarea></label>
<input type='submit' name='save' value='save' />
<?php 
        
    }
    
     
    public function getTitle(){
        return trim($this->property['titulo']) ? $this->property['titulo'] : null ;
    }
    
    public function __output(){
        global $current_user;
        $containers = array();
        for($i = 0; $i < $this->property['num_cols']; $i++)
            $containers[] = $this->id.'_'.$i;
        
        $widget_classes = array('TNB_Text_Widget', 'TNB_Image_Widget', 'TNB_Container_Widget');
        
        $widget_group = new TNB_WidgetContainerGroup('principal', $containers, $this->user_id, $widget_classes );
        $widget_group->do_actions(); 
?>
<table class='profile' cellpadding="0" cellspacing="0">
    <tr>
        <?php foreach($containers as $container):?>
        <td valign="top" style="text-align: justify;">
            <?php $widget_group->containers[$container]->__print();; ?>
            
        <?php endforeach;?>
   </tr>
</table>
<?php 
    }
    
    public static function form_icon(){
        echo 'CONTAINER';
    }
}