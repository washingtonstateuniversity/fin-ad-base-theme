<?php
//PSR-1/2 -ish

add_action( 'init', 'spine_load_builder_module_custom', 99 );
/**
* Allow our version of Make's builder tool to be disabled at the
* platform or WordPress installation level.
*
* Note: admin_init is too late for this to be brought in.
*/
function spine_load_builder_module_custom() {

	if ( true === apply_filters( 'spine_enable_builder_module', true ) ) {
		include_once 'inc/builder.php';
	}
}

add_filter( 'theme_page_templates', 'spine_show_builder_page_template_custom', 10, 1 );
/**
* If builder functionality is not available, do not show the builder template
* on the list of available page templates.
*
* @param array $page_templates List of available page templates.
*
* @return array Modified list of page templates.
*/
function spine_show_builder_page_template_custom( $page_templates ) {

	if ( false === apply_filters( 'spine_enable_builder_module', true ) ) {
		unset( $page_templates['template-builder.php'] );
	}
	return $page_templates;
}


add_action( 'wp_enqueue_scripts', 'fais_customizer_enqueue_scripts', 20 );
/**
 * Enqueue the styles and scripts used inside the Customizer.
 */
function fais_customizer_enqueue_scripts() {

	wp_enqueue_style( 'flexwork-devices', get_stylesheet_directory_uri() . '/TempAssests/css/flexwork-devices.css' );

	wp_enqueue_script( 'flexibility', get_stylesheet_directory_uri() . '/TempAssests/js/flexibility.js', array( 'jquery' ), spine_get_script_version(), true );
	wp_script_add_data( 'flexibility', 'conditional', 'lte IE 10' );
}



class WSU_FinAd_BaseTheme
{
	/**
	* @var WSU_FinAd_BaseTheme
	*/
	private static $instance;

	/**
	* Maintain and return the one instance and initiate hooks when
	* called the first time.
	*
	* @return \WSU_FinAd_BaseTheme
	*/
	public static function getInstance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSU_FinAd_BaseTheme;
		}
		return self::$instance;
	}
}
add_action( 'after_setup_theme', 'finAdBaseTheme' );
/**
* Start things up.
*
* @return \WSU_FinAd_BaseTheme
*/
function finAdBaseTheme() {
	return WSU_FinAd_BaseTheme::getInstance();
}
