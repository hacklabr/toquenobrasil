<?php
class TNB_WidgetContainerGroup{
    private $name;
    private $user_id;
    private $containers = array();
    
    private $css;
    
    public function __construct($name, array $containers_names, $user_id, $widget_classes, $default_widgets){
        global $wpdb;
        $this->name = $name;
        $this->user_id = $user_id;
        
        foreach ($containers_names as $container_name)
            $this->containers[$container_name] = new TNB_WidgetContainer($this, $container_name, $user_id, $widget_classes, isset($default_widgets[$container_name]) && is_array($default_widgets[$container_name]) ? $default_widgets[$container_name] : array());

        /* 
         * TNB - tratamento sintomático (seria melhor prevenir, mas como não se sabe a causa... )
         * 
         * por algum motivo os containers de widgets as vezes são salvos como um array vazio, se isso acontecer com os dois containers,
         * recuperarei todos os widgets deste usuário os dividirei entre os container right e left
         */
        $left_widgets = array();
        $right_widgets = array();
        
        $left_container_broken = (!is_array($this->containers['left']->widgets) or count($this->containers['left']->widgets) == 0 or (count($this->containers['left']->widgets) == 1 && is_null(array_pop($this->containers['left']->widgets))));
        $right_container_broken = (!is_array($this->containers['right']->widgets) or count($this->containers['right']->widgets) == 0 or (count($this->containers['right']->widgets) == 1 && is_null(array_pop($this->containers['right']->widgets))));
        global $TNBug_Perfil;
        
        if($left_container_broken && $right_container_broken){
            
            $all_widgets = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE user_id = $this->user_id AND meta_key LIKE '_widget_Widget%'");
            
            foreach($all_widgets as $i => $_wid){
                if($i < count($all_widgets) / 2)
                    $left_widgets[$_wid->meta_key] = unserialize(base64_decode($_wid->meta_value));
                else
                    $right_widgets[$_wid->meta_key] = unserialize(base64_decode($_wid->meta_value));
            }
            
            $this->containers['left']->setWidgets($left_widgets);
            $this->containers['right']->setWidgets($right_widgets);
            $TNBug_Perfil = 'ambos';
            
        }
        
        /* TNB - tratamento sintomático (seria melhor prevenir, mas como não se sabe a causa... )
         * 
         * se acontecer somente com um dos containers, seleciona todos os widgets que não estejam no container que não está vazio e os coloca
         * no container que está vazio
         */
        
        // somente o container da esquerda vazio
        elseif($left_container_broken && !$right_container_broken){
            
            $all_widgets = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE user_id = $this->user_id AND meta_key LIKE '_widget_Widget%'");
            
            foreach($all_widgets as $i => $_wid){
                if(!isset($this->containers['right']->widgets[$_wid->meta_key]))
                    $left_widgets[$_wid->meta_key] = unserialize(base64_decode($_wid->meta_value));
            }
            
            $this->containers['left']->setWidgets($left_widgets);
            $TNBug_Perfil = 'left';
        }
        
        // somente o container da direita vazio        
        elseif(!$left_container_broken && $right_container_broken){
            
            $all_widgets = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE user_id = $this->user_id AND meta_key LIKE '_widget_Widget%'");
            
            foreach($all_widgets as $i => $_wid){
                if(!isset($this->containers['left']->widgets[$_wid->meta_key]))
                    $right_widgets[$_wid->meta_key] = unserialize(base64_decode($_wid->meta_value));
            }
            
            $this->containers['right']->setWidgets($right_widgets);
            $TNBug_Perfil = 'right';
        }
        $this->css = get_user_meta($user_id, "_widgets_{$name}_css",true);
        
        if(!$this->css){
            $css = array(
                'body-background-color' => '',
                'body-image-url' => '',
                'body-background-image-repeat' => 'repeat',
                'widget-header-background-color' => '#04BAEE',
                'widget-header-font-color' => '#FFFFFF',
                'widget-background-color' => '255,255,255',
                'widget-font-color' => '#666666',
                'link-color' => '#04BAEE',
                'link-hover-color' => '#76B72A',
                'widget-alpha' => '1'
            );
            $this->css = $css;
            add_user_meta($user_id, "_widgets_{$name}_css", $css);
        }
        
    }
    
    public function __get($name){
        switch($name){
            case 'id':
                return "_widget_group_{$this->user_id}_{$this->name}";
            break;
            
            case 'name':
                return $this->name;
            break;
            
            case 'containers':
                return $this->getContainers();
            break;
            
            case 'scope':
                return $this->user_id ? 'user' : 'theme';
            break;
        }
    }
    
    public static function current_user_can_edit($scope, $user_id){
        global $current_user;
    
        if($scope == 'user'){
            $itsMe = $current_user->ID == $user_id;
            return (current_user_can('edit_users') || $itsMe);
        }else{
            return is_admin();
        }
        
    }
    
    public function editable(){
        return self::current_user_can_edit($this->scope, $this->user_id);
    }
    
