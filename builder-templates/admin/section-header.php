<?php
global $ttfmake_section_data, $ttfmake_is_js_template;
?>
<style>



.ttfmake-section.active-false{
	position: relative;
}
.ttfmake-section.active-false .ttfmake-section-header{

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
    border: 1px solid #cccbcb;
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
    border: 1px dashed rgba(119, 119, 119, 0.81);
    position: relative;
    background: #fff;
}
.wsuwp-spine-builder-column:before {
	content: "Column area";
	position: absolute;
	top: 1rem;
	left: 1rem;
	background: rgb(238, 238, 238);
	padding: 5px 10px;
	font-size: 0.8rem;
	z-index: 9;
	border: 1px solid #d9d9d9;
	border-right: none;
	color: #494949;
}


.wsuwp-spine-builder-column:before,
.wsuwp-spine-builder-column.pad-no:before,
.wsuwp-spine-builder-column.no-pad:before {
    top: 0rem;
    left: 0rem;
}
.wsuwp-spine-builder-column.pad-hair:before {
    top: 0.2rem;
    left: 0.2rem;
}
.wsuwp-spine-builder-column.pad-tight:before {
    top: 0.8rem;
    left: 0.8rem;
}
.wsuwp-spine-builder-column.pad-airy:before {
	top: 1.6rem;
    left: 1.6rem;
}

.wsuwp-spine-column-stage:after,
.wsuwp-spine-builder-column:after {

    position: absolute;

    color: #000;
    background: rgba(255, 255, 255, 1);
    border: 1px solid #494949;


    padding-top: 0;
    line-height: 11px;
	z-index: 9;
}

.wsuwp-spine-column-stage:after {
	content: "section-padding\ \}\ ";
    top: -8px;
    right: 6px;
    border-right: none;
    padding-left: .5rem;
    text-indent: -2px;
}
.wsuwp-spine-builder-column:after {
    content: "\{\ column-padding";
    top: -8px;
    left: 6px;
    border-left: none;
	padding-right: .5rem;
	text-indent: -4px;
}

.wsuwp-spine-column-stage{
    background: #fff5f5;
    border-radius: .5rem;
    background: repeating-linear-gradient( 135deg, rgb(219, 222, 212), rgb(219, 222, 212) 3px, rgba(255, 255, 255, 0.4) 3px, rgba(255, 255, 255, 0.4) 15px ), linear-gradient(to right, #dedede, #dedede);
	    box-shadow: inset 0 0 .5rem .2rem rgba(50,50,50,.55);
}

.wsuwp-spine-builder-column {
	background: #fff5f5;
    background: repeating-linear-gradient( 45deg, rgba(200, 200, 200, 0.2), rgba(200, 200, 200, 0.2) 3px, rgba(255, 255, 255, 0.2) 3px, rgba(255, 255, 255, 0.2) 15px ), linear-gradient(to right, #dedede, #dedede);
}
.box-model-part-content{

  background-repeat: no-repeat, no-repeat, no-repeat;
  background-position: bottom right, left, right;
background: linear-gradient(to right, rgba(255,255,255,.3), rgba(255,255,255,.3)), linear-gradient(to right, #dedede, #dedede);
    padding: 0;
}
.ttfmake-section-open .ttfmake-section-body {
    height: auto;
    padding: 0 !important;
    border-radius: .5rem;
}
.tfmake-section.ttfmake-section-open {

    border-radius:  0 0 .5rem .5rem;
}

.ttfmake-section-body {
	background: #dedede;
	padding-bottom: 3.4rem;
}
/*media all*/
#tinymce {

  background-repeat: no-repeat, no-repeat, no-repeat;
  background-position: bottom right, left, right;
background: linear-gradient(to right, rgba(255,255,255,.3), rgba(255,255,255,.3)), linear-gradient(to right, #dedede, #dedede);
}


}

iframe[id^="ttfmakeeditortext14666957786431_ifr"]{
	background: transparent;
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
