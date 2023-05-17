<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
$current_user_id = get_current_user_id();
$user_info = get_userdata($current_user_id);
if(!empty($user_info->first_name)) {
    $name = $user_info->first_name;
}
else {
    $name = "";
}
if(!empty($user_info->last_name)) {
    $surname = $user_info->last_name;
}
else {
    $surname = "";
}
if(!empty($user_info->user_email)) {
    $email = $user_info->user_email;
}
$step = "step2";
(strlen($name) == 0 || strlen($surname) == 0) ? $step = "step3" : $step = "step2";
?>
<div class="load-account-box"></div>
<div class="step1">
    <table class="form-table">
        <tbody>
        <tr>
            <th><?php echo __("Start using Wiremo","wiremo-widget"); ?></th>
            <td>
                <button class="connect-account btn btn-primary"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("Connect your Wiremo account","wiremo-widget"); ?>
                </button>
            </td>
        </tr>
        <tr>
            <th><?php echo __("I don’t have a Wiremo account","wiremo-widget"); ?></th>
            <td>
                <button data-step="<?php echo $step; ?>" class="wrpw-register-step btn btn-primary"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("Create Wiremo account","wiremo-widget"); ?>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="step2">
    <table class="form-table">
        <tbody>
        <tr>
            <th class="wr-user-info" colspan="2">
                <p>Hey <?php echo $name; ?>,<br>
                    Wiremo is a customer reviews <strong>service</strong> - the reviews are stored on Wiremo’s servers.<br>
                    To use the plugin we need to connect your account to <a target="_blank" href="https://wiremo.co/">Wiremo.co</a> Dashboard.</p>
            </th>
        </tr>
        <tr>
            <th><?php echo __("First name","wiremo-widget"); ?></th>
            <td>
                <input readonly class="wrpw-first-name" name="first-name" type="text" value="<?php echo $name; ?>">
            </td>
        </tr>
        <tr>
            <th><?php echo __("Last name","wiremo-widget"); ?></th>
            <td>
                <input readonly class="wrpw-last-name" name="last-name" type="text" value="<?php echo $surname; ?>">
            </td>
        </tr>
        <tr>
            <th><?php echo __("Email","wiremo-widget"); ?></th>
            <td>
                <input readonly class="wrpw-email" name="email" type="email" value="<?php echo $email; ?>">
            </td>
        </tr>
        <tr>
            <th class="wr-user-info" colspan="2">
                <p>Can we use your WordPress account details to register you in Wiremo Dashboard?</p>
            </th>
        </tr>
        <tr>
            <th colspan="2" class="wrpw-register-options">
                <button data-action="step2" class="wrpw-register-account btn btn-primary"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("Yes, use this details ","wiremo-widget"); ?>
                </button>
                <button class="wrpw-register-back btn btn-primary"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("No, I would like to change","wiremo-widget"); ?>
                </button>
                <p><?php echo __("or","wiremo-widget"); ?> <a class="connect-account" href="#"><?php echo __("I already have an account","wiremo-widget") ?></a></p>
            </th>
        </tr>
        </tbody>
    </table>
</div>
<div class="step3">
    <table class="form-table">
        <form method="POST" action="">
            <tbody>
            <tr>
                <th class="wr-user-info" colspan="2">
                    <p>Hey <?php echo $name; ?>,<br>
                        Wiremo is a customer reviews <strong>service</strong> - the reviews are stored on Wiremo’s servers.<br>
                        To use the plugin we need to connect your account to <a target="_blank" href="https://wiremo.co/">Wiremo.co</a> Dashboard.</p>
                </th>
            </tr>
            <tr>
                <th><?php echo __("First name","wiremo-widget"); ?></th>
                <td>
                    <input class="wrpw-first-name" name="first-name" type="text" value="<?php echo $name; ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo __("Last name","wiremo-widget"); ?></th>
                <td>
                    <input class="wrpw-last-name" name="last-name" type="text" value="<?php echo $surname; ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo __("Email","wiremo-widget"); ?></th>
                <td>
                    <input class="wrpw-email" name="email" type="email" value="<?php echo $email; ?>">
                </td>
            </tr>
            <tr>
                <th class="wrpw-register-options" colspan="2">
                    <button data-action="step3" class="wrpw-register-account btn btn-primary"
                            data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("Sign UP","wiremo-widget"); ?>
                    </button>
                    <p><?php echo __("or","wiremo-widget"); ?> <a class="connect-account" href="#"><?php echo __("I already have an account","wiremo-widget") ?></a></p>
                </th>
            </tr>
            </tbody>
        </form>
    </table>
</div>