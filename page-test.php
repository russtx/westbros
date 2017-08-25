<?php get_header(); ?>

<section id="hero"  >
	<div class="heroImage" style="background-image: url(<?php the_field('hero_image'); ?>);">
		<h1><?php echo get_field('hero_title'); ?></h1>
	</div>


</section><!-- heroTrails -->


	<!-- section -->
	<section>



	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class('mainContent'); ?>>

			<?php the_content(); ?>

			<?php comments_template( '', true ); // Remove if you don't want comments ?>

			<br class="clear">

			<?php edit_post_link(); ?>

		</article>
		<!-- /article -->

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h2><?php _e( 'Sorry, nothing to display.' ); ?></h2>

		</article>
		<!-- /article -->

	<?php endif; ?>

	</section>
	<!-- /section -->




<?php get_footer(); ?>
