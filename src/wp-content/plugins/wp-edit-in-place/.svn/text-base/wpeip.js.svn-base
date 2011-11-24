/**
 * 
 */
jQuery(document).ready(function(){
	jQuery(document).data('ctrl',false);
	jQuery(document).data('shift',false);
	
	jQuery(this).keydown(function(e){
		if(e.keyCode == 17){
			jQuery(document).data('ctrl',true);
			jQuery(".wpeip_term").css('cursor', 'pointer');
		}
		
		if(e.keyCode == 16){
			jQuery(document).data('shift',true);
		}
		if(jQuery(document).data('ctrl') && jQuery(document).data('shift'))
			jQuery(".wpeip_term").css({background: 'rgba(0,255,0,.2)'});
	});
	jQuery(this).keyup(function(e){
		if(e.keyCode == 17){
			jQuery(document).data('ctrl',false);
			jQuery(".wpeip_term").css('cursor', 'auto');
		}
		
		if(e.keyCode == 16){
			jQuery(document).data('shift',false);
		}
		
		if(!jQuery(document).data('ctrl') || !jQuery(document).data('shift')){
			jQuery(".wpeip_term").css({background: ''});
		}
	});
	
	jQuery(".wpeip_term").each(function(){
		var h = jQuery(this).height();
		var w = jQuery(this).height();
		
		var key = jQuery(this).attr('id');
		var html = jQuery(this).html();
		var lcode = jQuery(this).attr('lcode');
		jQuery(document.body).append('<div id="'+key+'-formdiv" class="wpeip_formdiv" style="display:none;"><form method="post"><input type="hidden" name="wpeip_action" value="update_terms"><input type="hidden" name="updated[]" value="'+key+'"/><textarea name="'+key+'['+lcode+']"></textarea><input type="reset" value="'+wpeip.cancel+'"/><input type="submit" value="'+wpeip.save+'"/></form></div>');
		
		jQuery(this).attr('title', 'pressione a tecla Ctrl e clique para editar este texto');
		
		jQuery(this).click(function (e){
                                
			if(e.ctrlKey){
				var div = jQuery('#'+key+'-formdiv');
				var _width = parseInt(div.css('width'));
				var _height = parseInt(div.css('height'));
				
				var _left = (parseInt(jQuery(window).width()/2 - _width/2))+'px';
				var _top = (parseInt(jQuery(window).height()/2 - _height/2) + jQuery(window).scrollTop())+'px'; 
				
				div.find('input:reset').click(function (){
					div.hide();
				});
				var val = jQuery(this).html().replace(/<br>/gi, "");
				div.find('textarea').val(val);
				div.css({
					position: 'absolute',
					zIndex: '999',
					left: _left,
					top: _top,
					display: 'block'
				});
				return false;
			}
		});
	});
});