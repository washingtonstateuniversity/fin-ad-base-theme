<section id="content_area" class="flex-row pad-tight kids-full-width-at-667">

	<div class="fifths-3 pad-tight">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/post', get_post_type() ) ?>

		<?php endwhile; ?>

	</div><!--/column-->

	<div class="fifths-2 pad-tight">

		<?php get_sidebar(); ?>

		<h4>Disposition Key:</h4>
		<ul>
			<li><strong>ACT: </strong>Active</li>
			<li><strong>CLO: </strong>Closed Case</li>
			<li><strong>AM: </strong>Alarm Malfunction</li>
			<li><strong>ECP: </strong>Cleared Adult Prosecution Declined</li>
			<li><strong>CAA: </strong>Cleared Adult Arrest</li>
			<li><strong>ECV: </strong>Cleared Adult Victim/Uncooperative</li>
			<li><strong>CEE: </strong>Created in Error</li>
			<li><strong>EE: </strong>Employee Error</li>
			<li><strong>CJA: </strong>Cleared Juvenile Arrest</li>
			<li><strong>TRP: </strong>Transferred to Property</li>
			<li><strong>RSP: </strong>Request Submit to Prosecutor</li>
			<li><strong>RSJ: </strong>Request Submit to Juvenile Services</li>
		</ul>

	</div><!--/column two-->

</section>

