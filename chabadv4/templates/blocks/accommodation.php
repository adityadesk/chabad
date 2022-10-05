<?php

/**
 * 
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'hotel-list-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'hotel-list';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
$no_of_items_to_show = get_field( 'no_of_items_to_show' );
if(empty($no_of_items_to_show)){
    $no_of_items_to_show = 4;
}
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php 
        $args = array(
            'post_type'      => 'hotel',
            'posts_per_page' => $no_of_items_to_show,
        );
        $hotels = new WP_Query( $args );
        if ( $hotels->have_posts() ) :
    ?>
            <div class="row">
                <?php 
                    while ( $hotels->have_posts() ) : $hotels->the_post();
                     $url = get_field('url',get_the_ID());
                     $rating = get_field('ratings_out_of_5',get_the_ID());
                    $rating_val = ($rating/5)*100;
                    if($url){
                        $url = $url;
                    }else{
                        $url = "#";
                    }
                ?>
                    <div class="col-12 col-sm-6 flex-item">
                        <a class="hotel-item flex-inner" href="<?php echo $url;?>" target="_blank">
                            <div class="hotel-item-content">
                                <h3><?php the_title();?></h3>
                                <?php 
                                    //printmeta('location', '<p>%s</p>');
                                    if($rating){
                                        echo '<div class="star-rating"><div class="star-rating-in" style="width:'.$rating_val.'%;">';
                                        for ($i=0; $i < 5; $i++) { 
                                            echo get_svg_icon('img-star', '20', '18','<span>', '</span>');
                                         } 
                                        echo '</div></div>';
                                    }
                                ?>
                            </div>
                        </a><!-- .hotel-item -->
                    </div><!-- .col -->
                <?php endwhile;?>
            </div><!-- .row -->
    <?php endif; wp_reset_postdata();?>
</div>