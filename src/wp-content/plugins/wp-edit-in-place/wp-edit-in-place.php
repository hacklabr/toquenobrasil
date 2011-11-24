<?php
/*
  Plugin Name: WP Edit in Place
  Description: Implements a set of functions to add editable messages to the front end
  Author: leogermani, rafael.chaves.freitas
  Version: 1.0 RC 1


  WP Edit in Place is released under the GNU General Public License (GPL)
  http://www.gnu.org/licenses/gpl.txt


 */

function wp_edit_in_place_init() {

    class WP_EditInPlace {

        protected $source;
        protected $optionsPrefix = 'wpeip_';

        function WP_EditInPlace() {
            global $WPEIP_CONFIG;
            $WPEIP_CONFIG = array();

            include dirname(__FILE__) . '/config.php';

            $wpeip_source_class = $WPEIP_CONFIG['source_class'];
            include dirname(__FILE__) . '/sources/' . $wpeip_source_class . '.class.php';

            if (class_exists($wpeip_source_class))
                eval('$wpeip_source = new ' . $wpeip_source_class . '($WPEIP_CONFIG);');
            else
                $wpeip_source = null;

            $this->source = $wpeip_source;

            $pluginFolder = plugin_basename(dirname(__FILE__));

            load_plugin_textdomain('wpeip', "wp-content/plugins/$pluginFolder/languages", "$pluginFolder/languages");

            $this->basepath = WP_CONTENT_DIR . "/plugins/$pluginFolder/";
            $this->baseurl = WP_CONTENT_URL . "/plugins/$pluginFolder/";

            register_deactivation_hook(__FILE__, array(&$this, 'wpeip_deactivate'));


            if (current_user_can('manage-wpeip') && !is_admin()) {
                //inicializa e carrega JS de quem pode editar as coisas
                wp_enqueue_script('wpeip', plugins_url('wpeip.js', __FILE__), array('jquery'));
                wp_localize_script('wpeip', 'wpeip', array(
                    'WPLANG' => WPLANG,
                    'cancel' => __('Cancel'),
                    'save'  => __('Save')
                    )
                );

                wp_enqueue_style('wpeip', plugins_url('wpeip.css', __FILE__));
            }
            add_action('admin_menu', array(&$this, 'add_options_page'));

            $this->actions();
        }

        function actions() {
            if (isset($_POST['wpeip_action'])) {
                switch ($_POST['wpeip_action']) {
                    case 'update_terms':
                        if (isset($_POST['updated']) && count($_POST['updated'])) {
                            foreach ($_POST['updated'] as $key) {
                                if ($key) {
                                    foreach ($_POST[$key] as $lcode => $term) {
                                        $this->source()->setTerm($key, $lcode, $term);
                                    }
                                }
                            }
                        }
                        if (isset($_POST['wpeip_update_frontend_form_options'])) {
                            $value = is_array($_POST['frontend_forms']) ? $_POST['frontend_forms'] : array();
                            $this->update_option('frontend_forms', $value);
                        }
                        break;
                }
            }
        }

        /**
         * Enter description here ...
         * @return WPEIP_Source
         */
        function source() {
            return $this->source;
        }

        /* internal methods */

        function wpeip_deactivate() {
            global $wpdb;
            $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$this->optionsPrefix}%'");
        }

        function get_option($option_name) {
            $option = get_option($this->optionsPrefix . $option_name);
            if($option_name == 'frontend_forms' && !is_array($option))
                $option = array();
            return $option;
        }

        function update_option($option, $value) {
            return update_option($this->optionsPrefix . $option, $value);
        }

        function add_options_page() {
            add_submenu_page(basename(__FILE__), __("WP Edit in Place Settings", 'wpeip'), __("Settings", 'wpeip'), 'admin-wpeip', basename(__FILE__), array(&$this, "options_page"));
            add_menu_page(__("WP Edit in Place Options", 'wpeip'), __("WP Edit in Place", 'wpeip'), 'manage-wpeip', basename(__FILE__), array(&$this, "options_page"));
            add_submenu_page(basename(__FILE__), __("WP Edit in Place Permissions", 'wpeip'), __("Permissions", 'wpeip'), 'admin-wpeip', 'wpeip-permissions', array(&$this, "permissions_page"));

            add_theme_page(__("WP Edit in Place Messages", 'wpeip'), __("WP Edit in Place Messages", 'wpeip'), 'manage-wpeip', 'wpeip-terms', array(&$this, "terms_page"));
        }

        /* Settings */

        function options_page() {
            echo '<div class="wrap">';
            echo '<h2>' . __('WP Edit in Place Settings', 'wpeip') . '</h2>';
            echo '<form method="post" action="options.php">';


            echo '<p class="submit"> 
		    <input class="button-primary" type="submit" value="', __('Save Changes'), '" name="Submit"/>
		    </p>
		    </form>
		    ';
            echo '</div>';
        }

        function terms_page() {
            $frontend_forms = $this->get_option('frontend_forms');
            
            $terms = $this->source->getAll();
            ?>
            <script type="text/javascript">
                <!--

                // term filer
                jQuery(document).ready(function(){
                    jQuery('#wpeip-filter').keyup(function(){
            					
                        jQuery(".term p.description").each(function(){
            			    		
                            if(jQuery(this).html().toLowerCase().indexOf(jQuery('#wpeip-filter').val().toLowerCase()) < 0){
                                jQuery(this).parent().hide();
                            }else{
                                jQuery(this).parent().show();
                            }
                        });
                    });
                });
                //-->
            </script>

            <style>
                /* Clearing floats without extra markup
                   Based on How To Clear Floats Without Structural Markup by PiE
                   [http://www.positioniseverything.net/easyclearing.html] */

                .clearfix:after, .container:after {
                    content: "\0020";
                    display: block;
                    height: 0;
                    clear: both;
                    visibility: hidden;
                    overflow:hidden;
                }
                .clearfix, .container {display: block;}

                /* Regular clearing
                   apply to column that should drop below previous ones. */

                .clear { clear:both; }

                .term { background-color: #f8f8f8; border-radius: 5px; border:1px solit #666; margin:10px;}
                .term .description { font-style: italic; }
                .term .language{ padding: 5px; float:left;}
                .term textarea { min-width: 400px; min-height:75px;}

            </style>
            <div class="wrap">
                <h2><?php _e('WP Edit in Place Messages', 'wpeip'); ?></h2>
                <label><?php _e('filter', 'wpeip') ?>: <input type='text' id='wpeip-filter'/></label>

                <form method="post" >
                    <input type='hidden' name='wpeip_action' value='update_terms' />
                    <input type='hidden' name='wpeip_update_frontend_form_options' value='1' />
            <?php foreach ($terms as $key => $term): ?>
                        <div class='term clearfix'>
                            <input id="hidden-<?php echo $key; ?>" type='hidden' name='updated[]' value='' />
                            <label><input type="checkbox" name='frontend_forms[]' value='<?php echo $key; ?>' <?php if (in_array($key, $frontend_forms)) echo 'checked="checked"'; ?>> <?php _e('frontend form', 'wpeip') ?></label>
                            <p class='description'><?php echo $term['description']; ?></p>

                <?php foreach ($term as $lcode => $text): if ($lcode != 'description'): ?>
                                    <div class='language'>
                                        <label> <?php echo $lcode ?><br/>
                                            <textarea name='<?php echo $key ?>[<?php echo $lcode; ?>]' onchange='jQuery("#hidden-<?php echo $key; ?>").val("<?php echo $key; ?>")'><?php echo htmlentities(utf8_decode($text)); ?></textarea>
                                        </label>
                                    </div><!-- .language -->
                    <?php endif;
                endforeach; ?>

                        </div> <!-- .term -->
                        <?php endforeach; ?>
                    <input type='submit' class="button-primary" value='save' />
                </form>
            </div>
            <?php
        }

        function permissions_page() {
            echo '<div class="wrap">';
            echo '<h2>' . __('WP Edit in Place Permissions', 'wpeip') . '</h2>';
            echo '<p>', __('Who can edit the messages?', 'wpeip'), '</p>';
            echo '<form method="post" >';
            global $wp_roles;

            if ($_POST['Submit']) {
                foreach ($wp_roles->roles as $r) {
                    if ($r['name'] != 'Administrator') {
                        $role = get_role(strtolower($r['name']));
                        if ($_POST[$r['name']]) {
                            $role->add_cap('manage-wpeip');
                        } else {
                            $role->remove_cap('manage-wpeip');
                        }
                    }
                }
            }

            foreach ($wp_roles->roles as $role) {

                if ($role['name'] != 'Administrator') {
                    echo "<input value='1' type='checkbox' name='{$role['name']}'";
                    if ($role['capabilities']['manage-wpeip'])
                        echo 'checked';
                    echo "> <label for='{$role['name']}'>{$role['name']}</label> <br />";
                }
            }

            echo '<p class="submit">
            <input class="button-primary" type="submit" value="', __('Save Changes', 'wpeip'), '" name="Submit"/>
            </p>
            </form>
            ';
            echo '</div>';
        }

    }

    abstract class WPEIP_Source {

        protected $terms_cache = array();
        protected $config = array();

        /**
         * Data source
         * @var WPEIP_Source $instance data source instance
         */
        protected static $instance = null;

        public function __construct($config = array()) {
            $this->config = $config;
        }

        public function getKey($term) {
            return hash('sha1', $term);
        }

        protected function cacheExists($key, $lcode) {
            return isset($this->terms_cache[$key][$lcode]);
        }

        protected function cacheGet($key, $lcode) {
            if ($this->cacheExists($key, $lcode))
                return $this->terms_cache[$key][$lcode];
            else
                return null;
        }

        protected function cacheSet($key, $lcode, $term) {
            $this->terms_cache[$key][$lcode] = $term;
        }

        protected function cacheDelete($key, $lcode) {
            unset($this->terms_cache[$key][$lcode]);
        }

        public function getTerm($term, $description = '', $lcode = WPLANG, $frontend_form = false) {
            
            $key = $this->getKey($term);

            if ($this->cacheExists($key, $lcode))
                return $this->cacheGet($key, $lcode);
            
            $result = $this->_getTerm($key, $lcode);

            if (is_null($result)) {
                $this->_insertTerm($lcode, $key, $term, $description, $frontend_form);
                $result = $term;
            }

            $this->cacheSet($key, $lcode, $result);
            
            return $result;
        }

        public function setTerm($key, $lcode, $new_term) {
            if ($this->_termExists($key, $lcode))
                $this->_updateTerm($lcode, $key, $new_term);
            else
                $this->_insertTerm($lcode, $key, $term, $description);
        }

        public function getAll() {
            return $this->_getAll();
        }

        public function getDescription($term) {
            return $this->_getDescription($this->getKey($term));
        }

        protected abstract function _termExists($key, $lcode);

        protected abstract function _getTerm($key, $lcode);

        protected abstract function _updateTerm($lcode, $key, $new_term);

        protected abstract function _insertTerm($lcode, $key, $term, $description, $frontend_form = false);

        protected abstract function _getAll();

        protected abstract function _getDescription($key);
    }

    global $WPEIP;

    $WPEIP = new WP_EditInPlace();
}

