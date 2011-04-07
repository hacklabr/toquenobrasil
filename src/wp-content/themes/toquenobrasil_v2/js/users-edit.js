jQuery(document).ready(function() {
     
    jQuery('.apagar-media').click(function() {
        
        if (confirm('Tem certeza que deseja excluir este item?')) {
            return true;
        } else {
            return false;
        }
        
    });
    
    
    jQuery('.ordenar').sortable({
        stop: function() {
            jQuery('#order_input').val(jQuery('.ordenar').sortable('toArray').join(","));
        }
    });
    
    jQuery('.cancelar-ordenacao').click(function() {
        jQuery('.ordenar-midias').hide();
        jQuery('.lista-midias').show();
        jQuery('section.profile form:first').show();
    });
    
    jQuery('.comecar-ordenacao').click(function() {
        jQuery('.ordenar-midias').show();
        jQuery('.lista-midias').hide();
        jQuery('section.profile form:first').hide();
    });
    
    
    // ============= CAMPO ORIGEM DE BANDAS ===================
     // muda o campo estado e cidade entre select e input, dependendo se o país é brasil ou não
     jQuery('#evento_pais').change(function(){
        if(jQuery('#evento_pais').val() == 'BR') {
            jQuery('#evento_estado_select').show();
            jQuery('#evento_estado_input').hide();

            jQuery('#evento_cidade_select').show();
            jQuery('#evento_cidade_input').hide();

            jQuery('#evento_estado').val(jQuery('#evento_estado_select').val());
            jQuery('#evento_estado').val(jQuery('#evento_estado_select').val());
        }else{
            jQuery('#evento_estado_select').hide();
            jQuery('#evento_estado_input').show();

            jQuery('#evento_cidade_select').hide();
            jQuery('#evento_cidade_input').show();

            jQuery('#evento_estado').val(jQuery('#evento_estado_input').val());
            jQuery('#evento_estado').val(jQuery('#evento_estado_input').val());
            jQuery('#evento_cidade').val(jQuery('#evento_cidade_input').val());
        }
     }).change();

     jQuery('#evento_estado_select').change(function(){
         jQuery('#evento_cidade_select').html('<option>carregando...</option>');
         jQuery('#evento_estado').val(jQuery('#evento_estado_select').val());
         tnbCarregaCidadesOptions('evento_cidade',jQuery('#evento_estado_select').val());
     });

     jQuery('#evento_estado_input').change(function(){
         jQuery('#evento_estado').val(jQuery('#evento_estado_input').val());
     });

     jQuery('#evento_cidade_select').change(function(){
         jQuery('#evento_cidade').val(jQuery('#evento_cidade_select').val());
     });

     jQuery('#evento_cidade_input').change(function(){
         jQuery('#evento_cidade').val(jQuery('#evento_cidade_input').val());
     });
     
     
     if(jQuery('#evento_pais').val() == 'BR'){
       tnbCarregaCidadesOptions('evento_cidade',jQuery('#evento_estado_select').val());
     }
    
 });
 
