<div class="saleassist-box">
	<h2><?php esc_html_e( 'Manual Configuration', 'saleassist' ); ?></h2>
	<p>
		<?php

		/* translators: %s is the wp-config.php file */
		echo sprintf( esc_html__( 'An Saleassist API key has been defined in the %s file for this site.', 'saleassist' ), '<code>wp-config.php</code>' );

		?>
	</p>
</div>