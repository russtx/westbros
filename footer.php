</div><!--container-->
<footer class="animated zoomIn">

  <p>
    &copy; <?php echo date('Y'); ?><?php bloginfo('name'); ?><br />
    ALL RIGHTS RESERVERED.
  </p>
  <p>
    -WEBSITE CREATED BY:<br />
    <a href="#" target="_blank">TOP LINE MEDIA TEAM</a>
  </p>
  <div class="social">
  <?php
  // social links, using the svgs inside the icons folder
  // sample icons are from https://dribbble.com/shots/2089345-Nucleo-Free-Social-Icons
  social_links('facebook');
  social_links('twitter');
  social_links('youtube');
  social_links('instagram');
  ?>

  </div>

<?php wp_footer(); ?>
</footer>

</div> <!-- close main container -->
</body>
</html>
