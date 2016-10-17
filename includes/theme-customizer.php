<?php
class Fais_Spine_Theme_Customizer {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}










	/**
	 * Add custom settings and controls to the WP Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function customize_register( $wp_customize ) {

	// Inlcude the Alpha Color Picker.
	//require_once( dirname( __FILE__ ) . '/assets/alpha-color-picker/alpha-color-picker.php' );

	// Include the Multi Color Picker.
	require_once( dirname( __FILE__ ) . '/assets/multi-color-picker/multi-color-picker.php' );

		$wp_customize->add_setting( 'spine_options[megamenu_show]', array(
			'default' => "true",
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control( 'megamenu_show', array(
			'label'    => __( 'MegaMenu Display' ),
			'section'  => 'static_background',
			'settings' => 'spine_options[megamenu_show]',
			'type'     => 'select',
			'choices'  => array(
				'true'  => 'Show the megamenu',
				'false'  => 'Don\'t show the megamenu',
			),
		) );



		// background image
		$wp_customize->add_setting( 'spine_options[background_url]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'background',
				array(
					'label'      => __( 'Upload `<body>` background' ),
					'section'    => 'static_background',
					'settings'   => 'spine_options[background_url]',
					//'context'    => 'your_setting_context'
				)
			)
		);
		// background image
		$wp_customize->add_setting( 'spine_options[jacket_background_url]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'jacket_background_url',
				array(
					'label'      => __( 'Upload `<div id="jacket">` background' ),
					'section'    => 'static_background',
					'settings'   => 'spine_options[jacket_background_url]',
					//'context'    => 'your_setting_context'
				)
			)
		);

	/**
	 * Define a default palette that we'll use for some of the colors.
	 *
	 * We could certainly define a separate palette for each color also.
	 */
	$palette = array(
		'rgba(255, 0, 0, 0.7)',
		'rgba(54, 0, 170, 0.8)',
		'#FFCC00',
		'rgba( 20, 20, 20, 0.8 )',
		'#00CC77',
	);

	/**
	 * Define our color settings under the group "Background Colors".
	 *
	 * This is one of the arrays that we'll pass to our helper function to
	 * register each setting and group them under a single control.
	 */
	$bg_colors = array(
		'background_color' => array(
			'label'   => __( 'Body Background' ),
			'default' => 'rgba(255, 0, 0, 0.7)',
			'palette' => $palette, // This could also be true or false
		),
		'primary_accent_color' => array(
			'label'      => __( 'Primary Accent Color' ),
			'palette' => $palette, // This could also be true or false
			'description' => 'The primary color will alter areas like the top borders. The classes to use:<br/><b>Background</b> : <code>primary-accent-bk</code> <br/><b>Text</b> : <code>primary-accent-text</code>',

		),

		'secoundary_accent_color' => array(
			'label'      => __( 'Secoundary Accent Color' ),

			'palette' => $palette, // This could also be true or false
			'description' => 'The primary color will alter areas like the top borders. The classes to use:<br/><b>Background</b> : <code>secoundary-accent-bk</code> <br/><b>Text</b> : <code>secoundary-accent-text</code>',
		),
		'header_color' => array(
			'label'      => __( 'Page Header Color' ),
			'palette' => $palette, // This could also be true or false
			'description' => '',
		),
		'header_text_color' => array(
			'label'      => __( 'Header Text Color' ),
			'palette' => $palette, // This could also be true or false'description' => 'The primary color will alter areas like the top borders. The classes to use:<br/><b>Background</b> : <code>secoundary-accent-bk</code> <br/><b>Text</b> : <code>secoundary-accent-text</code>',
		),
		/*'header_bg' => array(
			'label'   => __( 'Header Background'),
			'default' => 'rgba(54, 0, 170, 0.8)',
			'palette' => $palette,
		),
		'sidebar_bg' => array(
			'label'   => __( 'Sidebar Background' ),
			'default' => '#FFCC00',
			'palette' => $palette,
		),
		'article_bg' => array(
			'label'   => __( 'Article Background'),
			'default' => 'rgba( 20, 20, 20, 0.8 )',
			'palette' => $palette,
		),
		'footer_bg' => array(
			'label'   => __( 'Footer Background'),
			'default' => '#00CC77',
			'palette' => $palette,
		),*/
	);

	/**
	 * Set up the array of standard control data.
	 *
	 * This could also have an active_callback, a sanitize_callback, etc.
	 */
	$bg_colors_control_data = array(
		'label'       => __( 'Theme Color palette' ),
		'description' => __( 'The class to use the colors are in the format of <code>`block_name-type_name`</code>.\r\n\r\n <strong>type_name</strong> is the element\s part.\r\n  <strong>type_name</strong> is the element\s part.  Values supported are `bk` for background amd `text` for text. \r\n\r\nThis means for the &quot;Secoundary Accent Color&quot; color for the text block would be <code>secoundary-accent-text</code>' ),
		'section'     => 'static_background',
	);

	/**
	 * Use the helper function to register the group of settings and associate them with
	 * a single Multi Color Picker control.
	 */
	components_register_color_group(
		$wp_customize,
		'spine_options',
		$bg_colors,
		$bg_colors_control_data,
		$palette,
		'postMessage'
	);

		// background color
		/*$wp_customize->add_setting( 'spine_options[background_color]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
            $wp_customize,
            'background_color',
            array(
            'label'      => __( 'Background Color' ),
            'section'    => 'static_background',
            'settings'   => 'spine_options[background_color]',
            'description' => 'The class to use <code>background-color</code>',
			) )
		);

		// primary_accent
		$wp_customize->add_setting( 'spine_options[primary_accent_color]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'primary_accent_color',
				array(
				'label'      => __( 'Primary Accent Color' ),
				'section'    => 'static_background',
				'settings'   => 'spine_options[primary_accent_color]',
				'description' => 'The primary color will alter areas like the top borders. The classes to use:<br/><b>Background</b> : <code>primary-accent-bk</code> <br/><b>Text</b> : <code>primary-accent-text</code>',
			) )
		);



		// secoundary_accent
		$wp_customize->add_setting( 'spine_options[secoundary_accent_color]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'secoundary_accent_color',
				array(
				'label'      => __( 'Secoundary Accent Color' ),
				'section'    => 'static_background',
				'settings'   => 'spine_options[secoundary_accent_color]',
				'description' => 'The primary color will alter areas like the top borders. The classes to use:<br/><b>Background</b> : <code>secoundary-accent-bk</code> <br/><b>Text</b> : <code>secoundary-accent-text</code>',
			) )
		);



		// header_color
		$wp_customize->add_setting( 'spine_options[header_color]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'header_color',
				array(
				'label'      => __( 'Header Color' ),
				'section'    => 'static_background',
				'settings'   => 'spine_options[header_color]',
			) )
		);
		// header_text_color
		$wp_customize->add_setting( 'spine_options[header_text_color]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'header_text_color',
				array(
				'label'      => __( 'Header Text Color' ),
				'section'    => 'static_background',
				'settings'   => 'spine_options[header_text_color]',
			) )
		);






		*/

		$wp_customize->add_section( 'static_background', array(
			'title' => __( 'FAIS theme settings', 'static_background' ),
		) );
		$wp_customize->add_setting( 'spine_options[background_url]', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );

		$wp_customize->add_setting( 'spine_options[contact_streetAddress2]', array(
			'default'    => '',
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		) );
		$wp_customize->add_control( 'contact_streetAddress2', array(
			'label'    => false,
			'section'  => 'section_spine_contact',
			'settings' => 'spine_options[contact_streetAddress2]',
			'type'     => 'text',
			'priority' => 411,
			'input_attrs' => array(
				//'class' => 'my-custom-class-for-js',
				//'style' => 'border: 1px solid #900',
				'placeholder' => __( 'e.g. Suite 111' ),
			),
		) );

		$wp_customize->add_setting( 'spine_options[contact_verbal_location]', array(
			'default'    => '',
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		) );
		$wp_customize->add_control( 'contact_verbal_location', array(
			'label'    => __( 'Verbal Location' ),
			'section'  => 'section_spine_contact',
			'settings' => 'spine_options[contact_verbal_location]',
			'type'     => 'text',
			'priority' => 412,
			'input_attrs' => array(
				//'class' => 'my-custom-class-for-js',
				//'style' => 'border: 1px solid #900',
				'placeholder' => __( 'e.g. walk in and turn right' ),
			),
		) );
		/*$wp_customize->add_control( 'spine_options[front_page_title]', array(
			'label' => 'Show title on front page',
			'section' => 'static_background',
			'settings' => 'spine_options[front_page_title]',
			'type' => 'checkbox',
			'active_callback' => function() { return 'page' == get_option( 'show_on_front' ); },
		) );*/

		// background image
		$wp_customize->add_setting( 'spine_options[flexwork_coverage]', array(
			'default' => 'devices-lite',
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control( 'flexwork_coverage', array(
			'label'    => __( 'Flexworks width set' ),
			'section'  => '_flexwork',
			'settings' => 'spine_options[flexwork_coverage]',
			'type'     => 'select',
			'choices'  => array(
				'lite'  => 'the 5 most common viewport widths',
				'devices-lite'  => 'top 10 device widths',
				'devices'  => '(full) most current device widths',
				'50s'  => 'increments of 50px plus full devices',
				'25s'  => 'increments of 25px plus full devices',
			),
		) );
		$wp_customize->add_section( '_flexwork', array(
			'title' => __( 'Flexwork' ),
		) );

	}
}
new Fais_Spine_Theme_Customizer();