    public function do_actions(){
        global $wpdb, $current_user;
        if($this->editable()){
            
            if(isset($_POST['tnb_widget_action']) && isset($_POST['tnb_widget_group_id']) && $_POST['tnb_widget_group_id'] == $this->id){
                global $TNBug_Perfil;
                if($TNBug_Perfil){
                    $log_data['_POST'] = $_POST;
                    $log_data['_FILES'] = $_FILES;
                    tnb_log('bug-perfil-container-'.$TNBug_Perfil, $log_data);
                }
                switch($_POST['tnb_widget_action']){
                    case 'save':
                        
                        global $TNBug, $container_post;
                        $container_post = array();
                        //_pr($_POST, true);
                        foreach($this->containers as $container){
                            $container_post[$container->id] = $_POST[$container->id.'_items'];
                            $widgets_ids = $_POST[$container->id.'_items'];
                            
                            
                            /* 
                             * se no lugar da lista de ids existir a string [object Object] significa que houve erro na hora de recuperar a ordem
                             * dos widgets, então estas não serão salvas, o usuário será notificado e será gravado um log as seguintes informações:
                             * * data
                             * * nome do usuário
                             * * posições atuais dos widgets
                             * * navegador e versão
                             * a lista de ids é recuperada em: jQuery('#<?php echo $this->id; ?>_form').submit(function(){
                             */
                            
                            if($widgets_ids == '[object Object]'){
                                $TNBug = true;
                            }else{
                                // para a ordenação funcionar, primeiro crio o array com as chaves sendo o id do widget na ordem certa
                                $ids = explode(',', $widgets_ids);
                                $widgets = array();
                                foreach($ids as $id)
                                    $widgets[$id] = null;
                                     
        
                                $widgets_ids = str_replace(',', "','", $widgets_ids);
                                $widgets_ids = "'$widgets_ids'";
                                $widgets_rows = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key IN ($widgets_ids)");
                                
                                foreach($widgets_rows as $row)
                                    if(is_serialized($row->meta_value))
                                        $widgets[$row->meta_key] = unserialize($row->meta_value);
                                    else
                                        $widgets[$row->meta_key] = unserialize(base64_decode($row->meta_value));
                                        
                                $container->setWidgets($widgets);
                            }
                            $container->save();
                        }
                        
                        
                        if($TNBug){
                            // salva o log
                            
                            $bug_data = null;
                            foreach($this->containers as $container)
                                $bug_data[$container->name] = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key='$container->meta_key' AND user_id='$current_user->ID'");

                            $bug_data['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
                            $bug_data['_POST'] = $_POST;
                             
                            tnb_log('bug-perfil-tnbox', $bug_data);
                            
                        }
                        
                        if(isset($_POST['css']) && is_array($_POST['css'])){
                           if($_POST['css']['body-background-color'] != $this->css['body-background-color']){
                               $this->css['body-background-color'] = $_POST['css']['body-background-color'];
                           }
                           
                           if(isset($_POST['remove_background_image'])){
                               $this->css['body-image-url'] = '';
                           }
                           
                           $this->css['body-background-image-repeat'] = $_POST['css']['body-background-image-repeat'];
                           $this->css['widget-header-background-color'] = $_POST['css']['widget-header-background-color'];
                           $this->css['widget-header-font-color'] = $_POST['css']['widget-header-font-color'];
                           $this->css['widget-background-color'] = $_POST['css']['widget-background-color'];
                           $this->css['widget-font-color'] = $_POST['css']['widget-font-color'];
                           $this->css['widget-alpha'] = $_POST['css']['widget-alpha'];
                           $this->css['link-color'] = $_POST['css']['link-color'];
                           $this->css['link-hover-color'] = $_POST['css']['link-hover-color'];
                           //_pr($_POST);
                           $changed = true;     
                            
                        }
                        
                        //          UPLOAD_ERR_CANT_WRITE   = 7
                        //          UPLOAD_ERR_EXTENSION    = 8
                        //          UPLOAD_ERR_FORM_SIZE    = 2
                        //          UPLOAD_ERR_INI_SIZE     = 1
                        //          UPLOAD_ERR_NO_FILE      = 4
                        //          UPLOAD_ERR_NO_TMP_DIR   = 6
                        //          UPLOAD_ERR_OK           = 0
                        //          UPLOAD_ERR_PARTIAL      = 3
                        $acceptedFormats = array('image/gif', 'image/png', 'image/jpeg',
                                      'image/pjpeg', 'image/x-png');
          
                        if(isset($_FILES['body-background-image']) && $_FILES['body-background-image']['name'] && $_FILES['body-background-image']['error'] == UPLOAD_ERR_OK && in_array($_FILES['body-background-image']['type'], $acceptedFormats)){
                            require_once(ABSPATH . '/wp-admin/includes/file.php');
                            require_once(ABSPATH . '/wp-admin/includes/media.php');
                            require_once(ABSPATH . '/wp-admin/includes/image.php');
                            
                            $post_data = array(
                            	'post_author' => $this->user_id,
                                'post_title' => 'profile_background_image'
                            );
                            $postid = media_handle_upload('body-background-image', null, $post_data);
                            add_post_meta($postid, '_image_type', 'body-background');
        
                            $this->css['body-image-url'] = wp_get_attachment_url($postid);
                            
                            $changed = true;
                        }
                        
                        if($changed)
                            update_user_meta($this->user_id, "_widgets_{$this->name}_css", $this->css);
                    break;
                    
                    case 'add_widget':
                        
                        if(isset($this->containers[$_POST['tnb_widget_container_name']])){
                            
                            $widget = TNB_Widget::do_action();
                            if($widget){
                                $this->containers[$_POST['tnb_widget_container_name']]->addWidget($widget);
                                $this->containers[$_POST['tnb_widget_container_name']]->save();
                            }
                        }
                    break;
                    
                }
            }elseif(isset($_POST['tnb_widget_action']) && $_POST['tnb_widget_action'] == 'delete_widget'){
                foreach($this->containers as $container){
                    if(isset($container->widgets[$_POST['widget_id']])){
                        //_pr($_POST['widget_id'],true);
                        $widgets = $container->widgets;
                        unset($widgets[$_POST['widget_id']]);
                        delete_user_meta($this->user_id, $_POST['widget_id']);
                        
                        $container->setWidgets($widgets);
                        $container->save();
                    }
                }
            }elseif(isset($_POST['tnb_widget_action']) && $_POST['tnb_widget_action'] == 'update_widget'){
                 $instance = TNB_Widget::do_action();
                 if($instance)
                     foreach ($this->containers as $container)
                         $container->load();
            }
        }
    }
    
    public function getContainers(){
        return $this->containers;
    }
    
    protected function __print_js(){
        if($this->editable()){
            $containersIds = '';
            
            foreach ($this->containers as $container)
                $containersIds .= $containersIds ? ", #{$container->id}_ul" : "#{$container->id}_ul";
      
          
?>
<script type='text/javascript'>
<!--
var tnb_original_css = {};
var _widget_open_menu_id;
jQuery(document).ready(function() {
    <?php 
    global $TNBug;
    if($TNBug):?>
        alert('Ocorreu um erro conhecido e por este motivo os posicionamentos dos TNBox não serão salvos. Por favor, tente fazer estas alterações utilizando outro navegador. Estamos trabalhando para solucionar este problema.');
    <?php endif; ?>
    
    jQuery( '<?php echo $containersIds; ?>' ).sortable({
		connectWith: '.<?php echo $this->id; ?>',
	    placeholder: "tnb_widget_placeholder"
		        
	});

    
    jQuery('#<?php echo $this->id; ?>_form').submit(function(){
    	<?php foreach ($this->containers as $container): ?>
    	//alert(jQuery('#<?php echo $container->id;?>_ul').sortable('toArray'));
        var col;
        col = '';
        jQuery('#<?php echo $container->id;?>_ul').find('li').each(function(){
            var widget_id = jQuery(this).attr('id');
            if(typeof widget_id == 'string' && widget_id != '')
                col = col ? col+','+widget_id : widget_id;
                
        });
        
        jQuery('#<?php echo $container->id; ?>_items').val(col);
        
	    <?php endforeach; ?>
    });
    

    /* toolbar */

    /**   BACKGROUND OPTIONS  **/
    
    jQuery("#<?php echo $this->id?>_botao_fundo").click(function(){
    	jQuery("#widget_<?php echo $this->name; ?>_toolbar_widgets_options").hide();
        jQuery("#widget_<?php echo $this->name; ?>_toolbar_background_options").toggle();

        jQuery(this).data('aberto',!jQuery(this).data('aberto'));
        if(jQuery(this).data('aberto'))
        	jQuery(this).addClass('bg-yellow');
        else
        	jQuery(this).removeClass('bg-yellow');

        jQuery("#<?php echo $this->id?>_botao_widgets").removeClass('bg-yellow');
        jQuery("#<?php echo $this->id?>_botao_widgets").data("aberto",false);
        return false;
    });

    
    /**   WIDGETS OPTIONS  **/

    jQuery("#<?php echo $this->id?>_botao_widgets").click(function(){
    	jQuery("#widget_<?php echo $this->name; ?>_toolbar_background_options").hide();
    	jQuery("#widget_<?php echo $this->name; ?>_toolbar_widgets_options").toggle();

    	jQuery(this).data('aberto',!jQuery(this).data('aberto'));
        if(jQuery(this).data('aberto'))
        	jQuery(this).addClass('bg-yellow');
        else
        	jQuery(this).removeClass('bg-yellow');

        jQuery("#<?php echo $this->id?>_botao_fundo").removeClass('bg-yellow');
        jQuery("#<?php echo $this->id?>_botao_fundo").data("aberto",false);
    	return false;
    });    


    // COR DA FONTE DO HEADER DO WIDGET
    jQuery("#widget_<?php echo $this->name; ?>_widget-header-font-color").change(function(){
        jQuery(".tnb_widget_header").css({color: jQuery("#widget_<?php echo $this->name; ?>_widget-header-font-color").val()});
    });

    // COR DE FUNDO DO WIDGET
    jQuery("#widget_<?php echo $this->name; ?>_widget-background-color").change(function(){
        jQuery(".tnb_widget").css({background: jQuery("#widget_<?php echo $this->name; ?>_widget-background-color").val()});
    });

    // COR DA FONTE DO WIDGET
    jQuery("#widget_<?php echo $this->name; ?>_widget-font-color").change(function(){
        jQuery(".tnb_widget").css({color: jQuery("#widget_<?php echo $this->name; ?>_widget-font-color").val()});
    });

    
    jQuery('#widget_<?php echo $this->name; ?>_body-background-color-selector').ColorPicker({
    	
    	onChange: function (hsb, hex, rgb) {
            
    		jQuery('#widget_<?php echo $this->name; ?>_body-background-color').val("#"+hex);
            var repeat1;
    		switch(jQuery('#widget_<?php echo $this->name; ?>_body-background-image-repeat').val()){
                case 'repeat':
                    repeat1 = 'repeat'; 
                break;
                case 'no-repeat':
                	repeat1 = 'no-repeat top center';
                break;
                case 'fixed':
                	repeat1 = 'no-repeat fixed top center';
                break;
            }
            if(document.getElementById("<?php echo $this->name?>_remove_background_image").checked){
    		    jQuery(document.body).css({background: "#"+hex});
            }else{
            	jQuery(document.body).css({background: "#"+hex+' url(<?php echo $this->css['body-image-url']?>) '+repeat1});
                
            	
            }
    		jQuery('#widget_<?php echo $this->name; ?>_body-background-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
    	}
    });


    jQuery("#<?php echo $this->name?>_remove_background_image").change(function (){
    	var repeat1;
		switch(jQuery('#widget_<?php echo $this->name; ?>_body-background-image-repeat').val()){
            case 'repeat':
                repeat1 = 'repeat'; 
            break;
            case 'no-repeat':
            	repeat1 = 'no-repeat top center';
            break;
            case 'fixed':
            	repeat1 = 'no-repeat fixed top center';
            break;
        }
        if(document.getElementById("<?php echo $this->name?>_remove_background_image").checked){
            jQuery(document.body).css({background: jQuery('#widget_<?php echo $this->name; ?>_body-background-color').val()});
        }else{
        	jQuery(document.body).css({background: jQuery('#widget_<?php echo $this->name; ?>_body-background-color').val()+' url(<?php echo $this->css['body-image-url']?>) '+repeat1});
            
        	
        }
    });
    
    
    jQuery('#widget_<?php echo $this->name; ?>_widget-header-background-color-selector').ColorPicker({
    	onChange: function (hsb, hex, rgb) {
    		jQuery('#widget_<?php echo $this->name; ?>_widget-header-background-color').val("#"+hex);
    		jQuery(".tnb_widget_header").css({background: "#"+hex});
    		jQuery('#widget_<?php echo $this->name; ?>_widget-header-background-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
    	}
    });

    jQuery('#widget_<?php echo $this->name; ?>_widget-header-font-color-selector').ColorPicker({
    	onChange: function (hsb, hex, rgb) {
    		jQuery('#widget_<?php echo $this->name; ?>_widget-header-font-color').val("#"+hex);
    		jQuery(".tnb_widget_header").css({color: "#"+hex});
    		jQuery('#widget_<?php echo $this->name; ?>_widget-header-font-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
    	}
    });
    
    jQuery('#widget_<?php echo $this->name; ?>_widget-background-color-selector').ColorPicker({
    	onChange: function (hsb, hex, rgb) {
    		if(jQuery.browser.msie && jQuery.browser.version < 9){
    			jQuery('#widget_<?php echo $this->name; ?>_widget-background-color').val(rgb.r+','+rgb.g+','+rgb.b);

        		var _a = (parseInt(jQuery('#widget_<?php echo $this->name; ?>_widget-alpha').val()*255)).toString(16);
                _a = _a.length == 1 ? "0"+_a : _a;

                var color = _a+hex;
                
                jQuery(".tnb_widget").css({"background": 'none'});
                jQuery(".tnb_widget").css({"-ms-filter": "progid:DXImageTransform.Microsoft.gradient(startColorstr=#"+color+",endColorstr=#"+color+")"});
                jQuery(".tnb_widget").css({"filter": "progid:DXImageTransform.Microsoft.gradient(startColorstr=#"+color+",endColorstr=#"+color+")"});
                jQuery(".tnb_widget").css({"zoom": '1'});
                jQuery('#widget_<?php echo $this->name; ?>_widget-background-color-selector .widget_colorpicker').css({'background': '#'+hex});
               
    		}else{
    			jQuery('#widget_<?php echo $this->name; ?>_widget-background-color').val(rgb.r+','+rgb.g+','+rgb.b);
        		
                jQuery(".tnb_widget").css({background: 'rgba('+rgb.r+','+rgb.g+','+rgb.b+','+jQuery('#widget_<?php echo $this->name; ?>_widget-alpha').val()+')'});
        		jQuery('#widget_<?php echo $this->name; ?>_widget-background-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
                    
    		}
    	}
    });


    jQuery('#widget_<?php echo $this->name; ?>_widget-font-color-selector').ColorPicker({
        onChange: function (hsb, hex, rgb) {
    		jQuery('#widget_<?php echo $this->name; ?>_widget-font-color').val("#"+hex);
    		jQuery(".tnb_widget").css({color: "#"+hex});
    		jQuery('#widget_<?php echo $this->name; ?>_widget-font-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
    	}
    });

    jQuery('#widget_<?php echo $this->name; ?>_link-color-selector').ColorPicker({
        onChange: function (hsb, hex, rgb) {
        	jQuery('#widget_<?php echo $this->name; ?>_link-color').val("#"+hex);
    		
    		jQuery('#widget_<?php echo $this->name; ?>_link-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
            
    		jQuery(".tnb_widget a").css({'color': jQuery('#widget_<?php echo $this->name; ?>_link-color').val()});
            
    		jQuery(".tnb_widget a").hover(function(){
                jQuery(this).css({'color': jQuery('#widget_<?php echo $this->name; ?>_link-hover-color').val()});
    		},function(){
    			jQuery(this).css({'color': jQuery('#widget_<?php echo $this->name; ?>_link-color').val()});
    		});
    	}
    });

    jQuery('#widget_<?php echo $this->name; ?>_link-hover-color-selector').ColorPicker({
        onChange: function (hsb, hex, rgb) {
    		jQuery('#widget_<?php echo $this->name; ?>_link-hover-color').val("#"+hex);
    		
    		jQuery('#widget_<?php echo $this->name; ?>_link-hover-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
            
    		jQuery(".tnb_widget a").hover(function(){
    			jQuery(this).css({'color': jQuery('#widget_<?php echo $this->name; ?>_link-hover-color').val()});
    		},function(){
    			jQuery(this).css({'color': jQuery('#widget_<?php echo $this->name; ?>_link-color').val()});
    		});
    	}
    });

    

    jQuery('#widget_<?php echo $this->name; ?>_body-background-image-repeat').change(function (){
        // se a cor de fundo não foi alterada e existe uma imagem
        if(tnb_original_css['body-image-url'] != '' && !document.getElementById("<?php echo $this->name?>_remove_background_image").checked){
        	
            switch(jQuery('#widget_<?php echo $this->name; ?>_body-background-image-repeat').val()){
                case 'repeat':
                    jQuery(document.body).css({background: jQuery('#widget_<?php echo $this->name; ?>_body-background-color').val() + ' url('+tnb_original_css['body-image-url']+') repeat'}); 
                break;
                case 'no-repeat':
                	jQuery(document.body).css({background: jQuery('#widget_<?php echo $this->name; ?>_body-background-color').val() + ' url('+tnb_original_css['body-image-url']+') no-repeat top center'});
                break;
                case 'fixed':
                	jQuery(document.body).css({background: jQuery('#widget_<?php echo $this->name; ?>_body-background-color').val() + ' url('+tnb_original_css['body-image-url']+') no-repeat fixed top center'});
                break;
            }
        }
        
    });
    

    
    
    jQuery("#widget_<?php echo $this->name; ?>_widget-alpha-slider").slider({
        value: <?php echo $this->css['widget-alpha'] ? $this->css['widget-alpha'] : 0;?>,
        min: 0,
        max: 1,
        step: 0.05,
        slide: function (event, ui){
            var val = ui.value;
            jQuery("#widget_<?php echo $this->name; ?>_widget-alpha").val(val);
            var valor = jQuery('#widget_<?php echo $this->name; ?>_widget-background-color').val()+','+val; 
            if(jQuery.browser.msie && jQuery.browser.version < 9){
                var nums = valor.split(",");
                var _r = nums[0];
                var _g = nums[1];
                var _b = nums[2];
                var _a = nums[3];
                
                _a = parseInt((_a*255)).toString(16);
                _r = parseInt(_r).toString(16);
                _g = parseInt(_g).toString(16);
                _b = parseInt(_b).toString(16);

                _a = _a.length == 1 ? "0"+_a : _a;
                _r = _r.length == 1 ? "0"+_r : _r;
                _g = _g.length == 1 ? "0"+_g : _g;
                _b = _b.length == 1 ? "0"+_b : _b;
                
                var color = _a+_r+_g+_b;
                jQuery(".tnb_widget").css({"background": 'none'});
                jQuery(".tnb_widget").css({"-ms-filter": "progid:DXImageTransform.Microsoft.gradient(startColorstr=#"+color+",endColorstr=#"+color+")"});
                jQuery(".tnb_widget").css({"filter": "progid:DXImageTransform.Microsoft.gradient(startColorstr=#"+color+",endColorstr=#"+color+")"});
                jQuery(".tnb_widget").css({"zoom": '1'});
                
            }else{
                jQuery(".tnb_widget").css({"background": 'rgba('+valor+')'});
            }
        }
    });
    <?php $csss = is_array($this->css) ? $this->css : array(); ?>
    <?php foreach ($csss as $k => $v): ?>
    tnb_original_css['<?php echo $k; ?>'] = '<?php echo $v; ?>';
    
    <?php endforeach;?>
        
    jQuery('#widget_<?php echo $this->name; ?>_reset_button').click(function(){
        // ======================================================================
            // fundo
            jQuery('#widget_<?php echo $this->name; ?>_body-background-color').val(tnb_original_css['body-background-color']);
            jQuery(document.body).css({background: tnb_original_css['body-background-color']});
            jQuery('#widget_<?php echo $this->name; ?>_body-background-color-selector .widget_colorpicker').css('backgroundColor', tnb_original_css['body-background-color']);

            if(tnb_original_css['body-image-url']){
                var bg = tnb_original_css['body-background-color']+' url('+tnb_original_css['body-image-url']+')';
                switch(tnb_original_css['body-background-image-repeat']){
                    case 'repeat':
                    	bg = bg + ' repeat'; 
                    break;
                    case 'no-repeat':
                    	bg = bg + ' no-repeat top center';
                    break;
                    case 'fixed':
                    	bg = bg + ' no-repeat fixed top center';
                    break;
                }
                jQuery(document.body).css({background: bg});


                jQuery('#widget_<?php echo $this->name; ?>_body-background-image-repeat').val(tnb_original_css['body-background-image-repeat']);
                document.getElementById('<?php echo $this->name?>_remove_background_image').checked = false;
            }

            // widgets
            jQuery('#widget_<?php echo $this->name; ?>_widget-header-background-color').val(tnb_original_css['widget-header-background-color']);
            jQuery(".tnb_widget_header").css({background: tnb_original_css['widget-header-background-color']});
            jQuery('#widget_<?php echo $this->name; ?>_widget-header-background-color-selector .widget_colorpicker').css('backgroundColor', tnb_original_css['widget-header-background-color']);
        
            jQuery('#widget_<?php echo $this->name; ?>_widget-header-font-color').val(tnb_original_css['widget-header-font-color']);
            jQuery(".tnb_widget_header").css({color: tnb_original_css['widget-header-font-color']});
            jQuery('#widget_<?php echo $this->name; ?>_widget-header-font-color-selector .widget_colorpicker').css('backgroundColor', tnb_original_css['widget-header-font-color']);
       
            jQuery('#widget_<?php echo $this->name; ?>_widget-font-color').val(tnb_original_css['widget-font-color']);
            jQuery(".tnb_widget").css({color: tnb_original_css['widget-font-color']});
            jQuery('#widget_<?php echo $this->name; ?>_widget-font-color-selector .widget_colorpicker').css('backgroundColor', tnb_original_css['widget-font-color']);

<?php 

        if(Browsers::isIE7() || Browsers::isIE8()){
            list($r,$g,$b) = explode(',',$this->css['widget-background-color']);
            
            $a = dechex($this->css['widget-alpha']*255);
            $r = dechex($r);
            $g = dechex($g); 
            $b = dechex($b);
            
            $a = strlen($a) == 1 ? "0$a" : $a;
            $r = strlen($r) == 1 ? "0$r" : $r;
            $g = strlen($g) == 1 ? "0$g" : $g;
            $b = strlen($b) == 1 ? "0$b" : $b;
            
            // "$a$r$g$b"
            
            ?>
            jQuery(".tnb_widget").css({
                'background': 'none',
                '-ms-filter': 'progid:DXImageTransform.Microsoft.gradient(startColorstr=#<?php echo "$a$r$g$b";?>,endColorstr=#<?php echo "$a$r$g$b";?>)',
                'filter': 'progid:DXImageTransform.Microsoft.gradient(startColorstr=#<?php echo "$a$r$g$b";?>,endColorstr=#<?php echo "$a$r$g$b";?>)',
                'zoom':1
            });
            
            <?php 

        }else{
            ?>
            jQuery(".tnb_widget").css({'background': 'rgba('+tnb_original_css['widget-background-color']+','+tnb_original_css['widget-alpha']+')'});
            <?php 
        }
        
        
?>
        jQuery("#widget_<?php echo $this->name; ?>_widget-background-color").val(tnb_original_css['widget-background-color']);
        
        jQuery('#widget_<?php echo $this->name; ?>_widget-background-color-selector .widget_colorpicker').css({'backgroundColor': ' rgb('+tnb_original_css['widget-background-color']+')'});

        jQuery("#widget_<?php echo $this->name; ?>_widget-alpha-slider").slider({value: tnb_original_css['widget-alpha']});
        jQuery("#widget_<?php echo $this->name; ?>_widget-alpha").val(tnb_original_css['widget-alpha']);

        jQuery('#widget_<?php echo $this->name; ?>_link-color-selector').val(tnb_original_css['link-color']);
        jQuery(".tnb_widget a").css({'color': tnb_original_css['link-color']});
        jQuery('#widget_<?php echo $this->name; ?>_link-color-selector .widget_colorpicker').css('backgroundColor', tnb_original_css['link-color']);
        jQuery("#widget_<?php echo $this->name; ?>_link-color").val(tnb_original_css['link-color']);
        
        
        jQuery('#widget_<?php echo $this->name; ?>_link-hover-color-selector').val(tnb_original_css['link-hover-color']);
        jQuery('#widget_<?php echo $this->name; ?>_link-hover-color-selector .widget_colorpicker').css('backgroundColor', tnb_original_css['link-hover-color']);
        jQuery("#widget_<?php echo $this->name; ?>_link-hover-color").val(tnb_original_css['link-hover-color']);
        
        jQuery(".tnb_widget a").hover(function(){
			jQuery(this).css({'color': tnb_original_css['link-hover-color']});
		},function(){
			jQuery(this).css({'color': tnb_original_css['link-color']});
		});

        
    	//========================================================================

        return false;

        
    });
    jQuery(".colorpicker").mousemove(function(){
        
       jQuery(this).css({zIndex: 10000});
       
       jQuery(_widget_open_menu_id).show();
       jQuery(this).mouseleave(function(){jQuery(this).fadeOut()});
       
    });
    jQuery(".colorpicker").css({zIndex: 1500});
});
//-->
</script>
<?php 
        }
    } 
    
    
    /**
     * 
     * Imprime na tela a toolbar para este grupo de widgets
     */
    public function __print(){
        if($this->editable()){
            $this->__print_js();


?>

<div id='widget_<?php echo $this->name; ?>_toolbar' class='widget_toolbar grid_16 clearfix' style='z-index: 1000'>
    <div class="box clearfix">
        <form id='<?php echo $this->id; ?>_form' method='post' enctype="multipart/form-data">
            <p class="alignright">
                <button id='widget_<?php echo $this->name; ?>_reset_button' class='config-button' /><?php _e('descartar alterações', 'tnb');?></button>
                <button id='<?php echo $this->id?>_save' class='config-button bg-yellow'><?php _e('salvar tudo','tnb');?></button>            
            </p>
            <div id='widget_<?php echo $this->name; ?>_toolbar_background' class='alignleft'>
                <button id='<?php echo $this->id?>_botao_fundo' class="config-button"><?php _e('Configurar fundo', 'tnb'); ?></button>
                <div id='widget_<?php echo $this->name; ?>_toolbar_background_options' class='widget_toolbar_options'>
                    <div id="widget_<?php echo $this->name; ?>_body-background-color-selector" class="body-bgcolor grid_2 alpha">
                        <?php _e('cor de fundo', 'tnb'); ?>
                        <div class='widget_colorpicker' style='background-color:<?php echo htmlentities($this->css['body-background-color']);?>;'>&nbsp;</div>
                        <input type='hidden' id='widget_<?php echo $this->name; ?>_body-background-color' name='css[body-background-color]' value='<?php echo htmlentities($this->css['body-background-color']);?>'/>
                    </div>
                    <div class="grid_8">
                    <label><?php _e('imagem de fundo', 'tnb'); ?> <input type='file' id='widget_<?php echo $this->name; ?>_body-background-image' name='body-background-image' /></label>
                    
                    </div>
                    <div class="grid_5 omega">
                    <label>
                        <?php _e('repetição da imagem', 'tnb'); ?>
                        <select id='widget_<?php echo $this->name; ?>_body-background-image-repeat' name='css[body-background-image-repeat]'>
                            <option value='repeat'<?php if($this->css['body-background-image-repeat'] == 'repeat') echo ' selected="selected"';?>><?php _e('repetir', 'tnb');?></option>
                            <option value='no-repeat'<?php if($this->css['body-background-image-repeat'] == 'no-repeat') echo ' selected="selected"';?>><?php _e('não repetir', 'tnb');?></option>
                            <option value='fixed'<?php if($this->css['body-background-image-repeat'] == 'fixed') echo ' selected="selected"';?>><?php _e('fixa', 'tnb');?></option>
                        </select>
                    </label>
                    <label><input type="checkbox" id='<?php echo $this->name?>_remove_background_image' name='remove_background_image' value='1' /> <?php _e('remover imagem de fundo')?></label>
                    </div>
                    
                </div>
                <!-- .widget_toolbar_options -->
                
            </div>
            
            <div id='widget_<?php echo $this->name; ?>_toolbar_widgets' class='alignleft'>
                <button  id='<?php echo $this->id?>_botao_widgets' class="config-button"><?php _e('Configurar TNBox', 'tnb'); ?></button>
                <div id='widget_<?php echo $this->name; ?>_toolbar_widgets_options' class='widget_toolbar_options widget-settings'>
                
                    <div class="grid_4">
                        <div id="widget_<?php echo $this->name; ?>_widget-header-background-color-selector">
                            <div class='widget_colorpicker' style='background-color: <?php echo htmlentities($this->css['widget-header-background-color']);?>;'>&nbsp;</div>
                            <?php _e('Cabeçalhos dos TNBox', 'tnb'); ?> 
                            <input type='hidden' id='widget_<?php echo $this->name; ?>_widget-header-background-color' name='css[widget-header-background-color]' value='<?php echo htmlentities($this->css['widget-header-background-color']);?>'/>
                        </div>
                        
                        <div id="widget_<?php echo $this->name; ?>_widget-header-font-color-selector">
                            <div class='widget_colorpicker' style='background-color: <?php echo htmlentities($this->css['widget-header-font-color']);?>;'>&nbsp;</div>
                            <?php _e('Fonte dos cabeçalhos dos TNBox', 'tnb'); ?> 
                            <input type='hidden' id='widget_<?php echo $this->name; ?>_widget-header-font-color' name='css[widget-header-font-color]' value='<?php echo htmlentities($this->css['widget-header-font-color']);?>'/>
                        </div>
                        
                        <div id="widget_<?php echo $this->name; ?>_widget-background-color-selector">
                            <div class='widget_colorpicker' style='background-color: rgb(<?php echo htmlentities($this->css['widget-background-color']);?>);'>&nbsp;</div>
                            <?php _e('Fundo dos TNBox', 'tnb'); ?> 
                            <input type='hidden' id='widget_<?php echo $this->name; ?>_widget-background-color' name='css[widget-background-color]' value='<?php echo htmlentities($this->css['widget-background-color']);?>'/>
                        </div>
                        
                        <div id="widget_<?php echo $this->name; ?>_widget-font-color-selector">
                            <div class='widget_colorpicker' style='background-color: <?php echo htmlentities($this->css['widget-font-color']);?>;'>&nbsp;</div>
                            <?php _e('Fonte dos TNBox', 'tnb'); ?> 
                            <input type='hidden' id='widget_<?php echo $this->name; ?>_widget-font-color' name='css[widget-font-color]' value='<?php echo htmlentities($this->css['widget-font-color']);?>' />
                        </div>
                        
                        <div id="widget_<?php echo $this->name; ?>_link-color-selector">
                            <div class='widget_colorpicker' style='background-color: <?php echo htmlentities($this->css['link-color']);?>'>&nbsp;</div>
                            <?php _e('Links dos TNBox', 'tnb'); ?> 
                            <input type='hidden' id='widget_<?php echo $this->name; ?>_link-color' name='css[link-color]' value='<?php echo htmlentities($this->css['link-color']);?>'/>
                        </div>
                        
                        <div id="widget_<?php echo $this->name; ?>_link-hover-color-selector">
                            <div class='widget_colorpicker' style='background-color: <?php echo htmlentities($this->css['link-hover-color']);?>;'>&nbsp;</div>
                            <?php _e('Links destacados', 'tnb'); ?> 
                            <input type='hidden' id='widget_<?php echo $this->name; ?>_link-hover-color' name='css[link-hover-color]' value='<?php echo htmlentities($this->css['link-hover-color']);?>' />
                        </div>
                    </div>
                    <div class="grid_6">
                        <?php _e('Opacidade dos TNBox')?>
                        <input type='hidden' id='widget_<?php echo $this->name; ?>_widget-alpha' name='css[widget-alpha]' value='<?php echo $this->css['widget-alpha'] ? $this->css['widget-alpha'] : 0;?>'/>
                        <div id="widget_<?php echo $this->name; ?>_widget-alpha-slider"></div>
                    </div>
                </div>
                <!-- .widget_toolbar_options -->
                
            </div>
   			 <?php if(Browsers::isIE()): ?>
            	<div class='alignleft' style='margin-left:11px;'> <?php _e('O Internet Explorer não é totalmente compatível com a ferramenta de edição de perfil.','tnb'); ?> </div>
            <?php endif; ?>
            <input type='hidden' id='tnb_widget_action' name='tnb_widget_action' value='save' />
            <input type='hidden' id='tnb_widget_group_id' name='tnb_widget_group_id' value='<?php echo $this->id; ?>' />
            
        <?php foreach($this->containers as $container): ?>
            <input type='hidden' id='<?php echo $container->id; ?>_items' name='<?php echo $container->id; ?>_items' value='' />
        <?php endforeach;?>
        
            
        </form>
    </div>
    <!-- .box -->
</div>
<!-- .widget_toolbar -->
<?php 
            
            
        }
    }    
  
    public function __print_css(){
        // repeticoes: repeat, no-repeat, fixed, hidden
       
        echo "
<style type=\"text/css\">";
        
        if( $this->css['body-background-color'] || $this->css['body-image-url'] || $this->css['body-image-repeat']){
            echo "\nbody{ background: ";
                
               
            if($this->css['body-background-color']) echo $this->css['body-background-color'].' '; 
            
            if($this->css['body-image-url']) echo 'url('.$this->css['body-image-url'].') '; 
            
            if($this->css['body-background-image-repeat'] == 'repeat') echo 'repeat ';
            if($this->css['body-background-image-repeat'] == 'no-repeat') echo 'no-repeat top center';
            if($this->css['body-background-image-repeat'] == 'fixed') echo 'no-repeat fixed top center';
            
            
            echo "; }";
        }
        
        echo "\n.tnb_widget{ ";
        if(Browsers::isIE7() || Browsers::isIE8()){
            list($r,$g,$b) = explode(',',$this->css['widget-background-color']);
            
            $a = dechex($this->css['widget-alpha']*255);
            $r = dechex($r);
            $g = dechex($g); 
            $b = dechex($b);
            
            $a = strlen($a) == 1 ? "0$a" : $a;
            $r = strlen($r) == 1 ? "0$r" : $r;
            $g = strlen($g) == 1 ? "0$g" : $g;
            $b = strlen($b) == 1 ? "0$b" : $b;
            
            // "$a$r$g$b"
            
            echo "
            background: none;
            -ms-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#$a$r$g$b,endColorstr=#$a$r$g$b);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#$a$r$g$b,endColorstr=#$a$r$g$b);
            zoom:1;
            ";
            
//            jQuery(".tnb_widget").css({"background": 'none'});
//            jQuery(".tnb_widget").css({"-ms-filter": "progid:DXImageTransform.Microsoft.gradient(startColorstr=#"+color+",endColorstr=#"+color+")"});
//            jQuery(".tnb_widget").css({"filter": "progid:DXImageTransform.Microsoft.gradient(startColorstr=#"+color+",endColorstr=#"+color+")"});
//            jQuery(".tnb_widget").css({"zoom": '1'});
        }else{
            if($this->css['widget-background-color']) echo 'background: rgba('.$this->css['widget-background-color'].','.$this->css['widget-alpha'].'); ';
        }
        
        
        if($this->css['widget-font-color']) echo 'color: '.$this->css['widget-font-color'].'; ';
        
        echo '}';
        
        echo "";
        
        if($this->css['link-color']){
            echo "\n.tnb_widget a{ color: ".$this->css['link-color'].'; text-decoration:none !important;}';
            echo "\n.tnb_widget_body .jp-playlist a{ color: ".$this->css['link-color'].'; text-decoration:none !important;}';
            echo "\ndiv.jp-type-playlist div.jp-playlist a { color: ".$this->css['link-color'].'; text-decoration:none !important;}';
            echo "\n.tnb_widget_body .jp-playlist a.jp-playlist-current{color: ".$this->css['link-color'].'; font-weight:bold; text-decoration:none !important;}';
            echo "\ndiv.jp-type-playlist div.jp-playlist a.jp-playlist-current{color: ".$this->css['link-color'].'; font-weight:bold; text-decoration:none !important;}';
        }
        if($this->css['link-hover-color']){
            echo "\n.tnb_widget a:hover{ color: ".$this->css['link-hover-color'].'; text-decoration:none !important;}';
            echo "\n.tnb_widget_body .jp-playlist a:hover{ color: ".$this->css['link-hover-color'].'; text-decoration:none !important;}';
            echo "\ndiv.jp-type-playlist div.jp-playlist a:hover{ color: ".$this->css['link-hover-color'].'; text-decoration:none !important;}';
        }
        
        
        if( $this->css['widget-header-font-color'] || $this->css['widget-header-background-color']){
            echo "\n.tnb_widget_header{ ";
                if($this->css['widget-header-font-color']) echo "color: ".$this->css['widget-header-font-color'].'; ';
                if($this->css['widget-header-background-color']) echo "background: ".$this->css['widget-header-background-color'].'; ';
            echo "}";
            
            echo "\n.tnb_widget .button{ ";
                if($this->css['widget-header-font-color']) echo "color: ".$this->css['widget-header-font-color'].' !important; ';
                if($this->css['widget-header-background-color']) echo "background: ".$this->css['widget-header-background-color'].'; ';
            echo "}";
            
            echo "\n.tnb_widget .button:hover{ 
            		text-decoration:underline; ";
                if($this->css['widget-header-font-color']) echo "color: ".$this->css['widget-header-font-color'].' !important; ';
                if($this->css['widget-header-background-color']) echo "background: ".$this->css['widget-header-background-color'].'; ';

            echo "}";
            
        }
        
        
        echo "</style>";
        
    }
}

