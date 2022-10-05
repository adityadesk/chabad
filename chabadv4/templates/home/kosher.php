<?php 
	$args = array(
        'post_type'      => 'food',
        'posts_per_page' => 4,
	);
	$food = new WP_Query( $args );
	if ( $food->have_posts() ) :
?>
	<section id="kosher" class="home-kosher home-section home-section-alt">
		<div class="container">
			<?php printmeta('k_title', '<h2 class="section-title">%s</h2>');?>
			
			<div class="row home-section-list">
				<?php 
					while ( $food->have_posts() ) : $food->the_post();
					$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					$style = '';
					if($img){
						$style = ' style="background-image: url('.$img[0].')"';
					}
				?>
					<div class="col-12 col-sm-6 col-md-4 col-lg-3">
						<a class="kosher-item"<?php echo $style;?> href="<?php the_permalink();?>">
							<div class="kosher-item-content">
								<h3><?php the_title();?></h3>
								<?php printmeta('tagline', '<p>%s</p>');?>
							</div>
						</a><!-- .kosher-item -->
					</div><!-- .col -->
				<?php endwhile;?>
			</div><!-- .row -->
			
			<div class="home-section-btn text-center">
				<?php
					$k_button_text = get_field( 'k_button_text' );
					printmeta('k_button_url', '<a href="%s" class="button">'.esc_html( $k_button_text ).'</a>');
				?>
			</div>
		</div><!-- .container -->
	</section>
<?php endif; wp_reset_postdata();?>