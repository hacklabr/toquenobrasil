<?php
if(isset($_POST['action']) and $_POST['action'] == 'save-pagamento-modelo-contrato'){
	update_option('evento-pagamento-modelo-contrato', $_POST['modelo']);
	echo 'modelo salvo!';
}
$modelo = get_option('evento-pagamento-modelo-contrato', '');
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
	var pos = jQuery('#modelo').getCursorPosition();
	var bp_text = jQuery('#modelo').val().slice(0,pos);
	var ap_text = jQuery('#modelo').val().slice(pos,jQuery('#modelo').val().length);

	jQuery('#modelo').val(bp_text+substituicao+ap_text);
	jQuery('#substituicoes-lista').hide();
}
</script>


<h4>modelo de contrato</h4>
<div id='substituicoes-lista-div'>
	<div id='substituicoes-lista' style="display:none; position:absolute; background-color:white; padding:4px; width:700px; border:2px solid #aaa">
	<?php foreach (get_contrato_inscricao_substituicoes() as $substituicao => $descricao):?>
		<div style='margin-bottom:3px;'><a href='javascript:add_substituicao_to_textarea("{<?php echo $substituicao; ?>}")' style='float:left; min-width:200px; font-weight:bold;'>{<?php echo $substituicao; ?>}</a> -<?php echo $descricao?></div>
	<?php endforeach;?>
	</div>
	<strong >substituições</strong>
	
</div>
<form method="post">
	<input type='hidden' name='action' value='save-pagamento-modelo-contrato' />
	<textarea style='width:99%; height:450px' id='modelo' name='modelo' ><?php echo htmlentities(utf8_decode($modelo))?></textarea>
	<input type="submit" class='alignright' value="salvar">
</form>
<script type="text/javascript">
jQuery('#substituicoes-lista-div strong').mouseenter(function(){
	jQuery('#substituicoes-lista').show();
});

jQuery('#substituicoes-lista-div').mouseleave(function(){
	jQuery('#substituicoes-lista').hide();
});
</script>
