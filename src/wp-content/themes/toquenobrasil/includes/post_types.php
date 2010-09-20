<?php

// CUSTOM POST TYPES
add_action( 'init', 'theme_post_type_init' );

function theme_post_type_init() {
   
    // IMAGES
  register_post_type('images', array(
    'labels' => array(
                        'name' => _x('Images', 'post type general name'),
                        'singular_name' => _x('Image', 'post type singular name'),
                        'add_new' => _x('Add New', 'image'),
                        'add_new_item' => __('Add New Image'),
                        'edit_item' => __('Edit Image'),
                        'new_item' => __('New Image'),
                        'view_item' => __('View Image'),
                        'search_items' => __('Search images'),
                        'not_found' =>  __('No images found'),
                        'not_found_in_trash' => __('No images found in Trash'), 
                        'parent_item_colon' => ''
                     ),
     'public' => true,
     'rewrite' => array('slug' => __('images')),
     'capability_type' => 'post',
     'hierarchical' => false,
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
   'menu_position' => 5,
   'supports' => array('title','editor','author','comments'),
   'taxonomies' => array('post_tag')
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
      'hierarchical' => false,
      'menu_position' => 5,
      'supports' => array('title', 'editor', 'excerpt', 'author'),      
      'register_meta_box_cb' => 'eventos_meta_box'
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
  
  ?>

  <input type="hidden" name="eventos_noncename" id="eventos_noncename" value="<?php echo wp_create_nonce( "eventos_noncename" ); ?>" />

  <p><label><strong>Tipo de evento:</strong></label><br />
  <input type="text" name="evento_tipo" value="<?php echo $tipo; ?>" /></p>
  <p><label><strong>Data do início do evento:</strong></label><br />
  <input type="text" name="evento_inicio" value="<?php echo $inicio; ?>" /></p>
  <p><label><strong>Data do fim do evento:</strong></label><br />
  <input type="text" name="evento_fim" value="<?php echo $fim; ?>" /></p>
  <p><label><strong>Início das inscrições:</strong></label><br />
  <input type="text" name="evento_inscricao_inicio" value="<?php echo $inscricao_inicio; ?>" /></p>
  <p><label><strong>Fim das inscrições:</strong></label><br />
  <input type="text" name="evento_inscricao_fim" value="<?php echo $inscricao_fim; ?>" /></p>
  <p><label><strong>Local:</strong></label><br />
  <input type="text" name="evento_local" value="<?php echo $local ?>" /></p>
  <p><label><strong>Site do evento:</strong></label><br />
  <input type="text" name="evento_site" value="<?php echo $site ?>" /></p>
  <p><label><strong>Vagas:</strong></label><br />
  <input type="text" name="evento_vagas" value="<?php echo $vagas ?>" /></p>
  <p><label><strong>E-mail para inscrição:</strong></label><br />
  <input type="text" name="evento_recipient" value="<?php echo $recipient ?>" /></p>
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
  
  update_post_meta($post_id, 'evento_tipo', $_POST['evento_tipo']);
  update_post_meta($post_id, 'evento_inicio', $_POST['evento_inicio']);
  update_post_meta($post_id, 'evento_fim', $_POST['evento_fim']);
  update_post_meta($post_id, 'evento_inscricao_inicio', $_POST['evento_inscricao_inicio']);
  update_post_meta($post_id, 'evento_inscricao_fim', $_POST['evento_inscricao_fim']);
  update_post_meta($post_id, 'evento_local', $_POST['evento_local']);
  update_post_meta($post_id, 'evento_site', $_POST['evento_site']);
  update_post_meta($post_id, 'evento_vagas', $_POST['evento_vagas']);
  update_post_meta($post_id, 'evento_recipient', $_POST['evento_recipient']);

  return $post_id;
}
// END Dados do evento
?>