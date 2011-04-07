<?php
class Widget_Texto extends TNB_Widget{
    protected static function form_filter($instance){
        $_POST['property']['text'] = strip_tags(stripslashes($_POST['property']['text']), '<p><a><img><blockquote><i><b>' );
        $_POST['property']['titulo'] = strip_tags(stripslashes($_POST['property']['titulo']));
        return true;
    }
    
    protected static function form_validation(){
        return true;
    }
    
    public function getTitle(){
        return $this->property['titulo'] ? $this->property['titulo'] : null;
    }
    
    protected static function form_icon(){
         _e('texto','tnb');
    }
    
    
    protected static function insert_form(){
?>
<h3><?php _e('Texto','tnb')?></h3>
<label><?php _e('título','tnb')?>: <input type='text' name='property[titulo]' /></label><br/>
<label><?php _e('texto','tnb')?>: <textarea name='property[text]'></textarea></label>
<?php 
    }
    
    protected function update_form(){
?>
<h3><?php _e('Texto','tnb')?></h3>
<label><?php _e('título','tnb')?>: <input type='text' name='property[titulo]' value="<?php echo htmlentities(utf8_decode($this->property['titulo']))?>"/></label><br/>
<label><?php _e('texto','tnb')?>: <textarea name='property[text]'><?php echo htmlentities(utf8_decode($this->property['text'])); ?></textarea></label>
<?php 
        
    }
    
     
    public function __output(){ 
        echo nl2br($this->property['text']); 
    }
    
    public function __js_update_form_validation($form_id){
    ?>
        if(jQuery("#<?php echo $form_id?> textarea").val() == ''){
            alert("<?php _e('O campo texto deve ser preenchido.', 'tnb')?>");
            return false;
        }
    <?php 
    }
    
    public static function __js_insert_form_validation($form_id){
    ?>
        if(jQuery("#<?php echo $form_id?> textarea").val() == ''){
            alert("<?php _e('O campo texto deve ser preenchido.', 'tnb')?>");
            return false;
        }
    <?php 
    }
    
    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um aplicativo de texto.','tnb');
    }
}
