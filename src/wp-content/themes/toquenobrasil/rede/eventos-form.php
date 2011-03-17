<?php
    if ( !is_user_logged_in() ) {
        wp_redirect(get_bloginfo('siteurl'));
        exit();
    }

    add_action('wp_print_scripts', 'cadastro_de_evento_load_js');
    function cadastro_de_evento_load_js() {
        wp_enqueue_script('cadastro-de-evento', TNB_URL . '/js/cadastro-de-evento.js', array('jquery'));
        wp_localize_script('cadastro-de-evento', 'obj', array('ajax_url'=>admin_url('admin-ajax.php')));
    }

    $errors = array();

    global $current_user;
    $options = get_option('custom_system_notices');
    $evento_tipos = array("Competição","Festival","Publicidade","Show","Turnê");

    $profileuser = $current_user->data;
    if( !$profileuser->cnpj && !$profileuser->cpf){
        $profile_href = home_url().'/rede/editar/'.$current_user->user_nicename.'/';
        $errors[] = __("Complete seus dados do perfil com CPF ou CNPJ para poder criar eventos.").'<br/>'.
                    "<a class='tag-link' href='$profile_href'>".__("Clique aqui para editar seu perfil")."</a>";
    }
    if($profileuser->cpf && !is_a_valid_cpf($profileuser->cpf)) {
        $errors[] = __("Corrija seu CPF para poder criar eventos");
    }
    if($profileuser->cnpj && !is_a_valid_cnpj($profileuser->cnpj)) {
        $errors[] = __("Corrija seu CNPJ para poder criar eventos");
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
        'evento_local' => $_POST['evento_local'],
        'evento_cidade' => $_POST['evento_cidade'],
        'evento_estado' => $_POST['evento_estado'],
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
            wp_redirect(get_author_posts_url($current_user->ID).'/eventos/');
            exit();
        }

        foreach($event_meta as $key => $value) {
            $value = $value ? $value : get_post_meta($event->ID, $key, true);
            if($value) {
                $event_meta[$key] = $value;
            }
        }

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
            $errors[] = __("Você deve aceitar os termos do Toque No Brasil para poder criar um evento");
        }
    } else if(property_exists($event,'ID')) {
        $should_save_post = true;
    }

    /*
     * A partir daqui alterações são feitas no banco de dados.
     */
    if ($_POST && $should_save_post) {

        if(strip_tags($_POST['post_title']) == ''){
            $errors[] = __("O nome do evento não está preenchido corretamente.");
        }
        if(strip_tags($_POST['post_content']) == ''){
            $errors[] = __("A descrição do evento não está preenchida correntamente.");
        }

        // Restrições para mudar o status de um evento:

        if ($_POST['post_status'] == 'publish' && $event->post_parent > 0) {
            // Um sub evento só pode ser marcado como ativo se o evento pai estiver ativo:
            if ($parent_event->post_status != 'publish')
                $errors[] = __("Este evento só pode ser ativado quando o evento pai estiver ativo");
        }
        if(count($errors) == 0) {

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

                // Quando deu tudo certo volta pra /eventos/
                wp_redirect(get_author_posts_url($current_user->ID).'/eventos/');
                exit();
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
?>

<?php get_header(); ?>
<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
    <div class="item green clearfix">

        <div class="title pull-1">
            <div class="shadow"></div>
                <h1><?php _e('Novo Evento');?></h1>
            </div>
        <?php print_help_player_for('cadastro_evento');?>
        </div>

    <?php if(count($errors) > 0):?>
        <div class="error">
            <ul>
            <?php foreach($errors as $error): ?>
                <li><?php echo $error;?></li>
            <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>

    <div class="item yellow">
          <form enctype="multipart/form-data" id="cadastro-de-evento" class="background clearfix" method="post">
          <h3><?php _e('Informações Gerais');?></h3>
            <p>
                <label for="post_title"><?php _e('Nome');?>*</label><br />
                <input id="post_title" class="text" name="post_title" type="text" value="<?php echo $event->post_title;?>" />
            </p>
            <p>
                <label for="post_status"><?php _e('Status');?>*</label><br />

                <select name="post_status">
                    <option value="publish" <?php if ($event->post_status == 'publish') echo "selected"; ?>>Ativo</option>
                    <option value="draft" <?php if ($event->post_status == 'draft') echo "selected"; ?>>Inativo</option>
                </select>

            </p>
            <p style="clear:both">
                <label for="post_content"><?php _e('Descrição');?>*</label><br />
                <textarea id="post_content" class="text" name="post_content"><?php echo $event->post_content;?></textarea>
            </p>
            <p>
                <label for="evento_site"><?php _e('Site');?></label><br />
                <input id="evento_site" class="text" name="evento_site" type="text" value="<?php echo $event_meta['evento_site'];?>" />
            </p>
            <p>
                <label for="evento_avatar"><?php _e('Banner');?></label><br />
                <?php if($images['evento_avatar']):?>
                    <p>Esta é a maneira como o banner do seu evento será exibido nas listagens e na página do seu evento:</p>
                    <?php if ($event_meta['superevento'] == 'yes') : ?>
                        <?php echo wp_get_attachment_image($images['evento_avatar'], 'banner-horizontal');?>
                    <?php else: ?>
                        <?php echo wp_get_attachment_image($images['evento_avatar'], 'thumbnail');?>
                    <?php endif; ?>
                    <label for="excluir_evento_avatar">Apagar imagem?</label>
                    <input type="checkbox" id="excluir_evento_avatar" name="excluir_evento_avatar" value="0"/>
                    <br/>
                <?php endif;?>
                <input id="evento_avatar" class="text" name="evento_avatar" type="file" />
                <p>As imagens dos supereventos são maiores do que as dos eventos comuns, para terem mais destaque. Se o seu evento for um <b>superevento</b>, para melhor aparência, preferencialmente envie uma imagem proporcional à medida máxima de exibição que é de 550x150px (retangular).<br /> Se for um <b>evento normal</b>, envie uma imagem na proporção de 150x150px (quadrada). Imagens menores que estas medidas não serão redimensionadas.</p>
            </p>

            <p class="span-6" style="clear:left">
                <label for="superevento"><?php _e('Super-evento');?></label><br />
                <select id="superevento" name="superevento">
                    <option value="no"><?php _e('Não');?></option>
                    <?php if($event_meta['superevento'] === 'yes'):?>
                        <option value="yes" selected="selected"><?php _e('Sim');?></option>
                    <?php else: ?>
                        <option value="yes"><?php _e('Sim');?></option>
                    <?php endif;?>
                </select>
            </p>
            <p id="evento_pai" class="span-6">
                <?php if($superevents): ?>
                <label for="evento_pai"><?php _e('Evento pai');?></label><br /> <!--se for subevento -->
                <select name="post_parent">
                    <option value="0"><?php _e('Nenhum');?></option>
                    <?php foreach($superevents as $se): ?>
                    <option <?php echo $event->post_parent==$se->ID?'selected="selected" ':'';?>value="<?php echo $se->ID;?>"><?php echo $se->post_title;?></option>
                    <?php endforeach;?>
                </select>
                <?php endif;?>
            </p>

            <p id="produtores_selecionam">
                <label for="evento_produtores_selecionam"><?php _e('Os produtores dos sub-eventos podem selecionar os artistas');?></label>
                <input id="evento_produtores_selecionam" name="evento_produtores_selecionam" type="checkbox" value="1" <?php if ($event_meta['evento_produtores_selecionam'] == 1) echo 'checked' ?>/>
            </p>


            <p class="span-6 evento_tipo" style="clear:left">
                <label for="evento_tipo"><?php _e('Tipo de evento');?>*</label><br />
                <select id="evento_tipo">
                    <?php foreach($evento_tipos as $tipo):?>
                    <option value="<?php echo $tipo;?>"<?php echo $tipo==$event_meta['evento_tipo']?' selected="selected"':'';?>><?php echo $tipo;?></option>
                    <?php endforeach;?>
                    <option value="Outro"<?php echo !in_array($event_meta['evento_tipo'],$evento_tipos)?' selected="selected"':'';?>><?php _e('Outro');?></option>
                </select>
            </p>
            <p class="span-6" id="outro_evento_tipo" style="display:none">
                <small><?php _e('Qual o tipo de evento?');?></small><br />
                <input type="hidden" id="evento_tipo_original" value="<?php echo $event_meta['evento_tipo'];?>"/>
                <input type="text" class="text" name="evento_tipo" value="<?php echo $event_meta['evento_tipo'];?>"/>
            </p>

            <p style="clear:both">
                <label for="evento_patrocinador1"><?php _e('Logo do primeiro patrocinador.');?></label><br />
                <?php if($images['evento_patrocinador1']):?>
                    <?php echo wp_get_attachment_image($images['evento_patrocinador1'], 'banner-horizontal');?>
                    <label for="excluir_evento_patrocinador1">Apagar imagem?</label>
                    <input type="checkbox" id="excluir_evento_patrocinador1" name="excluir_evento_patrocinador1" value="0"/>
                    <br/>
                <?php endif;?>
                <input id="evento_patrocinador1" class="text alignleft" name="evento_patrocinador1" type="file" /> <span>(largura máxima - 550px)</span>
            </p>
            <p style="clear:both">
                <label for="evento_patrocinador2"><?php _e('Logo do segundo patrocinador.');?></label><br />
                <?php if($images['evento_patrocinador2']):?>
                    <?php echo wp_get_attachment_image($images['evento_patrocinador2'], 'banner-horizontal');?>
                    <label for="excluir_evento_patrocinador2">Apagar imagem?</label>
                    <input type="checkbox" id="excluir_evento_patrocinador2" name="excluir_evento_patrocinador2" value="0"/>
                    <br/>
                <?php endif;?>
                <input id="evento_patrocinador2" class="text alignleft" name="evento_patrocinador2" type="file" /> <span>(largura máxima - 550px)</span>
            </p>
            <p style="clear:both">
                <label for="evento_patrocinador3"><?php _e('Logo do terceiro patrocinador.');?></label><br />
                <?php if($images['evento_patrocinador3']):?>
                    <?php echo wp_get_attachment_image($images['evento_patrocinador3'], 'banner-horizontal');?>
                    <label for="excluir_evento_patrocinador3">Apagar imagem?</label>
                    <input type="checkbox" id="excluir_evento_patrocinador3" name="excluir_evento_patrocinador3" value="0"/>
                    <br/>
                <?php endif;?>
                <input id="evento_patrocinador3" class="text alignleft" name="evento_patrocinador3" type="file" /> <span>(largura máxima - 550px)</span>
            </p>

            <h3>Local</h3>
            <p >
                <label for="evento_local"><?php _e('Estabelecimento');?></label><br />
                <input id="evento_local" class="text" name="evento_local" type="text" value="<?php echo $event_meta['evento_local'];?>" />
            </p>
            <p class="span-3">
                <label for="evento_pais"><?php _e('País', 'tnb');?></label><br />
                <select name="evento_pais" id='evento_pais' class="span-3">
                     <?php
                        foreach($paises as $sigla=>$name){
                            echo "<option " . ($event_meta['evento_pais'] == $sigla ? 'selected':'') . " value='$sigla'>$name</option>";
                        }
                    ?>
                </select>
            </p>
            <p class="span-4">
                <label for="evento_estado"><?php _e('Estado');?></label><br />
                <select id="evento_estado" name="evento_estado" class="span-4">
                <?php
                    foreach($estados as $uf=>$name){
                        echo "<option " . ($event_meta['evento_estado'] == $uf ? 'selected':'') . " value='$uf'>$name</option>";
                    }
                ?>
                </select>
            </p>
            <p class="span-5">
                <label for="evento_cidade"><?php _e('Cidade');?></label><br />
                <input id="evento_cidade" class="text" name="evento_cidade" type="text" value="<?php echo $event_meta['evento_cidade'];?>" />
            </p>

            <div class="clear"></div>

            <h3>Data</h3>
            <p class="span-6">
                <label for="evento_inicio"><?php _e('Início');?></label><br />
                <input id="evento_inicio" class="text date" name="evento_inicio" type="text" value="<?php echo $event_meta['evento_inicio'];?>" />
            </p>
            <p class="span-6">
                <label for="evento_fim"><?php _e('Fim');?></label><br />
                <input id="evento_fim" class="text date" name="evento_fim" type="text" value="<?php echo $event_meta['evento_fim'];?>" />
            </p>

            <h3>Inscrições</h3>
            <p class="span-6">
                <label for="evento_inscricao_inicio"><?php _e('Início');?></label><br />
                <input id="evento_inscricao_inicio" class="text date" name="evento_inscricao_inicio" type="text" value="<?php echo $event_meta['evento_inscricao_inicio'];?>" />
            </p>
            <p class="span-6">
                <label for="evento_inscricao_fim"><?php _e('Fim');?></label><br />
                <input id="evento_inscricao_fim" class="text date" name="evento_inscricao_fim" type="text" value="<?php echo $event_meta['evento_inscricao_fim'];?>" />
            <script type="text/javascript">jQuery('input.date').datepicker();</script>

            </p>
            <p class="span-2">
                <label for="evento_vagas"><?php _e('Vagas');?></label><br />
                <input id="evento_vagas" class="text" name="evento_vagas" type="text" value="<?php echo $event_meta['evento_vagas'];?>" />
            </p>

            <p style="clear:both">
                <label for="evento_condicoes"><?php _e('Condições');?></label><br />
                <textarea <?php echo $parent_event->forcar_condicoes?'disabled="disabled" ':'';?>id="evento_condicoes" class="text" name="evento_condicoes"><?php echo $event_meta['evento_condicoes'];?></textarea>

                <small for="forcar_condicoes" class="forcar"><?php _e('Forçar sub-eventos a usarem as mesmas condições:');?></small>
                <input <?php echo $event_meta['forcar_condicoes']?'checked="checked" ':'';?>type="checkbox" id="forcar_condicoes" name="forcar_condicoes" class="forcar"/>
            </p>
            <p>
                <label for="evento_restricoes"><?php _e('Restrições');?></label><br />
                <textarea <?php echo $parent_event->forcar_restricoes?'disabled="disabled" ':'';?>id="evento_restricoes" class="text" name="evento_restricoes"><?php echo $event_meta['evento_restricoes'];?></textarea>

                <small for="forcar_restricoes" class="forcar"><?php _e('Forçar sub-eventos a usarem as mesmas restrições:');?></small>
                <input <?php echo $event_meta['forcar_restricoes']?'checked="checked" ':'';?>type="checkbox" id="forcar_restricoes" name="forcar_restricoes" class="forcar"/>
            </p>
            <p>
                <label for="evento_tos"><?php _e('Termos');?></label><br />
                <small><?php _e("Termos que os artistas terão que aceitar para se inscrever no seu evento");?></small>
                <textarea <?php echo $parent_event->forcar_tos?'disabled="disabled" ':'';?>id="evento_tos" class="text" name="evento_tos"><?php echo $event_meta['evento_tos'];?></textarea>

                <small for="forcar_tos" class="forcar"><?php _e('Forçar sub-eventos a usarem os mesmos termos:');?></small>
                <input <?php echo $event_meta['forcar_tos']?'checked="checked" ':'';?>type="checkbox" id="forcar_tos" name="forcar_tos" class="forcar"/>
            </p>

            <?php if( ! property_exists($event, 'ID')):?>
            <div class='tnb_modal' id='tnb_modal_cadastro_evento'>
                <h2><?php _e('Termo de Responsabilidade','tnb'); ?></h2>
                <?php echo nl2br($options['tnb_termo_para_novo_evento']); ?>
                <div class="textright">
                    <a href="#" class="button" id="aceito_termo_para_novo_evento"><?php _e('Li e aceito o termo', 'tnb');?></a>
                </div>
            </div>
            <input type="hidden" name="termo_para_novo_evento" id="termo_para_novo_evento" value="0"/>
            <?php endif;?>

            <div class="span-2 prepend-10 last">
                 <input type="image" class="submit" value="Enviar" src="<?php echo get_theme_image("submit-comment.png"); ?>">
            </div>

        </form>
    </div>
</div>

<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("blog-sidebar");?>
    </div>
</div>

<?php get_footer(); ?>
