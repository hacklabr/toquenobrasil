jQuery(document).ready(function() {

    // Close login error
    jQuery("#login .close").click(
        function() {
            jQuery(this).parent().fadeOut();
        }
    )
    
    
    // Busca
    jQuery('#search-options li a').click(function() {
    
        var action = jQuery(this).attr('id').replace('search_', '');
        jQuery('.search-option').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('#searchform').data('action', action);
    
    });
    
    //init busca
    jQuery('#search-options li a:first').click();
    
    jQuery('#searchform').submit(function() {
    
        switch(jQuery(this).data('action')) {
        
        
            case 'usuarios': 
                
                jQuery(this).attr('action', tnb.homeurl + '/universo');
                
                jQuery('#s').attr('name', 'user_name');
                jQuery('#search_param1').attr('name', 'tnb_action').val('tnb_busca_usuarios');
                
                break;
        
            case 'oportunidades':
            
                jQuery(this).attr('action', tnb.homeurl + '/oportunidades');
                
                jQuery('#s').attr('name', 'oportunidade_nome');
                jQuery('#search_param1').attr('name', 'tnb_action').val('tnb_busca_oportunidades');
                jQuery('#search_param2').attr('name', 'acontece').val('nao_importa');
            
                break;
                
            case 'blog':
                
                jQuery(this).attr('action', tnb.homeurl);
                jQuery('#s').attr('name', 's');
                
                break;
        
        
        }
    
    });
    
    jQuery('#not-found-search-form').submit(function() {
    
        
        
        switch(jQuery(this).find('input[name=404_search_action]:checked').val()) {
        
        
            case 'usuarios': 
                
                jQuery(this).attr('action', tnb.homeurl + '/universo');
                
                jQuery('#404_s').attr('name', 'user_name');
                jQuery('#404_search_param1').attr('name', 'tnb_action').val('tnb_busca_usuarios');
                
                break;
        
            case 'oportunidades':
            
                jQuery(this).attr('action', tnb.homeurl + '/oportunidades');
                
                jQuery('#404_s').attr('name', 'oportunidade_nome');
                jQuery('#404_search_param1').attr('name', 'tnb_action').val('tnb_busca_oportunidades');
                jQuery('#404_search_param2').attr('name', 'acontece').val('nao_importa');
            
                break;
                
            case 'blog':
                
                jQuery(this).attr('action', tnb.homeurl);
                jQuery('#404_s').attr('name', 's');
                
                break;
        
        
        }
    
    });
    
    
    //Busca de usuários
    jQuery('#search-users').find('input[type=radio]').click(function() {
        if (jQuery('#search-users').find('input[type=radio]:checked').val() == 'produtores') {
            jQuery('#search-users').find('input[type=checkbox]').attr('disabled', 'disabled');
        } else {
            jQuery('#search-users').find('input[type=checkbox]').attr('disabled', false);
        }
    });
    
    
    // Main Menu
    var mainMenuLiWidth = jQuery("#main-menu ul li").width();
    jQuery("#main-menu ul li").children("ul").width(mainMenuLiWidth);

    jQuery("#main-menu ul li").hover(
        function() {
            if(jQuery(this).children("ul").size() > 0) {
                jQuery(this).children("ul").show();
                jQuery(this).children("a").css("border-radius","4px 4px 0 0");
                jQuery(this).children("a").css("-moz-border-radius","4px 4px 0 0");
            }
        },
        function() {
            if(jQuery(this).children("ul").size() > 0) {
                jQuery(this).children("ul").hide();
                jQuery(this).children("a").css("border-radius","4px");
                jQuery(this).children("a").css("-moz-border-radius","4px 4px 0 0");
            }
        }
    )
    
    jQuery("#main-menu ul li ul").hover(
        function() { jQuery(this).prev().css("background","#04BAEE"); },
        function() { jQuery(this).prev().css("background","#666"); }
    )

    // User Nav
    jQuery("#user-nav").hover(
        function() { jQuery(this).find('ul li ul').show(); },
        function() { jQuery(this).find('ul li ul').hide(); }
    )

    // Remove border of images with link
    jQuery("img").parent("a").css("border","none");
    
    // Search Button
    jQuery(".btn-search").hover(
        function() { jQuery(this).attr("src", tnb.baseurl+"/img/btn-search-opportunities-hover.png"); }, 
        function() { jQuery(this).attr("src", tnb.baseurl+"/img/btn-search-opportunities.png"); }
    )
    
    
        
    // Sign Up Page    
    var cadastro_tipo = 'artista';
    
    jQuery(".i-am-artist").hover(
        function() { jQuery("#i-am-artist").show() },
        function() { jQuery("#i-am-artist").hide() }
    ).click(function() {
        cadastro_tipo = 'artista';
        jQuery(".i-am-producer").attr('src', tnb.baseurl+'/img/sou-produtor-off.png');
        jQuery(".i-am-artist").attr('src', tnb.baseurl+'/img/sou-artista.png');
        jQuery('#tipo_usuario').val('artista');
    });

    jQuery(".i-am-producer").hover(
        function() { jQuery("#i-am-producer").show() },
        function() { jQuery("#i-am-producer").hide() }
    ).click(function() {
        cadastro_tipo = 'artista';
        jQuery(".i-am-producer").attr('src', tnb.baseurl+'/img/sou-produtor.png');
        jQuery(".i-am-artist").attr('src', tnb.baseurl+'/img/sou-artista-off.png');
        jQuery('#tipo_usuario').val('produtor');
    });


    
    jQuery('#user_login, #user_email').focus(function() {
        if (jQuery(this).attr('title') == jQuery(this).val()) jQuery(this).val('');
    }).blur(function() {
        if ('' == jQuery(this).val()) jQuery(this).val(jQuery(this).attr('title'));
    });
    
    jQuery('#_user_pass').focus(function() {
        //if (jQuery(this).attr('title') == jQuery(this).val()) jQuery(this).val('');
        jQuery(this).hide();
        jQuery('#user_pass').show().focus();
    })
    
    jQuery('#user_pass').blur(function() {
        if (jQuery(this).val() == '') {
            jQuery(this).hide();
            jQuery('#_user_pass').show();
        }
    })
    
    jQuery('#user_login').change(function() {
        jQuery(this).val(   jQuery(this).val().replace(/([^qwertyuioplkjhgfdsazxcvbnm1234567890-])/i, '')  );
        jQuery('#url_preview span').html(jQuery(this).val());
        if (jQuery(this).val() == '') jQuery('#url_preview span').html(jQuery(this).attr('title'));
        
        if (jQuery(this).attr('title') == jQuery(this).val() || jQuery(this).val() == '') {
            jQuery('.check_username').hide();
            return false;
        } else if (jQuery(this).val().length < 3) {
            jQuery('.check_username').hide();
            jQuery('#check_username_short').show();    
            return false;
        }
            
        jQuery('.check_username').hide();
        jQuery('#check_username_loading').show();
        jQuery.post(tnb.baseurl+'/ajax-check-username-availability.php', {username: jQuery(this).val()}, function(data) {
            if (data == '1') {
                jQuery('#check_username_loading').hide();
                jQuery('#check_username_true').show();
            } else {
                jQuery('#check_username_loading').hide();
                jQuery('#check_username_false').show();
            }
        });

    
    }).keyup(function() {
        jQuery(this).val(   jQuery(this).val().replace(/([^qwertyuioplkjhgfdsazxcvbnm1234567890-])/i, '')  );
        jQuery('#url_preview span').html(jQuery(this).val());
    });;
    
    
    //SLIDESHOWS
    
    jQuery('.slideshow').each(function() {
    
        var selector = '#' + jQuery(this).attr('id');
        
        jQuery(selector + ' img:gt(0)').hide();
        if(jQuery(selector + ' img:gt(0)').length > 0)
	        setInterval(function(){
	          jQuery(selector + ' :first-child').fadeOut()
	             .next('img').fadeIn()
	             .end().appendTo(selector);}, 
	          3000);
    
    });
    
    
    
    /*
    // Select Box
    jQuery("div.select").children("ul").children("li:not(:first-child)").hide();
    jQuery("div.select").hover(
        function() { jQuery(this).children("ul").children("li").show(); },
        function() { jQuery(this).children("ul").children("li:not(:first-child)").hide(); }
    )
    jQuery("div.select").find("input").hover(
        function() { jQuery(this).parent().show(); },
        function() { }
    )    
    */

    // jQuery("section#profile input, section#profile textarea, section#profile select").focus(
    //     function() { 
    //         jQuery(this).prev().css("background","#FCEA0D");
    //         jQuery(this).prev().css("color","#666");
    //     }
    // )
    // 
    // jQuery("section#profile input, section#profile textarea, section#profile select").blur(
    //     function() {
    //         jQuery(this).prev().css("background","#BBB");
    //         jQuery(this).prev().css("color","#FFF");
    //     }
    // )
    
    // User menu
    jQuery('.user-nav').mouseover(function() {
        jQuery(this).find('ul.usermenu').show();
    }).mouseout(function() {
        jQuery(this).find('ul.usermenu').hide();
    });
    
    
    // Modais
    jQuery('.tnb_modal').dialog({
        autoOpen: false,
        modal: true,
        width: 700,
        resizable: false
    });
    
    hl.tip.init();
});

