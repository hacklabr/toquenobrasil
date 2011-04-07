<?php
global $oportunidade_item, $user;

$selecionados = get_post_meta( $oportunidade_item->ID, 'selecionado') ;
$num_selecionados = count($selecionados);


?>

<div class="selected-artists clearfix" >
    <?php if(count($selecionados)):?>
        <h2 class="title">
            <?php _e("Artistas Selecionados", "tnb"); ?>    
            <?php
                if(current_user_can('select_artists') || current_user_can('select_other_artists')) : 
                echo "($num_selecionados)";
            endif; ?>
        </h2>
    <?php endif; ?>
    <?php if (current_user_can('edit_post', $oportunidade_item->ID)): ?>
        <p>
            <a class="button" href="<?php echo add_query_arg('exportar','selecionado');?>">Exportar planilha</a>
            <a class="button" onclick="jQuery('#selected-artists-mailbox').dialog('open');">Enviar email</a>
        </p>

        <div class="tnb_modal" id="selected-artists-mailbox">
            <h2><?php _e("Email para artistas selecionados");?></h2>

            <form method="post">
                <p><?php _e('Produtor, se você espera alguma resposta dos artistas, não esqueça de informar um canal de contato. Este email é enviado pelo sistema e não pode ser respondido', 'tnb'); ?></p>
                <input type="hidden" name="action" value="mail_selected_artists"/>
                <input type="hidden" name="post_id" value="<?php echo $oportunidade_item->ID;?>"/>
                <p>
                    <label for="subject-for-selected" class="clearfix"><?php echo _e("Assunto");?></label>
                    <input type="text" id="subject-for-selected" name="subject"/>
                </p>

                <label for="message-for-selected" class="clearfix"><?php echo _e("Mensagem");?></label>
                <textarea id="message-for-selected" name="message"></textarea>
                <p class="text-right">
                    <input type="submit" class="button" value="<?php _e("Enviar");?>"</input>
                </p>
            </form>
        </div>
        <!-- .tnb_modal -->
        
        <?php if($_POST['action']=='mail_selected_artists' && isset($GLOBALS['tnb_errors'])):?>
        <div class="error">
            <ul>
            <?php foreach($GLOBALS['tnb_errors'] as $error): ?>
                <li><?php echo $error;?></li>
            <?php endforeach; unset($GLOBALS['tnb_errors']);?>
            </ul>
        </div>
        <?php elseif($_GET['message'] === 'sentforselected'): ?>
            <div class="message-sent"><?php _e('Mensagem enviada.');?></div>
        <?php endif;?>

    <?php endif; ?>
    <?php if(count($selecionados)):?>
    <div class="artists clearfix">
        <?php include 'ajax-oportunidades-selecionados.php'; ?>
    </div>
    <?php endif;?>
</div>
