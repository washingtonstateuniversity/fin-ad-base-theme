<?php /* Template Name: Side - Left */ ?>

<?php get_header(); ?>

<main class="spine-sideleft-template">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'parts/headers' ); ?>
<?php get_template_part( 'parts/featured-images' ); ?>

<section class="flex-row">

	<div class="grid-part fifths-2">

		<?php
		$column = get_post_meta( get_the_ID(), 'column-one', true );
		if ( ! empty( $column ) ) {
			echo wp_kses_post( $column );
		}
		?>

	</div><!--/column-->

	<div class="grid-part fifths-2">

		<?php get_template_part( 'articles/article' ); ?>

	</div>

</section>
<?php
endwhile;
endif;
get_template_part( 'parts/footers' );
?>
</main>
<?php get_footer();
