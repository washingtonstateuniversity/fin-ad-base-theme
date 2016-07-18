<?php get_header(); ?>

<main class="spine-page-default">

<?php get_template_part( 'parts/headers' ); ?>
<?php get_template_part( 'parts/featured-images' ); ?>

<section class="flex-row pad-TB ">

	<div class="fifths-3 full-width-at-600">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/article' ); ?>

		<?php endwhile; ?>

	</div><!--/column-->

	<div class="fifths-2 full-width-at-600">

		<?php get_sidebar(); ?>

	</div><!--/column two-->

</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main>

<?php get_footer();
