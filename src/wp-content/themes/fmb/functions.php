<?php

global $fmb_categorias, $fmb_subcategorias;

$fmb_categorias = array(
    'popular' => '1. Música popular',
    'erudito' => '2. Música erudita'
);

$fmb_subcategorias = array(
    'revelacao' => 'a. Revelação',
    'destaque' => 'b. Destaque',
    'renome' => 'c. Renome',
    'dj' => 'd. DJ'
);

function tnb_login_head() {
    ?>
    <style>
        #login {width: 400px;}
        #login h1 {}
        #login h1 a {background-image: url(<?php echo get_theme_image('fmb.png'); ?> ); height: 133px; width: 400px;}
    </style>
    
    <?php
}

function tnb_mail_name($from_name) {
    return __('Feira Música Brasil [inscrições]', 'tnb');
}


?>
