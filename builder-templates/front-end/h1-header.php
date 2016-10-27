<?php
global $ttfmake_section_data;
$section_wrapper_html = '';
// Sections can have ids (provided by outside forces other than this theme) and classes.

$section_classes = 'full-width'
if ( isset( $ttfmake_section_data['section-classes'] ) ) {
	$section_classes .= ' '.$ttfmake_section_data['section-classes'];
}

$section_attr = '';
if ( isset( $ttfmake_section_data['section-attr'] ) ) {
	$section_attr = $ttfmake_section_data['section-attr'];
}

// If a child theme or plugin has declared a section ID, we handle that.
// This may be supported in the parent theme one day.
$section_id  = ( isset( $ttfmake_section_data['section-id'] ) ) ? $ttfmake_section_data['section-id'] : '';

$column_classes = ( isset( $ttfmake_section_data['column-classes'] ) ) ? $ttfmake_section_data['column-classes'] : '';
//$column_classes .= ' '.( isset( $ttfmake_section_data['column-type'] ) ) ? $ttfmake_section_data['column-type'] : '';

$header_level = 'h1';
if ( isset( $ttfmake_section_data['header-level'] ) && in_array( $ttfmake_section_data['header-level'], array( 'h1', 'h2', 'h3', 'h4' ), true ) ) {
	$header_level = $ttfmake_section_data['header-level'];
}

$column_background = '';
if ( isset( $ttfmake_section_data['column-background-image'] ) && ! empty( $ttfmake_section_data['column-background-image'] ) ) {
	$column_background = "background-image:url('" . esc_url( $ttfmake_section_data['column-background-image'] ) ."');";
}

$section_background = false;
if ( isset( $ttfmake_section_data['background-img'] ) && ! empty( $ttfmake_section_data['background-img'] ) ) {
	$section_background = $ttfmake_section_data['background-img'];
}

$section_mobile_background = false;
if ( isset( $ttfmake_section_data['background-mobile-img'] ) && ! empty( $ttfmake_section_data['background-mobile-img'] ) ) {
	$section_mobile_background = $ttfmake_section_data['background-mobile-img'];
} elseif ( $section_background ) {
	$section_mobile_background = $section_background;
}

if ( $section_background || $section_mobile_background ) {
	$section_classes .= ' section-wrapper-has-background';

	if ( '' !== $section_id ) {
		$section_wrapper_html .= ' id="' . esc_attr( $section_id ) . '"';
	}
	if ( $section_background ) {
		$section_wrapper_html .= ' data-background="' . esc_url( $section_background ) . '"';
	}

	if ( $section_mobile_background ) {
		$section_wrapper_html .= ' data-background-mobile="' . esc_url( $section_mobile_background ) . '"';
	}

	// Reset section_id so that the default is built for the section.
	$section_id = '';
}

// If a section ID is not available for use, we build a default ID.
if ( '' === $section_id ) {
	$section_id = 'builder-section-' . esc_attr( $ttfmake_section_data['id'] );
} else {
	$section_id = sanitize_key( $section_id );
}
?>

<section id="<?php esc_attr_e( $section_id ); ?>" <?php esc_attr_e( stripslashes( $section_attr ) ); ?> <?php esc_attr_e( $section_wrapper_html ); ?> class="<?php echo esc_attr( $section_classes ); ?>">
	<div <?php if ( '' !== $column_background ) : echo ' style="' . esc_attr( $column_background ). '" '; endif; ?> <?php if ( '' !== $column_classes ) : echo ' class="' . esc_attr( $column_classes ). '" '; endif; ?>>
    <?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
		<<?php esc_attr_e( $header_level ); ?>><?php esc_attr_e( apply_filters( 'the_title', $ttfmake_section_data['title'] ) ); ?></<?php esc_attr_e( $header_level ); ?>>
    <?php endif; ?>
	</div>
</section>
<?php
