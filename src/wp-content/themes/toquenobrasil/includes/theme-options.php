<?php

function get_theme_option($option_name) {
	$option = get_option('theme_options');
	return $option[$option_name];
}

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_menu' );

function theme_options_init() {
	register_setting( 'theme_options_options', 'theme_options' );
}

function theme_options_menu() {
	add_theme_page( __('Theme Options'), __('Theme Options'), 'manage_options', 'theme_options', 'theme_options_page' );
}

function theme_options_page() {
	?>
	
	<div class="wrap">
		<h2><?php _e('Theme Options'); ?></h2>
		<form action="options.php" method="post">
			<?php 
				settings_fields('theme_options_options'); 
				$options = get_option('theme_options'); 
			?>
			
			<table class="form-table">
				<tr valign="top">
					<th colspan="2">
						<h3><?php _e('Home Customization', 'tnb'); ?></h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">Bem Vindos</th>
					<td>
						<textarea name="theme_options[home_welcome_text]" cols="60" rows="6"><?php echo htmlspecialchars($options['home_welcome_text']); ?></textarea>
						<br/>(Texto que aparece no box "Bem Vindos" da home)
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<textarea name="theme_options[home_welcome_video]" cols="60" rows="6"><?php echo htmlspecialchars($options['home_welcome_video']); ?></textarea>
						<br/>(Vídeo que aparece no box "Bem Vindos" da home. <strong>Formato: 430x242px</strong>)
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Artistas</th>
					<td>
						<textarea name="theme_options[home_artists_text]" cols="60" rows="6"><?php echo htmlspecialchars($options['home_artists_text']); ?></textarea>
						<br/>(Texto que aparece no box "Artistas" da home)
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Produtores</th>
					<td>
						<textarea name="theme_options[home_producers_text]" cols="60" rows="6"><?php echo htmlspecialchars($options['home_producers_text']); ?></textarea>
						<br/>(Texto que aparece no box "Produtores" da home)
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2">
						<h3><?php _e('Social Links at footer', 'tnb'); ?></h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">Facebook</th>
					<td>
						<input type="text" name="theme_options[facebook_url]" value="<?php echo htmlspecialchars($options['facebook_url']); ?>"/>
						<br/>(Link com http://)
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">YouTube</th>
					<td>
						<input type="text" name="theme_options[youtube_url]" value="<?php echo htmlspecialchars($options['youtube_url']); ?>"/>
						<br/>(Link com http://)
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Twitter</th>
					<td>
						<input type="text" name="theme_options[twitter_url]" value="<?php echo htmlspecialchars($options['twitter_url']); ?>"/>
						<br/>(Link com http://)
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>"
			</p>
		</form>
	</div>
	
	<?php
}

?>