<?php



global $current_user, $user_ID, $profileuser;
$profileuser = $current_user; 
// apenas artistas usam esse tpl
if(!is_user_logged_in() && !is_artista())
    wp_redirect(get_bloginfo('url'));

$profileuser = $current_user->data;

$estados = get_estados();


if(isset($_POST['action']) && $_POST['action'] == 'update' && wp_verify_nonce($_POST['_wpnonce'], 'edit_nonce' )){
    require_once( ABSPATH . WPINC . '/registration.php' );
    $profileuser_id = $user_ID;
    
    if(!filter_var( $_POST['user_email'], FILTER_VALIDATE_EMAIL))
        $msg['error'][] = __('E-mail informado inválido.','tnb');    
    
    if( $_POST['user_email'] != $profileuser->user_email && email_exists($_POST['user_email']))
         $msg['error'][] =  __('Esse e-mail já está sendo utilizado', 'tnb');
        
    if( strlen($_POST['user_pass'])>0  && $_POST['user_pass'] !=  $_POST['user_pass_confirm'] )
        $msg['error'][]= __('A senhas fornecidas não conferem.','tnb');
    
    
    
    if(strlen($_POST['site'])>0 && !filter_var($_POST['site'], FILTER_VALIDATE_URL))
        $msg['error'][]= __('O site fornecido não é válido.','tnb'); 
        
    if( !$msg['error']){
        $userdata['ID'] = $profileuser_id;
        $userdata['user_login'] = $_POST['user_login'];
        $userdata['display_name'] = $_POST['banda'];
        $userdata['user_email'] = $_POST['user_email'];
        $userdata['description'] = $_POST['description'];
        
        if(strlen($_POST['user_pass'])>0)
            $userdata['user_pass'] = wp_hash_password($_POST['user_pass']); 
        
        $rt = wp_insert_user($userdata);
        
        update_user_meta( $profileuser_id, 'banda' , $_POST['banda'] );
        update_user_meta( $profileuser_id, 'responsavel' , $_POST['responsavel'] );
        update_user_meta( $profileuser_id, 'telefone' , $_POST['telefone'] );
        update_user_meta( $profileuser_id, 'telefone_ddd' , $_POST['telefone_ddd'] );
        update_user_meta( $profileuser_id, 'site' , $_POST['site'] );
        
        update_user_meta( $profileuser_id, 'origem_estado' , $_POST['origem_estado'] );
        update_user_meta( $profileuser_id, 'origem_cidade' , $_POST['origem_cidade'] );
        
        update_user_meta( $profileuser_id, 'banda_estado' , $_POST['banda_estado'] );
        update_user_meta( $profileuser_id, 'banda_cidade' , $_POST['banda_cidade'] );
        
        update_user_meta( $profileuser_id, 'youtube' , $_POST['youtube'] );
        
        $msg['success'][] = __('Dados Atualizados', 'tnb');
        $profileuser = get_userdata( $user_ID );
    }
    
    if ($_FILES && !$msg['error']) {
        do_action('tnb_user_update', $profileuser_id);
        if(!$msg['error']){
            require_once(ABSPATH . '/wp-admin/includes/media.php');
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            require_once(ABSPATH . '/wp-admin/includes/image.php');
            $upload_dir = WP_CONTENT_DIR.'/uploads';
            $i = 0;
            foreach ($_FILES as $index=>$file){
                if($file['error'] == 4 || $index == 'userphoto_image_file')
                    continue;
                
                $type = preg_replace('/(_[0-9])/','', $index);
                $media_title = strlen($_POST["label_music"][$i])>0 ? $_POST["label_music"][$i] : $file['name'];
                
                $old_post = 
                
                $post = array(
                    "post_title" => $media_title, 
                    "post_content" => $media_title, 
                    "post_excerpt" => $media_title,
                    "post_author" => $user_ID, 
                     );
                
                     
                     
        		$acceptedFormats = array(
        		    
        			'images' => array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png'),
        			'music' => array('audio/mpeg', 'audio/x-mpeg', 'audio/mp3', 'audio/x-mp3', 'audio/mpeg3', 'audio/x-mpeg3', 'audio/mpg', 'audio/x-mpg', 'audio/x-mpegaudio'),
        			'rider' => array('application/pdf','application/x-pdf','application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' , 'image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png'),
        			'mapa_palco' => array('application/pdf','application/x-pdf','application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' , 'image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png' ),
                );
            
                $errors = array(1 => __('The file size can not exceed ','tnb') . ini_get('upload_max_filesize') . 'B',
                2 => __('The file size can not exceed ','tnb') . ini_get('upload_max_filesize') . 'B',
                3 => __('An error occurred and only part of the file was sent. Please send again.','tnb') ,
                4 => __('No file sent!','tnb'));    
            
                if ($file['error'] == 0) {
                    
                	$file['name'] = sanitize_file_name($file['name']);
                	
                	if (in_array($file['type'], $acceptedFormats[$type])) {
                        $media_id = media_handle_upload($index, '', $post);
                        if ($media_id->errors)
                            $msg['error'][] = implode(' ', $media_id->errors['upload_error']);
                    } else {
                        $msg['error'][] = __('File type not allowed','tnb');
                    }
                } else {
                    $msg['error'][] = $errors[$file['error']];
                }
                   
                if (!$msg['error']) {
                    $meta = get_post_meta($media_id, '_wp_attached_file');
                    $sizes = get_media_file_sizes($upload_dir ."/". $meta[0]);
                    
                    $msg['success'][] = __('File','tnb') . "
                        <span id='videoName' class='cor_rede'> {$media_title}</span> 
                        " . __('insert with success!','tnb') ;
                    
                    $update = array(
                        'ID' => $media_id,
                        'post_type' => $type,
                        'post_status' => 'publish',
                        'tags_input' => $_POST['media_tags']
                    );
                    
                    if ($type == 'music') {
        	            if (!update_post_meta($media_id, '_filesize', $sizes['filesize'], true))
        	            	add_post_meta($media_id, '_filesize', $sizes['filesize'], true); 
        	            if (!update_post_meta($media_id, '_playtime', $sizes['playtime'], true))
        	            	add_post_meta($media_id, '_playtime', $sizes['playtime'], true); 
                    }
                    
                    // remove the file for these position
                    $old_entrie = get_posts("post_type={$type}&meta_key=_media_index&meta_value={$index}&author={$user_ID}");
                	if(sizeof($old_entrie)>0){
                	    foreach($old_entrie as $p)
                	        wp_delete_post($p->ID);    
                	}
                    
                    add_post_meta($media_id, '_media_index', $index);
        			wp_update_post($update);
        			
                    
                
                } else {
                	$feedback = __('ERROR','tnb') . ": " . __('files ','tnb') . "
                        <span id='videoName' class='cor_rede'> {$media_title}</span> 
                        " . __('not inserted!','tnb');
                	if ($error) {
                	    $feedback .= "<br>" . __('Erro','tnb') . ": $error";	
                	} else {
                		$feedback .= __(' try again.','tnb');
                	}
                	$msg['error'][] = $feedback;
                }
                $i++;
            }// end for
        }    
    }
    
    // labels
    
    for ( $i = 0; $i < count($_POST["label_music"]); $i++){
        if(strlen($_POST["label_music"][$i])>0 && $_POST["id_music"][$i]>0 ){
            $post = get_post($_POST["id_music"][$i]);
            wp_update_post( array("ID"=>$_POST["id_music"][$i] , "post_title"=>$_POST["label_music"][$i]));
        }
    }
}


