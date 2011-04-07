<?php
    
    add_action('wp_print_scripts', 'cadastro_de_evento_load_js');
    function cadastro_de_evento_load_js() {
        wp_enqueue_script('cadastro-de-evento', TNB_URL . '/js/cadastro-de-evento.js', array('jquery'));
        wp_localize_script('cadastro-de-evento', 'obj', array('ajax_url'=>admin_url('admin-ajax.php')));
    }

    $msg['error'] = array();

    global $current_user, $profileuser;
    $options = get_option('custom_system_notices');
    $evento_tipos = array("Competição","Festival","Publicidade","Show","Turnê");

    
    
    if (!is_artista($profileuser->ID)) {
        if( !$profileuser->cnpj && !$profileuser->cpf){
            $profile_href = get_author_posts_url($current_user->ID) . '/editar/produtor/';
            $msg['error'][] = __("Complete seus dados do perfil com CPF ou CNPJ para poder criar oportunidades.").'<br/>'.
                        "<a class='tag-link' href='$profile_href'>".__("Clique aqui para editar seu perfil")."</a>";
        }
        if( $profileuser->origem_pais == 'BR' && $profileuser->cpf && !is_a_valid_cpf($profileuser->cpf)) {
            $msg['error'][] = __("Corrija seu CPF para poder criar eventos");
        }
        if( $profileuser->origem_pais == 'BR' && $profileuser->cnpj && !is_a_valid_cnpj($profileuser->cnpj)) {
            $msg['error'][] = __("Corrija seu CNPJ para poder criar eventos");
        }
    }
    
    /*
     * Normaliza campos 
     */
    if($_POST) {
        $_POST['evento_inicio'] = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_inicio']);

        $_POST['evento_fim'] = $_POST['evento_fim'] !=''
                                   ? preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_fim'])
                                   : $_POST['evento_inicio'];

        $_POST['evento_inscricao_inicio'] = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_inscricao_inicio']);

        $_POST['evento_inscricao_fim'] = $_POST['evento_inscricao_fim']!=''
                                             ? preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_inscricao_fim'])
                                             : $_POST['evento_inscricao_inicio'] ;

        $_POST['evento_site'] = preg_replace('|^(https?://)*(.+)$|', 'http://$2', $_POST['evento_site']);
    }

    // é confrontado com $_POST
    $event_meta = array(
        'evento_site' => $_POST['evento_site'],
        'evento_local' => stripslashes($_POST['evento_local']),
        'evento_cidade' => stripslashes($_POST['evento_cidade']),
        'evento_estado' => stripslashes($_POST['evento_estado']),
        'evento_pais' => $_POST['evento_pais'],
        'evento_inicio' => $_POST['evento_inicio'],
        'evento_fim' => $_POST['evento_fim'],
        'evento_tipo' => $_POST['evento_tipo'],
        'evento_inscricao_inicio' => $_POST['evento_inscricao_inicio'],
        'evento_inscricao_fim' => $_POST['evento_inscricao_fim'],
        'evento_vagas' => $_POST['evento_vagas'],
        'evento_condicoes' => $_POST['evento_condicoes'],
        'evento_restricoes' => $_POST['evento_restricoes'],
        'evento_tos' => $_POST['evento_tos'],
        'forcar_condicoes' => $_POST['forcar_condicoes'],
        'forcar_restricoes' => $_POST['forcar_restricoes'],
        'forcar_tos' => $_POST['forcar_tos'],
        'superevento' => $_REQUEST['superevento'],
        'evento_produtores_selecionam' => $_POST['evento_produtores_selecionam']
    );

    // é confrontado com $_FILES
    $images = array(
        'evento_avatar' => null,
        'evento_patrocinador1' => null,
        'evento_patrocinador2' => null,
        'evento_patrocinador3' => null,
    );

    $event = new stdClass();
    $event->post_title = strip_tags($_POST['post_title']);
    $event->post_content = strip_tags($_POST['post_content']);
    $event->post_status = $_POST['post_status'];

    if(get_query_var('event_name')) {
        $query_args = array(
            'name' => get_query_var('event_name'),
            'post_type' => 'eventos'
        );
        $author_name = get_query_var('author_name');
        $result = query_posts($query_args);

        $event = count($result)==1 ? $result[0] : new stdClass();
    }

    $parent_event = new stdClass();

    if ($event->post_parent==0 && !empty($_REQUEST['post_parent'])) {
        $event->post_parent = (int) sprintf("%d", $_REQUEST['post_parent']);
    }

    if ($event->post_parent > 0) {
        $parent_event = get_post($event->post_parent);

        if(get_post_meta($parent_event->ID, 'superevento', true) == 'yes' && $parent_event->post_type == 'eventos') {
            $event_meta['evento_tipo'] = get_post_meta($parent_event->ID, 'evento_tipo', true);

            if(get_post_meta($parent_event->ID, 'forcar_condicoes', true)) {
                $parent_event->forcar_condicoes = true;
                $event_meta['evento_condicoes'] = get_post_meta($parent_event->ID, 'evento_condicoes', true);
            }
            if(get_post_meta($parent_event->ID, 'forcar_restricoes', true)) {
                $parent_event->forcar_restricoes = true;
                $event_meta['evento_restricoes'] = get_post_meta($parent_event->ID, 'evento_restricoes', true);
            }
            if(get_post_meta($parent_event->ID, 'forcar_tos', true)) {
                $parent_event->forcar_tos = true;
                $event_meta['evento_tos'] = get_post_meta($parent_event->ID, 'evento_tos', true);
            }
        } else {
            $event->post_parent = 0;
            $parent_event = new stdClass();
        }
    }

    // Se for para editar um evento
    if($event->ID) {
        if(!current_user_can('edit_post', $event->ID)){
            wp_redirect(get_author_posts_url($current_user->ID));
            exit();
        }

        foreach($event_meta as $key => $value) {
            $value = $value ? $value : get_post_meta($event->ID, $key, true);
            if($value) {
                $event_meta[$key] = $value;
            }
        }
        $date_fields = array('evento_inscricao_inicio', 'evento_inscricao_fim', 'evento_inicio', 'evento_fim');
        foreach ($date_fields as $f) 
            $event_meta[$f] = preg_replace('/(\d\d\d\d)-(\d\d)-(\d\d)/', "$3/$2/$1", $event_meta[$f]);

        $images = array(
            'evento_avatar' => get_post_meta($event->ID, 'evento_avatar', true),
            'evento_patrocinador1' => get_post_meta($event->ID, 'evento_patrocinador1', true),
            'evento_patrocinador2' => get_post_meta($event->ID, 'evento_patrocinador2', true),
            'evento_patrocinador3' => get_post_meta($event->ID, 'evento_patrocinador3', true),
        );
    }

    /*
     * Verifica se este post deve persistir no banco de dados
     */
    $should_save_post = false;
    if(!property_exists($event,'ID') && $_POST['termo_para_novo_evento'] == '1') {
        if($_POST['termo_para_novo_evento'] == '1') {
            $should_save_post = true;
        } else {
            $msg['error'][] = __("Você deve aceitar os termos do Toque No Brasil para poder criar um evento");
        }
    } else if(property_exists($event,'ID')) {
        $should_save_post = true;
    }

    /*
     * A partir daqui alterações são feitas no banco de dados.
     */
    if ($_POST && $should_save_post) {

        if(strip_tags($_POST['post_title']) == ''){
            $msg['error'][] = __("O nome da oportunidade não está preenchido corretamente.");
        }
        if(strip_tags($_POST['post_content']) == ''){
            $msg['error'][] = __("A descrição da oportunidade não está preenchida correntamente.");
        }

        // Restrições para mudar o status de um evento:

        if ($_POST['post_status'] == 'publish' && $event->post_parent > 0) {
            // Um sub evento só pode ser marcado como ativo se o evento pai estiver ativo:
            if ($parent_event->post_status != 'publish')
                $msg['error'][] = __("Esta oportunidade só poderá ser ativada quando a oportunidade em que está inserida for ativada");
        }
        if(count($msg['error']) == 0) {

            if ($event->ID && $_POST['post_status'] == 'draft' && $_REQUEST['superevento'] == 'yes') {
                // Se um super evento for marcado como inativo, é preciso desativar todos os eventos filhos e comunicar seus produtores
                $subEventosParaDesativar = get_posts("post_type=eventos&post_parent={$event->ID}&post_status=publish&numberposts=-1");
                foreach ($subEventosParaDesativar as $subEventoDesativar) {
                    $subEventoDesativar->post_status = 'draft';
                    wp_update_post($subEventoDesativar);
                    do_action('tnb_subevento_desativado_por_superevento', $event, $subEventoDesativar);
                }
            }

            $event->post_title = strip_tags($_POST['post_title']);

            $post = array(
                'post_title' => $event->post_title,
                'post_content' => strip_tags($_POST['post_content']),
                'post_type' => 'eventos',
                'post_status' => $_POST['post_status'],
                'post_parent' => $_POST['superevento']=='yes' ? 0 : $_POST['post_parent']
            );

            if(!$event->post_name) {
                $post_name = sanitize_title_with_dashes($event->post_title);
                $post['post_name'] = tnb_unique_event_slug($post_name, $post['ID']);
            }

            if($event->ID) {
                $post['ID'] = $event->ID;
                wp_update_post($post);
            } else {
                $post['ID'] = wp_insert_post($post);

                if($post['post_parent'] != 0) {
                    do_action('tnb_superevento_recebe_um_subevento', $post['ID']);
                }
            }

            if($post['ID']) {
                if(get_post_meta($post['post_parent'], 'forcar_condicoes', true)) {
                    unset($event_meta['evento_condicoes']);
                }
                if(get_post_meta($post['post_parent'], 'forcar_restricoes', true)) {
                    unset($event_meta['evento_restricoes']);
                }
                if(get_post_meta($post['post_parent'], 'forcar_tos', true)) {
                    unset($event_meta['evento_tos']);
                }

                // Remove imagens marcadas para exclusão
                foreach($images as $key => $image) {
                    if($image && isset($_POST['excluir_'.$key])) {
                        wp_delete_attachment($image);
                        delete_post_meta($post['ID'], $key, $image);
                        $images[$key] = null;
                    }
                }

                // Atualiza metas
                foreach($event_meta as $key => $value) {
                    $value = isset($_POST[$key])?$_POST[$key]:'';

                    if(update_post_meta($post['ID'], $key, $value)){;
                        $event_meta[$key] = $value;
                    }
                }

                // Upload de imagens
                require_once(ABSPATH . '/wp-admin/includes/media.php');
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                require_once(ABSPATH . '/wp-admin/includes/image.php');
                $upload_dir = WP_CONTENT_DIR.'/uploads';

                foreach($_FILES as $index => $file) {
                    if ($file['size'] > 0 && array_key_exists($index, $images)) {
                        if(preg_match('#image/(gif|png|jpe?g|pjpeg|x-png)#', $file['type'])) {
                            // Apaga a figura antiga, se ela existir
                            $old_image_id = get_post_meta($post['ID'], $index, true);
                            if($old_image_id) {
                                wp_delete_attachment($old_image_id);
                            }

                            $sanitized_name = toquenobrasil_sanitize_file_name($file['name']);
                            $image_post = array(
                                "post_title" => $sanitized_name,
                                "post_content" => $file['name'],
                                "post_excerpt" => $file['name'],
                                "post_author" => $current_user->ID,
                            );

                            // Carrega imagem e atualiza o meta que relaciona post e imagem
                            $media_id = media_handle_upload($index, $post['ID'], $image_post);
                            update_post_meta($post['ID'], $index, $media_id);
                            if ($index == 'evento_avatar')
                                update_post_meta($post['ID'], '_thumbnail_id', $media_id);
                            $images[$index] = $media_id;
                        }
                    }
                }

                // Quando deu tudo certo vai pra lista de eventos
                wp_redirect(get_author_posts_url($current_user->ID).'/editar/oportunidades/?msg-success=1');
                exit();
                //$msg['success'][] = __('Oportunidade Salva', 'tnb');
            }
        }
    }

    // Lista de super eventos para combo
    $query_args = array(
        'post_type' => 'eventos',
        'meta_key' => 'superevento',
        'meta_value' => 'yes',
        'posts_per_page' => -1
    );
    $superevents = query_posts($query_args);

    $estados = get_estados();
    $paises = get_paises();

    wp_enqueue_script('datepicker_js', TNB_URL . '/js/ui.datepicker.js', array('jquery'));
    wp_enqueue_script('datepicker_br_js', TNB_URL . '/js/jquery.ui.datepicker-pt-BR.js', array('datepicker_js'));


    /*
     * Moderar sub eventos
     * TODO: Estas chamadas poderiam ser feitas via POST
     */    
    if ($moderarSubId = $_GET['aprovar_subevento']) {
        $moderarSub = get_post($moderarSubId);
        if (isset($moderarSub->post_parent) && $moderarSub->post_parent > 0) {
            if (current_user_can('edit_post', $moderarSub->post_parent)) {
                do_action('tnb_subevento_e_aprovado_em_um_superevento',$moderarSubId);
                delete_post_meta($moderarSubId, 'aprovado_para_superevento');
                update_post_meta($moderarSubId, 'aprovado_para_superevento', $moderarSub->post_parent);
            }
        }
    }
    if ($moderarSubId = $_GET['recusar_subevento']) {
        $moderarSub = get_post($moderarSubId);
        if (isset($moderarSub->post_parent) && $moderarSub->post_parent > 0) {
            if (current_user_can('edit_post', $moderarSub->post_parent)) {
                delete_post_meta($moderarSubId, 'aprovado_para_superevento', $moderarSub->post_parent);
            }
        }
    }



    if ($_GET['msg-success'])
        $msg['success'][] = __('Oportunidade Salva', 'tnb');
