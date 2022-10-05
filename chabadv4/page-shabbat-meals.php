<?php
/**
 * Template name: Shabbat Meals
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
					get_template_part( 'templates/parsha/listing' );
				?>	
			</div><!-- .container -->
		</div>
		<?php 
			get_template_part( 'templates/pages/everything' );
		?>
		

	</main><!-- #main -->

<?php
get_footer();
