<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chabad
 */

?>

<div class="col-12 col-sm-6 col-md-4">
	<a class="news-item" href="<?php the_permalink();?>">
		<div class="news-item-thumb">
			<?php the_post_thumbnail( 'card-thumb' );?>
		</div>
		<div class="news-item-content">
			<h3><?php the_title();?></h3>
			<p><?php echo get_the_date( 'd F Y' );?>
		</div>
	</a><!-- .news-item -->
</div><!-- .col -->
