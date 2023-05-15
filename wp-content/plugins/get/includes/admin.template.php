<?php
if (isset($_POST[GETREVIEW_INSTALL_TYPE])) {
	update_option(GETREVIEW_INSTALL_TYPE, sanitize_text_field($_POST[GETREVIEW_INSTALL_TYPE]));
}

if (isset($_POST[GETREVIEW_CHECKBOX_ENABLED])) {
	update_option(GETREVIEW_CHECKBOX_ENABLED, sanitize_text_field($_POST[GETREVIEW_CHECKBOX_ENABLED]));
}

if (isset($_POST[GETREVIEW_CHECKBOX_TEXT])) {
	update_option(GETREVIEW_CHECKBOX_TEXT, sanitize_text_field($_POST[GETREVIEW_CHECKBOX_TEXT]));
}

$currentInstall = get_option(GETREVIEW_INSTALL_TYPE);
$installTypes = [
	'auto' => __('automatically', GETREVIEW_TEXT_DOMAIN),
	'shortcode' => __('manually (shortcode)', GETREVIEW_TEXT_DOMAIN)
];
?>
<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>

	<?php if (!get_option(GETREVIEW_GUID_KEY)): ?>
		<div class="notice notice-success">
				<p><?php printf(__('Thank you for installing GetReview! For your convenience, the application panel can be managed from here: <a href="%s">%s</a>', GETREVIEW_TEXT_DOMAIN), 'https://app.getreview.pl', 'https://app.getreview.pl'); ?></p>
				<?php _e('If you don\'t have an account, you can create one for free and collect up to 50 opinions without costs. In the free version you can:', GETREVIEW_TEXT_DOMAIN); ?>
				<ul>
					<li><?php _e('set your own message', GETREVIEW_TEXT_DOMAIN); ?>,</li>
					<li><?php _e('set your own form with questions', GETREVIEW_TEXT_DOMAIN); ?>,</li>
					<li><?php _e('collect opinions with photos', GETREVIEW_TEXT_DOMAIN); ?>,</li>
					<li><?php _e('display opinions under products', GETREVIEW_TEXT_DOMAIN); ?>,</li>
				</ul>
				<?php _e('and much more :)', GETREVIEW_TEXT_DOMAIN); ?>
				<p><?php printf(__('After the account is created, create your first review campaign and save it. From now on you will be collecting opinions with Getreview. You can find more instructions here: <a href="%s">%s</a>', GETREVIEW_TEXT_DOMAIN), 'https://app.getreview.pl/faq', 'https://app.getreview.pl/faq'); ?></p>
		</div>
	<?php endif; ?>

	<form method="POST" action="" novalidate>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php _e('Installation mode', GETREVIEW_TEXT_DOMAIN); ?></label>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e('Installation mode', GETREVIEW_TEXT_DOMAIN); ?></span></legend>
							<p>
								<?php foreach ($installTypes as $installType => $installName): ?>
									<label><input name="<?php echo GETREVIEW_INSTALL_TYPE; ?>" type="radio" value="<?php echo $installType; ?>"
									<?php if ($currentInstall == $installType): ?>checked="checked"<?php endif; ?>><?php echo $installName; ?></label><br>
								<?php endforeach; ?>
							</p>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Consent to collect opinions at checkout', GETREVIEW_TEXT_DOMAIN); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e('Enable (add checkbox)', GETREVIEW_TEXT_DOMAIN); ?></span></legend>
							<label for="<?php echo GETREVIEW_CHECKBOX_ENABLED; ?>">
								<input type="hidden" name="<?php echo GETREVIEW_CHECKBOX_ENABLED; ?>" value="0" />
								<input name="<?php echo GETREVIEW_CHECKBOX_ENABLED; ?>" type="checkbox" id="<?php echo GETREVIEW_CHECKBOX_ENABLED; ?>"
									value="1" <?php if (get_option(GETREVIEW_CHECKBOX_ENABLED) == 1) echo 'checked="checked"'; ?>>
								<?php _e('Enable (add checkbox)', GETREVIEW_TEXT_DOMAIN); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="<?php echo GETREVIEW_CHECKBOX_TEXT; ?>"><?php _e('Text of consent to collect opinions', GETREVIEW_TEXT_DOMAIN); ?></label>
					</th>
					<td>
						<textarea name="<?php echo GETREVIEW_CHECKBOX_TEXT; ?>" id="<?php echo GETREVIEW_CHECKBOX_TEXT; ?>"
							class="large-text"><?php echo trim(get_option(GETREVIEW_CHECKBOX_TEXT)); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save'); ?>">
		</p>
	</form>

	<h2 class="title"><?php _e('Installation of GetReview widget', GETREVIEW_TEXT_DOMAIN); ?></h2>
	<p><?php _e('If you have chosen automatic mode, the GetReview widget will be automatically placed on the product page, just before the footer of the page. No additional action will be required.', GETREVIEW_TEXT_DOMAIN); ?></p>
	<p><?php printf(__('If you have chosen the shortcode mode, put the shortcode %s in the place of your choice. Make sure that shortcode support is enabled there.', GETREVIEW_TEXT_DOMAIN), '<code>[getreview]</code>'); ?></p>
</div>
