<?php
/**
 * Template name: Synagogues
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
			$args = array(
		        'post_type'      => 'synagogue',
		        'posts_per_page' => -1,
			);
			$synagogues = new WP_Query( $args );
			if ( $synagogues->have_posts() ) :
		?>
			<div class="col-max-10 col-center">
				<div class="row">
					<?php 
						while ( $synagogues->have_posts() ) : $synagogues->the_post();
					?>
						<div class="col-12 col-sm-6">
							<a class="hotel-item hotel-item-alt flex-inner" href="<?php the_permalink();?>">
								<div class="hotel-item-content">
									<h3><?php the_title();?></h3>
									<?php printmeta('tagline', '<p>%s</p>'); ?>
								</div>
							</a><!-- .hotel-item -->
						</div><!-- .col -->
					<?php endwhile;?>
				</div><!-- .row -->
			</div>
		<?php endif; wp_reset_postdata();?>
			</div><!-- .container -->
		</div>
		<?php 
			//get_template_part( 'templates/pages/everything' );
		?>
		

	</main><!-- #main -->

<?php
get_footer();
