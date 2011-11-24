=== WP Edit in Place ===
Contributors: rafael.chaves.freitas, leogermani
Tags: template, framework, edit in place, tools, developer
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.0

This plugin is for theme developers who want to let their users (site administrators) edit all the strings in the template interface.

== Description ==

This plugin is for theme developers who want to let their users (site administrators) to edit all the strings in the template interface.

It works in a similar way that the internacionalization functions _e() and __(). When you want a string to be editable by the admin, you will use: _oi('Default value for this text')

The admin will then be able to edit it directly from the front end or through the admin panel.

It will work together with you

== Installation ==

1. Upload the package content to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. See 'Usage' to understand how it works


== Usage ==

This plugin gives you, theme developer, two useful functions to add editable texts to your template. _oi() tha echoes the text and __i() that returns it.

Why "oi"? because its easy to type.

Simple use:

`<?php _oi('Please contact us'); ?>`

This simple line of code will:
1. echo the text
1. Give the ability for the admin to edit it right from the front end (Ctrl + Click over the text)
1. Create an entry in the admin, so he/she can also edit ir from there

Tip: Hold Shift + Ctrl to highlight all the editable texts in the page

Its a good idea to add a description to the text, so when you go to the admin panel, you know which text is which. This would be:

`<?php _oi('Please contact us', 'the text tha goes on the bottom right corner of the footer'); ?>`

If the text is an attribute of an HTML element, the value of a button for example, you can not user the edit in place funcionality. In those
cases you will have to disable it:

`<?php _oi('Please contact us', 'the text tha goes on the bottom right corner of the footer', false); ?>`

If you are using a multi language plugin, you can pass the current language, and you will have all the options in the admin:

`<?php _oi('Please contact us', 'the text tha goes on the bottom right corner of the footer', 'en_US'); ?>`
`<?php _oi('Please contact us', 'the text tha goes on the bottom right corner of the footer', $language_code); ?>`

If you need to pass the language AND disable the front end editor:

`<?php _oi('Please contact us', 'the text tha goes on the bottom right corner of the footer', $language_code, false); ?>`

If you want to return it to use somewhere in your code:

`<?php $myvar = __i('Please contact us', 'the text tha goes on the bottom right corner of the footer'); ?>`
