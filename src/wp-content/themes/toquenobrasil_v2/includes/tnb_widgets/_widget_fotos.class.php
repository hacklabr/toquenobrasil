<?php
class Widget_Fotos extends TNB_Widget{
    protected $property = array(
        'exibir_como' => 'slideshow',
        'title' => 'Fotos',
        'exibir_fotos' => 'todas',
        'fotos' => array()
    );
   
    public function __js_update_form_validation($form_id){
        ?>
        var quais = jQuery("#<?php echo $form_id?> input:radio[name=property[exibir_fotos]]:checked").val();
        if(quais == 'selecionadas' && jQuery("#<?php echo $form_id?> .selected").length == 0){
            alert('<?php _e('Por favor, selecione ao menos uma foto.')?>');
            return false;
        }
        <?php
    }
    
    public static function __js_insert_form_validation($form_id){
        ?>
        var quais = jQuery("#<?php echo $form_id?> input:radio[name=property[exibir_fotos]]:checked").val();
        
        if(quais == 'selecionadas' && jQuery("#<?php echo $form_id?> .selected").length == 0){
            alert('<?php _e('Por favor, selecione ao menos uma foto.')?>');
            return false;
        }
        <?php
    }
    
    public static function form_filter(){
        $fotos = array();
        if($_POST['property']['exibir_fotos'] == 'selecionadas')
            foreach ($_POST['property']['fotos'] as $key => $foto_id)
                if($foto_id) 
                    $fotos[] = $foto_id;
                    
        $_POST['property']['fotos'] = $fotos;
             
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['title'] ? $this->property['title'] : null;
    }
    
    protected function __output(){
        
        $fotos = tnb_get_artista_fotos($this->user_id);

        if($fotos) {
            if($this->property['exibir_fotos'] == 'selecionadas')
                foreach($fotos as $k => $foto)
                    if(!in_array($foto->ID, $this->property['fotos']))
                        unset($fotos[$k]);

            $galerryid = uniqid('gallery');
        
            if ($this->property['exibir_como'] == 'galeria') {
            
                foreach ($fotos as $foto) {
                    $imagelink = wp_get_attachment_image_src($foto->ID, 'large');
                    $imagelink = $imagelink[0];
                    ?>
                        <a rel="lightbox[<?php echo $galerryid; ?>]" title="<?php echo addslashes($foto->post_title); ?>" class="foto" href="<?php echo $imagelink; ?>">
                            <?php echo wp_get_attachment_image($foto->ID, array(130,130)); ?>
                        </a>
                    <?php
                }
        
            } else {
        
            ?>
                <div id="<?php echo $galerryid; ?>" class="slideshow">
                    <?php foreach ($fotos as $foto) : ?>
                        <?php echo wp_get_attachment_image($foto->ID, array(500,500)); ?>
                    <?php endforeach; ?>
                </div>
            <?php
            }            
        } else if($this->editable()) {
            ?>
                <div class="tnb_add_box_icons">
                    <p class="text-center bottom">
                        <?php _e("Você ainda não tem nenhuma foto!. Clique <a href='".get_author_posts_url($this->user_id)."/editar/fotos/'>aqui</a> para carregá-las", "tnb"); ?>
                    </p>
                </div>
            <?php
        }
        
    }
    
