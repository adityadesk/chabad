<?php
/**
 * chabad functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package chabad
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.2' );
}

if ( ! function_exists( 'chabad_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function chabad_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on chabad, use a find and replace
		 * to change 'chabad' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'chabad', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'card-thumb', '650', '420', 'true' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'chabad' ),
				'menu-2' => esc_html__( 'Footer', 'chabad' ),
				'menu-3' => esc_html__( 'Secondary', 'chabad' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'chabad_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'chabad_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function chabad_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'chabad_content_width', 640 );
}
add_action( 'after_setup_theme', 'chabad_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function chabad_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'chabad' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'chabad' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Sidebar', 'chabad' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Add widgets here.', 'chabad' ),
			'before_widget' => '<div id="%1$s" class="col-lg-2 col-md-4 col-12 site-footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'chabad_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function chabad_scripts() {
	wp_enqueue_style( 'chabad-style', get_stylesheet_uri(), array(), _S_VERSION );
	//wp_style_add_data( 'chabad-style', 'rtl', 'replace' );
	wp_enqueue_script( 'webfont-loader', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', array(), '1.6.26', true );
    wp_add_inline_script( 'webfont', "WebFont.load({ google: { families: ['Roboto:400,500,700'] } });" );
	wp_enqueue_style( 'chabad', get_template_directory_uri() . '/assets/css/app.css', array(), _S_VERSION);

	wp_enqueue_script( 'chabad', get_template_directory_uri() . '/assets/js/app.min.js', array( 'jquery','jquery-ui-datepicker'), _S_VERSION, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'chabad_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Custom functions.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Gravity Hooks
 */
require get_template_directory() . '/inc/gravity-hooks.php';

/**
 * Salesforce Hooks
 */
require get_template_directory() . '/inc/salesforce.php';

/**
 * Gravity Tags
 */
require get_template_directory() . '/inc/gravity-tags.php';


/**
 * ACF Blocks
 */
require get_template_directory() . '/inc/acf-blocks.php';

/**
 * Gravity Mails
 */
require get_template_directory() . '/inc/gravity-mails.php';

/**
 * Acf Hooks
 */
require get_template_directory() . '/inc/acf-hooks.php';