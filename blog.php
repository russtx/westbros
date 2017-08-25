<?php /* Template Name: Blog Page  */ get_header(); ?>

<main >
	<!-- section -->
	<section id="postSection">

    <div class="postArea">
          <div class="container">
            <div class="row">

              <?php

                $other_posts = new WP_Query(array(

                  'post_status' => 'publish',

                  'paged' => get_query_var('paged'),

                  'post__not_in' => array($latest_post_id)

                ));

                if($other_posts->have_posts()): $i=0; while($other_posts->have_posts()): $other_posts->the_post(); ?>

                  <?php if($i%3 == 0){ echo '<div class="clearfix"></div>'; } ?>

                  <div class="col-sm-4 postContent">

                    <?php the_post_thumbnail(); ?>

                    <h3><?php the_title(); ?></h3>

                    <span><p><?php echo get_the_date('F j,Y'); ?></p></span>

                    <p><?php the_excerpt(); ?></p>

                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="btn-main">Read More</a>

                  </div>

              <?php $i++; endwhile; wp_pagenavi(array('query' => $other_posts)); endif; wp_reset_postdata(); ?>

            </div><!-- row -->
          </div><!-- container -->
        </div><!-- postArea -->





	</section>
	<!-- /section -->
</main>



<?php get_footer(); ?>
