/**
 * This function creates a plugin for the tinymce editor used by wordpress.
 * This plugin adds a button to the visual editor that will add the shortcode
 * for the dynavic-content automatically, making it much easier to use.
 * The plugin will also add the shortcode around any text that is selected.
 */
(function() {
    // create a new plugin called dynavic_content
    tinymce.create( 'tinymce.plugins.dynavic_content', {
        // The init function does all the work.
        // ed is a reference to the editor
        // url is a reference to the relative url of this plugin
        init: function(ed, url) {
            // add a button to the editor with the name and image provided
            ed.addButton('dynavic_content', {
                title: 'Dynavic Content',
                image: url + '/dynavic-content-button.png',
                // when the user clicks we want to ask them what they want to filter on
                // This value is compared to the value inside a cookie with the name 'dynavic-content'
                onclick : function() {
                    // ask the user for the filter value. The default value is "vip"
                    var filter = prompt("What value should the content filter on?", "vip");
                    // grab any selected text so it can be wrapped.
                    var selected_text = ed.selection.getContent();
                    // have the editor run the insertContent command so we can automatically
                    // add the shortcode to the editor where the cursor is.
                    ed.execCommand('mceInsertContent', false, '[dynavic-content filter="'+filter+'"]'+selected_text+'[/dynavic-content]');
                }
            });
        },
        // return null because we aren't creating a new instance
        // https://www.tiny.cloud/docs-3x//api/plugins/class_tinymce.Plugin.html/#createcontrol
        createControl: function(n, cm) {
            return null;
        },
        // This information will be displayed in about or help dialogs
        getInfo: function() {
            return {
                longname: 'Dynavic Content',
                author: 'Ammon Lockwood',
                authorurl: 'http://www.plaidtie.net/author/ammon',
                version: '0.0.1'
            };
        }
    });
    // tell the plugin manager to add the new plugin.
    tinymce.PluginManager.add('dynavic_content', tinymce.plugins.dynavic_content);
})();