<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post" action="options.php">
    <?php
    settings_fields("section"); ?>
    <?php
    $tab = "general";
    if(isset($_GET["page"]) && !empty($_GET["page"])) {
        $page = sanitize_text_field($_GET["page"]);
        if($page == "wiremo-widget") {
            if(isset($_GET["tab"]) && !empty($_GET["tab"])) {
                $tab = sanitize_text_field($_GET["tab"]);
            }
        }
    }
    ?>
    <ul class="nav nav-pills wiremo-tabs-options">
        <li class="<?php if(isset($tab)) { if($tab == 'general') { echo 'active'; } else if($tab != 'manual-review-request' && $tab != 'automated-review-request') { echo 'active'; } } else { echo 'active'; } ?>"><a href="<?php echo esc_url( admin_url( 'admin.php?page=wiremo-widget&tab=general' ) ); ?>"><?php echo __("General","wiremo-widget"); ?></a></li>
        <li class="<?php if(isset($tab)) { if($tab == 'automated-review-request') { echo 'active'; } } ?>"><a href="<?php echo esc_url( admin_url( 'admin.php?page=wiremo-widget&tab=automated-review-request' ) ); ?>"><?php echo __("Automated Review Request", "wiremo-widget"); ?></a></li>
        <li class="<?php if(isset($tab)) { if($tab == 'manual-review-request') { echo 'active'; } } ?>"><a href="<?php echo esc_url( admin_url( 'admin.php?page=wiremo-widget&tab=manual-review-request' ) ); ?>"><?php echo __("Past Orders Review Request", "wiremo-widget"); ?></a></li>
    </ul>
    <?php if( isset($_GET["settings-updated"]) ) { ?>
        <div id="message" class="updated inline">
            <p><strong><?php echo __("Your settings have been saved.","wiremo-widget"); ?></strong></p>
        </div>
    <?php } ?>
    <div class="tab-content">
        <div id="general" class="tab-pane fade <?php if(isset($tab)) { if($tab == 'general') { echo 'in active'; } else if($tab != 'manual-review-request' && $tab != 'automated-review-request') { echo 'in active'; } } else { echo 'in active'; } ?>">
            <?php
            if($tab != 'manual-review-request') {
                do_settings_sections("theme-options");
            }
            ?>
        </div>
        <div id="automated-review-request" class="tab-pane fade <?php if(isset($tab)) { if($tab == 'automated-review-request') { echo 'in active'; } } ?>">
            <?php
            if($tab != 'manual-review-request') {
                $api_key = get_option("wiremo-api-key");
                $wiremo_email_template = get_option("wiremo_email_template");
                $wiremo_templates = get_wiremo_email_templates($api_key);
                if(!get_option("wiremo_email_template") && count($wiremo_templates) == 0) {
                    ?>
                    <div class="alert alert-info arr-no-campaigns">
                        <strong><?php echo __("Notice!","wiremo-widget"); ?></strong> <?php echo __("Please create templates for Automated Review Request","wiremo-widget"); ?>
                    </div>
                    <?php
                }
                else if(!get_option("wiremo_email_template") && count($wiremo_templates) > 0) {
                    do_settings_sections("wiremo-automated-request");
                }
                else {
                    do_settings_sections("wiremo-automated-request");
                }
            }
            ?>
        </div>
        <?php
        $class = "wiremo-no-campaigns";
        if(get_option("wiremo_total_campaigns")) {
            $wiremo_total_campaigns = json_decode(get_option("wiremo_total_campaigns"));
            if($wiremo_total_campaigns->count > 0) {
                $class = "wiremo-has-campaigns";
            }
            else {
                $class = "wiremo-no-campaigns";
            }
        }
        else {
            $class = "wiremo-no-campaigns";
        }
        ?>
        <div id="manual-review-request" class="tab-pane fade <?php if(isset($tab)) { if($tab == 'manual-review-request') { echo 'in active'; } } ?> <?php echo $class; ?>">
            <?php
            if($tab == 'manual-review-request') {
                $api_key = get_option("wiremo-api-key");
                $wiremo_email_template = get_option("wiremo_manual_email_template");
                $wiremo_templates = get_wiremo_email_templates($api_key);
                $customer_orders = wc_get_orders( $args = array(
                    'limit' => 1,
                    'post_status' => 'wc-completed',
                    'date_completed' => '>0',
                ) );
                if(count($customer_orders) == 0) {
                    ?>
                    <div class="alert alert-info mrr-no-campaigns">
                        <strong><?php echo __("Notice!","wiremo-widget"); ?></strong> <?php echo __("You don't have any completed order","wiremo-widget"); ?>
                    </div>
                    <?php
                }
                else {
                    if(!get_option("wiremo_manual_email_template") && count($wiremo_templates) == 0) {
                        ?>
                        <div class="alert alert-info mrr-no-campaigns">
                            <strong><?php echo __("Notice!","wiremo-widget"); ?></strong> <?php echo __("Please create templates for Manual Review Request","wiremo-widget"); ?>
                        </div>
                        <?php
                    }
                    else if(!get_option("wiremo_manual_email_template") && count($wiremo_templates) > 0) {
                        do_settings_sections("wiremo-manual-request");
                    }
                    else {
                        do_settings_sections("wiremo-manual-request");
                    }
                }
            }
            ?>
        </div>
    </div>
    <?php
    if (isset($tab)) {
        if ($tab == 'general' || $tab == 'automated-review-request') {
            submit_button();
        }
        else if($tab != 'manual-review-request' && $tab != 'automated-review-request') {
            submit_button();
        }
    } else {
        submit_button();
    }
    ?>
</form>