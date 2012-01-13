<?php

function sdd_payments_history() {
	global $sdd_options, $sdd_payments_db_name, $wpdb;	
	$current_page = get_bloginfo('wpurl') . '/wp-admin/admin.php?page=sdd-settings';
	?>
	<div class="wrap">
		<?php 
		if (isset($_GET['p'])) $page = $_GET['p']; else $page = 1;
		$per_page = 20;
		if(isset($_GET['show']) && $_GET['show'] > 0) {
			$per_page = intval($_GET['show']);
		}
		$total_pages = 1;
		$offset = $per_page * ($page-1);
		
		$payments = sdd_get_payments($offset, $per_page);
		$payment_count = sdd_count_payments();
		$total_pages = ceil($payment_count/$per_page);
		?>
		<form id="payments-filter" action="" method="get" style="float: right; margin-bottom: 5px;">
			<input type="hidden" name="page" value="sdd-settings"/>
			<label for="sdd_show"><?php _e('Payments per page', 'sdd'); ?></label>
			<input type="text" class="regular-text" style="width:30px;" id="sdd_show" name="show" value="<?php echo isset($_GET['show']) ? $_GET['show'] : ''; ?>"/>
			<input type="submit" class="button-secondary" value="<?php _e('Show', 'sdd'); ?>"/>
		</form>
		<table class="wp-list-table widefat fixed posts sdd-payments">
			<thead>
				<tr>
					<th style="width: 40px;"><?php _e('ID', 'sdd'); ?></th>
					<th style="width: 150px;"><?php _e('Email', 'sdd'); ?></th>
					<th style="width: 240px;"><?php _e('Key', 'sdd'); ?></th>
					<th><?php _e('Product', 'sdd'); ?></th>
					<th><?php _e('Price', 'sdd'); ?></th>
					<th><?php _e('Date', 'sdd'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th style="width: 40px;"><?php _e('ID', 'sdd'); ?></th>
					<th style="width: 150px;"><?php _e('Email', 'sdd'); ?></th>
					<th style="width: 240px;"><?php _e('Key', 'sdd'); ?></th>
					<th><?php _e('Product', 'sdd'); ?></th>
					<th><?php _e('Price', 'sdd'); ?></th>
					<th><?php _e('Date', 'sdd'); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php
					if($payments) :
						$i = 0;
						foreach($payments as $payment) : ?>
							<tr class="sdd_payment <?php if(sdd_is_odd($i)) echo 'alternate'; ?>">
								<td><?php echo $payment->id; ?></td>
								<td><?php echo $payment->email; ?></td>
								<td><?php echo $payment->key; ?></td>
								<td><?php echo get_the_title($payment->post_id); ?></td>
								<td style="text-transform:uppercase;"><?php echo ( $payment->amount / 100 ) . $payment->currency; ?></td>
								<td><?php echo date(get_option('date_format'), strtotime($payment->date)); ?></td>
							</tr>
						<?php
						$i++;
						endforeach;
					else : ?>
					<tr><td colspan="6"><?php _e('No payments recorded yet', 'rcp'); ?></td></tr>
				<?php endif;?>
			</table>
			<?php if ($total_pages > 1) : ?>
				<div class="tablenav">
					<div class="tablenav-pages alignright">
						<?php
							if(isset($_GET['show']) && $_GET['show'] > 0) {
								$base = 'admin.php?page=sdd-settings&show=' . $_GET['show'] . '%_%';
							} else {
								$base = 'admin.php?page=sdd-settings%_%';
							}
							echo paginate_links( array(
								'base' => $base,
								'format' => '&p=%#%',
								'prev_text' => __('&laquo; Previous'),
								'next_text' => __('Next &raquo;'),
								'total' => $total_pages,
								'current' => $page,
								'end_size' => 1,
								'mid_size' => 5,
							));
						?>	
				    </div>
				</div><!--end .tablenav-->
			<?php endif; ?>
			
	</div><!--end wrap-->
	<?php
}

// retrieve payments from the database
function sdd_get_payments( $offset = 0, $number = 20 ) {
	global $wpdb, $sdd_payments_db_name;
	if($number > 0)
		$payments = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $sdd_payments_db_name . " ORDER BY id DESC LIMIT " . $offset . "," . $number . ";"));
	else
		$payments = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $sdd_payments_db_name . " ORDER BY id DESC;")); // this is to get all payments
	return $payments;
}

// returns the total number of payments recorded
function sdd_count_payments() {
	global $wpdb, $sdd_payments_db_name;
	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $sdd_payments_db_name . ";"));
	return $count;
}