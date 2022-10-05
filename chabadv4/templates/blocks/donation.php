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
$id = 'donation-block-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'feedback-form';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
$title = get_field( 'title' );
$crm_event_id = get_field( 'chabad_crm_event_id' );
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="feedback-form-in">
        <?php 
            if($title){ echo '<h2>'.$title.'</h2>';}
            echo do_shortcode( '[gravityform id="5" field_values="crm_event_id='.$crm_event_id.'" title="false" description="false" ajax="true" tabindex="49"]', $ignore_html );
        ?>
    </div>
</div>