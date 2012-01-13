<?php

function sdd_register_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('stripe', 'https://js.stripe.com/v1/');
}
add_action('wp_enqueue_scripts', 'sdd_register_scripts');