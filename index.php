<?php 
/*
 Plugin Name: Medical Hospital Pro Posttype
 Plugin URI: https://www.themesglance.com/
 Description: Creating new post type for Medical Hospital Pro Theme
 Author: Themesglance
 Version: 1.0
 Author URI: https://www.themesglance.com/
*/

define( 'medical_hospital_pro_posttype_VERSION', '1.0' );

add_action( 'init', 'medical_hospital_pro_posttype_create_post_type' );

function medical_hospital_pro_posttype_create_post_type() {
	register_post_type( 'services',
    array(
        'labels' => array(
            'name' => __( 'Services','medical-hospital-pro-posttype' ),
            'singular_name' => __( 'Services','medical-hospital-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
	);
  register_post_type( 'doctor',
    array(
        'labels' => array(
            'name' => __( 'Doctors','medical-hospital-pro-posttype' ),
            'singular_name' => __( 'Doctor','medical-hospital-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'testimonials',
	array(
		'labels' => array(
			'name' => __( 'Testimonials','medical-hospital-pro-posttype-pro' ),
			'singular_name' => __( 'Testimonials','medical-hospital-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-businessman',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
  register_post_type( 'success_stories',
	array(
		'labels' => array(
			'name' => __( 'Success Stories','medical-hospital-pro-posttype-pro' ),
			'singular_name' => __( 'Success_Stories','medical-hospital-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-media-spreadsheet',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
}
// Serives section
function medical_hospital_pro_posttype_images_metabox_enqueue($hook) {
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		wp_enqueue_script('vw_lawyer-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

		global $post;
		if ( $post ) {
			wp_enqueue_media( array(
					'post' => $post->ID,
				)
			);
		}

	}
}
add_action('admin_enqueue_scripts', 'medical_hospital_pro_posttype_images_metabox_enqueue');
// Services Meta
function medical_hospital_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Services Meta', 'medical-hospital-pro-posttype' ), 'medical_hospital_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
	add_action('admin_menu', 'medical_hospital_pro_posttype_bn_custom_meta_services');
}

function medical_hospital_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_image = get_post_meta( $post->ID, 'meta-image', true );
    $meta_services_url=get_post_meta($post->ID, 'meta-services-url', true);
    ?>
	<div id="property_stuff">
		<table id="list-table">			
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<p>
						<label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
						<input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo esc_attr($meta_image); ?>">
						<input type="button" class="button image-upload" value="Browse">
					</p>
					<div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image'][0]; ?>" style="max-width: 250px;"></div>
				</tr>
        <tr id="meta-2">
          <td class="left">
            <?php esc_html_e( 'Want to link with custom URL', 'medical-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-services-url" id="meta-services-url" class="meta-services-url regular-text" value="<?php echo esc_attr($meta_services_url); ?>">
          </td>
        </tr>
			</tbody>
		</table>
	</div>
	<?php
}

function medical_hospital_pro_posttype_bn_meta_save_services( $post_id ) {

	if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	// Save Image
	if( isset( $_POST[ 'meta-image' ] ) ) {
	    update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
	}
  if( isset( $_POST[ 'meta-services-url' ] ) ) {
      update_post_meta( $post_id, 'meta-services-url', esc_url_raw($_POST[ 'meta-services-url' ]) );
  }

}
add_action( 'save_post', 'medical_hospital_pro_posttype_bn_meta_save_services' );

/* Attorney */
function medical_hospital_pro_posttype_bn_designation_meta() {
    add_meta_box( 'medical_hospital_pro_posttype_bn_meta', __( 'Enter Designation','medical-hospital-pro-posttype' ), 'medical_hospital_pro_posttype_bn_meta_callback', 'doctor', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'medical_hospital_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function medical_hospital_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'medical_hospital_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_facebookurl = get_post_meta( $post->ID,'meta-facebookurl',true);
    $meta_twitterurl = get_post_meta( $post->ID, 'meta-twitterurl', true );
    $meta_googleplusurl = get_post_meta( $post->ID, 'meta-googleplusurl', true );
    $meta_pinteresturl = get_post_meta( $post->ID, 'meta-pinteresturl', true );
    $meta_instagramurl= get_post_meta( $post->ID, 'meta-instagramurl', true );
    $meta_designation= get_post_meta( $post->ID, 'meta-designation', true );


    ?>
    <div id="doctor_custom_stuff">
        <table id="list-table">         
          <tbody id="the-list" data-wp-lists="list:meta">
              <tr id="meta-3">
                <td class="left">
                  <?php esc_html_e( 'Facebook Url', 'medical-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_attr($meta_facebookurl); ?>" />
                </td>
              </tr>
              <tr id="meta-5">
                <td class="left">
                  <?php esc_html_e( 'Twitter Url', 'medical-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_attr($meta_twitterurl); ?>" />
                </td>
              </tr>
              <tr id="meta-6">
                <td class="left">
                  <?php esc_html_e( 'GooglePlus URL', 'medical-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_attr($meta_googleplusurl); ?>" />
                </td>
              </tr>
              <tr id="meta-7">
                <td class="left">
                  <?php esc_html_e( 'Pinterest URL', 'medical-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-pinteresturl" id="meta-pinteresturl" value="<?php echo esc_attr($meta_pinteresturl); ?>" />
                </td>
              </tr>
               <tr id="meta-8">
                <td class="left">
                  <?php esc_html_e( 'Instagram URL', 'medical-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-instagramurl" id="meta-instagramurl" value="<?php echo esc_attr($meta_instagramurl); ?>" />
                </td>
              </tr>
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Designation', 'medical-hospital-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($meta_designation); ?>" />
                </td>
              </tr>
          </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function medical_hospital_pro_posttype_bn_metadesig_doctor_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', sanitize_text_field($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', sanitize_text_field($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'meta-pinteresturl', esc_url_raw($_POST[ 'meta-pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'meta-instagramurl' ] ) ) {
        update_post_meta( $post_id, 'meta-instagramurl', esc_url_raw($_POST[ 'meta-instagramurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', sanitize_text_field($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'medical_hospital_pro_posttype_bn_metadesig_doctor_save' );

/* doctor shorthcode */
function medical_hospital_pro_posttype_doctor_func( $atts ) {
    $doctor = ''; 
    $custom_url ='';
    $doctor = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'doctor' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=doctor'); 
    while ($new->have_posts()) : $new->the_post();
    	$post_id = get_the_ID();
    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
		  $url = $thumb['0'];
      $excerpt = wp_trim_words(get_the_excerpt(),25);
      $designation= get_post_meta($post_id,'meta-designation',true);
      $call= get_post_meta($post_id,'meta-call',true);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      $pinterest=get_post_meta($post_id,'meta-pinteresturl',true);
      $instagram=get_post_meta($post_id,'meta-instagramurl',true);
      $doctor .= '<div class="doctors_box col-lg-4 col-md-6 col-sm-6">
                    <div class="image-box ">
                      <img class="client-img" src="'.esc_url($thumb_url).'" alt="doctor-thumbnail" />
                      <div class="doctors-box w-100 float-left">
                        <h4 class="doctor_name"><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
                        <p class="designation">'.esc_html($designation).'</p>
                      </div>
                    </div>
                  <div class="content_box w-100 float-left">
                    <div class="short_text">'.$excerpt.'</div>
                    <div class="about-socialbox">
                      <p>'.$call.'</p>
                      <div class="att_socialbox">';
                        if($facebookurl != ''){
                          $doctor .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                        } if($twitter != ''){
                          $doctor .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                        } if($googleplus != ''){
                          $doctor .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                        } if($linkedin != ''){
                          $doctor .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                        }if($pinterest != ''){
                          $doctor .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
                        }if($instagram != ''){
                          $doctor .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a>';
                        }
                      $doctor .= '</div>
                    </div>
                  </div>
                </div>';

      if($k%2 == 0){
          $doctor.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $doctor.= '</div>';
  else :
    $doctor = '<h2 class="center">'.esc_html_e('Not Found','medical-hospital-pro-posttype').'</h2>';
  endif;
  return $doctor;
}
add_shortcode( 'doctor', 'medical_hospital_pro_posttype_doctor_func' );

/* Testimonial section */
/* Adds a meta box to the Testimonial editing screen */
function medical_hospital_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'medical-hospital-pro-posttype-pro-testimonial-meta', __( 'Enter Designation', 'medical-hospital-pro-posttype-pro' ), 'medical_hospital_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'medical_hospital_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function medical_hospital_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'medical_hospital_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  $desigstory = get_post_meta( $post->ID, 'medical_hospital_pro_posttype_posttype_testimonial_desigstory', true );
  $meta_facebookurl = get_post_meta( $post->ID, 'meta-facebookurl', true );
  $meta_twitterurl = get_post_meta( $post->ID, 'meta-twitterurl', true );
  $meta_googleplusurl = get_post_meta( $post->ID, 'meta-googleplusurl', true );
  $meta_pinteresturl = get_post_meta( $post->ID, 'meta-pinteresturl', true );
	$meta_instagramurl= get_post_meta( $post->ID, 'meta-instagramurl', true );

	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
        
         <tr id="meta-3">
                  <td class="left">
                    <?php esc_html_e( 'Facebook Url', 'medical-hospital-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_attr($meta_facebookurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php esc_html_e( 'Twitter Url', 'medical-hospital-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_attr( $meta_twitterurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php esc_html_e( 'GooglePlus URL', 'medical-hospital-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_attr($meta_googleplusurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php esc_html_e( 'Pinterest URL', 'medical-hospital-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-pinteresturl" id="meta-pinteresturl" value="<?php echo esc_attr($meta_pinteresturl); ?>" />
                  </td>
                </tr>
                 <tr id="meta-8">
                  <td class="left">
                    <?php esc_html_e( 'Instagram URL', 'medical-hospital-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-instagramurl" id="meta-instagramurl" value="<?php echo esc_attr($meta_instagramurl); ?>" />
                  </td>
                </tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function medical_hospital_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['medical_hospital_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['medical_hospital_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'medical_hospital_pro_posttype_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'medical_hospital_pro_posttype_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'medical_hospital_pro_posttype_posttype_testimonial_desigstory']) );
	}
  if( isset( $_POST[ 'meta-services-url' ] ) ) {
    update_post_meta( $post_id, 'meta-services-url', esc_url($_POST[ 'meta-services-url']) );
  }

}

add_action( 'save_post', 'medical_hospital_pro_posttype_bn_metadesig_save' );

/* Testimonials shortcode */
function medical_hospital_pro_posttype_testimonial_func( $atts ) {
	$testimonial = '';
	$testimonial = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
        $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
        $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
        $twitter=get_post_meta($post_id,'meta-twitterurl',true);
        $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
        $pinterest=get_post_meta($post_id,'meta-pinteresturl',true);
        $instagram=get_post_meta($post_id,'meta-instagramurl',true);
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      	$desigstory= get_post_meta($post_id,'medical_hospital_pro_posttype_posttype_testimonial_desigstory',true);
       
        $testimonial .= '
          <div class="col-md-6 col-sm-12">
            <div class=" testimonial_box mb-3">
              <div class="image-box media">
                <img class="testi-img" src="'.esc_url($thumb_url).'" alt="testimonial-thumbnail" />
                <div class="testimonial-box media-body">    
                  <p>'.esc_html($desigstory).'</p>
                </div>
              </div>
              <div class="content_box">
                 <h4 class="testimonial_name"><a href="'.the_permalink().'">'.esc_html(get_the_title()) .'</a></h4>
                <div class="social-icons">';
                if($facebookurl != '')
                {
                  $testimonial .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                }
                if($twitter != '')
                {
                  $testimonial .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                }
                if($googleplus != '')
                {
                  $testimonial .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                }
                if($pinterest != '')
                {
                  $testimonial .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
                }
                if($instagram != '')
                {
                  $testimonial .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a>';
                }
                 $testimonial .= '
              </div>
              <div class="short_text pt-3"><p>'.$excerpt.'</p></div>
              </div>
            </div>
          </div>';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','medical-hospital-pro-posttype-pro').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}

add_shortcode( 'testimonials', 'medical_hospital_pro_posttype_testimonial_func' );

/* Services shortcode */
function medical_hospital_pro_posttype_services_func( $atts ) {
  $services = '';
  $services = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $services_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-services-url',true !='')){$custom_url =get_post_meta($post_id,'meta-services-url',true); } else{ $custom_url = get_permalink(); }
        $services .= '<div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="row services">
                          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                             <div class="services_icon">
                             <img class="" src="'.esc_url($services_image).'">
                          </div>
                        </div>
                      <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <h6><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h6>
                        <p>
                          '.$excerpt.'
                        </p>
                    </div>
                  </div>
                </div>';


    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','medical-hospital-pro-posttype-pro').'</h2>';
  endif;
  $services .= '</div>';
  return $services;
}

add_shortcode( 'list-services', 'medical_hospital_pro_posttype_services_func' );

/* ------------------Success Stories--------------- */

/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'medical_hospital_pro_posttype_bn_custom_meta_success_stories');
}

function medical_hospital_pro_posttype_bn_custom_meta_success_stories() {

    add_meta_box( 'bn_meta', __( 'Success Stories Meta', 'medical-hospital-pro-posttype' ), 'medical_hospital_pro_posttype_bn_meta_callback_success_stories', 'success_stories', 'normal', 'high' );
}

function medical_hospital_pro_posttype_bn_meta_callback_success_stories( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_success_stories_url=get_post_meta( $post->ID,'meta-success-stories-url',true);

    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-2">
          <td class="left">
            <?php esc_html_e( 'Want to link with custom URL', 'medical-hospital-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-success-stories-url" id="meta-success-stories-url" class="meta-success-stories-url regular-text" value="<?php echo esc_attr($meta_success_stories_url) ?>">
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function medical_hospital_pro_posttype_bn_meta_save_success_stories( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
 
  if( isset( $_POST[ 'meta-success-stories-url' ] ) ) {
      update_post_meta( $post_id, 'meta-success-stories-url', esc_url_raw($_POST[ 'meta-success-stories-url' ]) );
  }

}
add_action( 'save_post', 'medical_hospital_pro_posttype_bn_meta_save_success_stories' );




/* success stories shortcode */
function medical_hospital_pro_posttype_success_stories_func( $atts ) {
  $custom_url ='';
  $post_id = get_the_ID();
  $success_stories = '';
  $success_stories = '<div id="accordion" class="row">';
  $query = new WP_Query( array( 'post_type' => 'success_stories') );
  if(get_post_meta($post_id,'meta-success-stories-url',true !='')){$custom_url =get_post_meta($post_id,'meta-success-stories-url',true); } else{ $custom_url = get_permalink(); }
    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=success_stories');

  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $desigstory= get_post_meta($post_id,'medical_hospital_pro_posttype_posttype_testimonial_desigstory',true);
        $success_stories .= '
        <div class="row success-stories-content my-stories-content">
          <div class="col-md-6 col-sm-4 col-lg-6 col-xs-12">
              <a href="'.esc_url($custom_url).'"><img src="'.esc_url($thumb_url).'"></a>
            </div>
          <div class="col-md-6 col-sm-8 col-lg-6 col-xs-12"> 
            <h5><a href="'.esc_url($custom_url).'">'.get_the_title().'</a></h5>
                <p>'.get_the_content().'</p>
          </div>
        </div>';
    if($k%2 == 0){
      $success_stories.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $success_stories = '<h2 class="center">'.esc_html__('Post Not Found','medical-hospital-pro-posttype-pro').'</h2>';
  endif;
  $success_stories .= '</div>';
  return $success_stories;
}
add_shortcode( 'list-success_stories', 'medical_hospital_pro_posttype_success_stories_func' );