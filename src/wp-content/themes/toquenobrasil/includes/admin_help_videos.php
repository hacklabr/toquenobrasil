<?php
add_action('admin_menu', 'help_videos_add_page');
add_action('admin_init', 'help_videos_init' );

function help_videos_add_page() {
    add_options_page(__("Vídeos de ajuda"), __("Vídeos de ajuda"), "manage_options", "help-videos", "help_videos");
}

function help_videos_init(){
    register_setting( 'help_videos_options', 'help_videos', 'help_videos_validate' );
}

function help_videos() {
	?>
        <style type="text/css">
            /* TODO: Refazer estilo para esta página */
            form.tnb-mail-messages div {
                margin-bottom: 0.5em;
                padding: 0.5em;
                background-color: #f0f0f0;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }
        </style>

		<div class="wrap">
            <div class="icon32" id="icon-options-general"><br/></div>
            <h2>Videos de ajuda do site</h2>

			<form method="post" action="options.php" class="tnb-mail-messages">
				<?php settings_fields('help_videos_options'); ?>
				<?php $options = get_option('help_videos');?>

                <div>
                    <label for="video_cadastro_produtor"><?php _e("Vídeo de ajuda para cadastro de produtor");?></label><br/>
                    <input id="video_cadastro_produtor" class="large-text code" name="help_videos[cadastro_produtor]"
                           value="<?php echo $options['cadastro_produtor'];?>"/>
                    <small>
                    Vídeo exibido na página de cadastro para novo produtor <br />
                    </small>
                </div>


                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
				</p>
			</form>
		</div>
	<?php
}

function help_videos_validate($msgs) {
	return $msgs;
}
