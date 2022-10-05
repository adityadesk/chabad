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
$id = 'kosher-list-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'kosher-list';
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
    <?php if ( have_rows( 'content_block' ) ) : ?>
            <div class="row">
               <?php 
                    while ( have_rows( 'content_block' ) ) : the_row();
                    $url = get_sub_field( 'url' );
                ?>
                    <div class="col-12 col-sm-6 col-md-4">
                        <?php if($url){?>
                            <a class="news-item food-list-item" href="<?php echo esc_url( $url );?>">
                        <?php }else{?>
                            <div class="news-item food-list-item">
                        <?php }?>
                                <div class="news-item-thumb">
                                    <?php
                                    $image = get_sub_field( 'image' );
                                    if ( $image ) : ?>
                                        <img src="<?php echo esc_url( $image['sizes']['card-thumb'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
                                    <?php endif; ?>
                                </div>
                                <div class="news-item-content">
                                    <?php if ( $title = get_sub_field( 'title' ) ) : ?>
                                        <h3><?php echo esc_html( $title ); ?></h3>
                                    <?php endif; ?>
                                    <?php if ( $sub_title = get_sub_field( 'sub_title' ) ) : ?>
                                        <p><?php echo esc_html( $sub_title ); ?></p>
                                    <?php endif; ?>
                                </div>
                        
                        <?php if($url){?>
                            </a><!-- .kosher-item -->
                        <?php }else{?>
                            </div> <!-- .kosher-item -->
                        <?php }?>
                    </div><!-- .col -->
                <?php endwhile;?>
            </div><!-- .row -->
    <?php endif; ?>
</div>