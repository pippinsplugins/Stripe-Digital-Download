<?php

// email the download link and payment confirmation to the buyer
function sdd_email_download_link($payment_data) {
	global $sdd_options;

	/* 
	the payment_data array looks like this:
	$payment_data = array(
		'amount' => $_POST['price'],
		'currency' => strtolower($sdd_options['currency']),
		'token' => $token,
		'date' => date('Y-m-d H:i:s'),
		'email' => $_POST['email'],
		'post_id' => $_POST['post_id'],
		'key' => strtolower(md5(uniqid()))			
	);
	*/
	
	$message = __('Hello', 'sdd') . "\n\n" . __('Thank you for your purchase!', 'sdd') . "\n\n";
	$message .= __('You bought', 'sdd') . ": " . get_the_title($payment_data['post_id']) . "\n\n";
	$message .= __('Click or copy the following link to download your purchase', 'sdd') . ":\n\n";
	$message .= add_query_arg('download', urlencode($payment_data['key']), add_query_arg('email', urlencode($payment_data['email']), home_url() ) );
	$message .= "\n\n" . __('Thank you', 'sdd');
	
	
	wp_mail( $payment_data['email'], __('Purchase Confirmation', 'sdd'), $message);
	
	/* send an email notification to the admin */
	$admin_email = get_option('admin_email');
	$admin_message = __('Hello', 'sdd') . "\n\n" . __('A download purchase has been made') . ".\n\n";
	$admin_message .= __('Product sold', 'sdd') . ': ' . get_the_title($payment_data['post_id']) . "\n\n";
	$admin_message .= __('Price', 'sdd') . " " . $payment_data['amount'] / 100 . $sdd_options['currency'] . "\n\n";
	$admin_message .= __('Thank you', 'sdd');
	wp_mail( $admin_email, __('New download purchase', 'sdd'), $admin_message );
	
}