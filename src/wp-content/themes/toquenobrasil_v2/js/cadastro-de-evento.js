jQuery(document).ready(function(){
    var $ = jQuery;
    
    jQuery('#nova_oportunidade').click(function() {
        jQuery("#opportunity-form").show();
        $("select#evento_tipo").change();
        $("select#superevento").change();
        jQuery(".minhas-oportunidades").hide();
        jQuery(this).hide();
        return false;
    });
    
    jQuery('#cancelar_novo').click(function() {
        jQuery("#opportunity-form").hide();
        jQuery('#nova_oportunidade').show();
        jQuery('.minhas-oportunidades').show();
        return false;
    });
    
    $("#evento_pai select").change(function(){
        var id = parseInt($(this).val());

        if(id > 0) {
            $.post(
                obj.ajax_url,
                {'action':'get_superevento', 'superevento': id},
                function(data) {
                    
                    if(data.forcar_condicoes) {
                        $('#evento_condicoes_div').find('input,textarea').each(function() { jQuery(this).attr('disabled',true) });
                        $('#evento_condicoes_div').find('.forced').show();
                    } else {
                        $('#evento_condicoes_div').find('input,textarea').each(function() { jQuery(this).attr('disabled',false) });
                        $('#evento_condicoes_div').find('.forced').hide();
                    }
                    if(data.forcar_tos) {
                        $('textarea#evento_tos').attr('disabled',true);
                        $('#evento_tos_div').find('.forced').show();
                    } else {
                        $('textarea#evento_tos').attr('disabled',false);
                        $('#evento_tos_div').find('.forced').hide();
                    }
                    if(data.forcar_restricoes) {
                        $('textarea#evento_restricoes').attr('disabled',true).val(data.evento_restricoes);
                        $('#evento_restricoes_div').find('.forced').show();
                    } else {
                        $('textarea#evento_restricoes').attr('disabled',false);
                        $('#evento_restricoes_div').find('.forced').hide();
                    }
                },
                'json'
            );
        } else {
            $('#evento_condicoes_div').find('input,textarea').each(function() { jQuery(this).attr('disabled',false) });
            $('#evento_condicoes_div').find('.forced').hide();
            $('textarea#evento_tos').attr('disabled',false);
            $('#evento_tos_div').find('.forced').hide();
            $('textarea#evento_restricoes').attr('disabled',false);
            $('#evento_restricoes_div').find('.forced').hide();
        }
    });

    $("#opportunity-form").submit(function(){
        if($("#tnb_modal_cadastro_evento") && $("#termo_para_novo_evento").val() == '0') {
            $("#tnb_modal_cadastro_evento a#aceito_termo_para_novo_evento").click(function() {
                $("#termo_para_novo_evento").val('1');
                $("#opportunity-form").submit();
            });

            $("#tnb_modal_cadastro_evento").dialog('open');
            return false;
        }
    });

    $("select#evento_tipo").change(function(){
        
        if($("select#evento_tipo").val() == 'Outro') {
            $('input[name=evento_tipo]').val($('input#evento_tipo_original').val());
            $("#outro_evento_tipo").show('fast');
        } else {
            $("#outro_evento_tipo").hide();
            $('input[name=evento_tipo]').val($("select#evento_tipo").val());
        }
    }).keyup(function(){$(this).change();}).change();

    $("select#superevento").change(function(){
        if($(this).find("option:selected").val() == "yes"){
            $("#evento_pai").hide('fast', function(){ 
                    $("#produtores_selecionam").show();
                    $("div .forcar").show();

                    $("div.evento_tipo").show();
                    $("select#evento_tipo").change();
                });
        } else {
            
            $("#produtores_selecionam").hide('fast', function(){
                $("#evento_pai").show();
                $('select[name=post_parent]').change();
                
            });
            
            $("div .forcar").hide('fast');
        }
    }).keyup(function(){
        $(this).change();
    }).change();

    $('select[name=post_parent]').change(function(){
        if($(this).val() == "0" && $("select#superevento").val() == "no") {
            $("div.evento_tipo").show();
            $("select#evento_tipo").change();
        } else {
            $("div.evento_tipo").hide();
            $("#outro_evento_tipo").hide();
        }
    }).keyup(function(){
        $(this).change();
    }).change();

    $('input#evento_site').change(function(){
        if($(this).val() != '' && !$(this).val().match(/^http:\/\//)) {
            $(this).val('http://'+$(this).val());
        }
    });
});
