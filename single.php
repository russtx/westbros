<?php get_header(); ?>

<main role="main">
	<!-- section -->
	<section id="singlePost">
    <div class="container">



            <?php

              $latest_post = new WP_Query(array('posts_per_page' => 1, 'post_status' => 'publish'));

              if($latest_post->have_posts()): while($latest_post->have_posts()): $latest_post->the_post(); ?>

                <?php $latest_post_id = get_the_ID(); //skip this id in the blog list below ?>
    						<div class="row">
                	<div class="col-md-8 col-md-offset-2 latestPostContent">

                    <a href="<?php echo home_url(); ?>/blog/" class="btn-main">&#60; &#60; Back</a>

    	              <h2><?php the_title(); ?></h2>

    	              <h3><?php echo get_the_date('F j,Y'); ?></h3>

    	              <?php the_content(); ?>


    		        <?php // check if the post has a Post Thumbnail assigned to it.
    		        if ( has_post_thumbnail() ) {
    		          the_post_thumbnail();
    		        }  ?>
    		      </div>
    					</div><!-- row -->

            <?php endwhile; endif; wp_reset_postdata(); ?>


          </div><!-- container -->
    </section><!-- singlePost -->

    <section id="divider">
    </section>

    <section id="otherPost">
      <div class="container">
        <div class="row">

              <?php
                  global $post;
                  $current_post = $post;

                  for($i = 1; $i <= 3; $i++):
                  $post = get_previous_post();
                  setup_postdata($post); ?>

                  <div class="col-sm-4 otherPostContent">
                    <?php  the_post_thumbnail(); ?>
                    <h2><?php the_title(); ?></h2>

    	              <h3><?php echo get_the_date('F j,Y'); ?></h3>
                    <?php the_excerpt();?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="btn-main">Read More</a>
                  </div>


                  <?php endfor;
                  $post = $current_post;

              ?>


        </div><!-- row -->
    </div><!-- container -->
	</section><!-- otherPosts -->
</main>



<?php get_footer(); ?>
