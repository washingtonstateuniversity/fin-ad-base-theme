<?php
//PSR-1/2 -ish

include_once( 'includes/theme-customizer.php' ); // Include customizer functionality.
include_once( 'includes/shortcode-contact-block.php' );
include_once( 'includes/shortcode-cards.php' );
include_once( 'includes/widget-shortcode.php' );

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
	$spine_options['large_format'] = ' folio max-1188';
	$spine_options['crop'] = '';
	$spine_options['spineless'] = '';
	$spine_options['broken_binding'] = '';

	update_option( 'spine_options', $spine_options );
	if ( true === apply_filters( 'spine_enable_builder_module', true ) ) {
		include_once 'inc/builder.php';
	}
}


/**
* Set flag on if site is in dev or production
* return boolean is production
*/
function is_development() {
	$filter_string = 'stage.,wsu.dev'; // this would be an option at some point
	$filters = explode( ',',$filter_string );
	$result = false;
	foreach ( $filters as $url_frag ) {
		if ( false !== strpos( $_SERVER['HTTP_HOST'], $url_frag ) ) {
			$result = true;
		}
	}
	return $result;
}


function add_meta_tags() {
	$is_dev_mode = fais_spine_get_option( 'is_dev_mode', 'false' );
	if ( 'true' === $is_dev_mode || is_development()  ) {
		echo '<meta name="robots" content="noindex" />' . "\n";
	}

}
add_action( 'wp_head', 'add_meta_tags' , 2 );


add_action( 'wp_enqueue_scripts', 'fais_customizer_enqueue_jQueryUICSS', 20 );
/**
 * Enqueue the styles and scripts used inside the Customizer.
 */
