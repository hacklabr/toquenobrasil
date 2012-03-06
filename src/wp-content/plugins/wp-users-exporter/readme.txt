=== WP Users Exporter ===
Contributors: rafael.chaves.freitas, leogermani
Tags: users, export, exporter, user, export users
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.1

Simple and complete plugin that allows you to export the users of your site in a spreadsheet, csv or html format with all metadatas.

== Description ==

Simple plugin that allows you to export the users of your site in a spreadsheet, csv or html format with all metadatas.

We have been looking for a good users exporter plugin for some time but none of them seems to accomplish this simple task, so we created this one. This plugin does everything you need when it comes to exporting your users.

* choose the metadata you want to export
* choose the label for each field
* choose the order the fields will be exported
* support for BuddyPress custom profile fields
* support for Multi Site installation
* Want to expor in another format? Its very easy (for a PHP developer) to support new formats.

== Installation ==

1. Upload the package content to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Tools > Export Users and enjoy
1. You can then go to Settings > WP Users Exporter and check some advanced options


== Usage ==

This plugin creates two pages on your admin panel.

1. Under Settings: Allows site administrator to decide who will be able to export users (based on user roles) and what options will be available for these users.
1. Under Tools: The exporter. Simply choose what users you want to export, the metadata you want and the format.


== Changelog ==

30 jan 2012 - 1.1
Security fixes

15 dec 2011 - 1.0
Export user Role.
Unserialize exported data when serialized.

