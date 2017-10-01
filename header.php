<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js no-svg lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js no-svg lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js no-svg lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EDGE">
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php echo esc_html( spine_get_title() ); ?></title>

	<!-- FAVICON -->
	<link rel="shortcut icon" href="https://repo.wsu.edu/spine/1/favicon.ico" />

	<!-- RESPOND -->
	<meta name="viewport" content="width=device-width, user-scalable=yes">

	<!-- DOCS -->
	<link type="text/plain" rel="author" href="https://repo.wsu.edu/spine/1/authors.txt" />
	<link type="text/html" rel="help" href="https://brand.wsu.edu/media/web" />

	<!-- SCRIPTS and STYLES -->
	<!-- Custom scripts and styles should be added with wp_enqueue_script() and wp_enqueue_style() -->


<!-- COMPATIBILITY -->
<!-- to get html5 in order -->
    <!--[if lt IE 9]><script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script><![endif]-->

<!-- polyfill for min/max-width CSS3 Media Queries -->
    <!--[if lt IE 9]><script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

<?php
	$is_dev_mode = fais_spine_get_option( 'is_dev_mode', 'false' );
	$flex_dev = '';
	if ( 'true' === $is_dev_mode || is_development() ) {
		$flex_dev = 'dev/';
	}
?>
<!-- polyfill for flex-box -->
    <!--[if lt IE 10]>
        <link href="https://webcore.fais.wsu.edu/resources/flexwork/<?php echo $flex_dev;?>extra/flexwork-ie9-.support.css" rel="stylesheet" type="text/css" />
    <![endif]-->

	<?php wp_head(); ?>
	<?php // this is temp ?>



	        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/PapaParse/4/papaparse.min.js"></script>
        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/moment/moment.js"></script>
        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/accounting/accounting.js"></script>

        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/bootstrap/dist/js/bootstrap.js"></script>
        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/datatables/1.10.13/media/js/jquery.dataTables.min.js"></script>

        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js"></script>
        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/typeahead.bundle/typeahead.bundle.js"></script>

        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/wsu.global.ns/0.1.0/vendors/hotkeys/0.2.0/jquery.hotkeys.js"></script>
        <script src="//webcore.fais.wsu.edu/resources/wsu.global.ns/1/js/mainline.js"></script>

        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/flexed/0.3.0/extra/tether.min.js"></script>
        <script type="text/javascript" src="//webcore.fais.wsu.edu/resources/flexed/0.3.0/extra/datatables/dataTables.flexed.js"></script>

	<noscript><style>#spine #spine-sitenav ul ul li { display: block !important; }</style></noscript>
</head>

<body <?php body_class(); ?>>

<?php
	if ( ( spine_get_option( 'spineless' ) == 'true' ) && is_front_page() ) {
		$spineless = ' spineless';
	} else {
		$spineless = '';
	}
?>

<?php get_template_part( 'parts/before-jacket' ); ?>
<div id="jacket" class="style-<?php echo esc_attr( spine_get_option( 'theme_style' ) ); ?> colors-<?php echo esc_attr( spine_get_option( 'secondary_colors' ) ); ?> spacing-<?php echo esc_attr( spine_get_option( 'theme_spacing' ) ); ?>">
<?php get_template_part( 'parts/before-binder' ); ?>
<div id="binder" class="<?php echo esc_attr( spine_get_option( 'grid_style' ) ); echo esc_attr( $spineless ); echo esc_attr( spine_get_option( 'large_format' ) ); echo esc_attr( spine_get_option( 'broken_binding' ) ); ?>">
<?php get_template_part( 'parts/before-main' );
