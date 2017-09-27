<?php

//disable admin bar
show_admin_bar(false);


//Add thumbnail, automatic feed links and title tag support
add_theme_support( 'post-thumbnails' );
//add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );

//Add content width (desktop default)
if ( ! isset( $content_width ) ) {
	$content_width = 768;
}

// filter the Gravity Forms button type
add_filter('gform_submit_button', 'form_submit_button', 10, 2);
function form_submit_button($button, $form){
    return "<button class='button btn' id='gform_submit_button_{$form["id"]}'><span>{$form['button']['text']}</span></button>";
}

// Register sidebar
add_action('widgets_init', 'theme_register_sidebar');
function theme_register_sidebar() {
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
		    'id' => 'sidebar-1',
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h4>',
		    'after_title' => '</h4>',
		 ));
	}
}

// Bootstrap_Walker_Nav_Menu setup
add_action( 'after_setup_theme', 'bootstrap_setup' );
if ( ! function_exists( 'bootstrap_setup' ) ):
	function bootstrap_setup(){
		add_action( 'init', 'register_menu' );
		function register_menu(){
			register_nav_menu( 'top-bar', 'Bootstrap Top Menu' );
		}
		class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {
			function start_lvl( &$output, $depth = 0, $args = array() ) {
				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"dropdown-menu\">\n";
			}
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				if (!is_object($args)) {
					return; // menu has not been configured
				}

				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				$li_attributes = '';
				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = ($args->has_children) ? 'dropdown' : '';
				$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
				$classes[] = 'menu-item-' . $item->ID;


				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				$class_names = ' class="' . esc_attr( $class_names ) . '"';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}

			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

				if ( !$element )
					return;

				$id_field = $this->db_fields['id'];

				//display this element
				if ( is_array( $args[0] ) )
					$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
				else if ( is_object( $args[0] ) )
					$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'start_el'), $cb_args);

				$id = $element->$id_field;

				// descend only when the depth is right and there are childrens for this element
				if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

					foreach( $children_elements[ $id ] as $child ){

						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							$cb_args = array_merge( array(&$output, $depth), $args);
							call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
						unset( $children_elements[ $id ] );
				}

				if ( isset($newlevel) && $newlevel ){
					//end the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
				}

				//end this element
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'end_el'), $cb_args);
			}
		}
 	}
endif;

//new menu

// START THEME OPTIONS
// custom theme options for user in admin area - Appearance > Theme Options
function pu_theme_menu()
{
  add_theme_page( 'Theme Option', 'Theme Options', 'manage_options', 'pu_theme_options.php', 'pu_theme_page');
}
add_action('admin_menu', 'pu_theme_menu');

function pu_theme_page()
{
?>
    <div class="section panel">
      <h1>Custom Theme Options</h1>
      <form method="post" enctype="multipart/form-data" action="options.php">
      <hr>
        <?php
          settings_fields('pu_theme_options');
          do_settings_sections('pu_theme_options.php');
          echo '<hr>';
        ?>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
      </form>
    </div>
    <?php
}

add_action( 'admin_init', 'pu_register_settings' );

/**
 * Function to register the settings
 */
