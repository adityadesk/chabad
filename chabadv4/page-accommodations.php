<?php
/**
 * Template name: Accommodation
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chabad
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="page-main">
			<div class="container">
				<div class="col-max-8 col-center">
					<?php while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content', 'page' );						

					endwhile; // End of the loop.
					?>
				</div>
				<?php 
					$locations = get_terms( 'hotel_location', array( 'hide_empty' => false ) );
					if($locations):
					$hotel_cat =  array();
					if(isset($_GET['hotel_category'])){
						$hotel_cat = array_map( 'esc_attr', $_GET['hotel_category']);
					}
				?>
					<div class="accommodation-items-main">
						<div class="row">
							<div class="col-12 col-lg-2 offset-lg-1">
								<div class="floating-sidebar">
									<div class="floating-sidebar-widget">
										<h3><?php esc_html_e( 'Locations', 'chabad' );?></h3>
										<ul class="floating-sidebar-widget-nav">
											<?php foreach ($locations as $key => $location){?>
												<li><a href="#<?php echo $location->slug;?>"><?php echo $location->name;?></a></li>
											<?php }?>
										</ul>
									</div><!-- .floating-sidebar-widget -->
									<div class="floating-sidebar-widget">
										<form action="<?php echo get_permalink(52) ;?>" method="get" id="hotelfilter">
											<h3><?php esc_html_e( 'Category', 'chabad' );?></h3>
											<?php echo get_tex_term_filter('hotel_category',$hotel_cat);?>
										</form>
									</div><!-- .floating-sidebar-widget -->
								</div><!-- .floating-sidebar -->
							</div>
							<div class="col-12 col-lg-8 offset-lg-1">
								<div class="food-list-item-main">
									<?php foreach ($locations as $key => $location):?>
										<?php 
											$args = array(
										        'post_type'      => 'hotel',
										        'posts_per_page' => -1,
										        'tax_query'   => array( 
										       		'relation' => 'AND',
										       		array(
												        'taxonomy'  => 'hotel_location',
												        'terms'     => array( $location->slug ),
												        'field'     => 'slug',
												        'operator'  => 'IN',
											    	) 
										       	)	
											);											
											if(isset($_GET['hotel_category'])){
												
												$args['tax_query'][] = 
													
													array(
													'taxonomy' => 'hotel_category',
													'field'    => 'term_id',
													'terms'    => $hotel_cat,
												);
											}
											$hotels = new WP_Query( $args );
										?>
										<div class="food-list-items" id="<?php echo $location->slug;?>">
											<h2><?php echo esc_html__($location->name, 'chabad' );?></h2>
											<?php 
												if($location->description){
													echo '<p>'.esc_html__( $location->description, 'chabad' ).'</p>';
												}
											?>
											<?php if ( $hotels->have_posts() ) : ?>
												<div class="row">
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
														<div class="col-12 col-sm-6 flex-item">
															<a class="hotel-item flex-inner" href="<?php echo $url;?>" target="_blank">
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
											<?php else:?>
												<h3 class="h5"><?php esc_html_e( 'Nothing found', 'chabd' );?></h3>
											<?php endif; wp_reset_postdata();?>
										</div><!-- .food-list-item -->
									<?php endforeach;?>
								</div><!-- .food-list-item-main -->
							</div><!-- .col -->
						</div><!-- .row -->
					</div><!-- .accommodation-items-main-->
				<?php endif;?>
			</div><!-- .container -->
		</div>
		<?php 
			//get_template_part( 'templates/pages/everything' );
		?>
		

	</main><!-- #main -->

<?php
get_footer();
