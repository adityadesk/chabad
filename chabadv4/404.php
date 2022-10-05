<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package chabad
 */

get_header();
?>

	<main id="primary" class="site-main page-main">

		<section class="error-404 not-found">
			<header class="banner">
				<div class="container">
					<h1 class="page-title"><span>404</span></h1>
				</div>
			</header><!-- .page-header -->

			<div class="page-content text-center">
				<div class="container">
					<h2 class="section-title">
						<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'chabad' ); ?>
					</h2>
					<ul class="list-inline">
						<li class="list-inline-item">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button"><?php esc_html_e( 'Back to home', 'chabad' ); ?></a>
						</li>
					</ul>
				</div><!-- .container -->
			</div><!-- .page-content -->
			
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
