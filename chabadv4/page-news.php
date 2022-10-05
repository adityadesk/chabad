<?php
/**
 * Template name: News
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
		        'post_type'      => 'post',
		        'posts_per_page' => -1,
			);
			$synagogues = new WP_Query( $args );
			if ( $synagogues->have_posts() ) :
		?>
			<div class="row">
				<?php 
					while ( $synagogues->have_posts() ) : $synagogues->the_post();
				?>
					<div class="col-12 col-sm-6 col-md-4">
						<a class="news-item" href="<?php the_permalink();?>">
							<div class="news-item-thumb">
								<?php the_post_thumbnail( 'card-thumb' );?>
							</div>
							<div class="news-item-content">
								<h3><?php the_title();?></h3>
								<p><?php echo get_the_date( 'd F Y' );?>
							</div>
						</a><!-- .news-item -->
					</div><!-- .col -->
				<?php endwhile;?>
			</div><!-- .row -->
		<?php endif; wp_reset_postdata();?>
			</div><!-- .container -->
		</div>
		<?php 
			//get_template_part( 'templates/pages/everything' );
		?>
		

	</main><!-- #main -->

<?php
get_footer();
