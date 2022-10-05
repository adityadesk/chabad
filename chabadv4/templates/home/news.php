<?php 
	$args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
	);
	$food = new WP_Query( $args );
	if ( $food->have_posts() ) :
?>
	<section id="news" class="home-news home-section home-section-alt">
		<div class="container">
			<?php printmeta('n_title', '<h2 class="section-title">%s</h2>');?>
			<div class="row home-section-list">
				<?php while ( $food->have_posts() ) : $food->the_post(); ?>
					<div class="col-12 col-sm-6 col-md-4">
						<a class="news-item" href="<?php the_permalink();?>">
							<div class="news-item-thumb">
								<?php the_post_thumbnail( 'card-thumb' );?>
							</div>
							<div class="news-item-content">
								<h3><?php the_title();?></h3>
								<p><?php echo get_the_date( 'd F Y' );?></p>
							</div>
						</a><!-- .news-item -->
					</div><!-- .col -->
				<?php endwhile;?>
			</div><!-- .row -->
			<div class="home-section-btn text-center">
				<?php
					$n_button_text = get_field( 'n_button_text' );
					printmeta('n_button_url', '<a href="%s" class="button">'.esc_html( $n_button_text ).'</a>');
				?>
			</div>
		</div><!-- .container -->
	</section>
<?php endif; wp_reset_postdata();?>