<?php
$entry = $args['entry'];
$products = $args['products'];
$name =  rgar($entry,15) . " ".rgar($entry,16);
$email =   rgar($entry,6);
$phone =   rgar($entry,7); 
$reserved_on = date('d F Y',strtotime($entry['date_created']));
$payment_status  = $entry['payment_status'];
$payment_method  = $entry['payment_method'];
$currency = new RGCurrency( GFCommon::get_currency() );
$payment_amount =  $currency->to_money( $entry['payment_amount'] );
$transaction_type = __("One Time Donation",'chabad');
if($entry['transaction_type']==2){
	$transaction_type = __("Monthly Donation",'chabad');
}
?>
<div class="thankyou-main" id="section-to-print">
						<div class="thankyou-in">
							<h2><?php _e('Thank you for your generous donation','chabad') ;?></h2>
							<div class="row">
								<div class="col-12 col-sm-6">
									<ul class="reservation-details">
										<li>
											<label><?php _e('Reservation Number','chabad') ;?>:</label>
											<p><strong>#<?php echo $entry['id'];?></strong></p>
										</li>
										<li>
											<label><?php _e('Donated on','chabad') ;?>:</label>
											<p><?php echo $reserved_on ;?></p>
										</li>
										<li>
											<label><?php _e('Payment Status','chabad') ;?>:</label>
											<p><strong><?php echo $payment_status;?></strong>, <?php echo $payment_method;?></p>
										</li>
									</ul>
								</div><!-- .col -->
								<div class="col-12 col-sm-6">
									<ul class="reservation-details">
										<li>
											<label><?php _e('Donated by','chabad') ;?>:</label>
											<p><?php echo $name;?><br><?php echo $email;?> <br><?php echo $phone;?></p>
										</li>
									</ul>
								</div><!-- .col -->
							</div><!-- .row -->
							<table class="product-info-table">
								<tr>
										<td>
											<strong><?php echo $transaction_type;?></strong><br/>
											<span><?php echo $reserved_on;?></span>
										</td>
										<td>
										 
										</td>
										<td>
											<?php echo $payment_amount;?>
										</td>
								</tr>
								<tr>
										<td colspan="2">
											<strong><?php _e('Total','chabad') ;?></strong>
										</td>
										<td><strong><?php echo $payment_amount;?></strong></td>
								</tr> 
							</table>
							 
							<div class="thankyou-main-footer">
								<a href="javascript:void(0);" onclick="window.print()"><img src="<?php echo get_template_directory_uri();?>/assets/images/printing.svg" alt="print"> <?php _e('PRINT THIS','chabad') ;?></a>
								
							</div>
						</div><!-- .thankyou-in -->
					</div><!-- .thankyou-main -->
	
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