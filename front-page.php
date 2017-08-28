<?php get_header(); ?>
  <section id="topSection">
		<h1 class="animated slideInLeft">West Brothers</h1>
		<h1 class="animated slideInRight">Supply Co.</h1>
		<div class="contactInfo animated fadeInUpBig">
			<a class="phone" href="tel:252 555 5555">Phone: 252 555-5555</a><br />

		</div>
		<div class="map animated fadeInUpBig">
			<a class="location" href="https://www.google.com/maps/place/West+Brothers/@36.0318581,-75.7051796,13z/data=!4m8!1m2!2m1!1swest+bros+supply+kill+devil+hills+nc!3m4!1s0x89a4e047a27107fd:0x3d015e6bb89bb79f!8m2!3d36.0605899!4d-75.6891744">
				3701 N Croatan Hwy # 5<br />
				Kitty Hawk, NC 2794</a>
		</div>
		<div class="homeContent animated fadeInUpBig">
					<?php printf(  get_bloginfo ( 'description' ) ); ?><br />
		</div>
	</section>
	<section id="centerSection">
		<a href="<?php echo home_url(); ?>/contact/" class="btn-main contactButton animated flipInX">Contact Us</a>
	</section>


<?php get_footer(); ?>