function fais_customizer_enqueue_jQueryUICSS() {

	$is_dev_mode = fais_spine_get_option( 'is_dev_mode', 'false' ); // yeah wil come base to case correctly, in rush ``/ lol
	$flex_dev = '';
	if ( 'true' === $is_dev_mode || is_development() ) {
		$flex_dev = 'dev/';
	}
	//will add picker and alt path shortly, just need it out.
	wp_enqueue_style( 'jQueryUICSS', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), '1.12.1' );
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

	$script_version = spine_get_script_version();
	$stylesheet_uri = get_stylesheet_directory_uri();

	$is_dev_mode = fais_spine_get_option( 'is_dev_mode', 'false' ); // yeah wil come base to case correctly, in rush ``/ lol
	$flex_dev = '';
	if ( 'true' === $is_dev_mode || is_development() ) {
		$flex_dev = 'dev/';
	}
	//will add picker and alt path shortly, just need it out.
	wp_enqueue_style( 'jQueryUICSS', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array( 'wsu-spine' ), '1.12.1' );
	wp_enqueue_style( 'fais_spine-theme',       get_template_directory_uri()   . '/style.css', array( 'jQueryUICSS' ), $script_version );

	if ( 'skeletal' !== spine_get_option( 'theme_style' ) ) {
		wp_enqueue_style( 'fais_spine-theme-extra', get_template_directory_uri()   . '/styles/' . spine_get_option( 'theme_style' ) . '.css', array( 'fais_spine-theme' ), $script_version );
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

	$coverage = fais_spine_get_option( 'flexwork_coverage', 'devices-lite' );

	$megamenu_show = fais_spine_get_option( 'megamenu_show', 'true' );

	/*wp_enqueue_style( 'flexwork-base', 'https://webcore.fais.wsu.edu/resources/flexwork/'.$flex_dev .'flexwork-'.$coverage.'.css', array( 'fais_spine-theme-print' ), spine_get_script_version() );
	wp_enqueue_style( 'flexwork-typography', 'https://webcore.fais.wsu.edu/resources/flexwork/'.$flex_dev .'extra/flexwork-typography.css', array( 'flexwork-base' ), spine_get_script_version() );
	wp_enqueue_style( 'flexwork-ui', 'https://webcore.fais.wsu.edu/resources/flexwork/'.$flex_dev .'extra/flexwork-ui.css', array( 'flexwork-typography' ), spine_get_script_version() );*/
	$vendor_path = '//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/';
	$flexed_path = '//webcore.fais.wsu.edu/resources/flexed/0.3.0/';
	$icons_path = $vendor_path . 'fontawesome/5.0.0-rc2/css/';
	$theme_path = '//webcore.fais.wsu.edu/resources/central_FnA_theme/'.$flex_dev; // default to over write

	wp_enqueue_style( 'vendors-bootstrap', $vendor_path . 'bootstrap/dist/css/bootstrap.min.css', array( 'fais_spine-theme-print' ), $script_version );
	wp_enqueue_style( 'vendors-bootstrap-dialog', $vendor_path . 'bootstrap3-dialog/dist/css/bootstrap-dialog.min.css', array( 'fais_spine-theme-print' ), $script_version );

	wp_enqueue_style( 'vendors-icons-solid', $icons_path . 'fontawesome-pro-solid.css', array( 'fais_spine-theme-print' ), $script_version );
	wp_enqueue_style( 'vendors-icons-light', $icons_path . 'fontawesome-pro-light.css', array( 'fais_spine-theme-print' ), $script_version );
	wp_enqueue_style( 'vendors-icons-regular', $icons_path . 'fontawesome-pro-regular.css', array( 'fais_spine-theme-print' ), $script_version );
	wp_enqueue_style( 'vendors-icons-core', $icons_path . 'fontawesome-pro-core.css', array( 'fais_spine-theme-print' ), $script_version );

	wp_enqueue_style( 'flexed-typography', $flexed_path . 'extra/flexed-typography.css', array( 'fais_spine-theme-print' ), $script_version );
	wp_enqueue_style( 'flexed-ui', $flexed_path . 'extra/flexed-ui.css', array( 'fais_spine-theme-print' ), $script_version );
	wp_enqueue_style( 'flexed-datatables', $flexed_path . 'extra/datatables/dataTables.flexed.css', array( 'fais_spine-theme-print' ), $script_version );
	wp_enqueue_style( 'flexed-custom', $flexed_path . 'custom.css', array( 'fais_spine-theme-print' ), $script_version );
	if ( 'true' === $is_dev_mode ) {//fal fa-exclamation-square
		if ( false !== strpos( $_SERVER['HTTP_HOST'],'wp.wsu.dev' ) ) {
			wp_enqueue_style( 'fais_spine-theme-child', $stylesheet_uri . '/' . $child_stylesheet, array( 'flexed-custom' ), $script_version );
		} else {
			wp_enqueue_style( 'fais_spine-theme-child',  $theme_path . '/wordpress/fin-ad-base-theme/style.css', array( 'flexed-custom' ), $script_version );
		}
	} else {
		wp_enqueue_style( 'fais_spine-theme-child', $stylesheet_uri . '/' . $child_stylesheet, array( 'flexed-custom' ), $script_version );
	}

	wp_enqueue_script( 'tether', $flexed_path . 'extra/tether.min.js', array( 'jquery' ), $script_version, true );
	wp_enqueue_script( 'drop', $flexed_path . 'extra/drop.min.js', array( 'tether' ), $script_version, true );

	if ( 'true' === $is_dev_mode ) {
		if ( false !== strpos( $_SERVER['HTTP_HOST'],'wp.wsu.dev' ) ) {
			wp_enqueue_script( 'child_controll', $stylesheet_uri . '/js/child_controll.js', array( 'jquery' ), $script_version, true );
		} else {
			wp_enqueue_script( 'child_controll', $theme_path . '/wordpress/fin-ad-base-theme/js/child_controll.js', array( 'jquery' ), $script_version, true );
		}
	} else {
		wp_enqueue_script( 'child_controll', $stylesheet_uri . '/js/child_controll.js', array( 'jquery' ), $script_version, true );
	}

	wp_enqueue_script( 'html2canvas', $stylesheet_uri . '/js/html2canvas.js', array( 'jquery' ), $script_version, true );

	$fais_site_object = array(
		'local' => array(
			'title' => get_bloginfo( 'name' ),
		),
		'parents' => [ [] ],
	);
	wp_localize_script( 'wsu-spine', 'fais_site_object', $fais_site_object );

	// for now we will hack this out but the goal is that when on local that you pull the menu system from the web
	$dev = '';
	if ( false !== strpos( $_SERVER['HTTP_HOST'],'wp.wsu.dev' ) ) {
		$dev = 'dev/';
	}

	$theme_path = '//webcore.fais.wsu.edu/resources/central_FnA_theme/'.$dev; // default to over write
	if ( 'true' === $megamenu_show ) {
		wp_enqueue_script( 'megamenu', $theme_path . '/megamenu/bootstrap.js', array( 'html2canvas' ), $script_version, true );
	}
}

