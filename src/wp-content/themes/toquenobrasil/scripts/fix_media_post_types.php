<?php
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
require_once('../../../../wp-load.php');

if (!current_user_can('manage_options'))
  die ('sem permissao');
else
  echo 'iniciando script<hr/>';
          
                
$sql_select_artistas = "SELECT * FROM $wpdb->users WHERE ID IN (SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$wpdb->prefix"."capabilities' AND meta_value LIKE '%artista%')";

$artistas = $wpdb->get_results($sql_select_artistas);

// para cada artista...
$i = 1;
foreach ($artistas as $artista){
	echo "<strong>$i - $artista->user_login (id: $artista->ID)</strong><br />";
	$i++;
	// ================ CRIA OS REPOSITÓRIOS =============== //
	
	echo "<div>criando posts: ";
	
    // ------------------------ POST MUSICAS -------------------- //
	echo "musicas ";
	$musicas = array(
        'post_author' => $artista->ID,
        'post_type' => 'music',
        'post_title' => "músicas de: $artista->user_login" 
    );
    
    $post_musicas = wp_insert_post($musicas);
    wp_publish_post($post_musicas);
	echo $post_musicas ? '(OK)' : '<strong>(ERROR)</strong>';
	
	
    // ------------------------ POST IMAGENS -------------------- //
	echo "; imagens ";
	$imagens = array(
        'post_author' => $artista->ID,
        'post_type' => 'images',
        'post_title' => "imagens de: $artista->user_login"
    );
    $post_imagems = wp_insert_post($imagens);
    wp_publish_post($post_imagems);
    echo $post_imagems ? '(OK)' : '<strong>(ERROR)</strong>';
	
    // ------------------------ POST MAPAS -------------------- //
    echo "; mapas ";
    $mapas = array(
        'post_author' => $artista->ID,
        'post_type' => 'mapa_palco',
        'post_title' => "mapas de palco de: $artista->user_login"
    );
    
    $post_mapas = wp_insert_post($mapas);
    wp_publish_post($post_mapas);
    echo $post_mapas ? '(OK)' : '<strong>(ERROR)</strong>';
    

    // ------------------------ POST RIDERS -------------------- //
    echo "; riders ";
    $rider = array(
        'post_author' => $artista->ID,
        'post_type' => 'rider',
        'post_title' => "riders de: $artista->user_login"
    );
    
    $post_riders = wp_insert_post($rider);
    wp_publish_post($post_riders);
    echo $post_riders ? '(OK)' : '<strong>(ERROR)</strong>';
    
    echo '</div>';
    
    
    // ================ CORRIGE OS ARQUIVOS =============== //
    echo '<div> corrigindo arquivos';
    // se inseriu o post musicas
	if($post_musicas){
	    // corrige as músicas já existentes
	    $sql_update_musicas = "
	        UPDATE
	            $wpdb->posts
	        SET
	            post_parent = '$post_musicas',
	            post_type = 'attachment'
	        WHERE
	            post_author = '$artista->ID' AND
	            post_mime_type <> '' AND
	            post_type = 'music'";
	            
	    $wpdb->query($sql_update_musicas);
	}
	
    // se inseriu o post imagens
    if($post_musicas){
    	
        // corrige as imagens já existentes
        $sql_update_imagens = "
            UPDATE
                $wpdb->posts
            SET
                post_parent = '$post_imagens',
                post_type = 'attachment'
            WHERE
                post_author = '$artista->ID' AND
                post_mime_type <> '' AND
                post_type = 'images'";
                
        $wpdb->query($sql_update_imagens);
    }
    
    // se inseriu o post riders
    if($post_riders){
        // corrige as riders já existentes
        $sql_update_riders = "
            UPDATE
                $wpdb->posts
            SET
                post_parent = '$post_riders',
                post_type = 'attachment'
            WHERE
                post_author = '$artista->ID' AND
                post_mime_type <> '' AND
                post_type = 'rider'";
                
        $wpdb->query($sql_update_riders);
    }

    // se inseriu o post mapas
    if($post_mapas){
        // corrige os mapas já existentes
        $sql_update_mapas = "
            UPDATE
                $wpdb->posts
            SET
                post_parent = '$post_mapas',
                post_type = 'attachment'
            WHERE
                post_author = '$artista->ID' AND
                post_mime_type <> '' AND
                post_type = 'mapa_palco'";
                
        $wpdb->query($sql_update_mapas);
    }
    
    echo "</div><hr /><br />";
}
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "<br /><br />This page was created in ".$totaltime." seconds<br/>"; 
   echo "QUERIES: ".$wpdb->num_queries;