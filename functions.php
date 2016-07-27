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

	wp_enqueue_style( 'flexwork-base', 'http://webcore.fais.wsu.edu/resources/flexwork/flexwork-'.$coverage.'.css', array( 'fais_spine-theme-print' ), spine_get_script_version() );
	wp_enqueue_style( 'flexwork-typography', 'http://webcore.fais.wsu.edu/resources/flexwork/extra/flexwork-typography.css', array( 'flexwork-base' ), spine_get_script_version() );
	wp_enqueue_style( 'flexwork-ui', 'http://webcore.fais.wsu.edu/resources/flexwork/extra/flexwork-ui.css', array( 'flexwork-typography' ), spine_get_script_version() );

	wp_enqueue_style( 'fais_spine-theme-child', get_stylesheet_directory_uri() . '/' . $child_stylesheet, array( 'flexwork-typography' ), spine_get_script_version() );

	wp_enqueue_script( 'tether', 'http://webcore.fais.wsu.edu/resources/flexwork/extra/tether.min.js', array( 'jquery' ), spine_get_script_version(), true );
	wp_enqueue_script( 'drop', 'http://webcore.fais.wsu.edu/resources/flexwork/extra/drop.min.js', array( 'tether' ), spine_get_script_version(), true );
	wp_enqueue_script( 'child_controll', get_stylesheet_directory_uri() . '/js/child_controll.js', array( 'jquery' ), spine_get_script_version(), true );




	wp_enqueue_script( 'flexibility', 'http://webcore.fais.wsu.edu/resources/flexwork/flexibility.js', array( 'jquery' ), spine_get_script_version(), true );
	wp_script_add_data( 'flexibility', 'conditional', 'lte IE 10' );

	wp_enqueue_script( 'megamenu', 'http://webcore.fais.wsu.edu/resources/central_FnA_theme/megamenu/bootstrap.js', array( 'flexibility' ), spine_get_script_version(), true );
	$fais = array(
		'local' => array(
			'title' => get_bloginfo("name"),
		),
		'parents' => [[  ]]
	);
	wp_localize_script( 'wsu-spine', 'fais', $fais );
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




// Add Shortcode
function contact_block_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'show_verbal' => 'true',
			'show_phone' => 'true',
		),
		$atts
	);
	ob_start(); ?>




<div class="flex-column items-start">
	<h3><?php echo __( 'Address' )?>:</h3>
	<div><?php echo esc_attr( spine_get_option( 'contact_name' ) ); ?></div>
	<div><?php echo esc_attr( spine_get_option( 'contact_department' ) ); ?></div>
	<div><?php echo esc_attr( spine_get_option( 'contact_streetAddress' ) ); ?></div>
	<?php
	$street_address2 = fais_spine_get_option( 'contact_streetAddress2' );
	if ( ! empty( $street_address2 ) ) {
		?>
		<div><?php echo esc_attr( $street_address2 ); ?></div>
		<?php
	}
	?>
	<div class="flex-row full-width items-start">
		<div class="grid-part pad-no fifths-2"><?php echo esc_attr( spine_get_option( 'contact_addressLocality' ) ); ?></div>
		<div class="grid-part pad-no fifths-3"><?php echo esc_attr( spine_get_option( 'contact_postalCode' ) ); ?></div>
	</div>

	<?php
	$verbal_location = fais_spine_get_option( 'contact_verbal_location' );
	if ( ! empty( $verbal_location ) ) {
		?><br/>
			<h3><?php echo __( 'Verbal Location' )?>:</h3>
			<div><?php echo esc_attr( $verbal_location ); ?></div>

		<?php
	}
	?>
	<br/>
	<h3><?php echo __( 'Contact Methods' )?>:</h3>

	<?php
	$contact_telephone = spine_get_option( 'contact_email' );
	if ( ! empty( $contact_telephone ) && '' !== trim( $contact_telephone ) ) {
		?>
		<div class="flex-row full-width items-start">
			<h4 class="grid-part pad-no fifths-2"><?php echo __( 'Phone' )?>:</h4>
			<div class="grid-part pad-no fifths-3"><?php echo esc_attr( $contact_telephone ); ?></div>
		</div>
		<?php
	}
	?>

	<?php
	$contact_email = spine_get_option( 'contact_email' );
	if ( ! empty( $contact_email ) && '' !== trim( $contact_email ) ) {
		?>
		<div class="flex-row full-width items-start">
			<h4 class="grid-part pad-no fifths-2"><?php echo __( 'Email' )?>:</h4>
			<div class="grid-part pad-no fifths-3"><?php echo esc_attr( $contact_email ); ?></div>
		</div>
		<?php
	}
	?>

	<?php
	$contact_point = spine_get_option( 'contact_ContactPoint' );
	if ( ! empty( $contact_point ) && '' !== trim( $contact_point ) ) {
		?>
		<div class="flex-row full-width items-start">
			<h4 class="grid-part pad-no fifths-2"><?php echo __( 'Point of Contact' )?>:</h4>
			<div class="grid-part pad-no fifths-3"><?php echo esc_attr( $contact_point ); ?></div>
		</div>
		<?php
	}
	?>
</div>

<?php
	$contact_content = ob_get_clean();

	return $contact_content;

}
add_shortcode( 'contact_block', 'contact_block_shortcode' );




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
	$background_url 	= spine_get_option( 'background_url' );
	$background_color 	= spine_get_option( 'background_color' );
	$output = "<style> body:not(.has-background-image) {background-image:url('".$background_url."'); } body:not(.has-background-image) {background-color:".$background_color.'; } </style>';
	echo $output;
}
