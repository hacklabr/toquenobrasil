<?php

// CUSTOM POST TYPES
add_action( 'init', 'theme_post_type_init' ,0);

function theme_post_type_init() {
    // IMAGES
    
  register_post_type('images', array(
  
    'labels' => array(
                        'name' => _x('Imagens', 'post type general name'),
                        'singular_name' => _x('Imagem', 'post type singular name'),
                        'add_new' => _x('Adicionar Nova', 'image'),
                        'add_new_item' => __('Adicionar nova imagem'),
                        'edit_item' => __('Editar Imagem'),
                        'new_item' => __('Nova Imagem'),
                        'view_item' => __('Ver Imagem'),
                        'search_items' => __('Search images'),
                        'not_found' =>  __('Nenhuma Imagem Encontrada'),
                        'not_found_in_trash' => __('Nenhuma Imagem na Lixeira'),
                        'parent_item_colon' => ''
                     ),
     'public' => true,
     'rewrite' => array('slug' => __('images')),
     'capability_type' => 'post',
     'hierarchical' => false,
     'map_meta_cap ' => true,
     'menu_position' => 5,
     'supports' => array('title','editor','excerpt','author','comments'),
     'taxonomies' => array('post_tag')
  ));

  // MUSIC
  register_post_type('music', array(
    'labels' => array(
                        'name' => _x('Music', 'post type general name'),
                        'singular_name' => _x('Music', 'post type singular name'),
                        'add_new' => _x('Add New', 'music'),
                        'add_new_item' => __('Add New Music'),
                        'edit_item' => __('Edit Music'),
                        'new_item' => __('New Music'),
                        'view_item' => __('View Music'),
                        'search_items' => __('Search musics'),
                        'not_found' =>  __('No musics found'),
                        'not_found_in_trash' => __('No musics found in Trash'),
                        'parent_item_colon' => ''
                     ),
   'public' => true,
   'rewrite' => array('slug' => __('music')),
   'capability_type' => 'post',
   'hierarchical' => false,
   'map_meta_cap ' => true,
   'menu_position' => 5,
   'supports' => array('title','editor','author','comments'),
   'taxonomies' => array('post_tag')
  ));
  
  // MUSIC
  register_post_type('videos', array(
    'labels' => array(
                        'name' => _x('Videos', 'post type general name'),
                        'singular_name' => _x('Video', 'post type singular name'),
                        'add_new' => _x('Add New', 'music'),
                        'add_new_item' => __('Add New Video'),
                        'edit_item' => __('Edit Video'),
                        'new_item' => __('New Video'),
                        'view_item' => __('View Video'),
                        'search_items' => __('Search videos'),
                        'not_found' =>  __('No videos found'),
                        'not_found_in_trash' => __('No videos found in Trash'),
                        'parent_item_colon' => ''
                     ),
   'public' => true,
   'rewrite' => array('slug' => __('videos')),
   'capability_type' => 'post',
   'hierarchical' => false,
   'map_meta_cap ' => true,
   'menu_position' => 5,
   'supports' => array('title','editor','author','comments'),
   'taxonomies' => array('post_tag')
  ));


  // RIDER
  register_post_type('rider', array(
    'labels' => array(
                        'name' => _x('Rider', 'post type general name'),
                        'parent_item_colon' => ''
                     ),
   'public' => true,
   'rewrite' => array('slug' => __('rider')),
   'capability_type' => 'post',
   'hierarchical' => false,
   'map_meta_cap ' => true,
   'menu_position' => 5,
   'supports' => array('title','editor','author','comments'),
  ));

  // RIDER
  register_post_type('mapa_palco', array(
    'labels' => array(
                        'name' => _x('Mapa do Palco', 'post type general name'),
                        'parent_item_colon' => ''
                     ),
   'public' => true,
   'rewrite' => array('slug' => __('rider')),
   'capability_type' => 'post',
   'hierarchical' => false,
   'map_meta_cap ' => true,
   'menu_position' => 5,
   'supports' => array('title','editor','author','comments'),
  ));
  
  // MURAIS DOS USUARIOS
  register_post_type('mural', array(
    'labels' => array(
                        'name' => _x('Mrual', 'post type general name'),
                        'parent_item_colon' => ''
                     ),
   'public' => false,
   'rewrite' => false,
   'capability_type' => 'post',
   'hierarchical' => false,
   'map_meta_cap ' => true,
   
   //'menu_position' => 5,
   //'supports' => array('title','editor','author','comments'),
   
  ));

  // Eventos
  register_post_type('eventos', array(
      'labels' => array(
                          'name' => _x('Eventos', 'post type general name'),
                          'singular_name' => _x('Evento', 'post type general name'),
                          'add_new' => _x('Adicionar Novo', 'post'),
                          'add_new_item' => __('Adicionar Novo Evento'),
                          'edit_item' => __('Editar Evento'),
                          'new_item' => __('Novo Evento'),
                          'view_item' => __('Ver Evento'),
                          'search_items' => __('Procurar eventos'),
                          'not_found' => __('Nenhum evento encontrado'),
                          'not_found_in_trash' => __("Nenhum evento encontrado na lixeira")
                        ),
      'public' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      
      'hierarchical' => true,
      'menu_position' => 5,
      'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail'),
      'register_meta_box_cb' => 'eventos_meta_box',
      'map_meta_cap' => true
    )
  );
}
// END Custom Post Types


// CUSTOM DATA FIELDS FOR POST TYPES

// Data do evento
function eventos_meta_box() {
  add_meta_box("eventos_meta", "Dados do evento", "eventos_meta", "eventos" );
}

