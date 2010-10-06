<div id="artist-<?php echo $banda->ID; ?>" class="artist span-6">
  <div id="artist-<?php echo $banda->ID; ?>-content" class="content clearfix">
    <div id="artist-<?php echo $banda->ID; ?>-avatar" class="avatar span-2">
      <a href="<?php echo get_author_posts_url($banda->ID)?>" class="avatar" title="<?php _e('Ver o perfil do artista/banda','tnb'); echo ' ', get_user_meta($banda->ID, 'banda', true); ?>">
        <?php echo get_avatar($banda->ID, 70); ?>
      </a>
    </div>
	
    <div id="artist-<?php echo $banda->ID; ?>-name" class=" span-3">
      <a href="<?php echo get_author_posts_url($banda->ID)?>" class="name" title="<?php _e('Ver o perfil do artista/banda','tnb'); echo ' ', get_user_meta($banda->ID, 'banda', true); ?>">
        <?php echo get_user_meta($banda->ID, 'banda', true); ?>
      </a>
    </div>
    <?php
      global $authordata, $current_user;
      if(current_user_can('select_other_artists') || $authordata->ID == $current_user->ID):
        if(in_postmeta(get_post_meta(get_the_ID(), 'inscrito'), $banda->ID)):?>
				
          <form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php echo $banda->ID; ?>'>
            <?php wp_nonce_field('select_band'); ?>
            <input type="hidden" name="banda_id" value='<?php echo $banda->ID; ?>' />
            <input type="hidden" name="evento_id" value='<?php the_ID(); ?>' />
          </form>
					
          <div class="select-artist">
            <a class="button" href="#" onclick="jQuery('#form_join_event_<?php echo $banda->ID; ?>').submit();"><?php _e('Selecionar!','tnb'); ?></a>
          </div>

        <?php elseif(in_postmeta(get_post_meta(get_the_ID(), 'selecionado'), $banda->ID)):?>

          <form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php echo $banda->ID; ?>'>
            <?php wp_nonce_field('unselect_band'); ?>
            <input type="hidden" name="banda_id" value='<?php echo $banda->ID; ?>' />
            <input type="hidden" name="evento_id" value='<?php the_ID(); ?>' />
          </form>
					
          <div class="deselect-artist">
            <a class="button" href="#" onclick="jQuery('#form_join_event_<?php echo $banda->ID; ?>').submit();"><?php _e('Deselecionar','tnb'); ?>.</a>
          </div><!-- .quero-tocar -->
        <?php endif;?>
    <?php endif;?>
  </div>
</div>
