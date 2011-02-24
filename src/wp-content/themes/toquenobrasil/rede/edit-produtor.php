<?php

global $current_user, $user_ID, $profileuser;
$profileuser = $current_user; 
// apenas artistas usam esse tpl
if(!is_user_logged_in() && !is_produtor())
    wp_redirect(get_bloginfo('url'));

$profileuser = $current_user->data;

$estados = get_estados();
$paises = get_paises();
//var_dump($_POST);

if(isset($_POST['action']) && $_POST['action'] == 'update' && wp_verify_nonce($_POST['_wpnonce'], 'edit_nonce' )){
    require_once( ABSPATH . WPINC . '/registration.php' );
    $profileuser_id = $user_ID;
    if(isset($_POST['action_user']) && $_POST['action_user']=='user_data'){
        if(!filter_var( $_POST['user_email'], FILTER_VALIDATE_EMAIL))
    		$msg['error'][] = __('O email informado é inválido.','tnb');    
        
        if( $_POST['user_email'] != $profileuser->user_email && email_exists($_POST['user_email']))
            $msg['error'][] =  __('Esse email já está sendo utilizado. Por favor verifique se digitou os dados corretamente.', 'tnb');
            
        if( strlen($_POST['user_pass'])>0  && $_POST['user_pass'] !=  $_POST['user_pass_confirm'] )
            $msg['error'][]= __('A senhas fornecidas não conferem.','tnb');
        
        if( strlen($_POST['youtube'])>0  && !preg_match("/\/watch\?v=/", $_POST['youtube']) )
            $msg['error'][]= __('URL de vídeo no Youtube inválida.','tnb');
        
        if(strlen($_POST['site'])>0 &&  $_POST['site'] != 'http://' && !filter_var($_POST['site'], FILTER_VALIDATE_URL))
            $msg['error'][]= __('O link fornecido não é válido.','tnb'); 
            
        if($_POST['cpf'] != '' && !is_a_valid_cpf($_POST['cpf']))
            $msg['error'][] = "Informe um CPF válido.";
        
        if($_POST['cnpj'] != '' && !is_a_valid_cnpj($_POST['cnpj']))
            $msg['error'][] = "Informe um CNPJ válido.";
        

        if( !$msg['error']){
            $userdata['ID'] = $profileuser_id;
            $userdata['user_login'] = $profileuser->user_login;
            $userdata['display_name'] = $_POST['banda'];
            $userdata['user_email'] = $_POST['user_email'];
            $userdata['description'] = $_POST['description'];
            
            if(strlen($_POST['user_pass'])>0)
                $userdata['user_pass'] = wp_hash_password($_POST['user_pass']); 
            
            $rt = wp_insert_user($userdata);
            update_user_meta( $profileuser_id, 'banda' , $_POST['banda'] );
            update_user_meta( $profileuser_id, 'nome' , $_POST['nome'] );
            update_user_meta( $profileuser_id, 'telefone' , $_POST['telefone'] );
            //update_user_meta( $profileuser_id, 'telefone_ddd' , $_POST['telefone_ddd'] );
            if(strlen($_POST['site'])> 0 && $_POST['site']!='http://')
                update_user_meta( $profileuser_id, 'site' , $_POST['site'] );
            else
                delete_user_meta($profileuser_id, 'site');
            
                
            update_user_meta( $profileuser_id, 'origem_pais' , $_POST['origem_pais'] );
            update_user_meta( $profileuser_id, 'origem_estado' , $_POST['origem_estado'] );
            update_user_meta( $profileuser_id, 'origem_cidade' , $_POST['origem_cidade'] );
            
            update_user_meta( $profileuser_id, 'youtube' , $_POST['youtube'] );

            $cpf = preg_replace('/[^0-9]/','',$_POST['cpf']);
            update_user_meta( $profileuser_id, 'cpf' , $cpf );

            $cnpj = preg_replace('/[^0-9]/','',$_POST['cnpj']);
            update_user_meta( $profileuser_id, 'cnpj' , $cnpj );
            
            $msg['success'][] = __('Dados Atualizados', 'tnb');
            $profileuser = get_userdata( $user_ID );
        }else{
            
            foreach($_POST as $n=>$v)
                $profileuser->{$n} = $v; 
            
        }
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
                
                $index_nr = preg_replace("/([^0-9])/",'', $index );    
                    
                $type = preg_replace('/(_[0-9])/','', $index);

                $media_title = $file['name'];
                
                #echo $file['name'];
                $file['name'] = toquenobrasil_sanitize_file_name($file['name']);
                $_FILES[$index]['name'] = $file['name'];
                #echo ' ', $file['name']; die;
                
                $post = array(
                    "post_title" => $media_title, 
                    "post_content" => $file['name'], 
                    "post_excerpt" => $file['name'],
                    "post_author" => $user_ID,
                	                    
                     );
                if($type == 'music' || $type == 'images' )
                    $post["menu_order"] = $index_nr;
                     
        		$acceptedFormats = array(
        		    
        			'images' => array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png'),
        			'music' => array('audio/mpeg', 'audio/x-mpeg', 'audio/mp3', 'audio/x-mp3', 'audio/mpeg3', 'audio/x-mpeg3', 'audio/mpg', 'audio/x-mpg', 'audio/x-mpegaudio'),
        			'rider' => array('application/pdf','application/x-pdf','application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' , 'image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png'),
        			'mapa_palco' => array('application/pdf','application/x-pdf','application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' , 'image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png' ),
                );
            
                $errors = array(1 => __('O tamanho do arquivo não pode ser maior do que ','tnb') . ini_get('upload_max_filesize') . 'B',
                2 => __('O tamanho do arquivo não pode ser maior do que ','tnb') . ini_get('upload_max_filesize') . 'B',
                3 => __('Um erro ocorreu e apenas padatarte do arquivo foi enviado. Tente novamente.','tnb') ,
                4 => __('Nenhum arquivo foi enviado!','tnb'));    
            
                if ($file['error'] == 0) {
                    
                	if (in_array($file['type'], $acceptedFormats[$type])) {
                	    unset($GLOBALS['post']);
                        $media_id = media_handle_upload($index, '', $post);
                        if ($media_id->errors)
                            $msg['error'][] = implode(' ', $media_id->errors['upload_error']);
                    } else {
                        $msg['error'][] = __('Tipo de arquivo não permitido','tnb');
                    }
                } else {
                    $msg['error'][] = $errors[$file['error']];
                }
                   
                if (!$msg['error']) {
                    $meta = get_post_meta($media_id, '_wp_attached_file');
                    $sizes = get_media_file_sizes($upload_dir ."/". $meta[0]);
                    
                    $msg['success'][] = __('Arquivo','tnb') . "
                        <span id='videoName' class='cor_rede'> {$media_title}</span> 
                        " . __('inserido com sucesso','tnb') ;
                    
                    $update = array(
                        'ID' => $media_id,
                        'post_type' => $type,
                        'post_status' => 'publish',
                        'tags_input' => $_POST['media_tags']
                    );
                    $_POST["id_music"] = $media_id;
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
                            toquenobrasil_delete_item($p->ID, $type);
                	}
                    
                    add_post_meta($media_id, '_media_index', $index);
        			wp_update_post($update);
        			
                    
                
                } else {
                	$feedback = __('ERRO','tnb') . ": " . __('Arquivos','tnb') . "
                        <span id='videoName' class='cor_rede'> {$media_title}</span> 
                        " . __('não inseridos!','tnb');
                	if ($error) {
                	    $feedback .= "<br>" . __('Erro','tnb') . ": $error ";	
                	} else {
                		$feedback .= __('Tente novamente','tnb');
                	}
                	$msg['error'][] = $feedback;
                }
                $i++;
            }// end for
        }    
    }
    if(is_array($_POST['delete_media'])){
        foreach ( $_POST['delete_media'] as $i=>$delete){
            preg_match("/^(.+)_([0-9]+)$/", $delete, $out);
            list(,$media_type,$media_id) = $out;
    //        var_dump($media_id);
            if($media_type == 'music'){
//                /$msg['notice'][] = sprintf(__('Musica "%s" excluído com sucesso.', 'tnb'), $_POST["label_music"][$i]);
                unset($_POST["label_music"][$i]);
            }
            $post = get_post($media_id); 
            $types = array(
                'mapa_palco' => __('Mapa do palco','tnb'),
            	'rider' => __('Rider','tnb'),
            	'images' => __('Imagem','tnb'),
            	'music' => __('Música','tnb'),
            );
            $msg['notice'][] = sprintf(__('%s (%s) excluído com sucesso.', 'tnb'), $types[$media_type],$post->post_title);
            
            toquenobrasil_delete_item($media_id, $media_type);
        } 
    }
    // music labels
    if (!$msg['error']) {
        if(isset($_POST["label_music"]) && $_POST["id_music"]>0){
            $post = get_post($_POST["id_music"]);
            $msg['success'][]= sprintf(__('"%s" atualizado com sucesso para "%s".','tnb'), $post->post_title, $_POST["label_music"] );
            wp_update_post( array("ID"=>$_POST["id_music"] , "post_title"=>$_POST["label_music"]));
        }
        /*for ( $i = 0; $i < count($_POST["label_music"]); $i++){
            if(strlen($_POST["label_music"][$i])>0 && $_POST["id_music"][$i]>0 ){
                $post = get_post($_POST["id_music"][$i]);
                wp_update_post( array("ID"=>$_POST["id_music"][$i] , "post_title"=>$_POST["label_music"][$i]));
            }
        }*/
    }
}
wp_enqueue_script('cadastre-se', get_stylesheet_directory_uri(). '/js/campo-cidade.js',array('jquery'));

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
	
	<div id='profile-loading-msg' class='error stay' style="display:none">
		<?php _e('Aguarde!', 'tnb');?>
		<br/>
		<?php _e('Seus dados estão sendo transferidos para os servidores do Toque no Brasil.', 'tnb');?>
		<br/>
		<?php _e('Não saia desta página, caso contrário irá perder suas alterações.', 'tnb');?>
		<br/>
		<?php _e('Esse processo pode demorar alguns minutos dependendo do tamanho dos arquivos enviados.', 'tnb');?>
	</div>
	
	<form class="background clearfix" method="post" enctype="multipart/form-data" id="your-profile" >
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="action_user" value="user_data" />
		
	    <?php wp_nonce_field('edit_nonce'); ?>
	    <i>Campos marcardos com <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?> não serão exibidos publicamente no site. Apenas os produtores de eventos terão acesso a estes dados</i>
	    <br/><br/>
	    
	    <p class="clearfix textright">
			<input class='profile-update-submit' type="submit" value="<?php _e('Salvar', 'tnb');?>" />
			<a href="" class="button cancel-submit"><?php _e('Cancelar', 'tnb');?></a>
		</p>
	    
    	<h2><?php _e('Informações de login', 'tnb');?></h2>
    	
        <p class="clearfix prepend-1">
      		<label for="user_login"><?php _e('Nome de usuário', 'tnb');?></label>
		    <br/>
      		<input type="text" id="user_login" name="user_login" disabled='disabled' value="<?php echo $profileuser->user_login; ?>" class="text span-13" />
    	</p>
    	
        <p class="clearfix prepend-1">
      		<label for="cpf"><?php _e('CPF', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
		    <br/>
      		<input type="text" id="cpf" name="cpf" value="<?php echo $profileuser->cpf; ?>" class="text span-13" />
    	</p>
    	
        <p class="clearfix prepend-1">
      		<label for="cnpj"><?php _e('CNPJ', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
		    <br/>
      		<input type="text" id="cnpj" name="cnpj" value="<?php echo $profileuser->cnpj; ?>" class="text span-13" />
    	</p>
    	
        <p class="clearfix prepend-1">
      		<label for="user_pass"><?php _e('Senha', 'tnb');?></label>
      		<br/>
      		<input type="password" id="user_pass" name="user_pass"  class="text span-13" />
    	</p>
    	
        <p class="clearfix prepend-1">
			<label for="user_pass_confirm"><?php _e('Confirmação da senha', 'tnb');?></label>
			<br/>
			<input type="password" id="user_pass_confirm" name="user_pass_confirm"  class="text span-13" />
		</p>

    	<h2><?php _e('Informações de contato', 'tnb');?></h2>
    	
        <p class="clearfix prepend-1">
			<label for="origem_pais"><?php _e('País', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
			<br/>
            <select class="span-13 text" name="origem_pais" id='origem_pais'>                            
                 <?php 
                    foreach($paises as $sigla=>$name){
                        echo "<option " . ($profileuser->origem_pais == $sigla ? 'selected':'') . " value='$sigla'>$name</option>";    
                    }
                ?>
            </select>
		</p>

        <p class="clearfix prepend-1">
			<label for="origem_estado"><?php _e('Estado', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
			<br/>
            <select class="span-13 text" name="origem_estado" id='origem_estado'>                            
                 <?php 
                    foreach($estados as $uf=>$name){
                        echo "<option " . ($profileuser->origem_estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";    
                    }
                ?>
            </select>
		</p>
        <p class="clearfix prepend-1">
			<label for="origem_cidade"><?php _e('Cidade', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
			<br/>
			<input type="text" id="origem_cidade" name="origem_cidade" value="<?php echo $profileuser->origem_cidade; ?>" class="text span-13" />
		</p>

        <p class="clearfix prepend-1">
			<label for="nome"><?php _e('Nome', 'tnb');?></label>
			<br/>
			<input type="text" id="nome" name="nome" value="<?php echo $profileuser->nome; ?>" class="text span-13" />
		</p>
		<p class="clearfix prepend-1">
			<label for="user_email"><?php _e('E-mail', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
			<br/>
			<input type="text" id="user_email" name="user_email" value="<?php echo $profileuser->user_email; ?>" class="text span-13" />
		</p>
		<p class="clearfix prepend-1">
			<label for="phone"><?php _e('Telefone', 'tnb');?> <?php theme_image('lock.png', array('title' => __('Informações restritas a Produtores', 'tnb'))); ?></label>
			<br/>
            <input type="text" id="telefone" name="telefone" value="<?php echo $profileuser->telefone; ?>" class="text span-6"/>
			<small><?php _e('Telefone completo. Ex: +55 11 5555 0111', 'tnb'); ?></small>
		</p>
		
        <h5 class='prepend-1'><?php _e('Foto do Perfil', 'tnb');?></h5>
		<div class="prepend-1 clearfix">
          <?php do_action('custom_edit_user_profile'); ?>

        </div>

		<br/>
		<h2><?php _e('Mídias', 'tnb');?></h2>
		<h3><?php _e('Vídeo', 'tnb');?></h3>
		<p class="clearfix prepend-1">
			
            
            <label for="youtube"><?php _e('URL de vídeo no YouTube', 'tnb');?></label>
			<br/>
			<input type="text" id="youtube" name="youtube" value="<?php echo $profileuser->youtube; ?>" class="text span-13" />
			<small><?php _e('(Exemplo: http://www.youtube.com/watch?v=videoid)', 'tnb'); ?></small>
		</p>
		
		
		<h3><?php _e('Imagens', 'tnb');?></h3>
		<?php for($i = 1; $i<=2; $i++):?>
        	<p class="clearfix prepend-1">
    			<label for="photo"><?php _e('Imagem', 'tnb');?> <?php echo $i;?></label>
    			<?php 
        		        $media = get_posts("post_type=images&meta_key=_media_index&meta_value=images_{$i}&author={$user_ID}");
        		        
        		        if(isset($media[0])){
        		            echo '<br />';
        		            $media  = $media[0];
        		            
        		            /*
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
                            */
                            
                            
                            echo wp_get_attachment_image( $media->ID, 'medium', true );
                            
        		            echo $media->post_title;
        		            
        		            echo "<br/><input type='checkbox'  name='delete_media[]' value='images_{$media->ID}' class='delete_profile_media' />Deletar arquivo<br/>";
        		        }
        		?>
    			<br/>
    			<input type="file" id="images" name="images_<?php echo $i;?>" value="" class="text span-13" />
                <small><?php _e('(Formato: JPG, PNG, GIF)', 'tnb'); echo" ", __('Tamanho máximo para upload:', 'tnb'),  " " , ini_get('upload_max_filesize'), 'B';?></small>
    		</p>
            
		<?php endfor;?>
		
		<p class="clearfix textright">
			<input class='profile-update-submit' type="submit" value="<?php _e('Salvar', 'tnb');?>" />
			<a href="" class="button cancel-submit"><?php _e('Cancelar', 'tnb');?></a>
		</p>
	</form>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
