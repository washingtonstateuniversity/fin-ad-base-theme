<?php
global $ttfmake_section_data, $ttfmake_is_js_template;
?>
<style>
.ttfmake-section.active-false{
    position: relative;
    opacity: 0.6;
    border: .1rem dashed red;
}
.ttfmake-section.active-false:before {
    content: "Inactive!";
    position: absolute;
    top: 0.9rem;
    left: -.1rem;
    background: rgba(216, 17, 17, 0.93);
    padding: 5px 10px;
    font-size: 0.8rem;
    z-index: 9;
    border: 1px solid #560a0a;
    border-left: none;
    color: #e2e2e2;
    font-weight: 600;
}
.wsuwp-spine-column-stage {
    border: 1px solid #a8a8a8;
    position:relative;
}
.wsuwp-spine-column-stage:before {
	content: "Section area";
    position: absolute;
    top: -28px;
    left: calc( 50% - 40px);
    background: rgba(168, 168, 168, 0.45);
    color: #fff;
    padding: 5px 10px;
    font-size: 0.8rem;
}

.wsuwp-spine-builder-column {
    border: 1px solid #a8a8a8;
    position:relative;
}
.wsuwp-spine-builder-column:before {
    content: "Column area";
	position: absolute;
    top: .5rem;
    left: -.1rem;
    background: rgb(238, 238, 238);
    padding: 5px 10px;
    font-size: 0.8rem;
    z-index: 9;
    border: 1px solid #d9d9d9;
    border-right: none;
    color: #494949;
}


.wsuwp-spine-builder-column.pad-no:before {
    top: 0;
}
.wsuwp-spine-builder-column.pad-hair:before {
    top: .2rem;
}
.wsuwp-spine-builder-column.pad-tight:before {
    top: .8rem;
}
.wsuwp-spine-builder-column.pad-airy:before {
    top: 1.6rem;
}

</style>

<?php if ( ! isset( $ttfmake_is_js_template ) || true !== $ttfmake_is_js_template ) : ?>

<?php $active = isset( $ttfmake_section_data['data']['section-active'] ) ? $ttfmake_section_data['data']['section-active'] : 'true' ?>

<div class="ttfmake-section <?php echo 'active-'.$active; ?> <?php
if ( isset( $ttfmake_section_data['data']['state'] ) && 'open' === $ttfmake_section_data['data']['state'] ) { echo 'ttfmake-section-open'; } ?> ttfmake-section-<?php esc_attr_e( $ttfmake_section_data['section']['id'] ); ?>" id="<?php esc_attr_e( 'ttfmake-section-' . $ttfmake_section_data['data']['id'] ); ?>" data-id="<?php esc_attr_e( $ttfmake_section_data['data']['id'] ); ?>" data-section-type="<?php esc_attr_e( $ttfmake_section_data['section']['id'] ); ?>">


	<?php endif; ?>
	<?php
	/**
	 * Execute code before the section header is displayed.
	 *
	 * @since 1.2.3.
	 */
	do_action( 'make_before_section_header' );
	?>
	<div class="ttfmake-section-header">
		<?php $header_title = ( isset( $ttfmake_section_data['data']['label'] ) ) ? $ttfmake_section_data['data']['label'] : ''; ?>
		<h3>
			<span class="ttfmake-section-header-title"><?php esc_html_e( $header_title ); ?></span><em><?php esc_html_e( $ttfmake_section_data['section']['label'] ); ?></em>
		</h3>
		<?php
		/**
		 * Filter the builder section footer links.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $links    The list of footer links.
		 */
		$links = apply_filters( 'make_builder_section_footer_links', array(
			50 => array(
				'href' => '#',
				'class' => 'spine-builder-section-configure',
				'label' => __( 'Configure this section', 'spine' ),
			),
			100 => array(
				'href'  => '#',
				'class' => 'ttfmake-section-remove',
				'label' => __( 'Remove this section', 'make' ),
			),
		) );
		ksort( $links );
		?>
		<?php $i = 1; foreach ( $links as $link ) : ?>
			<?php
			$href  = ( isset( $link['href'] ) ) ? '' . esc_url( $link['href'] ) : '';
			$id    = ( isset( $link['id'] ) ) ? ' ' . esc_attr( $link['id'] ) : '';
			$label = ( isset( $link['label'] ) ) ? esc_html( $link['label'] ) : '';

			// Set up the class value with a base class
			$class_base = ' ttfmake-builder-section-footer-link';
			$class      = ( isset( $link['class'] ) ) ? $class_base . ' ' . esc_attr( $link['class'] ) . ' ' : ' ';
			?>
		<a href="<?php esc_attr_e( $href ); ?>" id="<?php esc_attr_e( $id ); ?>"  class="<?php esc_attr_e( $class ); ?>" >
			<span><?php esc_html_e( $label ); ?></span>
			</a>
		<?php $i++; endforeach; ?>

		<a href="#" class="ttfmake-section-toggle" title="<?php esc_attr_e( 'Click to toggle', 'make' ); ?>">
			<div class="handlediv"></div>
		</a>
	</div>
	<div class="clear"></div>
	<div class="ttfmake-section-body">
		<input type="hidden" value="<?php esc_attr_e( $ttfmake_section_data['section']['id'] ); ?>" name="<?php esc_attr_e( ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template ) ); ?>[section-type]" />
