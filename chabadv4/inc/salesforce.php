<?php

function chabad_salesforce_settings(){
	$environment_key = get_field('salesforce_environment','options');
	$data['consumer_key'] = get_field('consumer_key_'.$environment_key,'options');
	$data['consumer_sub'] = get_field('consumer_sub_'.$environment_key,'options');
	$data['consumer_audience'] = get_field('consumer_audience_'.$environment_key,'options');
	if($environment_key=="live"){
		$data['login_base_url'] ='https://login.salesforce.com';
		$data['salesforce_pk_file'] ='live.crt';
	}else{
		$data['login_base_url'] ='https://test.salesforce.com';
		$data['salesforce_pk_file'] ='test.crt';
	}
	return $data;
}
function chabad_salesfroce_setting($key){
	$environment_key = get_field('salesforce_environment','options');
	$data = get_field('base_url_'.$environment_key,'options');
	return $data;
}
function chabad_get_saleforce_token()
{
	$settings = chabad_salesforce_settings();
 
    //Json Header
    $h = array(
    "alg" => "RS256"    
    );

    $jsonH = json_encode(($h)); 

    $header = base64url_encode($jsonH); 


    //Create JSon Claim/Payload

    $exp = strval(time() + (5 * 60));

    $c = array(
    "iss" => $settings['consumer_key'], 
    "sub" => $settings['consumer_sub'],
    "aud" => $settings['consumer_audience'], 
    "exp" => $exp
    );
 
    $jsonC = (json_encode($c)); 

    $payload = base64url_encode($jsonC);

    // LOAD YOUR PRIVATE KEY FROM A FILE - BE CAREFUL TO PROTECT IT USING

    $private_key = file_get_contents(get_template_directory() . '/certificates/' .$settings['salesforce_pk_file']);
 
    // This is where openssl_sign will put the signature
    $s = "";

    // SHA256 in this context is actually RSA with SHA256
    $algo = "SHA256";   

    // Sign the header and payload
    openssl_sign($header.'.'.$payload, $s, $private_key, $algo);

    // Base64 encode the result
    $secret = base64url_encode($s);

    $token = $header . '.' . $payload . '.' . $secret;
 
    $token_url =  $settings['login_base_url'].'/services/oauth2/token';
 
    $post_fields = array(
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $token
	); 
 
	$options = [
		'body'        => $post_fields,
		'headers'     => [
			'Content-Type' => 'application/x-www-form-urlencoded',
		],
	];
	
	$response  = wp_remote_post($token_url,$options);
	if ( !is_wp_error( $response ) ) {
		$token = json_decode($response['body'],true);
		if(isset($token['access_token'])){
			return $token['access_token'];
		}
		return false;
	}
	return false;

}
function base64url_encode($data) {
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }
  
  function base64url_decode($data) {
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
  }



