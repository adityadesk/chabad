<section id="acomodations" class="home-acomodations page-main">
	<div class="container">
		<?php printmeta('ac_title', '<h2 class="section-title">%s</h2>', '', 2);?>
		<?php 
			$args = array(
		        'post_type'      => 'hotel',
		        'posts_per_page' => 6,
			);
			$hotels = new WP_Query( $args );
			if ( $hotels->have_posts() ) :
		?>
			<div class="row home-section-list">
				<?php 
					while ( $hotels->have_posts() ) : $hotels->the_post();
					$url = get_field('url');
					$rating = get_field('ratings_out_of_5');
					$rating_val = ($rating/5)*100;
					if($url){
						$url = $url;
					}else{
						$url = "#";
					}
				?>
					<div class="col-12 col-sm-6 col-md-4 flex-item">
						<a class="hotel-item flex-inner" href="<?php echo $url;?>">
							<div class="hotel-item-content">
								<h3><?php the_title();?></h3>
								<?php 
									//printmeta('location', '<p>%s</p>');
									if($rating){
										echo '<div class="star-rating"><div class="star-rating-in" style="width:'.$rating_val.'%;">';
										for ($i=0; $i < 5; $i++) { 
										 	echo get_svg_icon('img-star', '20', '18','<span>', '</span>');
										 } 
										echo '</div></div>';
									}
								?>
							</div>
						</a><!-- .hotel-item -->
					</div><!-- .col -->
				<?php endwhile;?>
			</div><!-- .row -->
		<?php endif; wp_reset_postdata();?>
		<div class="home-section-btn text-center">
				<?php
					$ac_button_text = get_field( 'ac_button_text', 2 );
					printmeta('ac_button_url', '<a href="%s" class="button">'.esc_html( $ac_button_text ).'</a>','', 2);
				?>
		</div>
	</div><!-- .container -->
</section>
