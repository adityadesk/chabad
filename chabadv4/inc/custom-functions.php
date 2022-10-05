<?php
/*
* Register Theme Settings Page.
*/
function chabad_acf_options_page()
{
    // Check function exists.
    if(! function_exists('acf_add_options_page') ) {
        return;
    }

    // register options page.
    $option_page = acf_add_options_page(
        array(
        'page_title'      => __('Theme Settings', 'chabad'),
        'menu_title'      => __('Theme Settings', 'chabad'),
        'menu_slug'       => 'theme-settings',
        'capability'      => 'manage_options',
        'redirect'        => false,
        'updated_message' => __('Theme Options Updated', 'chabad'),
        )
    );
}
add_action('acf/init', 'chabad_acf_options_page');


/**
 * Print the meta data after checking it's existence (ACF)
 *
 * @param  String $metakey  Post meta key
 * @param  Sting  $template Template
 * @return String           Meta value in template
 */
function printmeta( $metakey, $template, $return = false, $post_id = null )
{
    if (! $post_id ) {
        global $post;
        $post_id = $post->ID;
    }

    if (get_field($metakey, $post_id) ) {
        $value = get_field($metakey, $post_id);
        if ($return ) {
            return sprintf($template, $value);
        } else {
            echo sprintf($template, $value);
        }
    }
}

function printimage( $metakey, $size = 'thumbnail', $return = false, $post_id = null )
{
    if (! $post_id ) {
        global $post;
        $post_id = $post->ID;
    }

    if (get_field($metakey, $post_id) ) {
        $image   = get_field($metakey, $post_id);
        $imgesrc = sprintf('<img src="%s" alt="%s">', $image['sizes'][ $size ], $image['title']);
        if ($return ) {
            return $imgesrc;
        } else {
            echo $imgesrc;
        }
    }
}

/**
 * Test Printing
 */
function tp( $data )
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
/**
 * Test Printing
 */
function dp( $data )
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

/*
 * Custom Mime Types for uploads.
 */
function chabad_custom_mime_types( $mimes )
{
    $mimes['svg'] = 'image/svg+xml';
    if (isset($mimes['exe']) ) {
        unset($mimes['exe']);
    }
    return $mimes;
}
add_filter('upload_mimes', 'chabad_custom_mime_types');


/**
 * Customize the form HTML content.
 *
 * @param  string $form_string The form markup.
 * @return string
 */
function chabad_gform_get_form_filter( $form_string )
{
    $form_string = str_replace(array( " type='text/javascript'", "This iframe contains the logic required to handle Ajax powered Gravity Forms." ), "", $form_string);
    return $form_string;
}
add_filter('gform_get_form_filter', 'chabad_gform_get_form_filter');


/**
 * Get svg sprite icon
 *
 * @param  string $id     SVG HTML ID
 * @param  string $width  SVG icon width
 * @param  string $height SVG icon height
 * @param  string $before before wraping element
 * @param  string $after  after wraping element
 * @return string
 */
function get_svg_icon( $id, $width, $height, $before = '', $after = '' )
{
    $icon = '';
    if ($id ) {
        $icon = $before . '<svg width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin"><use xlink:href="#' . $id . '"></use></svg>' . $after;
    }
    return $icon;
}


function get_inline_svg_form_url($url)
{
    $svg_file = file_get_contents($url);

    $find_string   = '<svg';
    $position = strpos($svg_file, $find_string);
    $svg_file_new = '<span class="wp-svg-img">'.substr($svg_file, $position).'</span>';

    return $svg_file_new;
}

function the_get_inline_svg_form_url($url)
{

    $svg_file_new = get_inline_svg_form_url($url);

    echo $svg_file_new;

}

function get_image_svg($meta = array(), $alt = '', $class = '')
{
    if($meta) {
        $img = '';
        if($alt) {
            $alt_text = $alt;
        }else{
            $alt_text = $meta['title'];
        }
        if($class) {
            $class = ' class="'.$class.'"';
        }
        if($meta['mime_type'] == 'image/svg+xml') {
            $img = get_inline_svg_form_url($meta['url']);
        }else{
            $img = '<img'.$class.' src="'.$meta['url'].'" alt="'.$alt_text.'" width="'.$meta['width'].'" height="'.$meta['height'].'">';
        }
    }
    return $img;
}

function the_get_image_svg($meta = array(), $alt = '', $class = '')
{
    echo get_image_svg($meta, $alt, $class);
}

