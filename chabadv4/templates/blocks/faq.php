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
$id = 'faq-list-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'faq-list';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
$title = get_field( 'title' );
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php if($title){ echo '<h2>'.$title.'</h2>';}?>
    <?php 
        $args = array(
            'post_type'      => 'faq',
            'posts_per_page' => -1,
        );
        $faq = new WP_Query( $args );
        if ( $faq->have_posts() ) :
    ?>

    <div class="faq-items">
            <?php 
                while ( $faq->have_posts() ) : $faq->the_post();
            ?>
                <div class="faq-item">
                    <div class="faq-item-title">
                        <h3><?php the_title();?></h3>
                    </div><!-- .faq-item-title -->
                    <div class="faq-item-content">
                        <?php the_content();?>
                    </div><!-- .faq-item-content -->
                </div><!-- .faq-item -->
            <?php endwhile;?>
        </div><!-- .faq-items -->
    <?php else:?>
        <h3 class="text-center"><?php esc_html_e( 'Nothing Found', 'chabad' ); ?></h3>
    <?php endif; wp_reset_postdata();?>
</div>
