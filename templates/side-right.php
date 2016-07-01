<?php /* Template Name: Side - Right */ ?>

<?php get_header(); ?>

<main class="spine-sideright-template">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'parts/headers' ); ?>
<?php get_template_part( 'parts/featured-images' ); ?>

<section class="flex-row">

	<div class="grid-part fifths-3">

		<?php get_template_part( 'articles/article' ); ?>

	</div><!--/column-->

	<div class="grid-part fifths-3">

		<?php
		$column = get_post_meta( get_the_ID(), 'column-two', true );
		if ( ! empty( $column ) ) {
			echo wp_kses_post( $column );
		}
		?>

	</div>

</section>
<?php
endwhile;
endif;

get_template_part( 'parts/footers' );

?>
</main>
<?php get_footer();
