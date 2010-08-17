<?php

// CUSTOM POST TYPES
add_action( 'init', 'theme_post_type_init' );

function theme_post_type_init() {

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

  $data = get_post_meta($post->ID, "evento_data", true);
  $inscricao_inicio = get_post_meta($post->ID, "eventos_inscricao_inicio", true);
  $inscricao_fim = get_post_meta($post->ID, "eventos_inscricao_fim", true);
  $local = get_post_meta($post->ID, "eventos_local", true);
  $site = get_post_meta($post->ID, "eventos_site", true);
  $vagas = get_post_meta($post->ID, "eventos_vagas", true);
  $recipient = get_post_meta($post->ID, "eventos_recipient", true);
  
  ?>

  <input type="hidden" name="eventos_noncename" id="eventos_noncename" value="<?php echo wp_create_nonce( "eventos_noncename" ); ?>" />


  <p><label><strong>Data do evento:</strong></label><br />
  <input type="text" name="evento_data" value="<?php echo $data; ?>" /></p>
  <h4>Inscrições</h4>
  <p><label><strong>Início:</strong></label><br />
  <input type="text" name="eventos_inscricao_inicio" value="<?php echo $inscricao_inicio; ?>" /></p>
  <p><label><strong>Fim:</strong></label><br />
  <input type="text" name="eventos_inscricao_fim" value="<?php echo $inscricao_fim; ?>" /></p>
  <p><label><strong>Local:</strong></label><br />
  <input type="text" name="eventos_local" value="<?php echo $local ?>" /></p>
  <p><label><strong>Site do evento:</strong></label><br />
  <input type="text" name="eventos_site" value="<?php echo $site ?>" /></p>
  <p><label><strong>Vagas:</strong></label><br />
  <input type="text" name="eventos_vagas" value="<?php echo $vagas ?>" /></p>
  <p><label><strong>E-mail para inscrição:</strong></label><br />
  <input type="text" name="eventos_recipient" value="<?php echo $recipient ?>" /></p>
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
  
  update_post_meta($post_id, 'evento_data', $_POST['evento_data']);
  update_post_meta($post_id, 'eventos_inscricao_inicio', $_POST['eventos_inscricao_inicio']);
  update_post_meta($post_id, 'eventos_inscricao_fim', $_POST['eventos_inscricao_fim']);
  update_post_meta($post_id, 'eventos_local', $_POST['eventos_local']);
  update_post_meta($post_id, 'eventos_site', $_POST['eventos_site']);
  update_post_meta($post_id, 'eventos_vagas', $_POST['eventos_vagas']);
  update_post_meta($post_id, 'eventos_recipient', $_POST['eventos_recipient']);

  return $post_id;
}
// END Dados do evento
?>