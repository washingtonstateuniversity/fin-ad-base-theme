<?php
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
	<h3><?php esc_attr_e( 'Address' )?>:</h3>
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
			<h3><?php esc_attr_e( 'Verbal Location' )?>:</h3>
			<div><?php echo esc_attr( $verbal_location ); ?></div>

		<?php
	}
	?>
    <br/>
	<h3><?php esc_attr_e( 'Contact Methods' )?>:</h3>

	<?php
	$contact_telephone = spine_get_option( 'contact_telephone' );
	if ( ! empty( $contact_telephone ) && '' !== trim( $contact_telephone ) ) {
		?>
        <div class="flex-row full-width items-start">
			<h4 class="grid-part pad-no fifths-2"><?php esc_attr_e( 'Phone' )?>:</h4>
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
			<h4 class="grid-part pad-no fifths-2"><?php esc_attr_e( 'Email' )?>:</h4>
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
			<h4 class="grid-part pad-no fifths-2"><?php esc_attr_e( 'Point of Contact' )?>:</h4>
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

