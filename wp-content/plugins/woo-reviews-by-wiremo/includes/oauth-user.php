<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>
<script>
    var urlOauth = "<?php echo WRPW_URLAPP; ?>/v1/ecommerce/OAuth";
    var popupBlockerChecker = {
        check: function (popup_window) {
            var _scope = this;
            if (popup_window) {
                if (/chrome/.test(navigator.userAgent.toLowerCase())) {
                    setTimeout(function () {
                        _scope._is_popup_blocked(_scope, popup_window);
                    }, 200);
                } else {
                    popup_window.onload = function () {
                        _scope._is_popup_blocked(_scope, popup_window);
                    };
                }
            } else {
                _scope._displayError();
            }
        },
        _is_popup_blocked: function (scope, popup_window) {
            if ((popup_window.innerHeight > 0) == false) {
                scope._displayError();
            }
        },
        _displayError: function () {
            alert("Popup Blocker is enabled! Please add this site to your exception list.");
        }
    };
    var popup = window.open(urlOauth, "Login in wiremo", "toolbar=0,location=0,directories=0,status=1,menubar=0,titlebar=0,scrollbars=1,resizable=1,width=620,height=620");
    popupBlockerChecker.check(popup);
    function gotoStepOne() {
        jQuery(".wiremo-container .step2").removeClass("show");
        jQuery(".wiremo-container .step2").addClass("hidden");
        jQuery(".wiremo-container .step3").removeClass("show");
        jQuery(".wiremo-container .step3").addClass("hidden");
        jQuery(".wiremo-container .step1").removeClass("hidden");
        jQuery(".wiremo-container .step1").addClass("show");
    }
    window.addEventListener('message', function (event) {
        const origin = event.origin || event.originalEvent.origin;
        if (origin == '<?php echo WRPW_URLAPP; ?>') {
            var response = JSON.parse(event.data);
            if (response.apiKey) {
                var apiKey = response.apiKey;
                jQuery.post(ajaxurl, {action: 'wiremoGetSiteId', nonce: document.getElementById('nonceWiremoGetSiteId').innerText, apiKey: apiKey}, function (data) {
                    jQuery(".connect-account").bootstrapBtn('loading');
                    var requestData = JSON.parse(data);
                    if (requestData.success == true) {
                        jQuery.post(ajaxurl, {action: 'wiremoAddApiKey', nonce: document.getElementById('nonceWiremoAddApiKey').innerText, apiKey: apiKey}, function () {
                            jQuery(".connect-account").bootstrapBtn('loading');
                            jQuery.post(ajaxurl, {action: 'wiremoAddRegisterHook', nonce: document.getElementById('nonceWiremoAddRegisterHook').innerText, apiKey: apiKey}, function () {
                                location.reload(true);
                            });
                        });
                    }
                    else {
                        if(requestData.message) {
                            jQuery(".connect-account").bootstrapBtn('reset');
                            jQuery(".wiremo-container .load-account-box").html('<div class="notice notice-error is-dismissible"><p>' + requestData.message +'</p></div>');
                            jQuery(".wiremo-container .load-account-box").removeClass("hidden");
                            jQuery(".wiremo-container .load-account-box").addClass("show");
                            gotoStepOne();
                            jQuery(document).on("click",".wrmr-connect-yes",function () {
                                var url = "<?php echo home_url(); ?>";
                                jQuery.post(ajaxurl, {action: 'wiremoValidateSite', nonce: document.getElementById('nonceWiremoValidateSite').innerText, apiKey: apiKey,url: url}, function (data) {
                                    var validateData = JSON.parse(data);
                                    if(validateData.success == true) {
                                        jQuery.post(ajaxurl, {action: 'wiremoAddApiKey', nonce: document.getElementById('nonceWiremoAddApiKey').innerText, apiKey: apiKey}, function () {
                                            jQuery(".connect-account").bootstrapBtn('loading');
                                        });
                                        jQuery.post(ajaxurl, {action: 'wiremoAddRegisterHook', nonce: document.getElementById('nonceWiremoAddRegisterHook').innerText, apiKey: apiKey}, function () {
                                            location.reload(true);
                                        });
                                    }
                                    else {
                                        jQuery(".connect-account").bootstrapBtn('reset');
                                        jQuery(".wiremo-container .load-account-box").html('<div class="notice notice-error is-dismissible"><p>' + validateData.message +'</p></div>');
                                        jQuery(".wiremo-container .load-account-box").removeClass("hidden");
                                        jQuery(".wiremo-container .load-account-box").addClass("show");
                                        gotoStepOne();
                                    }
                                });
                                return false;
                            });
                            jQuery(document).on("click",".wrmr-connect-no",function () {
                                jQuery.post(ajaxurl, {action: 'wiremoNoValidate', nonce: document.getElementById('nonceWiremoNoValidate').innerText,}, function (data) {
                                    jQuery(".connect-account").bootstrapBtn('reset');
                                    jQuery(".wiremo-container .load-account-box").html('<div class="notice notice-error is-dismissible"><p>' + data +'</p></div>');
                                    jQuery(".wiremo-container .load-account-box").removeClass("hidden");
                                    jQuery(".wiremo-container .load-account-box").addClass("show");
                                    gotoStepOne();
                                });
                                return false;
                            });
                        }
                    }
                });
            }
        }
    });
</script>