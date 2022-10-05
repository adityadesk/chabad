<?php
/**
 * Template name: With Table of content (2 col)
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
		<div class="page-main page-main-2-col">
			<div class="container">
				<div class="col-max-10 col-center">
					<?php while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div>
			</div><!-- .container -->
		</div>
		<?php 
			get_template_part( 'templates/pages/accommodations' );
			get_template_part( 'templates/pages/everything' );
		?>
		

	</main><!-- #main -->

<?php
get_footer();
