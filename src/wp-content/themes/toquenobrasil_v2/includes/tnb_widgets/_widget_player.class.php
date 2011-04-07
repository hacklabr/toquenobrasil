<?php
class Widget_Player extends TNB_Widget{
    protected $property = array(
        'title' => 'Músicas',
        'exibir' => 'todas',
        'musicas' => array()
    );
    
    public function __js_update_form_validation($form_id){
    }
    
    public static function __js_insert_form_validation($form_id){
    }
    
    public static function form_filter(){
        $musicas = array();
        if($_POST['property']['exibir'] == 'selecionadas')
            foreach ($_POST['property']['musicas'] as $key => $musica_id)
                if($musica_id) 
                    $musicas[] = $musica_id;
                    
        $_POST['property']['musicas'] = $musicas;   
         
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['title'] ? $this->property['title'] : null;
    }
    
    protected function __output(){
        
        $musicas = array();
        
        if($this->property['exibir'] == 'selecionadas'){
            $musicas = $this->property['musicas'];
        }else{
            $musicas_full = tnb_get_artista_musicas($this->user_id);
            foreach($musicas_full as $m)
                $musicas[] = $m->ID;
        }
        if(sizeof($musicas) > 0) {
            printFullPlayer(ids2playlist($musicas));
        } else if($this->editable()) {
        ?>
            <div class="tnb_add_box_icons">
                <p class="text-center bottom">
                    <?php _e("Você ainda não tem nenhuma música! Clique <a href='".get_author_posts_url($this->user_id)."/editar/musicas/'>aqui</a> para carregá-las", "tnb"); ?>
                </p>
            </div>
        <?php
        }
    }
    
    protected static function insert_form(){
        $profileuser = get_user_by( 'slug', get_query_var('author_name') );
        $formID = uniqid('form');
        $musicas = tnb_get_artista_musicas($profileuser->ID);
        
        ?>
        <h3><?php _e('Músicas','tnb'); ?></h3>
        <label>
            <?php _e('título', 'tnb'); ?>
            <input type="text" name='property[title]' value='<?php _e("Músicas")?>' />
        </label>
        <hr/>
        
        <div id='<?php echo $formID?>_exibir'>
            <?php _e("quais músicas","tnb")?>: 
            <label><input type='radio' name='property[exibir]' value='todas' checked="checked"><?php _e('todas','tnb'); ?></label> 
            <label><input type='radio' name='property[exibir]' value='selecionadas'><?php _e('somente as selecionadas','tnb'); ?></label>
        </div>
        <hr/>
        <div id='<?php echo $formID?>_musicas' class='hide'>
            <?php _e('selecione as músicas que deseja exibir', 'tnb');?>:
            <div class='clearfix'>
            
            <?php foreach($musicas as $musica): ?>
                <input type="checkbox" id='<?php echo $formID; ?>_widget_musica_<?php echo $musica->ID; ?>_input' name="property[musicas][]" value='<?php echo $musica->ID; ?>'  />
                <?php echo $musica->post_title; ?><br/>
            <?php endforeach; ?>
            </div>
        </div>
        <script type="text/javascript">

        jQuery("#<?php echo $formID?>_exibir input").change(function (){
            
            if(jQuery(this).val() == 'todas')
                jQuery("#<?php echo $formID; ?>_musicas").slideUp();
            else
            	jQuery("#<?php echo $formID; ?>_musicas").slideDown();
        });
        </script>
        <?php 
        
    }
    
    protected function update_form(){
        $profileuser = get_user_by( 'id', $this->user_id );
        $formID = uniqid('form');
        $musicas = tnb_get_artista_musicas($profileuser->ID);
        
        ?>
        <h3><?php _e('Músicas','tnb'); ?></h3>
        <label>
            <?php _e('título', 'tnb'); ?>
            <input type="text" name='property[title]' value='<?php echo htmlentities(utf8_decode($this->property['title']));?>' />
        </label>
        <hr/>
        
        <div id='<?php echo $formID?>_exibir'>
            <?php _e("quais músicas","tnb")?>: 
            <label><input type='radio' name='property[exibir]' value='todas' <?php if($this->property['exibir'] == 'todas') echo 'checked="checked"' ?>><?php _e('todas','tnb'); ?></label> 
            <label><input type='radio' name='property[exibir]' value='selecionadas' <?php if($this->property['exibir'] == 'selecionadas') echo 'checked="checked"' ?>><?php _e('somente as selecionadas','tnb'); ?></label>
        </div>
        <hr/>
        <div id='<?php echo $formID?>_musicas' <?php if($this->property['exibir'] == 'todas') echo 'class="hide";'?>>
            <?php _e('selecione as músicas que deseja exibir', 'tnb');?>:
            <div class='clearfix'>
            
            <?php foreach($musicas as $musica): ?>

                <input type="checkbox" id='<?php echo $formID; ?>_widget_musica_<?php echo $musica->ID; ?>_input' name="property[musicas][]" value='<?php echo $musica->ID; ?>' <?php if(in_array($musica->ID, $this->property['musicas'])) echo 'checked'; ?> />
                <?php echo $musica->post_title; ?><br/>
                
            <?php endforeach; ?>
            </div>
        </div>
        <script type="text/javascript">

        jQuery("#<?php echo $formID?>_exibir input").change(function (){
            
            if(jQuery(this).val() == 'todas')
                jQuery("#<?php echo $formID; ?>_musicas").slideUp();
            else
            	jQuery("#<?php echo $formID; ?>_musicas").slideDown();
        });
        </script>
        <?php 
        
    }
    
    
    protected static function form_icon(){
        _e('Player','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um aplicativo com suas músicas.','tnb');
    }
}
