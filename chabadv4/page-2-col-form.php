<?php
/**
 * Template Name: 2 col with form
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
				<div class="col-max-10 col-center">
					<?php while ( have_posts() ) : the_post();?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
								<?php 
									if(function_exists('bcn_display')){
										echo '<div class="bread-crumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
										bcn_display();
										echo '</div><!-- .bread-crumbs -->';
									}
								?>
							</header><!-- .entry-header -->
							<div class="row event-detail-main">						
								<div class="col-12 col-lg-6">									
									<div class="entry-content">
										<?php
										the_content();

										wp_link_pages(
											array(
												'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'chabad' ),
												'after'  => '</div>',
											)
										);
										?>
									</div><!-- .entry-content -->
								</div><!-- .col -->
								<div class="col-12 col-lg-6">
									<?php 
										$form_shortcode = get_field('form_shortcode');
										if($form_shortcode):
									?>
										<div class="event-detail-register">
											<?php 
											
											echo do_shortcode( $form_shortcode ); 
											?>
										</div><!-- .event-detail-register -->
									<?php endif;?>
								</div><!-- .col -->
							</div><!-- .row -->

							<?php if ( get_edit_post_link() ) : ?>
								<footer class="entry-footer">
									<?php
									edit_post_link(
										sprintf(
											wp_kses(
												/* translators: %s: Name of current post. Only visible to screen readers */
												__( 'Edit <span class="screen-reader-text">%s</span>', 'chabad' ),
												array(
													'span' => array(
														'class' => array(),
													),
												)
											),
											wp_kses_post( get_the_title() )
										),
										'<span class="edit-link">',
										'</span>'
									);
									?>
								</footer><!-- .entry-footer -->
							<?php endif; ?>
						</article><!-- #post-<?php the_ID(); ?> -->

					<?php endwhile; // End of the loop. ?>
				</div>
			</div><!-- .container -->
		</div>		
		

	</main><!-- #main -->

<?php
get_footer();
