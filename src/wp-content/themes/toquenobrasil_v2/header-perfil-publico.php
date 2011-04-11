<?php 
global $widget_group, $curauth;

$capabiltyPropertyName = $wpdb->prefix.'capabilities';

if(array_key_exists('artista', $curauth->$capabiltyPropertyName)){
    $widgets_padrao = array(
        'left' => array('Widget_Infos_Artista'),
        'right' => array('Widget_Fotos', 'Widget_Player', 'Widget_Videos')
    );
    $widget_classes = array('Widget_Texto', 'Widget_Infos_Artista', 'Widget_Fotos', 'Widget_Facebook', 'Widget_Eventos_Artista', 'Widget_RSS', 'Widget_Videos', 'Widget_Player', 'Widget_Twitter', 'Widget_Mural');
}else{
    $widgets_padrao = array(
        'left' => array('Widget_Infos_Produtor'),
        'right' => array('Widget_Eventos_Produtor', 'Widget_Fotos')
    );
    $widget_classes = array('Widget_Texto', 'Widget_Infos_Produtor', 'Widget_Fotos', 'Widget_Facebook', 'Widget_Eventos_Produtor', 'Widget_RSS', 'Widget_Videos', 'Widget_Player', 'Widget_Twitter', 'Widget_Mural');
}



$widget_group = new TNB_WidgetContainerGroup('principal', array('left', 'right'), $curauth->ID, $widget_classes,$widgets_padrao);
$widget_group->do_actions();
//_pr(get_bloginfo('stylesheet_directory').'/img/header-perfil-publico.png');
$perfil_header = get_user_meta($curauth->ID, '_header_css',true);
if(!$perfil_header){
    $perfil_header = array(
                'color' => '',
                'image_url' => get_bloginfo('stylesheet_directory').'/img/header-perfil-publico.png',
                'exibir_nome' => true,
                'font-color' => '#000000',
                'text_position' => 'top-left'
            );
    add_user_meta($curauth->ID, "_header_css", $perfil_header);
}