function get_shabbat_timing($date=false)
{
    $lattitude = 25.2048;
    $longitude = 55.270;
    if(!$date) {
        $date = date('Y-m-d');
    }
    $solstice = strtotime($date);
    $sunset = date_sunset($solstice, SUNFUNCS_RET_STRING, $lattitude, $longitude, 91.6, +4);
    $candlelight =  date('H:i', strtotime($sunset.'-18 minutes'));
    $time['sunset']= $sunset;
    $time['candlelight']= $candlelight;
    return $time;
}
function get_shabbat_api_timing()
{
    delete_transient('chabad_shabbat_timing') ;
	if ( false === ( $shabbat_timing = get_transient( 'chabad_shabbat_timing' ) ) ) {
		$current_day = date('w');
		if($current_day==5){
			$friday = strtotime('now');
		}else{
			$friday = strtotime('next friday');
		}
		$date =  date('d/m/Y',$friday);
		//$data =  wp_remote_get('http://www.eitan.ws/Json3/Chabad_Dubai.aspx/?EngState=False&Pass=rfc-TEMP-		345&MyDate='.$date.'&Longitude=55.2708&Latitude=25.2048&TimeZone=4&Region=Asia/Dubai');
		
		$data = wp_remote_get('http://www.eitan.ws/Json3/ChazonNet7.aspx/?Filename=Chabad_Dubai&CandleLight=18&LocName=Dubai&Longitude=55.278&Latitude=25.2&Region=Else&TimeZone=4&Pass=sfsfsf12654&MyDate='.$date);
	
		
		if( ! is_wp_error($data) ) {
			$data = json_decode($data['body'], true);
			foreach ($data['DailyZmanim'] as $key => $value) {
				switch ($value[0]) {
					case 'candle_lighting':
						$dt = new DateTime($value[1]);
						$shabbat_timing['candle_light']  = $dt->format('h:i');
						break;			
					case 'shabbat_ends':
						$dt = new DateTime($value[1]);
						$shabbat_timing['shabbat_ends']  = $dt->format('h:i');
						break;			
					case 'parsha':
						$shabbat_timing['parsha'] = $value[1];
						break;
				}
			}
 
		}
		set_transient( 'chabad_shabbat_timing', $shabbat_timing,  HOUR_IN_SECONDS );
	}
	return $shabbat_timing;
}
// Taxonomy term filter

function get_tex_term_filter($taxonomy,$selected=array()){
    if($taxonomy){
        $output = '';
        $tax_terms = get_terms($taxonomy);
        if($tax_terms){
            $color = $bg_color = '';            
            $output .= '<ul class="widget-filter-check widget-filter-check-'.$taxonomy.'">';
            foreach ($tax_terms as $key => $tax_term) {
                if($taxonomy == 'product_cat'){
                $color = get_field('color', 'product_cat_' . $tax_term->term_id);               
                    if ( $color ) {
                        $bg_color = colourBrightness($color, 0.25);
                        $color = ' style="--color:'.$color.'; --bgcolor: '.$bg_color.';"';
                    }
                }
                $selecteditem = "";
                if(in_array($tax_term->term_id,$selected)){
                    $selecteditem = "checked";
                }
                $output .= '<li>';
                $output .= '<label for="'.$tax_term->slug.'">';
                $output .= '<input type="checkbox" class="chabadautosubmit" name="'.$taxonomy.'[]" id="'.$tax_term->slug.'" value="'.$tax_term->term_id.'" '.$selecteditem.'>';
                if($taxonomy == 'product_cat'){
                    $output .= '<span><span class="cat-label" '.$color.'>'.$tax_term->name.'</span></span>';
                }else{
                    $output .= '<span>'.$tax_term->name.'</span>';
                }
                $output .= '</label>';
                $output .= '</li>';
            }
            $output .= '</ul>';
        }
    }
    return $output;
}
function chabad_get_post_id_from_meta($post_type,$metakey,$metavalue){
	$args = array (
		'post_type'              => $post_type,
		'post_status'            => array( 'any' ),
		'meta_query'             => array(
			array(
				'key'       => $metakey,
				'value'     => $metavalue,
			),
		),
	);
 
	// The Query
	$query = new WP_Query( $args );
	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			global $post;
			$post_data = $post;
		}
	} else {
		$post_data =  false;
	}
	wp_reset_postdata();
	return $post_data;
}

add_filter('views_edit-event','chabad_import_crm_button');

