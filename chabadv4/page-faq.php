<?php
/**
 * Template name: FAQ
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
				<div class="col-max-8 col-center">
					<?php
						$val = '';
						$search = $_GET['search'];
						if(isset($search)){
							$val = $search;
						}
					?>
					<form class="faq-search" action="<?php echo get_permalink( $post->ID );?>">
						<input type="text" placeholder="<?php esc_html_e( 'Search FAQ', 'chabad' ); ?>" name="search" value="<?php echo $val;?>">
						<button type="submit"><?php echo get_svg_icon('img-search', '20', '20','<span>', '</span>');?></button>
					</form>
					<?php 						
						$args = array(
					        'post_type'      => 'faq',
					        'posts_per_page' => -1,
					        's' => $val
						);
						$faq = new WP_Query( $args );
						if ( $faq->have_posts() ) :
					?>
						<div class="faq-items">
							<?php 
								while ( $faq->have_posts() ) : $faq->the_post();
							?>
								<div class="faq-item">
									<div class="faq-item-title">
										<h3><?php the_title();?></h3>
									</div><!-- .faq-item-title -->
									<div class="faq-item-content">
										<?php the_content();?>
									</div><!-- .faq-item-content -->
								</div><!-- .faq-item -->
							<?php endwhile;?>
						</div><!-- .faq-items -->
					<?php else:?>
						<h3 class="text-center"><?php esc_html_e( 'Nothing Found', 'chabad' ); ?></h3>
					<?php endif; wp_reset_postdata();?>
				</div>
			</div><!-- .container -->
		</div>
		<?php 
			//get_template_part( 'templates/pages/everything' );
		?>
		

	</main><!-- #main -->

<?php
get_footer();
