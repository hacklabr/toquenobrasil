<?php 

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

$this->themeSettings = array(
    'width' => __('Width', 'ph'),
    'height' => __('Height', 'ph'),
    'background_color' => __('Background color for the content and navigation arrows (e.g #FFCC00 or red)', 'ph')
);

$this->themeSettingsDefaults = array(
    'width' => 690,
    'height' => 225,
    'background_color' => "black"
);

$this->useThemeJS = false;

?>