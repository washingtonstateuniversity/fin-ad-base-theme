(function($) {

	'use strict';

	// only do this if TinyMCE is available
	if ( 'undefined' === typeof( tinymce ) ) {
		return;
	}
	// only do this if TinyMCE is available
	if ( 'undefined' === typeof( widgetShortcode ) ) {
		return;
	}

	tinymce.create( 'tinymce.plugins.widgetShortcode', {
		init: function( editor, url ) {
			var items = [];
			$.each( widgetShortcode.widgets, function( i, v ){
				var item = {
					'text' : v.title,
					'body': {
						'type': v.title
					},
					'onclick' : function(){
						editor.insertContent( '[widget id="' + v.id + '"]' );
					}
				};
				items.push( item );
			} );

			editor.addButton( 'widgetShortcode', {
				title: widgetShortcode.title,
				type : 'menubutton',
				//cmd: 'widgetShortcode_insert',
				image: widgetShortcode.image,
				menu : items
			} );
		}
	});
	tinymce.PluginManager.add( 'widgetShortcode', tinymce.plugins.widgetShortcode );
})(jQuery);