if($widget_group->editable() && isset($_POST['tnb_header_action']) && $_POST['tnb_header_action'] = "save"){
    
    $acceptedFormats = array('image/gif', 'image/png', 'image/jpeg',
                                      'image/pjpeg', 'image/x-png');
            
    if (isset($_FILES['header_image']) and in_array($_FILES['header_image']['type'], $acceptedFormats)) {
        //_pr($_FILES,true);
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        require_once(ABSPATH . '/wp-admin/includes/media.php');
        require_once(ABSPATH . '/wp-admin/includes/image.php');
        
        $si = new SimpleImage();
        
        $si->load($_FILES['header_image']['tmp_name']);
        
        $si->resizeToWidth(922);
        $si->save($_FILES['header_image']['tmp_name']);
        
        $post_data = array(
        	'post_author' => $curauth->ID,
            'post_title' => 'header_background_image'
        );
        $postid = media_handle_upload('header_image', null, $post_data);
        add_post_meta($postid, '_image_type', 'header');
        
        $perfil_header['image_url'] = wp_get_attachment_url($postid);

    }elseif($_POST['color'] != $perfil_header['color']){
       $perfil_header['color'] = $_POST['color'];
       $perfil_header['image_url'] = '';
    }
    
    $perfil_header['exibir_nome'] = isset($_POST['exibir_nome']);
    if($_POST['font_color'] != $perfil_header['font-color'])
        $perfil_header['font-color'] = $_POST['font_color'];
        
    $perfil_header['text_position'] = $_POST['text_position'];
    update_user_meta($curauth->ID, '_header_css', $perfil_header);
        
}
//var_dump($curauth);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title>
            <?php
                global $page, $paged;
                wp_title( '|', true, 'right' );
                bloginfo( 'name' );

                if ( $paged >= 2 || $page >= 2 )
                    echo ' | ' . sprintf( __( 'Page %s', 'tnb' ), max( $paged, $page ) );
            ?>
        </title>
        
        
        <?php if($widget_group->editable()): ?>
        
            <style>
            .tnb_widget_header {cursor: move; }
            </style>
        
        <?php endif; ?>
        
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
        <!--[if IE]>
            <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie.css" />
            <script src="<?php bloginfo('stylesheet_directory'); ?>/js/html5.js" type="text/javascript" charset="utf-8"></script>
        <![endif]-->
        <!--[if IE 7]>
            <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie7.css" />
        <![endif]-->

        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link type="image/x-icon" href="<?php echo get_theme_image('favicon.ico'); ?>" rel="shortcut icon" />
  
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/js/colorpicker/css/colorpicker.css" type="text/css" />
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/widgets.css" type="text/css" />
        
        <?php
            if ( is_singular() && get_option( 'thread_comments' ) )
                wp_enqueue_script( 'comment-reply' );
            wp_head();
        ?>
      
        <?php $widget_group->__print_css(); ?>
        <style>
        <?php if(!$perfil_header['image_url']):?> 
        .header-perfil-publico { background: <?php echo $perfil_header['color']; ?> }
        <?php endif;?>
        .header-perfil-publico h1 {position:absolute; width:920px; color: <?php echo $perfil_header['font-color']; ?>; <?php
        switch($perfil_header['text_position']){
            case 'top-center':
                echo 'top:11px; text-align:center;';
            break;
            
            case 'bottom-left':
                echo 'bottom:11px; text-align:left;';
            break;
            
            case 'bottom-center':
                echo 'bottom:11px; text-align:center;';
            break;
            
            default:
                echo 'top:11px; text-align:left;';
            break;
            
            
         } 
        ?>}
        </style>
    </head>

    <body <?php body_class(); ?>>
        
        
    
        <?php //echo '<pre>'; die(print_r($curauth));?>
        <div id="wrapper" class="container_16 clearfix">
            
            <?php $widget_group->__print(); ?>
            
            <div id='perfil_header' class="grid_16 header-perfil-publico<?php if($perfil_header['image_url']):?> tnb_widget<?php endif;?>" style='min-height:132px; height:auto;'>
                <div class="box" style="position:relative; min-height:132px; z-index:0">
                    
                    <?php if($perfil_header['image_url']):?>
                        <img alt="<?php echo htmlentities(utf8_decode($curauth->display_name))?>" src="<?php echo $perfil_header['image_url']?>" style='border-radius:5px; display:block; width:923px; -moz-border-radius:5px;'/>
                    <?php endif;?> 
                    <?php if($perfil_header['exibir_nome']):?>
                        <h1><?php echo $curauth->display_name?></h1>
                    <?php endif; ?>
                    <?php if($widget_group->editable()): ?>    
                    <button class='config-button' style='position:absolute; right:22px; top:11px;' onclick="jQuery('#perfil_header').fadeOut('fast',function(){_widget_open_menu_id = ''; jQuery('#perfil_header_form').fadeIn('fast');})" >
                        <?php _e('editar','tnb')?>
                    </button>
                    <?php endif;?>
                </div>
                <!-- .box -->
            </div>
            <!-- #perfil_header -->
            
           <?php if($widget_group->editable()): ?>
                <div id='perfil_header_form' class="grid_16">
                    <div class="box">
                        <form method="post" enctype="multipart/form-data" class="perfil_header_form-options">
                            <p class="alignright bottom">
                                <button class='config-button' onclick="jQuery('#perfil_header_form').fadeOut('fast',function(){_widget_open_menu_id = ''; jQuery('#perfil_header').fadeIn('fast');}); return false;" >
                                    <?php _e('cancelar','tnb')?>
                                </button>
                                 <button class='config-button' onclick="this.form.submit();">
                                     <?php _e('alterar', 'tnb'); ?>
                                 </button>
                             </p>
                             
                             <div class="exibir-nome alignleft">
                                 <input type='checkbox' id='hf_nome' name='exibir_nome' value="1" <?php if($perfil_header['exibir_nome']) echo 'checked="checked"'; ?> /><label for='hf_nome'><?php _e('Exibir nome')?></label>
                             </div>
                             
                            <div class="exibir-nome-opcoes alignleft">
                                <span id="hf-font-color-selector">
                                    <span class='widget_colorpicker' style='background-color:<?php echo $perfil_header['font-color'];?>;'>&nbsp;</span>
                                    <input type='hidden' id='hf-font-color' name='font_color' value='<?php echo $perfil_header['font-color'];?>'/>
                                </span>
                                <!-- #hf-font-color-selector -->

                                <select id="hf-select-h1-position" name='text_position'>
                                    <option value='top-left'<?php if($perfil_header['text_position'] == 'top-left') echo ' selected="selected"'; ?>><?php _e('topo - esquerda','tnb')?></option>
                                    <option value='top-center'<?php if($perfil_header['text_position'] == 'top-center') echo ' selected="selected"'; ?>><?php _e('topo - centro','tnb')?></option>
                                    <option value='bottom-left'<?php if($perfil_header['text_position'] == 'bottom-left') echo ' selected="selected"'; ?>><?php _e('base - esquerda','tnb')?></option>
                                    <option value='bottom-center'<?php if($perfil_header['text_position'] == 'bottom-center') echo ' selected="selected"'; ?>><?php _e('base - centro','tnb')?></option>
                                </select>

                            </div>
                            <!-- .exibir-nome-opcoes -->

                            <div class="fundo-label alignleft"><?php _e('Fundo', 'tnb'); ?></div>
                            <div class="fundo-opcoes alignleft">
                                <span id="hf-color-selector">
                                    <span class='widget_colorpicker' style='background-color:<?php echo $perfil_header['color'];?>;'>&nbsp;</span>
                                    <input type='hidden' id='hf-color' name='color' value='<?php echo $perfil_header['color'];?>'/>
                                </span>
                                <!-- #hf-color-selector -->
                            
                                <span id="adicionar-imagem">
                                   <input type='hidden' name='tnb_header_action' value='save' />
                                   <input type="file" id='hf_image' name='header_image'/>
                                </span>
                                <!-- #adicionar-imagem -->
                            </div>
                            <!-- .fundo-opcoes -->

                        </form>
                    </div>
                    <!-- .box -->
                </div>
                <!-- #perfil_header_form -->
                <script type="text/javascript">
                jQuery('#hf-color-selector').ColorPicker({
                	onChange: function (hsb, hex, rgb) {
                		jQuery('#hf-color').val("#"+hex);
                		jQuery('#hf-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
                	}
                });

                jQuery('#hf-font-color-selector').ColorPicker({
                	onChange: function (hsb, hex, rgb) {
                		jQuery('#hf-font-color').val("#"+hex);
                		jQuery('#hf-font-color-selector .widget_colorpicker').css('backgroundColor', '#' + hex);
                	}
                });

                jQuery("#hf_nome").change(function(){
                    if(jQuery(this).is(':checked')){
                        jQuery(".exibir-nome-opcoes").fadeIn();
                    }else{
                    	jQuery(".exibir-nome-opcoes").fadeOut();
                    }
                        
                });

                if(!jQuery("#hf_nome").is(':checked')){
                    jQuery(".exibir-nome-opcoes").hide();
                }

                jQuery("#hf-font-color-selector, #hf-color-selector").click(function(){
                	_widget_open_menu_id = '';
                });

                
                </script>
            <?php endif;?>
            
