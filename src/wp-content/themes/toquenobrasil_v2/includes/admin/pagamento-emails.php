<?php
if(isset($_POST['action']) and $_POST['action'] == 'save-pagamento-modelo-contrato'){
	update_option('evento-pagamento-modelo-contrato', $_POST['modelo']);
	echo 'modelo salvo!';
}
$pagamentos_emails = get_option('emails_pagamento');
?>
<script type="text/javascript">
new function($) {
	$.fn.getCursorPosition = function() {
	    var pos = 0;
	    var el = $(this).get(0);
	    // IE Support
	    if (document.selection) {
	        el.focus();
	        var Sel = document.selection.createRange();
	        var SelLength = document.selection.createRange().text.length;
	        Sel.moveStart('character', -el.value.length);
	        pos = Sel.text.length - SelLength;
	    }
	    // Firefox support
	    else if (el.selectionStart || el.selectionStart == '0')
	        pos = el.selectionStart;

	    return pos;
	}
} (jQuery);	

function add_substituicao_to_textarea(substituicao){
    var input = jQuery(document).data('focused');
	var pos = input.getCursorPosition();
	var bp_text = input.val().slice(0,pos);
	var ap_text = input.val().slice(pos,input.val().length);

	input.val(bp_text+substituicao+ap_text);
	jQuery('.substituicoes-lista').hide();
}
</script>

