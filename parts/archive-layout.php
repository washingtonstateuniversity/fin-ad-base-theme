<section id="content_area" class="flex-row pad-tight kids-full-width-at-667">

	<div class="fifths-3 pad-tight">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/post', get_post_type() ) ?>

		<?php endwhile; ?>

	</div><!--/column-->

	<div class="fifths-2 pad-tight">

		<?php get_sidebar(); ?>

	</div><!--/column two-->

</section>

