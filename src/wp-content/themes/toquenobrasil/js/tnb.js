var timer = 0;

function remove_feedback_msg(){
	clearTimeout(timer );
	jQuery('.success:not(.stay), .notice:not(.stay)').slideUp('slow',function(){
		//jQuery(this).remove();
	});
	
}

jQuery(document).ready(function() {

	if(jQuery('.success:not(.stay), .notice:not(.stay)')){
		timer = setTimeout('remove_feedback_msg()',6000); 
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
    /////////////////////////////////////  PROFILE SUBMIT    
    jQuery('.profile-update-submit').click(function(){
    	jQuery('#profile-loading-msg').show().insertAfter(jQuery(this).parent());
    	jQuery('.profile-update-submit').parent().hide();
    });
    
    /////////////////////////////////////  PROFILE DELETE MEDIAS    
    
    jQuery('.delete_profile_media').click(function(){
    	
    	if(jQuery(this).attr('checked')==true){
    		jQuery(this).parent().find('input[type!=checkbox]').attr('disabled', 'true');
    	}else{
    		jQuery(this).parent().find('input[type!=checkbox]').attr('disabled', '');
    	}
    	
    });
    
    /////////////////////////////////////  INPUT MASKS 
    
    
    jQuery('#user_login, .user_login').keyup(function(){
    	var val = jQuery(this).val();
    	
    	jQuery(this).val(val.replace(/[^0-9a-z_-]*/gi, ''));
    });
    
    jQuery('#site').blur(function(){
    	jQuery(this).triger('keyup');
    });
    
    jQuery('.tnb_modal').dialog({
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


function tnbCarregaCidadesOptions(campoId, uf){
	  var selected = jQuery('#'+campoId).val();
	  selected = encodeURI(selected);
	  jQuery('#'+campoId+'_select').load(params.base_url+'/cidades-options.php?uf='+uf+'&selected='+selected,function(result){jQuery('#'+campoId+'_select').html(result)});
	}
