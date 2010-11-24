 jQuery(document).ready(function() {
    
    jQuery('.categoria').click(function() {
        
        if (jQuery(this).val() == 'popular') {
            jQuery('#subcategorias_div').show();
        } else {
            jQuery('#subcategorias_div').hide();
            jQuery('.subcategorias').each(function() {
                jQuery(this).attr('checked', false);
            });
        }
        
        
    });
    
    jQuery('.categoria:checked').trigger('click');
    
    
});
