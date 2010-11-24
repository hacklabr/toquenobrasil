<?php 
include('../../../wp-config.php');
if(!current_user_can('edit_users')){
    wp_die("Você não tem permissão para acessar esta página");
    die;
}
    
    
    
$inscritos =  get_post_meta($_GET['evento'], 'inscrito');
$selecionados =  get_post_meta($_GET['evento'], 'selecionado');


foreach ($inscritos as &$inscrito){
    $inscrito = get_userdata($inscrito);
}

foreach ($selecionados as &$selecionado){
    $selecionado = get_userdata($selecionado);
}

//var_dump($inscritos, $selecionado);

if($inscritos){
    if(!is_array($inscritos))
        $inscritos = array($inscritos);
    
    echo "INSCRITOS:<br/>";    
    foreach($inscritos as $usu){
        echo $usu->banda, ", ", $usu->user_email , "<br/>"; 
    }
}



if($selecionados){
    if(!is_array($selecionados))
        $selecionados = array($selecionados);
    
    echo "ELECIONADOS:<br/>";    
    foreach($selecionados as $usu){
        echo $usu->banda, ", ", $usu->user_email , "<br/>"; 
    }
}