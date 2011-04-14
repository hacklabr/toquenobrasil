jQuery(document).ready(function() {


    //************* Abas do widget de oportunidades de artista *************////////
    
    //TODO: se tem mais de um widget desse no perfil, eles interferem um no outro
    
    jQuery('ul.widget_oportunidades_tabs li a').click(function() {
        
        jQuery(this).parents('ul').find('a').removeClass('current');
        jQuery(this).addClass('current');
        
    });
    
    // inicializa abas
    jQuery('ul.widget_oportunidades_tabs li a.inscrito').click();

	

        
});
