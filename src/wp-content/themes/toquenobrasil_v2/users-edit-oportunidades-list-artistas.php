<?php
    /* Configura main loop para eventos normais */
    global $paged, $wpdb;
    
    $query_subevents_arovados = " AND (post_parent = 0 OR ID IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento') ) ";

    $query = "
    SELECT 
        ID 
    FROM 
        $wpdb->posts 
    WHERE
        post_type = 'eventos' AND
        post_status = 'publish' AND
        post_title LIKE '%$nome%' AND 
        (ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'inscrito' AND meta_value = '{$profileuser->ID}' ) OR
        ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'selecionado' AND meta_value = '{$profileuser->ID}' ))
        $query_subevents_arovados";

    //echo " QUERY { $query } ";
    $oportunidadesID = $wpdb->get_col($query);
    if (sizeof($oportunidadesID) == 0) {
        // se não vier nada, temos que colocar alguma coisa que impeça a query de trazer todos
        $oportunidadesID = array(0);
    }
    $query_args = array(
        'post_type' => 'eventos',
        'post__in' => $oportunidadesID,
        'meta_key' => 'evento_inicio', 
        'orderby' => 'meta_value',
        'order' => 'DESC',
        'paged' => $paged
    );
    
    $normal_events = query_posts($query_args);
    global $post, $oportunidade_item;
    $post;

?>

<h2 class="section-title">
    <span class="bg-blue"><?php _e("Minhas Oportunidades", "tnb"); ?></span>
</h2>

<?php if(have_posts()) : while(have_posts()): the_post();?>

    <?php $data = get_oportunidades_data(get_the_ID()); ?>

    <div id="<?php echo $post->post_name; ?>" class="opportunity clearfix">
        <div class="grid_3 alpha">
        <?php if ( has_post_thumbnail($evento_list_item_id) ) : ?>
            <?php echo get_the_post_thumbnail($evento_list_item_id, array(160,160));?>
        <?php else : ?>
            <?php theme_image("tnb-thumb-160.png", array("alt" => get_the_title($evento_list_item_id), "title" => get_the_title($evento_list_item_id))); ?>
        <?php endif; ?>
        </div>
        <div class="grid_6 alpha omega">
            
            <h3 class="title bottom"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
            <ul>
                <li><span class="label">Data do evento:</span> <?php echo ($data['br_fim']==$data['br_inicio'] ? $data['br_inicio'] : "$data[br_inicio] - $data[br_fim]") ;?></li>
                
                <?php if(strtotime($data['inscricao_fim']) < strtotime(date('Y-m-d'))): ?>
                    <li><span class="label">Inscrições encerradas</span></li>
                 <?php elseif (strtotime($inscricao_data['inicio']) > strtotime(date('Y-m-d'))):?>
                    <li><span class="label">Inscrições a partir de:</span> <?php echo $data['br_insc_inicio']; ?></li>
                 <?php else:?>
                    <li><span class="label">Inscrições até:</span> <?php echo $data['br_insc_fim']; ?></li>
                 <?php endif;?>
                    <li><span class="label">Local:</span> <?php echo $data['local']; ?></li>

            </ul>

            <?php if( is_artista($profileuser->ID) && in_postmeta(get_post_meta($post->ID, 'selecionado'), $profileuser->ID)): ?>
                <p class="quero-tocar iam-selected">
                    <a><?php _e('Já fui selecionado!', 'tnb');?></a>
                </p>
            <?php  elseif(is_artista($profileuser->ID) &&  in_postmeta(get_post_meta($post->ID, 'inscrito'), $profileuser->ID) && strtotime($data['inscricao_inicio']) <= strtotime(date('Y-m-d')) && strtotime($data['inscricao_fim']) >= strtotime(date('Y-m-d'))): ?>

                <form action='<?php the_permalink();?>' method="post" id='form_unjoin_event_<?php echo $post->ID; ?>'>
                    <?php wp_nonce_field('unjoin_event'); ?>
                    <input type="hidden" name="banda_id" value='<?php echo $profileuser->ID; ?>' />
                    <input type="hidden" name="action" value='unjoin' />
                    <input type="hidden" name="evento_id" value='<?php echo $post->ID; ?>' />
                </form>

                <p class="quero-tocar cancel-subscription">
                    <a onclick="jQuery('#form_unjoin_event_<?php echo $post->ID; ?>').submit();"><?php _e('Cancelar inscrição', 'tnb');?></a>
                </p>
            
            <?php elseif(strtotime($data['inscricao_fim']) < strtotime(date('Y-m-d'))): ?>

                <p class="quero-tocar inscricoes-encerradas">
                    <a><?php _e('Inscrições encerradas!', 'tnb');?></a>
                </p>
            
            <?php endif; ?>

        </div>

    </div>
    <!-- .opportunity -->
    <hr/>
    
<?php endwhile; else : ?>
    <hr/>
    <p class="text-center">
        <?php _e("Você ainda não se inscreveu em nenhum evento", "tnb"); ?>
    </p>
    <hr/>
<?php endif; ?>

<?php previous_posts_link('<div class="left-navigation alignleft">Anterior</div>'); ?>
<?php next_posts_link('<div class="right-navigation alignright">Próxima</div>'); ?>
