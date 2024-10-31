<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<?php if ( $type == 'plugin' ) : ?>
<div class="updated" id="saleassist_setup_prompt">
	<form name="saleassist_activate" action="<?php echo esc_url( Saleassist_Admin::get_page_url() ); ?>" method="POST">
		<div class="saleassist_activate">
			<div class="aa_a">A</div>
			<div class="aa_button_container">
				<div class="aa_button_border">
					<input type="submit" class="aa_button" value="<?php esc_attr_e( 'Set up your Saleassist account', 'saleassist' ); ?>" />
				</div>
			</div>
			<div class="aa_description"><?php _e('<strong>Almost done</strong> - configure Saleassist and say goodbye to spam', 'saleassist');?></div>
		</div>
	</form>
</div>
<?php elseif ( $type == 'spam-check' ) : ?>
<div class="notice notice-warning">
	<p><strong><?php esc_html_e( 'Saleassist has detected a problem.', 'saleassist' );?></strong></p>
	<p><?php esc_html_e( 'Some comments have not yet been checked for spam by Saleassist. They have been temporarily held for moderation and will automatically be rechecked later.', 'saleassist' ); ?></p>
	<?php if ( $link_text ) { ?>
		<p><?php esc_html_e($link_text); ?></p>
	<?php } ?>
</div>
<?php elseif ( $type == 'alert' ) : ?>
<div class='error'>
	<p><strong><?php printf( esc_html__( 'Saleassist Error Code: %s', 'saleassist' ), $code ); ?></strong></p>
	<p><?php esc_html_e( $msg ); ?></p>
	<p><?php

	/* translators: the placeholder is a clickable URL that leads to more information regarding an error code. */
	printf( esc_html__( 'For more information: %s' , 'saleassist'), '<a href="https://saleassist.ai/errors/' . $code . '">https://saleassist.ai/errors/' . $code . '</a>' );

	?>
	</p>
