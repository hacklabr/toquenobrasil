=== post highlights ===
Contributors: LeoGermani, PedroGermani
Donate link: http://post-highlights.hacklab.com.br
Plugin URI: http://post-highlights.hacklab.com.br
Tags: post, highlight, home
Requires at least: 2.6
Tested up to: 3.0
Stable tag: 2.2

Add a nice looking animated highlights box to you theme, and lets you highlight your posts

== Description ==

Add a nice looking animated highlights box to you theme, and lets you highlight your posts

Features:

* Beautiful Jquery Box with fade between each picture
* Localization ready
* Easy to build your own theme
* Permission manager lets you choose who can highlight posts in your site

Refer to the <a href="http://post-highlights.hacklab.com.br">plugin website</a> for a live demo and documentation on how to build your theme

Localizations:

English <BR>
Brazilian Portuguese <BR>
Belorussian - by <a href="http://www.fatcow.com">FatCow</a> <BR>
Dutch - by <a href="http://www.bodrumturkeytravel.com">Rene</a>

== Installation ==

. Download the package
. Extract it to the "plugins" folder of your wordpress
. In the Admin Panes go to "Plugins" and activate it

IMPORTANT: If you are upgrading from a previous version, deactivate and reactivate the plugin

== Usage ==

Place the following code where you want the highlights to appear on your theme:

<?php if(function_exists("insert_post_highlights")) insert_post_highlights(); ?>

To highlight a post go to Manage > Posts and check the checkbox under the Highlight column

Go to Post Highlights > Settings to change some options, such as delay time, button color and size.

== Changelog ==

2.2 - Jul 08 2010
* Add option to choose in which order the posts should be loaded

2.1.1 - May 18 2010
* Fix theme Default 2 CSS bug for chrome in Windows (Thanks to Lucas Daniel)

2.1 - Feb 11 2010
* Possibility to highlight pages - Thanks to Pablo Faria
* Post Highlights can create and use its own thumbnail

2.0.1
* Layout fix for default theme on i.E
* Fix on JS prevents from JS error when no posts are highlighted

2.0 New version
