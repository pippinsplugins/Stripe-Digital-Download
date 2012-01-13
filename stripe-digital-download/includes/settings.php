<?php


function sdd_options_page() {

	global $sdd_options;

	ob_start(); ?>
	<div class="wrap">
		
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".tab_content").hide(); //Hide all content
			$("h2.nav-tab-wrapper a:first").addClass("nav-tab-active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content
			$('h2.nav-tab-wrapper a').click(function(e) {				
				e.preventDefault();
				var tab = $(this).attr('href');
				//hide all 
				$( 'h2.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
				$(this).addClass( 'nav-tab-active' );
				$(".tab_content").hide();		
				$("#tab_container " + tab).fadeIn();

			});
		});
		</script>
		
		<h2 class="nav-tab-wrapper">
			<?php _e('Stripe Digital Downloads', 'sdd'); ?>
			<a href="#history" class="nav-tab"><?php _e('Payment History', 'sdd'); ?></a>
			<a href="#settings" class="nav-tab"><?php _e('Settings', 'sdd'); ?></a>
		</h2>
			
		<div id="tab_container">
			
			<div class="tab_content" style="display:none;" id="history">	
				<?php sdd_payments_history(); ?>
			</div><!--end #history-->
			
			<div class="tab_content" style="display:none;" id="settings">	
					
				<form method="post" action="options.php">
		
					<?php settings_fields('sdd_settings_group'); ?>
		
					<h4><?php _e('Test Mode', 'sdd'); ?></h4>
					<p>
						<input id="sdd_settings[test_mode]" name="sdd_settings[test_mode]" type="checkbox" value="1" <?php checked(1, $sdd_options['test_mode']); ?> />
						<label class="description" for="sdd_settings[test_mode]"><?php _e('Check this to use the plugin in test mode.', 'sdd'); ?></label>
					</p>
			
					<h4><?php _e('API Keys', 'sdd'); ?></h4>
					<p>
						<input id="sdd_settings[live_secret_key]" name="sdd_settings[live_secret_key]" type="text" class="regular-text" value="<?php echo $sdd_options['live_secret_key']; ?>"/>
						<label class="description" for="sdd_settings[live_secret_key]"><?php _e('Paste your live secret key.', 'sdd'); ?></label>
					</p>
					<p>
						<input id="sdd_settings[live_publishable_key]" name="sdd_settings[live_publishable_key]" type="text" class="regular-text" value="<?php echo $sdd_options['live_publishable_key']; ?>"/>
						<label class="description" for="sdd_settings[live_publishable_key]"><?php _e('Paste your live publishable key.', 'sdd'); ?></label>
					</p>
					<p>
						<input id="sdd_settings[test_secret_key]" name="sdd_settings[test_secret_key]" type="text" class="regular-text" value="<?php echo $sdd_options['test_secret_key']; ?>"/>
						<label class="description" for="sdd_settings[test_secret_key]"><?php _e('Paste your test secret key.', 'sdd'); ?></label>
					</p>
					<p>
						<input id="sdd_settings[test_publishable_key]" name="sdd_settings[test_publishable_key]" class="regular-text" type="text" value="<?php echo $sdd_options['test_publishable_key']; ?>"/>
						<label class="description" for="sdd_settings[test_publishable_key]"><?php _e('Paste your test publishable key.', 'sdd'); ?></label>
					</p>
			
					<h4><?php _e('Currency', 'sdd'); ?></h4>
					<p>
						<select id="sdd_settings[currency]" name="sdd_settings[currency]">
							<?php 
							$currencies = array(
								'USD' => __('US Dollars (&#36;)', 'sdd'),
								'EUR' => __('Euros (&euro;)', 'sdd'),
								'GBP' => __('Pounds Sterling (&pound;)', 'sdd'),
								'AUD' => __('Australian Dollars (&#36;)', 'sdd'),
								'BRL' => __('Brazilian Real (&#36;)', 'sdd'),
								'CAD' => __('Canadian Dollars (&#36;)', 'sdd'),
								'CZK' => __('Czech Koruna', 'sdd'),
								'DKK' => __('Danish Krone', 'sdd'),
								'HKD' => __('Hong Kong Dollar (&#36;)', 'sdd'),
								'HUF' => __('Hungarian Forint', 'sdd'),
								'ILS' => __('Israeli Shekel', 'sdd'),
								'JPY' => __('Japanese Yen (&yen;)', 'sdd'),
								'MYR' => __('Malaysian Ringgits', 'sdd'),
								'MXN' => __('Mexican Peso (&#36;)', 'sdd'),
								'NZD' => __('New Zealand Dollar (&#36;)', 'sdd'),
								'NOK' => __('Norwegian Krone', 'sdd'),
								'PHP' => __('Philippine Pesos', 'sdd'),
								'PLN' => __('Polish Zloty', 'sdd'),
								'SGD' => __('Singapore Dollar (&#36;)', 'sdd'),
								'SEK' => __('Swedish Krona', 'sdd'),
								'CHF' => __('Swiss Franc', 'sdd'),
								'TWD' => __('Taiwan New Dollars', 'sdd'),
								'THB' => __('Thai Baht', 'sdd')
							);
							foreach($currencies as $key => $currency) {
								echo '<option value="' . $key . '" ' . selected($key, $sdd_options['currency'], false) . '>' . $currency . '</option>';
							}				
							?>
						</select>
					</p>
		
					<h4><?php _e('Messages', 'sdd'); ?></h4>
					<p>
						<textarea id="sdd_settings[payment_confirmation]" name="sdd_settings[payment_confirmation]" class="large-text" rows="10" cols="50"><?php echo $sdd_options['payment_confirmation']; ?></textarea><br/>
						<label class="description" for="sdd_settings[live_secret_key]"><?php _e('Enter the message displayed after a user makes a successful purchase. HTML is accepted.', 'sdd'); ?></label>
					</p>
		
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Options', 'sdd'); ?>" />
					</p>
		
				</form>
			</div><!--end #settings-->
			
		</div><!--end #tab_container-->
		
	</div>
	<?php
	echo ob_get_clean();
}

function sdd_add_options_link() {
	add_options_page(__('Stripe Digital Download Settings', 'sdd'), __('Stripe Downloads', 'sdd'), 'manage_options', 'sdd-settings', 'sdd_options_page');
}
add_action('admin_menu', 'sdd_add_options_link');

function sdd_register_settings() {
	// creates our settings in the options table
	register_setting('sdd_settings_group', 'sdd_settings');
}
add_action('admin_init', 'sdd_register_settings');