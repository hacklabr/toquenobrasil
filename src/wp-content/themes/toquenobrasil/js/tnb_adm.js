
jQuery(document).ready(function() {
	jQuery("input.calendar").datepicker({clickInput:true,firstDay:1});
});

function tnbCarregaCidadesOptions(campoId, userId, uf){
	  var selected = jQuery('#'+campoId+'_'+userId).val();
	  selected = encodeURI(selected);
	  jQuery('#'+campoId+'_select_'+userId).load(params.base_url+'/cidades-options.php?uf='+uf+'&selected='+selected,function(result){jQuery('#'+campoId+'_select_'+userId).html(result)});
	}
