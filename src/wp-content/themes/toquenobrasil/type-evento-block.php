<?php
	$inicio = get_post_meta(get_the_ID(), "evento_inicio", true);
	$fim = get_post_meta(get_the_ID(), "evento_fim", true);
	$br_inicio = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3", $inicio);
	$br_fim = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3",$fim);
	$inscricao_inicio = get_post_meta(get_the_ID(), "evento_inscricao_inicio", true);
	$inscricao_fim = get_post_meta(get_the_ID(), "evento_inscricao_fim", true);
	$br_insc_inicio = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3", $inscricao_inicio);
	$br_insc_fim = preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/","$1/$2/$3",$inscricao_fim);
?>

<?php global $current_user; ?>

<div id="thumb" class="span-4">
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail('eventos'); ?>
	<?php else : ?>
		<?php theme_image("thumb.png") ?>
	<?php endif; ?>
</div><!-- .thumb -->

<div class="span-8">
	<div id="dados-do-evento">
		<p>
			<span class="labels">Tipo de evento:</span> <?php echo get_post_meta(get_the_ID(), "evento_tipo", true); ?><br />
			<span class="labels">Data do evento:</span> <?php echo ($br_fim==$br_inicio ? $br_inicio : "$br_inicio - $br_fim") ;?><br />
			<span class="labels">Inscrições até:</span> <?php echo $br_insc_fim; ?><br />
			<span class="labels">Local:</span> <?php echo get_post_meta(get_the_ID(), "eventos_local", true); ?><br />
			<span class="labels">Site:</span> <a href="<?php echo get_post_meta(get_the_ID(), "eventos_site", true); ?>" target="_blank"><?php echo get_post_meta(get_the_ID(), "eventos_site", true); ?></a><br />
			<span class="labels">Vagas:</span> <?php echo get_post_meta(get_the_ID(), "eventos_vagas", true); ?>
		</p>
	</div><!-- .dados-do-evento -->
</div>

<div class="span-2 last">
	<?php if( is_artista() && in_postmeta(get_post_meta(get_the_ID(), 'selecionado'), $current_user->ID)): ?>
	
		<div class="quero-tocar">
			<a >Já fui<br />selecionado!</a>
		</div><!-- .quero-tocar -->
	
	<?php  elseif(is_artista() &&  in_postmeta(get_post_meta(get_the_ID(), 'inscrito'), $current_user->ID)): ?>
		<div class="quero-tocar">
			<a >Já estou<br />inscrito!</a>
		</div><!-- .quero-tocar -->
	
	<?php  elseif(strtotime($inscricao_inicio) <= time() && strtotime($inscricao_fim) >= time() && is_artista()):?>
		<form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php the_ID(); ?>'>
			<?php wp_nonce_field('join_event'); ?>
			<input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
			<input type="hidden" name="evento_id" value='<?php the_ID(); ?>' />
		</form>
		
		<div class="quero-tocar">
			<a href="#" onclick="jQuery('#form_join_event_<?php the_ID(); ?>').submit();">Quero<br />tocar!</a>
		</div><!-- .quero-tocar -->
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