function pu_register_settings()
{
    // Register the settings with Validation callback
    register_setting( 'pu_theme_options', 'pu_theme_options' );

    // Add settings section
    add_settings_section( 'pu_text_section', 'Social Links', 'pu_display_section', 'pu_theme_options.php' );

    // Create textbox field
    $field_args = array(
      'type'      => 'text',
      'id'        => 'twitter_link',
      'name'      => 'twitter_link',
      'desc'      => 'Twitter Link - Example: http://twitter.com/username',
      'std'       => '',
      'label_for' => 'twitter_link',
      'class'     => 'css_class'
    );

    // Add twitter field
    add_settings_field( 'twitter_link', 'Twitter', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    $field_args = array(
      'type'      => 'text',
      'id'        => 'facebook_link',
      'name'      => 'facebook_link',
      'desc'      => 'Facebook Link - Example: http://facebook.com/username',
      'std'       => '',
      'label_for' => 'facebook_link',
      'class'     => 'css_class'
    );

    // Add facebook field
    add_settings_field( 'facebook_link', 'Facebook', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    $field_args = array(
      'type'      => 'text',
      'id'        => 'youtube_link',
      'name'      => 'youtube_link',
      'desc'      => 'Youtube Link - Example: https://www.youtube.com/channel/channel_id',
      'std'       => '',
      'label_for' => 'youtube_link',
      'class'     => 'css_class'
    );

    // Add youtube field
    add_settings_field( 'youtube_ink', 'Youtube', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    $field_args = array(
      'type'      => 'text',
      'id'        => 'instagram_link',
      'name'      => 'instagram_link',
      'desc'      => 'Instagram Link - Example: http://instagram.com/username',
      'std'       => '',
      'label_for' => 'instagram_link',
      'class'     => 'css_class'
    );

    // Add Instagram field
    add_settings_field( 'instagram_link', 'Instagram', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

}

// helper for inserting social links
function social_links($args)
{
  $social_links = get_option( 'pu_theme_options' );
  if($social_links[$args.'_link']) {
    echo('
      <a href="'.$social_links[$args.'_link'].'" target="_blank">
      <img src="' . get_bloginfo('template_url') . '/icons/'.$args.'.svg" >
      </a>');
  }
}

// allow wordpress post editor functions to be used in theme options
function pu_display_setting($args)
{
    extract( $args );

    $option_name = 'pu_theme_options';

    $options = get_option( $option_name );

    switch ( $type ) {
          case 'text':
              $options[$id] = stripslashes($options[$id]);
              $options[$id] = esc_attr( $options[$id]);
              echo "<input class='regular-text$class' type='text' id='$id' name='" . $option_name . "[$id]' value='$options[$id]' />";
              echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
          break;
          case 'textarea':
              $options[$id] = stripslashes($options[$id]);
              //$options[$id] = esc_attr( $options[$id]);
              $options[$id] = esc_html( $options[$id]);

              printf(
              	wp_editor($options[$id], $id,
              		array('textarea_name' => $option_name . "[$id]",
              			'style' => 'width: 200px'
              			))
				);
              // echo "<textarea id='$id' name='" . $option_name . "[$id]' rows='10' cols='50'>".$options[$id]."</textarea>";
              // echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
          break;
    }
}

function pu_validate_settings($input)
{
  foreach($input as $k => $v)
  {
    $newinput[$k] = trim($v);

    // Check the input is a letter or a number
    if(!preg_match('/^[A-Z0-9 _]*$/i', $v)) {
      $newinput[$k] = '';
    }
  }

  return $newinput;
}

// Add custom styles to theme options area
add_action('admin_head', 'custom_style');

function custom_style() {
  echo '<style>
    .appearance_page_pu_theme_options .wp-editor-wrap {
      width: 75%;
    }
    .regular-textcss_class {
    	width: 50%;
    }
    .appearance_page_pu_theme_options h3 {
    	font-size: 2em;
    	padding-top: 40px;
    }
  </style>';
}

// END THEME OPTIONS


/**
 * Load site scripts.
 */
function bootstrap_theme_enqueue_scripts() {
	$template_url = get_template_directory_uri();
	add_action('wp_enqueue_scripts', 'jquery_cdn');

function jquery_cdn(){
  if(!is_admin()){
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, null, true);
    wp_enqueue_script('jquery');
  }
}
	wp_register_script(
	'bootstrap-script',
	'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
	array('jquery'),
	'',
	true
);
  wp_register_script(
    'google-maps',
    '//maps.googleapis.com/maps/api/js?key=AIzaSyCFTzESYhJl9b73O7vP32FP7J1c5Uf3jL4',
    array('jquery'),
    '',
    false
  );
 	wp_enqueue_script('bootstrap-script');
	wp_register_style('bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
  wp_enqueue_style('bootstrap-css');
	wp_enqueue_script('google-maps');
	wp_enqueue_script( 'script', $template_url . '/js/script.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'lightbox', $template_url . '/js/lightbox.min.js', array( 'jquery' ), '', true );
	//Main Style
  //wp_enqueue_style( 'main-style', $template_url . 'style.css' );
	wp_enqueue_style( 'main-style', get_stylesheet_uri() );
	//wp_enqueue_style( 'animate.min.css', $template_url . 'style.css' );
	wp_enqueue_style( 'animate.css', $template_url . '/css/animate.css');
  //wp_enqueue_style( 'lightbox', $template_url . '/css/lightbox.min.css' );
	// Load Thread comments WordPress script.
	if ( is_singular() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bootstrap_theme_enqueue_scripts', 1 );
function pu_display_section($section){}
	function new_excerpt_more( $more ) {
		return '';
	}
	add_filter('excerpt_more', 'new_excerpt_more');

	/**
	 * Filter the "read more" excerpt string link to the post.
	 *
	 * @param string $more "Read more" excerpt string.
	 * @return string (Maybe) modified "read more" excerpt string.
	 */
	function wpdocs_excerpt_more( $more ) {
	    return sprintf( '<a class="read-more" href="%1$s">%2$s</a>',
	        get_permalink( get_the_ID() ),
	        __( '&nbsp; Full Blog Post &#62;', 'textdomain' )
	    );
	}
	add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );
//pev next OPTIONS
// AFC functions

if(function_exists('acf_add_options_page')){
  acf_add_options_page(array(
    'page_title' => 'General Settings',
    'menu_title' => 'General Settings',
    'menu_slug' => 'general-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Social Links',
		'menu_title'	=> 'Social Links',
    'parent_slug'	=> 'general-settings',
	));
}
//google apply_filters
function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyCFTzESYhJl9b73O7vP32FP7J1c5Uf3jL4');
}
add_action('acf/init', 'my_acf_init');

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
