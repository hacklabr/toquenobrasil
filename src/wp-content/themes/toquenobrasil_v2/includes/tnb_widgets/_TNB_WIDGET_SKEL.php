<?php
class TNB_WIDGET_SKEL extends TNB_Widget{
    public static function form_filter(){
        // retornando false, o widget não será alterado / inserido
        if(!trim($_POST['property']['title'])){
            // não está funcionando ainda, mas a idéia é que o que for impresso seja apresentado para o usuário 
            // como uma mensagem de erro.
            _e('o título é obrigatório');
            return false;
        }
        
        $_POST['property']['title'] = strtoupper($_POST['property']['title']); 
        
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['title'] ? $this->property['title'] : null;
    }
    
    protected function __output(){
?>
SÓ TITULO: <?php echo $this->property['title']; ?>
<?php 
    }
    
    protected static function insert_form(){
?>
titulo: <input type='text' name='property[title]' value=''/> 
<?php 
    }
    
    protected function update_form(){
?>
titulo: <input type='text' name='property[title]' value='<?php echo $this->property['title']; ?>'/> 
<?php 
    }
    
    
    protected static function form_icon(){
        _e("ESQUELETO",'tnb');
    }
    
    public static function getWidgetDescription(){
        return __('Este é apenas um modelo de widget que não deve ser incluido...');
    }
}