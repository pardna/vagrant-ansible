<?php
global $ginit;
$errMsg = array();
$activeTab = empty($_GET['at']) ? 2 : $_GET['at'];
if (isset($_POST['contact'])) {
	if (!empty($_POST['your-name']) && !empty($_POST['your-email'])) {
		$name = sanitize_text_field($_POST['your-name']);
		$email = sanitize_email($_POST['your-email']);
		$subject = sanitize_text_field($_POST['subject']);
		$text_message = sanitize_text_field($_POST['message']);
		$message = array();
		$message[] = 'Date : '.date('Y-m-d H:i:s');
		$message[] = 'Name : '.$name;
		$message[] = 'Email : '.$email;
		$message[] = 'Subject : '.$subject;
		$message[] = 'Message : ';
		$message[] = $text_message;
		$message = implode("\r\n", $message);
		wp_mail( 'contact@ghuwad.com', 'Material_Lite_Theme - Contact', $message );
		if ($ginit) {
			wp_redirect(admin_url( 'admin.php?page=wp_author_info&email_sent=1&at=3'));
			exit;
		}
	} else {
		$errMsg['contact'] = 'Please enter require field';
		$activeTab = 3;
	}
}
if (!$ginit) {
?>
<div class="wrap">
	<h2></h2>
	<div class="ghuwad-panel">
		<h2 class="gtitle"><i class="icn-main"></i><?php _e( 'Material Lite', 'material-lite'); ?> <span class="version">v<?php echo Material_Lite_Theme::getVar('version');?></span></h2>
		<div class="gnav">
			<a href="javascript:void(0)" class="<?php echo (int)$activeTab==2?' active':'';?>" data-tab="help"><?php _e( 'Help', 'material-lite'); ?></a>
			<a href="javascript:void(0)" class="<?php echo (int)$activeTab==3?' active':'';?>" data-tab="contact"><?php _e( 'Report Bug or Request New Feature', 'material-lite'); ?></a>
			<a href="javascript:void(0)" class="<?php echo (int)$activeTab==4?' active':'';?>" data-tab="about-us"><?php _e( 'About Us', 'material-lite'); ?></a>
		</div>
		<div class="gbody">
			<div id="gtab-help" class="gtab-content info<?php echo (int)$activeTab==2?' open':'';?>">
				<div class="p">
					<strong><?php _e('Take look for all features.', 'material-lite');?></strong>
					<ul class="t">
						<li><span><?php _e(sprintf('Material Lite is fully develop on Google Material Lite CSS framework, so you could get all benefits which framework provide, <a href="%s" target="_blank">read more</a>.', esc_url(__('http://www.getmdl.io', 'material-lite'))), 'material-lite');?></span></li>
						<li><span><?php _e('Easily customize entire theme.', 'material-lite');?></span></li>
						<li><span><?php _e(sprintf('Get benefits from MDL components, <a href="%s" target="_blank">read more</a>.', esc_url(__('http://www.getmdl.io/components/index.html', 'material-lite'))), 'material-lite');?></span></li>
						<li><span><?php _e(sprintf('See MDL styles, typography, icons <a href="%s" target="_blank">read more</a>.', esc_url(__('http://www.getmdl.io/styles/index.html', 'material-lite'))), 'material-lite');?></span></li>
						<li><span><?php _e(sprintf('Use theme <a href="%s">customizer</a> to change, like upload new logo, set new favicon, set theme layout, set theme width, etc', esc_url(admin_url('customize.php'))), 'material-lite');?></span></li>
					</ul>
				</div>
				<div class="p">
					<strong><?php _e('FAQs:', 'material-lite');?></strong>
					<ul class="t">
						<li><span><strong><?php _e('How to use MDL Custom CSS theme builder?', 'material-lite');?></strong><br/>
							<ol>
								<li><span><?php _e(sprintf('<a href="%s" target="_blank">Click here</a> to MDL Custom CSS theme builder', esc_url(__('http://www.getmdl.io/customize/index.html', 'material-lite'))), 'material-lite');?></span></li>
								<li><span><?php _e('Select primary &amp; secondary color for your theme from wheel', 'material-lite');?></span></li>
								<li><span><?php _e('Copy CSS (hosted on CDN) file url ie. https://storage.googleapis.com/code.getmdl.io/1.0.1/material.indigo-pink.min.css', 'material-lite');?></span></li>
								<li><span><?php _e('Paste into Appearance (WP Menu)  > Customize (Sub Menu) > Custom CSS (Block) > MDL CSS URL (Field)', 'material-lite');?></span></li>
								<li><span><?php _e('Click on Save &amp; Publish button', 'material-lite');?></span></li>
							</ol>
						</span></li>
						<li><span><strong><?php _e('Is Material Lite Free?', 'material-lite');?></strong><br/><?php _e('Yes, Material Lite theme is under GNU General Public License. You can use and modify as you want.', 'material-lite');?></span></li>
						<li><span><?php _e(sprintf('For more question on MDL&nbsp;<a href="%s" target="_blank">read more</a>', esc_url(__('http://www.getmdl.io/faq/index.html', 'material-lite'))), 'material-lite');?></span></li>
					</ul>
				</div>
			</div>
			<div id="gtab-contact" class="gtab-content gcontact-us info<?php echo (int)$activeTab==3?' open':'';?>">
				<div class="p">
					<?php _e('You can contact us as below', 'material-lite');?>
				</div>
				<?php
				if(!empty($_GET['email_sent'])) {
					echo '<div class="g-success-msg">'.__('Thanks for contact us, We\'ll reply as soon as possible.', 'material-lite' ).'</div>';
				}else if(!empty($errMsg['contact'])) {
					echo '<div class="g-error-msg">',$errMsg['contact'],'</div>';
				}?>
				<form method="post" id="frmContact">
					<input type="hidden" name="g_request" value="g-wai-contact-us"/>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Your Name', 'material-lite'); ?> <?php _e('(required)', 'material-lite'); ?></div>
						<div class="gcon">
							<input type="text" name="your-name" class="regular-text"/>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Your Email', 'material-lite'); ?> <?php _e('(required)', 'material-lite'); ?></div>
						<div class="gcon">
							<input type="text" name="your-email" class="regular-text"/>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Subject', 'material-lite'); ?></div>
						<div class="gcon">
							<input type="text" name="subject" class="regular-text"/>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Purpose', 'material-lite'); ?></div>
						<div class="gcon">
							<select name="purpose">
								<option><?php _e('Request new feature', 'material-lite'); ?></option>
								<option><?php _e('Report Bug', 'material-lite'); ?></option>
								<option><?php _e('Other', 'material-lite'); ?></option>
							</select>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Message', 'material-lite'); ?></div>
						<div class="gcon">
							<textarea name="message" rows="3" cols="30"></textarea>
						</div><div class="gclear"></div>
					</div>
					<div class="grow last">
						<div class="glbl">&nbsp;</div>
						<div class="gcon">
							<button type="submit" name="contact" class="button-primary orange"><?php _e('Submit', 'material-lite'); ?></button>
						</div>
					</div>
				</form>
			</div>
			<div id="gtab-about-us" class="gtab-content gabout-us info<?php echo $activeTab==4?' open':'';?>">
				<a href="http://ghuwad.com"><img src="<?php echo esc_url(Material_Lite_Theme::getVar('images','url'));?>/ghuwad-logo.png" alt="Ghuwad.com"/></a><br/>
				<?php _e('Email', 'material-lite'); ?> : <a href="mailto:contact@ghuwad.com">contact@ghuwad.com</a>
			</div>
			<div class="subheadd">
                <?php _e('Add your <a href="https://wordpress.org/support/view/theme-reviews/material-lite#postform" target="_blank"><span class="greq">&#9733;&#9733;&#9733;&#9733;&#9733;</span></a> on WordPress.org to spread the love.', 'material-lite');?> |
				<?php _e('Develop with <span class="greq"><strong>&#9829;</strong></span> by', 'material-lite'); ?> <a href="<?php echo esc_url('http://ghuwad.com'); ?>" target="_blank">Ghuwad.com</a>
			</div>
		</div>
	</div>
</div>
<?php } ?>