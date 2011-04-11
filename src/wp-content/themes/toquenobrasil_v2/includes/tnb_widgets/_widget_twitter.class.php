<?php
class Widget_Twitter extends TNB_Widget{
    public function __js_update_form_validation($form_id){
        ?>
        if(jQuery("#<?php echo $form_id?> #_twitter_user").val() == ''){
            alert("<?php _e('Por favor, informe o usuário do twitter')?>");
            return false;
        }
        <?php   
    }
    
    public static function __js_insert_form_validation($form_id){
        ?>
        if(jQuery("#<?php echo $form_id?> #_twitter_user").val() == ''){
            alert("<?php _e('Por favor, informe o usuário do twitter')?>");
            return false;
        }
        <?php   
    }
    
    public static function form_filter(){
        $_POST['num_posts'] = (is_numeric($_POST['num_posts']) && is_int(intval($_POST['num_posts'])) && intval($_POST['num_posts']) > 0) ? intval($_POST['num_posts']) : 4;
        
        return true;
    }
    
    public function getTitle(){
        // re retornar null, não aparecerá a barra de título do widget
        return $this->property['titulo'] ? $this->property['titulo'] : null;
    }
    
    protected function __output(){
        $div_id = uniqid('tweetblender');
        if (!$this->property['twitter_user'])
            return;
        $num = $this->property['num_posts'] ? $this->property['num_posts'] : 4;
        $twitter_user = str_replace('http://twitter.com/', '', $this->property['twitter_user']);
        $twitter_user = str_replace('twitter.com/', '', $twitter_user);
        
       ?> 
        <div id='<?php echo $div_id;?>'><?php 
        tweet_blender_widget(array(
            'unique_div_id' => $div_id,
            'sources' => '@'.$twitter_user,
            'refresh_rate' => 60,
            'tweets_num' => $num,
            'view_more_url' => 'http://twitter.com/tweetblender'
        ));
        ?></div>    
        <?php 
        
        
        
    }
    
    protected static function insert_form(){
        self::form();
    }
    
    protected function update_form(){
        self::form($this);
    }
    
    protected static function form($object = null) {
    
        if (is_null($object)) {
            global $curauth;
            $object = new stdClass();
            $object->property = array();
            $object->property['num_posts'] = 4;
            $object->property['twitter_user'] = $curauth->twitter[0] == '@' ? substr($curauth->twitter, 1) : $curauth->twitter;
        }
        //$user = get_user_by('id', $this->user_id);
        
        
        ?>
        <h3><?php _e('Twitter','tnb');?></h3>
        <div>
            <label><?php _e('Título', 'tnb')?>: <input type='text' name='property[titulo]' value='<?php echo htmlentities(utf8_decode($object->property['titulo'])); ?>'/></label>
        </div> 
        
        <div>
            <label><?php _e('Usuário do twitter', 'tnb')?>: @<input type='text' id='_twitter_user' name='property[twitter_user]' value='<?php echo htmlentities(utf8_decode($object->property['twitter_user'])); ?>'/></label>
        </div> 
        
        <div>
            <label><?php _e('Número de posts', 'tnb')?>: 
            <select name='property[num_posts]'>
                <?php for ($i = 1; $i < 11; $i ++): ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == $object->property['num_posts']) echo 'selected'; ?> ><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            </label>
        </div> 

        
        <?php
        
        
    
    }
    
    
    protected static function form_icon(){
        _e('Twitter','tnb');
    }

    public static function getWidgetDescription(){
        return __('Clique aqui para inserir um TNBox com as últimas entradas do seu twitter.','tnb');
    }
}
