<?php
  $inicio = get_post_meta(get_the_ID(), "evento_inicio", true);
  $fim = get_post_meta(get_the_ID(), "evento_fim", true);
  $br_inicio = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3", $inicio);
  $br_fim = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3",$fim);
  $inscricao_inicio = get_post_meta(get_the_ID(), "evento_inscricao_inicio", true);
  $inscricao_fim = get_post_meta(get_the_ID(), "evento_inscricao_fim", true);
  $br_insc_inicio = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3", $inscricao_inicio);
  $br_insc_fim = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3",$inscricao_fim);
  $local = get_post_meta(get_the_ID(), "evento_local", true);
  $link = get_post_meta(get_the_ID(), "evento_site", true);
  $vagas = get_post_meta(get_the_ID(), "evento_vagas", true);
  $condicoes = get_post_meta($post->ID, "evento_condicoes", true);
  $restricoes = get_post_meta($post->ID, "evento_restricoes", true);
  $tos = get_post_meta(get_the_ID(), "evento_tos", true);
?>

<?php global $current_user; ?>

<a href="<?php the_permalink(); ?>" title="">
	<div id="thumb" class="span-4">
		<?php
			$event_link_title = __('Visitar página do evento', 'tnb');
			if ( has_post_thumbnail() ) :
				the_post_thumbnail('eventos', array('title'=> $event_link_title));
			else :
				theme_image("thumb.png", array('title'=> $event_link_title));
			endif;
		?>
	</div><!-- .thumb -->
</a>
<div class="span-7">
  <div id="dados-do-evento">
		
    <p>
      <span class="labels"><?php _e('Tipo de evento:', 'tnb');?></span> <?php echo get_post_meta(get_the_ID(), "evento_tipo", true); ?><br />
      <span class="labels"><?php _e('Data do evento:', 'tnb');?></span> <?php echo ($br_fim==$br_inicio ? $br_inicio : "$br_inicio - $br_fim") ;?><br />
      <span class="labels"><?php _e('Inscrições até:', 'tnb');?></span> <?php echo $br_insc_fim; ?><br />
			
      <?php if($local):?>
        <span class="labels"><?php _e('Local:', 'tnb');?></span> <?php echo $local; ?><br />
      <?php endif; ?>
            
      <?php if($link):?>
        <span class="labels"><?php _e('Site:', 'tnb');?></span> <a href="<?php echo $link; ?>" target="_blank"><?php echo $link; ?></a><br />
      <?php endif; ?>
            
      <?php if($vagas):?>
        <span class="labels"><?php _e('Vagas:', 'tnb');?></span> <?php echo $vagas; ?>
      <?php endif; ?>
    </p>
  </div><!-- .dados-do-evento -->
</div>

<div class="span-3 last">
  
  <?php if( is_artista() && in_postmeta(get_post_meta(get_the_ID(), 'selecionado'), $current_user->ID)): ?>
	
    <div class="quero-tocar iam-selected">
      <a><?php _e('Já fui<br />selecionado!', 'tnb');?></a>
    </div>
	
  <?php  elseif(is_artista() &&  in_postmeta(get_post_meta(get_the_ID(), 'inscrito'), $current_user->ID)): ?>
    <div class="quero-tocar iam-signed">
      <a><?php _e('Já estou<br />inscrito!', 'tnb');?></a>
    </div>
	
  <?php  elseif(strtotime($inscricao_inicio) <= strtotime(date('Y-m-d')) && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))):?>
		
    <?php if( is_artista() ):?>
    	<form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php the_ID(); ?>'>
          <?php wp_nonce_field('join_event'); ?>
          <input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
          <input type="hidden" name="evento_id" value='<?php the_ID(); ?>' />
        </form>
        
        <?php if($tos):?>	
            <div class='evento_tos_modal' id='evento_tos_modal_<?php the_ID(); ?>'>
            	<h2><?php _e('Termo de Responsabilidade','tnb'); ?></h2>
                <?php echo $tos; ?>
                <div class="textright">
           	    	<a onclick="jQuery('#form_join_event_<?php the_ID(); ?>').submit();" class="button"><?php _e('Li e aceito o termo', 'tnb');?></a>
                </div>
            </div>
            <div class="quero-tocar i-wanna-play">
            	<a onclick="jQuery('#evento_tos_modal_<?php the_ID(); ?>').dialog('open');" title="<?php printf(__('Participe do evento %s', 'tnb'),  get_the_title());?>"><?php _e('Quero <br />tocar!', 'tnb');?></a>
            </div>
        <?php else:?>
            <div class="quero-tocar i-wanna-play">
       	    	<a onclick="jQuery('#form_join_event_<?php the_ID(); ?>').submit();" ><?php _e('Quero <br />tocar!', 'tnb');?></a>
            </div>
        <?php endif;?>   
      
    <?php  elseif(!is_user_logged_in()) :?>
      <div class="quero-tocar i-wanna-play">
        <a href="<?php bloginfo('url');?>/cadastre-se/artista" title='<?php _e('Cadastre-se para poder participar do Toque no Brasil!', 'tnb');?>'><?php _e('Quero <br />tocar!', 'tnb');?></a>
      </div>
    <?php endif;?>

  <?php  else :?>	

    <div class="quero-tocar iam-signed">
      <a><?php _e('Inscrições <br /> encerradas!', 'tnb');?></a>
    </div>

  <?php endif;?>
</div>

<?php if(is_single()) echo '<div class="clear"></div>' ;?>

<div class="span-14">
  <?php 
    if(is_single())
      the_content();
    else
      the_excerpt();
  ?>
  <div class="clear"></div>

  <?php if($restricoes) : ?>
    <div class="restrictions prepend-bottom">
      <h3>
        <a><?php _e('Restrições para participar','tnb'); ?></a>
      </h3>
      <p><?php echo $restricoes; ?></p>
    </div>
  <?php endif; ?>	
			
  <?php if($condicoes) : ?>
    <div class="conditions">
      <h3>
        <a><?php _e('Condições para participar','tnb'); ?></a>
      </h3>
      <p><?php echo $condicoes; ?></p>
    </div>
  <?php endif;  ?>	
	
  <div class="post-tags">
    <p><?php the_tags(" "," "," "); ?></p>
  </div><!-- .post-tags -->
</div>

<div class="clear"></div>
