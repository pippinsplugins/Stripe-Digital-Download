<?php

function sdd_process_download() {
	if(isset($_GET['download']) && isset($_GET['email'])) {
		$key = urldecode($_GET['download']);
		$email = urldecode($_GET['email']);
		
		$payment = sdd_verify_purchase($key, $email);
		
		if($payment) {
			// payment has been verified, setup the download
			$download_url = get_post_meta($payment->post_id, 'sdd_download_url', true);
			
			$file_extension = sdd_get_file_extension($download_url);

            switch ($file_extension) :
                case "pdf": $ctype = "application/pdf"; break;
                case "exe": $ctype = "application/octet-stream"; break;
                case "zip": $ctype = "application/zip"; break;
                case "doc": $ctype = "application/msword"; break;
                case "xls": $ctype = "application/vnd.ms-excel"; break;
                case "ppt": $ctype = "application/vnd.ms-powerpoint"; break;
                case "gif": $ctype = "image/gif"; break;
                case "png": $ctype = "image/png"; break;
                case "jpe": case "jpeg": case "jpg": $ctype="image/jpg"; break;
                default: $ctype = "application/force-download";
            endswitch;
			
			@ini_set('zlib.output_compression', 'Off');
			@set_time_limit(0);
			@session_start();					
			@session_cache_limiter('none');		
			@set_magic_quotes_runtime(0);
			@ob_end_clean();
			@session_write_close();
			
			
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Robots: none");
			header("Content-Type: " . $ctype . "");
			header("Content-Description: File Transfer");	
		    header("Content-Disposition: attachment; filename=\"" . $download_url . "\";");
			header("Content-Transfer-Encoding: binary");
			header('Location: ' . $download_url);
			exit;
			
		} else {
			wp_die(__('You do not have permission to download this file', 'sdd'), __('Purchase Verification Failed', 'sdd'));
		}
		exit;
	}
}
add_action('init', 'sdd_process_download', 100);

function sdd_verify_purchase($key, $email) {
	global $wpdb, $sdd_payments_db_name;
	
	$payment = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM " . $sdd_payments_db_name . " WHERE `key`='" . $key . "' AND `email`='" . $email . "';"));
	if($payment)
		return $payment[0]; // payment has been verified
	
	// payment not verified
	return false;
}

function sdd_get_file_extension($str)
{
   $parts = explode('.', $str);
   return end($parts);
}