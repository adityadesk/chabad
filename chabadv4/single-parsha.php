<?php
 
get_header();
?>

	<main id="primary" class="site-main">
		<div class="page-main">
			<div class="container">
				<div class="col-max-8 col-center">
					<?php while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content', 'parsha' );						
					endwhile; // End of the loop.
					?>
				</div>

					<div class="food-list-item-main">
							<?php 
					 
								$args = array(
									'post_type'      => 'event',
									'post_parent'	 => $post->ID,
									'posts_per_page' => -1,			        
								);
								$parsha_id = $post->ID;
								$food = new WP_Query( $args );
								if ( $food->have_posts() ) :
									while ( $food->have_posts() ) : $food->the_post();
									$location_terms = get_the_terms(get_the_ID(),'locations');
									if(	$location_terms){
										foreach($location_terms as $terms){
											$locations[$terms->term_id] = $terms;
										}	
										 
									}
									endwhile;
								endif;	
							?>
							<?php if(!empty($locations)):?>
								<div class="food-list-items">
									<div class="row ">
										<?php 
											foreach($locations as $location_data):
											$filter = add_query_arg( array(
												'parsha_id' =>$parsha_id,
												'location_id' => $location_data->term_id,
											), get_permalink(2243) );// 2243 = Event Registration
										?>
											<div class="col-12 col-sm-6 col-md-4">
												<div class="news-item food-list-item">
													<?php print_r($filter); ?>
													<?php if ( $image = get_field( 'image_link', $location_data ) ) : ?>
														<a class="news-item-thumb" href="<?php echo esc_url( $filter ); ?>">
															<img src="<?php echo $image; ?>">
														</a>
													<?php endif; ?>

													<div class="news-item-content">
														<?php if ( $location_data->name ) : ?>
															<h3 class="list-item-title">
																<a href="<?php echo esc_url( $filter ); ?>">
																	<?php echo $location_data->name; ?>
																</a>		
															</h3>
														<?php endif; ?>

														<?php if( $address = get_field( 'address', $location_data ) ) : ?>
															<p><?php echo $address; ?></p>
														<?php endif; ?>

														<a class="button" href="<?php echo esc_url( $filter ); ?>">
															Reserve Now
														</a>
													</div>
												</div><!-- .kosher-item -->
											</div><!-- .col -->
										<?php endforeach;?>
									</div><!-- .row -->
								</div><!-- .food-list-item -->
							<?php endif; wp_reset_postdata();?>
					</div><!-- .food-list-items -->

			</div><!-- .container -->
		</div>
		

	</main><!-- #main -->

<?php
get_footer();
