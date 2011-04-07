<?php
/*
Plugin Name: WP Easy Banners
Plugin URI: 
Description: WP Easy Banners
Author: hacklab
Version: 1.0
*
*/

/*

		global $wp_roles, $wp_query;
     	$roles = $wp_roles->roles;
     	
     	
		$opcoesParaPosicoes['home'] = 'home';
		$opcoesParaPosicoes['páginas'] = 'pages';
		
		
		$postTypes = get_post_types();
		foreach($postTypes as $postType){
			if($postType != 'page' && $postType != 'attachment' && $postType != 'revision' && $postType != 'nav_menu_item'){
				$type = get_post_type_object($postType);
				$type = $type->labels;
				$opcoesParaPosicoes['listagem de '.$type->name] = 'pt_'.$postType.'_list';
				$opcoesParaPosicoes['visualização de '.$type->singular_name] = 'pt_'.$postType.'_single';
			}
		}
		
		foreach($roles as $role){
			
			if($role['name'] != 'Administrator' && $role['name'] != 'Editor' && $role['name'] != 'Author' &&  $role['name'] != 'Contributor' &&  $role['name'] != 'Subscriber'){
				$opcoesParaPosicoes['listagem de '.$role['name']] = 'pt_'.$role['name'].'_list';
				$opcoesParaPosicoes['visualização de '.$role['name']] = 'pt_'.$role['name'].'_single';
			}
		}
*/



function WPEB_Init(){
	global $wpeb_positions, $wpeb_banners;
	if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
		
		$opcoesParaPosicoes = WPEB_getRules();
		
		$model_positions = array(
			'fields' => array(
				array(
					'name' => 'nome',
			        'display_name' => 'Nome',
			        'type' => 'textfield',
			        'list_display' => true,
			        'description' => 'Nome da posição (ex: Topo, Banners da esquerda)'
				),
	            array(
	                'name' => 'tipo_rotacao',
	                'display_name' => 'Tipo de rotação',
	                'type' => 'select',
	                'values' => array('por tempo' => 'tempo', 'por carregamento' => 'carregamento'),
	                'list_display' => false,
	                'description' => 'Se houver mais de um banner nesta posição, como eles devem se intercalar?'
	            ),
	            array(
	                'name' => 'tempo_rotacao',
	                'display_name' => 'Tempo de rotação',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Se o tipo de rotação for "por tempo". De quantos em quantos segundos os banners devem trocar?',
	                'default' => 0
	            ),
	            array(
	                'name' => 'exibir_em',
	                'display_name' => 'Exibir em',
	                'type' => 'checkboxes',
	                'list_display' => true,
	                'description' => '',
	                'values' => $opcoesParaPosicoes,
	            	'br' => true
	            )
			),
	        'sortable' => true,
	        'tableName' => 'wpeb_positions',
	        'adminName' => 'Posições para os banners',
	        'topMenuName' => 'Banners'
        			
		);
		
		$wpeb_positions = new Wp_easy_data($model_positions, __FILE__);
		
		
		$opcoesDaPosicao = array();
		
		if(isset($_GET['positionID'])){
			
			$current_position = $wpeb_positions->get_item((int)$_GET['positionID']);
			
			foreach($current_position->fields as $field){
				if($field['name'] == 'exibir_em'){
					foreach($field['values'] as $key=>$value)
						if(isset($current_position->info->exibir_em[$value]))
							$opcoesDaPosicao[$key] = $value;
					continue;
				}
			}
			
		}
		
		
		// BANNERS 
		$model_banner = array(
			'fields' => array(
				array(
	                'name' => 'positionID',
	                'display_name' => '',
	                'type' => 'hiddenInt',
	                'list_display' => true,
	                'description' => '',
	                'value' => $_GET['positionID']
	            ),
	            array(
	                'name' => 'nome',
	                'display_name' => 'Nome',
	                'type' => 'textfield',
	                'list_display' => true,
	                'description' => 'Nome para controle interno (ex: Banner da Padaria)'
	            ),
	            array(
	                'name' => 'views',
	                'display_name' => 'Views',
	                'type' => 'hiddenInt',
	                'list_display' => true,
	                'description' => 'Numero de exibições',
	                'default' => 0
	                
	            ),
	            array(
	                'name' => 'clicks',
	                'display_name' => 'Clicks',
	                'type' => 'hiddenInt',
	                'list_display' => true,
	                'description' => 'Numero de cliques',
	                'default' => 0
	            ),
	            array(
	                'name' => 'image',
	                'display_name' => 'Image',
	                'type' => 'file',
	                'list_display' => true,
	                'description' => 'Arquivo do banner: imagem (gif, jpg ou png) ou flash (swf)'
	            ),
	            array(
	                'name' => 'link',
	                'display_name' => 'Link',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Link completo (com http) para onde o banner aponta'
	            ),
	            array(
	                'name' => 'html',
	                'display_name' => 'HTML extra',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Use essa área para códigos HTML complementares, como pixel contadores'
	            ),
	            array(
	                'name' => 'target',
	                'display_name' => 'Abrir:',
	                'type' => 'select',
	                'values' => array('Nova janela' => '_blank', 'Na mesma janela' => '_self'),
	                'default' => '_self',
	                'list_display' => false,
	                'description' => 'Quando clicado, abrir o destino em uma nova janela ou na mesma janela?'
	            ),
	            array(
	                'name' => 'width',
	                'display_name' => 'Largura',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Em caso de arquivo SWF, é preciso informar a largura, em pixels'
	            ),
	            array(
	                'name' => 'height',
	                'display_name' => 'Altura',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Em caso de arquivo SWF, é preciso informar a altura, em pixels'
	            ),
	            array(
	                'name' => 'peso',
	                'display_name' => 'Peso',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Quando dividindo um mesmo local com outros banners, o peso definirá qual banner aparecerá mais vezes'
	            ),
	            array(
	                'name' => 'data_expiracao',
	                'display_name' => 'Expiração por data',
	                'type' => 'date',
	                'list_display' => false,
	                'description' => 'A partir desta data, este banner não será mais exibido (deixe vazio para ilimitado)'
	            ),
	            array(
	                'name' => 'clicks_expiracao',
	                'display_name' => 'Expiração por cliks',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Quando atingir este número de clicks este banner não será mais exibido (0 para ilimitado)'
	            ),
	            array(
	                'name' => 'views_expiracao',
	                'display_name' => 'Expiração por visualizações',
	                'type' => 'textfield',
	                'list_display' => false,
	                'description' => 'Quando atingir este número de visualizações este banner não será mais exibido (0 para ilimitado)'
	            ),
	            array(
	                'name' => 'ocultar_em',
	                'display_name' => 'Ocultar em',
	                'type' => 'checkboxes',
	                'list_display' => true,
	                'description' => '',
	                'values' => $opcoesDaPosicao,
	            	'br' => true
	            )
		            			),
	        'sortable' => true,
	        'tableName' => 'wpeb_banners',
	        'parent_menu' => __FILE__
		);
				
		
		$wpeb_banners = new Wp_easy_data($model_banner, __FILE__);
		
		
		add_filter('wp-easy-data-list', 'WPEB_FilterList');
		$wpeb_banners->add_action('wp-easy-data-form-end', 'WPEB_PositionID');
	}
}

