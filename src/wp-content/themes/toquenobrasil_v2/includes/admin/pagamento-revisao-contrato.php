<?php

if(isset($_GET['evento_id']) && ($evento = get_post($_GET['evento_id'])) && $evento->post_status == 'pay_pending_review'): 
	$cdata = get_contrato_inscricao($evento->ID);
	$valor = $cdata['valor'];
	$porcentagem = $cdata['porcentagem'];
	$contrato = $cdata['contrato'];
	
	$total_tnb = (is_numeric($valor) && is_numeric($porcentagem)) ? $valor * $porcentagem / 100 : 0;
	$total_tnb = number_format($total_tnb, 2);
	
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
	var pos = jQuery('#contrato').getCursorPosition();
	var bp_text = jQuery('#contrato').val().slice(0,pos);
	var ap_text = jQuery('#contrato').val().slice(pos,jQuery('#contrato').val().length);

	jQuery('#contrato').val(bp_text+substituicao+ap_text);
	jQuery('#substituicoes-lista').hide();
}
</script>


<h4><?php echo $evento->display_name?></h4>


<form id='form_pagamento_contrato' method="post">
	<input type='hidden' id='pagamento_action' name='pagamento_action' value=''/>
	<input type='hidden' name='evento_id' value='<?php echo $_GET['evento_id']?>'/>
	<label>valor da inscrição: R$<input type='text' id='contrato_valor' name='valor' value='<?php echo $valor ?>'  style='width:70px'/></label> 
	<label>porcentagem para o TNB: <input type='text' id='contrato_porcentagem' name='porcentagem' value='<?php echo $porcentagem ?>' style='width:50px'/>%</label> 
	= <strong id='total_tnb'>R$ <?php echo $total_tnb; ?></strong>
	<br/>
	
	
	<div id='substituicoes-lista-div'>
		<div id='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
		<?php foreach (get_contrato_inscricao_substituicoes() as $substituicao => $descricao):?>
			<div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
		<?php endforeach;?>
		</div>
		<strong >substituições</strong>
		
	</div>
	<label>contrato:
	<textarea style='width:99%; height:450px' id='contrato' name='contrato'><?php echo htmlentities(utf8_decode($contrato));?></textarea>
	</label>
	<div style='margin:10px 20px; text-align: right;'>
		<input type='button' id='botao_visualizar' value='visualizar contrato' />
		<input type="button" id='botao_finalizar' value='finalizar e enviar para o produtor' />
		<input type="button" id='botao_salvar' value='salvar rascunho' />
	</div>
</form>

<div id='contrato-preview' style='display:none; background-color:white; top:0px; left:0px; position:absolute; width:90%; height:90%; z-index:1000; margin:5%; padding:5px; border:1px solid gray; overflow:auto; '>
	<button id='contrato-preview-fechar' class='alignright'>fechar</button>
	<h4>pré-visualização do contrato</h4>
	<hr>
	<div id='contrato-preview-content' style="padding:15px;"></div>
</div>

<?php else: ?> 
	<h4>contrato não encontrado</h4> 
<?php endif; ?>

<script type="text/javascript">
jQuery('#botao_finalizar').click(function(){
	jQuery('#pagamento_action').val('finalizar_contrato_inscricao');
	jQuery('#form_pagamento_contrato').submit();
});

jQuery('#botao_salvar').click(function(){
	jQuery('#pagamento_action').val('salvar_contrato_inscricao');
	jQuery('#form_pagamento_contrato').submit();
});


jQuery('#botao_visualizar').click(function(){
	jQuery.post('<?php echo bloginfo('stylesheet_directory')?>/includes/admin/pagamento-contrato-preview.php',
				jQuery('#form_pagamento_contrato').serialize(),function (data,status){
					jQuery('#contrato-preview').show();
					jQuery('#contrato-preview-content').html(data);
				});
});

jQuery('#contrato-preview-fechar').click(function() {
	jQuery('#contrato-preview').hide();
});

function calculaContratoValor (){
	if(parseFloat(jQuery('#contrato_valor').val())  && parseFloat(jQuery('#contrato_porcentagem').val())){
		var tot = parseFloat(jQuery('#contrato_valor').val()) * parseFloat(jQuery('#contrato_porcentagem').val()) / 100;
		
		jQuery('#total_tnb').html('R$ '+tot.toFixed(2));
	}else{
		
		jQuery('#total_tnb').html('R$ 0.00');
	}
}

jQuery('#contrato_valor').keyup(calculaContratoValor);
jQuery('#contrato_porcentagem').keyup(calculaContratoValor);



jQuery('#substituicoes-lista-div strong').mouseenter(function(){
	jQuery('#substituicoes-lista').show();
});

jQuery('#substituicoes-lista-div').mouseleave(function(){
	jQuery('#substituicoes-lista').hide();
});
</script>