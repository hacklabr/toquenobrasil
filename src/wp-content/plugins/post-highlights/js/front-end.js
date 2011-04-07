jQuery(document).ready(function() {
	
	
    phClass = function(instanceName) {
        this.init(instanceName);
    }
    
    
    jQuery.extend(phClass.prototype, {
        current: 1, 
        total: 0,
        delay: phSettings.delay,
        name: '',

        init: function(instanceName) {
            var th = this;
            this.name = instanceName;
            
            // get the total number of highlights
            this.total = jQuery('#posthighlights_container .ph_post').length;
            
            if (this.total == 0)
                return;
            
            // display elements after loading is complete
            jQuery('#posthighlights_container .ph-hide-while-loading').fadeIn();

            // adds numeric navigation if present
            jQuery('#posthighlights_container #ph-numeric-nav').append('<ul></ul>');
            
            var countNav = 1;
            jQuery('#posthighlights_container .ph_post').each(function() {
            	var title = jQuery('#posthighlights_container .ph_post .ph_content .ph_title').attr('alt');
            	jQuery('#posthighlights_container #ph-numeric-nav ul').append('<li id="ph-numeric-nav-' + countNav + '"><a title="' + title + '">' + countNav + '</a></li>');
            	countNav++;
            });
            jQuery('#posthighlights_container #ph-numeric-nav li a').each(function() {
                jQuery(this).click(function() {
                	var number = jQuery(this).html();
                    if (number != th.current) th.goTo(number);
                });
            });
            
            // add arrows navigation
            jQuery('#posthighlights_container #ph-next-nav').click(function() {
            	th.next();
            });
            
            jQuery('#posthighlights_container #ph-prev-nav').click(function() {
            	th.previous();
            });
            jQuery('#posthighlights_container').append('<div id="ph-canvas-base" class="ph-canvas"></div>');
            jQuery('#posthighlights_container').append('<a href="" id="ph-canvas-overlay" class="ph-canvas"></a>');
                
            this.goTo(this.current);
        }, 
        
        next: function() {
            var target;
            if (this.current < this.total) {
                target = this.current + 1;
            } else {
                target = 1;
            }
            this.goTo(target);
        },
        
        previous: function() {
            var target;
            if (this.current > 1) {
                target = this.current - 1;
            } else {
                target = this.total;
            }
            this.goTo(target);
        }, 
        
        goTo: function(target) {
        	
        	jQuery('#posthighlights_container #ph-canvas-base').css('backgroundImage','url(' + jQuery('#posthighlights_container #ph_highlight-' + this.current + ' div.ph_picture img').attr('src')+ ')');
        	jQuery('#posthighlights_container #ph-canvas-overlay').hide();
        	
        	jQuery('#posthighlights_container #ph-canvas-overlay').css(
        			'backgroundImage', 'url(' + jQuery('#posthighlights_container #ph_highlight-' + target + ' div.ph_picture img').attr('src') + ')'
        			).attr('href',jQuery('#posthighlights_container #ph_highlight-' + target + ' div.ph_picture a').attr('href'));
        	
        	
        	jQuery('#posthighlights_container #ph_highlight-' + this.current + ' div.ph_content').fadeOut('normal', function() {
        		jQuery('#posthighlights_container div.ph_content').hide();
        		jQuery('#posthighlights_container #ph_highlight-' + target + ' div.ph_content').fadeIn('normal');
        		jQuery('#posthighlights_container #ph-canvas-overlay').fadeIn('normal');
        	});
        	
            jQuery('#posthighlights_container #ph-numeric-nav #ph-numeric-nav-' + this.current).removeClass('current');
            jQuery('#posthighlights_container #ph-numeric-nav #ph-numeric-nav-' + target).addClass('current');
            this.current = parseInt(target);

            if (this.total > 1) {
	            clearTimeout(this.slideshow);
	            this.slideshow = setTimeout(this.name + '.next()', this.delay);
            }
        }

    });
    
    postHighlights = new phClass('postHighlights');

});
