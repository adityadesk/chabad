<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chabad
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
 			
					<?php 
		$holiday =  get_field('holiday');
		if(strpos($holiday,'pesah') === true) : ?>
   					<h1 class="entry-title"><?php _e('Shabbat pesah','chabad');?></h1>
                <?php endif; ?>
			<?php 
		if(strpos($holiday,'pesah') === false) : ?>
   					<h1 class="entry-title"><?php _e('Shabbat Reservation','chabad');?></h1>
                <?php endif; ?>
<!-- 		<h1 class="entry-title"><?php _e('Shabbat Reservation','chabad');?></h1> -->
		<?php 
			if(function_exists('bcn_display')){
				echo '<div class="bread-crumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
				bcn_display();
				echo '</div><!-- .bread-crumbs -->';
			}
		?>
	</header><!-- .entry-header -->
	<div class="parsha-header">
	<?php the_title( '<h3 >', '</h3>' ); ?>		
	<?php
	 $start_day = date("d", strtotime( get_field('startDate')));
	 $start_label = date("M Y", strtotime( get_field('startDate')));
	 $end_day = date("d", strtotime( get_field('endDate')));
	 $name = date("d", strtotime( get_field('parsha')));
	?>
	<p class="date-meta">
		<span><?php echo $start_day.'-'.$end_day;?></span>
		<?php echo $start_label;?>
	</p>
	</div>

	<?php chabad_post_thumbnail(); ?>

 
</article><!-- #post-<?php the_ID(); ?> -->
