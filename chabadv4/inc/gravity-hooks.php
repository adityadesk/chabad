<?php
// Defualt Quantity

add_filter('gform_field_value_default_quantity', 'chabad_set_default_quantity');
function chabad_set_default_quantity()
{
    return 0; // change this number to whatever you want the default quantity to be
}

add_filter('gform_field_value_default_donation', 'chabad_set_default_donation');
function chabad_set_default_donation()
{
    $default = get_field('default_feedback_donation_amount', 'options');
    return $default; // change this number to whatever you want the default quantity to be
}

add_action('gform_post_payment_completed', 'chabad_gravity_after_payment', 10, 2);

add_action('gform_after_submission', 'chabad_gravity_after_submission', 10, 2);

add_action( 'gform_post_subscription_started', 'chabad_gravity_after_payment', 10, 2 );


function chabad_gravity_after_payment($entry, $action)
{
	GFCommon::log_debug( ' $form_id after  => ' . print_r( $entry, 1 ) );
    switch ($entry['form_id']) {
    case 4:
        chabad_gravity_reservation($entry, $action);
        break;
    case 5:
        chabad_gravity_donation($entry, $action);
        break;
    default:
        // code...
        break;
    }
}
function chabad_gravity_after_submission( $entry, $form )
{
	//dp($entry);
    switch ($entry['form_id']) {
    case 3:
        chabad_gravity_feedback($entry, $form);
        break;  
    default:
        // code...
        break;
    }
}
function chabad_gravity_feedback($entry)
{
	
    $data =  array(
		"requestType" => 'feedback',
		"eventId"=>rgar( $entry, '20' ),
		"donateAmount" => intval(rgar( $entry, 'payment_amount' )),
		"phoneNumber" => rgar($entry, '7'),
		"customerFirstName" =>rgar($entry, '15'),
		"customerLastName" =>rgar($entry, '16'),
		"customerEmail"=>rgar($entry, '6'),
		"feedbackRating"=>rgar($entry, '9'),
		"feedbackMessage"=>rgar($entry, '8'),
		"leadSource" => "chabad.ae",
	);

	//as_enqueue_async_action( 'chabad_async_donation_push', array('data' => $data) );
	chabad_post_feedback($data);
     
}
function chabad_gravity_donation($entry, $action)
{
	GFCommon::log_debug( ' $heressss => ' . print_r( $action, 1 ) );
    $data = array(
		"requestType" => 'donation',
		"eventId"=>rgar($entry, '19','7014W000000tu43QAA'),
		"donateAmount" => intval(rgar( $entry, 'payment_amount' )),
		"phoneNumber" => rgar($entry, '7'),
		"customerFirstName" => rgar($entry, '15'),
		"customerLastName" => rgar($entry, '16'),
		"customerEmail"=>rgar($entry, '6'),
		"leadSource" => "chabad.ae",
    );
	GFCommon::log_debug( ' $action => ' . print_r( $action, 1 ) );
	GFCommon::log_debug( ' $data => ' . print_r( $data, 1 ) );
    if($action['is_success']==1) {
		chabad_post_feedback($data);
		//as_enqueue_async_action( 'chabad_async_donation_push', array('data' => $data) );
    }
}

add_action( 'chabad_async_donation_push', function( $data ) {
	chabad_post_feedback($data);
}, 10, 1 );

function chabad_gravity_reservation($entry, $action)
{

	$events = json_decode(base64_decode(rgar($entry, '21')));
	$data['Reservers'] = array();
	$basedata = array(
		"Name" => rgar($entry, '1'),
		"FamilyName"=>rgar($entry, '2'),
		"Email" => rgar($entry, '3'),
		"Phone" => rgar($entry, '22'),
		"Category" => "Visitor",
		"Country" => "Ukraine",
		//"ContactPriceCategory" => "Adult",
		"TotalPrice" => 0,
	);
	
	if($events){
		$category = false;
		
		foreach($events as $event_id => $event){
			$crm_id = get_field('crm_id',$event_id);
			$total = 0;
			$PriceCategories = array();
			if(!empty($event)){
				foreach($event as $key => $item){
					$price  = intval(rgar($entry, $item.'.2'));
					$quantity = intval(rgar($entry, $item.'.3'));
					$total +=$price;
					$PriceCategories[] = array(
						'PriceCategory' => ucfirst($key),
						'Amount'		=> $quantity,
					);
				}
			}
		
			$event_data =array(
				"EventId" =>$crm_id,
			);
			$event_data['PriceCategories'] = $PriceCategories;
			$event_data['TotalPrice'] = $total;
			$basedata = array_merge($basedata,$event_data);


			// [ ! ] custom code by Alex
			if ( $PriceCategories[0]['Amount'] != 0 || $PriceCategories[1]['Amount'] != 0 ) {
                $data['Reservers'][] = $basedata;
            }
			
			// [ ! ] commented by Alex
			// $data['Reservers'][]=$basedata;


			//break;
		}
		if(!$category){
			//$basedata['ContactPriceCategory'] = "Kids";
		}
	}
    if($action['is_success']==1) {
		chabad_post_reservation($data);
		//as_enqueue_async_action( 'chabad_async_reservation_push', array('data' => $data) );
	 }  
}
 
