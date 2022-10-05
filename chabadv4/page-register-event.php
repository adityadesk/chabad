<?php
/**
 * Template name: Register Event
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

if(!isset($_GET['parsha_id']) || !isset($_GET['location_id'])){
	wp_redirect(home_url());
}
$parsha_id = intval($_GET['parsha_id']);
$location_id = intval($_GET['location_id']);
$parsha = get_post($parsha_id);
$location = get_term($location_id ,'locations');
$data['parsha_id'] = $parsha_id;
$data['location_id'] = $location_id;
$event_parent = chabad_get_event($data);

if(!$parsha  || !$location){
	wp_redirect(home_url());
}
get_header();
?>

	<main id="primary" class="site-main">
		<div class="page-main">
			<div class="container">
				<div class="col-max-10 col-center">
					<?php while ( have_posts() ) : the_post();?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<h3 class="entry-title"><?php the_title(); ?> </h3>
								<?php 
									/*if(function_exists('bcn_display')){
										echo '<div class="bread-crumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
										bcn_display();
										echo '</div><!-- .bread-crumbs -->';
									}*/
								?>
								<div class="bread-crumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
									<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="<?php bloginfo( 'name' ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="home"><span property="name"><?php echo get_the_title(2);?></span></a><meta property="position" content="1"></span> / <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="<?php echo get_the_title(48);?>" href="<?php echo get_permalink( 48 );?>"><span><?php echo get_the_title(48);?></span></a></span><meta property="url" content="<?php echo get_permalink( 48 );?>"><meta property="position" content="2"></span> / <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="<?php echo get_the_title($parsha);?>" href="<?php echo get_the_title($parsha);?>"><span><?php echo get_the_title($parsha);?></span></a></span><meta property="url" content="<?php echo get_permalink( $parsha );?>"><meta property="position" content="3"></span>
									 / <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item"><?php echo get_the_title($post->ID);?></span><meta property="url" content="<?php echo get_permalink( $post->ID );?>"><meta property="position" content="4"></span>
								</div>
							</header><!-- .entry-header -->
							<div class="row event-detail-main">						
								<div class="col-12 order-md-2 order-lg-1 col-lg-6">									
									<div class="entry-content">
										<?php 
										$start_day = date("d", strtotime( get_field('startDate',$parsha_id)));
										$start_label = date("M Y", strtotime( get_field('startDate',$parsha_id)));
										$end_day = date("d", strtotime( get_field('endDate',$parsha_id)));
										$is_live = chabad_is_event_live($parsha_id);
										?>
										<h3 class="date"><?php echo $parsha->post_title.'<br/>'.$start_day.'-'.$end_day;?> <?php echo $start_label;?></h3>
										<p>
										<strong><?php _e('Candle Light','chabad') ;?>  : </strong><?php echo get_field('shabbatStart',$parsha_id);?></br>
										<strong><?php _e('Shabbat Ends','chabad') ;?>  : </strong><?php echo get_field('shabbatEnd',$parsha_id);?></br>
										<strong><?php _e('Reserve By','chabad') ;?>  : </strong><?php echo chabad_local_time(get_field('newReservationsEndTime',$parsha_id));?>
										</p>
										<?php
					
										if($event_parent){
											echo apply_filters('the_content',$event_parent->post_content);
										}else{
											echo apply_filters('the_content',$parsha->post_content);
										}

										wp_link_pages(
											array(
												'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'chabad' ),
												'after'  => '</div>',
											)
										);
										?>
										<div class="event-detail-main-location">
											<h3>Location</h3>
											<div class="event-detail-main-location-info">
												<?php 
												$image = get_field('image_link',$location);
								
												if($image ){
													printf('<img src="%s" alt="">',$image);
												}
												
												?>
												<div class="event-detail-main-location-info-in">
													<p><?php echo $location->name;?></p>
													<?php 
													$address= get_field('address',$location);
													if($address){
														printf('<p class="lcn">%s</p>',$address);
													}
													?>
													
												</div><!-- .event-detail-main-location-info-in -->
											</div><!-- .event-detail-main-location-info -->
										</div><!-- .event-detail-main-location -->
										<div class="event-detail-main-location-contact">
											<?php
											$phone= get_field('phone',$location);
											$website = get_field('website',$location);
											if($phone){
												printf('<p>%s: <span>%s</span></p>',__('Phone','chabad'),$phone);
											}
											if($website){
												printf('<p>%s: <span><a href="%2$s" target="_blank">%2$s</a></span></p>',__('Website','chabad'),$website);
											}
											?>
										</div>
										<?php 
										$locationIframe = get_field('locationIframe',$location);
										if($locationIframe ){
											printf('<div class="map-embed">%s</div>',$locationIframe);
										}
										
										?>
									</div><!-- .entry-content -->
								</div><!-- .col -->
								<div class="col-12 order-md-1 order-lg-2 col-lg-6">
									<div class="event-detail-register">ccc
										<?php 
										$data['parsha_id'] = $parsha_id;
										$data['location_id'] = $location_id;
										$data['event_mapping'] =  base64_encode(chabad_get_event_mapping($data));
										$data['event_date'] =  get_field('startDate',$parsha_id);
										$data['feedback_date'] =  date('Y-m-d', strtotime($data['event_date'] . ' +1 day'));
										$dynamic_data = build_query($data);
										if($is_live){
											echo do_shortcode( '[gravityform id="4" field_values="'.$dynamic_data.'" title="false" description="false" ajax="true"]' ); 
											// [gravityform id="4" title="true"]
										}else{
											echo '<div style="border: 1px solid #e6db55; background-color: #FFFFE0; padding: 10px;">'.__('Event Registration Closed','chabad').'</div>';
										}
										
										?>
									</div><!-- .event-detail-register -->
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
				</div>
			</div><!-- .container -->
		</div>
		<?php 
			get_template_part( 'templates/pages/everything' );
		?>
	</main><!-- #main -->

<?php
get_footer();