function tnbCarregaCidadesOptions(campoId, uf){
	  var selected = jQuery('#'+campoId).val();
	  selected = encodeURI(selected);
	  jQuery('#'+campoId+'_select').load(tnb.baseurl+'/cidades-options.php?uf='+uf+'&selected='+selected,function(result){jQuery('#'+campoId+'_select').html(result)});
	}



var hl = {
    tip:{
        init: function(){
            jQuery(".hltip").live('mouseenter, mousemove',function(e){
                var tip = jQuery(this).data('tip');
                var _left = e.clientX + jQuery(document).scrollLeft() - 45;
                var _top = jQuery(this).offset().top ;
                var _height = jQuery(this).height();
                
                if(!tip){
                    var content = jQuery(this).attr('title');
                    
                    if(content.indexOf(':') > 0){
                        content = '<div class="hltip-title">'+(content.substr(0, content.indexOf(':')))+'</div>'+(content.substr(content.indexOf(':')+1));
                    }
                    tip = jQuery('<div class="hltip-box"><div class="hltip-arrow-top"></div><div class="hltip-text">'+content+'</div><div class="hltip-arrow-bottom"></div></div><').hide();
                    tip.css({position:'absolute', zIndex: 9999});
                    jQuery(document.body).append(tip);
                    tip.css('width', tip.width());
                    jQuery(this).data('tip',tip);
                    jQuery(this).attr('title','');
                }
                if(_left+tip.width() - jQuery(document).scrollLeft() > jQuery(window).width() - 11)
                    tip.css('left', jQuery(window).width() - 11 - tip.width() + jQuery(document).scrollLeft());
                else if(_left - jQuery(document).scrollLeft() < 6)
                    tip.css('left',jQuery(document).scrollLeft()+6);
                else
                    tip.css('left', _left);

                var diff = e.clientX + jQuery(document).scrollLeft() - parseInt(tip.css('left'));
                
                if(diff < 1)
                    diff = 1;
                else if (diff > parseInt(tip.outerWidth()) -11)
                    diff = parseInt(tip.outerWidth()) -11;
                
                if(jQuery(window).height() + jQuery(document).scrollTop() - 11 < _top + _height + tip.height()){
                    tip.find('.hltip-arrow-top').hide();
                    tip.find('.hltip-arrow-bottom').show();
                    tip.css('top', _top - tip.height() - 6);

                    tip.find('.hltip-arrow-bottom').css('margin-left',diff);
                }else{
                    tip.find('.hltip-arrow-top').show();
                    tip.find('.hltip-arrow-bottom').hide();
                    tip.find('.hltip-arrow-top').css('margin-left',diff);
                    tip.css('top', _top + _height + 6);
                }

                if(!tip.is(':visible')){
                    tip.fadeIn('fast');
                }
            });
            
            jQuery(".hltip").live('mouseleave',function(e){
                jQuery(this).data('tip').hide();
                
            });
        }
        
    }
}