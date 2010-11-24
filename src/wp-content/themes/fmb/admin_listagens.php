<?php


add_action('admin_menu', 'fmb_menus');

function fmb_menus() {

    add_submenu_page( basename(__FILE__), 'Por Categoria', 'Por Categoria', 'manage_options', basename(__FILE__), 'fmb_listagem_catgegoria_page' );
    add_menu_page( 'Listagens Úteis', 'Listagens de Inscritos', 'manage_options', basename(__FILE__), 'fmb_listagem_catgegoria_page');
    add_submenu_page( basename(__FILE__), 'Inativos', 'Inativos', 'manage_options', 'listagem_inativos', 'fmb_listagem_inativos_page' );
    add_submenu_page( basename(__FILE__), 'Incompletos', 'Incompletos', 'manage_options', 'listagem_incompletos', 'fmb_listagem_incompletos_page' );
    add_submenu_page( basename(__FILE__), 'Válidos', 'Válidos', 'manage_options', 'listagem_validos', 'fmb_listagem_validos_page' );

}

function fmb_listagem_catgegoria_page() {
    
    

    ?>
    <div class="wrap">
    Selecione a categoria e subcategoria<br/>
    
    <?php 
                                
    global $fmb_categorias, $fmb_subcategorias;
    ?>
    
    <?php foreach($fmb_categorias as $cat_value => $cat_name) : ?>
                                
        <a href="<?php echo add_query_arg('categoria', $cat_value); ?>"><?php echo $cat_name; ?></a><br/>
        
    <?php endforeach; ?>
    
    <?php foreach($fmb_subcategorias as $cat_value => $cat_name) : ?>
        <a href="<?php echo add_query_arg(array('categoria' => '', 'subcategoria' => $cat_value)); ?>"><?php echo $cat_name; ?></a><br/>
    <?php endforeach; ?>
    <br/>
    <?php 

    if ($_GET['categoria'] != '' || $_GET['subcategoria'] != '') {
        global $wpdb; 
        if ($cat = $_GET['categoria']) {
        
            $query = "SELECT ID FROM $wpdb->users JOIN $wpdb->usermeta ON user_id = $wpdb->users.ID WHERE meta_key = 'categoria' AND meta_value = '$cat'";
        
        } elseif ($cat = $_GET['subcategoria']) {
            $query = "SELECT ID FROM $wpdb->users JOIN $wpdb->usermeta ON user_id = $wpdb->users.ID WHERE meta_key = 'subcategoria' AND meta_value = '$cat'";
        }
        
        ?>
        
        <h1>Inscritos na categoria "<?php echo $cat; ?>"</h1>
        
        <?php fmb_listagem($query); ?>
        
        <?php 
    
    }
    ?>
    </div>
    <?php
    
}

function fmb_listagem_inativos_page() {
    
    global $wpdb;
    
    $query = "SELECT ID FROM $wpdb->users JOIN $wpdb->usermeta ON user_id =  $wpdb->users.ID WHERE meta_key = 'tnb_inactive' and meta_value = 1";
    
    ?>
    <div class="wrap">
    <h1>Usuários Inativos</h1>
    <p>São aqueles que se cadastraram mas não confirmaram sua inscrição através do email de confirmação</p>
    <?php fmb_listagem($query); ?>
    
    </div>
    
    <?php 
    
}


function fmb_listagem_incompletos_page() {
    
    global $wpdb;
    
    $validUsers = fmb_get_IDS_usuarios_completos();
    
    $query = "SELECT ID FROM $wpdb->users WHERE ID NOT IN (" . implode(',', $validUsers) . ")";
    
    
    
    ?>
    <div class="wrap">
    <h1>Usuários com perfil incompleto</h1>
    <p>São aqueles que NÃO completaram todas as informações obrigatórias do perfil, sendo elas:
    
    <ul>
        <li>a) Nome artístico do artista/ grupo/ DJ/ conjunto de câmara.</li>
        <li>b) Release.</li>
        <li>c) Integrantes do grupo.</li>
        <li>d) 01 (uma) foto que aparecerá no perfil.</li>
        <li>e) 01 (um) link principal do trabalho (ex: MySpace, site próprio, etc.).</li>
        <li>f) Cidade de origem do artista/grupo.</li>
        <li>g) Cidade de residência atual do artista/grupo.</li>
        <li>h) Rider técnico (detalhamento da demanda técnica para o espetáculo).</li>
        <li>i) Mapa de palco.</li>
        <li>j) 03 (três) músicas de sua execução para avaliação.</li>
        <li>k) Escolha de 01 (uma) categoria de inscrição: Música popular ou Música Erudita. No caso da escolha da categoria Música Popular, escolher ainda uma entre quatro subcategorias existentes.</li>
    </ul>
    
    </p>
    <p>
    <br/>
    </p>
    
    <?php fmb_listagem($query); ?>
    
    </div>
    
    <?php 
    
}