add_action( 'chabad_async_reservation_push', function( $data ) {
	chabad_post_reservation($data);
}, 10, 1 );



add_action( 'init', function( $data ) {
	if(isset($_GET['asdnkajsndasd'])){
		$lids = array(299);
		foreach($lids as $lid){
			$entry = GFAPI::get_entry($lid);
			$action['is_success'] = 1;
			chabad_gravity_reservation($entry,$action);
		}
		
	}
});


add_filter('gform_pre_render_4', 'chabad_populate_validate_content');
add_filter('gform_pre_validation_4', 'chabad_populate_html_content');
add_filter('gform_pre_submission_filter_4', 'chabad_populate_html_content');
add_filter('gform_admin_pre_render_4', 'chabad_populate_html_content');

function chabad_populate_validate_content($form){
	if(isset($form['fields'])){
		foreach($form['fields'] as $gsfield){
			if($gsfield->id == 9999){
				return $form;
			}
		}
	} 
	return chabad_populate_html_content($form);
}
 
function chabad_populate_html_content($form)
{

	$field_values = array();

	$hidden_base = array(
		'type' => 'hidden',
		'id' => 9999,
		'label' => 'gcheck',
	);
	
	
	if(isset($_GET['page']) && $_GET['page']=='gf_entries' ){
		$lid = $_GET['lid'];
		$entry = GFAPI::get_entry( $lid );
		$field_values['parsha_id'] =rgar($entry,19);
		$field_values['location_id'] =rgar($entry,20);
	}
	//$field_values['parsha_id'] =1465;
	//$field_values['location_id'] =21;
	if(isset($_GET['parsha_id'])){
		$field_values['parsha_id'] = $_GET['parsha_id'];
	}
	if(isset($_GET['location_id'])){
		$field_values['location_id'] = $_GET['location_id'];
	}
 
	$events        = chabad_get_event_data($field_values);

	$html_base = array(
		'type' => 'html',
		'id' => 123,
		'label' => '',
	);
	$product_base = array( 
		'id' => 123,
		'label' => 'Adult',
		'type' => 'product',
		'basePrice' => 25,
		'inputType' => 'singleproduct',
		'cssClass' => 'chabad-qty',
		'defaultValue'=>0,
		'allowsPrepopulate' => true,
		'value'=>0,
	);
	if($events){
		$gfield[] = GF_Fields::create($hidden_base);
		$form['fields'] = array_merge($gfield,$form['fields']);

		$json = array();
		$start_id = 2000;
		foreach($events as $key => $event){
			$html_base['id']   	=	$html_base['id']+$key;
			$html_base['label'] =	$event['title'];
			$remainingCapacity  = 	chabad_remainingCapacity($key);
			$html_base['cssClass'] =	'seats-remaining';
			
			$html_base['content'] =	sprintf('<h3 class="do_not_translate">%s</h3>%s',$event['title'],$remainingCapacity['html']) ;

			$field[] = GF_Fields::create($html_base);
			
			if(isset($event['pricing'])){
				
				foreach($event['pricing'] as $k => $pricing){
					$product_base['id'] =  $start_id++;
					$json[$key][strtolower($pricing['key'])] = $product_base['id'];
					$product_base['inputs'] = array(
						array(
									'id'=>$product_base['id']+.1,
									'label'=>'Name',
									'name'=>'',
						),
						array(
									'id'=>$product_base['id']+.2,
									'label'=>'Price',
									'name'=>'friday_adult_price'
						),
						array(
									'id'=>$product_base['id']+.3,
									'label'=>'Quantity',
									'name'=>'default_quantity',
					)
					);
					$product_base['basePrice'] = $pricing['price'];
					$product_base['label'] = $pricing['label'];
					$product_base['adminLabel'] = $event['title'].' '. $pricing['label'];
		 
					if($remainingCapacity['number'] <= 0){
						$product_base['cssClass'] .=	' all-booked';
					}
					$field[] = GF_Fields::create($product_base);
				}
			}
			
		}
 
		$form['fields'] = array_merge($field,$form['fields']);
	}

	//dp($form);
    return $form;
    
     
}

