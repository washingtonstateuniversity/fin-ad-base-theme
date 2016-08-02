<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_is_js_template, $ttfmake_slide_id;
$section_name = 'ttfmake-section';
if ( true === $ttfmake_is_js_template ) {
	$section_name .= '[{{{ parentID }}}][banner-slides][{{{ id }}}]';
} else {
	$section_name .= '[' . $ttfmake_section_data['data']['id'] . '][banner-slides][' . $ttfmake_slide_id . ']';
}

$content          = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['content'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['content'] : '';
$background_color = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['background-color'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['background-color'] : '';
$darken           = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['darken'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['darken'] : 0;
$image_id         = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['image-id'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['image-id'] : 0;
$alignment        = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['alignment'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['alignment'] : 'none';
$state            = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['state'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['state'] : 'open';
$slide_title      = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['slide-title'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['slide-title'] : '';
$slide_url        = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['slide-url'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['slide-url'] : false;
?>
<?php if ( true !== $ttfmake_is_js_template ) : ?>
<div class="ttfmake-banner-slide<?php if ( 'open' === $state ) { echo ' ttfmake-banner-slide-open'; } ?>" id="ttfmake-banner-slide-<?php esc_attr_e( $ttfmake_slide_id ); ?>" data-id="<?php esc_attr_e( $ttfmake_slide_id ); ?>" data-section-type="banner-slide">
	<?php endif; ?>
    <div class="ttfmake-banner-slide-header">
        <h3>
			<em><?php esc_html_e( 'Slide', 'make' ); ?></em>
        </h3>
		<a href="#" class="ttfmake-banner-slide-toggle" title="<?php esc_attr_e( 'Click to toggle', 'make' ); ?>">
            <div class="handlediv"></div>
        </a>
    </div>

    <div class="clear"></div>

    <div class="ttfmake-banner-slide-body">

        <div class="ttfmake-banner-slide-option-wrapper">
			<h4><?php esc_html_e( 'Background image', 'make' ); ?></h4>

            <p>
				<input id="<?php esc_attr_e( $section_name ); ?>[darken]" type="checkbox" name="<?php esc_attr_e( $section_name ); ?>[darken]" value="1"<?php checked( $darken ); ?> />
				<label for="<?php esc_attr_e( $section_name ); ?>[darken]">
					<?php esc_html_e( 'Darken to improve readability', 'make' ); ?>
                </label>
            </p>

            <div class="ttfmake-banner-slide-background-color-wrapper">
                <h4>
					<label for="<?php esc_attr_e( $section_name ); ?>[background-color]"><?php esc_html_e( 'Background color', 'make' ); ?></label>
                </h4>
				<input id="<?php esc_attr_e( $section_name ); ?>[background-color]" type="text" name="<?php esc_attr_e( $section_name ); ?>[background-color]" class="ttfmake-banner-slide-background-color" value="<?php esc_attr_e( maybe_hash_hex_color( $background_color ) ); ?>"/>
            </div>

            <div class="ttfmake-banner-slide-alignment-wrapper">
                <h4>
					<label for="<?php esc_attr_e( $section_name ); ?>[alignment]"><?php esc_html_e( 'Content position', 'make' ); ?></label>
                </h4>
				<select id="<?php esc_attr_e( $section_name ); ?>[alignment]" name="<?php esc_attr_e( $section_name ); ?>[alignment]">
					<option value="none"<?php selected( 'none', $alignment ); ?>><?php esc_html_e( 'None', 'make' ); ?></option>
					<option value="left"<?php selected( 'left', $alignment ); ?>><?php esc_html_e( 'Left', 'make' ); ?></option>
					<option value="right"<?php selected( 'right', $alignment ); ?>><?php esc_html_e( 'Right', 'make' ); ?></option>
                </select>
            </div>

        </div>

        <div class="ttfmake-banner-slide-background-image-wrapper">
			<?php
			ttfmake_get_builder_base()->add_uploader(
				$section_name,
				ttfmake_sanitize_image_id( $image_id ),
				array(
					'add'    => __( 'Set slide image', 'make' ),
					'remove' => __( 'Remove slide image', 'make' ),
					'title'  => __( 'Slide image', 'make' ),
					'button' => __( 'Use as slide image', 'make' ),
				)
			);
			?>
        </div>

        <div class="clear"></div>

        <h2>Slide Title:</h2>
		<input type="text" id="spine-slide-title" name="<?php esc_attr_e( $section_name ); ?>[spine_slide_title]" style="width: 100%;" value="<?php esc_attr_e( $slide_title ); ?>" />

        <h2>Slide URL:</h2>
		<input type="text" id="spine-slide-url" name="<?php esc_attr_e( $section_name ); ?>[spine_slide_url]" style="width: 100%;" value="<?php esc_attr_e( $slide_url ); ?>"/>

        <h2>
			<?php esc_html_e( 'Slide content overlay', 'make' ); ?>
        </h2>

		<?php
		$editor_settings = array(
			'tinymce'       => true,
			'quicktags'     => true,
			'textarea_name' => $section_name . '[content]',
		);

		if ( true === $ttfmake_is_js_template ) : ?>
			<?php ttfmake_get_builder_base()->wp_editor( '', 'ttfmakeeditorbannerslidetemp', $editor_settings ); ?>
		<?php else : ?>
			<?php ttfmake_get_builder_base()->wp_editor( $content, 'ttfmakeeditorbannerslide' . $ttfmake_slide_id, $editor_settings ); ?>
		<?php endif; ?>

        <a href="#" class="ttfmake-banner-slide-remove">
			<?php esc_html_e( 'Remove this slide', 'make' ); ?>
        </a>
    </div>
	<input type="hidden" class="ttfmake-banner-slide-state" name="<?php esc_attr_e( $section_name ); ?>[state]" value="<?php esc_attr_e( $state ); ?>" />
	<?php if ( true !== $ttfmake_is_js_template ) : ?>
</div>
<?php endif; ?>
