<?php
/*
Plugin Name: Custom Author Base
Plugin URI: http://webdesign.jaedub.com/wordpress-plugins/custom-author-base-plugin
Description: Adds a field to the Permalink Settings page to change the author permalink base.
Version: 1.0.1
Author: Jae Dub
Author URI: http://webdesign.jaedub.com

Version History

1.0.0 - 2009-03-09
    Initial release version
1.0.1 - 2009-03-14
    Made backwards compatible to WordPress 2.5+

*/

function cab_admin() {
    if( $_POST ) {
        $author_base = stripslashes($_POST['author_base']);
        update_option('author_base', $author_base);
    }
?>
<div class="wrap">
<h2>Custom Author Base</h2>

<form method="post" action="">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Author base</th>
<td><input type="text" name="author_base" value="<?php echo get_option('author_base'); ?>" /></td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="author_base" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
<?php
}

function cab_add_admin() {
    add_submenu_page('options-general.php', 'Author Base', 'Author Base', 10, __FILE__, 'cab_admin');
}

function cab_rewrite() {
    global $wp_rewrite;

    $author_base = get_option('author_base') ? get_option('author_base') : 'author';
    $wp_rewrite->author_base = $author_base;
    $wp_rewrite->flush_rules();
}

add_action('init', 'cab_rewrite');
add_action('admin_menu', 'cab_add_admin');

?>

