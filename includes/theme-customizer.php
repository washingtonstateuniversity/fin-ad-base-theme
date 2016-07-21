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
					'label'      => __( 'Upload a logo' ),
					'section'    => 'static_background',
					'settings'   => 'spine_options[background_url]',
					//'context'    => 'your_setting_context'
				)
			)
		);
		// background image
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
	}
}
new Fais_Spine_Theme_Customizer();
