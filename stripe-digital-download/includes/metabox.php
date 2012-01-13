<?php


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
            'name' => 'Enable Digital Download',    
            'desc' => 'Check this to enable a digital download purchase form',    
            'id' => $sdd_prefix . 'enable_download',            
            'type' => 'checkbox',  
        ),
		array(
            'name' => 'Price',    
            'desc' => 'Enter the download price in dollars',    
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
    new bb_meta_box($meta_box);
}