function eventos_meta() {
  global $post;
  $tipo = get_post_meta($post->ID, "evento_tipo", true);
  $inicio = get_post_meta($post->ID, "evento_inicio", true);
  $fim = get_post_meta($post->ID, "evento_fim", true);
  $inscricao_inicio = get_post_meta($post->ID, "evento_inscricao_inicio", true);
  $inscricao_fim = get_post_meta($post->ID, "evento_inscricao_fim", true);
  $local = get_post_meta($post->ID, "evento_local", true);
  $site = get_post_meta($post->ID, "evento_site", true);
  $vagas = get_post_meta($post->ID, "evento_vagas", true);
  $recipient = get_post_meta($post->ID, "evento_recipient", true);

  $tos = get_post_meta($post->ID, "evento_tos", true);
  $tos = preg_replace('/\<br\/\>/', "", $tos);

  $condicoes = get_post_meta($post->ID, "evento_condicoes", true);
  $condicoes = preg_replace('/\<br\/\>/', "", $condicoes);

  $restricoes = get_post_meta($post->ID, "evento_restricoes", true);
  $restricoes = preg_replace('/\<br\/\>/', "", $restricoes);



  $inicio = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1", $inicio);
  $fim = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1",$fim);

  $inscricao_inicio = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1", $inscricao_inicio);
  $inscricao_fim = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1",$inscricao_fim);

  ?>

  <input type="hidden" name="eventos_noncename" id="eventos_noncename" value="<?php echo wp_create_nonce( "eventos_noncename" ); ?>" />

  <p><label><strong>Tipo de evento:</strong></label><br />
  <input type="text" name="evento_tipo" value="<?php echo $tipo; ?>" /></p>
  <p><label><strong>Data do início do evento:</strong></label><br />
  <input type="text"  class='calendar'  name="evento_inicio" value="<?php echo $inicio; ?>" /></p>
  <p><label><strong>Data do fim do evento:</strong></label><br />
  <input type="text" class='calendar' name="evento_fim" value="<?php echo $fim; ?>" /></p>
  <p><label><strong>Início das inscrições:</strong></label><br />
  <input type="text"  class='calendar'  name="evento_inscricao_inicio" value="<?php echo $inscricao_inicio; ?>" /></p>
  <p><label><strong>Fim das inscrições:</strong></label><br />
  <input type="text" class='calendar'  name="evento_inscricao_fim" value="<?php echo $inscricao_fim; ?>" /></p>
  <p><label><strong>Local:</strong></label><br />
  <input type="text" name="evento_local" value="<?php echo $local ?>" /></p>
  <p><label><strong>Site do evento:</strong></label><br />
  <input type="text" name="evento_site" value="<?php echo $site ?>" /></p>
  <p><label><strong>Vagas:</strong></label><br />
  <input type="text" name="evento_vagas" value="<?php echo $vagas ?>" /></p>
  <p><label><strong>E-mail para inscrição:</strong></label><br />
  <input type="text" name="evento_recipient" value="<?php echo $recipient ?>" /></p>

  <p>
  	<label><strong>Condições:</strong></label>
  	<br />
  	<textarea name='condicoes' rows="10" cols="90"><?php echo $condicoes; ?></textarea>
  </p>

  <p>
  	<label><strong>Restrições:</strong></label>
  	<br />
  	<textarea name='restricoes' rows="10" cols="90"><?php echo $restricoes; ?></textarea>
  </p>

  <p>
  	<label><strong>TERMOS:</strong></label>
  	<br />
  	<textarea name='tos' rows="10" cols="90"><?php echo $tos; ?></textarea>
  </p>
  <?php
}

add_action( 'save_post', 'save_eventos_meta_box' );

function save_eventos_meta_box( $post_id ) {
  if ( !wp_verify_nonce( $_POST['eventos_noncename'], "eventos_noncename" )) {
    return $post_id;
  }

  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    return $post_id;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
     if ( !current_user_can( 'edit_post', $post_id ) )
       return $post_id;
  }

  $dt_inicio = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_inicio']);
  $dt_fim = $_POST['evento_fim']!='' ? preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_fim']) : $dt_inicio  ;


  $dt_inscricao_inicio = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_inscricao_inicio']);
  $dt_inscricao_fim = $_POST['evento_inscricao_fim']!='' ? preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1", $_POST['evento_inscricao_fim']) : $dt_inscricao_inicio  ;



  update_post_meta($post_id, 'evento_tipo', $_POST['evento_tipo']);
  update_post_meta($post_id, 'evento_inicio', $dt_inicio);
  update_post_meta($post_id, 'evento_fim', $dt_fim);
  update_post_meta($post_id, 'evento_inscricao_inicio', $dt_inscricao_inicio);
  update_post_meta($post_id, 'evento_inscricao_fim', $dt_inscricao_fim);
  update_post_meta($post_id, 'evento_local', $_POST['evento_local']);
  update_post_meta($post_id, 'evento_site', $_POST['evento_site']);
  update_post_meta($post_id, 'evento_vagas', $_POST['evento_vagas']);
  update_post_meta($post_id, 'evento_recipient', $_POST['evento_recipient']);
  update_post_meta($post_id, 'evento_tos', preg_replace('/\n/', '<br/>' , $_POST['tos']));

  update_post_meta($post_id, 'evento_condicoes', preg_replace('/\n/', '<br/>' , $_POST['condicoes']));
  update_post_meta($post_id, 'evento_restricoes', preg_replace('/\n/', '<br/>' , $_POST['restricoes']));


  return $post_id;
}
// END Dados do evento




?>
