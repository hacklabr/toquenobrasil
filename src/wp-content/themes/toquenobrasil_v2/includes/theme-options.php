<?php

function get_theme_option($option_name) {
    $option = get_option('theme_customization');
    return $option[$option_name];
}

add_action('admin_init', 'theme_customization_init');
add_action('admin_menu', 'theme_customization_menu');

function theme_customization_init() {
    register_setting('theme_customization_options', 'theme_customization');
}

function theme_customization_menu() {
    add_theme_page(__('Theme Options'), __('Theme Options'), 'manage_options', 'theme_customization', 'theme_customization_page');
}

function theme_customization_page() {
?>
    <div class="wrap">
        <h2><?php _e('Theme Options'); ?></h2>
        
        <form action="options.php" method="post">
            
            <?php
                settings_fields('theme_customization_options');
                $options = get_option('theme_customization');
            ?>
            
            <h3><?php _e('Home', 'tnb'); ?></h3>
            <p>
                <label for="theme_customization[slogan]">
                    <strong><?php _e('Slogan no box do player', 'tnb'); ?></strong>
                </label>
                <br/>
                <textarea name="theme_customization[slogan]" cols="40" rows="6"><?php echo htmlspecialchars($options[slogan]); ?></textarea>
            </p>
            <p>
                <label for="theme_customization[iam-artist]">
                    <strong><?php _e('Link para o botão Artista', 'tnb'); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[iam-artist]" value="<?php echo htmlspecialchars($options['iam-artist']); ?>" />
            </p>
            <p>
                <label for="theme_customization[iam-producer]">
                    <strong><?php _e('Link para o botão Produtor', 'tnb'); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[iam-producer]" value="<?php echo htmlspecialchars($options['iam-producer']); ?>" />
            </p>
            <p>
                <label for="theme_customization[iam-groupie]">
                    <strong><?php _e('Link para o botão Fã', 'tnb'); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[iam-groupie]" value="<?php echo htmlspecialchars($options['iam-groupie']); ?>" />
            </p>
            <p>
                <label for="theme_customization[iam-brand]">
                    <strong><?php _e('Link para o botão Marca', 'tnb'); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[iam-brand]" value="<?php echo htmlspecialchars($options['iam-brand']); ?>" />
            </p>
            <p>
                <label for="theme_customization[signup]">
                    <strong><?php _e('Link para o botão Cadastro', 'tnb'); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[signup]" value="<?php echo htmlspecialchars($options['signup']); ?>" />
            </p>
            
            <p>
                <label for="theme_customization[tnb_users_rows]">
                    <strong><?php _e('Número de linhas nas listagens de usuários', 'tnb'); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[tnb_users_rows]" value="<?php echo htmlspecialchars($options['tnb_users_rows']); ?>" />

            </p>
            
            <p>
                <label for="theme_customization[tnb_eventos_rows]">
                    <strong><?php _e('Número de linhas nas listagens de eventos', 'tnb'); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[tnb_eventos_rows]" value="<?php echo htmlspecialchars($options['tnb_eventos_rows']); ?>" />

            </p>

            <h3><?php _e('Página de Cadastro', 'tnb'); ?></h3>
            <p>
                <label for="theme_customization[iam_artist_explanation]">
                    <strong><?php _e("Explicação do que é o artista na página de cadastro", "tnb"); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[iam_artist_explanation]" value="<?php echo htmlspecialchars($options['iam_artist_explanation']); ?>" />
            </p>

            <p>
                <label for="theme_customization[iam_producer_explanation]">
                    <strong><?php _e("Explicação do que é o produtor na página de cadastro", "tnb"); ?></strong>
                </label>
                <br/>
                <input type="text" name="theme_customization[iam_producer_explanation]" value="<?php echo htmlspecialchars($options['iam_producer_explanation']); ?>" />
            </p>

            <p><input type="submit" value="<?php _e('Salvar','tnb'); ?>" class="button-primary"/></p>
        </form>
    </div>

<?php
}

?>
