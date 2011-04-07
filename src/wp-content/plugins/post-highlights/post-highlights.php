<?php
/*
Plugin Name: Post Highlights
Plugin URI: http://post-highlights.hacklab.com.br
Description: Description: Adds a nice looking animated highlights box to your theme, and lets you highlight your posts
Author: leogermani, andersonorui, pedger
Version: 2.2


    Post Highlights is released under the GNU General Public License (GPL)
    http://www.gnu.org/licenses/gpl.txt

    
*/

function post_highlights_init() {
	
	class postHighlights {
		
		function postHighlights() {
			
			$pluginFolder = plugin_basename( dirname(__FILE__) );
			
			load_plugin_textdomain('ph', "wp-content/plugins/$pluginFolder/languages", "$pluginFolder/languages");
            
            $this->basepath =  WP_CONTENT_DIR . "/plugins/$pluginFolder/";
            $this->baseurl = WP_CONTENT_URL . "/plugins/$pluginFolder/"; 
            
            $this->optionsPrefix = 'post_highlights_';
            $this->custom_thumb_name = 'posthighlightscustomthumb';
            
            $this->checkForOldSettings();
            
            $this->loadTheme();
            
            register_deactivation_hook(__FILE__, array(&$this, 'ph_deactivate'));
            
            if (current_user_can('manage-post-highlights')) {
	            add_action('admin_print_scripts-edit.php',array(&$this, 'addJS'));
	            add_action('admin_print_styles-edit.php',array(&$this, 'addCSS'));
                add_action('admin_print_scripts-edit-pages.php',array(&$this, 'addJS')); 
                add_action('admin_print_styles-edit-pages.php',array(&$this, 'addCSS')); 
				add_action('manage_posts_custom_column', array(&$this, 'highlight_It'), 10, 2);
				add_filter('manage_posts_columns',array(&$this, 'add_column'));
                add_action('manage_pages_custom_column', array(&$this, 'highlight_It'), 10, 2); 
                add_filter('manage_pages_columns',array(&$this, 'add_column')); 
				
				add_action('restrict_manage_posts', array(&$this, 'posts_filter'));
				
				add_action('admin_print_scripts-toplevel_page_post-highlights',array(&$this, 'addJS'));
                add_action('admin_print_styles-toplevel_page_post-highlights',array(&$this, 'addCSS'));
				global $pagenow;
                
                if ($pagenow == 'edit.php' && isset($_GET['showhighlighted'])) {
                	query_posts('meta_key=ph_order&orderby=meta_value&order=ASC&m=' . $_GET['m'] . '&cat=' . $_GET['cat'] . '&paged=' . $_GET['paged']);
                }
                
            }
			add_action('admin_menu',array(&$this, 'add_options_page'));
			add_action('admin_init', array(&$this, 'global_settings'));
			
			if ($this->get_option('custom_thumb')) 
                add_filter('intermediate_image_sizes', array(&$this, 'register_custom_thumb'));
			
		}
		
		/* internal methods */
		
		function ph_deactivate() {
			global $wpdb;
			$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$this->optionsPrefix}%'");
		}
        
		function checkForOldSettings() {
            // check for post highlights 1.5 settings and update it
			$oldSettings = get_option('post_highlights');
			
			if (is_array($oldSettings)) {
			    // We have old options here, lets update it.
			    $this->update_option('width', $oldSettings['width']);
			    $this->update_option('height', $oldSettings['height']);
			    $this->update_option('arrow_colors', $oldSettings['bg_color']);
			    $this->update_option('delay', $oldSettings['delay']);
			    
			    // now we can delete the old options
			    delete_option('post_highlights');
			}
		}
		
        function get_option($option_name) {
		    return get_option($this->optionsPrefix . $option_name);
		}
		
		function update_option($option, $value) {
			return update_option($this->optionsPrefix . $option, $value);
		}
		
        function loadTheme() {
			
            $this->theme = $this->get_option("theme");
            if (!$this->theme) {
                $this->theme = 'default';
                $this->update_option('theme', 'default');
            }
            
            //check if current theme exists, otherwise uses default
            if (!file_exists($this->basepath . 'themes/' . $this->theme . '/index.php'))
                $this->theme = 'default';
            
        	if (file_exists($this->basepath . 'themes/' . $this->theme . '/settings.php'))
        	   include('themes/' . $this->theme . '/settings.php');
        	
        	$this->themeurl = $this->baseurl . 'themes/' . $this->theme . '/';
		}
		
		function addJS() {
            wp_enqueue_script('post-highlights-admin', $this->baseurl . 'js/post-highlights.js');
            wp_localize_script('post-highlights-admin', 'ph', array('loadingMessage' => __('Loading', 'ph'), 'baseurl' => $this->baseurl));
        }
        
        function addCSS() {
            wp_enqueue_style('post-highlights-admin', $this->baseurl . 'css/style.css');
        }
		
        function register_custom_thumb($sizes) {
			array_push($sizes, $this->custom_thumb_name);
			return $sizes;
		}
        
        /* Interface Methods */
        
        function highlight_It($column_name, $id) {
		    if ($column_name=="posthighlight"){
		    	$highlighted = get_post_meta($id, "ph_order") ?  "checked" : "";
                echo '<input type="checkbox" class="ph_dialog_button" id="ph_', $id, '" ', $highlighted, '>';    
                $display = !$highlighted ? "style='display:none'" : "";
		        echo "<div class='ph_dialog_button' $display id='phEdit_$id'>" . __("Edit","ph") . "</div>" ;
		        echo "<div class='ph_hide_settings' id='ph_hide_settings_$id'>" . __("Hide Settings","ph") . "</div>"   ;
            
		    }
		}
		
		function posts_filter() {
			$checked = $_GET['showhighlighted'] == 1 ? 'checked' : '';
			echo "<input type='checkbox' name='showhighlighted' value='1' $checked>", __('only highlighted', 'ph'), ' ';
		}
		
		function add_column($defaults) {
		    $defaults['posthighlight'] = __('Highlight');
		    return $defaults;
		}
        
        function add_options_page() {
        	add_submenu_page(basename(__FILE__), __("Post Highlight Settings", 'ph'), __("Settings", 'ph'), 'admin-post-highlights', basename(__FILE__), array(&$this, "ph_options_page"));
        	add_menu_page(__("Post Highlight Options", 'ph'), __("Post Highlights", 'ph'), 'manage-post-highlights', basename(__FILE__), array(&$this, "ph_options_page"));
        	add_submenu_page(basename(__FILE__), __("Post Highlight Permissions", 'ph'), __("Permissions", 'ph'), 'admin-post-highlights', 'post-highlights-permissions', array(&$this, "ph_permissions_page"));
		}
		
		
		/* Settings */
		
        function print_page_title($title) {
            echo "<h2>$title</h2>";
        }
		
		function global_settings() {
			add_settings_section('ph_global_settings', __('Global Options', 'ph'), array(&$this, 'global_settings_intro'), 'post-highlights');
			add_settings_field($this->optionsPrefix . 'global', __('Options', 'ph'), array(&$this, 'global_settings_fields'), 'post-highlights', 'ph_global_settings');
            register_setting('post-highlights',$this->optionsPrefix . 'theme');
            
			$this->global_fields = array(
                'delay' => __('Delay: The time between each highlight in miliseconds (1 sec = 1000) - default = 8000', 'ph'),
                'max_posts' => __('Number of posts: Maximum number of posts to display (0 or empty = unlimited) - default = unlimited', 'ph')
			);
			
			foreach($this->global_fields as $field => $description) {
                register_setting('post-highlights',$this->optionsPrefix . $field);
            }
            
            register_setting('post-highlights',$this->optionsPrefix . 'order');

            register_setting('post-highlights',$this->optionsPrefix . 'custom_thumb');
            register_setting('post-highlights',$this->custom_thumb_name . '_size_w');
            register_setting('post-highlights',$this->custom_thumb_name . '_size_h');
            register_setting('post-highlights',$this->custom_thumb_name . '_crop');
            
            if (is_array($this->themeSettings)) {
            	add_settings_section('ph_theme_settings', __('Theme Options', 'ph'), array(&$this, 'theme_settings_intro'), 'post-highlights');
                add_settings_field($this->optionsPrefix . 'themeSettings', __('Options', 'ph'), array(&$this, 'theme_settings_fields'), 'post-highlights', 'ph_theme_settings');
            
	            foreach($this->themeSettings as $field => $description) {
	                register_setting('post-highlights',$this->optionsPrefix . $field);
	                
	                // load Default if empty
	                if (!$this->get_option($field) && is_array($this->themeSettingsDefaults) && $this->themeSettingsDefaults[$field])
                        $this->update_option($field, $this->themeSettingsDefaults[$field]);
	            }
                
            }
            
		}
		
        function global_settings_intro() {
            echo '<p>', __('These settings applies to all Post Highlight themes', 'ph'), '</p>';
        }
		
		function global_settings_fields() {

			echo '<p>', __('Current Theme', 'ph'), '</p>';
			echo '<select name="', $this->optionsPrefix, 'theme">';
			
				$dir_handle = @opendir($this->basepath . 'themes');
				while ($theme = readdir($dir_handle)) 
				{
                    if ( substr($theme, 0, 1) != '.') {
                        echo "<option value='$theme'";
                        if ($theme == $this->theme) echo ' selected';
                        echo ">$theme</option>";
                    }
				}
				closedir($dir_handle);
			echo '</select>';
			
			//TODO: print a select box instead to avoid invalid data
			foreach($this->global_fields as $field => $description) {
                $this->print_settings_field($field, $description);
            }
            
            echo '<p>';
            
            _e('Order:', 'ph');
            
            $orderOptions = array( __('Order Value', 'ph') => 'orderby=meta_value&order=ASC', __('Newer to Older', 'ph') => 'orderby=date&order=DESC', __('Older to Newer', 'ph') => 'orderby=date&order=ASC');
            
            echo '<select name="', $this->optionsPrefix, 'order">';
            
            foreach ($orderOptions as $name => $order) {
                
                echo '<option value="' . $order . '"';
                if ($this->get_option('order') == $order) echo ' selected';
                echo '>', $name, '</option>';
                
            }
            
            echo '</select>';
            
            echo '<br/><small>';
            _e('"Order Value" refers to the Order field that appears in the Post highlights dialog box when you highlight a post', 'ph');
            echo '</small>';
            
            echo '<br /><br />';
            
            $customImageChecked = $this->get_option('custom_thumb') == 1 ? 'checked' : '';
            echo '<input type="checkbox" name="' . $this->optionsPrefix . 'custom_thumb" value="1" ' . $customImageChecked . ' />';
            _e('I want Post Highlights to automatically create and use an image with these dimensions:', 'ph');
            echo '<br />';
            _e('Width:', 'ph');
            echo '<input type="text" size="3" name="' . $this->custom_thumb_name . '_size_w" value="', get_option($this->custom_thumb_name . '_size_w'), '">px'; 
            echo ' &nbsp; ';
            _e('Height:', 'ph');
            echo '<input type="text" size="3" name="' . $this->custom_thumb_name . '_size_h" value="', get_option($this->custom_thumb_name . '_size_h'), '">px'; 
            echo '<input type="hidden" name="' . $this->custom_thumb_name . '_crop" value="1">'; 
            echo '</p>';

		}
		
        function theme_settings_intro() {
            echo '<p>', __('These settings applies only to the active theme', 'ph'), '</p>';
        }
        
        function theme_settings_fields() {

            foreach($this->themeSettings as $field => $description) {
                $this->print_settings_field($field, $description);
            }

        }
		
		function print_settings_field($field, $description) {
			echo '<p>', $description, '</p>';
            echo '<input type="text" name="', $this->optionsPrefix . $field, '" value="', $this->get_option($field), '">';
		}
		
		function ph_options_page() {
            echo '<div class="wrap">';
			$this->print_page_title('Post Highlights Settings', 'ph');
            echo '<form method="post" action="options.php">';
            
            settings_fields( 'post-highlights' );
		    do_settings_sections('post-highlights');
		       
		    echo '<p class="submit">
		    <input class="button-primary" type="submit" value="', __('Save Changes'), '" name="Submit"/>
		    </p>
		    </form>
		    ';
		    echo '</div>';
  
		}
		
		
	    function ph_permissions_page() {
            echo '<div class="wrap">';
            $this->print_page_title('Post Highlights Permissions');
            echo '<p>', __('Who can Highlight Posts?', 'ph'), '</p>';
            echo '<form method="post" >';
            global $wp_roles;

            if ($_POST['Submit']) {
            	foreach ($wp_roles->roles as $r) {
            		if ($r['name'] != 'Administrator') {
	            		$role = get_role(strtolower($r['name']));
	            		if ($_POST[$r['name']]) {
	            			$role->add_cap('manage-post-highlights');
	            		} else {
	            			$role->remove_cap('manage-post-highlights');
	            		}
            		}
            	}
            }
            
            foreach ($wp_roles->roles as $role) {
            	
            	if ($role['name'] != 'Administrator') {
            		echo "<input value='1' type='checkbox' name='{$role['name']}'";
            		if ($role['capabilities']['manage-post-highlights'])
                        echo 'checked';
                    echo "> <label for='{$role['name']}'>{$role['name']}</label> <br />";
            	}
            }
            
            echo '<p class="submit">
            <input class="button-primary" type="submit" value="', __('Save Changes', 'ph'), '" name="Submit"/>
            </p>
            </form>
            ';
            echo '</div>';
            
        }
		
		
        /* Output Methods */
        
        /* 
         * Returns the url for the image to use. This methos must be called within the loop
         * it will check for image url or id and if post highlights is using custom thumbs
         * */
        function get_post_image() {
			global $post;
			if ($image_url = get_post_meta($post->ID, 'ph_picture_url', true))
				return $image_url;
			
			$image_id = get_post_meta($post->ID, 'ph_picture_id', true);
			$size = ($this->get_option('custom_thumb') == 1) ? $this->custom_thumb_name : 'full';
			$image_url = wp_get_attachment_image_src($image_id, $size);
			
			if (is_array($image_url))
				return $image_url[0];
			else
				return false;
			
		}
        
        function insert() {
			global $wpdb, $wp_scripts;
			
			// Lets see if jquery was alredy loaded. If not, load it
			if (!is_object($wp_scripts) || !in_array('jquery', $wp_scripts->done)) {
				echo '<script type="text/javascript" src="' . get_option('siteurl') . '/wp-includes/js/jquery/jquery.js"></script>';
			}
						
			$limit = $this->get_option('max_posts');
            if (is_numeric($limit) && $limit > 0)
                $sel_limit = '&showposts=' . $limit;
            $delay = $this->get_option('delay');
            if (!is_numeric($delay))
                $delay = 8000; //default delay
            
            $order = $this->get_option('order') ? $this->get_option('order') : 'orderby=meta_value&order=ASC';
            
            $highlightedPosts = new WP_Query("post_type=any&meta_key=ph_order&$order$sel_limit");

			if (file_exists($this->basepath . 'themes/' . $this->theme . '/style.css'))
                echo '<link rel="stylesheet" id="posthighlights-' . $this->theme . '"  href="' . $this->themeurl . 'style.css" type="text/css" media="all" />';
            
            echo "<script type='text/javascript'>
                    /* <![CDATA[ */
                    var phSettings = {
                        delay: $delay
                    };
                    /* ]]> */
                    </script>
                    ";
                
            if ($this->useThemeJS) {
            	echo '<script type="text/javascript" src="' . $this->themeurl . 'script.js"></script>';
            } else {
            	echo '<script type="text/javascript" src="' . $this->baseurl . 'js/front-end.js"></script>';
            }
			
			$counter = 1;
			echo '<div id="posthighlights_container">';
	            require_once('themes/' . $this->theme . '/index.php');
			echo '</div>';
		}
	}
	
	global $postHighlights;
    $postHighlights = new postHighlights();
	
}

add_action('init', 'post_highlights_init');

register_activation_hook(__FILE__, 'ph_activate');

function ph_activate() {
    $admin = get_role('administrator');   
    $admin->add_cap('manage-post-highlights');
    $admin->add_cap('admin-post-highlights');
}

// Compability with old versions of post highlights
function insert_post_highlights() {
	global $postHighlights;
	if (is_object($postHighlights)) $postHighlights->insert();
}



?>
