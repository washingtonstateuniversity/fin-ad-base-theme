<?php

//@todo turn into a class


// Add Shortcode

function cards_shortcode( $atts, $content ) {

	// Attributes
	$att = shortcode_atts(
		array(
			'position' => false,
			'name' => false,
			'tel' => false,
			'email' => false,
			'profile_url' => false,
		),
		$atts
	);
	ob_start(); ?>


<div class="flex-row items-start no-pad content-card">
    <div class="thirds-1 profile-image"> <!--flex-column items-start -->
		<?php if ( false !== $att['profile_url'] && '' !== $att['profile_url'] ) :  ?>
			<img src="<?php esc_attr_e( $att['profile_url'] ); ?>" alt="<?php if ( false !== $att['name'] && '' !== $att['name'] ) :  ?><?php esc_attr_e( $att['name'] ); ?><?php endif; ?>" />
		<?php else : ?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 463 562">
				<path d="M2,2V564H465V2H2ZM448,547H399.37c23.85-11,38.44-34.14,38.44-65.87,0-64.2-15.05-162.77-98.28-162.77-8.81,0-46,39.48-101.69,39.48s-92.89-39.48-101.69-39.48c-83.23,0-98.28,98.57-98.28,162.77,0,31.72,14.58,54.87,38.44,65.87H19V19H448V547Z" transform="translate(-2 -2)"/>
				<path d="M237.83,336.55A109.08,109.08,0,1,0,128.75,227.47,109.11,109.11,0,0,0,237.83,336.55Z" transform="translate(-2 -2)"/>
			</svg>
			<!--<span class="fa-10x"><i class="fal fa-portrait large"></i></span>-->
		<?php endif; ?>
    </div>
    <div class="flex-column items-start thirds-2 pad-tight-l profile-summary">
		<?php if ( false !== $att['position'] && '' !== $att['position'] ) :  ?>
			<h3><?php esc_attr_e( $att['position'] ); ?></h3>
		<?php endif; ?>
		<?php if ( false !== $att['name'] && '' !== $att['name'] ) :  ?>
			<h4><?php esc_attr_e( $att['name'] ); ?></h4>
		<?php endif; ?>
        <span class="full-width no-pad">
			<?php if ( false !== $att['tel'] && '' !== $att['tel'] ) :  ?>
				<h5 class=" pad-no"  style="margin: 0;"><?php esc_attr_e( $att['tel'] ); ?></h5>
			<?php endif; ?>
			<?php if ( false !== $att['email'] && '' !== $att['email']  && is_email( $att['email'] )  ) :  ?>
				<a class=" pad-tight" href="mailto:<?php esc_attr_e( $att['email'] ); ?>">Email</a>
			<?php endif; ?>
        </span>


<?php
	$contact_card = ob_get_clean();

	return $contact_card. wp_kses( $content, array(
		'a' => array(
			'href' => array(),
			'title' => array(),
		),
		'br' => array(),
		'b' => array(),
		'u' => array(),
		'i' => array(),
		'em' => array(),
		'strong' => array(),
		'ul' => array(
			'class' => array(),
		),'li' => array(
			'class' => array(),
		),
	) )  .'</div></div>';

}
add_shortcode( 'contact_cards', 'cards_shortcode' );

add_filter( 'mce_external_plugins', 'contactCard_mce_external_plugins' );
add_filter( 'mce_buttons', 'contactCard_mce_buttons', 15 );

function contactCard_mce_external_plugins( $plugins ) {
	$plugins['contactCard'] = get_stylesheet_directory_uri() . '/includes/assets/card_helper.js';
	$plugins['iconPicker'] = get_stylesheet_directory_uri() . '/includes/assets/icon_picker.js';
	return $plugins;
}

function contactCard_mce_buttons( $mce_buttons ) {
	array_push( $mce_buttons, 'separator', 'contactCard' );
	array_push( $mce_buttons, 'separator', 'iconPicker' );
	return $mce_buttons;
}

