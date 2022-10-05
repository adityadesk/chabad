<?php 
			$current_time = current_time( 'mysql', 1 );	
			$args = array(
		        'post_type'      => 'parsha',
				'posts_per_page' => -1,
				//'orderby' 		 => 'startDate',
				//'meta_key'       => 'startDate',
				//'order'     	=> 'DESC',
				'meta_query' => array(  
					array(
						'key' => 'newReservationsEndTime',  
						'value' =>$current_time ,  
						'compare' => '>=',  
						'type' => 'DATETIME'  
					),
					array(
							'key' => 'startDate',  
							)
					),
			);
			$events = new WP_Query( $args );
			if ( $events->have_posts() ) :
		?>
			<div class="col-max-8 col-center events-list-main">
				<h2 class="section-title">שבתות הקרובות</h2>
				<?php 
				 while ( $events->have_posts() ) : 
				 $events->the_post(); 
				 $start_day = date("d", strtotime( get_field('startDate')));
				 $start_label = date("M Y", strtotime( get_field('startDate')));
				 $end_day = date("d", strtotime( get_field('endDate')));
				 $holiday =  get_field('holiday');
				 ?>
					<?php if(empty($holiday)) : ?>
   					<div class="events-list-item">
						<p class="date-meta">
							<span><?php echo $start_day.'-'.$end_day;?></span>
							<?php echo $start_label;?>
						</p>
						<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						<a href="<?php the_permalink();?>" class="button"><?php esc_html_e('Register', 'chabad' );?></a>
					</div><!-- .events-list-item -->
                <?php endif; ?>
				<?php endwhile;?>
			</div><!-- .row -->
			<?php else:?>
			<div class="alert alert-warning"><?php _e('No events found','chabad');?></div>
		<?php endif; wp_reset_postdata();?>