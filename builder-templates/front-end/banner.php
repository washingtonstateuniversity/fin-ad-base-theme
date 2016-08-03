<?php
global $ttfmake_section_data, $ttfmake_sections;
$section_wrapper_html = '';
// Sections can have ids (provided by outside forces other than this theme) and classes.
$section_classes         = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';

// If a child theme or plugin has declared a section ID, we handle that.
// This may be supported in the parent theme one day.
$section_id  = ( isset( $ttfmake_section_data['section-id'] ) ) ? $ttfmake_section_data['section-id'] : '';

$column_classes = ( isset( $ttfmake_section_data['column-classes'] ) ) ? $ttfmake_section_data['column-classes'] : '';

$banner_slides = ttfmake_builder_get_banner_array( $ttfmake_section_data );
$is_slider = ( count( $banner_slides ) > 1 ) ? true : false;

$responsive = ( isset( $ttfmake_section_data['responsive'] ) ) ? $ttfmake_section_data['responsive'] : 'balanced';
$slider_height = absint( $ttfmake_section_data['height'] );
if ( 0 === $slider_height ) {
	$slider_height = 600;
}
$slider_ratio = ( $slider_height / 960 ) * 100;
?>

<?php
if ( isset( $ttfmake_section_data['background-img'] ) && ! empty( $ttfmake_section_data['background-img'] ) ) {
	$section_background = $ttfmake_section_data['background-img'];
} else {
	$section_background = false;
}

if ( isset( $ttfmake_section_data['background-mobile-img'] ) && ! empty( $ttfmake_section_data['background-mobile-img'] ) ) {
	$section_mobile_background = $ttfmake_section_data['background-mobile-img'];
} elseif ( $section_background ) {
	$section_mobile_background = $section_background;
} else {
	$section_mobile_background = false;
}

$section_background_class = '';
$section_background_data = '';
$section_background_mobile_data = '';
if ( $section_background || $section_mobile_background ) {


	$section_background_class = ' builder-section section-wrapper section-wrapper-has-background '. ttfmake_builder_get_banner_class( $ttfmake_section_data, $ttfmake_sections ).' ';

	if ( $section_background ) {
		$section_background_data = ' data-background="' . esc_url( $section_background ) . '"';
	}

	if ( $section_mobile_background ) {
		$section_background_mobile_data = esc_url( $section_mobile_background );
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

<section id="<?php esc_attr_e( $section_id ); ?>" data-background="<?php esc_attr_e( $section_background_data ); ?>" data-background-mobile="<?php esc_attr_e( $section_background_mobile_data ); ?>" class="banner-section full-width  <?php esc_attr_e( $section_background_class ); ?>">


	<div class="banner-column <?php esc_attr_e( $column_classes ); ?>">
    <?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
            <header>
				<h2><?php esc_attr_e( apply_filters( 'the_title', $ttfmake_section_data['title'] ) ); ?></h2>
            </header>
    <?php endif; ?>

		<div class="builder-section-content <?php esc_attr_e( $is_slider? ' cycle-slideshow' : '' ); ?>" <?php echo ( ( $is_slider ) ? ttfmake_builder_get_banner_slider_atts( $ttfmake_section_data ) : '' ); ?>>

		<style type="text/css">
			<?php
			// Maintain aspect ratio
			if ( 'aspect' === $responsive ) : ?>

			#builder-section-<?php esc_attr_e( $ttfmake_section_data['id'] ); ?> .builder-banner-slide {
			padding-bottom: <?php esc_attr_e( $slider_ratio ); ?>%;
			}
			<?php
			// Balanced
			else : ?>

			#builder-section-<?php esc_attr_e( $ttfmake_section_data['id'] ); ?> .builder-banner-slide {
				padding-bottom: <?php esc_attr_e( $slider_height ); ?>px;
			}
			@media screen and (min-width: 600px) and (max-width: 960px) {
				#builder-section-<?php esc_attr_e( $ttfmake_section_data['id'] ); ?> .builder-banner-slide {
					padding-bottom: <?php esc_attr_e( $slider_ratio ); ?>%;
				}
			}
			<?php endif; ?>
		</style>
    <?php if ( ! empty( $banner_slides ) ) : $i = 0; foreach ( $banner_slides as $slide ) : ?>
				<div class="builder-banner-slide<?php esc_attr_e( ttfmake_builder_banner_slide_class( $slide ) ); ?> <?php esc_attr_e( ( 0 == $i++ ) ? ' first-slide' : '' ); ?>" style="<?php esc_attr_e( ttfmake_builder_banner_slide_style( $slide, $ttfmake_section_data ) ); ?>">
        <?php if ( ! empty( $slide['slide-url'] ) ) : ?><a href="<?php echo esc_url( $slide['slide-url'] ); ?>"><?php
		endif; ?>
                    <div class="builder-banner-content">
        <?php if ( ! empty( $slide['slide-title'] ) ) : ?>
                        <div class="builder-banner-inner-title">
							<span class="builder-banner-slide-title"><?php esc_html_e( $slide['slide-title'] ); ?></span>
                        </div>
        <?php endif; ?>
                        <div class="builder-banner-inner-content">
        <?php ttfmake_get_builder_save()->the_builder_content( $slide['content'] ); ?>
                        </div>
                    </div>
        <?php if ( 0 !== absint( $slide['darken'] ) ) : ?>
                        <div class="builder-banner-overlay"></div>
        <?php endif; ?>
        <?php if ( ! empty( $slide['slide-url'] ) ) : ?></a><?php
		endif; ?>
                </div>
    <?php endforeach;
	endif; ?>
    <?php if ( $is_slider && false === (bool) $ttfmake_section_data['hide-dots'] ) : ?>
                <div class="cycle-pager"></div>
    <?php endif; ?>
    <?php if ( $is_slider && false === (bool) $ttfmake_section_data['hide-arrows'] ) : ?>
                <div class="cycle-prev"></div>
                <div class="cycle-next"></div>
    <?php endif; ?>
        </div>
    </div>
</section>
<?php
