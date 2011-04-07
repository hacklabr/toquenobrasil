<?php $inscritos = get_post_meta( $oportunidade_item->ID, 'inscrito') ; ?>

<table class="artists">
    <?php foreach ($inscritos as $banda_id): ?>
        <?php if($banda = get_userdata($banda_id)): ?>
            <?php $musica = tnb_get_artista_musica_principal($banda->ID); ?>
            <tr>
                <td> <a  target="_blank" href="<?php echo get_author_posts_url($banda->ID); ?>"> <?php echo $banda->display_name; ?></a> </td>
                <td> 
                    <?php if($musica): ?>
                        <?php print_audio_player($musica->ID);?>
                    <?php endif;?> 
                </td>
                <td class="text-right"> 
                    <?php if(current_user_can('select_other_artists') || current_user_can('select_artists', $oportunidade_item_id) ): ?>
                        <form method="post" id='form_join_event_<?php echo $banda->ID; ?>'>
                            <?php wp_nonce_field('select_band'); ?>
                            <input type="hidden" name="action" value="select_band"/>
                            <input type="hidden" name="banda_id" value='<?php echo $banda->ID; ?>' />
                            <input type="hidden" name="evento_id" value='<?php echo $oportunidade_item->ID; ?>' />
                        </form>
                        <a class="button" onclick="jQuery('#form_join_event_<?php echo $banda->ID; ?>').submit();"><?php _e('Selecionar!','tnb'); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
