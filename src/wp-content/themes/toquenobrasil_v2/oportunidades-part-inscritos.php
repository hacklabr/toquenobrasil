<?php 
global $oportunidade_item, $user;

$inscritos = get_post_meta( $oportunidade_item->ID, 'inscrito') ;
$num_inscritos = count($inscritos);

?>

<div class="signedup-artists clearfix">
    <?php if(count($inscritos)): ?>	
        <h2 class="title">
            <?php _e("Artistas Inscritos", "tnb"); ?>
            <?php
            if(current_user_can('select_artists') || current_user_can('select_other_artists')) : 
                echo "($num_inscritos)";
            endif; ?>
        </h2>
    <?php endif;?>
    <div class="clear"></div>         
                    
    <?php if (current_user_can('edit_post', $oportunidade_item->ID)): ?>
        <p>
            <a class="button" href="<?php echo add_query_arg('exportar','inscrito');?>">Exportar planilha</a>
            <a class="button" onclick="jQuery('#signed-artists-mailbox').dialog('open');">Enviar email</a>
        </p>

        <div class="tnb_modal" id="signed-artists-mailbox">
            <h2><?php _e("Email para artistas inscritos");?></h2>

            <form method="post">
                <p><?php _e('Produtor, se você espera alguma resposta dos artistas, não esqueça de informar um canal de contato. Este email é enviado pelo sistema e não pode ser respondido', 'tnb'); ?></p>
                <input type="hidden" name="action" value="mail_signed_artists"/>
                <input type="hidden" name="post_id" value="<?php echo $oportunidade_item->ID;?>"/>
                <p>
                    <label for="subject-for-signed" class="clearfix"><?php echo _e("Assunto");?></label>
                    <input type="text" id="subject-for-signed" name="subject"/>
                </p>
                <label for="message-for-signed" class="clearfix"><?php echo _e("Mensagem");?></label>
                <textarea id="message-for-signed" name="message"></textarea>
                <p class="text-right">
                    <input type="submit" class="btn-grey" value="<?php _e("Enviar");?>"/>
                </p>
            </form>
        </div>
        <!-- .tnb_modal -->

        <?php if($_POST['action']=='mail_signed_artists' && isset($GLOBALS['tnb_errors'])):?>
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

    <?php endif; ?>
    
    <?php if(current_user_can('select_other_artists') || current_user_can('select_artists', $oportunidade_item->ID) ): ?>
        <?php include 'oportunidades-inscritos-tabela-produtor.php'; ?>
    <?php elseif(count($inscritos)): ?>
        <div class="artists clearfix" style='height:410px;'>
            <?php include 'ajax-oportunidades-inscritos.php'; ?>
    	</div>
    <?php endif; ?>
            
</div>
