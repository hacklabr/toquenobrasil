<?php
global $oportunidade_item, $join_success, $unjoin_success;
$oportunidade_item = $post;

$tipo_evento = get_post_meta($oportunidade_item->ID, 'evento_tipo', true);

?>

<article id="<?php echo $oportunidade_item->post_name; ?>" class="opportunity grid_11 clearfix box-shadow">
    <h1 class="title">oportunidades</h1>
    <br/>
    
    <h2 class="title"><?php echo $tipo_evento; ?></h2>
    
    <h3><?php echo $oportunidade_item->post_title;?></h3>
    
    <?php if($join_success):?>
        <div class='success' id='join_success'><?php _e('Suas informações foram enviadas ao produtor do evento para curadoria. <br/> Apos encerramento das inscrições você receberá um email com a resposta positiva ou negativa.', 'tnb');?></div>
    <?php endif;?>
    
    <?php if($unjoin_success):?>
        <div class='success' id='join_success'><?php _e('Sua inscrição foi cancelada com sucesso', 'tnb');?></div>
    <?php endif;?>
    
    <div class="content">
        <iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:30px;" allowTransparency="true"></iframe>

    	<?php get_template_part('oportunidades-part-header');?>
        <?php get_template_part('oportunidades-part-join-button');?>
        <hr/>
        
        <?php get_template_part('oportunidades-part-selecionados'); ?>
        <?php get_template_part('oportunidades-part-inscritos'); ?>
    </div>
    <!-- .content -->
</article>
<!-- #opportunity -->
