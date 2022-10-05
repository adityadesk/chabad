<?php
/**
 * Template Name: Home
 *
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chabad
 */

get_header();
?>

	<main id="primary" class="site-main">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'templates/home/banner' );
				get_template_part( 'templates/home/everything' );
				get_template_part( 'templates/home/kosher' );
				get_template_part( 'templates/home/accommodations' );
				get_template_part( 'templates/home/news' );

			endwhile; // End of the loop.
			?>
	</main><!-- #main -->

<?php
get_footer();