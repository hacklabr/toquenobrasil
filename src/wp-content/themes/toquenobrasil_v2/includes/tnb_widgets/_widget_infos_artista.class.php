<?php
class Widget_Infos_Artista extends TNB_Widget{
    // valores padrão
    protected $property = array(
        'titulo' => 'Informações da Banda',
        'exibir_nome' => true,
        'exibir_avatar' => true,
        'exibir_mapa' => true,
        'exibir_rider' => true,
        'exibir_redes_sociais' => true,
        'exibir_release' => true,
        'exibir_integrantes' => true,
        'exibir_telefone'  => true,
        'exibir_email' => true,
        'exibir_origem'  => true,
        'exibir_residencia' => true,
        'exibir_estilo' => true
    );
    
    public function __js_update_form_validation($form_id){
        ?>
        if(jQuery("#<?php echo $form_id?> input:checked").length == 0){
            alert("<?php _e("Por favor, selecione ao menos uma opção.")?>");
            return false;
        }
        <?php
    }
    
    public static function __js_insert_form_validation($form_id){
        ?>
        if(jQuery("#<?php echo $form_id?> input:checked").length == 0){
            alert("<?php _e("Por favor, selecione ao menos uma opção.")?>");
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
        $artista = get_user_by('id', $this->user_id);
        $galeriasId['rider']        = tnb_get_artista_galeria($profileuser->ID, 'rider');
        $galeriasId['mapa_palco']   = tnb_get_artista_galeria($profileuser->ID, 'mapa_palco');
        
        $mapa_palco = null;
        $rider = null;
        
        if($this->property['exibir_mapa']){
            $media_mp = get_posts("post_parent=".$galeriasId['mapa_palco']->ID."&post_type=attachment&meta_key=_media_index&meta_value=mapa_palco_1&author={$this->user_id}");
            if(isset($media_mp[0]))
                $mapa_palco  = $media_mp[0];
        }
        
        if($this->property['exibir_rider']){
            $media_r = get_posts("post_parent=".$galeriasId['rider']->ID."&post_type=attachment&meta_key=_media_index&meta_value=rider_1&author={$this->user_id}");
            if(isset($media_r[0]))
                $rider  = $media_r[0];
        }    
        
        ?>
        <?php if($this->property['exibir_nome']):?>
            <h2> <?php echo $artista->display_name; ?> </h2>
        <?php endif;?>
        
        
        <div>
            <?php if($this->property['exibir_avatar']):?>
                <?php echo get_avatar($this->user_id); ?>
            <?php endif;?>
            
            <?php if($this->property['exibir_release']):?>
                <p class="bottom"><?php echo nl2br( strip_tags($artista->description, '<p><a><img><blockquote><i><b><hr>') ); ?></p>
            <?php endif;?>
            
            <?php if($this->property['exibir_integrantes']):?>
                <p class="bottom">
                    <strong><?php _e('Integrantes','tnb'); ?>:</strong><br />
                    <?php echo nl2br( strip_tags($artista->integrantes, '<p><a><img><blockquote><i><b><hr>') ); ?>
                </p>
            <?php endif;?>
        </div>
        <?php if($mapa_palco || $rider):?>
        <p class="bottom">
            <strong><?php _e('Downloads','tnb'); ?>:</strong>
            
            <?php if($mapa_palco):?>
            
                <a class="btn-download button" href="<?php echo wp_get_attachment_url($mapa_palco->ID); ?>"><?php _e('Mapa do Palco','tnb'); ?></a>
                
            <?php endif;?>
            
            <?php if($rider):?>
                
                <a class="btn-download button" href="<?php echo wp_get_attachment_url($rider->ID); ?>"><?php _e('Rider','tnb'); ?></a>
                
            <?php endif;?>

        </p>
        <?php endif; ?>
        <?php // INFORMAÇÔES RESTRITAS A PRODUTORES ?>
        
        <?php //if (current_user_can('select_artists')): ?>
            <p class="bottom">
                
                <?php if($this->property['exibir_telefone'] && $artista->telefone): ?>
                    <strong><?php _e('Telefone','tnb'); ?></strong>:                     
                   <?php echo $artista->telefone; ?><br />
                <?php endif;?>
                                
                <?php if($this->property['exibir_email'] && $artista->email_publico): ?>
                    <strong><?php _e('E-mail','tnb'); ?></strong>: 
                    <?php echo $artista->email_publico; ?>
                <?php endif;?>
            </p>
        <?php //endif; // user_can select_artists ?>
        
        <?php if($this->property['exibir_origem']):?>
                        
            <p class="bottom">
                <?php $paises = get_paises(); ?>
                <?php if($artista->origem_cidade || $artista->origem_estado) : ?>
                    <strong><?php _e('Origem:','tnb'); ?></strong>
                    <?php echo $artista->origem_cidade; ?> - <?php echo $artista->origem_estado; ?> (<?php echo $paises[$artista->origem_pais]; ?>)
                <?php elseif($this->editable()) : ?>
                    <strong><?php _e('Origem:','tnb'); ?></strong>
                    <a href="<?php echo get_author_posts_url($this->user_id); ?>/editar/banda"><?php _e("adicionar esta informação.") ?></a>
                <?php endif; ?>
            </p>
            
        <?php endif;?>
        
        <?php if($this->property['exibir_residencia']):?>
            <p class="bottom">
                <?php if (!is_array($paises)) $paises = get_paises(); ?>
                <?php if($artista->banda_cidade || $artista->banda_estado || $artista->banda_pais) : ?>
                    <strong><?php _e('Residência:','tnb'); ?></strong>
                    <?php echo $artista->banda_cidade; ?> - <?php echo $artista->banda_estado; ?> (<?php echo $paises[$artista->banda_pais]; ?>)
                <?php elseif($this->editable()) : ?>
                    <strong><?php _e('Residência:','tnb'); ?></strong>
                    <a href="<?php echo get_author_posts_url($this->user_id); ?>/editar/banda"><?php _e("adicionar esta informação.") ?></a>
                <?php endif; ?>
            </p>
        <?php endif;?>
        
        <?php if($this->property['exibir_estilo'] && $artista->estilo_musical_livre):?>
            <p class="bottom">
                <strong><?php _e('Estilo','tnb'); ?></strong><br />
                <?php echo $artista->estilo_musical_livre; ?>
            </p>
        <?php endif;?>
        <?php if($this->property['exibir_redes_sociais']):?>
            <?php if($artista->facebook || $artista->twitter || $artista->orkut || $artista->youtube || $artista->vimeo) : ?>
                <p class="redes-sociais bottom">
                    <strong><?php _e('Redes Sociais','tnb'); ?> </strong><br />
                    <?php if($artista->facebook): ?> <a href='<?php echo $artista->facebook?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/facebook.png" alt="" /></a> <?php endif; ?> 
                    <?php if($artista->twitter): ?> <a href='<?php echo $artista->twitter?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/twitter.png" alt="" /></a> <?php endif; ?>
                    <?php if($artista->orkut): ?> <a href='<?php echo $artista->orkut?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/orkut.png" alt="" /></a> <?php endif; ?>
                    <?php if($artista->youtube): ?> <a href='<?php echo $artista->youtube?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/youtube.png" alt="" /></a> <?php endif; ?>
                    <?php if($artista->vimeo): ?> <a href='<?php echo $artista->vimeo?>' target="blank"><img src="<?php bloginfo('stylesheet_directory');?>/img/vimeo.png" alt="" /></a> <?php endif; ?>
                </p>
            <?php endif; ?>
        <?php endif;?>
        <?php 

    }
    
    protected static function insert_form(){
?>
<h3><?php _e('Informações', 'tnb'); ?></h3>
<div><label><?php _e('Título', 'tnb')?>: <input type='text' name='property[titulo]' value='<?php _e('Informações da banda','tnb'); ?>'/></label></div> 
<div><?php _e("Exibir",'tnb')?>:
    <div><label><input type='checkbox' name='property[exibir_nome]' value='1'/> <?php _e('Nome da banda', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_avatar]' value='1'/> <?php _e('Foto do perfil', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_mapa]' value='1'/> <?php _e('Mapa do palco', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_rider]' value='1'/> <?php _e('Rider', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_redes_sociais]' value='1'/> <?php _e('Redes Sociais', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_release]' value='1'/> <?php _e('Release', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_integrantes]' value='1'/> <?php _e('Integrantes', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_telefone]' value='1'/> <?php _e('Telefone', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_email]' value='1'/> <?php _e('E-mail', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_origem]' value='1'/> <?php _e('Local de Origem', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_residencia]' value='1'/> <?php _e('Local de Resiência', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_estilo]' value='1'/> <?php _e('Estilo', 'tnb')?></label></div>
</div>
<?php 
    }
    
    protected function update_form(){
?>
<h3><?php _e('Informações', 'tnb'); ?></h3>
<div><label><?php _e('Título', 'tnb')?>: <input type='text' name='property[titulo]' value='<?php echo htmlentities(utf8_decode($this->property['titulo'])); ?>'/></label></div> 
<div><?php _e("Exibir",'tnb')?>:
    <div><label><input type='checkbox' name='property[exibir_nome]' value='1' <?php if($this->property['exibir_nome']) echo 'checked="checked"'; ?>/> <?php _e('Nome da banda', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_avatar]' value='1' <?php if($this->property['exibir_avatar']) echo 'checked="checked"'; ?>/> <?php _e('Foto do perfil', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_mapa]' value='1' <?php if($this->property['exibir_mapa']) echo 'checked="checked"'; ?>/> <?php _e('Mapa do palco', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_rider]' value='1' <?php if($this->property['exibir_rider']) echo 'checked="checked"'; ?>/> <?php _e('Rider', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_redes_sociais]' value='1' <?php if($this->property['exibir_redes_sociais']) echo 'checked="checked"'; ?>/> <?php _e('Redes Sociais', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_release]' value='1' <?php if($this->property['exibir_release']) echo 'checked="checked"'; ?>/> <?php _e('Release', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_integrantes]' value='1' <?php if($this->property['exibir_integrantes']) echo 'checked="checked"'; ?>/> <?php _e('Integrantes', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_telefone]' value='1' <?php if($this->property['exibir_telefone']) echo 'checked="checked"'; ?>/> <?php _e('Telefône', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_email]' value='1' <?php if($this->property['exibir_email']) echo 'checked="checked"'; ?>/> <?php _e('E-mail', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_origem]' value='1' <?php if($this->property['exibir_origem']) echo 'checked="checked"'; ?>/> <?php _e('Local de Origem', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_residencia]' value='1' <?php if($this->property['exibir_residencia']) echo 'checked="checked"'; ?>/> <?php _e('Local de Resiência', 'tnb')?></label></div>
    <div><label><input type='checkbox' name='property[exibir_estilo]' value='1' <?php if($this->property['exibir_estilo']) echo 'checked="checked"'; ?>/> <?php _e('Estilo', 'tnb')?></label></div>
</div>
<?php 
    }
    
    
    protected static function form_icon(){
        _e('Informações da banda','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um TNBox com as informações da banda que deseja publicar.','tnb');
    }
}
