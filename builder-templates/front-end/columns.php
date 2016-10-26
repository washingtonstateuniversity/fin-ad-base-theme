<?php
global $ttfmake_section_data, $ttfmake_sections;

$active = 'true';
if ( isset( $ttfmake_section_data['section-active'] ) && 'false' === $ttfmake_section_data['section-active'] ) {
	$active = 'false';
}

if ( 'false' === $active ) {
	?><!-- hidden section (<?php echo $ttfmake_section_data['section-type']; ?>) --><?php

} else {


// Default to sidebar right if a section type has not been specified.
$section_type = ( isset( $ttfmake_section_data['section-type'] ) ) ? $ttfmake_section_data['section-type'] : 'faiswsuwpsidebarright';


$fw_column_width_default = fais_spine_get_option( 'fw_column_width_default', '' );

$column_size_defaults = [ 1 => $fw_column_width_default ];

if ( 'faiswsuwphalves' === $section_type ) {
	$column_size_defaults = [ 1 => 'fourths-2', 2 => ' fourths-2' ];
} elseif ( 'faiswsuwpsidebarright' === $section_type ) {
	$column_size_defaults = [ 1 => 'fifths-3', 2 => 'fifths-2' ];
} elseif ( 'faiswsuwpsidebarleft' === $section_type ) {
	$column_size_defaults = [ 1 => 'fifths-2', 2 => 'fifths-3' ];
} elseif ( 'faiswsuwpthirds' === $section_type ) {
	$column_size_defaults = [ 1 => 'thirds-1',2 => 'thirds-1', 3 => 'thirds-1' ];
} elseif ( 'faiswsuwpquarters' === $section_type ) {
	$column_size_defaults = [ 1 => 'fourths-1', 2 => 'fourths-1', 3 => 'fourths-1', 4 => 'fourths-1' ];
}

// Provide a list matching the number of columns to the selected section type.
$section_type_columns = array(
	'faiswsuwpsidebarright' => 2,
	'faiswsuwpsidebarleft'  => 2,
	'faiswsuwpthirds'       => 3,
	'faiswsuwphalves'       => 2,
	'faiswsuwpquarters'     => 4,
	'faiswsuwpsingle'       => 1,
);

// Retrieve data for the column being output.
$data_columns = fais_spine_get_column_data( $ttfmake_section_data, $section_type_columns[ $section_type ] );

// Sections can have ids (provided by outside forces other than this theme) and classes.

$section_attr         = ( isset( $ttfmake_section_data['section-attr'] ) ) ? $ttfmake_section_data['section-attr'] : '';
$section_classes         = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_flextype = ( isset( $ttfmake_section_data['section-flextype'] ) ) ? $ttfmake_section_data['section-flextype'] : '';

$section_classes = $section_flextype . ' ' . $section_classes;

// make sure we have the var ahead of .=
$section_wrapper_html = '';


// If a child theme or plugin has declared a section ID, we handle that.
// This may be supported in the parent theme one day.
$section_id  = ( isset( $ttfmake_section_data['section-id'] ) ) ? $ttfmake_section_data['section-id'] : '';

// If a background image has been assigned to the section, capture it for use.
if ( isset( $ttfmake_section_data['background-img'] ) && ! empty( $ttfmake_section_data['background-img'] ) ) {
	$section_background = $ttfmake_section_data['background-img'];
} else {
	$section_background = false;
}

// If a mobile background image has been assigned to the section, capture it. Fallback to the section background.
if ( isset( $ttfmake_section_data['background-mobile-img'] ) && ! empty( $ttfmake_section_data['background-mobile-img'] ) ) {
	$section_mobile_background = $ttfmake_section_data['background-mobile-img'];
} elseif ( $section_background ) {
	$section_mobile_background = $section_background;
} else {
	$section_mobile_background = false;
}


// If a background image has been assigned, a wrapper is required.
if ( $section_background || $section_mobile_background ) {

	$section_classes .= ' section-wrapper-has-background';

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
	<section id="<?php esc_attr_e( $section_id ); ?>" <?php esc_attr_e( $section_wrapper_html ); ?> data-type="<?php esc_attr_e( $section_type ); ?>" class="column-section <?php esc_attr_e( trim( $section_classes ) ); ?>" <?php esc_attr_e( $section_attr ); ?>>
    <?php

	if ( ! empty( $data_columns ) ) {

		$count = 1;
		foreach ( $data_columns as $column ) {
			if ( isset( $column['column-background-image'] ) && ! empty( $column['column-background-image'] ) ) {
				$column_background = "background-image:url('" . esc_url( $column['column-background-image'] ) ."');";
			} else {
				$column_background = '';
			}
			?>
         <div style="<?php esc_attr_e( $column_background ); ?>"
		 		<?php if ( "" !== $column_background ) : esc_attr_e( "style='".$column_background."'" ) ; endif; ?>
		class="<?php esc_attr_e( $column['column-type'] ); ?> <?php $count++; ?> <?php if ( isset( $column['column-classes'] ) ) : echo esc_attr( $column['column-classes'] );
		endif; ?>">

        <?php if ( '' !== $column['title'] ) : ?>
        <?php $header_level = in_array( $column['header-level'], array( 'h2', 'h3', 'h4' ), true ) ? $column['header-level'] : 'h2'; ?>
						<header>
							<<?php esc_attr_e( $header_level ); ?>><?php esc_attr_e( apply_filters( 'the_title', $column['title'] ) ); ?></<?php esc_attr_e( $header_level ); ?>>
						</header>
        <?php endif; ?>

        <?php if ( '' !== $column['content'] ) : ?>
        <?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
        <?php endif; ?>

       </div>
        <?php
		}
	}
	?>
	</section>
<?php

}
