<?php

function WPEB_getRules(){
	return array(
		'home do site' => 'home',

		'blog' => 'blog',

		'universo tnb' => 'universo',
	
		'perfil de artista' => 'artista_single',
		'listagem de artistas' => 'artista_list',

		'perfil de produtor' => 'produtor_single',
		'listagem de produtores' => 'produtor_list',

		'visualização de evento' => 'evento_single',
		'listagem de eventos' => 'evento_list',
		
		'outras' => 'outras'
		
		);
}

function WPEB_getActiveRule(){
	global $wp_query, $wpdb;

	/*
	 * =====================================
	 LÓGICA PARA SE DESCOBRIR ONDE O USUÁRIO ESTÁ NO SITE

	 * home - função is_home() ou is_front_page()
	  
	 * perfil dos usuários - $wp_query->is_author === TRUE
	 * artista - capability artista
	 * produtor - capability produtor

	 * listagem dos usuários - $wp_query->query_vars['tpl'] == 'list_author'
	 * artistas - $wp_query->query_vars['reg_type'] == 'artista'
	 * produtores - $wp_query->query_vars['reg_type'] == 'produtor'

	 * listagem blog - $wp_query->post->post_type == 'page' && $wp_query->post->post_name == 'blog'
			
	 * single blog - $wp_query->is_single === TRUE && $wp_query->post->post_type == ''

	 * página de evento - $wp_query->is_single === TRUE && $wp_query->post->post_type == 'eventos'

	 * listagem de eventos - $wp_query->query_vars['tpl'] == 'list' && $wp_query->post->post_type == 'eventos'
	 	
	 */
	
	// se estiver na listagem do blog ou num post do blog
	if(($wp_query->query_vars['pagename'] == 'blog'  ) OR ($wp_query->is_single === TRUE && $wp_query->post->post_type == 'post') OR $wp_query->is_archive === true )
		return 'blog';

	// listagem de eventos
	if($wp_query->query_vars['tpl'] == 'list' && $wp_query->post->post_type == 'eventos')
		return 'evento_list';

	 
	// página de evento
	if($wp_query->is_single === TRUE && $wp_query->post->post_type == 'eventos')
		return 'evento_single';

	// perfil de usuário
	if( isset($wp_query->query_vars["author_name"]) && $wp_query->queried_object){
		$cap = $wpdb->prefix.'capabilities';

		// artista
		if(@array_key_exists('artista',$wp_query->queried_object->$cap))
			return 'artista_single';
		 
		// produtor
		if(@array_key_exists('produtor',$wp_query->queried_object->$cap))
			return 'produtor_single';

	}
	 
	// listagem de usuários
	if($wp_query->query_vars['tpl'] == 'list_author'){
		if($wp_query->query_vars['reg_type'] == 'universo')
			return 'universo';
		
		if($wp_query->query_vars['reg_type'] == 'artistas')
			return 'artista_list';
		 
		if($wp_query->query_vars['reg_type'] == 'produtores')
			return 'produtor_list';

	}
	
	// se está na home
	if(is_home() or is_front_page())
		return 'home';
		
	return 'outras';
}
