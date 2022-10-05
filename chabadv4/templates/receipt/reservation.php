<?php
$entry = $args['entry'];
$name =  rgar($entry, 1) . " ".rgar($entry, 2);
$email =   rgar($entry, 3);
$phone =   rgar($entry, 22);
$reserved_on = date('d F Y', strtotime($entry['date_created']));
$payment_status  = $entry['payment_status'];
$payment_method  = $entry['payment_method'];
$location_id = rgar($entry, 20);
$location = get_term($location_id, 'locations');
$location_phone= get_field('phone', $location);
$location_website = get_field('website', $location);
$events = json_decode(base64_decode(rgar($entry, '21')));
$products = array();
$currency = new RGCurrency(GFCommon::get_currency());
if($events) {
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
                 $products['products'][$event_id]['subtotal'] = $currency->to_money($subtotal); 
                 $products['products'][$event_id]['items'][$key]['qty'] = $quantity;
                 $products['products'][$event_id]['items'][$key]['label'] = ucfirst($key);
            }
        }
                
            
    }
	
    $products['total'] = $currency->to_money($entry['payment_amount']);
}
?>
<div class="thankyou-main" id="section-to-print">
                        <div class="thankyou-in">
                            <h2><?php _e('Thank you, Your Reservation is confirmed', 'chabad');?></h2>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <ul class="reservation-details">
                                        <li>
                                            <label><?php _e('Reservation Number', 'chabad');?>:</label>
                                            <p><strong>#<?php echo $entry['id'];?></strong></p>
                                        </li>
                                        <li>
                                            <label><?php _e('Reserved on', 'chabad');?>:</label>
                                            <p><?php echo $reserved_on ;?></p>
                                        </li>
                                        <li>
                                            <label><?php _e('Payment Status', 'chabad');?>:</label>
                                            <p><strong><?php echo $payment_status;?></strong>, <?php echo $payment_method;?></p>
                                        </li>
                                    </ul>
                                </div><!-- .col -->
                                <div class="col-12 col-sm-6">
                                    <ul class="reservation-details">
                                        <li>
                                            <label><?php _e('Reserved by', 'chabad');?>:</label>
                                            <p><?php echo $name;?><br><?php echo $email;?> <br><?php echo $phone;?></p>
                                        </li>
                                    </ul>
                                </div><!-- .col -->
                            </div><!-- .row -->
                            <table class="product-info-table">
                                <tbody>
                                    <?php if(isset($products['products']) && !empty($products['products'])) :
                                        foreach($products['products'] as $product):  $subtotal = $currency->to_number($product['subtotal']); if($subtotal==0) { continue;  } ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $product['title'];?></strong><br/>
                                            <span><?php echo $product['date'];?></span>
                                        </td>
                                        <td>
                                            <?php foreach($product['items'] as $item):?>
                                        
                                                <?php echo  $item['qty']. " ".$item['label'] ;?><br/>
                                        
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php echo $product['subtotal'];?>
                                        </td>
                                    </tr>
                                        <?php endforeach; ?>
                                    <tr>
                                        <td colspan="2">
                                            <strong>Total</strong>
                                        </td>
                                        <td><strong><?php echo $products['total'];?></strong></td>
                                    </tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                            <p class="reservation-address">
                                <?php _e('Location', 'chabad');?>:<br>
                                <?php echo $location->name;?><br>
                                <?php _e('Phone', 'chabad');?>: <?php echo  $location_phone?><br>
                                <?php _e('Website', 'chabad');?>:  <a href="<?php echo  $location_website;?>"><?php echo  $location_website;?></a><br>
                            </p>
                            <div class="thankyou-main-footer">
                                <a href="javascript:void(0);" onclick="window.print()"><img src="<?php echo get_template_directory_uri();?>/assets/images/printing.svg" alt="print"> <?php _e('PRINT THIS', 'chabad');?></a>
                                
                            </div>
                        </div><!-- .thankyou-in -->
                    </div><!-- .thankyou-main -->
                    <div class="thankyou-footer">
                        <div class="thankyou-footer-in">
                            <div class="thankyou-footer-in-left">
                                <h3><?php _e('Make a Donation', 'chabad');?></h3>
                                <p><?php _e('Please consider donating to help us continue with our work.', 'chabad');?></p>
                            </div>
                            <a href="<?php echo get_permalink('344');?>" class="button"><?php _e('Donate Now', 'chabad');?></a>
                        </div><!-- .thankyou-footer-in -->
                    </div><!-- .thankyou-footer -->
                    <style>
                    @media print {
                    body * {
                        visibility: hidden;
                    }
                    #section-to-print, #section-to-print * {
                        visibility: visible;
                    }
                    #section-to-print {
                        position: absolute;
                        left: 0;
                        top: 0;
                    }
                    }
                    </style>