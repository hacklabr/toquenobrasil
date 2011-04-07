<?php 
/*
 * This is an optional file you can have in your theme
 * 
 * Here you say what settings you want your theme to have
 */


// Here we register the settings by defining the themeSettings attribute
// each setting is a pair of key => value in the array
// key is the name of the settings. This is the name you will use to get the options later
// value is the human readable name that will show up in the Settings page
$this->themeSettings = array(
    'width' => __('Width', 'ph'),
    'height' => __('Height', 'ph'),
    'arrow_colors' => __('Background color for the navigation arrows (e.g #FFCC00 or red)', 'ph')
);

// you can also set the defaul values for each setting
// Its also a pair of key => value
// key is the name of the setting (the same name you  used on the array above
// value is the default value for that setting
$this->themeSettingsDefaults = array(
    'width' => 690,
    'height' => 225,
    'arrow_colors' => "red"
);

// You will probably never want to set this to true
// In fact, you can just remove this line
// But if you are crazy enough, you can write your own javascript file to handle
// with the post highlight front end (you can also make a copy of the original front-end.js
// and make your custom JS
// 
// If you set this to true, Post Highlights will look for a file called script.js
// here on your theme folder and will not load the default JS file
$this->useThemeJS = false;


?>