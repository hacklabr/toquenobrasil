<?php if (can_create_oportunidade_paga() && current_user_can('edit_post', $oportunidade_item->ID)): ?>
<?php 
global $oportunidade_item, $user;

$pendentes = get_post_meta( $oportunidade_item->ID, 'inscricao_pendente') ;
$num_inscritos = count($pendentes);

?>
    <?php if(count($pendentes)): ?>
    <div class="signedup-artists clearfix">
        	
            <h2 class="title">
                <?php _e("Inscrições Pendentes", "tnb"); ?>
                <?php
                if(current_user_can('select_artists') || current_user_can('select_other_artists')) : 
                    echo "($num_inscritos)";
                endif; ?>
            </h2>
        
        <div class="clear"></div>         
                        
        
            <p>
                <a class="button" href="<?php echo add_query_arg('exportar','inscricao_pendente');?>">Exportar planilha</a>
                <a class="button" onclick="jQuery('#pending-artists-mailbox').dialog('open');">Enviar email</a>
            </p>
    
            <div class="tnb_modal" id="pending-artists-mailbox">
                <h2><?php _e("Email para artistas com inscrições pendentes");?></h2>
    
                <form method="post">
                    <p><?php _e('Produtor, se você espera alguma resposta dos artistas, não esqueça de informar um canal de contato. Este email é enviado pelo sistema e não pode ser respondido', 'tnb'); ?></p>
                    <input type="hidden" name="action" value="mail_pending_artists"/>
                    <input type="hidden" name="post_id" value="<?php echo $oportunidade_item->ID;?>"/>
                    <p>
                        <label for="subject-for-pending" class="clearfix"><?php echo _e("Assunto");?></label>
                        <input type="text" id="subject-for-pending" name="subject"/>
                    </p>
                    <label for="message-for-pending" class="clearfix"><?php echo _e("Mensagem");?></label>
                    <textarea id="message-for-pending" name="message"></textarea>
                    <p class="text-right">
                        <input type="submit" class="btn-grey" value="<?php _e("Enviar");?>"/>
                    </p>
                </form>
            </div>
            <!-- .tnb_modal -->
    
            <?php if($_POST['action']=='mail_pending_artists' && isset($GLOBALS['tnb_errors'])):?>
            <div class="error">
                <ul>
                <?php foreach($GLOBALS['tnb_errors'] as $error): ?>
                    <li><?php echo $error;?></li>
                <?php endforeach; unset($GLOBALS['tnb_errors']);?>
                </ul>
            </div>
            <?php elseif($_GET['message'] === 'sentforsigned'): ?>
                <div class="message-sent"><?php _e('Mensagem enviada.');?></div>
            <?php endif;?>
    
    
        
        <?php if(current_user_can('select_other_artists') || current_user_can('select_artists', $oportunidade_item->ID) ): ?>
            <?php include 'oportunidades-inscricoes-pendentes-tabela-produtor.php'; ?>
        <?php endif; ?>
                
    </div>
    <?php endif;?>
<?php endif;?>