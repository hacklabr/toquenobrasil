<?php

    $candidates = get_post_meta(get_the_ID(), $_GET['exportar']);
    $result = "";

    $fields = array('user_nicename', 'responsavel', 'first_name', 'last_name',
                    'nickname', 'banda', 'banda_cidade', 'banda_estado',
                    'origem_cidade', 'origem_estado', //'description',
                    'estado', 'integrantes', 'site', 'telefone_ddd',
                    'user_email', 'user_url', 'telefone_ddd', 'telefone',
                    'aim', 'jabber', 'yim', 'youtube');

    header('Content-Type: application/csv');
    header('Content-disposition: attachment; filename=TNB_Users_' . date('Y-m-d') . '.csv');
 
    // label das colunas da planilha csv
    echo implode(';', $fields).';link_perfil';

    foreach($candidates as $candidate) {
        echo "\n";
        
        $data = get_userdata($candidate);
        
        if($data) {
            // transforma o objeto para um array associativo
            $data = get_object_vars($data);

            foreach($fields as $field) {
                $value = $data[$field];
                echo '"' . $value . '"' . ';';
            }

            // link_perfil
            echo '"' . get_author_posts_url($candidate) . '"';
        }
    }
    die;

