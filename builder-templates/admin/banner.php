<?php

spine_load_section_header();

global $ttfmake_section_data, $ttfmake_is_js_template;
$section_name  = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );
$title         = ( isset( $ttfmake_section_data['data']['title'] ) ) ? $ttfmake_section_data['data']['title'] : '';
$hide_arrows   = ( isset( $ttfmake_section_data['data']['hide-arrows'] ) ) ? $ttfmake_section_data['data']['hide-arrows'] : 0;
$hide_dots     = ( isset( $ttfmake_section_data['data']['hide-dots'] ) ) ? $ttfmake_section_data['data']['hide-dots'] : 0;
$autoplay      = ( isset( $ttfmake_section_data['data']['autoplay'] ) ) ? $ttfmake_section_data['data']['autoplay'] : 1;
$transition    = ( isset( $ttfmake_section_data['data']['transition'] ) ) ? $ttfmake_section_data['data']['transition'] : 'scrollHorz';
$delay         = ( isset( $ttfmake_section_data['data']['delay'] ) ) ? $ttfmake_section_data['data']['delay'] : 6000;
$height        = ( isset( $ttfmake_section_data['data']['height'] ) ) ? $ttfmake_section_data['data']['height'] : 600;
$responsive    = ( isset( $ttfmake_section_data['data']['responsive'] ) ) ? $ttfmake_section_data['data']['responsive'] : 'balanced';
$section_order = ( ! empty( $ttfmake_section_data['data']['banner-slide-order'] ) ) ? $ttfmake_section_data['data']['banner-slide-order'] : array();
?>

    <div class="ttfmake-add-slide-wrapper">
		<a href="#" class="button button-primary ttfmake-button-large button-large ttfmake-add-slide"><?php esc_html_e( 'Add New Slide', 'make' ); ?></a>
    </div>

    <div class="ttfmake-banner-slides">
        <div class="ttfmake-banner-slides-stage">
			<?php
			foreach ( $section_order as $key => $section_id  ) {
				if ( isset( $ttfmake_section_data['data']['banner-slides'][ $section_id ] ) ) {
					global $ttfmake_slide_id;
					$ttfmake_slide_id = $section_id;
					get_template_part( '/builder-templates/admin/banner', 'slide' );
				}
			}
			?>
        </div>
		<input type="hidden" value="<?php esc_attr_e( implode( ',', $section_order ) ); ?>" name="<?php esc_attr_e( $section_name ); ?>[banner-slide-order]" class="ttfmake-banner-slide-order" />
    </div>

    <div class="ttfmake-banner-options">
        <h2 class="ttfmake-large-title">
			<?php esc_html_e( 'Options', 'make' ); ?>
        </h2>

        <div class="ttfmake-titlediv">
            <div class="ttfmake-titlewrap">
				<input placeholder="<?php esc_attr_e( 'Enter title here', 'make' ); ?>" type="text" name="<?php esc_attr_e( $section_name ); ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php esc_attr_e( htmlspecialchars( $title ) ); ?>" autocomplete="off" />
            </div>
        </div>

        <div class="ttfmake-banner-options-container">
            <h4 class="ttfmake-banner-options-title">
				<?php esc_html_e( 'Slideshow display', 'make' ); ?>
            </h4>

            <p>
				<input id="<?php esc_attr_e( $section_name ); ?>[hide-arrows]" type="checkbox" name="<?php esc_attr_e( $section_name ); ?>[hide-arrows]" value="1"<?php checked( $hide_arrows ); ?> />
				<label for="<?php esc_attr_e( $section_name ); ?>[hide-arrows]">
					<?php esc_html_e( 'Hide navigation arrows', 'make' ); ?>
                </label>
            </p>

            <p>
				<input id="<?php esc_attr_e( $section_name ); ?>[hide-dots]" type="checkbox" name="<?php esc_attr_e( $section_name ); ?>[hide-dots]" value="1"<?php checked( $hide_dots ); ?> />
				<label for="<?php esc_attr_e( $section_name ); ?>[hide-dots]">
					<?php esc_html_e( 'Hide navigation dots', 'make' ); ?>
                </label>
            </p>

            <p>
				<input id="<?php esc_attr_e( $section_name ); ?>[autoplay]" type="checkbox" name="<?php esc_attr_e( $section_name ); ?>[autoplay]" value="1"<?php checked( $autoplay ); ?> />
				<label for="<?php esc_attr_e( $section_name ); ?>[autoplay]">
					<?php esc_html_e( 'Autoplay slideshow', 'make' ); ?>
                </label>
            </p>
        </div>

        <div class="ttfmake-banner-options-container">
            <h4 class="ttfmake-banner-options-title">
				<?php esc_html_e( 'Time between slides (in ms)', 'make' ); ?>
            </h4>
			<input id="<?php esc_attr_e( $section_name ); ?>[delay]" class="code" type="number" name="<?php esc_attr_e( $section_name ); ?>[delay]" value="<?php echo absint( $delay ); ?>" />

            <h4>
				<?php esc_html_e( 'Transition effect', 'make' ); ?>
            </h4>
			<select id="<?php esc_attr_e( $section_name ); ?>[transition]" name="<?php esc_attr_e( $section_name ); ?>[transition]">
				<option value="scrollHorz"<?php selected( 'scrollHorz', $transition ); ?>><?php esc_html_e( 'Slide horizontal', 'make' ); ?></option>
				<option value="fade"<?php selected( 'fade', $transition ); ?>><?php esc_html_e( 'Fade', 'make' ); ?></option>
				<option value="none"<?php selected( 'none', $transition ); ?>><?php esc_html_e( 'None', 'transition effect', 'make' ); ?></option>
            </select>
        </div>

        <div class="ttfmake-banner-options-container">
            <h4 class="ttfmake-banner-options-title">
				<?php esc_html_e( 'Section height (px)', 'make' ); ?>
            </h4>
			<input id="<?php esc_attr_e( $section_name ); ?>[height]" class="code" type="number" name="<?php esc_attr_e( $section_name ); ?>[height]" value="<?php echo absint( $height ); ?>" />

            <h4>
				<?php esc_html_e( 'Responsive behavior', 'make' ); ?>
            </h4>
			<select id="<?php esc_attr_e( $section_name ); ?>[responsive]" name="<?php esc_attr_e( $section_name ); ?>[responsive]">
				<option value="balanced"<?php selected( 'balanced', $responsive ); ?>><?php esc_html_e( 'Default', 'make' ); ?></option>
				<option value="aspect"<?php selected( 'aspect', $responsive ); ?>><?php esc_html_e( 'Maintain slider aspect ratio', 'make' ); ?></option>
            </select>
			<p class="help-text howto"><?php esc_html_e( 'Choose how the banner will respond to varying screen widths.', 'make' ); ?></p>
        </div>

        <div class="clear"></div>

    </div>
    <div class="spine-builder-overlay">
        <div class="spine-builder-overlay-wrapper">
            <div class="spine-builder-overlay-header">
                <div class="spine-builder-overlay-title">Configure Section</div>
                <div class="spine-builder-overlay-close">Done</div>
            </div>
            <div class="spine-builder-overlay-body">
				<?php
				fais_spine_output_builder_section_flextree( $section_name, $ttfmake_section_data );
				spine_output_builder_section_classes( $section_name, $ttfmake_section_data );
				spine_output_builder_section_label( $section_name, $ttfmake_section_data );
				spine_output_builder_column_classes( $section_name, $ttfmake_section_data );
				spine_output_builder_section_background( $section_name, $ttfmake_section_data );

				do_action( 'spine_output_builder_section', $section_name, $ttfmake_section_data, 'banner' );
				?>
            </div>
        </div>
    </div>
	<input type="hidden" class="ttfmake-section-state" name="<?php esc_attr_e( $section_name ); ?>[state]" value="<?php
	if ( isset( $ttfmake_section_data['data']['state'] ) ) {
		esc_attr_e( $ttfmake_section_data['data']['state'] );
	} else {
		echo 'open';
	} ?>" />
<?php
spine_load_section_footer();
