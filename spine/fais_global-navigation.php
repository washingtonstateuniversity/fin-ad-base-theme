<nav id="spine-offsitenav-fa-global" class="spine-offsitenav">
	<?php
	$spine_offsite_args = array(
		'theme_location'  => 'fais_global',
		'menu'            => 'fais_global',
		'container'       => false,
		'container_class' => false,
		'container_id'    => false,
		'menu_class'      => null,
		'menu_id'         => null,
		'fallback_cb'     => false,
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 3,
	);
	wp_nav_menu( $spine_offsite_args );
	?>
</nav>
