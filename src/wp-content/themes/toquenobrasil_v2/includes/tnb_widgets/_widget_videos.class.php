<?php
class Widget_Videos extends TNB_Widget{
    protected $property = array(
        'title' => 'Vídeo',
        'exibir' => 'todas',
        'videos' => array()
    );
    
    public function __js_update_form_validation($form_id){
   
    }
    
    public static function __js_insert_form_validation($form_id){
   
    }
    
    public static function form_filter(){
        $videos = array();
        if($_POST['property']['exibir'] == 'selecionadas')
            foreach ($_POST['property']['videos'] as $key => $video_id)
                if($video_id) 
                    $videos[] = $video_id;
                    
        $_POST['property']['videos'] = $videos;    
        
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['title'] ? $this->property['title'] : null;
    }
    
    protected function __output(){
        
        $video = array();
        
        if($this->property['exibir'] == 'selecionadas'){
            $videos_full = tnb_get_artista_videos($this->user_id);
            foreach ($videos_full as $video)
                if(in_array($video->ID, $this->property['videos']))
                    $videos[] = $video;
        }else{
            $videos = tnb_get_artista_videos($this->user_id);
        
        }
        
        if(sizeof($videos) > 0) {
            foreach ($videos as $video):
                print_video_player($video->post_excerpt,440,330);
            endforeach;
        } else if($this->editable()) {
        ?>
            <div class="tnb_add_box_icons">
                <p class="text-center bottom">
                    <?php _e("Você ainda não tem nenhum vídeo. Clique <a href='".get_author_posts_url($this->user_id)."/editar/videos/'>aqui</a> para carregá-los.", "tnb"); ?>
                </p>
            </div>
        <?php
        }
    }
    
    protected static function insert_form(){
        $profileuser = get_user_by( 'slug', get_query_var('author_name') );
        $formID = uniqid('form');
        $videos = tnb_get_artista_videos($profileuser->ID);
        
        ?>
        <h3><?php _e('Vídeos','tnb'); ?></h3>
        <label>
            <?php _e('título', 'tnb'); ?>
            <input type="text" name='property[title]' value='<?php htmlentities(utf8_decode(_e('Vídeos','tnb')));?>' />
        </label>
        <hr/>
        
        <div id='<?php echo $formID?>_exibir'>
            <?php _e("quais vídeos","tnb")?>: 
            <label><input type='radio' name='property[exibir]' value='todas' checked="checked"><?php _e('todos','tnb'); ?></label> 
            <label><input type='radio' name='property[exibir]' value='selecionadas'><?php _e('somente os selecionados','tnb'); ?></label>
        </div>
        <hr/>
        <div id='<?php echo $formID?>_videos' class='hide'>
            <?php _e('selecione os vídeos que deseja exibir', 'tnb');?>:
            <div class='clearfix'>
            
            <?php foreach($videos as $video): ?>
               
                    <input type="checkbox" id='<?php echo $formID; ?>_widget_video_<?php echo $video->ID; ?>_input' name="property[videos][]" value='<?php echo htmlentities(utf8_decode($video->ID)); ?>'/>
                    <?php echo $video->post_title; ?><br/>
                    
            <?php endforeach; ?>
            </div>
        </div>
        <script type="text/javascript">
        jQuery("#<?php echo $formID?>_exibir input").change(function (){
            
            if(jQuery(this).val() == 'todas')
                jQuery("#<?php echo $formID; ?>_videos").slideUp();
            else
            	jQuery("#<?php echo $formID; ?>_videos").slideDown();
        });
        </script>
        <?php 
        
    }
    
    protected function update_form(){
        $profileuser = get_user_by( 'id', $this->user_id );
        $formID = uniqid('form');
        $videos = tnb_get_artista_videos($profileuser->ID);
        
        ?>
        <h3><?php _e('Vídeos','tnb'); ?></h3>
        <label>
            <?php _e('título', 'tnb'); ?>
            <input type="text" name='property[title]' value='<?php echo htmlentities(utf8_decode($this->property['title']));?>' />
        </label>
        <hr/>
        
        <div id='<?php echo $formID?>_exibir'>
            <?php _e("quais vídeos","tnb")?>: 
            <label><input type='radio' name='property[exibir]' value='todas' <?php if($this->property['exibir'] == 'todas') echo 'checked="checked"' ?>><?php _e('todos','tnb'); ?></label> 
            <label><input type='radio' name='property[exibir]' value='selecionadas' <?php if($this->property['exibir'] == 'selecionadas') echo 'checked="checked"' ?>><?php _e('somente as selecionadas','tnb'); ?></label>
        </div>
        <hr/>
        <div id='<?php echo $formID?>_videos' <?php if($this->property['exibir'] == 'todas') echo 'class="hide";'?>>
            <?php _e('selecione os vídeos que deseja exibir', 'tnb');?>:
            <div class='clearfix'>
            
            <?php foreach($videos as $video): ?>
               
                    <input type="checkbox" id='<?php echo $formID; ?>_widget_video_<?php echo $video->ID; ?>_input' name="property[videos][]" value='<?php echo $video->ID; ?>' <?php if(in_array($video->ID, $this->property['videos'])) echo 'checked'; ?> />
                     <?php echo $video->post_title; ?><br/>
                    
               
            <?php endforeach; ?>
            </div>
        </div>
        <script type="text/javascript">
        

        jQuery("#<?php echo $formID?>_exibir input").change(function (){
            
            if(jQuery(this).val() == 'todas')
                jQuery("#<?php echo $formID; ?>_videos").hide('drop');
            else
            	jQuery("#<?php echo $formID; ?>_videos").show('drop');
        });
        </script>
        <?php 
        
    }
    
    
    protected static function form_icon(){
        _e('Player','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um TNBox com vídeos.','tnb');
    }
}
