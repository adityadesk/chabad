<?php
/**
 * The template for displaying all single posts
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
				<div class="col-max-10 col-center">
					<?php while ( have_posts() ) : the_post();?>
					<?php the_title();?>
					<?php endwhile ?>
					</div> 
				</div>
			</div><!-- .container -->
		</div>
		<?php 
			get_template_part( 'templates/pages/everything' );
		?>
	</main><!-- #main -->

<?php
get_footer();
