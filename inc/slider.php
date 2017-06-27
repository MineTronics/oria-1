<?php
/**
 * Carousel template
 *
 * @package
 */

	//Scripts
	function oria_slider_scripts() {
			wp_enqueue_script( 'oria-owl-script', get_template_directory_uri() .  '/js/owl.carousel.min.js', array( 'jquery' ), true );	
			wp_enqueue_script( 'oria-slider-init', get_template_directory_uri() .  '/js/slider-init.js', array( 'jquery' ), true );
			
			//Slider speed options
			if ( ! get_theme_mod('carousel_speed') ) {
				$slideshowspeed = 4000;
			} else {
				$slideshowspeed = intval(get_theme_mod('carousel_speed'));
			}			
			$slider_options = array(
				'slideshowspeed' => $slideshowspeed,
			);			
			wp_localize_script('oria-slider-init', 'sliderOptions', $slider_options);			
	}
	add_action( 'wp_enqueue_scripts', 'oria_slider_scripts' );

	//Template
	if ( ! function_exists( 'oria_slider_template' ) ) {
		function oria_slider_template() {
		       	//Get the user choices
			$in_type    = get_theme_mod('carousel_post_type'); /** default 'post' */
			// $in_selctions = get_theme_mod('carousel_post_selections');
		        $number     = get_theme_mod('carousel_number');
		        $cat        = get_theme_mod('carousel_cat');
	        	$number     = ( ! empty( $number ) ) ? intval( $number ) : 6;
		        $cat        = ( ! empty( $cat ) ) ? intval( $cat ) : '';
			$pages = array();
			for ( $count = 1; $count <= $number; $count++ ) {
				$mod = get_theme_mod( 'showcase-page-' . $count );
				if ( 'page-none-selected' != $mod ) {
					$pages[] = $mod;
				}
			}
			$args = array(
				'post_type'			=> $in_type,
				'posts_per_page'		=> $number,
				'post_status'   		=> 'publish',
			        'cat'				=> $cat,
				'post__in' 			=> $pages,
	       			'ignore_sticky_posts'   	=> true,
				'orderby' => 'post__in'
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
			?>
			<div class="oria-slider slider-loader">
				<div class="featured-inner clearfix">
					<div class="slider-inner">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="slide">
<a href="<?php the_permalink();?>" rel="bookmark">
							<?php

							if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'oria-carousel' ); ?>
							<?php else : ?>
								<?php echo '<img src="' . get_stylesheet_directory_uri() . '/images/placeholder.png"/>'; ?>
							<?php endif; ?>
							</a>
							<?php the_title( sprintf( '<h3 class="slide-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					
						</div>
					<?php endwhile; ?>
					</div>
				</div>
			</div>
			<?php }
			wp_reset_postdata();
		}
	}
