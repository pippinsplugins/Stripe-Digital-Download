<?php

function sdd_load_scripts() {
	if(is_singular()) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('stripe', 'https://js.stripe.com/v1/');
	}
}
add_action('wp_enqueue_scripts', 'sdd_load_scripts');

function sdd_register_styles() {
	wp_register_style('sdd-forms', SDD_PLUGIN_URL . '/includes/css/forms.css');
}
add_action('init', 'sdd_register_styles');

function sdd_print_styles() {
	global $sdd_load_scripts;
	
	if(!$sdd_load_scripts)
		return;
		
	wp_print_styles('sdd-forms');
}
add_action('wp_footer', 'sdd_print_styles');