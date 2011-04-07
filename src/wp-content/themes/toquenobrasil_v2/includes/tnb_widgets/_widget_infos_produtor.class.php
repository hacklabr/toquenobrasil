<?php
class Widget_Infos_Produtor extends TNB_Widget{
     protected $property = array(
        'titulo' => 'Informações do Produtor',
        'exibir_nome' => true,
        'exibir_avatar' => true,
        'exibir_telefone'  => true,
        'exibir_email' => true,
        'exibir_origem'  => true,
        'exibir_redes_sociais' => true
    );
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
       return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['titulo'] ? $this->property['titulo'] : null;
    }
    
    protected function __output(){
        $user = get_user_by('id', $this->user_id);
        
        ?>
        <?php if($this->property['exibir_nome']):?>
            <h2> <?php echo $user->display_name; ?> </h2>
        <?php endif;?>
        
        <?php if($this->property['exibir_avatar']):?>
            <div>
                <?php echo get_avatar($this->user_id); ?>
            </div>
        <?php endif;?>
         
        <?php //if (current_user_can('select_artists')): ?>
            <p>
                <?php if($this->property['exibir_telefone'] && $user->telefone):?>
                    <strong><?php _e('Telefone','tnb'); ?></strong>:                     
                    <?php echo $user->telefone; ?><br />
                <?php endif;?>
                                
                <?php if($this->property['exibir_email'] && $user->email_publico):?>
                    <strong><?php _e('E-mail','tnb'); ?></strong>: 
                    <?php echo $user->email_publico; ?>
                <?php endif;?>
            </p>
        <?php //endif; // user_can select_artists ?>
        
        <?php if($this->property['exibir_origem']):?>
            <p>
                <strong><?php _e('Origem','tnb'); ?></strong><br />
                <?php $paises = get_paises(); ?>
                <?php echo $user->origem_cidade; ?> - <?php echo $user->origem_estado; ?> (<?php echo $paises[$user->origem_pais]; ?>)
            </p>
            
        <?php endif;?>
       
       <?php if($this->property['exibir_redes_sociais']):?>
            <?php if($user->facebook || $user->twitter || $user->orkut || $user->youtube || $user->vimeo) : ?>
                <p class="redes-sociais bottom">
                    <strong><?php _e('Redes Sociais','tnb'); ?> </strong><br />
                    <?php if($user->facebook): ?> <a href='<?php echo $user->facebook?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/facebook.png" alt="" /></a> <?php endif; ?> 
                    <?php if($user->twitter): ?> <a href='<?php echo $user->twitter?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/twitter.png" alt="" /></a> <?php endif; ?>
                    <?php if($user->orkut): ?> <a href='<?php echo $user->orkut?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/orkut.png" alt="" /></a> <?php endif; ?>
                    <?php if($user->youtube): ?> <a href='<?php echo $user->youtube?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/youtube.png" alt="" /></a> <?php endif; ?>
                    <?php if($user->vimeo): ?> <a href='<?php echo $user->vimeo?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/vimeo.png" alt="" /></a> <?php endif; ?>
                </p>
            <?php endif; ?>
        <?php endif;?>
        <?php 

    }
    
    protected static function insert_form(){
?>
<h3><?php _e('Informações','tnb'); ?></h3>
<div><label><?php _e('Título', 'tnb')?>: <input type='text' name='property[titulo]' value='<?php _e('Informações do produtor','tnb'); ?>'/></label></div> 
<div>Exibir:
    <div><label><input type='checkbox' name='property[exibir_nome]' value='1'/> <?php _e('Nome', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_avatar]' value='1'/> <?php _e('Foto do perfil', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_telefone]' value='1'/> <?php _e('Telefone', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_email]' value='1'/> <?php _e('E-mail', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_origem]' value='1'/> <?php _e('Local de Origem', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_redes_sociais]' value='1'/> <?php _e('Redes Sociais', 'tnb')?></label></div>

</div>
<?php 
    }
    
    protected function update_form(){
?>
<h3><?php _e('Informações','tnb'); ?></h3>
<div><label><?php _e('Título', 'tnb')?>: <input type='text' name='property[titulo]' value='<?php echo htmlentities(utf8_decode($this->property['titulo'])); ?>'/></label></div> 
<div>Exibir:
    <div><label><input type='checkbox' name='property[exibir_nome]' value='1' <?php if($this->property['exibir_nome']) echo 'checked="checked"'; ?>/> <?php _e('Nome', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_avatar]' value='1' <?php if($this->property['exibir_avatar']) echo 'checked="checked"'; ?>/> <?php _e('Foto do perfil', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_telefone]' value='1' <?php if($this->property['exibir_telefone']) echo 'checked="checked"'; ?>/> <?php _e('Telefône', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_email]' value='1' <?php if($this->property['exibir_email']) echo 'checked="checked"'; ?>/> <?php _e('E-mail', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_origem]' value='1' <?php if($this->property['exibir_origem']) echo 'checked="checked"'; ?>/> <?php _e('Local de Origem', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_redes_sociais]' value='1' <?php if($this->property['exibir_redes_sociais']) echo 'checked="checked"'; ?>/> <?php _e('Redes Sociais', 'tnb')?></label></div>

</div>
<?php 
    }
    
    
    protected static function form_icon(){
        _e('Informações do produtor','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique para inserir uma caixa com suas informações.','tnb');
    }
}