function fmb_listagem_validos_page() {
    
    global $wpdb;
    
    $validUsers = fmb_get_IDS_usuarios_completos();
    
    $query = "SELECT ID FROM $wpdb->users WHERE ID IN (" . implode(',', $validUsers) . ")";
    
    
    
    ?>
    <div class="wrap">
    <h1>Usuários com perfil válido</h1>
    <p>São aqueles que completaram todas as informações obrigatórias do perfil, sendo elas:
    
    <ul>
        <li>a) Nome artístico do artista/ grupo/ DJ/ conjunto de câmara.</li>
        <li>b) Release.</li>
        <li>c) Integrantes do grupo.</li>
        <li>d) 01 (uma) foto que aparecerá no perfil.</li>
        <li>e) 01 (um) link principal do trabalho (ex: MySpace, site próprio, etc.).</li>
        <li>f) Cidade de origem do artista/grupo.</li>
        <li>g) Cidade de residência atual do artista/grupo.</li>
        <li>h) Rider técnico (detalhamento da demanda técnica para o espetáculo).</li>
        <li>i) Mapa de palco.</li>
        <li>j) 03 (três) músicas de sua execução para avaliação.</li>
        <li>k) Escolha de 01 (uma) categoria de inscrição: Música popular ou Música Erudita. No caso da escolha da categoria Música Popular, escolher ainda uma entre quatro subcategorias existentes.</li>
    </ul>
    
    </p>
    <p>
    <br/>
    <?php echo sizeof($validUsers); ?> usuários com perfil completo: 
    </p>
    
    <?php fmb_listagem($query); ?>
    
    </div>
    
    <?php 
    
}


function fmb_get_IDS_usuarios_completos() {
    ini_set(max_execution_time,30000);
    ini_set('memory_limit','128000M');
    global $wpdb;
    
    $campos_obrigatorios = array(
        'description', 
        'banda',
        'youtube',
        //'responsavel',
        //'telefone',
        //'telefone_ddd',
        'site',
        'origem_estado',
        'origem_cidade',
        'banda_estado',
        'banda_cidade',
        'categoria'
    );
    
    $query = "SELECT ID, user_nicename FROM $wpdb->users";
    
    $allUsers = $wpdb->get_results($query);
    
    $validUsers = array();
    
    foreach ($allUsers as $u) {
    
        $valid = true;
        
        foreach ($campos_obrigatorios as $co) {
        
            $ff = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE user_id = {$u->ID} AND meta_key = '$co' and meta_value <> ''");
            #echo "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = {$u->ID} AND meta_key = '$co' and meta_value <> ''";
            if (!$ff) {
                $valid = false;
                #echo 'a';
                break;
            }
        
        }
        
        if (!$valid)
            continue;
        
        // mapa de palco?
        $mm = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_author = {$u->ID} AND post_type = 'mapa_palco'");
        if (!$mm)
           continue;
        
        // rider?
        $mm = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_author = {$u->ID} AND post_type = 'rider'");
        if (!$mm)
           continue;
        
        // 3 músicas?        
        // rider?
        $mm = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts WHERE post_author = {$u->ID} AND post_type = 'music'");
        if (!$mm || $mm < 3)
           continue;
        
        
        //Foto de perfil ou uma foto
        if (!file_exists(WP_CONTENT_DIR . '/uploads/userphoto/' . $u->user_nicename . '.jpg')) 
{
        
            // se não tem avatar, vemos se tem uma foto normal
            $mm = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_author = {$u->ID} AND post_type = 'images'");
            if (!$mm)
               continue;
        
        }
        
        
        if ($valid) {
        
            $validUsers[] = $u->ID;
        
        }
        

    }
    
    return $validUsers;
}



function fmb_listagem($query) {
    global $wpdb;
    $users = $wpdb->get_results($query);
    
    if (!$users)
        return false;
    
    
    
    ?>
    
    <table class="widefat fixed">
        <thead>
        <tr class="thead">
            <th style="width: 20px; ">ID</th>
            <th>Nome</th>
            <th>Perfil</th>
            <th>Nome do Artista/Banda</th>
            <th>Categoria</th>
            <th>Responsável</th>
            <th>E-mail</th>
            <th>Tel</th>
            <th>Estado de origem</th>
            <th>Cidade de origem</th>
            <th>Estado residência</th>
            <th>Cidade residência</th>
            <th>Site</th>
        </tr>
        </thead>
        <?php foreach ($users as $u): $user = get_userdata($u->ID); ?>
            <tr>

                <td>
                    <?php echo $user->ID; ?>
                </td>
                <td>
                    <a href="<?php echo get_author_posts_url($user->ID); ?>">
                    <?php echo $user->display_name; ?> <br/>
                    <?php echo get_avatar($user->ID, 50); ?>
                    </a>
                </td>
                <td>
                    <a href="<?php echo get_author_posts_url($user->ID); ?>">
                    <?php echo get_author_posts_url($user->ID); ?>
                    </a>
                </td>
                <td>
                    <?php echo $user->banda; ?>
                </td>
                <td>
                    <?php if($user->categoria !=''): global $fmb_categorias, $fmb_subcategorias; ?>
                      
                      <?php echo $fmb_categorias[$user->categoria]; ?>
                      <?php if ($user->subcategoria) echo ' / ',  $fmb_subcategorias[$user->subcategoria]; ?>
                  
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $user->responsavel; ?>
                </td>
                <td>
                    <?php echo $user->user_email; ?>
                </td>
                <td>
                    <?php echo '(', $user->telefone_ddd, ') ', $user->telefone; ?>
                </td>
                <td>
                    <?php echo $user->origem_cidade; ?>
                </td>
                <td>
                    <?php echo $user->origem_estado; ?>
                </td>
                <td>
                    <?php echo $user->banda_cidade; ?>
                </td>
                <td>
                    <?php echo $user->banda_estado; ?>
                </td>
                <td>
                    <?php echo $user->site; ?>
                </td>
                
            </tr>
        <?php endforeach; ?>
        
        
    </table>
    </div>
    
    <?php


}

?>
