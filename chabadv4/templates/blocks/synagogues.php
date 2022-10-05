<?php

/**
 * Banner Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'synagogues-list-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'synagogues-list';
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
            'post_type'      => 'synagogue',
            'posts_per_page' => $no_of_items_to_show,
        );
        $synagogues = new WP_Query( $args );
        if ( $synagogues->have_posts() ) :
    ?>
            <div class="row">
                <?php 
                    while ( $synagogues->have_posts() ) : $synagogues->the_post();
                ?>
                    <div class="col-12 col-sm-6 flex-item">
                        <a class="hotel-item hotel-item-alt flex-inner" href="<?php the_permalink();?>">
                            <div class="hotel-item-content">
                                <h3><?php the_title();?></h3>
                                <?php printmeta('tagline', '<p>%s</p>'); ?>
                            </div>
                        </a><!-- .hotel-item -->
                    </div><!-- .col -->
                <?php endwhile;?>
            </div><!-- .row -->
    <?php endif; wp_reset_postdata();?>
</div>