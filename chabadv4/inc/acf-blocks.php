<?php
add_action('acf/init', 'sbc_acf_init_block_types');
function sbc_acf_init_block_types() {

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'donation_block',
            'title'             => __('Form block'),
            'description'       => __('A custom block to show gravity form.'),
            'render_template'   => 'templates/blocks/donation.php',
            'category'          => 'formatting',
            'icon'              => 'money-alt',
            'keywords'          => array( 'Form', 'Donation', 'Donate' ),
            //'enqueue_style' 	=> get_template_directory_uri() . '/assets/css/blocks/banner.css',
        ));
        acf_register_block_type(array(
            'name'              => 'synagogues',
            'title'             => __('Synagogues'),
            'description'       => __('A custom block to show Synagogues.'),
            'render_template'   => 'templates/blocks/synagogues.php',
            'category'          => 'formatting',
            'icon'              => 'location',
            'keywords'          => array( 'Synagogues', 'Synagogue' ),
            //'enqueue_style'   => get_template_directory_uri() . '/assets/css/blocks/banner.css',
        ));
        acf_register_block_type(array(
            'name'              => 'hotel',
            'title'             => __('Accommodation'),
            'description'       => __('A custom block to show Accommodation.'),
            'render_template'   => 'templates/blocks/accommodation.php',
            'category'          => 'formatting',
            'icon'              => 'buddicons-topics',
            'keywords'          => array( 'Accommodation', 'Hotel' ),
            //'enqueue_style'   => get_template_directory_uri() . '/assets/css/blocks/banner.css',
        ));
        acf_register_block_type(array(
            'name'              => 'kosher',
            'title'             => __('Kosher'),
            'description'       => __('A custom block to show Kosher.'),
            'render_template'   => 'templates/blocks/kosher.php',
            'category'          => 'formatting',
            'icon'              => 'buddicons-community',
            'keywords'          => array( 'kosher', 'Kosher' ),
            //'enqueue_style'   => get_template_directory_uri() . '/assets/css/blocks/banner.css',
        ));
        acf_register_block_type(array(
            'name'              => 'faq',
            'title'             => __('FAQ'),
            'description'       => __('A custom block to show FAQ.'),
            'render_template'   => 'templates/blocks/faq.php',
            'category'          => 'formatting',
            'icon'              => 'editor-help',
            'keywords'          => array( 'faq', 'FAQ' ),
            //'enqueue_style'   => get_template_directory_uri() . '/assets/css/blocks/banner.css',
        ));
        acf_register_block_type(array(
            'name'              => 'content-block',
            'title'             => __('Custom Content Block'),
            'description'       => __('A custom block to show FAQ.'),
            'render_template'   => 'templates/blocks/content-blocks.php',
            'category'          => 'formatting',
            'icon'              => 'grid-view',
            'keywords'          => array( 'Custom Content Block', 'Content Block' ),
            //'enqueue_style'   => get_template_directory_uri() . '/assets/css/blocks/banner.css',
        ));
        
    }
}

function editor_styles_enqueue_gutenberg() {
 	// Make sure you link this to your actual file.
	wp_register_style( 'sbc-editor-styles', get_stylesheet_directory_uri() . '/assets/css/blocks/editor-styles.css' );
	wp_enqueue_style( 'sbc-editor-styles' );
	
}
add_action( 'enqueue_block_editor_assets', 'editor_styles_enqueue_gutenberg' );