add_action('acf/init', 'chabad_salesforce_acf_op_init');
function chabad_salesforce_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_page') ) {

        // Register options page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('SalesForce Settings'),
            'menu_title'    => __('SalesForce Settings'),
            'menu_slug'     => 'salesforce-chabad-settings',
            'capability'    => 'manage_options',
			'redirect'      => false,
			'parent_slug'   => 'options-general.php'
        ));
    }
}
function chabad_post_reservation($data){
	GFCommon::log_debug( ' $chabad_post_reservation => ' . print_r( $data, 1 ) );
	//error_log( 'post-data'.json_encode($data) );
	$settings = chabad_salesforce_settings();
	$endpoint_url = chabad_salesfroce_setting('base_url').'/services/apexrest/api/reserve';
	//tp(json_encode($data));
	$response = chabad_salesforce_post( $endpoint_url, $data );
	$responseBody = json_decode($response['body'],true);
	GFCommon::log_debug( ' chabad_post_reservation $response  => ' . print_r( $response, 1 ) );
	//dp($responseBody);
	//if(isset($responseBody['isSuccess']) && $responseBody['isSuccess'] == 1){
	if(isset($responseBody['isSuccess']) && $responseBody['isSuccess'] == 1){
		foreach($data['Reservers'] as $data){
			$event = chabad_get_post_id_from_meta('event', 'crm_id',$data['EventId']);
			if($event ){
				$total_booking = 0;
				foreach($data['PriceCategories'] as $PriceCategories){
					$total_booking +=$PriceCategories['Amount'];
				}
				$remainingCapacity = get_field('remainingCapacity',$event->ID );
				$remainingCapacity = $remainingCapacity-$total_booking ;
				update_post_meta($event->ID, 'remainingCapacity', $remainingCapacity);
			}
		}
		
	}
	return $response;
}
function chabad_post_feedback($data){
	GFCommon::log_debug( ' $chabad_post_feedback => ' . print_r( $data, 1 ) );
	$endpoint_url = 'https://prod2-koroka.secure.force.com/services/apexrest/feedback/v1';
	$options  = array( 
		'body' => json_encode($data),
		'timeout' 	=> 300,
		'headers' => array(
			'Content-Type' => 'application/json' ,
		),
	);
	$response = wp_remote_post( $endpoint_url, $options );
	GFCommon::log_debug( ' chabad_post_feedback $response  => ' . print_r( $response, 1 ) );
	return $response;
}
function chabad_salesforce_post($endpoint_url,$data){
	$token    = chabad_get_saleforce_token();
	$options  = array( 
		'body' => json_encode($data),
		'timeout' 	=> 300,
		'headers' => array(
			'Authorization' => 'Bearer ' . $token,
			'Content-Type' => 'application/json' ,
		),
	);
	
	if(!$token){
		return false;
	}
	
	$response = wp_remote_post( $endpoint_url, $options );
	return $response;
}
function chabad_salesforce_get($endpoint_url){
	$token    = chabad_get_saleforce_token();
	$options  = array( 
		'headers' => array(
			'Authorization' => 'Bearer ' . $token,
			'Content-Type' => 'application/json' ,
		),
	);
	
	if(!$token){
		return false;
	}
	$response = wp_remote_get( $endpoint_url, $options );
	$token = json_decode($response['body'],true);
	if ( !is_wp_error( $response ) ) {
		$data = json_decode($response['body'],true);
		if( is_array($data) ){
			return $data;
		}
		return false;
	}
}
 
function chabad_get_donation_event_list(){
	$endpoint_url = chabad_salesfroce_setting('base_url').'/services/apexrest/api/events/other';
	$response = chabad_salesforce_get($endpoint_url);
	$options[''] = 'Select Event';
	if(is_array($response)){
		foreach($response  as $data){
			$options[$data['Id']] =   $data['Name'];
		}
	}
	return $options;
}

