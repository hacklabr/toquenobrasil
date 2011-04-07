jQuery(document).ready(function() {
    
	// não está funcionando as funções addClass e removeClass, por isso uso o css
	jQuery("div.select").click(function(){
        if (!jQuery(this).data('visible')) {
            jQuery("#periodo_selecionado").css({color:'white'});
    		jQuery("div.select ul").show();
            jQuery(this).data('visible', true);
        }
		
	});
	
	
    // jQuery("div.select li").hover(function(){
    //         this.oldColor = jQuery(this).css('background-color');
    //  
    //  jQuery(this).css({backgroundColor: '#eee'});
    // },function(){
    //  jQuery(this).css({backgroundColor: this.oldColor});
    // });
	
	jQuery("div.select li").click(function(){
        // jQuery("div.select li").css({backgroundColor: ''});
        // jQuery(this).css({backgroundColor: '#ddd'});
        // this.oldColor = '#ddd';
		
		jQuery("#acontece").val(jQuery(this).attr('id'));
		
		switch(jQuery(this).attr('id')){
			case 'este_mes':
				jQuery("#periodo_selecionado").html("Este mês");
				
				jQuery("#acontece_de").val('01'+'/'+vars['month']+'/'+vars['year']);
				jQuery("#acontece_ate").val(vars['last_day_this_month']+'/'+vars['month']+'/'+vars['year']);
				
				jQuery("#periodo_selecionado").css({color:''});
				jQuery("div.select ul").hide();
                jQuery("div.select").data('visible', false);
                return false;
			break;
			case 'proximo_mes':
				jQuery("#periodo_selecionado").html("Mês que vem");
				
				var year, month;
				if(vars['month'] < 12){
					month = parseInt(vars['month']) + 1;
					year = parseInt(vars['year']);
				}else{
					month = '01';
					year = parseInt(vars['year'])+1;
					
				}
				
				jQuery("#acontece_de").val('01'+'/'+month+'/'+year);
				jQuery("#acontece_ate").val(vars['last_day_next_month']+'/'+month+'/'+year);
				
				jQuery("#periodo_selecionado").css({color:''});
				jQuery("div.select ul").hide();
                jQuery("div.select").data('visible', false);
                return false;
                
			break;
            case 'nao_importa':
				jQuery("#periodo_selecionado").html("Não importa quando");
                jQuery("#periodo_selecionado").css({color:''});
				jQuery("div.select ul").hide();
                jQuery("div.select").data('visible', false);
                return false;
			break;
			case 'periodo':
				jQuery("#periodo_selecionado").html("de "+jQuery("#acontece_de").val()+" à "+" " +jQuery("#acontece_ate").val());
				jQuery("#acontece_de").show();
				jQuery("#acontece_ate").show();
			break;
		}
		
		
	});
   
   jQuery('#ok_datas_search').click(function() {
	    jQuery("#periodo_selecionado").html("de "+jQuery("#acontece_de").val()+" à "+" " +jQuery("#acontece_ate").val());
        jQuery("#periodo_selecionado").css({color:''});
        jQuery("div.select ul").hide();
        jQuery("div.select").data('visible', false);
        return false;
    });
	
	jQuery("div.select ul").hide();
	
	// se o formulário não foin enviado
	if(!vars['tnb_action']){
		jQuery("#acontece_de").val('01'+'/'+vars['month']+'/'+vars['year']);
		jQuery("#acontece_ate").val(vars['last_day_this_month']+'/'+vars['month']+'/'+vars['year']);
	}
	jQuery("#acontece_de").show();
	jQuery("#acontece_ate").show();
	
	jQuery("#acontece_de").keydown(function (){
		return false;
	});
	
	jQuery("#acontece_ate").keydown(function (){
		return false;
	});
	
	jQuery("#acontece_de").datepicker();
	jQuery("#acontece_ate").datepicker();
	jQuery(".ui-datepicker").css({'z-index': 99999});
});

