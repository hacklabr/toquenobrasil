<div class="artist clearfix">
    <h2><a href="http://www.prefuse73.com" target="_blank"><?php echo $banda->Banda;?></a></h2>
    <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/9k799uxd-150x150.jpg" class="span-3"/>
    <p>
      Responsável: <?php echo $banda->responsavel;?> 
      <br/>
      Telefone: <?php echo $banda->ddd;?> <?php echo $banda->telefone;?>
      <br/>
      <a href="<?php echo $banda->site;?>"><?php echo $banda->site;?></a>
      <br/>
      <a href="mailto:<?php echo $banda->user_email;?>"><?php echo $banda->user_email;?></a>
      
      <?php if((current_user_can('select_other_artists') || current_user_can('select_artists')) && !get_post_meta(get_the_ID(), 'selecionado', $banda->ID)):?>
      	<form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php the_ID(); ?>'>
         	 <?php wp_nonce_field('select_band'); ?>
         	 <input type="hidden" name="banda_id" value='<?php echo $banda->ID; ?>' />
         	 <input type="hidden" name="evento_id" value='<?php the_ID(); ?>' />
        </form>
          <div class="quero-tocar">
            <a href="#" onclick="jQuery('#form_join_event_<?php the_ID(); ?>').submit();">Selecionar esta<br />Banda!</a>
            <div class="shadow"></div>
          </div><!-- .quero-tocar -->
      <?php endif;?>
      
    </p>
    
</div>