add_action('init','chabad_auto_post_shabbat');
function chabad_auto_post_shabbat(){
	if(isset($_GET['chabadimportevents'])){
	require_once(ABSPATH . 'wp-config.php'); 
   	require_once(ABSPATH . 'wp-includes/wp-db.php'); 
   	require_once(ABSPATH . 'wp-admin/includes/taxonomy.php'); 
	$endpoint_url = chabad_salesfroce_setting('base_url').'/services/apexrest/api/events/shabbats';
	$response = chabad_salesforce_get($endpoint_url);

	if(is_array($response)){
		$post_data = array();
		foreach($response as $shabat){

				$post_data['post']['post_title']	= $shabat['name'];
				if(isset($shabat['description'])){
					$post_data['post']['post_content']	= $shabat['description'];
				}
				$post_data['post']['post_type']				= 'event';
				$post_data['post']['post_status']   		= 'publish';
				$post_data['meta']['event_date']			= isset($shabat['startDate'])?chabad_mysql_date($shabat['startDate']):'';
				$post_data['meta']['end_date']				= isset($shabat['endDate'])?chabad_mysql_date($shabat['endDate']):'';
				$post_data['meta']['parsha']				= isset($shabat['parshaName'])?$shabat['parshaName']:'';
				$post_data['meta']['crm_id']				= $shabat['shabbatId'];
				$post_data['meta']['locationIframe']		= $shabat['locationIframe'];
				$post_data['meta']['location']				= $shabat['location'];
				$post_data['meta']['newReservationsEndTime'] = $shabat['newReservationsEndTime'];
				$post_data['meta']['inStock']				= $shabat['inStock'];
				$post_data['meta']['available']				= $shabat['available'];
				$post_data['meta']['remainingCapacity']		= $shabat['remainingCapacity'];
				$post_data['meta']['shabbatStart']			= $shabat['shabbatStart'];
				$post_data['meta']['shabbatEnd']			= $shabat['shabbatEnd'];
				$post_data['meta']['name_en_US']			= $shabat['name'];
				$post_data['meta']['name_he_IL']			= $shabat['shabbatNameHebrew'];
				$pricing_field								= array();
				if(isset($shabat['pricing'])){
					foreach($shabat['pricing'] as $pricing){
						$key = strtolower($pricing['category']).'_price';
						$post_data['meta'][$key]= $pricing['price'];
						$pricing_field[] = array(
							'price_label' =>$pricing['category'],
							'price_value' =>$pricing['price']
						);
						 
					}
					$pricing_field= $pricing_field;
				}

				if(isset($shabat['location'])){
					$parsha_term = wp_create_term( $shabat['location'], 'locations');
					if($shabat['image']!==""){
						add_term_meta( $parsha_term['term_id'], 'image_link',$shabat['image'],true ); 
					}
					if($shabat['locationIframe']!==""){
						add_term_meta( $parsha_term['term_id'], 'locationIframe', $shabat['locationIframe'],true ); 
					}
				 	if(!is_wp_error($parsha_term)){
				 		$post_data['terms']['locations']		= array((int)$parsha_term['term_id']);
				 	}
				}  

				$post_data['post']['post_parent']			= 0;
				if(isset($shabat['parshaName'])){
					if(isset($shabat['description'])){
						$parsha_data['post']['post_content']	= $shabat['description'];
					}
					$parsha_data['post']['post_title']		= $shabat['parshaName'];
					$parsha_data['post']['post_type']		= 'parsha';
					$parsha_data['post']['post_status']  	= 'publish'; 
					$parsha_data['meta']['startDate']		= chabad_mysql_date($shabat['startDate']);
					$parsha_data['meta']['endDate']			= chabad_mysql_date($shabat['endDate']);
					$parsha_data['meta']['shabbatStart']	= $shabat['shabbatStart'];
					$parsha_data['meta']['shabbatEnd']		= $shabat['shabbatEnd'];
					$parsha_data['meta']['parsha_id']		= md5($shabat['parshaName'].$shabat['startDate'].$shabat['endDate']);
					$parsha_data['meta']['newReservationsEndTime'] = $shabat['newReservationsEndTime'];
					$parsha_id 								= chabad_insert_parsha($parsha_data);
					$post_data['post']['post_parent']		= $parsha_id;
					$post_data['meta']['parsha_parent']		= $parsha_id;

				}  
				$event_id = chabad_insert_event($post_data);
				if(!empty($pricing_field)){
					update_field('pricing_field',$pricing_field,$event_id);
				}
			
		}
	}
	wp_redirect( admin_url( 'edit.php?post_type=event' ) );
	exit;
	}	
}
function chabad_insert_post($post_data,$post){
	
	if(!$post){
		$post_id  = wp_insert_post($post_data['post']);
	}else{
		$post_id = $post->ID;
		$post_data['post']['ID'] = $post_id;
		$post  = wp_update_post($post_data['post']);
	}
	if(isset($post_data['meta'])){
		foreach($post_data['meta']  as $meta_key=> $meta_value){
			update_post_meta($post_id, $meta_key, $meta_value);
		}
	}
	
	if(isset($post_data['terms'])){
		foreach($post_data['terms']  as $taxonomy => $tax){
			wp_set_object_terms( $post_id, $tax, $taxonomy );
		}
	}
	return $post_id;
}
function chabad_insert_event($post_data){
	$event = chabad_get_post_id_from_meta('event', 'crm_id', $post_data['meta']['crm_id']);
	$event_id = chabad_insert_post($post_data,$event);
	return $event_id;
}
function chabad_insert_parsha($post_data){
	$parsha = chabad_get_post_id_from_meta('parsha', 'parsha_id', $post_data['meta']['parsha_id']);
	$parsha_id = chabad_insert_post($post_data,$parsha);
	return $parsha_id;
}

add_action('init','chabad_sales_force_init');
add_action( 'chabad_auto_post_shabbat_cron', 'chabad_auto_post_shabbat' );

function chabad_sales_force_init(){
	if ( ! wp_next_scheduled( 'chabad_auto_post_shabbat_cron' ) ) {
		wp_schedule_event( time(), 'hourly', 'chabad_auto_post_shabbat_cron' );
	}
}