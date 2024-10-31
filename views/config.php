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
		<?php if ( Saleassist::get_api_key() ) { ?>
			<?php Saleassist_Admin::display_status(); ?>
		<?php } ?>
		<?php if ( ! empty( $notices ) ) { ?>
			<?php foreach ( $notices as $notice ) { ?>
				<?php Saleassist::view( 'notice', $notice ); ?>
			<?php } ?>
		<?php } ?>
		<?php		
			$saleassist_user = Saleassist_Admin::get_saleassist_user( );
			if(!empty($saleassist_user)) :
		?>
			<div class="saleassist-card">
				<div class="saleassist-section-header">
					<div class="saleassist-section-header__label">
						<span><?php esc_html_e( 'SaleAssist Live Chat Setup' , 'saleassist'); ?></span>
					</div>
					
					<span>
						<a href="https://my.saleassist.ai/auth/login-with-email-otp?email=<?= get_bloginfo('admin_email'); ?>" target="_blank">Manage Your Widgets</a>
					</span>
				</div>
				
				<div class="saleassist-block">
					<ul>
					<?php
					
					Saleassist::save_widgets();
					
					$widgets = Saleassist::get_widgets();
					if($widgets) {
						foreach($widgets as $wid) {
							?>
							<li class="saleassist_widget">
								<h3 class="saleassist_widget_title">
									<a href="<?php echo esc_url( Saleassist_Admin::get_page_url( 'view_widget',  $wid['widget_id'])); ?>">
										<?php esc_html_e(  $wid['title'], 'saleassist'); ?>
									</a> 
								<?php 
								if($wid['is_enabled']) {
									echo '<span class="widget_active">[Active]</span>';
								} else {
									echo '<span class="widget_inctive">[Inactive]</span>';
								} 
								?>
								</h3>
								<div class="saleassist_widget_body">
									<div class="saleassist_widget_icon">
										<img src="<?php echo esc_url($wid['source_image_url']);?>" />
									</div>
									<div class="saleassist_widget_detail">
										<p class="widget_description"><?php esc_html_e(  $wid['description'], 'saleassist');?></p>
										<p><strong> Widget Key:</strong> <?php esc_html_e(  $wid['widget_id'], 'saleassist');?></p>
										<?php if(!$wid['in_use']) { ?>
											<form action="<?php echo esc_url( Saleassist_Admin::get_page_url() ); ?>" method="POST">												
												<?php wp_nonce_field(Saleassist_Admin::NONCE) ?>
												<input type="hidden" id="setup_widget_id" name="setup_widget_id" value="<?php esc_attr_e($wid['widget_id']); ?>" />
												<input type="hidden" name="action" value="active-widget-inuse">
												<input type="submit" name="submit" id="submit" class="btn btn-sm btn-success" value="<?php esc_attr_e('Use This Widget', 'saleassist');?>">
											</form>
										<?php } else {
											echo '<button class="btn btn-sm btn-warning">In Use</span>'; 
										} ?>
									</div>
									<div class="saleassist_widget_action">
										<?php 
										if(!$wid['is_enabled']) {
											?>
											<div class="saleassist_widget_status">
												<span class="widget_inctive">Widget Inactive</span>
											</div>
											<?php
										} 
										?>	
										
										<div class="saleassist_widget_button">
											<a href="<?php echo esc_url( Saleassist_Admin::get_page_url( 'view_widget',  $wid['widget_id'])); ?>" class="btn btn-sm btn-default widget_active">Manage</a>											
										</div>
									</div>
									
								</div>
							</li>
							<?php
						}
					} else {
						?>
						<li class="saleassist_widget">								
							<div class="saleassist_widget_body">
								<div>
									<p><strong style="color:orange"> Notice:</strong> No Widgets found in your account. Please <a href="https://my.saleassist.ai/assets" target="_blank">click here</a> to add new widgets for your website.</p>
								</div>
							</div>
						</li>
						<?php
					}
					?>
					</ul>
				</div>
			</div>
			<?php
				endif;
				if($saleassist_user) :
			?>

			<div class="saleassist-card">
				<div class="saleassist-section-header">
					<div class="saleassist-section-header__label">
						<?php if ( ! Saleassist::predefined_api_key() ) { ?>
						<div id="delete-action">
							<a class="submitdelete deletion" href="<?php echo esc_url( Saleassist_Admin::get_page_url( 'delete_key' ) ); ?>"><?php esc_html_e('Reset', 'saleassist'); ?></a>
						</div>
						<?php } ?>
					</div>
					<div class="saleassist-section-header__actions">
						<span> Client ID: <?php esc_attr_e(get_option('saleassist_client_id')); ?> </span>
					</div>
				</div>
			</div>

		<?php endif ?>
	</div>
</div>
