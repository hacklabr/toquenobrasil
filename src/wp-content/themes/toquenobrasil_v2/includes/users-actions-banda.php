<?php 

$galeriasId['rider']        = tnb_get_artista_galeria($profileuser->ID, 'rider');
$galeriasId['mapa_palco']   = tnb_get_artista_galeria($profileuser->ID, 'mapa_palco');

$estados = get_estados();
$paises = get_paises();

wp_enqueue_script('campo-cidade', get_stylesheet_directory_uri(). '/js/campo-cidade.js',array('jquery'));
global $unjoin_err;

if(isset($_REQUEST['tnb_user_action']) && $_REQUEST['tnb_user_action'] == 'edit-banda'){
   
    if($_POST['origem_pais'] == 'BR' && $_POST['origem_estado'] == '')
      $msg['error'][] = __("Por favor informe o estado de origem.",'tnb');
    
    if($_POST['origem_cidade'] == '')
      $msg['error'][] = __("Por favor informe a cidade de origem.",'tnb');
            
    if($_POST['banda_pais'] == 'BR' && $_POST['banda_estado'] == '')
      $msg['error'][] = __("Por favor informe o estado de residência.",'tnb');
     
    if($_POST['banda_cidade'] == '')
      $msg['error'][] = __("Por favor informe a cidade de residência.",'tnb');
    
    if(count($_POST['estilo_musical']) > 3)
      $msg['error'][] = __("Por favor selecione no máximo três estilos.",'tnb');
      
    if($_POST['email_publico'] != '' && !filter_var( $_POST['email_publico'], FILTER_VALIDATE_EMAIL))
        $msg['error'][] = __('O email informado é inválido.','tnb');
    
      
    if( !$msg['error']){
        $userdata['ID'] = $profileuser->ID;
        $userdata['display_name'] = $_POST['banda'];
        
        $userdata['description'] = strip_tags($_POST['description'], '<p><a><img><blockquote><i><b><hr>');

        $rt = wp_update_user($userdata);
        
        $profileuser->display_name = $_POST['banda'];

        
        $updateMetaFields = array(
            'origem_pais' , 
            'origem_estado' , 
            'origem_cidade' , 
            'banda',
            'email_publico',
            'responsavel',
            'telefone',

            'banda_pais' , 
            'banda_estado' , 
            'banda_cidade' , 
            'integrantes' , 
            'facebook' , 
            'twitter' , 
            'orkut' , 
            'youtube' , 
            'vimeo',
            'estilo_musical_livre'
        );
        
        foreach ($updateMetaFields as $field) {
            
            // Salva no banco
            update_user_meta( $profileuser->ID, $field , strip_tags($_POST[$field], '<p><a><img><blockquote><i><b><hr>') );
            
            // Atualiza usuário para visualização
            $profileuser->$field = $_POST[$field];
            
        }
        
        // Estilos musicais
        
        delete_user_meta( $profileuser->ID, 'estilo_musical' );
        
        if (is_array($_POST['estilo_musical'])) {
            foreach ($_POST['estilo_musical'] as $e) {
                add_user_meta( $profileuser->ID, 'estilo_musical', $e);
            }
        }        
        /*
        update_user_meta( $profileuser->ID, 'origem_pais' , $_POST['origem_pais'] );
        update_user_meta( $profileuser->ID, 'origem_estado' , $_POST['origem_estado'] );
        update_user_meta( $profileuser->ID, 'origem_cidade' , $_POST['origem_cidade'] );

        update_user_meta( $profileuser->ID, 'banda_pais' , $_POST['banda_pais'] );
        update_user_meta( $profileuser->ID, 'banda_estado' , $_POST['banda_estado'] );
        update_user_meta( $profileuser->ID, 'banda_cidade' , $_POST['banda_cidade'] );
        
        update_user_meta( $profileuser->ID, 'integrantes' , $_POST['integrantes'] );
        
        update_user_meta( $profileuser->ID, 'facebook' , $_POST['facebook'] );
        update_user_meta( $profileuser->ID, 'twitter' , $_POST['twitter'] );
        update_user_meta( $profileuser->ID, 'orkut' , $_POST['orkut'] );
        update_user_meta( $profileuser->ID, 'youtube' , $_POST['youtube'] );
        update_user_meta( $profileuser->ID, 'vimeo' , $_POST['vimeo'] );
        
        $profileuser->banda_pais = $_POST['origem_pais'];
        $profileuser->origem_estado = $_POST['origem_estado'];
        $profileuser->origem_cidade = $_POST['origem_cidade'];
        
        $profileuser->banda_pais = $_POST['banda_pais'];
        $profileuser->banda_estado = $_POST['banda_estado'];
        $profileuser->banda_cidade = $_POST['banda_cidade'];
        */
        
        
        $msg['success'][] = __('Dados Atualizados', 'tnb');
        
        
        // VERIFICANDO SE O ARTISTA PODE CONTINUAR INSCRITO NOS EVENTOS EM QUE ELE ESTÁ INSCRITO 
        // (SOMENTE VERIFICA OS EVENTOS COM A DATA FINAL MENOS DO QUE A DATA ATUAL)
        global $wpdb;
        
        $query_subevents_arovados = " AND (post_parent = 0 OR ID IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = 'aprovado_para_superevento') ) ";
    
        $query = "
        SELECT 
            ID, post_title 
        FROM 
            $wpdb->posts 
        WHERE
            post_type = 'eventos' AND
            post_status = 'publish' AND
            ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'inscrito' AND meta_value = '{$profileuser->ID}' ) AND
            ID in (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'evento_fim' AND meta_value >= CURRENT_TIMESTAMP OR meta_value = '')
            $query_subevents_arovados";
    
       
        $oportunidades = $wpdb->get_results($query);
        $unjoin = '';
        foreach ($oportunidades as $ops){
            if(!tnb_artista_can_join($ops->ID, $profileuser->ID)){
                delete_post_meta($ops->ID, 'inscrito', $profileuser->ID);
                //do_action('tnb_artista_desinscrito_pelo_filtro_editado_pelo_artista', $ops->ID, $profileuser->ID);
                
                $unjoin .= '<li>'.$ops->post_title.'</li>';
            }
        }
        if($unjoin){
            $unjoin_err = __('Devido às alterações em seu perfil, você foi desinscrito das seguintes oportunidades: %s');
            $unjoin_err = sprintf($unjoin_err, '<ul>'.$unjoin.'</ul>');
            
        }
        
    }else{

        foreach($_POST as $n=>$v)
            $profileuser->{$n} = $v;

    }
    
    if ($_FILES && !$msg['error']) {
        do_action('tnb_user_update', $profileuser->ID);
        if(!$msg['error']){
            require_once(ABSPATH . '/wp-admin/includes/media.php');
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            require_once(ABSPATH . '/wp-admin/includes/image.php');
            $upload_dir = WP_CONTENT_DIR.'/uploads';
            $i = 0;
            
            
            // para cada arquivo que foi enviado 
            foreach ($_FILES as $index=>$file){
            	

            	// se não foi enviado o arquivo ou se é a foto de perfil? pula pro próximo arquivo
                if($file['error'] == UPLOAD_ERR_NO_FILE || $index == 'userphoto_image_file')
                    continue;

                // descobre o index do arquivo
                $index_nr = preg_replace("/([^0-9])/",'', $index );

                // descobre o tipo do arquivo (rider, mapa de palco, imagem ou música)
                $type = preg_replace('/(_[0-9])/','', $index);

                
                $media_title = $file['name'];

                // echo $file['name'];
                $file['name'] = toquenobrasil_sanitize_file_name($file['name']);
                $_FILES[$index]['name'] = $file['name'];
                // echo ' ', $file['name']; die;

                $post = array(
                    "post_title"    => $media_title,
                    "post_content"  => $file['name'],
                    "post_excerpt"  => $file['name'],
                    "post_author"   => $user_ID,
                    "post_status"   => 'publish'
                 );


        		$acceptedFormats = array(

        			'rider' => array('application/pdf','application/x-pdf','application/msword',
                                     'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                     'application/vnd.oasis.opendocument.text',
                                     'image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png'),

        			'mapa_palco' => array('application/pdf','application/x-pdf','application/msword',
                                          'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                          'application/vnd.oasis.opendocument.text',
                                          'image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png' ),
                );

                $errors = array(1 => __('O tamanho do arquivo não pode ser maior do que ','tnb') . ini_get('upload_max_filesize') . 'B',
                                2 => __('O tamanho do arquivo não pode ser maior do que ','tnb') . ini_get('upload_max_filesize') . 'B',
                                3 => __('Um erro ocorreu e apenas parte do arquivo foi enviado. Tente novamente.','tnb') ,
                                4 => __('Nenhum arquivo foi enviado!','tnb'));

                if ($file['error'] == 0) {

                	if (in_array($file['type'], $acceptedFormats[$type])) {
                	    unset($GLOBALS['post']);
                        $media_id = media_handle_upload($index, $galeriasId[$type]->ID, $post);
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

                    // remove the file for these position
                    $old_entrie = get_posts("post_type=attachment&post_parent=".$galeriasId[$type]->ID."&meta_key=_media_index&meta_value={$index}&author={$user_ID}");
                	if(sizeof($old_entrie)>0){
                	    foreach($old_entrie as $p)
                            wp_delete_attachment($p->ID);
                	}

                    add_post_meta($media_id, '_media_index', $index);


                } else {
                	$feedback = __('ERRO','tnb') . ": " . __('Arquivos','tnb') . "
                        <span> {$media_title}</span>
                        " . __('não inseridos! ','tnb');
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

            $post = get_post($media_id);
            $types = array(
                'mapa_palco' => __('Mapa do palco','tnb'),
            	'rider' => __('Rider','tnb'),
            );
            $msg['notice'][] = sprintf(__('%s (%s) excluído com sucesso.', 'tnb'), $types[$media_type],$post->post_title);

            wp_delete_attachment($media_id);
        }
    }
   
}
if($unjoin_err){
    $msg['error'][] = $unjoin_err;
}
$usuarioOK = tnb_contatoUsuarioCorreto($profileuser);

?>
