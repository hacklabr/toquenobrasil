<?php
    global $wpdb; 
    
    $filename = "artists_in_{$post->post_name}.xls";

    $subeventos = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_type = 'eventos' AND post_status = 'publish' AND post_parent = " . get_the_ID() );
    
    
    
    

    $fields = array('display_name' => __("Banda"),
                    'profile_link' => __("Link do perfil"),
                    'responsavel' => __("Responsável"),
                    'telefone' => __("Telefone"),
                    'user_email' => __("Email"),
                    'origem_cidade' => __("Cidade de origem"),
                    'origem_estado' => __("Estado de origem"),
                    'origem_pais' => __("Pais de origem"),
                    'banda_cidade' => __("Cidade de residencia"),
                    'banda_estado' => __("Estado de residência"),
                    'banda_pais' => __("País de residência"));

    header('Pragma: public'); 
    header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    header('Content-Transfer-Encoding: none'); 
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');                 // This should work for IE & Opera 
    header("Content-type: application/x-msexcel; charset=utf-8");                    // This should work for the rest 
    header("Content-Language: pt");
    header('Content-Disposition: attachment; filename="'.$filename.'"'); 
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="Content-Language" content="pt"/>
    </head>
    <body>
        <table width="1000">
            <thead>
                <?php foreach($fields as $f): ?>
                <th><?php echo $f;?></th>
                <?php endforeach;?>
                <th>Evento</th>
            </thead>
            <tbody>


            <?php foreach ($subeventos as $subevento): ?>
            
                <?php $candidates = get_post_meta($subevento->ID, $_GET['exportar']); ?>

                <?php foreach($candidates as $candidate):
                    $data = get_userdata($candidate);
                    if($data):
                        // transforma o objeto para um array associativo
                        $data = get_object_vars($data);
                        $data['profile_link'] = get_author_posts_url($candidate);
                        ?>
                        <tr>
                            <?php foreach($fields as $key => $field): $value = $data[$key];?>
                                <td><?php echo $value;?></td>
                            <?php endforeach;?>
                            <td><?php echo $subevento->post_title; ?></td>
                        </tr>
                    <?php endif;
                endforeach;?>
                
            <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>