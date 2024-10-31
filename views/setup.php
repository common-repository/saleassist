<div class="saleassist-setup-instructions">
	<p><?php esc_html_e( 'Set up your Saleassist account to enable live chatting on this site.', 'saleassist' ); ?></p>
	<form action="<?php echo esc_url( Saleassist_Admin::get_page_url() ); ?>" method="post">
		<?php wp_nonce_field( Saleassist_Admin::NONCE ) ?>
		<input type="hidden" name="action" value="temp-setup">
		<p style="width: 100%; flex-wrap: nowrap; box-sizing: border-box;">
			<input type="submit" name="submit" id="submit" class="saleassist-button saleassist-is-primary" value="<?php esc_attr_e( 'Lets Start With Saleassist', 'saleassist' );?>">
		</p>
	</form>	
</div>
