<?php

function sdd_payment_form() {

	global $sdd_load_scripts, $sdd_options;
	
	$sdd_load_scripts = true;

	$publishable_key = NULL;

	if(isset($sdd_options['test_mode']) && $sdd_options['test_mode']) {
		$publishable_key = $sdd_options['test_publishable_key'];
	} else {
		$publishable_key = $sdd_options['live_publishable_key'];
	}
	
	ob_start();
	if(isset($_GET['payment_status']) && $_GET['payment_status'] == 'paid') { 
	
		echo '<div id="payment-confirmation">' . $sdd_options['payment_confirmation'] . '</div>';
		
	} else { ?>
		<script type="text/javascript">
			// this identifies your website in the createToken call below
			Stripe.setPublishableKey('<?php echo $publishable_key; ?>');

			function stripeResponseHandler(status, response) {
				if (response.error) {
					// re-enable the submit button
					jQuery('.submit-button').removeAttr("disabled");
					
					jQuery('#sdd_loading').hide();
					
					// show the errors on the form
					jQuery(".payment-errors").html(response.error.message);
					
				} else {
					var form$ = jQuery("#payment-form");
					// token contains id, last4, and card type
					var token = response['id'];
					var price = response['amount'];
					// insert the token into the form so it gets submitted to the server
					form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
					form$.append("<input type='hidden' name='stripePrice' value='" + price + "' />");
					
					// and submit
					form$.get(0).submit();
				
				}
			}
			jQuery(document).ready(function($) {
				$("#payment-form").submit(function(event) {
					// disable the submit button to prevent repeated clicks
					$('.submit-button').attr("disabled", "disabled");
					$('#sdd_loading').show();
					var chargeAmount = $('#sdd_price').val(); //amount you want to charge, in cents. 1000 = $10.00, 2000 = $20.00 ...
					// createToken returns immediately - the supplied callback submits the form if there are no errors
					Stripe.createToken({
						number: $('.card-number').val(),
						cvc: $('.card-cvc').val(),
						exp_month: $('.card-expiry-month').val(),
						exp_year: $('.card-expiry-year').val()
					}, chargeAmount, stripeResponseHandler);
					return false; // submit from callback
				});
				$('.purchase-download').click(function() {			
					$('#payment-form').slideToggle();
					$('.purchase-download').toggle();
					return false;
				});
			});
			
		</script>
		<?php if(!isset($publishable_key)) { ?>
		<p style="color: red;"><?php _e('You must enter your Stripe.com publishable keys in the settings page before you can accept payments', 'sdd'); ?>
		<?php } else { 
		$button_text = get_post_meta(get_the_ID(), 'sdd_purchase_button', true);	
		?>
		<button class="purchase-download tb-button blue tb-button-small"><?php echo strlen(trim($button_text)) > 0 ? $button_text : __('Purchase', 'sdd'); ?></button>
		<button class="purchase-download tb-button blue tb-button-small" style="display: none;"><?php _e('Hide Form', 'sdd'); ?></button>
		<span class="payment-errors"></span>
		<form action="" method="POST" id="payment-form" style="display:none;">
			<p><?php _e('Please enter your credit/debit card information to purchase this item. All transactions are made securely and no card information is stored.', 'sdd'); ?></p>
			<p><?php _e('All transactions are processed through <a href="http://stripe.com">Stripe.com</a>', 'sdd'); ?></p>
			<div class="form-row">
				<label><?php _e('Email', 'sdd'); ?></label>
				<input type="text" size="20" autocomplete="off" name="email" class="email" />
			</div>
			<div class="form-row">
				<label><?php _e('Card Number', 'sdd'); ?></label>
				<input type="text" size="20" autocomplete="off" class="card-number" />
			</div>
			<div class="form-row">
				<label><?php _e('CVC', 'sdd'); ?></label>
				<input type="text" size="4" autocomplete="off" class="card-cvc" />
			</div>
			<div class="form-row">
				<label><?php _e('Expiration', 'sdd'); ?> (MM/YYYY)</label>
				<input type="text" size="2" class="card-expiry-month"/>
				<span> / </span>
				<input type="text" size="4" class="card-expiry-year"/>
			</div>
			<input type="hidden" name="price" id="sdd_price" value="<?php echo get_post_meta(get_the_ID(), 'sdd_price', true) * 100; ?>"/>
			<input type="hidden" name="post_id" id="sdd_post_id" value="<?php echo get_the_ID(); ?>"/>
			<input type="hidden" name="action" value="sdd_payment"/>
			<input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>#payment-confirmation"/>
			<button type="submit" class="submit-button"><?php _e('Purchase', 'sdd'); ?></button>
			<img src="<?php echo SDD_PLUGIN_URL; ?>/includes/images/loading.gif" style="display:none;" id="sdd_loading"/>
		</form>
		<?php
		}
	}
	return ob_get_clean();
}
add_shortcode('purchase_download', 'sdd_payment_form');