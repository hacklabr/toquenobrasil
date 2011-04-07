<?php
class Widget_Facebook extends TNB_Widget{
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
                
        $_POST['property']['title'] = strtoupper($_POST['property']['title']); 
        
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['title'] ? $this->property['title'] : null;
    }
    
    protected function __output(){
        $uid = uniqid('facebook_');
        $url = get_author_posts_url($this->user_id);
        ?>
        <iframe id='<?php echo $uid?>' src="http://www.facebook.com/plugins/like.php?href<?php echo $url; ?>&layout=standard&show_faces=false&width=442&action=like&colorscheme=light&height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:23px; margin-top:5px;"  allowTransparency="true"></iframe>
        
        <?php if($this->property['fundo_branco']):?>
            <script type="text/javascript">
            document.getElementById('<?php echo $uid?>').parentNode.parentNode.parentNode.style.backgroundColor = 'white';
            </script>
        <?php endif;
        
    }
    
    protected static function insert_form(){
        ?>
        <h3>Facebook</h3>
        <label><input type='checkbox' name='property[fundo_branco]' /> <?php _e('utilizar fundo branco')?></label>
        <?php
    }
    
    protected function update_form(){
         ?>
         <h3>Facebook</h3>
         <label><input type='checkbox' name='property[fundo_branco]' <?php if($this->property['fundo_branco']) echo 'checked="checked" '?>/> <?php _e('utilizar fundo branco')?></label>
         <?php
    }
    
    
    protected static function form_icon(){
        _e('Facebook','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir botão “Curtir” do Facebook.','tnb');
    }
}
