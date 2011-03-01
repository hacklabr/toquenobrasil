<?php 
require_once('../../../wp-load.php');
//var_dump($_POST);
$errors = array();
if(isset($_POST['action']) AND $_POST['action'] == 'user.update.local'){
    // VALIDAÇÃO DA LOCALIDADE DE ARTISTA
    if($_POST['capability'] == 'artista'){
        // PARA ARTISTAS BRASILEIROS
        if($_POST['origem_pais'] == 'BR') {
            if($_POST['origem_estado'] == '')
                $errors[] = "estado de origem.";
                
            if($_POST['origem_cidade'] == '')
                $errors[] = "cidade de origem";
                
        // PARA ARTISTAS NÃO BRASILEIROS
        }else if($_POST['origem_pais'] != 'BR' AND $_POST['origem_cidade'] == '')
            $errors[] =  "cidade de origem.";
            
        //
        if($_POST['banda_pais'] == 'BR'){
            if($_POST['banda_estado'] == '')
                $errors[] = "estado de residência";
                
            if($_POST['banda_cidade'] == '')
            $errors[] = "cidade de residência";
            
        }else if($_POST['banda_pais'] != 'BR' AND $_POST['banda_cidade'] == '')
            $errors[] =  "cidade de residência";
    }
    
    // VALIDAÇÃO DA LOCALIDADE DE PRODUTOR
    if($_POST['capability'] == 'produtor'){
        if($_POST['origem_pais'] == 'BR'){
            if($_POST['origem_estado'] == '')
                  $errors[] = "estado de origem.";
                  
            if($_POST['origem_cidade'] == '')
                  $errors[] = "cidade de origem.";
            
        }else if($_POST['origem_pais'] != 'BR' AND $_POST['origem_cidade'] == '')
            $errors[] = "cidade de origem.";
    }
}
if($errors){
    echo "Por favor, selecione os seguintes campos:";
    foreach($errors as $error) echo "\n * ".$error;
}else{

    $query_origem_pais = "
        UPDATE 
            $wpdb->usermeta 
        SET 
            meta_value = '".$_POST['origem_pais']."' 
        WHERE 
            meta_key = 'origem_pais' AND
            user_id = '".$_POST['user_id']."'";

    $query_origem_estado = "
        UPDATE 
            $wpdb->usermeta 
        SET 
            meta_value = '".$_POST['origem_estado']."' 
        WHERE 
            meta_key = 'origem_estado' AND
            user_id = '".$_POST['user_id']."'";

    $query_origem_cidade = "
        UPDATE 
            $wpdb->usermeta 
        SET 
            meta_value = '".$_POST['origem_cidade']."' 
        WHERE 
            meta_key = 'origem_cidade' AND
            user_id = '".$_POST['user_id']."'";
    
    $wpdb->query($query_origem_pais);
    $wpdb->query($query_origem_estado);
    $wpdb->query($query_origem_cidade);
    
    if($_POST['capability'] == 'artista'){
        
        $query_banda_pais = "
            UPDATE 
                $wpdb->usermeta 
            SET 
                meta_value = '".$_POST['origem_pais']."' 
            WHERE 
                meta_key = 'banda_pais' AND
                user_id = '".$_POST['user_id']."'";

        $query_banda_estado = "
            UPDATE 
                $wpdb->usermeta 
            SET 
                meta_value = '".$_POST['origem_estado']."' 
            WHERE 
                meta_key = 'banda_estado' AND
                user_id = '".$_POST['user_id']."'";

        $query_banda_cidade = "
            UPDATE 
                $wpdb->usermeta 
            SET 
                meta_value = '".$_POST['origem_cidade']."' 
            WHERE 
                meta_key = 'banda_cidade' AND
                user_id = '".$_POST['user_id']."'";
                
        $wpdb->query($query_banda_pais);
        $wpdb->query($query_banda_estado);
        $wpdb->query($query_banda_cidade);
    
    }
    
    echo "OK";
}
?>