function chabad_import_crm_button($views){
    $views['import'] = '<a href="'.admin_url('?chabadimportevents').'" class="primary">Sync Data</a>';
    return $views;
}
function chabad_mysql_date( $date ){
	$date = DateTime::createFromFormat('d/m/Y', $date);
	return $date->format('Y-m-d');
}
function chabad_get_event_mapping($field_values){
	$events = chabad_get_event_data($field_values);
	$json = array();
	if($events){
		$start_id = 2000;
		foreach($events as $key => $event){
			if(isset($event['pricing'])){
				foreach($event['pricing'] as $k => $pricing){
					$json[$key][strtolower($pricing['key'])] = $start_id++;
				}
			}
		}
	}
	return json_encode($json);
}
function chabad_get_event_data( $field_values ){
	//$field_values['parsha_id']=1458;
	//$field_values['location_id'] =22;
	if(!isset($field_values['parsha_id']) || !isset($field_values['location_id'])){
		return false;
	}
	$parsha_id =  $field_values['parsha_id'];
	$location_id =  $field_values['location_id'];
	$location = get_term($location_id,'locations');
	$args = array(
		'post_type' => 'event',
		'post_status'=> 'publish',
		'post_parent'=>$parsha_id,
	);
	if($location){
		$args['tax_query'] = array(
				array(
					'taxonomy' => 'locations',
					'field'    => 'id',
					'terms'    => $location->term_id
				)
		);
	}
	$events = get_posts($args);
	if($events){
		$event_data = array();
		foreach($events as $event){
			$event_data[$event->ID] = array(
				//'title' => '<span class="do_not_translate">'.chabad_lang_meta('name',$event->ID).'</span>',
				'title' => get_field('name_en_US',$event->ID),
				'id'	=> $event->ID,
				'pricing' =>chabad_event_pricing($event->ID),
				'crm_id' => get_field('crm_id', $event->ID),
			);
		}
	
		return $event_data;
	}
	return false;
}
function chabad_event_pricing($eventID){
	$pricing = get_field('pricing_field',$eventID);
	$price_field = array();
	if($pricing){
		foreach( $pricing as $price ) {
			$price_field[]=array(
				'label'=>  $price['price_label'],
				'key'  => strtolower( $price['price_label']),
				'price' => $price['price_value'],
			);
		}
	}
	return $price_field;
}
function chabad_get_location($event_id){
	$locations = get_the_terms($event_id,'locations');
	if($locations){
		$location = array_pop($locations);
		return $location;
	}else{
		return "";
	}
	
}

function  chabad_parasha_group_by_filter($groupby){
	global $wpdb;

	return $wpdb->postmeta . '.meta_value ';
 }
function chabad_remainingCapacity($post_id){
	$remainingCapacity['number'] = get_field('remainingCapacity',$post_id);
	if($remainingCapacity['number'] <= 0){
		$remainingCapacity['html'] = sprintf('<span class="remaining booked">%s</span>',__('All seats booked','chabad'));
	}else{
		if($remainingCapacity['number'] < 33){
			$remainingCapacity['html'] = sprintf('<span class="remaining">%s %s</span>',$remainingCapacity['number'],__('Seats Remaining','chabad'));
		}else{
			$remainingCapacity['html'] = "";
		}
		
		
	}
	return $remainingCapacity;
}
function chabad_local_time($date){
	if($date==""){
		return "";
	}
	$dt = new DateTime($date);
	$tz = new DateTimeZone('Asia/Dubai'); // or whatever zone you're after

	$dt->setTimezone($tz);
	return $dt->format('Y-m-d H:i:s');

}