    protected static function insert_form(){
        $profileuser = get_user_by( 'slug', get_query_var('author_name') );
        $formID = uniqid('form');
        $fotos = tnb_get_artista_fotos($profileuser->ID);
        
        ?>
        <h3><?php _e('Fotos', 'tnb')?></h3>
        <div id='<?php echo $formID; ?>_ecomo'>
            <?php _e("exibir fotos como","tnb")?>: 
            <label><input type='radio' name='property[exibir_como]' value='galeria' checked="checked"><?php _e('galeria','tnb'); ?></label> 
            <label><input type='radio' name='property[exibir_como]' value='slideshow'><?php _e('slideshow','tnb'); ?></label>
        </div>
        <hr/>
        
        <label id='<?php echo $formID; ?>_title_label' for='<?php echo $formID; ?>_title'><?php _e('título da galeria', 'tnb'); ?>: </label>
        <input type="text" id='<?php echo $formID; ?>_title' name='property[title]' value='' />       
        <hr/>
        
        <div id='<?php echo $formID; ?>_efotos'>
            <?php _e("exibir","tnb")?>: 
            
            <label><input type='radio' name='property[exibir_fotos]' value='todas' checked="checked"><?php _e('todas as fotos','tnb'); ?></label>
            <label><input type='radio' name='property[exibir_fotos]' value='selecionadas'><?php _e('somente as fotos selecionadas','tnb'); ?></label>
        </div>
        <hr/>
        <div id='<?php echo $formID; ?>_fotos' class='hide'>
            <?php _e('clique nas fotos para selecionar as que deseja exibir', 'tnb');?>:
            <div class='clearfix'>
            
            <?php foreach($fotos as $foto): ?>
                <div id="<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>" class='foto' >
                    <input type="hidden" id='<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input' name="property[fotos][]" value=''/>                      
                    <?php echo wp_get_attachment_image($foto->ID, array(130,130));?>                    
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <script type="text/javascript" >
        <?php foreach($fotos as $foto): ?>
            jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>").click(function(){
                
                if(jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input").val() == ''){
                    jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input").val("<?php echo $foto->ID; ?>");
                    jQuery(this).addClass('selected');
                }else{
                	jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input").val("");
                    jQuery(this).removeClass('selected');
                }
            });
        <?php endforeach;?>

        jQuery('#<?php echo $formID; ?>_ecomo input').change(function(){
            if(jQuery(this).val() == 'galeria')
                jQuery("#<?php echo $formID; ?>_title_label").html('<?php _e('título da galeria')?>: ');
            else
            	jQuery("#<?php echo $formID; ?>_title_label").html('<?php _e('título do slideshow')?>: ');
        });
        
        jQuery('#<?php echo $formID; ?>_efotos input').change(function(){
            if(jQuery(this).val() == 'todas')
                jQuery("#<?php echo $formID; ?>_fotos").slideUp();
            else
            	jQuery("#<?php echo $formID; ?>_fotos").slideDown();
        });
        </script>
        <?php 
        
    }
    
    protected function update_form(){
        $formID = uniqid('form');
        $fotos = tnb_get_artista_fotos($this->user_id);
        ?>
        <h3><?php _e('Fotos', 'tnb')?></h3>
        <div id='<?php echo $formID; ?>_ecomo'>
            <?php _e("exibir fotos como","tnb")?>: 
            <label><input type='radio' name='property[exibir_como]' value='galeria' <?php if($this->property['exibir_como'] == 'galeria') echo 'checked="checked"' ?>><?php _e('galeria','tnb'); ?></label> 
            <label><input type='radio' name='property[exibir_como]' value='slideshow' <?php if($this->property['exibir_como'] == 'slideshow') echo 'checked="checked"' ?>><?php _e('slideshow','tnb'); ?></label>
        </div>
        <hr/>
        
        <label id='<?php echo $formID; ?>_title_label' for='<?php echo $formID; ?>_title'><?php if($this->property['exibir_como'] == 'galeria') _e('título da galeria', 'tnb'); else   _e('título do slideshow', 'tnb');?>: </label>
        <input type="text" name='property[title]' value='<?php echo htmlentities(utf8_decode($this->property['title'])); ?>' />
        
        <hr />
        
        <div id='<?php echo $formID; ?>_efotos'>
            <?php _e("quais fotos exibir","tnb")?>: 
            <label><input type='radio' name='property[exibir_fotos]' value='todas' <?php if($this->property['exibir_fotos'] == 'todas') echo 'checked="checked"' ?>><?php _e('todas','tnb'); ?></label> 
            <label><input type='radio' name='property[exibir_fotos]' value='selecionadas' <?php if($this->property['exibir_fotos'] == 'selecionadas') echo 'checked="checked"' ?>><?php _e('somente as selecionadas','tnb'); ?></label>
        </div>
        <hr/>
        <div id='<?php echo $formID?>_fotos' <?php if($this->property['exibir_fotos'] == 'todas') echo 'class="hide"'?>>
       <?php _e('clique nas fotos para selecionar as que deseja exibir', 'tnb');?>:
        <div class='clearfix<?php if($this->property['exibir_fotos'] == 'todas') echo 'hide'; ?>'>
        
        <?php foreach($fotos as $foto): ?>
            <div id="<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>" class='<?php if(in_array($foto->ID, $this->property['fotos'])) echo "selected ";?>foto'>
                <input type="hidden" id='<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input' name="property[fotos][]" value='<?php if(in_array($foto->ID, $this->property['fotos'])) echo $foto->ID?>' />                  
                <?php echo wp_get_attachment_image($foto->ID, array(130,130));?>
                
            </div>
        <?php endforeach; ?>
        </div>
        </div>
        <script type="text/javascript">
        <?php foreach($fotos as $foto): ?>
            jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>").click(function(){
                
                if(jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input").val() == ''){
                    jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input").val("<?php echo $foto->ID; ?>");
                    jQuery(this).addClass('selected');
                }else{
                	jQuery("#<?php echo $formID; ?>_widget_foto_<?php echo $foto->ID; ?>_input").val("");
                    jQuery(this).removeClass('selected');
                }
            });
        <?php endforeach;?>

        jQuery('#<?php echo $formID; ?>_ecomo input').change(function(){
            if(jQuery(this).val() == 'galeria')
                jQuery("#<?php echo $formID; ?>_title_label").html('<?php _e('título da galeria')?>: ');
            else
            	jQuery("#<?php echo $formID; ?>_title_label").html('<?php _e('título do slideshow')?>: ');
        });
        
        jQuery('#<?php echo $formID; ?>_efotos input').change(function(){
            if(jQuery(this).val() == 'todas')
                jQuery("#<?php echo $formID; ?>_fotos").slideUp();
            else
            	jQuery("#<?php echo $formID; ?>_fotos").slideDown();
        });
        
        </script>
        <?php 
    }
    
    
    protected static function form_icon(){
        _e('fotos','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um aplicativo com fotos para dispor em galeria ou como slideshow.','tnb');
    }
}
