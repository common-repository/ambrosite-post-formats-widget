=== Ambrosite Post Formats Widget ===
Contributors: ambrosite
Donate link: http://www.ambrosite.com/plugins
Tags: post formats, post format, widget, widgets, archives, archive, list, dropdown
Requires at least: 3.1
Tested up to: 3.3
Stable tag: trunk

A list or dropdown of Post Format archives. Works much the same as the Categories widget.

== Description ==

Post Formats are a theme feature introduced in WordPress version 3.1. This plugin creates a simple widget that lets you place a list or dropdown of post formats in your sidebar. It works much the same as the Categories widget. The Post Formats Widget offers the following options: set a custom widget title, set custom tooltip text, display as a list or as a dropdown, show or hide post counts, show or hide post format ID numbers.

**You must have at least one post assigned to a format other than 'Standard'** in order for this widget to display anything. Note that there is a %format variable you can use in custom tooltip text to output the format name. Also, the format ID refers to the term_id in the wp_terms table. You would not normally want to display the format IDs to your site visitors, however it may be useful to know the IDs during theme development, since Post Formats are just a specialized case of a custom taxonomy.

== Installation ==

* Upload 'ambrosite-post-formats-widget.php' to the '/wp-content/plugins/' directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* Use the Appearance|Widgets menu to configure the widget and add it to your sidebar.

== Frequently Asked Questions ==

n/a

== Screenshots ==

1. The Post Formats archive widget

== Changelog ==

= 1.1 =
* Added 'current_format_item' CSS class to the currently selected format (list display).
* Added 'selected' attribute to the currently selected format (dropdown display).
* Changed dropdown to use permalinks instead of query strings when permalinks are enabled.

= 1.0 =
* Initial version.