// adciona as posições já existentes ao menu
function WPEB_addMenuItens(){
    if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
    	global $wpeb_positions, $wpeb_banners;
    	$positions = $wpeb_positions->get_items();
    	//die(var_dump($positions));
    	foreach ($positions as $position){
    		add_submenu_page(basename(__FILE__), $position->info->nome, $position->info->nome, 8, 'admin.php?page=manage_wpeb_banners&positionID='.$position->info->ID.'&positionName='.$position->info->nome, array(&$banners, 'admin'));
    	}
    }
}

// filtra a lista de banners para a posição selecionada
function WPEB_FilterList(){
    if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
    	echo "banners da posição <strong>".$_GET['positionName'].'</strong>';
    	if(isset($_GET['positionID']))
    		return "positionID='".$_GET['positionID']."'";
    	else 
    		return '';
    }
}

// adciona um input hidden com o id da posição atual no formulário de inserção de banner
function WPEB_PositionID(){
	echo "<input type='hidden' name='positionID' value='".$_GET['positionID']."' />";
	
}

function WPEB_getPositions(){
    if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
    	global $wpeb_positions, $wpeb_positions_cache;
    	
    	if($wpeb_positions_cache)
    		return $wpeb_positions_cache;
    
    	$wpeb_positions_cache = array();
    	
    	$positions = $wpeb_positions->get_items();
    	foreach ($positions as $position){
    		$wpeb_positions_cache[$position->info->nome] = $position->info;
    	}
    	
    	return $wpeb_positions_cache;
    }
}

function WPEB_getPageBanners(){
    if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
    	global $wpdb, $wpeb_page_banners;
    	
    	if($wpeb_page_banners)
    		return $wpeb_page_banners;
    		
    	$wpeb_page_banners = array();
    	
    	$rule = WPEB_getActiveRule();
    	
    	$table_positions = $wpdb->prefix.'wpeb_positions';
    	$table_banners = $wpdb->prefix.'wpeb_banners';
    	$query = "
    	SELECT 
    		$table_banners.*,
    		$table_positions.nome AS pslug
    	FROM 
    		$table_banners,
    		$table_positions
    	WHERE
    		$table_positions.exibir_em LIKE '%\"$rule\"%' AND
    		$table_banners.positionID = $table_positions.ID AND
    		$table_banners.ocultar_em NOT LIKE '%\"$rule\"%'";
    	
    	
    	$rs = $wpdb->get_results($query);
    	
    	foreach ($rs as $r){
    		$wpeb_page_banners[$r->pslug][] = $r;
    	} 
    	return $wpeb_page_banners;
    }
}