add_filter( 'gform_validation_4', 'chabad_fail_for_total_zero' );
function chabad_fail_for_total_zero( $validation_result ) {
    GFCommon::log_debug( __METHOD__ . '(): running.' );
    $form = $validation_result['form'];
 
    // Change 3 to the id number of your Total field.
    if ( empty( rgpost( 'input_8' ) ) ) {
        GFCommon::log_debug( __METHOD__ . '(): Total field is empty.' );
        // Set the form validation to false.
        $validation_result['is_valid'] = false;
        // Find Total field, set failed validation and message.
        foreach ( $form['fields'] as &$field ) {
            if ( $field->type == 'total' ) {
                $field->failed_validation = true;
                $field->validation_message = __('You must select at least one product!','chabad');
                break;
            }
        }
    }
 
    // Assign modified $form object back to the validation result.
    $validation_result['form'] = $form;
    return $validation_result;
}

add_filter('gform_custom_merge_tags', 'custom_merge_tags', 10, 4);
function custom_merge_tags( $merge_tags, $form_id, $fields, $element_id ) {
	
	if($form_id==4){
		$merge_tags[] = array('label' => 'Event Name', 'tag' => '{event_name}');
		$merge_tags[] = array('label' => 'Event Description', 'tag' => '{event_desc}');
		$merge_tags[] = array('label' => 'Location Iframe', 'tag' => '{location_iframe}');
		$merge_tags[] = array('label' => 'Location Name', 'tag' => '{location_name}');
		$merge_tags[] = array('label' => 'Event Date', 'tag' => '{event_date}');
		$merge_tags[] = array('label' => 'Order Summery', 'tag' => '{order_summary}');
		$merge_tags[] = array('label' => 'Parsha', 'tag' => '{parsha}');
		$merge_tags[] = array('label' => 'Shabbat Start', 'tag' => '{shabbat_start}');
		$merge_tags[] = array('label' => 'Shabbat End', 'tag' => '{shabbat_end}');
	}
    return $merge_tags;
}

add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
function custom_confirmation( $confirmation, $form, $entry, $ajax ) {
    if( in_array ( $form['id'], array(4,5) ) ) {
        $confirmation = array( 'redirect' => site_url('thank-you?f='.chabad_crypt( $entry['id'] ) ) );
    }  
    return $confirmation;
}

add_filter( 'gform_stripe_customer_id', 'create_stripe_customer', 10, 4 );
function create_stripe_customer( $customer_id, $feed, $entry, $form ) {
    if ( empty( $customer_id ) ) {
        gf_stripe()->log_debug( 'Feed ' . json_encode($feed) );
		gf_stripe()->log_debug( ': ' . $customer_id );
        if ( rgars( $feed, 'meta/transactionType' ) === 'product' ) {
            $customer_params['email'] = gf_stripe()->get_field_value( $form, $entry, rgar( $feed['meta'], 'customerInformation_email' ) );
        }
 
        $customer    = gf_stripe()->create_customer( $customer_params, $feed, $entry, $form );
        $customer_id = $customer->id;
        gf_stripe()->log_debug( 'gform_stripe_customer_id: Returning Customer ID: ' . $customer_id );
		gf_stripe()->log_debug( 'Customer : Returning Customer ID: ' . json_encode($customer_params) );
    }
 
    return $customer_id;
}

add_filter( 'gform_gravityformsstripe_feed_settings_fields', function( $feed_settings_fields, $addon ) {
 
    $feed_settings_fields = $addon->replace_field( 'billingInformation', array(
        array(
			'name'       => 'customerInformation',
			'label'      => esc_html__( 'Customer Information', 'gravityformsstripe' ),
			'type'       => 'field_map',
			'dependency' => array(
				'field'  => 'transactionType',
				'values' => array( 'product' ),
			),
			'field_map'  => array(
				array(
					'name'       => 'email',
					'label'      => esc_html__( 'Email', 'gravityformsstripe' ),
					'required'   => true,
					'field_type' => array( 'email' ),
					'tooltip'    => '<h6>' . esc_html__( 'Email', 'gravityformsstripe' ) . '</h6>' . esc_html__( 'You can specify an email field and it will be sent to the Stripe Checkout screen as the customer\'s email.', 'gravityformsstripe' ),
				),
			),
		)
    ), $feed_settings_fields );
 
    return $feed_settings_fields;
}, 10, 2 );