add_action('init', 'wp_edit_in_place_init');

register_activation_hook(__FILE__, 'wpeip_activate');

function wpeip_activate() {
    $admin = get_role('administrator');
    $admin->add_cap('manage-wpeip');
    $admin->add_cap('admin-wpeip');
}

/**
 * 
 * Displays a text and adds it to be edited in the admin area. 
 * By default, it also creates an Edit in Place form. 
 * Hold Ctrl and click on the text to edit it.
 * Hold Ctrl + Shift to see all the editable texts in the page.
 * 
 * Note that if your text is an attribute of a HTML element, the value of a button for example,
 * you should not use the Edit in place form. It will break your layout. In those cases please
 * set the third or forth parameter to false.
 * 
 * @param string $term the default value for this string
 * @param string $description the description of this string, will show on the admin screen
 * @param bool/string $param3 wether to have a frontend form (bool) or language code (string)
 * @param bool/string $param4 wether to have a frontend form (bool) or language code (string)
 * 
 */
function _oi($term, $description = '') {
    global $WPEIP;
    
    //default values
    $lcode = WPLANG;
    $frontend_form = true;
    
    for ($i = 2; $i < max(3, func_num_args()); $i++) {

        $arg = func_get_arg($i);
        if (is_bool($arg))
            $frontend_form = $arg;

        if (is_string($arg))
            $lcode = $arg;
    }
    
    
    $key = $WPEIP->source()->getKey($term);
    
    $text = nl2br(__i($term, $description, $lcode, $frontend_form));
    
    $frontend_forms = $WPEIP->get_option('frontend_forms');
    if (in_array($key, $frontend_forms) && current_user_can('manage-wpeip'))
        echo '<span id="' . $key . '" class="wpeip_term" lcode="'.$lcode.'">' . $text . '</span>';
    else
        echo $text;
}

/**
 * 
 * Returns a text and adds it to be edited in the admin area. 
 * By default, it does not create an Edit in Place form. 
 * 
 * 
 * @param string $term the default value for this string
 * @param string $description the description of this string, will show on the admin screen
 * 
 * @return string the text you asked for 
 */
function __i($term, $description = '', $lcode = WPLANG, $frontend_form = false) {
    global $WPEIP;
    $source = $WPEIP->source();
    return $source->getTerm($term, $description, $lcode, $frontend_form);
}
?>
