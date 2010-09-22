var timer = 0;

function remove_feedback_msg(){
	clearTimeout(timer );
	jQuery('.success, .error, .notice').slideUp('fast',function(){
		//jQuery(this).remove();
	});
	
}

jQuery(document).ready(function() {

	if(jQuery('.success, .error, .notice')){
		timer = setTimeout('remove_feedback_msg()',3000); 
	} 
	
	// Remove default input values of forms
    jQuery(".auto_clean").each(function() {
    	
    	jQuery(this).focus(function() {
            if(jQuery(this).val() == jQuery(this).attr('title')){
            	jQuery(this).val('');
            }
        }).blur(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).val(jQuery(this).attr('title'));
            };
        });
    });
	
	// ////////////////////////////// LOST PASS //////////////////////////////
	jQuery("#lostpassform").hide();
	jQuery('#lost-pass').click(function(){
		jQuery('#signinform').hide();
		jQuery('#lostpassform').show();
	});
	
	jQuery('#cancel-lost-pass').click(function(){
		jQuery('#signinform').show();
		jQuery('#lostpassform').hide();
	});
	
    Cufon.replace('h1, h2, h3, h4, h5, h6, #footer #twit .content .author, #nav-bottom, #hacklab, .post .post-comments span, #anterior, #proximo, .signup, .quero-tocar');
	
});