do_action('custom_profile_update', $profileuser->ID);
get_header();




?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
	<div class="item green">
		<div class="title pull-1">
			<div class="shadow"></div>
			<h1><?php _e('Editando perfil', 'tnb');?></h1>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>

<?php print_msgs($msg);?>
	<div class="clear"></div>

	<form class="background clearfix" method="post" enctype="multipart/form-data" id="your-profile" >
		<input type="hidden" name="action" value="update" />
	    <?php wp_nonce_field('edit_nonce'); ?>
    	<h2><?php _e('Informações de login', 'tnb');?></h2>
    	<p class="clearfix prepend-1">
      		<label for="user_login"><?php _e('Nome de usuário', 'tnb');?></label>
		    <br/>
      		<input type="text" id="user_login" name="user_login" disabled='disabled' value="<?php echo $profileuser->user_login; ?>" class="text span-12" />
    	</p>
    	<p class="clearfix prepend-1">
      		<label for="user_pass"><?php _e('Senha', 'tnb');?></label>
      		<br/>
      		<input type="password" id="user_pass" name="user_pass"  class="text span-12" />
    	</p>
    	<p class="clearfix prepend-1">
			<label for="user_pass_confirm"><?php _e('Confirmação da senha', 'tnb');?></label>
			<br/>
			<input type="password" id="user_pass_confirm" name="user_pass_confirm"  class="text span-12" />
		</p>

    	<h2><?php _e('Informações de contato', 'tnb');?></h2>
    	<p class="clearfix prepend-1">
			<label for="responsable"><?php _e('Responsável', 'tnb');?></label>
			<br/>
			<input type="text" id="responsavel" name="responsavel" value="<?php echo $profileuser->responsavel; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="user_email"><?php _e('E-mail', 'tnb');?></label>
			<br/>
			<input type="text" id="user_email" name="user_email" value="<?php echo $profileuser->user_email; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="phone"><?php _e('Telefone', 'tnb');?></label>
			<br/>
			<input type="text" id="telefone_" name="telefone_ddd" value="<?php echo $profileuser->telefone_ddd; ?>" class="text span-1 margin-right" /> <input type="text" id="telefone" name="telefone" value="<?php echo $profileuser->telefone; ?>" class="text span-5"/>
		</p>
		

		<h2><?php _e('Informações da banda', 'tnb');?></h2>
		
		<div class="prepend-1 clearfix">
          <?php do_action('custom_edit_user_profile'); ?>
        </div>
		
		<p class="prepend-1 clearfix">
			<label for="banda"><?php _e('Nome da banda', 'tnb');?></label>
			<br/>
			<input type="text" id="banda" name="banda" value="<?php echo $profileuser->banda; ?>" class="text span-12" />
		</p>
		
		
		<h4><?php _e('Origem da banda', 'tnb');?></h4>
		<p class="clearfix prepend-1">
			<label for="origem_estado"><?php _e('Estado', 'tnb');?></label>
			<br />
			<select name="origem_estado">                            
				 <?php 
                    foreach($estados as $uf=>$name){
                        echo "<option " . ($profileuser->origem_estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";    
                    }
                ?>
			</select>
		</p>
		
		<p class="clearfix prepend-1">	
			<label for="origem_cidade"><?php _e('Cidade', 'tnb');?></label>
			<br />
			<input class="span-6 text" type="text" id="origem_cidade" name="origem_cidade" value="<?php echo $profileuser->origem_cidade; ?>" />
		</p>
		
		
		<h4><?php _e('Residência da banda', 'tnb');?></h4>
		<p class="clearfix prepend-1">
			<label for="banda_estado"><?php _e('Estado', 'tnb');?></label>
			<br />
			<select name="banda_estado">                            
				 <?php 
                    foreach($estados as $uf=>$name){
                        echo "<option " . ($profileuser->banda_estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";    
                    }
                ?>
			</select>
		</p>
		
		<p class="clearfix prepend-1">	
			<label for="banda_cidade"><?php _e('Cidade', 'tnb');?></label>
			<br />
			<input class="span-6 text" type="text" id="banda_cidade" name="banda_cidade" value="<?php echo $profileuser->banda_cidade; ?>" />
		</p>
		
		
		<p class="clearfix prepend-1">
			<label for="site"><?php _e('Site', 'tnb');?></label>
			<br/>
			<input type="text" id="site" name="site" value="<?php echo $profileuser->site; ?>" class="text span-12" />
			<small><?php _e('Use http://', 'tnb');?></small>
		</p>
		<p class="clearfix prepend-1">
			<label for="youtube"><?php _e('URL do vídeo no YouTube', 'tnb');?></label>
			<br/>
			<input type="text" id="youtube" name="youtube" value="<?php echo $profileuser->youtube; ?>" class="text span-12" />
		</p>
		
		<p class="clearfix prepend-1">
			<label for="description"><?php _e('Release', 'tnb');?></label>
			<br/>
			<textarea  id="description" name="description" class="span-12" ><?php echo $profileuser->description; ?></textarea>
		</p>
			
		
		<?php for($i = 1; $i<=3; $i++): ?>
		<p class="clearfix prepend-1">
			<label for="music"><?php _e('Música', 'tnb');?> #<?php echo $i;?> <?php _e('(Formato: MP3)', 'tnb');?></label> 
        		<?php 
        		        $media = get_posts("post_type=music&meta_key=_media_index&meta_value=music_{$i}&author={$user_ID}");
        		        
        		        if(isset($media[0])){
        		            echo '<br />';
        		            $media  = $media[0];
        		            print_audio_player($media->ID);
        		            echo $media->post_title;
        		        }
        		?>
    		
    			
    			<br/>
    			<input type="hidden" name="id_music[]" value="<?php echo $media->ID; ?>" /><br/>
    			<input type="text" id="music_title" name="label_music[]" value="<?php echo $media->post_title; ?>" class="text span-12" /><br/>
    			<input type="file" id="music" name="music_<?php echo $i;?>" value="" class="text span-12" />
    			
    		</p>
		<?php endfor;?>
		
		
		<?php for($i = 1; $i<=2; $i++):?>
        	<p class="clearfix prepend-1">
    			<label for="photo"><?php _e('Foto', 'tnb');?> #<?php echo $i;?> <?php _e('(Formato: JPG, PNG, GIF)', 'tnb');?></label>
    			<?php 
        		        $media = get_posts("post_type=images&meta_key=_media_index&meta_value=images_{$i}&author={$user_ID}");
        		        
        		        if(isset($media[0])){
        		            echo '<br />';
        		            $media  = $media[0];
        		            
        		            $meta = get_post_meta($media->ID, '_wp_attachment_metadata');


                            preg_match('/(\d{4}\/\d\d\/).+/', $meta[0]['file'], $folder);
                            $images_url = get_option('siteurl') . '/wp-content/uploads/';
                            
                            if (isset($meta[0]['sizes']) && array_key_exists('thumbnail', $meta[0]['sizes'])) {
                                $thumb = $folder[1] . $meta[0]['sizes']['thumbnail']['file'];
                            } else {
                                $thumb = $meta[0]['file'];
                            }
                            
                            if (isset($meta[0]['sizes']) && array_key_exists('medium', $meta[0]['sizes'])) {
                                $medium = $folder[1] . $meta[0]['sizes']['medium']['file'];
                            } else {
                            	$medium = $meta[0]['file'];
                            }
                            
                            if (isset($meta[0]['sizes']) && array_key_exists('large', $meta[0]['sizes'])) {
                                $large = $folder[1] . $meta[0]['sizes']['large']['file'];
                            } else {
                            	$large = $meta[0]['file'];
                            }
                                
                            $thumburl = $images_url . $thumb;
                            $mediumurl = $images_url . $medium;
                            $largeurl = $images_url . $large;
        		            
        		            echo "<img src='" . $mediumurl ."'>";
        		            echo $media->post_title;
        		        }
        		?>
    			<br/>
    			<input type="file" id="images" name="images_<?php echo $i;?>" value="" class="text span-12" />
    		</p>
		<?php endfor;?>
		
		<?php for($i = 1; $i<=1; $i++): ?>
		<p class="clearfix prepend-1">
			<label for="music"><?php _e('Rider (Formato: PDF, DOC, ODT, JPG, PNG, GIF)', 'tnb');?></label> 
        		<?php 
        		        $media = get_posts("post_type=rider&meta_key=_media_index&meta_value=rider_{$i}&author={$user_ID}");
        		        
        		        if(isset($media[0])){
        		            $media  = $media[0];
                            echo '<br />';
        		            echo "<a href='{$media->guid}'>ARQUIVO</a>";
        		            echo '<br />';
        		            echo $media->post_title;
        		        }
        		?>
    		
    			
    			<br/>
    			<input type="file" id="rider" name="rider_<?php echo $i;?>" value="" class="text span-12" />
    		</p>
		<?php endfor;?>
		
		<?php for($i = 1; $i<=1; $i++): ?>
		<p class="clearfix prepend-1">
			<label for="music"><?php _e('Mapa do palco (Formato: PDF, DOC, ODT, JPG, PNG, GIF)', 'tnb');?></label> 
        		<?php 
        		        $media = get_posts("post_type=mapa_palco&meta_key=_media_index&meta_value=mapa_palco_{$i}&author={$user_ID}");
        		        
        		        if(isset($media[0])){
        		            $media  = $media[0];
        		            echo '<br />';
        		            echo "<a href='{$media->guid}'>ARQUIVO</a>";
        		            echo '<br />';
        		            echo $media->post_title;
        		        }
        		?>
    		
    			
    			<br/>
    			<input type="file" id="mapa_palco" name="mapa_palco_<?php echo $i;?>" value="" class="text span-12" />
    		</p>
		<?php endfor;?>
		
		<p class="clearfix textright">
			<input type="submit" value="<?php _e('Salvar', 'tnb');?>" />
			<a href="" class="button"><?php _e('Cancelar', 'tnb');?></a>
		</p>
	</form>
</div>
<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("tnb-sidebar");?>
    </div>
</div>
<?php get_footer(); ?>