function filter_ptags_on_images( $content ) {
	return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
}
add_filter( 'the_content', 'filter_ptags_on_images' );


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

	public function __construct() {
		add_action( 'init', array( $this, 'theme_menus' ), 10, 1 );
	}

	/**
	 * Setup the default navigation menus used in the Spine.
	 */
	public function theme_menus() {
		register_nav_menus(
			array(
				'fais_global'    => 'Global',
			)
		);

	// If the Global Menu doesn't exist, let's create it
		if ( ! is_nav_menu( 'global-menu' ) ) {

			// Create menu
			$menu_id = wp_create_nav_menu( 'Global Menu' );

			// Add menu items
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Contact Us' ),
				'menu-item-url' => home_url( '/contact-information/' ),
				'menu-item-status' => 'publish',
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Where are we Located' ),
				'menu-item-url' => home_url( '/located-at/' ),
				'menu-item-status' => 'publish',
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Give Feedback' ),
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
				'menu-item-classes' => ' inline-form ',
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Home Page' ),
				'menu-item-url' => 'https://baf.wsu.edu',
				'menu-item-attr-title' => 'F-n-A-Home-Page',
				'menu-item-status' => 'publish',
			) );

			// Lower case theme_name
			$theme = strtolower( str_replace( ' ', '_', wp_get_theme() ) );

			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations['fais_global'] = $menu_id;
			// Update the theme options
			set_theme_mod( 'nav_menu_locations', $locations );

		}
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
	// best would be to creat a css file on type save to better cache

	$background_url = fais_spine_get_option( 'background_url', false );
	$background_color = fais_spine_get_option( 'background_color', '#9bbdf5' );
	$secoundary_accent_color = fais_spine_get_option( 'secoundary_accent_color', '#1122a3' );
	$primary_accent_color = fais_spine_get_option( 'primary_accent_color', '#1122a3' );
	$header_color = fais_spine_get_option( 'header_color', '#981e32' );
	$header_text_color = fais_spine_get_option( 'header_text_color', '#FFF' );
	$jacket_background_url = fais_spine_get_option( 'jacket_background_url', false );

	?><style>
	<?php if ( false !== $background_url ) : ?>
		body:not(.has-background-image) { background-image:url('<?php esc_attr_e( $background_url ); ?>'); }
	<?php endif; ?>
	<?php if ( false !== $jacket_background_url ) : ?>
		#jacket { background: transparent url('<?php esc_attr_e( $jacket_background_url ) ?>') bottom center no-repeat;background-size: contain; }
	<?php endif; ?>
	body:not(.has-background-image) { background-color:<?php esc_attr_e( $background_color ); ?>; }
    .background-color { background-color:<?php esc_attr_e( $background_color ); ?>; }
    .primary-accent-bk {background-color:<?php esc_attr_e( $primary_accent_color ); ?>; }
    .secoundary-accent-bk {background-color:<?php esc_attr_e( $secoundary_accent_color ); ?>; }
    .primary-accent-text{color:<?php esc_attr_e( $primary_accent_color ); ?>; }
    .secoundary-accent-text{color:<?php esc_attr_e( $secoundary_accent_color ); ?>; }
    body div#border_top{background-color:<?php esc_attr_e( $primary_accent_color ); ?>; }
    body div#border_bottom{background-color:<?php esc_attr_e( $primary_accent_color ); ?>; }
	.main-header .header-group { background-color:<?php esc_attr_e( $header_color ); ?>; }
	.main-header .sup-header-default,
	.main-header .sup-header-default a,
	.main-header .sup-header-default span,
	.main-header .sub-header-default,
	.main-header .sub-header-default a,
	.main-header .sub-header-default span { color:<?php esc_attr_e( $header_text_color ); ?>; }
</style>
<?php
}

/*
function wsu_wp_post_search() {
		$term = strtolower( $_GET['term'] );
		$suggestions = array();

		$loop = new WP_Query( 's=' . $term );


//{"label":"Business Policies & Procedures Manual","value":"http://www.wsu.edu/~forms/manuals.html","searchKeywords":"","related":"false"}
		wp_reset_query();


    	$response = json_encode( $suggestions );
		//wp_send_json( $response );
		wp_send_json_success( $response );
    	//echo $response;
    	exit();

}
add_action( 'wp_ajax_wsu_wp_post_search', 'wsu_wp_post_search' );
add_action( 'wp_ajax_nopriv_wsu_wp_post_search', 'wsu_wp_post_search' );
*/




add_action( 'rest_api_init', 'myplugin_register_routes' );

/**
 * Register the /wp-json/myplugin/v1/foo route
 */
function myplugin_register_routes() {
	register_rest_route( 'wsuwp_search/v1', 'byterm/(?P<s>.*)', array(
		'methods'  => WP_REST_Server::READABLE,
		'callback' => 'wsuwp_search_serve_route',
	) );
}

/**
 * Generate results for the /wp-json/myplugin/v1/foo route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function wsuwp_search_serve_route( WP_REST_Request $request ) {
		$items = array(); //do a query, call another class, etc
		$data = array();
		$params = $request->get_params();
		$args = array( 's' => $params['name_startsWith'] );

		$loop = new WP_Query( $args );
		$suggestions = array();

		while ( $loop->have_posts() ) {
			$loop->the_post();
			$suggestion = array();
			$suggestion['label'] = get_the_title();
			$suggestion['value'] = get_permalink();
			$suggestion['searchKeywords'] = '';
			$suggestion['related'] = 'true';

			$suggestions[] = (object) $suggestion;
		}

		return new WP_REST_Response( $suggestions, 200 );
}
