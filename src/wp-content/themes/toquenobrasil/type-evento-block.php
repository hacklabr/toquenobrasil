<?php
	$inicio = get_post_meta(get_the_ID(), "evento_inicio", true);
	$fim = get_post_meta(get_the_ID(), "evento_fim", true);
	$br_inicio = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3", $inicio);
	$br_fim = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3",$fim);
	$inscricao_inicio = get_post_meta(get_the_ID(), "evento_inscricao_inicio", true);
	$inscricao_fim = get_post_meta(get_the_ID(), "evento_inscricao_fim", true);
	$br_insc_inicio = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3", $inscricao_inicio);
	$br_insc_fim = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3",$inscricao_fim);
    $el = get_post_meta(get_the_ID(), "evento_local", true);
    $es = get_post_meta(get_the_ID(), "evento_site", true);
    $ev = get_post_meta(get_the_ID(), "evento_vagas", true);
?>

<?php global $current_user; ?>

<div id="thumb" class="span-4">
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail('eventos'); ?>
	<?php else : ?>
		<?php theme_image("thumb.png") ?>
	<?php endif; ?>
</div><!-- .thumb -->

<div class="span-7">
	<div id="dados-do-evento">
		
		<p>
			<span class="labels"><?php _e('Tipo de evento:', 'tnb');?></span> <?php echo get_post_meta(get_the_ID(), "evento_tipo", true); ?><br />
			<span class="labels"><?php _e('Data do evento:', 'tnb');?></span> <?php echo ($br_fim==$br_inicio ? $br_inicio : "$br_inicio - $br_fim") ;?><br />
			<span class="labels"><?php _e('Inscrições até:', 'tnb');?></span> <?php echo $br_insc_fim; ?><br />
			
            <?php if($el):?>
                <span class="labels"><?php _e('Local:', 'tnb');?></span> <?php echo $el; ?><br />
            <?php endif; ?>
            
            <?php if($es):?>
			    <span class="labels"><?php _e('Site:', 'tnb');?></span> <a href="<?php echo $es; ?>" target="_blank"><?php echo $es; ?></a><br />
            <?php endif; ?>
            
            <?php if($ev):?>
			    <span class="labels"><?php _e('Vagas:', 'tnb');?></span> <?php echo $ev; ?>
            <?php endif; ?>
		</p>
	</div><!-- .dados-do-evento -->
</div>

<div class="span-3 last">

	<div class='evento_tos_modal' id='evento_tos_modal_<?php the_ID(); ?>'>
		<?php echo get_post_meta(get_the_ID(), "evento_tos", true);?>
		<form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php the_ID(); ?>'>
			<?php wp_nonce_field('join_event'); ?>
			<input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
			<input type="hidden" name="evento_id" value='<?php the_ID(); ?>' />
		</form>
		<a onclick="jQuery('#form_join_event_<?php the_ID(); ?>').submit();" ><?php _e('Li e aceito <br />tocar', 'tnb');?></a>
		
	</div>

	<?php if( is_artista() && in_postmeta(get_post_meta(get_the_ID(), 'selecionado'), $current_user->ID)): ?>
	
		<div class="quero-tocar iam-selected">
			<a><?php _e('Já fui<br />selecionado!', 'tnb');?></a>
		</div>
	
	<?php  elseif(is_artista() &&  in_postmeta(get_post_meta(get_the_ID(), 'inscrito'), $current_user->ID)): ?>
		<div class="quero-tocar iam-signed">
			<a><?php _e('Já estou<br />inscrito!', 'tnb');?></a>
		</div>
	
	<?php  elseif(strtotime($inscricao_inicio) <= time() && strtotime($inscricao_fim) >= time()):?>
		
		<?php if( is_artista() ):?>
    		<div class="quero-tocar i-wanna-play">
    			<a onclick="jQuery('#evento_tos_modal_<?php the_ID(); ?>').dialog('open');" title="<?php printf(__('Participe do evento %s', 'tnb'),  get_the_title());?>"><?php _e('Quero <br />tocar!', 'tnb');?></a>
    		</div>
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
	<div class="post-tags">
		<p><?php the_tags(" "," "," "); ?></p>
	</div><!-- .post-tags -->
</div>

<div class="clear"></div>
