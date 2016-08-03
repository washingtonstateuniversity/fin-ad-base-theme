(function($) {

	'use strict';

	// only do this if TinyMCE is available
	if ( 'undefined' === typeof( tinymce ) ) {
		return;
	}
	// only do this if TinyMCE is available
	/*if ( 'undefined' === typeof( widgetShortcode ) ) {
		return;
	}*/

	tinymce.create( 'tinymce.plugins.contactCard', {
		init: function( editor, url ) {
			var items = [];
			/*$.each( widgetShortcode.widgets, function( i, v ){
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
			} );*/

			/*editor.addButton( 'contactCard', {
				title: widgetShortcode.title,
				type : 'menubutton',
				//cmd: 'widgetShortcode_insert',
				image: widgetShortcode.image,
				menu : items
			} );*/


		editor.addButton( 'contactCard', {
			text: 'Add contact card',
			icon: false,
			//type: 'menubutton',
			onclick: function() {
				editor.windowManager.open( {

//[contact_cards position="Fiscal Technician 3" name="Tarryn Anderson" tel="509-335-2057" email="tarryn.anderson@wsu.edu" descrip="Hours: 8:00-4:30"]


					title: 'Insert a contact card Shortcode',
					body: [
						{
							type: 'textbox',
							name: 'position',
							label: 'Position',
							value: ''
						},{
							type: 'textbox',
							name: 'name',
							label: 'Name',
							value: ''
						},{
							type: 'textbox',
							name: 'tel',
							label: 'Telephone',
							value: ''
						},{
							type: 'textbox',
							name: 'Email',
							label: 'email',
							value: ''
						},{
							type: 'textbox',
							name: 'descrip',
							label: 'Descriptions',
							value: '',
							multiline: true,
							minWidth: 300,
							minHeight: 100
						},{
							type: 'textbox',
							name: 'profile_url',
							label: 'Profile Url',
							value: ''
						},


						/*{
							type: 'textbox',
							name: 'multilineName',
							label: 'Multiline Text Box',
							value: 'You can say a lot of stuff in here',
							multiline: true,
							minWidth: 300,
							minHeight: 100
						},
						{
							type: 'listbox',
							name: 'listboxName',
							label: 'List Box',
							'values': [
								{text: 'Option 1', value: '1'},
								{text: 'Option 2', value: '2'},
								{text: 'Option 3', value: '3'}
							]
						}*/
					],
					onsubmit: function( e ) {
						editor.insertContent( '[contact_cards position="' + e.data.position + '" name="' + e.data.name + '" tel="' + e.data.tel + '" email="' + e.data.email + '" descrip="' + e.data.descrip + '" profile_url="' + e.data.profile_url + '" ]');
					}
				});
			}
		});



		}
	});
	tinymce.PluginManager.add( 'contactCard', tinymce.plugins.contactCard );
})(jQuery);
