<?php
global $oportunidade_item;
$oportunidade_item = $post;

$sub_oportunidades = $wpdb->get_results("
	SELECT * FROM {$wpdb->posts} WHERE post_type = 'eventos' AND post_parent = '$post->ID' AND post_status='publish' AND ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento' AND meta_value = '$post->ID')");
?>

<article id="<?php echo $oportunidade_item->post_name; ?>" class="opportunity grid_11 clearfix box-shadow">
    <h1 class="title"><?php _e("oportunidades", "tnb"); ?></h1>
    <br/>
    <h2 class="title"><?php _e("Oportunidade para Produtores", "tnb"); ?></h2>
    <h3><?php echo $oportunidade_item->post_title;?></h3>
    <div class="content">
        <iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:30px;" allowTransparency="true"></iframe>
        
        <?php get_template_part('oportunidades-part-header');?>
        <?php get_template_part('oportunidades-part-join-button');?>
        
        <?php if (current_user_can('edit_post', $oportunidade_item->ID)): ?>
            <p>
            <a class="btn-grey" href="<?php echo add_query_arg(array('exportar_tipo' => 'superevento', 'exportar' =>'inscricao_pendente'));?>"><?php _e('Exportar planilha de inscrições pendentes em todos as oportunidades dentro desta oportunidade'); ?></a>
            <br />
            <a class="btn-grey" href="<?php echo add_query_arg(array('exportar_tipo' => 'superevento', 'exportar' =>'inscrito'));?>"><?php _e('Exportar planilha de inscritos em todos as oportunidades dentro desta oportunidade'); ?></a>
            <br />
            <a class="btn-grey" href="<?php echo add_query_arg(array('exportar_tipo' => 'superevento', 'exportar' => 'selecionado'));?>"><?php _e('Exportar planilha de selecionados em todos as oportunidades dentro desta oportunidade'); ?></a>
            <br />
            <a class="btn-grey" href="<?php echo add_query_arg(array('exportar_tipo' => 'superevento', 'exportar' => 'produtor'));?>"><?php _e('Exportar planilha com produtores de todos as oportunidades dentro desta oportunidade'); ?></a>
            </p>
        <?php endif; ?>
        
        <hr/>
        <h2 class="title top"><?php _e("Oportunidades Cadastradas", "tnb"); ?></h2>
        
        <?php foreach ($sub_oportunidades as $oportunidade_item):?>
            <h3><a href="<?php echo get_permalink($oportunidade_item->ID); ?>" title="<?php echo $oportunidade_item->post_title;?>"><?php echo $oportunidade_item->post_title;?></a></h3>
            <div class="content sub-opportunity">
                <?php get_template_part('oportunidades-part-header');?>
            </div>
            <hr />
        <?php endforeach; ?>
        
    </div>
    <!-- .content -->
</article>
<!-- #opportunity -->
