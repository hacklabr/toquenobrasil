var timer = 0;

function remove_feedback_msg(){
	clearTimeout(timer );
	jQuery('.success:not(.stay), .notice:not(.stay)').slideUp('slow',function(){
		//jQuery(this).remove();
	});
	
}

jQuery(document).ready(function() {

	if(jQuery('.success:not(.stay), .notice:not(.stay)')){
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
	
	// ////////////////////////////// Login handler //////////////////////////////
    jQuery("#signin_btn").click(function(){
    	
    	if(jQuery("#signinform #senha").val() == '' || jQuery("#signinform #user_login").val() == '')
    		return false;
    	
    	return true;
    	
    });
    
    jQuery("#lostpassform_submit").click(function(){
    	
    	if(jQuery("#lostpassform #user_login").val() == '')
    		return false;
    	
    	return true;
    	
    });
    
    
    /////////////////////////////////////  INPUT MASKS 
    
    
    jQuery('#user_login, .user_login').keyup(function(){
    	var val = jQuery(this).val();
    	
    	jQuery(this).val(val.replace(/[^0-9a-z_-]*/gi, ''));
    });
    
    
    jQuery('#site').keyup(function(event){
    	var val = jQuery(this).val();
    	// backspace and DELETE
    	if(event.keyCode == 8 || event.keyCode == 46 ){
    		if(!val.match(/^(https?:\/\/)/i)){
    			jQuery(this).val("");
    		}
    	}else{
//    		alert(event.keyCode);
	    	if(!val.match(/^(https?:\/\/)/i) && event.keyCode != 72 /*key:h*/ && event.keyCode != 84 /*key:t*/ && event.keyCode != 80 /*key:p*/ && event.keyCode != 191 /*key:/*/ && event.keyCode != 16 /*key:':'*/&& event.keyCode != 59 /*key:':'*/  ){
	    		jQuery(this).val("http://" + jQuery(this).val());
	    	}
    	}
    });
    jQuery('#site').blur(function(){
    	jQuery(this).triger('keyup');
    });
    
    jQuery('.evento_tos_modal').dialog({
        autoOpen: false,
        modal: true,
        width: 700,
        resizable: false
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
	
    /* =COLORIZE WIDGETS */
    var i = 1;
    jQuery(".widgets .widget").each(function() {
         if (i==1) {
             jQuery(this).addClass("widget-yellow");
         } else if (i==2) {
             jQuery(this).addClass("widget-green");
         } else if (i==3) {
             jQuery(this).addClass("widget-blue");
             i = 0;
         }
         i++;
    })

    /* =ADD CLASS TO IMAGES OF THE WIDGETS */
    jQuery(".widgets img").each(function() {
        jQuery(this).parent().addClass("widget-image");
    })

    /* =REMOVE UNDERLINE FROM IMAGES WITH LINKS */
    jQuery("img").parent("a").each(function() {
        jQuery(this).css("border","none");
    })

    /* =TOGGLE RESTRICTIONS AND CONDITIONS OF EVENTS */
    jQuery(".restrictions h3").toggle(
        function() {
            jQuery(this).next().slideDown();
        },
        function() {
            jQuery(this).next().slideUp();
        }
    )

    jQuery(".conditions h3").toggle(
        function() {
            jQuery(this).next().slideDown();
        },
        function() {
            jQuery(this).next().slideUp();
        }
    )

});
