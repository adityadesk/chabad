<?php if ( have_rows( 'banner_slider' ) ) : ?>
	<section id="banner" class="home-banner">
		<div class="banner-cl">
			<?php 
				while ( have_rows( 'banner_slider' ) ) : the_row();
				$image = get_sub_field( 'image' );
				$button_text = get_sub_field( 'button_text' );
				$button_url = get_sub_field( 'button_url' );
			?>
				<div class="slick-slide">
					<div class="banner-cl-item" style="background-image: url('<?php echo esc_url( $image['url'] ); ?>')">
						<div class="banner-cl-content">
							<div class="container">
								<?php if ( $title = get_sub_field( 'title' ) ) : ?>
									<h2><?php echo esc_html( $title ); ?></h2>
								<?php endif; ?>
								<?php if ( $sub_title = get_sub_field( 'sub_title' ) ) : ?>
									<h3><?php echo esc_html( $sub_title ); ?></h3>
								<?php endif; ?>
								<?php 
									if($button_url){
										echo '<a href="'.esc_url($button_url).'" class="button">'.esc_html( $button_text ).'</a>';
									}
								?>							
							</div>
						</div>
					</div>
				</div>

			<?php endwhile; ?>
		</div>
	</section>
<?php endif; ?>