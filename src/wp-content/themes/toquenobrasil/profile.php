<?php

global $current_user, $user_ID;
// apenas artistas usam esse tpl
if(!is_user_logged_in() && !is_artista())
    wp_redirect(get_bloginfo('url'));

$profileuser = $current_user->data;

$estados = get_estados();


if(isset($_POST['action']) && $_POST['action'] == 'update' && wp_verify_nonce($_POST['_wpnonce'], 'edit_nonce' )){
    require_once( ABSPATH . WPINC . '/registration.php' );
    $profileuser_id = $user_ID;
    
    if(!filter_var( $_POST['user_email'], FILTER_VALIDATE_EMAIL))
        $msg['error'][] = __('Invalid email address.','itsnoon');    
    
    if( $_POST['user_email'] != $profileuser->user_email && email_exists($_POST['user_email']))
         $msg['error'][] =  __('This email address is already registered.', 'itsnoon');
        
    if( strlen($_POST['user_pass'])>0  && $_POST['user_pass'] !=  $_POST['user_pass_confirm'] )
        $msg['error'][]= __('Password does not match with password confirmation.','itsnoon');
    
    
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
        update_user_meta( $profileuser_id, 'estado' , $_POST['estado'] );
        update_user_meta( $profileuser_id, 'youtube' , $_POST['youtube'] );
        
        $msg['success'][] = __('Dados Atualizados', 'tnb');
        $profileuser = get_userdata( $user_ID );
    }
    
    if ($_FILES && !$msg['error']) {
        if(!$msg['error']){
            require_once(ABSPATH . '/wp-admin/includes/media.php');
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            require_once(ABSPATH . '/wp-admin/includes/image.php');
            $upload_dir = WP_CONTENT_DIR.'/uploads';
            foreach ($_FILES as $index=>$file){
                if($file['error'] == 4)
                    continue;
                
                $type = preg_replace('/(_[0-9])/','', $index);
                $media_title = $file['name'];
                
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
        			'rider' => array('application/pdf','application/x-pdf','application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ),
        			'mapa_palco' => array('application/pdf','application/x-pdf','application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ),
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
                        $msg['error'][] = __('File type not allowed','itsnoon');
                    }
                } else {
                    $msg['error'][] = $errors[$file['error']];
                }
                   
                if (!$msg['error']) {
                    $meta = get_post_meta($media_id, '_wp_attached_file');
                    $sizes = get_media_file_sizes($upload_dir ."/". $meta[0]);
                    
                    $msg['success'][] = __('File','itsnoon') . "
                        <span id='videoName' class='cor_rede'> {$media_title}</span> 
                        " . __('insert with success!','itsnoon') ;
                    
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
                	$feedback = __('ERROR','itsnoon') . ": " . __('files ','itsnoon') . "
                        <span id='videoName' class='cor_rede'> {$media_title}</span> 
                        " . __('not inserted!','itsnoon');
                	if ($error) {
                	    $feedback .= "<br>" . __('Erro','itsnoon') . ": $error";	
                	} else {
                		$feedback .= __(' try again.','itsnoon');
                	}
                	$msg['error'][] = $feedback;
                }
            }
        }    
    }
}


get_header();




?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
	<div class="item green">
		<div class="title pull-1">
			<div class="shadow"></div>
			<h1>Editando perfil</h1>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>

<?php print_msgs($msg);?>
	<div class="clear"></div>

	<form class="background clearfix" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="update" />
	    <?php wp_nonce_field('edit_nonce'); ?>
    	<h2>Informações de login</h2>
    	<p class="clearfix prepend-1">
      		<label for="username">Nome de usuário</label>
		    <br/>
      		<input type="text" id="user_login" name="user_login" value="<?php echo $profileuser->user_login; ?>" class="text span-12" />
    	</p>
    	<p class="clearfix prepend-1">
      		<label for="user_pass">Senha</label>
      		<br/>
      		<input type="text" id="user_pass" name="user_pass"  class="text span-12" />
    	</p>
    	<p class="clearfix prepend-1">
			<label for="user_pass_confirm">Confirmação da senha</label>
			<br/>
			<input type="text" id="user_pass_confirm" name="user_pass_confirm"  class="text span-12" />
		</p>

    	<h2>Informações de contato</h2>
    	<p class="clearfix prepend-1">
			<label for="responsable">Responsável</label>
			<br/>
			<input type="text" id="responsavel" name="responsavel" value="<?php echo $profileuser->responsavel; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="user_email">E-mail</label>
			<br/>
			<input type="text" id="user_email" name="user_email" value="<?php echo $profileuser->user_email; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="phone">Telefone</label>
			<br/>
			<input type="text" id="telefone_" name="telefone_ddd" value="<?php echo $profileuser->telefone_ddd; ?>" class="text span-1" /> <input type="text" id="telefone" name="telefone" value="<?php echo $profileuser->telefone; ?>" class="text span-5"/>
		</p>
		<p class="clearfix prepend-1">
			<label for="estado">Estado:</label>
			<br />
			<select name="estado">                            
				 <?php 
                    foreach($estados as $uf=>$name){
                        echo "<option " . ($profileuser->estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";    
                    }
                ?>
			</select>
		</p>

		<h2>Informações da banda</h2>
		<p class="prepend-1 clearfix">
			<label for="banda">Nome da banda</label>
			<br/>
			<input type="text" id="banda" name="banda" value="<?php echo $profileuser->banda; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="site">Site</label>
			<br/>
			<input type="text" id="site" name="site" value="<?php echo $profileuser->site; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="youtube">URL do YouTube</label>
			<br/>
			<input type="text" id="youtube" name="youtube" value="<?php echo $profileuser->youtube; ?>" class="text span-12" />
		</p>
		
		<p class="clearfix prepend-1">
			<label for="description">Resealse</label>
			<br/>
			<textarea  id="description" name="description" ><?php echo $profileuser->description; ?></textarea>
		</p>
			
		
		<?php for($i = 1; $i<=3; $i++): ?>
		<p class="clearfix prepend-1">
			<label for="music">Música #<?php echo $i;?></label> 
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
    			<input type="file" id="music" name="music_<?php echo $i;?>" value="" class="text span-12" />
    		</p>
		<?php endfor;?>
		
		
		<?php for($i = 1; $i<=2; $i++):?>
        	<p class="clearfix prepend-1">
    			<label for="photo">Foto #<?php echo $i;?></label>
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
			<label for="music">Rider</label> 
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
			<label for="music">Mapa do palco </label> 
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
			<input type="submit" value="Salvar" />
			<a href="" class="button">Cancelar</a>
		</p>
	</form>
</div>

<?php get_footer(); ?>