<?php

function sdd_metaboxes() {

	include_once('metabox/meta-box-class.php');
	$sdd_prefix = 'sdd_';

	$sdd_meta_boxes = array();

	$sdd_meta_boxes[] = array(
	    'id' => 'download-meta',                    
	    'title' => 'Digital Download',          
	    'pages' => array('post', 'page'),   
	    'context' => 'normal',       
	    'priority' => 'high',              
	    'fields' => array(                  
	        array(
	            'name' => 'Short Code',    
	            'desc' => 'Place the [purchase_download] short code in the post content to enable this digital download',    
	            'id' => '_download_instructions',            
	            'type' => 'plaintext',  
	        ),
			array(
	            'name' => 'Purchase Button',    
	            'desc' => '<br/>Enter the text to be shown on the "show purchase form" button',    
	            'id' => $sdd_prefix . 'purchase_button',            
	            'type' => 'text',  
	        ),
			array(
	            'name' => 'Price',    
	            'desc' => '<br/>Enter the download price',    
	            'id' => $sdd_prefix . 'price',            
	            'type' => 'text',  
	        ),
			array(
	            'name' => 'Download URL',    
	            'desc' => 'Enter the file download URL',    
	            'id' => $sdd_prefix . 'download_url',            
	            'type' => 'text',  
	        )
	    )
	);

	foreach ($sdd_meta_boxes as $meta_box) {
	    new sdd_meta_box($meta_box);
	}
}
add_action('init', 'sdd_metaboxes');