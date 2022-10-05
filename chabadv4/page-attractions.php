<?php
/**
 * Template name: Attractions
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
					$foot_cats = get_terms( 'attractions_category', array( 'hide_empty' => false ) );
					if($foot_cats):
				?>
					<div class="food-list-item-main">
						<?php foreach ($foot_cats as $key => $foot_cat):?>
							<?php 
								$args = array(
							        'post_type'      => 'attraction',
							        'posts_per_page' => -1,
							        'tax_query'   => array( array(
								        'taxonomy'  => 'attractions_category',
								        'terms'     => array( $foot_cat->slug ),
								        'field'     => 'slug',
								        'operator'  => 'IN',
								    ) )	
								);
								$food = new WP_Query( $args );
								if ( $food->have_posts() ) :
							?>
								<div class="food-list-items">
									<h2><?php echo $foot_cat->name;?></h2>
									<div class="row ">
										<?php 
											while ( $food->have_posts() ) : $food->the_post();
										?>
											<div class="col-12 col-sm-6 col-md-4">
												<a class="news-item food-list-item" href="<?php the_permalink();?>">
													<div class="news-item-thumb">
														<?php the_post_thumbnail( 'card-thumb' );?>
													</div>
													<div class="news-item-content">
														<h3><?php the_title();?></h3>
														<?php printmeta('tagline', '<p>%s</p>');?>
													</div>
												</a><!-- .kosher-item -->
											</div><!-- .col -->
										<?php endwhile;?>
									</div><!-- .row -->
								</div><!-- .food-list-item -->
							<?php endif; wp_reset_postdata();?>
						<?php endforeach;?>
					</div><!-- .food-list-items -->
				<?php endif;?>
			</div><!-- .container -->
		</div>
		<?php 
			get_template_part( 'templates/pages/everything' );
		?>
		

	</main><!-- #main -->

<?php
get_footer();
