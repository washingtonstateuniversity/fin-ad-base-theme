<?php get_header(); ?>

<main class="spine-page-default">

	<?php get_template_part( 'parts/headers' ); ?>
	<?php get_template_part( 'parts/featured-images' ); ?>

	<section class="page type-page">
		<div id="builder-section-shim" class="flex-row  pad-tight kids-full-width-at-667">
			<div class="fifths-3 pad-tight">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'articles/article' ); ?>
				<?php endwhile; ?>
			</div><!--/column-->
			<div class="fifths-2 pad-tight">
				<?php get_sidebar(); ?>
			</div><!--/column two-->
		</div>
	</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main>

<?php get_footer();