</div>
<?php elseif ( $type == 'notice' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status failed"><?php esc_html_e($notice_header); ?></h3>
	<p class="saleassist-description">
		<?php esc_html_e($notice_text); ?>
	</p>
</div>
<?php elseif ( $type == 'missing-functions' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status failed"><?php esc_html_e('Network functions are disabled.', 'saleassist'); ?></h3>
	<p class="saleassist-description"><?php printf( __('Your web host or server administrator has disabled PHP&#8217;s <code>gethostbynamel</code> function.  <strong>Saleassist cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about Saleassist&#8217;s system requirements</a>.', 'saleassist'), 'https://blog.saleassist.ai/saleassist-hosting-faq/'); ?></p>
</div>
<?php elseif ( $type == 'servers-be-down' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status failed"><?php esc_html_e("Your site can&#8217;t connect to the Saleassist servers.", 'saleassist'); ?></h3>
	<p class="saleassist-description"><?php printf( __('Your firewall may be blocking Saleassist from connecting to its API. Please contact your host and refer to <a href="%s" target="_blank">our guide about firewalls</a>.', 'saleassist'), 'https://blog.saleassist.ai/saleassist-hosting-faq/'); ?></p>
</div>
<?php elseif ( $type == 'active-dunning' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status"><?php esc_html_e("Please update your payment information.", 'saleassist'); ?></h3>
	<p class="saleassist-description"><?php printf( __('We cannot process your payment. Please <a href="%s" target="_blank">update your payment details</a>.', 'saleassist'), 'https://saleassist.ai/account/'); ?></p>
</div>
<?php elseif ( $type == 'cancelled' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status"><?php esc_html_e("Your Saleassist plan has been cancelled.", 'saleassist'); ?></h3>
	<p class="saleassist-description"><?php printf( __('Please visit your <a href="%s" target="_blank">Saleassist account page</a> to reactivate your subscription.', 'saleassist'), 'https://saleassist.ai/account/'); ?></p>
</div>
<?php elseif ( $type == 'suspended' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status failed"><?php esc_html_e("Your Saleassist subscription is suspended.", 'saleassist'); ?></h3>
	<p class="saleassist-description"><?php printf( __('Please contact <a href="%s" target="_blank">Saleassist support</a> for assistance.', 'saleassist'), 'https://saleassist.ai/contact/'); ?></p>
</div>
<?php elseif ( $type == 'active-notice' && $time_saved ) : ?>
<div class="saleassist-alert saleassist-active">
	<h3 class="saleassist-key-status"><?php esc_html_e( $time_saved ); ?></h3>
	<p class="saleassist-description"><?php printf( __('You can help us fight spam and upgrade your account by <a href="%s" target="_blank">contributing a token amount</a>.', 'saleassist'), 'https://saleassist.ai/account/upgrade/'); ?></p>
</div>
<?php elseif ( $type == 'missing' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status failed"><?php esc_html_e( 'There is a problem with your API key.', 'saleassist'); ?></h3>
	<p class="saleassist-description"><?php printf( __('Please contact <a href="%s" target="_blank">Saleassist support</a> for assistance.', 'saleassist'), 'https://saleassist.ai/contact/'); ?></p>
</div>
<?php elseif ( $type == 'no-sub' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status failed"><?php esc_html_e( 'You don&#8217;t have an Saleassist plan.', 'saleassist'); ?></h3>
	<p class="saleassist-description">
		<?php printf( __( 'In 2012, Saleassist began using subscription plans for all accounts (even free ones). A plan has not been assigned to your account, and we&#8217;d appreciate it if you&#8217;d <a href="%s" target="_blank">sign into your account</a> and choose one.', 'saleassist'), 'https://saleassist.ai/account/upgrade/' ); ?>
		<br /><br />
		<?php printf( __( 'Please <a href="%s" target="_blank">contact our support team</a> with any questions.', 'saleassist' ), 'https://saleassist.ai/contact/' ); ?>
	</p>
</div>
<?php elseif ( $type == 'new-key-valid' ) : ?>
<div class="saleassist-alert saleassist-active">
	<h3 class="saleassist-key-status"><?php esc_html_e( 'Saleassist is now enabled to your site for live chat. Happy blogging!', 'saleassist' ); ?></h3>
</div>
<?php elseif ( $type == 'new-key-invalid' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status"><?php esc_html_e( 'The Api key OR Secret key you entered is invalid. Please double-check it.' , 'saleassist'); ?></h3>
</div>
<?php elseif ( $type == 'new-key-emptydata' ) : ?>
<div class="saleassist-alert saleassist-active">
	<h3 class="saleassist-key-status"><?php esc_html_e( 'The Api key OR Secret key you entered is valid But widgets are not found in your account. Please add widgets first then re-try intigrate.' , 'saleassist'); ?></h3>
</div>
<?php elseif ( $type == 'widget-update' ) : ?>
<div class="saleassist-alert saleassist-active">
	<h3 class="saleassist-key-status"><?php esc_html_e( 'Widget successfully change for yout website.' , 'saleassist'); ?></h3>
</div>
<?php elseif ( $type == 'setting-updated' ) : ?>
<div class="saleassist-alert saleassist-active">
	<h3 class="saleassist-key-status"><?php esc_html_e( 'Saleassist Setting successfully updated.' , 'saleassist'); ?></h3>
</div>
<?php elseif ( $type == 'existing-key-invalid' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status"><?php esc_html_e( __( 'Your API key OR Secret key is no longer valid.', 'saleassist' ) ); ?></h3>
	<p class="saleassist-description">
		<?php

		echo wp_kses(
			sprintf(
				/* translators: The placeholder is a URL. */
				__( 'Please enter new API key and Secret key OR <a href="%s" target="_blank">contact Saleassist support</a>.', 'saleassist' ),
				'https://saleassist.ai/contact/'
			),
			array(
				'a' => array(
					'href' => true,
					'target' => true,
				),
			)
		);

		?>
	</p>
</div>
<?php elseif ( $type == 'new-key-failed' ) : ?>
<div class="saleassist-alert saleassist-critical">
	<h3 class="saleassist-key-status"><?php esc_html_e( 'The API key OR Secret key you entered could not be verified.' , 'saleassist'); ?></h3>
	<p class="saleassist-description">
		<?php

		echo wp_kses(
			sprintf(
				/* translators: The placeholder is a URL. */
				__( 'The connection to saleassist.ai could not be established. Please refer to <a href="%s" target="_blank">our guide about firewalls</a> and check your server configuration.', 'saleassist' ),
				'https://blog.saleassist.ai/saleassist-hosting-faq/'
			),
			array(
				'a' => array(
					'href' => true,
					'target' => true,
				),
			)
		);

		?>
	</p>
</div>
<?php endif; ?>
