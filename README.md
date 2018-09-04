# Dynavic Pages Plugin
Creates a dynamic page that has customizable content. The content can be shown or hidden based on the value in a user cookie or url parameter.

The plugin includes a shortcode which can be used in all posts, pages, widgets and excerpts.

## Installation
1. Clone or download this repository into the `wp-content/plugins/` folder of your wordpress installation.
2. Open the wordpress admin `/wp-admin/`
3. Click on the plugins and activate

## How to Use
Once activated the plugin will create a new post type `Dynavic Pages` which can be used to create new pages. These pages could be used as landing pages for marketing campaigns or as an additional section to display content specific to users with the shortcode.

### Shortcode
This shortcode is the real magic of this plugin and it can be placed in any post, widget, page or excerpt.

The shortcode `[dynavic-content filter='vip'][/dynavic-content]` is what will allow the user to determine which content to show to whom. The `filter` parameter can be anything that the user chooses, however, in order for the content within the tags to appear on the page, there must be a cookie with the name `dynavic-content` with the same value.

Also included is a plugin for the tinyMCE editor that will allow users to click a button in the toolbar which will present them with a prompt to set the filter value and the shortcode will be added where the cursor is with the filter set. If there is any text selected, then that text will automatically be wrapped by the shortcode.
