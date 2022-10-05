<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package chabad
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php echo get_field( "header_scripts", 'options' ); ?>
</head>

<body <?php body_class(); ?>>
<?php echo get_field( "body_tags", 'options' ); ?>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'chabad' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header-top">
			<div class="container">
				<nav class="secondary-navigation">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-3',
							'menu_id'        => 'secondary-menu',
						)
					);
					?>
				</nav><!-- #site-navigation -->
			</div><!-- .container -->
		</div><!-- .site-header-top -->
		<div class="container">
			<div class="site-header-in">
				<div class="site-branding">
					<?php
						$site_logo = get_field('site_logo', 'options');	
						$cls = '';
						if($site_logo){
							$cls = ' class="site-logo-img"';
						}					
						
					if ( is_front_page() || is_home() ) :
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo $cls;?> rel="home">

							<?php 
								if($site_logo){
									echo '<img src="'.$site_logo['url'].'" alt="'.get_bloginfo( 'name' ).'">';
								}else{
									bloginfo( 'name' ); 
								}
							?>
								
							</a></h1>
						<?php
					else :
						?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo $cls;?> rel="home">
						<?php 
								if($site_logo){
									echo '<img src="'.$site_logo['url'].'" alt="'.get_bloginfo( 'name' ).'">';
								}else{
									bloginfo( 'name' ); 
								}
							?></a></p>
					<?php endif; ?>
				</div><!-- .site-branding -->
				<nav id="site-navigation" class="main-navigation">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
					?>
				</nav><!-- #site-navigation -->
				<span class="nav-toggle">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</div><!-- .site-header-in -->
		</div><!-- .container -->
	</header><!-- #masthead -->
