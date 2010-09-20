<?php

global $current_user, $user_ID;

//var_dump($current_user);

$data = $current_user->data;

$estados = get_estados();


//var_dump($_FILES);

if ($_FILES ) {
 
        
    if(!$msg['error']){
//        error_reporting(E_ALL);
        require_once(ABSPATH . '/wp-admin/includes/media.php');
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        require_once(ABSPATH . '/wp-admin/includes/image.php');
        $upload_dir = WP_CONTENT_DIR.'/uploads';
        foreach ($_FILES as $index=>$file){
            if($file['error'] == 4)
                continue;
            
            
            $type = preg_replace('/(_[0-9])/','', $index);
//            var_dump($type, $index, $file);die;
//            var_dump($files);die;
           /* for($i=0; $i<count($files); $i++){
                foreach($files as $data=>$values){
                    $file[$data] = $values[$i];    
                }*/
                $media_title = $file['name'];
                $post = array(
                    "post_title" => $media_title, 
                    "post_content" => $media_title, 
                    "post_excerpt" => $media_title,
                    "post_author" => $user_ID, 
    //                "post_category" => array($_POST['media_type'])
                     );
                
                     
                     
        		$acceptedFormats = array(
        			'images' => array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png'),
        			'music' => array('audio/mpeg', 'audio/x-mpeg', 'audio/mp3', 'audio/x-mp3', 'audio/mpeg3', 'audio/x-mpeg3', 'audio/mpg', 'audio/x-mpg', 'audio/x-mpegaudio'),
    //    			'radio' => array('audio/mpeg', 'audio/x-mpeg', 'audio/mp3', 'audio/x-mp3', 'audio/mpeg3', 'audio/x-mpeg3', 'audio/mpg', 'audio/x-mpg', 'audio/x-mpegaudio') 
                );
            
                $errors = array(1 => __('The file size can not exceed ','tnb') . ini_get('upload_max_filesize') . 'B',
                2 => __('The file size can not exceed ','tnb') . ini_get('upload_max_filesize') . 'B',
                3 => __('An error occurred and only part of the file was sent. Please send again.','tnb') ,
                4 => __('No file sent!','tnb'));
            
                if ($file['error'] == 0) {
                    
                	$file['name'] = sanitize_file_name($file['name']);
                	
//                	var_dump($file['type'] ,  $acceptedFormats[$type], in_array($file['type'], $acceptedFormats[$type]) );die;
                	
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
//            }
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
    	<h2>Informações de login</h2>
    	<p class="clearfix prepend-1">
      		<label for="username">Nome de usuário</label>
		    <br/>
      		<input type="text" id="user_login" name="user_login" value="<?php echo $data->user_login; ?>" class="text span-12" />
    	</p>
    	<p class="clearfix prepend-1">
      		<label for="password">Senha</label>
      		<br/>
      		<input type="text" id="password" name="password" value="" class="text span-12" />
    	</p>
    	<p class="clearfix prepend-1">
			<label for="password_confirmation">Confirmação da senha</label>
			<br/>
			<input type="text" id="password_confirmation" name="password_confirmation" value="" class="text span-12" />
		</p>

    	<h2>Informações de contato</h2>
    	<p class="clearfix prepend-1">
			<label for="responsable">Responsável</label>
			<br/>
			<input type="text" id="responsavel" name="responsavel" value="<?php echo $data->responsavel; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="user_email">E-mail</label>
			<br/>
			<input type="text" id="user_email" name="user_email" value="<?php echo $data->user_email; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="phone">Telefone</label>
			<br/>
			<input type="text" id="ddd" name="ddd" value="<?php echo $data->ddd; ?>" class="text span-1" /> <input type="text" id="telefone" name="telefone" value="<?php echo $data->telefone; ?>" class="text span-5"/>
		</p>
		<p class="clearfix prepend-1">
			<label for="estado">Estado:</label>
			<br />
			<select name="estado">                            
				 <?php 
                    foreach($estados as $uf=>$name){
                        echo "<option " . ($data->estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";    
                    }
                ?>
			</select>
		</p>


		<h2>Informações da banda</h2>
		<p class="prepend-1 clearfix">
			<label for="banda">Nome da banda</label>
			<br/>
			<input type="text" id="banda" name="banda" value="<?php echo $data->banda; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="site">Site</label>
			<br/>
			<input type="text" id="site" name="site" value="<?php echo $data->site; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="youtube">URL do YouTube</label>
			<br/>
			<input type="text" id="youtube" name="youtube" value="<?php echo $data->youtube_url; ?>" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="music">Música #1</label>
			<br/>
			<input type="file" id="music" name="music_1" value="" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="music">Música #2</label>
			<br/>
			<input type="file" id="music" name="music_2" value="" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="music">Música #3</label>
			<br/>
			<input type="file" id="music" name="music_3" value="" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="photo">Foto #1</label>
			<br/>
			<input type="file" id="images" name="images_1" value="" class="text span-12" />
		</p>
		<p class="clearfix prepend-1">
			<label for="photo">Foto #2</label>
			<br/>
			<input type="file" id="images" name="images_2" value="" class="text span-12" />
		</p>
		<p class="clearfix textright">
			<input type="submit" value="Salvar" />
			<a href="" class="button">Cancelar</a>
		</p>
	</form>
</div>

<?php get_footer(); ?>