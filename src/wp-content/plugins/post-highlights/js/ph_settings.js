
function ph_update_message(id){

	jQuery("#ph_updated_"+id).fadeIn();
	window.setTimeout("ph_update_message_hide("+id+")",2000);	

}

function ph_update_message_hide(id){
	jQuery("#ph_updated_"+id).fadeOut();
}
	
	
jQuery(".ph_set_pic > img").click(function(){

	box_id = jQuery(this).parents('table').parents('td').attr("id");
	box_id = box_id.replace("ph_settings_","");
	
	jQuery(".ph_set_pic > img").each(function(){jQuery(this).removeClass("ph_img_selected")})
	jQuery(this).addClass("ph_img_selected");
	
	jQuery("#ph_picture_url_"+box_id).val("");
	
	//save the post meta data 
	jQuery.post(ph.baseurl + '/ajax/ph_save.php',{action: "picture_id", picture_id: jQuery(this).attr("alt"), id: box_id});
	ph_update_message(box_id);
	

});

jQuery(".ph_picture_url").blur(function(){

	box_id = jQuery(this).parents('table').parents('td').attr("id");
	box_id = box_id.replace("ph_settings_","");
	
	if (jQuery(this).val()!=""){ 
		jQuery("#pictures_"+box_id+" > img").each(function(){jQuery(this).removeClass("ph_img_selected")})
		
		//save the post meta data 
		jQuery.post(ph.baseurl + '/ajax/ph_save.php',{action: "picture_url", url: jQuery(this).val(), id: box_id});
		ph_update_message(box_id);
	}
});

jQuery(".ph_headline").blur(function(){

	box_id = jQuery(this).parents('table').parents('td').attr("id");
	box_id = box_id.replace("ph_settings_","");
	
	if (jQuery(this).val()!=""){ 
		//save the post meta data 
		jQuery.post(ph.baseurl + '/ajax/ph_save.php',{action: "headline", txt: jQuery(this).val(), id: box_id});
		ph_update_message(box_id);
	}
});


jQuery(".ph_order").change(function(){

	box_id = jQuery(this).parents('table').parents('td').attr("id");
	box_id = box_id.replace("ph_settings_","");
	
	//save the post meta data 
	jQuery.post(ph.baseurl + '/ajax/ph_save.php',{action: "order", order: jQuery(this).val(), id: box_id});
	ph_update_message(box_id);
});
