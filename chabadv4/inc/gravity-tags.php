<?php
add_filter( 'gform_replace_merge_tags', 'chabad_replace_reservation_notes', 10, 7 );
function chabad_replace_reservation_notes( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
	 
    if(!isset($form['id']) ||  $form['id']!=4){
		return $text;
	}
	
    $parsha_id = rgar($entry,19);
	$location_id = rgar($entry,20);
	$parsha = get_post($parsha_id);
	$data['parsha_id'] = $parsha_id;
	$data['location_id'] = $location_id;
	$event_parent = chabad_get_event($data);
	if($event_parent){
		$merge_tags['{event_desc}'] = $event_parent->post_content;
	}else{
		$merge_tags['{event_desc}'] = $parsha->post_content;
	}
	$merge_tags['{event_name}'] = $parsha->post_title;
	$merge_tags['{parsha}'] 	= $parsha->post_title;
	$merge_tags['{shabbat_start}'] 	= get_field('shabbatStart',$parsha->ID);
	$merge_tags['{shabbat_end}'] 	= get_field('shabbatEnd',$parsha->ID);
	$location = get_term($location_id,'locations');
	$merge_tags['{location_iframe}'] = get_field('locationIframe',$location);
	$merge_tags['{location_name}'] = $location->name;
	$merge_tags['{event_date}'] = get_field('startDate',$parsha);
	 
	$merge_tags['{order_summary}'] = chabad_get_order_summery($entry);

	$text = str_replace(
		array_keys($merge_tags), 
		array_values($merge_tags), 
		$text
	  ); 
	return $text;
}

function chabad_get_order_summery( $entry ){
	$events = json_decode(base64_decode(rgar($entry, '21')));
	$currency = new RGCurrency( GFCommon::get_currency() );
	$overview= "";
	$products = array();
	if($events){
		$total = 0;
		foreach($events as $event_id => $event){
			$products['products'][$event_id]['title'] = get_the_title($event_id);
			$products['products'][$event_id]['number'] = $number;
			$event_date = get_field('event_date',$event_id);
			$the_date = date( 'd F Y', strtotime($event_date));
			$products['products'][$event_id]['date'] = $the_date;
			if(!empty($event)){
				$subtotal = 0;
				foreach($event as $key => $item){
					 $quantity = intval(rgar($entry, $item.'.3'));
					 $price = $currency->to_number(rgar($entry, $item.'.2'));
					 $total += $price*$quantity;
					 $subtotal+=$price*$quantity;
					 $products['products'][$event_id]['price'] = $subtotal;
					 $products['products'][$event_id]['subtotal'] = $currency->to_money($subtotal); 
					 $products['products'][$event_id]['items'][$key]['qty'] = $quantity;
					 $products['products'][$event_id]['items'][$key]['label'] = ucfirst($key);
				}
			}
		}
		$products['total'] = $currency->to_money( $entry['payment_amount']);
	}
	ob_start();
	?>
	<table style="border-top: 3px solid #891738; border-bottom: 3px solid #891738; margin-bottom: 40px; width: 100%;">
								<tbody>
									<?php if(isset($products['products']) && !empty($products['products'])):
									foreach($products['products'] as $product): if($product['price']==0){continue;}  ?>
									<tr>
										<td style="border:none; border-bottom: 1px solid #dcdcdc; padding: 20px 0;">
											<strong><?php echo $product['title'];?></strong><br/>
											<span><?php echo $product['date'];?></span>
										</td>
										<td style="border:none; border-bottom: 1px solid #dcdcdc; padding: 20px 0;">
										<?php foreach($product['items'] as $item):?>
										
											<?php echo  $item['qty']. " ".$item['label'] ;?><br/>
										
										<?php endforeach; ?>
										</td>
										<td style="border:none; border-bottom: 1px solid #dcdcdc; padding: 20px 0;">
											<?php echo $product['subtotal'];?>
										</td>
									</tr>
									<?php endforeach; ?>
									<tr>
										<td style="padding: 20px 0; border:none;" colspan="2">
											<strong>Total</strong>
										</td>
										<td style="padding: 20px 0; border:none;"><strong><?php echo $products['total'];?></strong></td>
									</tr>
									<?php endif;?>
								</tbody>
							</table>
	<?php
	$overview =  ob_get_clean();
	return $overview;
	 
}