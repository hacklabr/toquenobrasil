jQuery(document).ready(function(){
    var $ = jQuery;

    $("p#evento_pai select").change(function(){
        var id = parseInt($(this).val());

        if(id > 0) {
            $.post(
                obj.ajax_url,
                {'action':'get_superevento', 'superevento': id},
                function(data) {
                    if(data.forcar_condicoes) {
                        $('textarea#evento_condicoes').attr('disabled',true).val(data.evento_condicoes);
                    }
                    if(data.forcar_tos) {
                        $('textarea#evento_tos').attr('disabled',true).val(data.evento_tos);
                    }
                    if(data.forcar_restricoes) {
                        $('textarea#evento_restricoes').attr('disabled',true).val(data.evento_restricoes);
                    }
                },
                'json'
            );
        }
    });

    $("#cadastro-de-evento").submit(function(){
        if($("#tnb_modal_cadastro_evento") && $("#termo_para_novo_evento").val() == '0') {
            $("#tnb_modal_cadastro_evento a#aceito_termo_para_novo_evento").click(function() {
                $("#termo_para_novo_evento").val('1');
                $("#cadastro-de-evento").submit();
            });

            $("#tnb_modal_cadastro_evento").dialog('open');
            return false;
        }
    });

    $("select#evento_tipo").change(function(){
        if($("select#evento_tipo").val() == 'Outro') {
            $('input[name=evento_tipo]').val($('input#evento_tipo_original').val());
            $("p#outro_evento_tipo").show('fast');
        } else {
            $("p#outro_evento_tipo").hide();
            $('input[name=evento_tipo]').val($("select#evento_tipo").val());
        }
    }).keyup(function(){$(this).change();}).change();
});
