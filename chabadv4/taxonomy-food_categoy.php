<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chabad
 */

get_header();
?>

	<main id="primary" class="site-main page-main">
		<div class="container">
			<?php if ( have_posts() ) : ?>

				<header class="entry-header text-center">
					<h1 class="entry-title"><?php single_term_title( '' );?></h1>
					<?php the_archive_description( '<div class="archive-description">', '</div>' );?>
				</header><!-- .page-header -->
				<div class="row">
					<?php while ( have_posts() ) : the_post();?>

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
				</div>

				<?php the_posts_navigation();?>

			<?php
				else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();
