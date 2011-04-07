<?php 

$estados = get_estados();
$paises = get_paises();

wp_enqueue_script('campo-cidade', get_stylesheet_directory_uri(). '/js/campo-cidade.js',array('jquery'));

if(isset($_REQUEST['tnb_user_action']) && $_REQUEST['tnb_user_action'] == 'edit-produtor'){
   
    if($_POST['origem_pais'] == 'BR' && $_POST['origem_estado'] == '')
      $msg['error'][] = "Por favor informe o estado de origem.";
    
    if($_POST['origem_cidade'] == '')
      $msg['error'][] = "Por favor informe a cidade de origem.";
            
     if($_POST['cpf'] != '' && !is_a_valid_cpf($_POST['cpf']))
        $msg['error'][] = "Informe um CPF válido.";
    
    if($_POST['cnpj'] != '' && !is_a_valid_cnpj($_POST['cnpj']))
        $msg['error'][] = "Informe um CNPJ válido.";

    if($_POST['email_publico'] != '' && !filter_var( $_POST['email_publico'], FILTER_VALIDATE_EMAIL))
        $msg['error'][] = __('O email informado é inválido.','tnb');
      
    if( !$msg['error']){
        $userdata['ID'] = $profileuser->ID;
        $userdata['display_name'] = $_POST['nome_produtor'];
        
        $rt = wp_update_user($userdata);
        
        $profileuser->display_name = $_POST['nome_produtor'];
        
        $updateMetaFields = array(
            'origem_pais' , 
            'origem_estado' , 
            'origem_cidade' , 
            'email_publico',
            'responsavel',
            'telefone',
            'facebook' , 
            'twitter' , 
            'orkut' , 
            'youtube' , 
            'vimeo',
            'cpf' , 
            'cnpj' , 
        );
        
        foreach ($updateMetaFields as $field) {
            
            // Salva no banco
            update_user_meta( $profileuser->ID, $field , $_POST[$field] );
            
            // Atualiza usuário para visualização
            $profileuser->$field = $_POST[$field];
            
        }
        
        
        $msg['success'][] = __('Dados Atualizados', 'tnb');
        //$profileuser = get_userdata( $user_ID );
        
    }else{

        foreach($_POST as $n=>$v)
            $profileuser->{$n} = $v;

    }
    
   
}

$usuarioOK = tnb_contatoUsuarioCorreto($profileuser);

?>
