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
				
				Saleassist::save_widgets();					
				$widgets = Saleassist::get_widgets();
				$wid 	= [];
				if($widgets) {
					foreach($widgets as $widloop) {
						if($widloop['widget_id'] == $_REQUEST['show']) {
							$wid = $widloop;
						}
					}
				} 
			?>

<section>
  <div class="container">
    
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<!-- <li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#WidgetSetup" role="tab">Widget Setup</a>
		</li> -->
		<?php if($wid) { ?>
			<?php if(false) { ?>
		<li class="nav-item" >
			<a class="nav-link" data-toggle="tab" href="#PageSetup" role="tab">Page Setup</a>
		</li>
			<?php } ?>
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#ShortcodeMaker" role="tab">Shortcode Maker</a>
		</li>
		<?php } ?>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<?php /* ?>
		<div class="tab-pane active" id="WidgetSetup" role="tabpanel">
			
			<div class="saleassist-card">
				<div class="saleassist-section-header">
					
					<div class="saleassist-section-header__label">
						<span><?php esc_html_e( 'Widget Setup' , 'saleassist'); ?></span>
					</div>
					
					<div class="saleassist-section-header__actions">
						<a href="<?php echo esc_url( Saleassist_Admin::get_page_url( )); ?>">
							<?php esc_html_e( 'Back' , 'saleassist');?>
						</a>
					</div>
				</div>
				
				<div class="saleassist-block">
					<ul>
					<?php
					if($wid) {
							?>
							<li class="saleassist_widget">
								<h3 class="saleassist_widget_title"><?php esc_html_e(  $wid['title'], 'saleassist'); ?>
								<?php 
								
								if($wid['is_enabled']) {
									echo '[<strong style="color:green">Active</strong>]';
								} else {
									echo '[<strong style="color:red">Inactive</strong>]';
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
									</div>
								</div>
							</li>
							<?php
					} else {
						?>						
						<li class="saleassist_widget">								
							<div class="saleassist_widget_body">
								<div>
									<p><strong style="color:orange"> Notice:</strong> No Widgets found in your account. Please <a href="https://my.saleassist.ai/settings/widgets" target="_blank">click here</a> to add new widgets for your website.</p>
								</div>
							</div>
						</li>
						<?php
					}
					?>
					</ul>
				</div>
			</div>

		</div>
		<?php */ ?>
		<?php if($wid) { ?>
			<?php if(false) { ?>
		<div class="tab-pane" id="PageSetup" role="tabpanel">
			<div class="saleassist-card">
				<div class="saleassist-section-header">
					<div class="saleassist-section-header__label">
						<span><?php esc_html_e( 'Page Setup' , 'saleassist'); ?></span>
					</div>
					
					<div class="saleassist-section-header__actions">
						<a href="<?php echo esc_url( Saleassist_Admin::get_page_url( )); ?>">
							<?php esc_html_e( 'Back' , 'saleassist');?>
						</a>
					</div>
				</div>

				<div class="inside chatbox_setup">
					<form action="<?php echo esc_url( Saleassist_Admin::get_page_url() ); ?>" method="POST">
						<table class="form-table" />
							<tbody>
								<tr class="code_gen_box code_btn code_ifm">
									<th scope="row"><label for="blogname">Page Setup</label></th>
									<td>
										<fieldset>
											<label><input class="select_radio page_selector" id="radio_page_all" type="radio" name="radio_page" value="all" checked> <span>All</span></label>
											<label><input class="select_radio page_selector" id="radio_page_sel" type="radio" name="radio_page" value="sel"> <span class="date-time-text format-i18n">Manual Select</span></label><br>
										</fieldset>
										<select class="saleassist_chat_setup select2" name="page_setup[]" id="page_setup" multiple>
											<?php
											$pages = get_pages();
											$pageArr = explode(",", $wid['page_list']);
											foreach ( $pages as $page ) {
												$selected = (in_array($page->ID, $pageArr)) ? "selected" : ""; 
											?>
												<option value="<?php esc_attr_e($page->ID); ?>" <?php esc_html_e($selected); ?>><?php esc_html_e(  $page->post_title, 'saleassist'); ?></option>';
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr class="code_gen_box code_btn code_ifm">
									<th scope="row"><label for="blogname">Post Setup</label></th>
									<td>
										<fieldset>
											<label><input class="select_radio post_selector" id="radio_post_all" type="radio" name="radio_post" value="all" checked> <span>All</span></label>
											<label><input class="select_radio post_selector" id="radio_post_sel" type="radio" name="radio_post" value="sel"> <span class="date-time-text format-i18n">Manual Select</span></label><br>
										</fieldset>
										<select class="saleassist_chat_setup select2" name="post_setup[]" id="post_setup" multiple>
										<?php
											/*
											$args = array(
												'public'   => true,
												'_builtin' => false
											);
										
											$output = 'names'; // 'names' or 'objects' (default: 'names')
											$operator = 'or'; // 'and' or 'or' (default: 'and')
											
											$post_types = get_post_types( $args, $output, $operator );
											
											*/
											$postArr = explode(",", $wid['post_list']);
											$post_types = get_post_types(array('public' => true), 'names', 'and');
											if ( $post_types ) {
												foreach ( $post_types  as $post_type ) {
													if($post_type == "page" || $post_type == "attachment") {
														continue;
													}
													$selected2 = (in_array($post_type, $postArr)) ? "selected" : "";

													echo '<option value="'.esc_html($post_type).'"  '.esc_html($selected2).'>'.esc_html($post_type).'</option>';
												}										
											}
										?>
										</select>
										
									</td>
									<td style="display: none;">
										<label for="saleassist_enable_on_page" title="<?php esc_attr_e( 'Show approved comments' , 'saleassist'); ?>">
											<input name="saleassist_enable_on_page" id="saleassist_enable_on_page" value="1"
												type="checkbox"
												<?php
													if(get_option( 'saleassist_page_enable' ) == 1)
													esc_html_e("checked") ;
												?>
												/>
												<?php esc_html_e( 'Page Intigration', 'saleassist' ); ?>
										</label>
									</td>
								</tr>
								<tr class="">
									<?php wp_nonce_field(Saleassist_Admin::NONCE) ?>
									<th scope="row" colspan="2">
										<div id="publishing-action">
											<input type="hidden" id="setup_widget_id" name="setup_widget_id" value="<?php esc_attr_e($wid['widget_id']); ?>" />
											
											<input type="hidden" id="old_pagesetup" name="old_pagesetup" value="<?php esc_attr_e($wid['page_list']); ?>" />
											<input type="hidden" id="old_postsetup" name="old_postsetup" value="<?php esc_attr_e($wid['post_list']); ?>" />
											
											<input type="hidden" name="action" value="update-widget-setting">
											<input type="submit" name="submit" id="submit" class="saleassist-button saleassist-could-be-primary" value="<?php esc_attr_e('Save Changes', 'saleassist');?>">
										</div>
										<div class="clear"></div>
									</th>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
			<?php } ?>
		<div class="tab-pane active" id="ShortcodeMaker" role="tabpanel">
			<div class="saleassist-card">
				<div class="saleassist-section-header">
					<div class="saleassist-section-header__label">
						<span><?php esc_html_e( 'Shortcode Setup' , 'saleassist'); ?></span>
					</div>
					
					<div class="saleassist-section-header__actions">
						<a href="<?php echo esc_url( Saleassist_Admin::get_page_url( )); ?>">
							<?php esc_html_e( 'Back' , 'saleassist');?>
						</a>
					</div>
				</div>
				
				<div class="inside chatbox_setup">
					<table class="form-table" />
						<tbody>
							<tr>
								<th scope="row"><label for="blogname">Shortcode Type</label></th>
								<td>
								<fieldset>
									<label><input class="feature_radio" id="radio_button" type="radio" name="feature_type" value="button" checked> <span>Button</span></label><br>
									<label><input class="feature_radio" id="radio_iframe" type="radio" name="feature_type" value="iframe"> <span class="date-time-text format-i18n">iFrame</span></label><br>
								</fieldset>
								</td>
							</tr>
							<tr class="code_gen_box code_btn code_ifm">
								<th scope="row"><label for="blogname">Select Height</label></th>
								<td>
									<input type="number" name="hight" class="height_get" placeholder="please enter height" />
								</td>
							</tr>
							<tr class="code_gen_box code_btn code_ifm">
								<th scope="row"><label for="blogname">Select Width</label></th>
								<td>
									<input type="number" name="width" class="width_get" placeholder="please enter width" />
								</td>
							</tr>
							<tr class="code_gen_box code_btn">
								<th scope="row"><label for="blogname">Select Background Color</label></th>
								<td>
									<input type="color" name="background_color" class="backgound_set" placeholder="please enter button height" />
								</td>
							</tr>
							<tr class="code_gen_box code_btn">
								<th scope="row"><label for="blogname">Select Text Color</label></th>
								<td>
									<input type="color" name="text_color" class="color_set" placeholder="please enter button width" />
								</td>
							</tr>
						</tbody>
					</table>
					<h4>Generated Shortcode <button class="shortcode_copy_btn shortcode_btn_copy_btn btn btn-warning" >Copy</button> <button class="shortcode_copy_btn shortcode_ifm_copy_btn btn btn-warning" style="display:none" >Copy</button></h4>
					<input type="hidden" id="shortcode_widget_id" value="" />
					<div class="shortcode_txt shortcode_btn">
						<p>[saleassist_button wid="<?php esc_attr_e( $wid['widget_id'] ); ?>" <span class="backgound_get"></span><span class="color_get"></span><span class="font_size_get"></span><span class="height_get"></span><span class="width_get"></span>]</p>
					</div>
					<div class="shortcode_txt shortcode_ifm">
						<p>[saleassist_iframe wid="<?php esc_attr_e( $wid['widget_id'] ); ?>" <span class="height_get"></span><span class="width_get"></span>]</p>
					</div>
				</div>
			</div>
			<section id="content" style="display:none;">
				<div class="row">
					<div class="lg-6">
						<div class="inner cf">
							<style id="dynamic-styles" type="text/css"></style>
							<div id="settings-wrap">
								<h2>Settings</h2>
								<div id="settings-wrap-inner">
									<div class="panel-wrap active"><h3><span>+</span> Font / Text</h3></div>
									<div class="accordion-inner font cf">
										<div class="accordion-inner2">
											<div class="form-wrap">
												<label for="text">text:</label>
												<input type="text" name="text" value="Live Chat" id="text">
											</div>
											<div class="form-wrap">
												<input type="checkbox" name="font-family-check" checked id="font-family-check" data-control="font-family">
												<div data-control-group="font-family">
													<label for="font-family">font-family:</label>
													<select id="font-family">
														<option value="Arial">Arial</option>
														<option value="Georgia">Georgia</option>
														<option value="Courier New">Courier New</option>
													</select>
												</div>
											</div>
											<div class="form-wrap">
												<input type="checkbox" name="font-color-check" checked id="font-color-check" data-control="font-color">
												<div data-control-group="font-color">
													<label for="color">font-color:</label>
													<input class="color" type="text" name="font-color" value="#ffffff" id="color">
													<div class="color-view bg"></div>
												</div>
											</div>
											<div class="form-wrap">
												<input type="checkbox" name="font-size-check" checked id="font-size-check" data-control="font-size">
												<div data-control-group="font-size">
													<label for="font-size">font-size:</label>
													<input class="slider-bound" type="text" name="font-size" value="20" id="font-size" max="60">
													<div id="font-size-slider"></div>
												</div>
											</div>
											<div class="form-wrap">
												<input type="checkbox" name="text-shadow-color-check" id="text-shadow-color-check" data-control="text-shadow">
												<div data-control-group="text-shadow">
													<label for="text-shadow-color">text-shadow:</label>
													<input class="color" type="text" name="text-shadow-color" value="#666666" id="text-shadow-color" max="60">
													<div class="color-view"></div>
												</div>
											</div>
											<div data-control-group="text-shadow">
												<div class="form-wrap">
													<label for="text-shadow-x">x:</label>
													<input class="slider-bound" type="text" name="text-shadow-x" value="1" id="text-shadow-x" max="30">
													<div id="text-shadow-x-slider"></div>
												</div>
												<div class="form-wrap">
													<label for="text-shadow-y">y:</label>
													<input class="slider-bound" type="text" name="text-shadow-y" value="1" id="text-shadow-y" max="30">
													<div id="text-shadow-y-slider"></div>
												</div>
												<div class="form-wrap">
													<label for="text-shadow-blur">blur:</label>
													<input class="slider-bound" type="text" name="text-shadow-blur" value="3" id="text-shadow-blur" max="30">
													<div id="text-shadow-blur-slider"></div>
												</div>
											</div>
										</div>
									</div><!-- end accordion-inner, accordion-inner2 -->
									<div class="panel-wrap"><h3><span>+</span> Box</h3></div>
									<div class="accordion-inner cf">
										<div class="accordion-inner2">
											<div class="form-wrap">
												<input type="checkbox" name="box-shadow-color-check" id="box-shadow-color-check" data-control="box-shadow" checked>
												<div data-control-group="box-shadow">
													<label for="box-shadow-color">box-shadow:</label>
													<input class="color" type="text" name="box-shadow-color" value="#666666" id="box-shadow-color" max="60">
													<div class="color-view"></div>
												</div>
											</div>
											<div data-control-group="box-shadow">
												<div class="form-wrap">
													<label for="box-shadow-x">x:</label>
													<input class="slider-bound" type="text" name="box-shadow-x" value="4" id="box-shadow-x" max="30">
													<div id="box-shadow-x-slider"></div>
												</div>
												<div class="form-wrap">
													<label for="box-shadow-y">y:</label>
													<input class="slider-bound" type="text" name="box-shadow-y" value="4" id="box-shadow-y" max="30">
													<div id="box-shadow-y-slider"></div>
												</div>
												<div class="form-wrap">
													<label for="box-shadow-blur">blur:</label>
													<input class="slider-bound" type="text" name="box-shadow-blur" value="9" id="box-shadow-blur" max="30">
													<div id="box-shadow-blur-slider"></div>
												</div>
											</div>
											<div class="form-wrap">
												<input type="checkbox" name="padding-check" id="padding-check" data-control="padding">
												<div data-control-group="padding">
													<label for="padding">padding:</label>
													<input class="slider-bound" type="text" name="padding" value="10" id="padding" max="40">
													<div id="padding-slider"></div>
												</div>
											</div>
											<div data-control-group-switch="padding">
												<div class="form-wrap">
													<input type="checkbox" name="padding-top-check" checked id="padding-top-check" data-control="padding-top">
													<div data-control-group="padding-top">
														<label for="padding-top">padding-top:</label>
														<input class="slider-bound" type="text" name="padding-top" value="10" id="padding-top" max="40">
														<div id="padding-top-slider"></div>
													</div>
												</div>
												<div class="form-wrap">
													<input type="checkbox" name="padding-right-check" checked id="padding-right-check" data-control="padding-right">
													<div data-control-group="padding-right">
														<label for="padding-right">padding-right:</label>
														<input class="slider-bound" type="text" name="padding-right" value="20" id="padding-right" max="40">
														<div id="padding-right-slider"></div>
													</div>
												</div>
												<div class="form-wrap">
													<input type="checkbox" name="padding-bottom-check" checked id="padding-bottom-check" data-control="padding-bottom">
													<div data-control-group="padding-bottom">
														<label for="padding-bottom">padding-bottom:</label>
														<input class="slider-bound" type="text" name="padding-bottom" value="10" id="padding-bottom" max="40">
														<div id="padding-bottom-slider"></div>
													</div>
												</div>
												<div class="form-wrap">
													<input type="checkbox" name="padding-left-check" checked id="padding-left-check" data-control="padding-left">
													<div data-control-group="padding-left">
														<label for="padding-left">padding-left:</label>
														<input class="slider-bound" type="text" name="padding-left" value="20" id="padding-left" max="40">
														<div id="padding-left-slider"></div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- end accordion-inner, accordion-inner2 -->
									<div class="panel-wrap"><h3><span>+</span> Border</h3></div>
									<div class="accordion-inner cf">
										<div class="accordion-inner2">
											<div class="form-wrap">
												<input type="checkbox" name="border-radius-check" checked id="border-radius-check" data-control="border-radius">
												<div data-control-group="border-radius">
													<label for="border-radius">border-radius:</label>
													<input class="slider-bound" type="text" name="border-radius" value="10" id="border-radius" max="60">
													<div id="border-radius-slider" class="slider"></div>
												</div>
											</div>
											<div class="form-wrap">
												<input type="checkbox" name="border-color-check" id="border-color-check" data-control="border">
												<div data-control-group="border">
													<label for="border-color">border:</label>
													<input class="color" type="text" name="border-color" value="#1f628d" id="border-color" max="60">
													<div class="color-view"></div>
												</div>
											</div>
											<div data-control-group="border">
												<div class="form-wrap">
													<label for="border-style">border-style:</label>
													<select id="border-style">
														<option id="solid">solid</option>
														<option id->dotted</option>
													</select>
												</div>
												<div class="form-wrap">
													<label for="border-width">border-width:</label>
													<input class="slider-bound" type="text" name="border-width" value="2" id="border-width" max="20">
													<div id="border-width-slider"></div>
												</div>
											</div>
											<a href="" class="more-link" data-section="border">More</a>
											<div class="more" data-section-more="border">
												<div data-control-group-switch="border">
													<!-- border-top -->
													<div class="form-wrap">
														<input type="checkbox" name="border-top-color-check" id="border-top-color-check" data-control="border-top">
														<div data-control-group="border-top">
															<label for="border-top-color">border-top:</label>
															<input class="color" type="text" name="border-top-color" value="#666666" id="border-top-color">
															<div class="color-view"></div>
														</div>
													</div>
													<div data-control-group="border-top">
														<div class="form-wrap">
															<label for="border-top-style">border-top-style:</label>
															<select id="border-top-style">
																<option id="solid">solid</option>
																<option id->dotted</option>
															</select>
														</div>
														<div class="form-wrap">
															<label for="border-top-width">border-top-width:</label>
															<input class="slider-bound" type="text" name="border-top-width" value="1" id="border-top-width" max="20">
															<div id="border-top-width-slider"></div>
														</div>
													</div>
													<!-- border-right -->
													<div class="form-wrap">
														<input type="checkbox" name="border-right-color-check" id="border-right-color-check" data-control="border-right">
														<div data-control-group="border-right">
															<label for="border-right-color">border-right:</label>
															<input class="color" type="text" name="border-right-color" value="#666666" id="border-right-color">
															<div class="color-view"></div>
														</div>
													</div>
													<div data-control-group="border-right">
														<div class="form-wrap">
															<label for="border-right-style">border-right-style:</label>
															<select id="border-right-style">
																<option id="solid">solid</option>
																<option id->dotted</option>
															</select>
														</div>
														<div class="form-wrap">
															<label for="border-right-width">border-right-width:</label>
															<input class="slider-bound" type="text" name="border-right-width" value="1" id="border-right-width" max="20">
															<div id="border-right-width-slider"></div>
														</div>
													</div>
													<!-- border-bottom -->
													<div class="form-wrap">
														<input type="checkbox" name="border-bottom-color-check" id="border-bottom-color-check" data-control="border-bottom">
														<div data-control-group="border-bottom">
															<label for="border-bottom-color">border-bottom:</label>
															<input class="color" type="text" name="border-bottom-color" value="#666666" id="border-bottom-color">
															<div class="color-view"></div>
														</div>
													</div>
													<div data-control-group="border-bottom">
														<div class="form-wrap">
															<label for="border-bottom-style">border-bottom-style:</label>
															<select id="border-bottom-style">
																<option id="solid">solid</option>
																<option id->dotted</option>
															</select>
														</div>
														<div class="form-wrap">
															<label for="border-bottom-width">border-bottom-width:</label>
															<input class="slider-bound" type="text" name="border-bottom-width" value="1" id="border-bottom-width" max="20">
															<div id="border-bottom-width-slider"></div>
														</div>
													</div>
													<!-- border-left -->
													<div class="form-wrap">
														<input type="checkbox" name="border-left-color-check" id="border-left-color-check" data-control="border-left">
														<div data-control-group="border-left">
															<label for="border-left-color">border-left:</label>
															<input class="color" type="text" name="border-left-color" value="#666666" id="border-left-color">
															<div class="color-view"></div>
														</div>
													</div>
													<div data-control-group="border-left">
														<div class="form-wrap">
															<label for="border-left-style">border-left-style:</label>
															<select id="border-left-style">
																<option id="solid">solid</option>
																<option id->dotted</option>
															</select>
														</div>
														<div class="form-wrap">
															<label for="border-left-width">border-left-width:</label>
															<input class="slider-bound" type="text" name="border-left-width" value="1" id="border-left-width" max="20">
															<div id="border-left-width-slider"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- end accordion-inner, accordion-inner2 -->
									<div class="panel-wrap"><h3><span>+</span> Background</h3></div>
									<div class="accordion-inner cf">
										<div class="accordion-inner2">
											<div class="form-wrap">
												<input type="checkbox" name="background-check" checked id="background-check" data-control="background">
												<div data-control-group="background">
													<label for="background">background:</label>
													<div class="radio-wrap">
														<input type="radio" name="background" value="" id="background-gradient" data-control-display="background-gradient" checked><label>gradient</label>
														<input type="radio" name="background" value="" id="background-solid" data-control-display="background-solid"><label>solid</label>
														<div class="clear"></div>
													</div>
												</div>
											</div>
											<div data-control-group="background">
												<div id="gradient-wrap" data-control-display-group="background-gradient" data-control-display-selector="background">
													<div class="form-wrap">
														<label for="bg-start-gradient">start color:</label>
														<input class="color" type="text" name="color" value="#3498db" id="bg-start-gradient">
														<div class="color-view bg"></div>
													</div>
													<div class="form-wrap">
														<label for="bg-end-gradient">end color:</label>
														<input class="color" type="text" name="color" value="#2980b9" id="bg-end-gradient">
														<div class="color-view bg"></div>
													</div>
												</div><!-- end #gradient-wrap -->
												<div id="solid-wrap" data-control-display-group="background-solid" data-control-display-selector="background">
													<div class="form-wrap">
														<label for="background">color:</label>
														<input class="color" type="text" name="color" value="#3498db" id="background">
														<div class="color-view bg"></div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- end accordion-inner, accordion-inner2 -->
									<div class="panel-wrap"><h3><span>+</span> Hover</h3></div>
									<div class="accordion-inner last cf">
										<div class="accordion-inner2">
											<div class="form-wrap">
												<input type="checkbox" name="background-check" checked id="background-hover-check" data-control="hover-background">
												<div data-control-group="hover-background">
													<label for="background-hover">background:</label>
													<div class="radio-wrap">
														<input type="radio" name="hover-background" value="" id="background-gradient-hover" data-control-display="hover-background-gradient" checked><label>gradient</label>
														<input type="radio" name="hover-background" value="" id="background-solid-hover" data-control-display="hover-background-solid"><label>solid</label>
														<div class="clear"></div>
													</div>
												</div>
											</div>
											<div data-control-group="hover-background">
												<div id="gradient-hover-wrap" data-control-display-group="hover-background-gradient" data-control-display-selector="hover-background">
													<div class="form-wrap">
														<label for="bg-start-gradient-hover">start color:</label>
														<input class="color" type="text" name="color" value="#3cb0fd" id="bg-start-gradient-hover">
														<div class="color-view bg"></div>
													</div>
													<div class="form-wrap">
														<label for="bg-end-gradient-hover">end color:</label>
														<input class="color" type="text" name="color" value="#3498db" id="bg-end-gradient-hover">
														<div class="color-view bg"></div>
													</div>
												</div><!-- end #gradient-wrap -->
												<div id="solid-hover-wrap" data-control-display-group="hover-background-solid" data-control-display-selector="hover-background">
													<div class="form-wrap">
														<label for="background-hover">color:</label>
														<input class="color" type="text" name="color" value="#3cb0fd" id="background-hover">
														<div class="color-view bg"></div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- end accordion-inner, accordion-inner2 -->
									<div class="clear"></div>
								</div><!-- end settings-wrap-inner -->
							</div><!-- end settings-wrap -->
							<?php 
							$className = Saleassist_Admin::generateRandomString()."-btn";
							?>			
						</div>
					</div>
					<div class="lg-6">
						<div class="inner cf">							
							<form action="<?php echo esc_url( Saleassist_Admin::get_page_url() ); ?>" method="POST">
								<div id="button-wrap">
									<h2>Button preview</h2>
									<div id="button-wrap-inner">
										<input type="hidden" id="btn_class_name" value="<?php esc_attr_e($className);?>" />
										<input type="hidden" name="action" value="save-button-ui">
										<a class="saleassist-btn <?php esc_attr_e($className);?>" href="">Live Chat</a>
										<input type="submit" name="submit" id="submit" class="saleassist-button saleassist-could-be-primary save-btn-ui" value="<?php esc_attr_e('Save Changes', 'saleassist');?>">
									</div><!-- end button-wrap-inner -->
									<h2>Style Preview</h2>
									<div id="styles-wrap">
										<div id="styles-wrap-inner css-display-box">
											<textarea id="css-display" readonly></textarea>
										</div><!-- end styles-wrap-inner -->
									</div><!-- end styles-wrap -->
								</div><!-- end button-wrap -->	
								<?php wp_nonce_field(Saleassist_Admin::NONCE) ?>
							</form>
						</div>
					</div>
				</div>
				
			</section>
		</div>
		<?php } ?>
	</div>
    
  </div>
</section>
<script>
	$(".nav-tabs li.nav-item a.nav-link").click(function() {
		$(".nav-tabs li.nav-item a.nav-link").removeClass('active');
	});
</script>
			
			<?php
				endif;
			?>
	</div>
</div>

<script>
jQuery(document).ready(function () {
	jQuery('.select2').select2({
		placeholder: "Please select"
	});

	let pageSetting = jQuery("#old_pagesetup").val();
	let postSetting = jQuery("#old_postsetup").val();


	if(pageSetting == "*") {
		jQuery("#radio_page_all").prop("checked", true);
		//jQuery("#page_setup").select2("val", "");
		jQuery('#page_setup').next(".select2-container").hide();
	} else {
		jQuery("#radio_page_sel").prop("checked", true);
		//jQuery("#page_setup").select2("val", pageSetting);
		jQuery('#page_setup').next(".select2-container").show();
	}

	if(postSetting == "*") {
		jQuery("#radio_post_all").prop("checked", true);
		//jQuery("#post_setup").select2("val", "");
		jQuery('#post_setup').next(".select2-container").hide();
	} else {		
		jQuery("#radio_post_sel").prop("checked", true);
		//jQuery("#post_setup").select2("val", postSetting);	
		jQuery('#post_setup').next(".select2-container").show();
	}
	

	jQuery(".code_gen_box").hide();
	jQuery(".shortcode_txt").hide();
	jQuery(".code_btn").show();
	jQuery(".shortcode_btn").show();

	jQuery(".page_selector").on("change", function() {
		let boxVal = jQuery(this).val();
		if(boxVal == "sel") {
			jQuery('#page_setup').next(".select2-container").show();
		} else {
			jQuery('#page_setup').next(".select2-container").hide();
		}
	});
	jQuery(".post_selector").on("change", function() {
		let boxVal = jQuery(this).val();
		if(boxVal == "sel") {
			jQuery('#post_setup').next(".select2-container").show();
		} else {
			jQuery('#post_setup').next(".select2-container").hide();
		}
	});
	jQuery(".feature_radio").on("change", function() {
		jQuery(".code_gen_box").hide();
		jQuery(".shortcode_txt").hide();
		jQuery(".shortcode_copy_btn").hide();
		let btnType = jQuery(this).val();
		if(btnType == "iframe") {
			jQuery(".code_ifm").show();
			jQuery(".shortcode_ifm").show();
			jQuery(".shortcode_ifm_copy_btn").show();
		} else {
			jQuery(".code_btn").show();
			jQuery(".shortcode_btn").show();
			jQuery(".shortcode_btn_copy_btn").show();
		}
	});

	jQuery(".height_get").on("change", function(){
		let heightVal = jQuery(this).val();
		if(heightVal > 0 && heightVal != '') {
			jQuery('.height_get').html(' whi="'+heightVal+'"');
		} else {
			jQuery('.height_get').html('');
		}
	});	
	jQuery(".width_get").on("change", function(){
		let heightVal = jQuery(this).val();
		if(heightVal > 0 && heightVal != '') {
			jQuery('.width_get').html(' wwi="'+heightVal+'"');
		} else {
			jQuery('.width_get').html('');
		}
	});
	jQuery(".font_size").on("change", function(){
		let fontSizeVal = jQuery(this).val();
		if(fontSizeVal > 0 && fontSizeVal != '') {
			jQuery('.font_size_get').html(' fo_si="'+fontSizeVal+'"');
		} else {
			jQuery('.font_size_get').html('');
		}
	});	
	jQuery(".backgound_set").on("change", function(){
		let BackVal = jQuery(this).val();
		if(BackVal != '') {
			jQuery('.backgound_get').html(' bk_co="'+BackVal+'"');
		} else {
			jQuery('.backgound_get').html('');
		}
	});	
	jQuery(".color_set").on("change", function(){
		let TextVal = jQuery(this).val();
		if(TextVal != '') {
			jQuery('.color_get').html(' tx_co="'+TextVal+'"');
		} else {
			jQuery('.color_get').html('');
		}
	});

	jQuery(".shortcode_btn_copy_btn").on("click", function(){
		let temp = jQuery("<input>");
		jQuery("body").append(temp);
		temp.val(jQuery(".shortcode_btn p").text()).select();
		document.execCommand("copy");
		temp.remove();
	});
	jQuery(".shortcode_ifm_copy_btn").on("click", function(){
		let temp = jQuery("<input>");
		jQuery("body").append(temp);
		temp.val(jQuery(".shortcode_ifm p").text()).select();
		document.execCommand("copy");
		temp.remove();
	});
});
</script>