/**
 * Helper function for registering a group of color settings.
 *
 * @param  Object  $wp_customize      The main Customizer object.
 * @param  String  $option_name       The shared option name to use for the settings.
 * @param  Array   $color_settings    The array of color settings data.
 * @param  Array   $control_data      The data to pass to the control.
 * @param  Array   $fallback_palette  An array of fallback palette colors to use if a palette is not included in $color_settings. (optional)
 * @param  String  $transport         The transport method for the setting group.
 */
function components_register_color_group( $wp_customize, $option_name, $color_settings = array(), $control_data = array(), $fallback_palette = 'true', $transport = 'refresh' ) {

	/**
	 * Loop over the colors array and register each setting while also building
	 * the color_settings and color_data arrays that we'll send to the control.
	 */
	foreach ( $color_settings as $setting_name => $setting_data ) {

		// For this example we'll store all of our colors in a single settings array,
		// which requires using the setting type "option". We could also use the
		// setting type "theme_mod" by giving each setting its own unique option key.
		$color_setting_id = $option_name . "[{$setting_name}]";

		// Make default, palette, and show_opacity optional by providing fallbacks here.
		$setting_data['default'] = ( isset( $setting_data['default'] ) ) ? $setting_data['default'] : '#000000';
		$setting_data['palette'] = ( isset( $setting_data['palette'] ) ) ? $setting_data['palette'] : $fallback_palette;
		$setting_data['show_opacity'] = ( isset( $setting_data['show_opacity'] ) ) ? $setting_data['show_opacity'] : 'true';

		// Register the current setting.
		// This still needs a proper sanitize_callback.
		$wp_customize->add_setting(
			$color_setting_id,
			array(
				'default'    => $setting_data['default'],
				'type'       => 'option',
				'capability' => 'edit_theme_options', // Modify this as needed.
				'transport'  => $transport, // postMessage or refresh
			)
		);

		// Build the simple array that contains only the color setting names.
		// We'll pass this as the "settings" value to our control.
		$settings[] = $color_setting_id;

		// Build the more advanced color_data array that contains all the extra information
		// we need for each color setting. We'll pass this to our control.
		$color_data[ $color_setting_id ] = array(
			'label'        => $setting_data['label'],
			'default'      => $setting_data['default'],
			'show_opacity' => $setting_data['show_opacity'],
			'palette'      => $setting_data['palette'],
		);
	}

	/**
	 * Add our arrays to $control_data
	 */
	$control_data['settings'] = $settings;
	$control_data['color_data'] = $color_data;

	/**
	 * Register the Multi Color Control.
	 */
	$wp_customize->add_control(
		new Customize_Multi_Color_Control(
			$wp_customize,
			$option_name,
			$control_data
		)
	);

}
