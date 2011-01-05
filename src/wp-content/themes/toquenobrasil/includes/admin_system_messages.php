<?php
add_action('admin_menu', 'custom_system_notices_add_page');
add_action('admin_init', 'custom_system_notices_init' );

function custom_system_notices_add_page() {
    add_options_page(__("Mensagens do Sistema"), __("Mensagens do Sistema"), "manage_options", "system-messages", "custom_system_notices");
}

function custom_system_notices_init(){
    register_setting( 'custom_system_notices_options', 'custom_system_notices', 'custom_system_notices_validate');
}

function custom_system_notices() {
	?>
        <style type="text/css">
            /* TODO: Refazer estilo para esta página */
            form.tnb-system-messages div {
                margin-bottom: 0.5em;
                padding: 0.5em;
                background-color: #f0f0f0;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }
        </style>

		<div class="wrap">
            <div class="icon32" id="icon-options-general"><br/></div>
            <h2>Mensagens do Sistema</h2>

			<form method="post" action="options.php" class="tnb-system-messages">
				<?php settings_fields('custom_system_notices_options'); ?>
				<?php $options = get_option('custom_system_notices');?>

                <div>
                    <label for="tnb_termo_para_novo_evento"><?php _e("Termo para novo evento");?></label><br/>
                    <textarea id="tnb_termo_para_novo_evento" class="large-text code" rows="10" name="custom_system_notices[tnb_termo_para_novo_evento]"><?php echo $options['tnb_termo_para_novo_evento'];?></textarea><br/>
                </div>


                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
				</p>
			</form>
		</div>
	<?php
}

/**
 * Altera valores do array $msgs, passado como
 * parâmetro pelo Wordpress
 */
function custom_system_notices_validate($msgs) {
	return $msgs;
}
