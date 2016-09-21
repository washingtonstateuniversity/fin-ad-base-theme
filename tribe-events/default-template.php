<?php
get_header();

?>
<main>

	<?php get_template_part( 'parts/headers' ); ?>

	<section id="content_area">
		<div class="" id="tribe-events-pg-template">
			<?php tribe_events_before_html(); ?>
			<?php tribe_get_view(); ?>
			<?php tribe_events_after_html(); ?>
		</div>
	</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main>
<?php

get_footer();
