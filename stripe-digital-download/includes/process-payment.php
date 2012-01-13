<?php

function sdd_process_payment() {
	if(isset($_POST['action']) && $_POST['action'] == 'sdd_payment') {
	
		global $sdd_options;
	
		require_once(SDD_PLUGIN_DIR . '/stripe/Stripe.php');
		$token = $_POST['stripeToken'];

		if(isset($sdd_options['test_mode']) && $sdd_options['test_mode']) {
			$secret_key = $sdd_options['test_secret_key'];
		} else {
			$secret_key = $sdd_options['live_secret_key'];
		}
		
		try {
			Stripe::setApiKey($secret_key);
			$charge = Stripe_Charge::create(array(
				"amount" => $_POST['price'], // amount in cents, again
				"currency" => strtolower($sdd_options['currency']),
				"card" => $token,
				"description" => $_POST['email'])
			);
			
			$payment_data = array(
				'amount' => $_POST['price'],
				'currency' => strtolower($sdd_options['currency']),
				'token' => $token,
				'date' => date('Y-m-d H:i:s'),
				'email' => $_POST['email'],
				'post_id' => $_POST['post_id'],
				'key' => strtolower(md5(uniqid()))
			);
			
			// record this payment
			sdd_insert_payment($payment_data);
				
			// send email with secure download link
			sdd_email_download_link($payment_data);
				
			// redirect on successful payment
			$redirect = add_query_arg('payment_status', 'paid', $_POST['redirect']);
			
		} catch (Exception $e) {
			// redirect on failed payment
			$redirect = add_query_arg('payment_status', 'failed', $_POST['redirect']);
		}
		wp_redirect($redirect); exit;
	}
}
add_action('init', 'sdd_process_payment');

function sdd_insert_payment($payment_data = array()) {
	global $wpdb, $sdd_payments_db_name;

	if(empty($payment_data))
		return false;
	
	$wpdb->insert( 
		$sdd_payments_db_name, 
		array( 
			'amount' => $payment_data['amount'], 
			'date' => $payment_data['date'], 
			'email' => $payment_data['email'],
			'key' => $payment_data['key'],
			'token' => $payment_data['token'],
			'currency' => $payment_data['currency'],
			'post_id' => $payment_data['post_id']
		), 
		array( 
			'%d', 
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		) 
	);
	
	// if insert was succesful, return the payment ID
	if($wpdb->insert_id)
		return $wpdb->insert_id;
	
	// return false if payment wasn't recorded
	return false;
}