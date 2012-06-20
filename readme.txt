=== Subtitle ===
Contributors: Horttcore
Donate link: http://www.horttcore.de
Tags: subtitle
Requires at least: 3.3
Tested up to: 3.3
Stable tag: 1.0

Subtitle for post type ´page´

== Description ==

This Plugin creates a new input field for subtitles

== Installation ==

*   Put the plugin file in your plugin directory and activate it in your WP backend.
*   Go to edit a page
*   Below title you can enter the ´Subtitle´

== Screenshots ==

1. Screenshot of the Meta box in the content

== Frequently Asked Questions ==

= How can I output my subtitle =
There are two functions for subtitles.

`<?php the_subtitle(); ?>`

and

`<?php $title = get_subtitle( $post_id ); ?>`

= Are there any filters I can use? =
There is just one filter called ´the_subtitle´

= Where can I get support or report bugs?=
Please use the github to report bugs or add feature requests!
https://github.com/Horttcore/WordPress-Subtitle

= Are there any actions I can hook on? =
No hooks yet sorry...

= I want subtitles for another post type, is this possible? =
Sure nothing easier then that, simply add post type support for your custom post type.
`add_post_type_support( 'YOUR_POST_TYPE', 'subtitle' )`

== Changelog ==

1.0
*   Initial release
