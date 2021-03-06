<?php
global $oportunidade_item, $current_user;
$data = get_oportunidades_data($oportunidade_item->ID); 
extract($data);
$can_join = tnb_artista_can_join($oportunidade_item->ID);
?>

<?php if(is_produtor() && $superevento && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))): ?>
    <p class="novo-subevento quero-tocar text-right">
        <a href="<?php echo get_author_posts_url($current_user->ID);?>/editar/oportunidades/?superevento=no&post_parent=<?php echo $oportunidade_item->ID;?>" class="btn-green">Inscrever meu evento</a>
    </p>
<?php elseif( is_artista() && in_postmeta(get_post_meta($oportunidade_item->ID, 'selecionado'), $current_user->ID)): ?>
    <p class="quero-tocar iam-selected text-right">
        <a class="btn-green"><?php _e('Já fui selecionado!', 'tnb');?></a>
    </p>
<?php  elseif(is_artista() &&  in_postmeta(get_post_meta($oportunidade_item->ID, 'inscrito'), $current_user->ID) && strtotime($inscricao_inicio) <= strtotime(date('Y-m-d')) && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))): ?>

    <form action='<?php the_permalink();?>' method="post" id='form_unjoin_event_<?php echo $oportunidade_item->ID; ?>'>
        <?php wp_nonce_field('unjoin_event'); ?>
        <input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
        <input type="hidden" name="action" value='unjoin' />
        <input type="hidden" name="evento_id" value='<?php echo $oportunidade_item->ID; ?>' />
    </form>
    
    <p class="quero-tocar cancel-subscription text-right">
        <?php if(isset($data['inscricao_cobrada']) && $data['inscricao_cobrada']):?>
            <a onclick="if(confirm('<?php echo addslashes(__i("Você está cancelando sua inscrição para uma oportunidade paga. O dinheiro da sua inscrição não será reembolsado. Tem certeza que deseja cancelar sua inscrição?", '[gerenciar oportunidades - artista] - mensagem que avisa ao usuário que ele está cancelando a inscrição em uma oportunidade paga', false))?>')) jQuery('#form_unjoin_event_<?php echo $oportunidade_item->ID; ?>').submit();" class="btn-green"><?php _e('Cancelar inscrição', 'tnb');?></a>
        <?php else:?>
            <a onclick="jQuery('#form_unjoin_event_<?php echo $oportunidade_item->ID; ?>').submit();" class="btn-green"><?php _e('Cancelar inscrição', 'tnb');?></a>
        <?php endif;?>
    </p>

<?php  elseif(is_artista() &&  in_postmeta(get_post_meta($oportunidade_item->ID, 'inscricao_pendente'), $current_user->ID) && strtotime($inscricao_inicio) <= strtotime(date('Y-m-d')) && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))): ?>
    
    <form action='<?php the_permalink();?>' method="post" id='form_unjoin_event_<?php echo $oportunidade_item->ID; ?>'>
        <?php wp_nonce_field('unjoin_event'); ?>
        <input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
        <input type="hidden" name="action" value='unjoin' />
        <input type="hidden" name="evento_id" value='<?php echo $oportunidade_item->ID; ?>' />
    </form>

    <div class="quero-tocar cancel-subscription text-right append-bottom clearfix">
        <div class="alignright"><?php print_inscricao_pay_button($oportunidade_item->ID, $current_user->ID); ?></div>
        <div class="alignright">
            <a onclick="jQuery('#form_unjoin_event_<?php echo $oportunidade_item->ID; ?>').submit();" class="btn-green"><?php _e('Cancelar inscrição', 'tnb');?></a>
        </div>
    </div>
    
<?php  elseif(!$superevento && strtotime($inscricao_inicio) <= strtotime(date('Y-m-d')) && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))):?>  
    <?php if( is_artista() ):?>
        <?php if($can_join): ?>
            <form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php echo $oportunidade_item->ID; ?>'>
                <?php wp_nonce_field('join_event'); ?>
                <input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
                <input type="hidden" name="action" value='join' />
                <input type="hidden" name="evento_id" value='<?php echo $oportunidade_item->ID; ?>' />
            </form>
            
            <?php if($tos):?>    
                <div class='tnb_modal' id='tnb_modal_<?php echo $oportunidade_item->ID; ?>'>
                    <h2><?php _e('Termo de Responsabilidade','tnb'); ?></h2>
                    <p><?php echo $tos; ?></p>
                    <div class="text-center">
                        <a onclick="jQuery('#form_join_event_<?php echo $oportunidade_item->ID; ?>').submit();" class="btn-grey" class="btn-green"><?php _e('Li e aceito o termo', 'tnb');?></a>
                    </div>
                </div>
                <p class="quero-tocar i-wanna-play text-right">
                    <a onclick="jQuery('#tnb_modal_<?php echo $oportunidade_item->ID; ?>').dialog('open');" title="<?php printf(__('Participe do evento %s', 'tnb'),  get_the_title());?>" class="btn-green"><?php _e('Me Inscrever!', 'tnb');?></a>
                </p>
            <?php else:?>
                <p class="quero-tocar i-wanna-play text-right">
                    <a onclick="jQuery('#form_join_event_<?php echo $oportunidade_item->ID; ?>').submit();" class="btn-green"><?php _e('Me Inscrever!', 'tnb');?></a>
                </p>
            <?php endif;?>
        <?php else: ?>
            <p class="text-center"><?php _e('Você não pode se inscrever pois seu perfil não se enquadra dentro das restrições de estilo musical ou localidade dessa oportunidade.', 'tnb')?></p>
        <?php endif; ?>
    <?php  elseif(!is_user_logged_in()) :?>
      <p class="quero-tocar i-wanna-play text-right">
        <a href="<?php bloginfo('url');?>/cadastro" title='<?php _e('Cadastre-se para poder participar do Toque no Brasil!', 'tnb');?>' class="btn-green"><?php _e('Me Inscrever!', 'tnb');?></a>
      </p>
    <?php endif;?>

<?php /* Quando as inscrições estão encerradas */ ?>
<?php elseif(strtotime($inscricao_fim) < strtotime(date('Y-m-d'))): ?>    

    <p class="quero-tocar inscricoes-encerradas text-right">
        <a class="btn-green"><?php _e('Inscrições encerradas!', 'tnb');?></a>
    </p>

<?php /* Quando as inscrições ainda não abriram */ ?>
<?php elseif(strtotime($inscricao_inicio) > strtotime(date('Y-m-d'))): ?>    

    <p class="quero-tocar em-breve text-right">
        <a class="btn-green"><?php _e('Em breve!', 'tnb');?></a>
    </p>

<?php endif;?>

