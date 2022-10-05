<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package chabad
 */

?>


	<footer id="colophon"  class="site-footer">
		<div class="container">
			<div class="site-footer-top">
				<div class="row">
					<div class="col-lg-3 col-12 site-footer-widget site-footer-address">
						<div class="site-footer-logo">
							<?php 
								$site_logo = get_field('site_logo', 'options');
								if($site_logo){
							?>
								<img src="<?php echo $site_logo['url'];?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php }else{?>
								<img src="<?php echo get_template_directory_uri();?>/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?>" width="250" height="76">
							<?php }?>

							<?php printmeta('address', '<p>%s</p>', '', 'option');?>
						</div>
					</div><!-- .col -->
					<?php if (  is_active_sidebar( 'sidebar-2' ) ) { dynamic_sidebar( 'sidebar-2' );}?>
					<div class="col-lg-3 col-12 site-footer-widget">
						<?php 
						$shabaat = get_shabbat_api_timing();
						if ( have_rows( 'shabbat_timing', 'options' ) ) : ?>
							<?php while ( have_rows( 'shabbat_timing', 'options' ) ) :
								the_row(); ?>
								<a href="<?php echo get_permalink(48);?>" id="shabbat-times-widget" class="site-footer-timing">
									<?php if ( $title = get_sub_field( 'title', 'options' ) ) : ?>
										<h3><?php echo esc_html( $title.' '.$shabaat['parsha'] ); ?></h3>
                                        <!--<h3><?php echo esc_html( $shabaat['parsha'] ); ?></h3> -->
									<?php endif; ?>
									<ul>
										<li>
 											<?php if ( $label_1 = get_sub_field( 'label_1', 'options' ) ) : ?>
												<?php echo esc_html( $label_1 ); ?>
											<?php endif; ?>

											<span><?php echo esc_html( $shabaat['candle_light'] ); ?></span>
										</li>

										<li>
											<?php if ( $label_2 = get_sub_field( 'label_2', 'options' ) ) : ?>
												<?php echo esc_html( $label_2 ); ?>
											<?php endif; ?>

											<span><?php echo esc_html( $shabaat['shabbat_ends'] ); ?></span>
										</li>
									</ul>
								</a>

							<?php endwhile; ?>
						<?php endif; ?>
					</div><!-- .col -->
				</div><!-- .row -->
			</div><!-- .footer-top -->
			<div class="site-footer-bottom">
				<div class="row">
					<div class="col-12 col-md-6">
						<nav class="footer-navigation">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-2',
									'menu_id'        => 'footer-menu',
								)
							);
							?>
						</nav><!-- #site-navigation -->
					</div><!-- .col -->
					<div class="col-12 col-md-6">
						<p><?php printmeta('copy_text', '<p>%s</p>', '', 'option');?></p>
					</div><!-- .col -->
				</div><!-- .row -->
			</div><!-- .footer-bottom -->
		</div><!-- .container -->
		<?php printmeta('whatsapp_url', '<a href="%s" target="_blank" class="whatsapp-btn"></a>', '', 'option');?>
		<div class="mobile-navigation">
			<nav class="mobile-navigation-in">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'mobile-menu',
					)
				);
				?>
			</nav>
		</div>
		<svg display="none">
			<defs>
				<g id="img-star">
					<path xmlns="http://www.w3.org/2000/svg" fill="#000000" d="M15.2652273,12.2414959 L19.8207341,7.81825074 L13.5252645,6.90699838 C13.3568972,6.88285925 13.2115007,6.77750203 13.1360257,6.62537514 L10.3207341,0.943123296 L7.50544249,6.62537514 C7.43021999,6.77750203 7.28457094,6.88285925 7.11620372,6.90699838 L0.820734127,7.81825074 L5.37624093,12.2414959 C5.49790962,12.3599285 5.55344303,12.5304111 5.5246666,12.6973735 L4.44959025,18.9431233 L10.0801735,15.994377 C10.2308709,15.915422 10.4105973,15.915422 10.5612947,15.994377 L16.191878,18.9431233 L15.1165492,12.697625 C15.0877728,12.5304111 15.1433062,12.3599285 15.2652273,12.2414959 Z" transform="translate(0 -1)"/>
				</g>
				<g id="img-search">
					<path xmlns="http://www.w3.org/2000/svg" fill="#000000" d="M13.9334816,11.86797 C16.382282,8.47786053 15.5632852,3.78355374 12.1053636,1.38334932 C8.64744203,-1.0168551 3.85923569,-0.214492957 1.41102025,3.17619014 C-1.03719519,6.56629974 -0.218783336,11.2600329 3.23972321,13.6602373 C5.7078285,15.3733582 8.97094588,15.5052691 11.5741857,13.998044 L17.2305286,19.510198 C17.8412664,20.1405026 18.8574074,20.1657377 19.5003198,19.5669771 C20.1432323,18.9687899 20.1689722,17.972576 19.5588197,17.3422715 C19.5395147,17.3221981 19.5207948,17.3038453 19.5003198,17.2849188 L13.9334816,11.86797 Z M7.66757095,12.381849 C4.93504654,12.3824225 2.71966012,10.2122018 2.71790498,7.5332639 C2.71732013,4.85432599 4.93095152,2.68238467 7.66406089,2.68123724 C10.3930752,2.68009057 12.6072917,4.84687019 12.6125641,7.52236688 C12.6172367,10.2018783 10.4047753,12.3772607 7.67108086,12.381849 C7.66991094,12.381849 7.66932583,12.381849 7.66757095,12.381849 Z"/>
				</g>
			</defs>
		</svg>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
