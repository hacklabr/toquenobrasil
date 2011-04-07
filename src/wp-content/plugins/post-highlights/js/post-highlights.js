
// When document is ready
jQuery(document).ready(function(){
	
	//hide the "hide" buttons
	jQuery(".ph_hide_settings").css('cursor','pointer').hide();
	
	//when you click on the check box or in the edit button
	jQuery(".ph_dialog_button").css('cursor','pointer').click(function(){
	
			// get the post id
			var post_id = jQuery(this).attr("id");	
			post_id = post_id.replace("ph_","");
			post_id = post_id.replace("phEdit_","");
			
			//if youve just checked the checkbox
			if(document.getElementById("ph_"+post_id).checked){
				
				//Hide and show the appropriate buttons
				jQuery("#phEdit_"+post_id).hide();
				jQuery("#ph_hide_settings_"+post_id).show().click(function(){
					jQuery("#ph_settings_"+post_id).hide();
					jQuery(this).hide();
					jQuery("#phEdit_"+post_id).show();
					jQuery(this).parents('tr').removeClass("ph_settings_header");
				});
				

				if(document.getElementById('ph_settings_'+post_id)){
					jQuery('#ph_settings_'+post_id).show();
				}else{
					// insert the tr with a loading message
					var numberOfCols = jQuery(this).parents('tr').children().size();
					jQuery(this).parents('tr').after('<tr class="ph_settings_tr"><td id="ph_settings_'+post_id+'" colspan="' + numberOfCols + '">' + ph.loadingMessage + ' ...</td></tr>').addClass("ph_settings_header");
					
					//loads the settings for the highlight
					jQuery('#ph_settings_'+post_id).load(ph.baseurl + '/ajax/ph_settings.php','id='+post_id+'&baseurl='+ph.baseurl);
				}								
				//save the post meta data 
				jQuery.post(ph.baseurl + '/ajax/ph_save.php',{action: "highlight", id: post_id});				
			}else{
				jQuery('#ph_settings_'+post_id).hide();
				jQuery("#phEdit_"+post_id).hide();
				jQuery("#ph_hide_settings_"+post_id).hide();
				jQuery.post(ph.baseurl + '/ajax/ph_save.php',{action: "unhighlight", id: post_id});
				jQuery(this).parents('tr').removeClass("ph_settings_header");		
			}				
	})	
});