<form method="post">
	<input type='hidden' name='pagamento_action' value='save-emails-pagamento' />
	
	email do editor: <input name='pagamentos_emails[email_editor]' class="para-substituir" style="width:400px" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['email_editor']))); ?>"><br />
    <div class='email-tipo'>
        <h4><strong>produtor</strong> cadastrou evento com cobrança</h4>
        <div class='substituicoes-lista-div'>
            <strong>substituições</strong>
            <div class='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
            <?php foreach (get_contrato_inscricao_substituicoes() as $substituicao => $descricao):?>
                <div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
            <?php endforeach;?>
            </div>
            
        </div>
        <table width="98%">
            <tr>
                <td valign="top" width="49%">
                    Email para o editor (admin):<br />
                    <input name='pagamentos_emails[produtor_cadastrou_evento_cobranca][editor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_cadastrou_evento_cobranca']['editor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[produtor_cadastrou_evento_cobranca][editor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_cadastrou_evento_cobranca']['editor']['message']))); ?></textarea>
                </td>
                <td valign="top" width="49%">
                    Email para o produtor: <br />
                    <input name='pagamentos_emails[produtor_cadastrou_evento_cobranca][produtor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_cadastrou_evento_cobranca']['produtor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[produtor_cadastrou_evento_cobranca][produtor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_cadastrou_evento_cobranca']['produtor']['message']))); ?></textarea>
                </td>
                
            </tr>
        </table>
    </div>
    
    <div class='email-tipo'>
        <h4><strong>editor</strong> revisou contrato de evento com cobrança</h4>
        <div class='substituicoes-lista-div'>
            <strong>substituições</strong>
            <div class='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
            <?php foreach (get_contrato_inscricao_substituicoes() as $substituicao => $descricao):?>
                <div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
            <?php endforeach;?>
            </div>
            
        </div>
        <table width="75%">
            <tr>
                
                <td valign="top">
                    Email para o produtor: <br />
                    <input name='pagamentos_emails[editor_revisou_evento_cobranca][produtor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['editor_revisou_evento_cobranca']['produtor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[editor_revisou_evento_cobranca][produtor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['editor_revisou_evento_cobranca']['produtor']['message']))); ?></textarea>
                </td>
                
            </tr>
        </table>
    </div>

    <div class='email-tipo'>
        <h4><strong>produtor</strong> aceitou contrato</h4>
        <div class='substituicoes-lista-div'>
            <strong>substituições</strong>
            <div class='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
            <?php foreach (get_contrato_inscricao_substituicoes() as $substituicao => $descricao):?>
                <div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
            <?php endforeach;?>
            </div>
            
        </div>
        <table width="98%">
            <tr>
                <td valign="top" width="49%">
                    Email para o editor (admin):<br />
                    <input name='pagamentos_emails[produtor_aceitou_contrato_inscricao][editor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_aceitou_contrato_inscricao']['editor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[produtor_aceitou_contrato_inscricao][editor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_aceitou_contrato_inscricao']['editor']['message']))); ?></textarea>
                </td>
                <td valign="top" width="49%">
                    Email para o produtor: <br />
                    <input name='pagamentos_emails[produtor_aceitou_contrato_inscricao][produtor][title]' class="para-substituir" style="width:95%"  value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_aceitou_contrato_inscricao']['produtor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[produtor_aceitou_contrato_inscricao][produtor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_aceitou_contrato_inscricao']['produtor']['message']))); ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    
    <div class='email-tipo'>
        <h4><strong>produtor</strong> não aceitou o contrato</h4>
        <div class='substituicoes-lista-div'>
            <strong>substituições</strong>
            <div class='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
            <?php foreach (get_contrato_inscricao_substituicoes() as $substituicao => $descricao):?>
                <div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
            <?php endforeach;?>
            </div>
            
        </div>
        <table width="75%">
            <tr>
                
                <td valign="top">
                    Email para o editor: <br />
                    <input name='pagamentos_emails[produtor_recusou_contrato_inscricao][editor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_recusou_contrato_inscricao']['editor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[produtor_recusou_contrato_inscricao][editor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['produtor_recusou_contrato_inscricao']['editor']['message']))); ?></textarea>
                </td>
                
            </tr>
        </table>
    </div>
    
	<div class='email-tipo'>
		<h4><strong>artista</strong> se inscreveu (inscrição pendente) em evento pago</h4>
		<div class='substituicoes-lista-div'>
            <strong>substituições</strong>
            <div class='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
            <?php foreach (get_contrato_inscricao_substituicoes(true) as $substituicao => $descricao):?>
                <div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
            <?php endforeach;?>
            </div>
            
        </div>
        <table width="99%">
            <tr>
                
                <td valign="top" width="33%">
                    Email para o editor: <br />
                    <input name='pagamentos_emails[artista_inscreveu_em_um_evento_pago][editor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscreveu_em_um_evento_pago']['editor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_inscreveu_em_um_evento_pago][editor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscreveu_em_um_evento_pago']['editor']['message']))); ?></textarea>
                </td>
                <td valign="top" width="33%">
                    Email para o produtor: <br />
                    <input name='pagamentos_emails[artista_inscreveu_em_um_evento_pago][produtor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscreveu_em_um_evento_pago']['produtor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_inscreveu_em_um_evento_pago][produtor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscreveu_em_um_evento_pago']['produtor']['message']))); ?></textarea>
                </td>
                <td valign="top" width="33%">
                    Email para o artista: <br />
                    <input name='pagamentos_emails[artista_inscreveu_em_um_evento_pago][artista][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscreveu_em_um_evento_pago']['artista']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_inscreveu_em_um_evento_pago][artista][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscreveu_em_um_evento_pago']['artista']['message']))); ?></textarea>
                </td>
                
            </tr>
        </table>
    </div>
    <div class='email-tipo'>
        <h4><strong>artista</strong> se desincreveu de evento pago em que estava pendente (não havia efetuado o pagamento)</h4>
        <div class='substituicoes-lista-div'>
            <strong>substituições</strong>
            <div class='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
            <?php foreach (get_contrato_inscricao_substituicoes(true) as $substituicao => $descricao):?>
                <div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
            <?php endforeach;?>
            </div>
            
        </div>
        <table width="98%">
            <tr>
                <td valign="top" width="49%">
                    Email para o editor (admin):<br />
                    <input name='pagamentos_emails[artista_desinscreveu_em_um_evento_em_que_estava_pendente][editor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_desinscreveu_em_um_evento_em_que_estava_pendente']['editor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_desinscreveu_em_um_evento_em_que_estava_pendente][editor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_desinscreveu_em_um_evento_em_que_estava_pendente']['editor']['message']))); ?></textarea>
                </td>
                <td valign="top" width="49%">
                    Email para o produtor: <br />
                    <input name='pagamentos_emails[artista_desinscreveu_em_um_evento_em_que_estava_pendente][produtor][title]' class="para-substituir" style="width:95%"  value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_desinscreveu_em_um_evento_em_que_estava_pendente']['produtor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_desinscreveu_em_um_evento_em_que_estava_pendente][produtor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_desinscreveu_em_um_evento_em_que_estava_pendente']['produtor']['message']))); ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    
    <div class='email-tipo'>
        <h4>confirmado o pagamento e inscrição de artista em evento pago.</h4>
        <div class='substituicoes-lista-div'>
            <strong>substituições</strong>
            <div class='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
            <?php foreach (get_contrato_inscricao_substituicoes(true) as $substituicao => $descricao):?>
                <div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
            <?php endforeach;?>
            </div>
            
        </div>
        <table width="99%">
            <tr>
                
                <td valign="top" width="33%">
                    Email para o editor: <br />
                    <input name='pagamentos_emails[artista_inscricao_confirmada_em_evento_pago][editor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscricao_confirmada_em_evento_pago']['editor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_inscricao_confirmada_em_evento_pago][editor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscricao_confirmada_em_evento_pago']['editor']['message']))); ?></textarea>
                </td>
                <td valign="top" width="33%">
                    Email para o produtor: <br />
                    <input name='pagamentos_emails[artista_inscricao_confirmada_em_evento_pago][produtor][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscricao_confirmada_em_evento_pago']['produtor']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_inscricao_confirmada_em_evento_pago][produtor][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscricao_confirmada_em_evento_pago']['produtor']['message']))); ?></textarea>
                </td>
                <td valign="top" width="33%">
                    Email para o artista: <br />
                    <input name='pagamentos_emails[artista_inscricao_confirmada_em_evento_pago][artista][title]' class="para-substituir" style="width:95%" value="<?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscricao_confirmada_em_evento_pago']['artista']['title']))); ?>"><br />
                    <textarea name='pagamentos_emails[artista_inscricao_confirmada_em_evento_pago][artista][message]' class="para-substituir" style='width:100%; height:200px' ><?php echo stripslashes(htmlentities(utf8_decode($pagamentos_emails['artista_inscricao_confirmada_em_evento_pago']['artista']['message']))); ?></textarea>
                </td>
                
            </tr>
        </table>
    </div>
    
	<input type="submit" name='salvar' value='salvar' />
</form>
<script type="text/javascript">
jQuery('.substituicoes-lista-div strong').mouseenter(function(){
	jQuery(this).next().show();
});

jQuery('.substituicoes-lista-div').mouseleave(function(){
	jQuery('.substituicoes-lista').hide();
});

jQuery('.para-substituir').focus(function(){
    jQuery(document).data('focused', jQuery(this));
});

</script>
