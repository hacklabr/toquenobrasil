<?php 
	global $current_user;
	
	$join_success = false;
	

	if(isset($_POST['_wpnonce']) &&  wp_verify_nonce($_POST['_wpnonce'], 'join_event' ) ){
		if(!in_postmeta(get_post_meta($_POST['evento_id'], 'inscrito'), $_POST['banda_id'])){
			add_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);
			$to = get_post_meta($_POST['evento_id'] , 'evento_recipient', true);
//			$copyAdm = false;
			$header = '';
			if(!$to){
				$to = get_bloginfo('admin_email');
			}else{
			    $header = 'cc:' . get_bloginfo('admin_email');
			    //$copyAdm = true;    
			}
			
			$banda = get_userdata($_POST['banda_id']);
			$event = get_post($_POST['evento_id']);
			$event_name = $event->post_title; 
	
			
			
			//  PARA o organizador e/ou admin  (admin em cópia quando há produtor)
			
			$msg = "Você acaba de receber uma nova inscrição para o evento {$event_name}.\n\n";
			$msg.= "Informações do artista\n";
			$msg.= "Nome: {$banda->banda}\n";
			$msg.= "Perfil: ". get_author_posts_url($banda->ID)."\n";
			$msg.= "Responsável: {$banda->responsavel}\n";
			$msg.= "Email: {$banda->user_email}\n";
			$msg.= "Telefone: {$banda->telefone_ddd} {$banda->telefone}\n";
			$msg.= "Residência: {$banda->banda_cidade} - {$banda->banda_estado}\n\n";
			$msg.= "Atenciosamente\n";
			$msg.= "Toque No Brasil";
			
			$join_success = true;
			$subject = 'Inscrição TNB | ' . $event_name . ' | '. $banda->banda;
			wp_mail($to, $subject, $msg, $header);
			
			
			
			///// PARA O ARTISTA
			$subject = 'Inscrição TNB | ' . $event_name ;
					
			$msg = "Obrigado por se inscrever no {$event_name}.\n\n";
			$msg.= "Em breve você receberá um e-mail confirmando se você foi selecionado para \"Tocar no Brasil!\"\n\n";
			$msg.= "Atenciosamente\n";
			$msg.= "Toque No Brasil";
			wp_mail($banda->user_email, $subject, $msg);
			
			//if($copyAdm)
			  //  wp_mail(get_bloginfo('admin_email'), $subject, $msg);    
		}

	} elseif(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'select_band' ) ) {
		delete_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);

		if(!in_postmeta(get_post_meta($_POST['evento_id'] , 'selecionado'), $_POST['banda_id']))
			add_post_meta($_POST['evento_id'], 'selecionado', $_POST['banda_id']);

	} elseif(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'unselect_band' ) ) {
		delete_post_meta($_POST['evento_id'], 'selecionado', $_POST['banda_id']);
		if(!in_postmeta(get_post_meta($_POST['evento_id'] , 'inscrito'), $_POST['banda_id']))
			add_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);    
	}

	//var_dump($current_user);

	get_header(); 
?>

<div class="prepend-top"></div>

<?php if ( have_posts() ) : the_post(); ?>
	<div id="event-<?php echo the_ID(); ?>" class="event span-14 prepend-1 right-colborder">
		<div id="event-<?php echo the_ID(); ?>-title" class="item green clearfix">
			<div class="title pull-1 clearfix">
				<div class="shadow"></div>
				<h1><?php the_title(); ?></h1>
			</div>
		</div>

		<div id="event-<?php echo the_ID(); ?>-content" class="clearfix">
			<?php get_template_part('type-evento', 'block'); ?>
		</div>

		<div id="selected-artists-title" class="item yellow clearfix">
			<div class="title pull-1 clearfix">
				<div class="shadow"></div>
				<h3><?php _e('Artistas/Bandas Selecionados','tnb'); ?></h3>
			</div>
		</div>

		<div id="selected-artists-list" class="clearfix">
			<?php
				$inscritos = get_post_meta( get_the_ID(), 'selecionado') ;

				foreach($inscritos as $banda_id){
					if($banda = get_userdata($banda_id))
					    include('evento-banda-block.php'); 
				}
			?>
		</div>

		<div id="signed-artists-title" class="item yellow clearfix">
			<div class="title pull-1 clearfix">
				<div class="shadow"></div>
                                  <h3><?php _e('Artistas/Bandas Inscritos','tnb'); ?></h3>
			</div>
		</div>

		<div id="signed-artists-list" class="clearfix">
			<?php if($join_success):?>
				<div class='success' id='join_success'><?php _e('Suas informações foram enviadas ao produtor do evento para curadoria. <br/> Apos encerramento das inscrições você receberá um email com a resposta positiva ou negativa.', 'tnb');?></div>
				<script type="text/javascript">
					jQuery.scrollTo('#join_success', 800);
				</script>
			<?php endif;?>

			<?php
				$inscritos = get_post_meta( get_the_ID(), 'inscrito') ;

				foreach($inscritos as $banda_id){
					if($banda = get_userdata($banda_id))
					    include('evento-banda-block.php'); 
				}
			?>
		</div>
		
		<div id="posts-navigation">
			<?php previous_post_link('<div id="anterior">%link</div>','Evento anterior', true); ?>
			<?php next_post_link('<div id="proximo">%link</div>', 'Próximo evento', true); ?>            
		</div><!-- #posts-navigation -->
	</div>
<?php endif; ?>

<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("tnb-sidebar");?>
    </div>
</div>
<?php get_footer(); ?>
