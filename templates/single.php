<?php /* Template Name: Single */ ?>

<?php get_header(); ?>

<main class="spine-single-template">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'parts/headers' ); ?>
<?php get_template_part( 'parts/featured-images' ); ?>

<section class="pad-airy">

	<div class="full-width">

		<?php get_template_part( 'articles/article' ); ?>

	</div><!--/column-->

</section>
<?php
endwhile;
endif;

get_template_part( 'parts/footers' );

?>
</main>
<?php get_footer();
