<?php
    /* Configura main loop para eventos normais */
    global $paged;
    $list_status = ($_GET['list_status'] == 'draft' || $_GET['list_status'] == 'pending') ? $_GET['list_status'] : 'publish';
    
    if (current_user_can('edit_others_posts')) {
        $query_args = array(
            'post_type' => 'eventos',
            'post_parent' => 0,
            'post_status' => $list_status,
            //'posts_per_page' => 3,
            'paged' => $paged
        );
    } else {
        $query_args = array(
            'author' => $current_user->ID,
            'post_type' => 'eventos',
            'post_status' => $list_status,
            //'posts_per_page' => 3,
            'paged' => $paged
        );
    }   
    if($list_status == 'pending')
        $query_args['post_status'] = 'pay_pending_review,pay_pending_ok';
    
    $normal_events = query_posts($query_args);
    global $wp_query, $post;
?>

<div class="minhas-oportunidades" <?php if ($creatingSubEvent) echo 'style="display:none;"'; ?> >
    
    <h2 class="section-title alignleft">
        <span class="bg-blue"><?php _e("Minhas Oportunidades", "tnb"); ?> (<?php echo $wp_query->found_posts; ?>)</span>
    </h2>

    <div class="clear"></div>

    <a href="<?php echo get_author_posts_url($profileuser->ID), '/editar/oportunidades/?list_status=publish'; ?>" class="<?php echo ($list_status == 'publish' || $list_status == "") ? 'btn-yellow ' : 'btn-grey'; ?>"><?php _e('oportunidades ativas','tnb'); ?></a>
    <a href="<?php echo get_author_posts_url($profileuser->ID), '/editar/oportunidades/?list_status=draft'; ?>" class="btn-yellow <?php echo ($list_status == 'draft') ? 'btn-yellow' : 'btn-grey'; ?>"><?php _e('oportunidades inativas','tnb'); ?></a>
    <?php if(can_create_oportunidade_paga()):?>
    <a href="<?php echo get_author_posts_url($profileuser->ID), '/editar/oportunidades/?list_status=pending'; ?>" class="btn-yellow <?php echo ($list_status == 'pending') ? 'btn-yellow' : 'btn-grey'; ?>"><?php _e('oportunidades pendentes','tnb'); ?></a>
    <?php endif;?>

    <hr/>

    <?php while(have_posts()): the_post();?>

        <?php $data = get_oportunidades_data(get_the_ID()); ; ?>

        <div id="<?php echo $post->post_name; ?>" class="opportunity clearfix">
            <div class="thumbnail clearfix">
                <?php if ( has_post_thumbnail($evento_list_item_id) ) : ?>
                    <?php echo get_the_post_thumbnail($evento_list_item_id, array(80,80));?>
                <?php else : ?>
                    <?php theme_image("tnb-thumb-80.png", array("alt" => get_the_title($evento_list_item_id), "title" => get_the_title($evento_list_item_id))); ?>
                <?php endif; ?>
            </div>
            <!-- .thumbnail -->

            <div class="information clearfix">
                <div class="title">
                    <h3 class="title bottom">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                    </h3>
                </div>
                <!-- .title -->

                <?php if (current_user_can('edit_post', get_the_ID())): ?>
                    <div class="edit text-right">
                        <a href="<?php echo get_author_posts_url($post->post_author), '/editar/oportunidades/', $post->post_name; ?>" title="<?php _e('Editar');?>" class="alignright">
                            <?php _e('Editar');?>
                        </a><br />
                    </div>
                    <!-- .edit -->
                <?php endif; ?>
                
                <ul class="clear">
                    <?php if($post->post_status == 'pay_pending_ok'): $contrato = get_contrato_inscricao($post->ID); ?>
                    	<?php if(current_user_can('edit_post', get_the_ID())): ?> 
	                        <li>
	                        	<strong><a href='javascript:void(0);' onclick='jQuery("#<?php echo $post->ID?>-contrato").dialog({title: "<?php echo sprintf(__('Contrato para oportunidade %s','tnb'),$post->post_title); ?>",width: "900px"});'><?php _e('aguardando aceitação do contrato','tnb'); ?></a></strong>
	                        	<div id='<?php echo $post->ID?>-contrato' style="display:none">
	                        		<div style='max-height: 100%; overflow: auto;'>
	                        			<?php echo nl2br(get_contrato_inscricao_substituido($post->ID, $contrato['valor'], $contrato['porcentagem'], $contrato['contrato'])); ?>
	                        		</div>
	                        		<form method="post" class="text-center">
	                        			<input type='hidden' name='tnb_user_action' value='' />
	                        			<input type='hidden' name='evento_id' value='<?php the_ID()?>' />
	                        			<input type='button' value='aceitar e publicar evento' onclick="this.form.tnb_user_action.value = 'contrato-oportunidade-aceitar'; this.form.submit();" class="grey"/> 
	                        			<input type='button' value='recusar'  onclick="this.form.tnb_user_action.value = 'contrato-oportunidade-recusar'; this.form.submit();" class="grey"/>
									</form>
	                        	</div>
	                       	</li>
	                    <?php else:?>
	                    	<li><strong><?php _e('aguardando aceitação do contrato','tnb'); ?></strong></li>
	                	<?php endif; ?>
                    <?php elseif($post->post_status == 'pay_pending_review'):?>
                        <li><strong><?php _e('aguardando a revisão do editor','tnb'); ?></strong></li>
                    <?php elseif(get_contrato_inscricao($post->ID)):  $contrato = get_contrato_inscricao($post->ID);?>
                    		<li>
	                        	<strong><a href='javascript:void(0);' onclick='jQuery("#<?php echo $post->ID?>-contrato").dialog({title: "<?php echo sprintf(__('Contrato para oportunidade %s','tnb'),$post->post_title); ?>",width: "900px"});'><?php _e('visualizar contrato','tnb'); ?></a></strong>
	                        	<div id='<?php echo $post->ID?>-contrato' style="display:none">
	                        		<div style='max-height: 100%; overflow: auto;'>
	                        		<?php echo nl2br(get_contrato_inscricao_substituido($post->ID, $contrato['valor'], $contrato['porcentagem'], $contrato['contrato'])); ?>
	                        		</div>
	                        	</div>
	                       	</li>
                    <?php endif;?>
                    <li><span class="label">Data:</span> <?php echo ($data['br_fim']==$data['br_inicio'] ? $data['br_inicio'] : "$data[br_inicio] - $data[br_fim]") ;?></li>                
                    <?php if(strtotime($data['inscricao_fim']) < strtotime(date('Y-m-d'))): ?>
                        <li><span class="label">Inscrições encerradas</span></li>
                    <?php elseif (strtotime($inscricao_data['inicio']) > strtotime(date('Y-m-d'))):?>
                        <li><span class="label">Inscrições a partir de:</span> <?php echo $data['br_insc_inicio']; ?></li>
                    <?php else:?>
                        <li><span class="label">Inscrições até:</span> <?php echo $data['br_insc_fim']; ?></li>
                    <?php endif;?>
                    <li><span class="label">Local:</span> <?php echo $data['local']; ?></li>
                </ul>
            
                <?php if ($post->post_parent > 0): ?>
                    Esta oportunidade está cadastrada dentro da oportunidade <strong>"<?php echo get_the_title($post->post_parent); ?>"</strong> e está 
                    <?php if (get_post_meta($post->ID, 'aprovado_para_superevento', true) == $post->post_parent): ?>
                        <strong>aprovada</strong>
                    <?php else: ?>
                        <strong>aguardando aprovação</strong>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- LISTAGEM DE SUB-EVENTOS -->
                <?php if ($data['superevento']): ?>
                    <?php
                        if (current_user_can('edit_post', get_the_ID())) {
                            $query_args = array(
                                'post_type' => 'eventos',
                                'post_parent' => get_the_ID(),
                                'meta_key' => 'superevento',
                                'meta_value' => 'no',
                                'post_status' => 'any',
                                'numberposts' => -1
                            );
                        } else {
                            $query_args = array(
                                'post_type' => 'eventos',
                                'post_parent' => get_the_ID(),
                                'author' => $current_user->ID,
                                'meta_key' => 'superevento',
                                'meta_value' => 'no',
                                'post_status' => 'any',
                                'numberposts' => -1
                            );
                        }

                        $add_subevent_url=  sprintf("%s/editar/oportunidades/?superevento=no&post_parent=%d",
                                                    get_author_posts_url($post->post_author),
                                                    get_the_ID());

                        $subevents = get_posts($query_args); 
                    ?>
                    <div class="sub-eventos clearfix">
                        <h4 class="bottom"><?php echo sizeof($subevents) . ' ' . (sizeof($subevents) > 1 ? __('Oportunidades cadastradas', 'tnb') : __('Subevento', 'tnb')); ?></h4>
                        <ul>
                            <?php foreach($subevents as $sub): ?>
                                <li <?php if ($sub->post_status == 'draft') echo 'class="evento-inativo"';?>>
                                    <a href="<?php echo get_permalink($sub->ID); ?>" title="<?php echo $sub->post_title;?>"><?php echo $sub->post_title;?></a>
                                    <?php if ($sub->post_status == 'draft') : ?><span>(Inativo)</span><?php endif; ?>

                                    <div class="edit">
                                        <?php if (current_user_can('edit_post', $sub->ID)): ?>
                                            <a href="<?php echo get_author_posts_url($sub->post_author), '/editar/oportunidades/', $sub->post_name; ?>" title="<?php _e('Editar');?>"><?php _e('Editar');?></a>
                                        <?php endif; ?>

                                        <?php if (get_post_meta($sub->ID, 'aprovado_para_superevento', true) == get_the_ID()): ?>                            
                                            <?php if (current_user_can('edit_post', get_the_ID())): // quem pode editar o superevento, pode moderar os subeventos  ?>
                                                <a class="recusar-subevento" href="<?php echo add_query_arg(array('recusar_subevento' => $sub->ID, 'aprovar_subevento' => null)); ?>" title="<?php _e('Recusar');?>"><?php _e('Recusar');?></a>
                                            <?php else: ?>
                                                Aprovada
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if (current_user_can('edit_post', get_the_ID())): // quem pode editar o superevento, pode moderar os subeventos  ?>
                                                <a class="aprovar-subevento" href="<?php echo add_query_arg(array('aprovar_subevento'=> $sub->ID, 'recusar_subevento' => null)); ?>" title="<?php _e('Aprovar');?>"><?php _e('Aprovar');?></a>
                                            <?php else: ?>
                                                Aguardando aprovação
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <!-- .edit -->
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <!-- .sub-eventos -->
                <?php endif; ?>
            </div>
            <!-- .information -->
        </div>
        <!-- .opportunity -->

        <hr/>
    
    <?php endwhile; ?>

    <div class="navigation clearfix">
        <?php previous_posts_link('<div class="left-navigation alignleft">Anterior</div>'); ?>
        <?php next_posts_link('<div class="right-navigation alignright">Próxima</div>'); ?>
    </div>
    <!-- .navigation -->
</div>
<!-- .minhas-oportunidades -->
