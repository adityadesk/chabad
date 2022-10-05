<section id="everything" class="home-nav home-section">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-12 col-lg-5 col-xl-4 offset-xl-1 home-nav-content">
				<?php
					$in_button_text = get_field( 'in_button_text', 2 );
					printmeta('in_title', '<h2 class="section-title">%s</h2>','', 2);
					printmeta('in_description', '<p>%s</p>', '', 2);
					printmeta('in_button_url', '<a href="%s" class="button">'.esc_html( $in_button_text ).'</a>', '', 2);
				?>
			</div><!-- .col -->
			<div class="col-12 col-lg-7 col-xl-6">
				<div class="row home-nav-items">
					<?php if ( have_rows( 'icon_navigation', 2 ) ) : ?>
						<?php 
							while ( have_rows( 'icon_navigation', 2 ) ) :
							the_row(); 
							$url = get_sub_field( 'url' );
						?>
							<div class="col-6 col-sm-4 flex-item">
								<?php if ( $url) {?>
									<a class="home-nav-item flex-item" href="<?php echo esc_url( $url);?>">
								<?php }else{ ?>
									<div class="home-nav-item flex-item">
								<?php }?>
									<?php
									$icon = get_sub_field( 'icon' );
									if ( $icon ) : ?>
										<span><img src="<?php echo esc_url( $icon['url'] ); ?>" alt="<?php echo esc_attr( $icon['alt'] ); ?>" /></span>
									<?php endif; ?>
									<?php if ( $title = get_sub_field( 'title' ) ) : ?>
										<p><?php echo esc_html( $title ); ?></p>
									<?php endif; ?>
								<?php if ( $url) {?>
									</a>
								<?php }else{ ?>
									</div>
								<?php }?>
							</div>

						<?php endwhile; ?>
					<?php endif; ?>
				</div><!-- .row -->
			</div><!-- .col -->
		</div><!-- .row -->
	</div><!-- .container -->
</section>
