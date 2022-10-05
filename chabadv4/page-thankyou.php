<?php
/**
 * Template name: Thankyou page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chabad
 */

get_header();
$entry = false;
if(isset($_GET['f'])){
	$entry_id  = chabad_decrypt($_GET['f']);
    $entry = GFAPI::get_entry($entry_id);
	$form = GFAPI::get_form($entry['form_id']);
	$products = GFCommon::get_product_fields($form, $entry,false, true);
	$data = array(
		'entry' => $entry,
		'form' => $form ,
		'products' =>$products,
	);
} 
if(!$entry){
	wp_redirect(home_url().'/thank-you-tracking');
}


?>

	<main id="primary" class="site-main">
		<div class="page-main">
			<div class="container">
				<div class="col-max-8 col-center">
					<?php 
						while ( have_posts() ) : the_post();
							get_template_part( 'template-parts/content', 'page' , );
							
							switch ( $data['entry']['form_id']) {
								case 4:
									get_template_part( 'templates/receipt/reservation',false,$data);
									break;
								case 5:
									get_template_part( 'templates/receipt/donation',false,$data);
									break;
								default:
									# code...
									break;
							}
							

							

						endwhile; // End of the loop.
					?>
					
				</div>
			</div><!-- .container -->
		</div>
	</main><!-- #main -->

<?php
get_footer();
