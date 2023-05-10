<?php

namespace GetReview;

class Connect extends \WC_Auth {
	public function __construct() {
		add_action('rest_api_init', [$this, 'registerRestApiEndpoint']);
		add_action('woocommerce_review_order_before_submit', [$this, 'injectReviewRequestCheckbox']);
		add_action('woocommerce_checkout_update_order_meta', [$this, 'checkReviewRequestAtCheckout']);
		add_action('admin_head', [$this, 'createOrderUpdateWebhook']);

		if (get_option(GETREVIEW_INSTALL_TYPE) == 'auto') {
			add_action('get_footer', [$this, 'injectWidgetCode']);
		}
		elseif (get_option(GETREVIEW_INSTALL_TYPE) == 'shortcode') {
			add_shortcode('getreview', [$this, 'getWidgetCode']);
		}

		if (in_array(get_option(GETREVIEW_CHECKBOX_TEXT), [null, false, ''])) {
			update_option(GETREVIEW_CHECKBOX_TEXT, __('I want to express my opinion on the purchase and agree to send me a survey to the e-mail address.'));
		}
	}

	public function getGuid() {
		return get_option(GETREVIEW_GUID_KEY, null);
	}

	public function setGuid($guid) {
		update_option(GETREVIEW_GUID_KEY, $guid);
	}

	public function getSiteKey() {
		return get_option(GETREVIEW_TOKEN_KEY, null);
	}

	public function setSiteKey($sitekey) {
		update_option(GETREVIEW_TOKEN_KEY, $sitekey);
	}

	public function isCheckboxEnabled() {
		return get_option(GETREVIEW_CHECKBOX_ENABLED) == 1;
	}

	public function registerRestApiEndpoint() {
		register_rest_route('getreview/v2', '/update/', [
			'methods' => 'GET',
			'callback' => [$this, 'restApiGuidUpdateCallback'],
			'args' => [
				'data' => ['required']
			]
		]);
	}

	public function restApiGuidUpdateCallback($request) {
		$data = $request->get_param('data');
		$data = base64_decode($data);
		if (!$data) return ['error' => 'INVALID_REQUEST'];

		$data = json_decode($data, true);
		if (!$data) return ['error' => 'INVALID_REQUEST'];

		if (!isset($data['guid'])) return ['error' =>'GUID_NOT_FOUND'];
		if (!isset($data['token'])) return ['error' =>'TOKEN_NOT_FOUND'];

		$currentSiteKey = $this->getSiteKey();
		if ($currentSiteKey === null) {
			$this->setSiteKey($data['token']);
		}
		elseif ($currentSiteKey !== $data['token']) {
			return ['error' =>'TOKEN_NOT_VALID'];
		}

		$this->setGuid($data['guid']);
		return ['success' => '1'];
	}

	public function getWidgetCode() {
		$guid = $this->getGuid();
		if ($guid !== null) {
			return '<div id="gr-widget"></div><script>(function() { var s=document.createElement("script");s.src="//app.getreview.pl/widget/app.js";s.async=true; window.grw = {id:"'.$guid.'",lang:"pl",type:"woo"};document.getElementsByTagName("body")[0].appendChild(s); })();</script>';
		}
	}

	public function injectWidgetCode() {
		echo $this->getWidgetCode();
	}

	public function injectReviewRequestCheckbox() {
		$guid = $this->getGuid();
		if ($guid !== null && $this->isCheckboxEnabled()) {
			echo '<p><input type="checkbox" name="'.GETREVIEW_CHECKBOX_KEY.'" id="'.GETREVIEW_CHECKBOX_KEY.'" checked> '.get_option(GETREVIEW_CHECKBOX_TEXT).' <a href="https://app.getreview.pl/pdf/terms/'.$guid.'" rel="nofollow" target="_blank">'.__('Terms of Service', GETREVIEW_TEXT_DOMAIN).'</a></p>';
		}
	}

	public function checkReviewRequestAtCheckout($orderId) {
		if ($_POST[GETREVIEW_CHECKBOX_KEY]) {
			update_post_meta($orderId, GETREVIEW_CHECKBOX_KEY, sanitize_text_field($_POST[GETREVIEW_CHECKBOX_KEY]));
		}
	}

	public function createOrderUpdateWebhook() {
		$guid = $this->getGuid();
		if ($guid === null) {
			return;
		}

		global $wpdb;
		$webhookKeyName = 'GetReview Webhook Key';
		$deliveryUrl = GETREVIEW_WEBHOOK_URL.$guid;

		$queryKeys = "SELECT consumer_secret FROM {$wpdb->prefix}woocommerce_api_keys WHERE `description` LIKE %s";
		$queryWebhooks = "SELECT webhook_id FROM {$wpdb->prefix}wc_webhooks WHERE delivery_url='%s'";

		$sql = $wpdb->prepare($queryKeys, $webhookKeyName.'%');
		$foundKeys = $wpdb->get_results($sql);
		if (count($foundKeys) == 0) {
			$this->create_keys($webhookKeyName, 1, 'read');
		}

		$sql = $wpdb->prepare($queryWebhooks, $deliveryUrl);
		$foundWebhooks = $wpdb->get_results($sql);
		if (count($foundWebhooks) == 0) {
			$sql = $wpdb->prepare($queryKeys, $webhookKeyName.'%');
			$foundKeys = $wpdb->get_results($sql);

			if (count($foundKeys) > 0) {
				$webhook = new \WC_Webhook();
				$webhook->set_name('Getreview: Order updated');
				$webhook->set_topic('order.updated');
				$webhook->set_status('active');
				$webhook->set_user_id(1);
				$webhook->set_delivery_url($deliveryUrl);
				$webhook->set_secret($foundKeys[0]->consumer_secret);

				$webhookDataStore = new \WC_Webhook_Data_Store();
				$webhookDataStore->create($webhook);
			}
		}

		// Clean-up old webhooks
		$queryOldWebhooks = "DELETE FROM {$wpdb->prefix}wc_webhooks WHERE delivery_url LIKE '%s' AND delivery_url <> '%s'";
		$sql = $wpdb->prepare($queryOldWebhooks, GETREVIEW_WEBHOOK_URL.'%', $deliveryUrl);
		$wpdb->query($sql);
	}
}
