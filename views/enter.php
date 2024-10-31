<div class="saleassist-enter-api-key-box centered">
	<a href="#"><?php esc_html_e( 'Manually enter an API key', 'saleassist' ); ?></a>
	<div class="enter-api-key">
		<form action="<?php echo esc_url( Saleassist_Admin::get_page_url() ); ?>" method="post">
			<?php wp_nonce_field( Saleassist_Admin::NONCE ) ?>
			<input type="hidden" name="action" value="enter-key">
			<p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box;">
				<input id="key" name="key" value="" type="text" value="" placeholder="<?php esc_attr_e( 'Enter your API key' , 'saleassist' ); ?>" class="regular-text code" style="flex-grow: 1; margin-right: 1rem;">
				<input id="secret" name="secret" value="" type="text"  value="" placeholder="<?php esc_attr_e( 'Enter your Secret key' , 'saleassist' ); ?>" class="regular-text code" style="flex-grow: 1; margin-right: 1rem;">
				<input type="submit" name="submit" id="submit" class="saleassist-button" value="<?php esc_attr_e( 'Connect with API key', 'saleassist' );?>">
			</p>
		</form>
	</div>
</div>