<?php

spine_load_section_header();

global $ttfmake_section_data, $ttfmake_is_js_template;

if ( in_array( $ttfmake_section_data['section']['id'], array( 'faiswsuwphalves', 'faiswsuwpsidebarright', 'faiswsuwpsidebarleft' ), true ) ) {
	$wsuwp_range = 2;
} elseif ( 'faiswsuwpthirds' === $ttfmake_section_data['section']['id'] ) {
	$wsuwp_range = 3;
} elseif ( 'faiswsuwpquarters' === $ttfmake_section_data['section']['id'] ) {
	$wsuwp_range = 4;
} else {
	$wsuwp_range = 1;
}

if ( 'faiswsuwphalves' === $ttfmake_section_data['section']['id'] ) {
	$column_size_defaults = [ 1 => 'fourths-2',2 => 'fourths-2' ];
} elseif ( 'faiswsuwpsidebarright' === $ttfmake_section_data['section']['id'] ) {
	$column_size_defaults = [ 1 => 'fifths-3',2 => 'fifths-2' ];
} elseif ( 'faiswsuwpsidebarleft' === $ttfmake_section_data['section']['id'] ) {
	$column_size_defaults = [ 1 => 'fifths-2',2 => 'fifths-3' ];
} elseif ( 'faiswsuwpthirds' === $ttfmake_section_data['section']['id'] ) {
	$column_size_defaults = [ 1 => 'thirds-1',2 => 'thirds-1',3 => 'thirds-1' ];
} elseif ( 'faiswsuwpquarters' === $ttfmake_section_data['section']['id'] ) {
	$column_size_defaults = [ 1 => 'fourths-1',2 => 'fourths-1',3 => 'fourths-1',4 => 'fourths-1' ];
} else {
	$column_size_defaults = [ 1 => 'fourths-4' ];
}


$section_flextype = ( isset( $ttfmake_section_data['data']['section-flextype'] ) ) ? $ttfmake_section_data['data']['section-flextype'] : 'flex-row';

// We didn't always treat single as a column layout. Provide a shim for the old data structure.
if ( 'faiswsuwpsingle' === $ttfmake_section_data['section']['id'] ) {
	if ( ! empty( $ttfmake_section_data['data']['content'] ) ) {
		$ttfmake_section_data['data']['columns'][1]['content'] = $ttfmake_section_data['data']['content'];
	}

	if ( ! empty( $ttfmake_section_data['data']['title'] ) ) {
		$ttfmake_section_data['data']['columns'][1]['title'] = $ttfmake_section_data['data']['title'];
	}
}

$section_name   = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );
$section_order  = ( ! empty( $ttfmake_section_data['data']['columns-order'] ) ) ? $ttfmake_section_data['data']['columns-order'] : range( 1, $wsuwp_range );

?>
<style>
.wsuwp-spine-halves-stage {
    border: 1px solid #a8a8a8;
    position:relative;
}
.wsuwp-spine-halves-stage:before {
    content: "Section area";
    position: absolute;
    top: -20px;
    left: calc( 50% - 44px);
    background: #a8a8a8;
    color: #fff;
    padding: 5px 10px;
}

.wsuwp-spine-builder-column {
    border: 1px solid #a8a8a8;
    position:relative;
}
.wsuwp-spine-builder-column:before {
    content: "Column area";
    position: absolute;
    top: -20px;
    left: calc( 50% - 44px);
    background: #a8a8a8;
    color: #fff;
    padding: 5px 10px;
}




