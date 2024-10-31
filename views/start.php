<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<div id="saleassist-plugin-container">
	<div class="saleassist-masthead">
		<div class="saleassist-masthead__inside-container">
			<div class="saleassist-masthead__logo-container">
				<img class="saleassist-masthead__logo" src="<?php echo esc_url( plugins_url( '../_inc/img/logo-full-2x.png', __FILE__ ) ); ?>" alt="Saleassist" />
			</div>
		</div>
	</div>
	<div class="saleassist-lower">
		<?php Saleassist_Admin::display_status();?>
		<div class="saleassist-boxes">
			<?php
			if ( Saleassist::predefined_api_key() ) {
				Saleassist::view( 'predefined' );
			} else {
				Saleassist::view( 'activate' );
			}
			?>
		</div>
	</div>
</div>