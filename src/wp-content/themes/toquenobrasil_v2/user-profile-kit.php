<?php 
$galeriasId['rider']        = tnb_get_artista_galeria($profileuser->ID, 'rider');
$galeriasId['mapa_palco']   = tnb_get_artista_galeria($profileuser->ID, 'mapa_palco'); ?>

<button id="abrir-kit" class="config-button" >Abrir KIT</button>

<div class="tnb_modal" id="kit-artista">
    
    <h2><?php _e("Kit do artista", "tnb"); ?> "<?php echo $curauth->display_name; ?>"</h2>
    
    <p><em><?php _e('Você está vendo o KIT (Kit de Informações Técnicas) do artista. Feche essa janela para ver o perfil completo. Se quiser abrir novamente o KIT, clique no botão no topo da página', 'tnb'); ?></em></p>

    <div class="content">
        <div class="thumbnail"><?php echo get_avatar($curauth->ID); ?></div>
        <p class="description"><?php echo $curauth->description; ?></p>

        <p>
            <strong><?php _e("Downloads", "tnb"); ?>:</strong>
            <?php
                $media = get_posts("post_parent=".$galeriasId['rider']->ID."&post_type=attachment&meta_key=_media_index&meta_value=rider_1&author={$curauth->ID}");
                if(isset($media[0])){
                    $media  = $media[0];
                    ?>
                    <a class="btn-download bg-yellow" href="<?php echo wp_get_attachment_url($media->ID); ?>">Rider</a>
                    <?php
                }
            ?>
            &nbsp;
            <?php
                $media = get_posts("post_parent=".$galeriasId['mapa_palco']->ID."&post_type=attachment&meta_key=_media_index&meta_value=mapa_palco_1&author={$curauth->ID}");
        
                if(isset($media[0])){
                    $media  = $media[0];
                    ?>
                    <a class="btn-download bg-yellow" href="<?php echo wp_get_attachment_url($media->ID); ?>">Mapa de Palco</a>
                    <?php
                }
            ?>
        </p>
    
        <?php $musica = tnb_get_artista_musica_principal($curauth->ID); ?>
        <?php if($musica): ?>
            <p>
                <strong><?php _e('Música Principal', 'tnb'); ?></strong>
                <br/>
                <?php print_audio_player($musica->ID);?>
            </p>
        <?php endif;?> 
    
        <?php $video = tnb_get_artista_video_principal($curauth->ID); ?>
        <?php if($video): ?>
            <p>
                <strong><?php _e('Vídeo Principal', 'tnb'); ?></strong>
                <br/>
                <?php print_video_player($video->post_excerpt);?>
            </p>
        <?php endif;?> 

        <p>
            <strong><?php _e('Contato', 'tnb'); ?></strong>
            <br/>
            <?php _e('Responsável', 'tnb'); ?>: <?php echo $curauth->responsavel; ?>
            <br/>
            <?php _e('e-mail', 'tnb'); ?>: <?php echo $curauth->user_email; ?>
            <br/>
            <?php _e('Telefone', 'tnb'); ?>: <?php echo $curauth->telefone; ?>
        </p>
    
        <p>
            <strong><?php _e('Origem', 'tnb'); ?></strong>
            <br/>
            <?php $paises = get_paises(); ?>
            <?php echo $curauth->origem_cidade; ?> - <?php echo $curauth->origem_estado; ?> (<?php echo $paises[$curauth->origem_pais]; ?>)
        </p>
    
        <p>
            <strong><?php _e('Residência', 'tnb'); ?></strong>
            <br/>
            <?php echo $curauth->banda_cidade; ?> - <?php echo $curauth->banda_estado; ?> (<?php echo $paises[$curauth->banda_pais]; ?>)
        </p>
    </div>
    
    

</div>

<script>

jQuery(document).ready(function() {

    jQuery('#kit-artista').dialog('open');
    
    jQuery('#abrir-kit').click(function() {
        jQuery('#kit-artista').dialog('open');
    });

});

</script>