</style>
	<div class="wsuwp-spine-halves-stage <?php echo $section_flextype ?>">
    <?php $j = 1; foreach ( $section_order as $key => $i ) : ?>
    <?php
	$column_name = $section_name . '[columns][' . $i . ']';
	$title    = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['title'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['title'] : '';
	$content  = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['content'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['content'] : '';
	$visible  = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['toggle'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['toggle'] : 'visible';

	$column_flextype  = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['column_flextype'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['column_flextype'] : $column_size_defaults[ $i ];

	if ( ! in_array( $visible, array( 'visible', 'invisible' ), true ) ) {
		$visible = 'visible';
	}

	if ( 'invisible' === $visible ) {
		$column_style = 'style="display: none;"';
		$toggle_class = 'wsuwp-toggle-closed';
	} else {
		$column_style = '';
		$toggle_class = '';
	}
	?>
			<div class="wsuwp-spine-builder-column wsuwp-spine-builder-column-position-<?php echo $j; ?> <?php echo $column_flextype?>" data-id="<?php echo $i; ?>">
				<div title="<?php esc_attr_e( 'Drag-and-drop this column into place', 'ttfmake' ); ?>" class="ttfmake-sortable-handle">
					<div class="sortable-background">
						<a href="#" class="spine-builder-column-configure"><span>Configure this column</span></a>
						<a href="#" class="wsuwp-column-toggle" title="Click to toggle"><div class="handlediv <?php echo $toggle_class; ?>"></div></a>
						<div class="wsuwp-builder-column-title">Column <?php echo $j; ?> of <?php echo $wsuwp_range; ?></div>
					</div>
				</div>

				<div class="ttfmake-titlediv">
					<div class="ttfmake-titlewrap">
						<input placeholder="<?php esc_attr_e( 'Enter title here', 'ttfmake' ); ?>" type="text" name="<?php echo $column_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php echo esc_attr( htmlspecialchars( $title ) ); ?>" autocomplete="off" />
					</div>
				</div>

				<div class="wsuwp-column-content" <?php echo $column_style; ?>>
					<input type="hidden" class="wsuwp-column-visible" name="<?php echo $column_name; ?>[toggle]" value="<?php echo $visible; ?>" />
				<?php
				$editor_settings = array(
					'tinymce'       => true,
					'quicktags'     => true,
					'editor_height' => 345,
					'textarea_name' => $column_name . '[content]',
				);

				if ( true === $ttfmake_is_js_template ) : ?>
        <?php ttfmake_get_builder_base()->wp_editor( '', 'ttfmakeeditortextcolumn' . $i . 'temp', $editor_settings ); ?>
				<?php else : ?>
        <?php ttfmake_get_builder_base()->wp_editor( $content, 'ttfmakeeditortext' . $ttfmake_section_data['data']['id'] . $i, $editor_settings ); ?>
				<?php endif; ?>
				</div>
				<div class="spine-builder-column-overlay">
					<div class="spine-builder-column-overlay-wrapper">
						<div class="spine-builder-overlay-header">
							<div class="spine-builder-overlay-title">Configure Column</div>
							<div class="spine-builder-column-overlay-close">Done</div>
						</div>
						<div class="spine-builder-overlay-body">
        <?php
		fais_spine_output_builder_column_type( $column_name, $ttfmake_section_data, $j );
		fais_spine_output_builder_column_classes( $column_name, $ttfmake_section_data, $j );
		?>
						</div>
					</div>
				</div>
			</div>
    <?php
	$j++;
	endforeach; ?>
	</div>

	<div class="clear"></div>
	<div class="spine-builder-overlay">
		<div class="spine-builder-overlay-wrapper">
			<div class="spine-builder-overlay-header">
				<div class="spine-builder-overlay-title">Configure Section</div>
				<div class="spine-builder-overlay-close">Done</div>
			</div>
			<div class="spine-builder-overlay-body">
				<?php
				fais_spine_output_builder_section_flextree( $section_name, $ttfmake_section_data );
				fais_spine_output_builder_section_layout( $section_name, $ttfmake_section_data );
				fais_spine_output_builder_section_classes( $section_name, $ttfmake_section_data );

				fais_spine_output_builder_section_wrapper( $section_name, $ttfmake_section_data );
				fais_spine_output_builder_section_label( $section_name, $ttfmake_section_data );
				fais_spine_output_builder_section_background( $section_name, $ttfmake_section_data );

				do_action( 'spine_output_builder_section', $section_name, $ttfmake_section_data, 'columns' );
				?>
			</div>
		</div>
	</div>
	<input type="hidden" value="<?php echo esc_attr( implode( ',', $section_order ) ); ?>" name="<?php echo $section_name; ?>[columns-order]" class="wsuwp-spine-builder-columns-order" />
	<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php
	if ( isset( $ttfmake_section_data['data']['state'] ) ) {
		echo esc_attr( $ttfmake_section_data['data']['state'] );
	} else {
		echo 'open';
	} ?>" />
<?php
spine_load_section_footer();
