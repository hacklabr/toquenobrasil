jQuery(document).ready(function() {
     // ============= CAMPO ORIGEM DE BANDAS ===================
     // muda o campo estado e cidade entre select e input, dependendo se o país é brasil ou não
     jQuery('#origem_pais').change(function(){
        if(jQuery('#origem_pais').val() == 'BR') {
            jQuery('#origem_estado_select').show();
            jQuery('#origem_estado_input').hide();

            jQuery('#origem_cidade_select').show();
            jQuery('#origem_cidade_input').hide();

            jQuery('#origem_estado').val(jQuery('#origem_estado_select').val());
            jQuery('#origem_estado').val(jQuery('#origem_estado_select').val());
        }else{
            jQuery('#origem_estado_select').hide();
            jQuery('#origem_estado_input').show();

            jQuery('#origem_cidade_select').hide();
            jQuery('#origem_cidade_input').show();

            jQuery('#origem_estado').val(jQuery('#origem_estado_input').val());
            jQuery('#origem_estado').val(jQuery('#origem_estado_input').val());
            jQuery('#origem_cidade').val(jQuery('#origem_cidade_input').val());
        }
     });

     jQuery('#origem_estado_select').change(function(){
         jQuery('#origem_cidade_select').html('<option>carregando...</option>');
         jQuery('#origem_estado').val(jQuery('#origem_estado_select').val());
         tnbCarregaCidadesOptions('origem_cidade',jQuery('#origem_estado_select').val());
     });

     jQuery('#origem_estado_input').change(function(){
         jQuery('#origem_estado').val(jQuery('#origem_estado_input').val());
     });

     jQuery('#origem_cidade_select').change(function(){
         jQuery('#origem_cidade').val(jQuery('#origem_cidade_select').val());
     });

     jQuery('#origem_cidade_input').change(function(){
         jQuery('#origem_cidade').val(jQuery('#origem_cidade_input').val());
     });
     
     
     if(jQuery('#origem_pais').val() == 'BR'){
       tnbCarregaCidadesOptions('origem_cidade',jQuery('#origem_estado_select').val());
     }
     

     // ============= CAMPO RESIDENCIA BANDAS ===================
     // muda o campo estado e cidade entre select e input, dependendo se o país é brasil ou não
     jQuery('#banda_pais').change(function(){
        if(jQuery('#banda_pais').val() == 'BR') {
            jQuery('#banda_estado_select').show();
            jQuery('#banda_estado_input').hide();

            jQuery('#banda_cidade_select').show();
            jQuery('#banda_cidade_input').hide();

            jQuery('#banda_estado').val(jQuery('#banda_estado_select').val());
            jQuery('#banda_estado').val(jQuery('#banda_estado_select').val());
        }else{
            jQuery('#banda_estado_select').hide();
            jQuery('#banda_estado_input').show();

            jQuery('#banda_cidade_select').hide();
            jQuery('#banda_cidade_input').show();

            jQuery('#banda_estado').val(jQuery('#banda_estado_input').val());
            jQuery('#banda_estado').val(jQuery('#banda_estado_input').val());
            jQuery('#banda_cidade').val(jQuery('#banda_cidade_input').val());
        }
     });

     jQuery('#banda_estado_select').change(function(){
         jQuery('#banda_cidade_select').html('<option>carregando...</option>');
         jQuery('#banda_estado').val(jQuery('#banda_estado_select').val());
         tnbCarregaCidadesOptions('banda_cidade',jQuery('#banda_estado_select').val());
     });

     jQuery('#banda_estado_input').change(function(){
         jQuery('#banda_estado').val(jQuery('#banda_estado_input').val());
     });

     jQuery('#banda_cidade_select').change(function(){
         jQuery('#banda_cidade').val(jQuery('#banda_cidade_select').val());
     });

     jQuery('#banda_cidade_input').change(function(){
         jQuery('#banda_cidade').val(jQuery('#banda_cidade_input').val());
     });


     if(jQuery('#banda_pais').val() == 'BR'){
       tnbCarregaCidadesOptions('banda_cidade',jQuery('#banda_estado_select').val());
     }



     // ============= CAMPO ORIGEM PRODUTOR ===================
     // muda o campo estado e cidade entre select e input, dependendo se o país é brasil ou não
     jQuery('#produtor_pais').change(function(){
        if(jQuery('#produtor_pais').val() == 'BR') {
            jQuery('#produtor_estado_select').show();
            jQuery('#produtor_estado_input').hide();

            jQuery('#produtor_cidade_select').show();
            jQuery('#produtor_cidade_input').hide();

            jQuery('#produtor_estado').val(jQuery('#produtor_estado_select').val());
            jQuery('#produtor_estado').val(jQuery('#produtor_estado_select').val());
        }else{
            jQuery('#produtor_estado_select').hide();
            jQuery('#produtor_estado_input').show();

            jQuery('#produtor_cidade_select').hide();
            jQuery('#produtor_cidade_input').show();

            jQuery('#produtor_estado').val(jQuery('#produtor_estado_input').val());
            jQuery('#produtor_estado').val(jQuery('#produtor_estado_input').val());
            jQuery('#produtor_cidade').val(jQuery('#produtor_cidade_input').val());
        }
     });

     jQuery('#produtor_estado_select').change(function(){
         jQuery('#produtor_cidade_select').html('<option>carregando...</option>');
         jQuery('#produtor_estado').val(jQuery('#produtor_estado_select').val());
         tnbCarregaCidadesOptions('produtor_cidade',jQuery('#produtor_estado_select').val());
     });

     jQuery('#produtor_estado_input').change(function(){
         jQuery('#produtor_estado').val(jQuery('#produtor_estado_input').val());
     });

     jQuery('#produtor_cidade_select').change(function(){
         jQuery('#produtor_cidade').val(jQuery('#produtor_cidade_select').val());
     });

     jQuery('#produtor_cidade_input').change(function(){
         jQuery('#produtor_cidade').val(jQuery('#produtor_cidade_input').val());
     });


     if(jQuery('#produtor_pais').val() == 'BR'){
       //alert('teste');
       tnbCarregaCidadesOptions('produtor_cidade',jQuery('#produtor_estado_select').val());
     }

 });
 