if ( !function_exists( 'chabad_is_rest' ) ) {
    /**
     * Checks if the current request is a WP REST API request.
     *
     * Case #1: After WP_REST_Request initialisation
     * Case #2: Support "plain" permalink settings and check if `rest_route` starts with `/`
     * Case #3: It can happen that WP_Rewrite is not yet initialized,
     *          so do this (wp-settings.php)
     * Case #4: URL Path begins with wp-json/ (your REST prefix)
     *          Also supports WP installations in subfolders
     *
     * @returns boolean
     * @author matzeeable
     */
    function chabad_is_rest() {
        if (defined('REST_REQUEST') && REST_REQUEST // (#1)
                || isset($_GET['rest_route']) // (#2)
                        && strpos( $_GET['rest_route'], '/', 0 ) === 0)
                return true;

        // (#3)
        global $wp_rewrite;
        if ($wp_rewrite === null) $wp_rewrite = new WP_Rewrite();
            
        // (#4)
        $rest_url = wp_parse_url( trailingslashit( rest_url( ) ) );
        $current_url = wp_parse_url( add_query_arg( array( ) ) );
        return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
    }
}
//Ip redirect
add_action('init','chabad_lang_redirect');
function chabad_lang_redirect(){
	if ( chabad_is_rest() ) { 
		return false;
	   } 
   
	if(!isset($_SERVER['HTTP_STRIPE_SIGNATURE'])){
		if(!isset($_COOKIE['chabadlanguageredirect'])){
			$ip_info = chabad_ip_info();
			if(isset($ip_info['country_code']) && $ip_info['country_code']!="IL"){
				setcookie( 'chabadlanguageredirect', 1, time() + 3600 * 24 * 100, COOKIEPATH, COOKIE_DOMAIN ,false);
				$current_url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$localized = str_replace(site_url(),site_url('/en'),$current_url);
				wp_redirect( $localized );
			}else{
				setcookie( 'chabadlanguageredirect', 0, 60 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
			}
		}
	}
	
}
function chabad_ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

function chabad_lang_meta($meta,$post_id){
	return get_field($meta.'_'.get_locale(),$post_id);
}
function  chabad_is_event_live($parsha_id){
	$closing_time = chabad_local_time(get_field('newReservationsEndTime',$parsha_id));
    //sprintf('<!-- %s -->',print_r(new DateTime("now", new DateTimeZone('Asia/Dubai'))));
    //sprintf('<!-- %s -->',print_r( new DateTime($closing_time,new DateTimeZone('Asia/Dubai'))));
	if (new DateTime("now", new DateTimeZone('Asia/Dubai')) > new DateTime($closing_time,new DateTimeZone('Asia/Dubai'))) {
		return false;
	}  
	return true;
}

add_filter( 'trp_no_translate_selectors', 'trpc_no_stranslate_selectors', 10, 2);
function trpc_no_stranslate_selectors($selectors_array, $language){
    $selectors_array[] = '.do_not_translate';
    return $selectors_array;
}

function chabad_acf_save_post( $post_id ) {
    
    // Get the selected post status
    $value = get_field('parsha_parent', $post_id);

	if($value){
		// Update current post
		$my_post = array(
			'ID'           => $post_id,
			'post_parent'   => $value,
		  );
	  
		  // Remove the action to avoid infinite loop
		  remove_action('acf/save_post', 'my_acf_save_post', 20);
		  
		  // Update the post into the database
		  wp_update_post( $my_post );
		  
		  // Add the action back
		  add_action('acf/save_post', 'my_acf_save_post', 20);
	}
    
    
}

add_action('acf/save_post', 'chabad_acf_save_post', 20);


function chabad_crypt( $string, $action = 'e' ) {
	// you may change these values to your own
	$secret_key = 'chabad';
	$secret_iv  = 'asad221alsmmuenj';

	$output         = false;
	$encrypt_method = 'AES-256-CBC';
	$key            = hash( 'sha256', $secret_key );
	$iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	if ( $action == 'e' ) {
		$output = base64url_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	} elseif ( $action == 'd' ) {
		$output = openssl_decrypt( base64url_decode( $string ), $encrypt_method, $key, 0, $iv );
	}

	return $output;
}
function chabad_encrypt( $string ) {
	return chabad_crypt( $string, $action = 'e' );
}
function chabad_decrypt( $string ) {
	return chabad_crypt( $string, $action = 'd' );
}

function chabad_get_event( $field_values ){
	//$field_values['parsha_id']=1458;
	//$field_values['location_id'] =22;
	if(!isset($field_values['parsha_id']) || !isset($field_values['location_id'])){
		return false;
	}
	$parsha_id =  $field_values['parsha_id'];
	$location_id =  $field_values['location_id'];
	$location = get_term($location_id,'locations');
	$args = array(
		'post_type' => 'event',
		'post_status'=> 'publish',
		'post_parent'=>$parsha_id,
	);
	if($location){
		$args['tax_query'] = array(
				array(
					'taxonomy' => 'locations',
					'field'    => 'id',
					'terms'    => $location->term_id
				)
		);
	}
	$events = get_posts($args);
    
	if($events){
		$event_data = array();
		foreach($events as $event){
            return $event;
		}
	}
	return false;
}