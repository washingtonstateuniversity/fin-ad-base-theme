<?php
//PSR-1/2 -ish

include_once( 'includes/theme-customizer.php' ); // Include customizer functionality.

add_action( 'init', 'spine_load_builder_module_custom', 99 );
/**
* Allow our version of Make's builder tool to be disabled at the
* platform or WordPress installation level.
*
* Note: admin_init is too late for this to be brought in.
*/
function spine_load_builder_module_custom() {
	$spine_options = get_option( 'spine_options' );
	$spine_options['grid_style'] = 'hybrid';
	update_option( 'spine_options', $spine_options );
	if ( true === apply_filters( 'spine_enable_builder_module', true ) ) {
		include_once 'inc/builder.php';
	}
}


add_action( 'wp_enqueue_scripts', 'fais_customizer_enqueue_scripts', 21 );
/**
 * Enqueue the styles and scripts used inside the Customizer.
 */
function fais_customizer_enqueue_scripts() {
	 wp_dequeue_style( 'spine-theme' );
	 wp_dequeue_style( 'spine-theme-extra' );
	 wp_dequeue_style( 'spine-theme-child' );
	 wp_dequeue_style( 'spine-theme-print' );
	/**
	* we remove the enqueued styles as they didn't have an order to them that we could work with
	* also we know this is a child theme so no need to check.
	 */

	wp_enqueue_style( 'fais_spine-theme',       get_template_directory_uri()   . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
	if ( 'skeletal' !== spine_get_option( 'theme_style' ) ) {
		wp_enqueue_style( 'fais_spine-theme-extra', get_template_directory_uri()   . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array( 'fais_spine-theme' ), spine_get_script_version() );
	}

	if ( apply_filters( 'spine_child_min_css', false ) ) {
		$child_stylesheet = 'style.min.css';
	} else {
		$child_stylesheet = 'style.css';
	}

	wp_enqueue_style( 'fais_spine-theme-print', get_template_directory_uri() . '/css/print.css', array( 'fais_spine-theme' ), spine_get_script_version(), 'print' );

	wp_deregister_style( 'open-sans' );
	wp_enqueue_style( 'open-sans', '//fonts.googleapis.com/css?family=Open+Sans:100,200,300,400,500,600,700,800,900', array(), false );

	// All theme styles have been output at this time. Plugins and other themes should print styles here, before blocking
	// Javascript resources are output.
	do_action( 'spine_enqueue_styles' );

	$coverage = fais_spine_get_option( 'flexwork_coverage', 'devices-light' );

	wp_enqueue_style( 'flexwork-base', 'https://webcore.fais.wsu.edu/resources/flexwork/flexwork-'.$coverage.'.css', array( 'fais_spine-theme-print' ), spine_get_script_version() );
	wp_enqueue_style( 'flexwork-typography', 'https://webcore.fais.wsu.edu/resources/flexwork/extra/flexwork-typography.css', array( 'flexwork-base' ), spine_get_script_version() );
	wp_enqueue_style( 'flexwork-ui', 'https://webcore.fais.wsu.edu/resources/flexwork/extra/flexwork-ui.css', array( 'flexwork-typography' ), spine_get_script_version() );

	wp_enqueue_style( 'fais_spine-theme-child', get_stylesheet_directory_uri() . '/' . $child_stylesheet, array( 'flexwork-typography' ), spine_get_script_version() );

	wp_enqueue_script( 'tether', 'https://webcore.fais.wsu.edu/resources/flexwork/extra/tether.min.js', array( 'jquery' ), spine_get_script_version(), true );
	wp_enqueue_script( 'drop', 'https://webcore.fais.wsu.edu/resources/flexwork/extra/drop.min.js', array( 'tether' ), spine_get_script_version(), true );
	wp_enqueue_script( 'child_controll', get_stylesheet_directory_uri() . '/js/child_controll.js', array( 'jquery' ), spine_get_script_version(), true );

	wp_enqueue_script( 'flexibility', 'https://webcore.fais.wsu.edu/resources/flexwork/flexibility.js', array( 'jquery' ), spine_get_script_version(), true );
	wp_script_add_data( 'flexibility', 'conditional', 'lte IE 10' );

	wp_enqueue_script( 'megamenu', 'https://webcore.fais.wsu.edu/resources/central_FnA_theme/megamenu/bootstrap.js', array( 'flexibility' ), spine_get_script_version(), true );
	$fais_site_object = array(
		'local' => array(
			'title' => get_bloginfo( 'name' ),
		),
		'parents' => [ [] ],
	);
	wp_localize_script( 'wsu-spine', 'fais_site_object', $fais_site_object );
}





function fais_spine_get_option( $option_name, $default = '' ) {
	$spine_options = get_option( 'spine_options' );

	if ( isset( $spine_options[ $option_name ] ) ) {
		// A child theme can override a specific spine option with the spine_option filter.
		$spine_options[ $option_name ] = apply_filters( 'spine_option', $spine_options[ $option_name ], $option_name );
		return $spine_options[ $option_name ];
	} else {
		return $default;
	}
}









// will refactor in to this later in crunch mode
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


add_action( 'wp_head','background_hook_css', 21 );

function background_hook_css() {

$background_url = fais_spine_get_option( 'background_url', '' );
$background_color = fais_spine_get_option( 'background_color', '#9bbdf5' );
$secoundary_accent_color = fais_spine_get_option( 'secoundary_accent_color', '#1122a3' );
$primary_accent_color = fais_spine_get_option( 'primary_accent_color', '#1122a3' );

	$output = "<style> body:not(.has-background-image) {background-image:url('".$background_url."'); }
	body:not(.has-background-image) {background-color:".$background_color.'; }
    .primary-accent-bk{background-color:'.$primary_accent_color.';}
    .secoundary-accent-bk{background-color:'.$secoundary_accent_color.';}
    .primary-accent{color:'.$primary_accent_color.';}
    .secoundary-accent{color:'.$secoundary_accent_color.';}
    div#border_top{background-color:'.$primary_accent_color.'}
    div#border_bottom{background-color:'.$primary_accent_color.'}  </style>';
	echo $output;
}
