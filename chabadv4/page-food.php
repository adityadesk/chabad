<?php
/**
 * Template name: Food page 2 col
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package chabad
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="page-main">
			<div class="container">

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
						<div class="row">						
							<div class="col-12 col-lg-7">
								<?php if(has_post_thumbnail( )){?>
									<div class="food-detail-thumb">
										<?php chabad_post_thumbnail(); ?>
									</div><!-- .food-detail-thumb -->
								<?php }?>
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
							<div class="col-12 col-lg-5">
								<div class="food-detail-info">
									<?php 
										printmeta('map_embed', '<div class="map-embed">%s</div>');
										printmeta('contact_info', '<div class="food-detail-info-item"><h3>'.esc_html__( 'Contact Info', 'chabad' ).'</h3>%s</div>');
										printmeta('timing_', '<div class="food-detail-info-item"><h3>'.esc_html__( 'Opening Hours', 'chabad' ).'</h3>%s</div>');
									?>								
								</div><!-- .food-detail-info -->
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

				<?php endwhile; ?>
			</div><!-- .container -->
		</div>
		<?php 
			get_template_part( 'templates/pages/everything' );
		?>
	</main><!-- #main -->

<?php
get_footer();