// imprime o banner na tela
function WPEB_printBanner($positionSlug){
    if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
    	global $user_ID;
    	if (class_exists('Wp_easy_data')) {
    		
    		
    		$positions = WPEB_getPositions();
    		$position = $positions[$positionSlug];
    		
    		
    		$logged = $user_ID ? 1 : 0;
    		
    		echo "<script type='text/javascript'>\n";
    		echo "/* <![CDATA[ */\n";
    		
    			if ($position->tipo_rotacao == 'tempo') {
                    echo "wpeb_print_banners_local_tempo('$positionSlug', $logged, {$position->tempo_rotacao}); \n";
                } else {
                    echo "wpeb_print_banners_local('$positionSlug', $logged); \n";
                }
    		
    		echo "/* ]]> */\n";
        	echo "</script>";
    	    	
        }
    }
} 

function WPEB_countBanners($positionSlug){
    if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
    	$pageBanners = WPEB_getPageBanners();
    	if(!isset($pageBanners[$positionSlug]) or !is_array($pageBanners[$positionSlug]))
    		return 0;
    	else
    		return count($pageBanners[$positionSlug]);
    }
}


function WPEB_init_js_vars() {
    if(class_exists('Wp_easy_data') && function_exists('WPEB_getRules') && function_exists('WPEB_getActiveRule')){
        
        $pluginFolder = plugin_basename( dirname(__FILE__) );
        $jsurl = WP_CONTENT_URL . "/plugins/$pluginFolder/wpeb.js";
        $baseurl = WP_CONTENT_URL . "/plugins/$pluginFolder/";
    
        echo "<script type='text/javascript'>\n";
        echo "/* <![CDATA[ */\n";
        echo "var wpeb_baseurl = '$baseurl';\n";
        echo "var wpeb = new Array();\n";
        echo "var wpeb_timers = new Array();\n";
        
        // Um array para cada posicao
        $banners = WPEB_getPageBanners();
        $pslugs = array_keys($banners);
        foreach ($pslugs as $pslug) {
        	$i = 0;
        	
            echo "wpeb['$pslug'] = new Array()\n";   
            
            foreach ($banners[$pslug] as $banner){
            	 	$img = wp_get_attachment_metadata($banner->image, 'full');
                    $src = $img['file'];
                    $imgTag = wp_get_attachment_image($banner->image, 'full');
                    $is_image = true;
                    // may be a SWF, but certainly isn't a image 
                    if(sizeof($img) == 0){
                        $up = wp_upload_dir();
                        $src = $up['baseurl'] . '/' . get_post_meta($banner->image, '_wp_attached_file', true);
                        
                        $is_image = false;                        
                    }   
                    
                    $local = pslug;
                    
                    echo "wpeb['$pslug'][$i] = new Array();\n";
                    echo "wpeb['$pslug'][$i]['ID'] = '" . addslashes($banner->ID) . "';\n";
                    echo "wpeb['$pslug'][$i]['file'] = '" . addslashes($src) . "';\n";
                    echo "wpeb['$pslug'][$i]['is_image'] = '" . $is_image . "';\n";
                    echo "wpeb['$pslug'][$i]['link'] = '" . addslashes($banner->link) . "';\n";
                    echo "wpeb['$pslug'][$i]['name'] = '" . addslashes($banner->nome) . "';\n";
                    echo "wpeb['$pslug'][$i]['html'] = '" . addslashes($banner->html) . "';\n";
                    echo "wpeb['$pslug'][$i]['target'] = '" . addslashes($banner->target) . "';\n";
                    echo "wpeb['$pslug'][$i]['peso'] = '" . addslashes( ( is_numeric($banner->peso) && $banner->peso != 0 ? $banner->peso : 1 )) . "';\n";
                    echo "wpeb['$pslug'][$i]['width'] = '" . addslashes($banner->width) . "';\n";
                    echo "wpeb['$pslug'][$i]['height'] = '" . addslashes($banner->height) . "';\n";
                    echo "wpeb['$pslug'][$i]['posicao_ID'] = '" . $banner->positionID . "';\n";
                    
                    $i++;
            }
        }
         
                
              
				
		echo "/* ]]> */\n";
		echo "</script>";
        
        
        wp_enqueue_script('wpeb', $jsurl);
    }
}


add_action('init', 'WPEB_Init');
add_action('admin_menu', 'WPEB_addMenuItens');


add_action('wp_print_scripts', 'WPEB_init_js_vars');
