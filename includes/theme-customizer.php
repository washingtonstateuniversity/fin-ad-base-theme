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
		// background color
		$wp_customize->add_setting( 'spine_options[background_color]', array(
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
				'description' => 'The primary color will alter areas like the top borders',
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
			) )
		);

		$wp_customize->add_section( 'static_background', array(
			'title' => __( 'Background', 'static_background' ),
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
			'default' => 'devices-light',
			'capability' => 'edit_theme_options',
			'type' => 'option',
		) );
		$wp_customize->add_control( 'flexwork_coverage', array(
			'label'    => __( 'Flexworks width set' ),
			'section'  => '_flexwork',
			'settings' => 'spine_options[flexwork_coverage]',
			'type'     => 'select',
			'choices'  => array(
				'light'  => 'the 5 most common viewport widths',
				'devices-light'  => 'top 10 device widths',
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
