<?php


class wiremo_review_widget extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'wiremo_review_widget',

__('Wiremo Review Widget for Elementor', 'wiremo_review_widget_domain'),

array( 'description' => __( 'Wiremo Review Widget for Elementor' ), )
);
}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

echo __( ' ', 'wiremo_review_widget_domain' );
echo $args['after_widget'];
}

public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( ' ', 'wiremo_review_widget_domain' );
}
?>

<p>
<label>Wiremo Review Widget has been added. Please, don't forget to hit the <b>Update</b> button. In order to see the result open product page in <b>incognito</b>.</label>
</p>

<?php
}


// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}

// Class wiremo_review_widget ends here
}

// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wiremo_review_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
// end of  wiremo widget custom script

// start of wiremo product review widget universal
class wiremo_review_widget_universal extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'wiremo_review_widget_universal',

__('Wiremo Review Widget any Builder', 'wiremo_review_widget_universal_domain'),

array( 'description' => __( 'Wiremo Review Widget any Builder' ), )
);
}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

echo __( ' ', 'wiremo_review_widget_universal_domain' );
	

echo wiremo_before_woocommerce_output_related_products_universal();
echo $args['after_widget'];
}

	
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( ' ', 'wiremo_review_widget_universal_domain' );
}
?>

<p>
<label>Wiremo Review Widget any Builder has been added. Please, don't forget to hit the <b>Update</b> button. In order to see the result open product page in <b>incognito</b>.</label>
</p>

<?php
}


// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}

// Class wiremo_review_widget_universal ends here
}

// Register and load the widget
function wpk_load_widget() {
    register_widget( 'wiremo_review_widget_universal' );
}
add_action( 'widgets_init', 'wpk_load_widget' );
// End of wiremo product review widget universal



// Start of wiremo product star review widget universal
class wiremo_star_review_widget_universal extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'wiremo_star_review_widget_universal',

__('Wiremo Rating Widget any Builder', 'wiremo_star_review_widget_universal_domain'),

array( 'description' => __( 'Wiremo Rating Widget any Builder' ), )
);
}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

echo __( ' ', 'wiremo_star_review_widget_universal_domain' );
	

echo wiremo_woocommerce_template_single_rating_universal();

echo $args['after_widget'];
}

	
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( ' ', 'wiremo_star_review_widget_universal_domain' );
}
?>

<p>
<label>Wiremo Rating Widget any Builder has been added. Please, don't forget to hit the <b>Update</b> button. In order to see the result open product page in <b>incognito</b>.</label>
</p>

<?php
}


// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}

// Class wiremo_star_review_widget_universal ends here
}

// Register and load the widget
function wpk2_load_widget() {
    register_widget( 'wiremo_star_review_widget_universal' );
}
add_action( 'widgets_init', 'wpk2_load_widget' );
// END of wiremo product star review widget universal 

// wordpres wiremo widgets END


function wiremo_woocommerce_template_single_rating_universal() {
    global $product;
    
    if(!empty($product)) {    
    $id = $product->get_id();
    }
	
	
echo "<a href=#wiremo-widget><wiremo-widget-lite data-type=2 data-source=".$id."></wiremo-widget-lite></a>";

	
}

    
//Duplicat product action hook
function action_woocommerce_duplicate_product( $post_id ) { 

	$post_type = get_post_type($post_id);
		if ($post_type == "product") {
			if (get_post_meta($post_id, 'wiremo-review-count')) {
                            delete_post_meta($post_id, 'wiremo-review-count');
                        }

                        if (get_post_meta($post_id, 'wiremo-review-total')) {
                            delete_post_meta($post_id, 'wiremo-review-total');
                        }

                        if (get_post_meta($post_id, 'wiremo-review-average')) {
                            delete_post_meta($post_id, 'wiremo-review-average');
                        }

			} // if product
}; 
add_action( 'woocommerce_duplicate_product', 'action_woocommerce_duplicate_product', 10, 3 ); 



function wiremo_before_woocommerce_output_related_products_universal()
{
    $location = esc_attr(get_option("wiremo_widget_location"));

    global $post;
    $countReview = 0;
    $siteId = esc_attr(get_option("wiremo-siteId"));
    $path = strtok($_SERVER["REQUEST_URI"],'?');
    $productId = rawurlencode($post->ID);
    if (class_exists('SitePress')) {
        $wrpw_wpml_data = new WiremoWpmlData();
        $productId= $wrpw_wpml_data->wrpw_get_main_product_id($post->ID);
    } else {
        $productId = $post->ID;
    }

    $productUrl = rawurlencode(get_the_permalink());
    $title = rawurlencode(get_the_title());
    $productFullPath = get_the_permalink();
    $productPath = str_replace(home_url(),"",$productFullPath);
    $productPath = rawurlencode($productPath);
    if(get_post_meta($productId, "wiremo-review-total", true)) {
        $wiremoReviewTotal = get_post_meta($productId, "wiremo-review-total", true);
        $wiremoTotalRating = json_decode($wiremoReviewTotal);
        $countReview = $wiremoTotalRating->count;
    }
    $custom_text_reviews = esc_attr(get_option("wiremo_custom_text_reviews"));
    $custom_text_reviews = str_replace("{count}",$countReview,$custom_text_reviews);
    $widget_location = get_option("wiremo_widget_location");
    if(get_option("wiremo_automated_authentification")) {
        if(is_user_logged_in()) {
            $current_user_id = get_current_user_id();
            $token = wiremo_get_user_token($current_user_id);
            if($token != "") {
                $token = ',token:"'.$token.'"';
            }
        }
        else {
            $token = "";
        }
    }
    else {
        $token = "";
    }

    
        if(!empty($custom_text_reviews)) {
            ?>
            <h2 class="wiremo-custom-text-reviews"><?php echo $custom_text_reviews; ?></h2>
            <?php
        }
    

    $product = new \stdClass();
    $product->title = get_the_title();
    $product->url = get_the_permalink();
    $product->image = get_the_post_thumbnail_url();
    $product->sku = getProductIdentifier($productId);

	function get_permalinkStructure_widget(){
        $structure_permalink = get_option("permalink_structure");
        if ($structure_permalink == ''){
            $str_permalink = 'plainPermalink';
        }else{
            $str_permalink = 'notRelevant';
        }

        return $str_permalink;
    }
	$str_permalink = get_permalinkStructure_widget();

    echo "<div id='customer-reviews-box'><div id='wiremo-widget' data-platform='woocommerce'></div></div>";
    wp_register_script('wiremo-customer-reviews-box-script', '', [], '', true);
    wp_enqueue_script('wiremo-customer-reviews-box-script');
    wp_add_inline_script('wiremo-customer-reviews-box-script', '!function(){var e=window.wiremo_config?new window.wiremo_config:{},t=Object.assign({platform:"woocommerce",permalinkStructure:"'.$str_permalink.'",reviewType:"product",product:'.json_encode($product).',reviewSource:"'.(string)$productId.'",identifier:"'.(string)$productId.'"'.$token.'},e),n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src="'.WRPW_URLWIDGET.'?k='.$siteId.'&w="+encodeURIComponent(JSON.stringify(t));var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(n,o)}();');
    }
//widget-universal

// Main plugin functions starts

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wrpw_wpml_data;
if (class_exists('SitePress')) {
    include dirname( __FILE__ ).'/includes/class-wpml-data.php';
}
$location_widget = get_option("wiremo_widget_location");
function wiremo_validate_gravatar($id_or_email) {
    //id or email code borrowed from wp-includes/pluggable.php
    $email = '';
    if ( is_numeric($id_or_email) ) {
        $id = (int) $id_or_email;
        $user = get_userdata($id);
        if ( $user )
            $email = $user->user_email;
    } elseif ( is_object($id_or_email) ) {
        // No avatar for pingbacks or trackbacks
        $allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
        if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
            return false;

        if ( !empty($id_or_email->user_id) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_userdata($id);
            if ( $user)
                $email = $user->user_email;
        } elseif ( !empty($id_or_email->comment_author_email) ) {
            $email = $id_or_email->comment_author_email;
        }
    } else {
        $email = $id_or_email;
    }

    $hashkey = md5(strtolower(trim($email)));
    $uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

    $data = wp_cache_get($hashkey);
    if (false === $data) {
        $response = wp_remote_head($uri);
        if( is_wp_error($response) ) {
            $data = 'not200';
        } else {
            $data = $response['response']['code'];
        }
        wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);

    }
    if ($data == '200'){
        return true;
    } else {
        return false;
    }
}

function getProductIdentifier($productId) {
    $wp_product = new WC_Product($productId);

    $sku = $wp_product->get_sku();

    if ($sku) {
        return $sku;
    }

    return (string)$productId;
}

function wiremo_get_user_token($current_user_id) {
    $user_info = get_userdata($current_user_id);
    $token = "";

    if(count($user_info->roles) > 0) {
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
        if(wiremo_validate_gravatar($email)) {
            $avatar_url = get_avatar_url($email);
        }
        else {
            $avatar_url = "";
        }
        $urlSite = WRPW_URLAPP."/v1/ecommerce/userLogin/";
        $wi_api_key = get_option("wiremo-api-key");
        $post_fields = array();
        $post_fields["apiKey"] = $wi_api_key;
        $post_fields["name"] = $name;
        $post_fields["surname"] = $surname;
        $post_fields["email"] = $email;
        $post_fields["avatarUrl"] = $avatar_url;
        $response = wp_remote_post( $urlSite, array(
                'method' => 'POST',
                'body' => $post_fields
            )
        );

        if ( is_wp_error( $response ) ) {
            $token = "";
        } else {
            $data = json_decode($response["body"]);
            $token = $data;
        }
    }

    return $token;
}

function wiremo_user_scripts()
{   wp_enqueue_style('font-awesome-site', plugins_url('/css/font-awesome.min.css', __FILE__), '1.0', 'screen');
    wp_enqueue_style('wiremo-style-css', plugins_url('/css/style.css', __FILE__), '3.9', true);
    wp_enqueue_script('wiremo-script-users-js', plugins_url('/js/scripts.js', __FILE__), array('jquery'), '3.3', true);
}

add_shortcode( 'do_hook', function( $atts = array(), $content = null, $tag = ''){

  if (!is_plugin_active( 'elementor/elementor.php' )) {

	if ( isset( $atts['hook'] ) ) {
		do_action( $hook );
	}
	return;
} // if elementor plugin is active

});

add_action('wp_enqueue_scripts', 'wiremo_user_scripts');

function wiremo_disable_woo()
{
    if (current_user_can('administrator')) {
        ?>
        <input name="wiremo_disable_woo" type="checkbox"
               value="1" <?php checked('1', get_option('wiremo_disable_woo')); ?> />
        <?php
    }
}

function display_wiremo_widget()
{
    if (current_user_can('administrator')) {
        ?>
        <input id="wiremo_widget_display" name="wiremo_widget_display" type="checkbox"
               value="1" <?php checked('1', get_option('wiremo_widget_display')); ?> />
        <?php
    }
}

function display_wiremo_schema()
{
    if (current_user_can('administrator')) {
        ?>
        <input id="wiremo_generate_schema" name="wiremo_generate_schema" type="checkbox"
        value="1" <?php if (!get_option('wiremo_generate_schema')) { add_option('wiremo_generate_schema', '1'); }; checked('1', get_option('wiremo_generate_schema', '1'));?> />
        <?php
    }
}
function display_wiremo_location_widget()
{
    if (current_user_can('administrator')) {

if (!is_plugin_active( 'elementor/elementor.php' )) {

        $options = array("name" => "Location for widget",
            "desc" => "Set location for widget",
            "id" => "wiremo_widget_location",
            "std" => "Footer",
            "type" => "select",
            "options" => array("Footer" => "Footer", "Tab" => "Tab"));
}

else {
  $options = array("name" => "Location for widget",
      "desc" => "Set location for widget",
      "id" => "wiremo_widget_location",
      "std" => "Footer",
      "type" => "select",
      "options" => array("Footer" => "Footer"));

}

        ?>

        <select <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> class="of-input" name="<?php if (isset($options['id'])) {
            echo $options['id'];
        } ?>" id="<?php if (isset($options['id'])) {
            echo $options['id'];
        } ?>">
            <?php
            if (is_array($options["options"])) {
                foreach ($options["options"] as $key => $option) {
                    $selected = '';
                    if ($options["std"] != '') {
                        if ($key == get_option("wiremo_widget_location")) {
                            $selected = ' selected="selected"';

                        }
                    }
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }
}

function display_wiremo_custom_text_reviews()
{
    if (current_user_can('administrator')) {
        ?>
        <input data-toggle="tooltip" data-placement="right" title="Use tag {count} to show the number of reviews." <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> type="text" name="wiremo_custom_text_reviews" id="wiremo_custom_text_reviews"
               value="<?php if(get_option('wiremo_custom_text_reviews')) { echo esc_attr(get_option('wiremo_custom_text_reviews')); } ?>"/>
        <?php
    }
}

function display_wiremo_widget_tab()
{
    if (current_user_can('administrator')) {
        $disabled = ' ';
        if (get_option("wiremo_widget_location")) {
            $location = esc_attr(get_option("wiremo_widget_location"));
            if ($location == "Footer") {
                $disabled = "disabled";
            }
        }
        ?>
        <input data-toggle="tooltip" data-placement="right" title="Use tag {count} to show the number of reviews." <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> class="custom-tab-name"  <?php echo $disabled; ?> type="text" name="wiremo_custom_tab_name"
               id="wiremo_custom_tab_name" value="<?php if(!(get_option('wiremo_custom_tab_name'))) { echo "Reviews ({count})"; } else { echo esc_attr(get_option('wiremo_custom_tab_name')); } ?>"/>
        <?php
    }
}

function display_wiremo_mini_widget_home()
{
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> name="wiremo_hide_mini_widget_home" id="wiremo_hide_mini_widget_home" type="checkbox"
                                                                                    value="1" <?php checked('1', get_option('wiremo_hide_mini_widget_home')); ?> />
        <?php
    }
}

function display_wiremo_mini_widget_cat()
{
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> name="wiremo_hide_mini_widget_cat" id="wiremo_hide_mini_widget_cat" type="checkbox"
                                                                                    value="1" <?php checked('1', get_option('wiremo_hide_mini_widget_cat')); ?> />
        <?php
    }
}

function display_wiremo_mini_widget_prod()
{
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> name="wiremo_hide_mini_widget_prod" id="wiremo_hide_mini_widget_prod" type="checkbox"
                                                                                    value="1" <?php checked('1', get_option('wiremo_hide_mini_widget_prod')); ?> />
        <?php
    }
}


function display_wiremo_mini_widget()
{
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> name="wiremo_hide_mini_widget" id="wiremo_hide_mini_widget" type="checkbox"
                                                                                    value="1" <?php checked('1', get_option('wiremo_hide_mini_widget')); ?> />
        <?php
    }
}

function show_wiremo_custom_text_related() {
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> name="wiremo_show_custom_text_related" id="wiremo_show_custom_text_related" type="checkbox"
                                                                                    value="1" <?php checked('1', get_option('wiremo_show_custom_text_related')); ?> />
        <?php
    }
}

function display_wiremo_custom_text_related()
{
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo " disabled "; if(!(get_option("wiremo_show_custom_text_related"))) { echo " disabled "; } ?> type="text" name="wiremo_related_custom_text" id="wiremo_related_custom_text"
                                                                                                                                                                  value="<?php if(!(get_option('wiremo_related_custom_text'))) { echo "Other top rated products"; } else { echo esc_attr(get_option('wiremo_related_custom_text')); } ?>"/>
        <?php
    }
}

function display_wiremo_related_products_sort()
{
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo " disabled "; if(!(get_option("wiremo_show_custom_text_related"))) { echo " disabled "; } ?> name="wiremo_related_products_sort" id="wiremo_related_products_sort" type="checkbox"
                                                                                                                                                                  value="1" <?php checked('1', get_option('wiremo_related_products_sort')); ?> />
        <?php
    }
}
function display_wiremo_automated_authentification()
{
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo " disabled "; ?> name="wiremo_automated_authentification" id="wiremo_automated_authentification" type="checkbox"
                                                                                      value="1" <?php checked('1', get_option('wiremo_automated_authentification')); ?> />
        <?php
    }
}

function display_wiremo_import_reviews()
{
    if (current_user_can('administrator')) {
        include dirname( __FILE__ ).'/includes/import-statistics.php';
        ?>
        <button <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> data-toggle="tooltip" data-placement="top" title="Use this button to sync Wiremo with WooCommerce if you imported reviews via CSV or imported reviews manually.
Use this button if you Disabled/Enabled Wiremo plugin for some reason." class="import-from-wiremo btn btn-primary"
                                                                                     data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("Sync Wiremo","wiremo-widget"); ?>
        </button>
        <div id="wiremo-import-bar">
            <div id="wiremo-import-bar-percent"></div>
        </div>
        <div class="notification-import from-wiremo hidden alert alert-success"></div>
        <?php
    }
}

function display_wiremo_sync_products()
{
    if (current_user_can('administrator')) {
        ?>
        <button <?php if(!(get_option('wiremo_widget_display'))) echo "disabled"; ?> class="wiremo-import-reviews btn btn-primary"
                                                                                     data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("Export reviews to Wiremo","wiremo-widget"); ?>
        </button>
        <div class="notification-import to-wiremo hidden alert alert-success"></div>
        <?php
    }
}

//functions for automated review request
function wiremo_enable_automated_review_request() {
    if (current_user_can('administrator')) {
        ?>
        <input <?php if(!(get_option('wiremo_widget_display'))) echo "enabled"; ?> name="wiremo_automated_review_request" id="wiremo_automated_review_request" type="checkbox"
                                                                                      value="1" <?php checked('1', get_option('wiremo_automated_review_request')); ?> />
        <?php
    }
}


// vj new added begin
//Arrfunction
    if(!function_exists("wiremo_woo_order_status_completed")) {
        function wiremo_woo_order_status_completed($order_id) {
            $order = wc_get_order($order_id);
            $api_key = get_option("wiremo-api-key");
            $campaign_id = get_option("wiremo_email_template");
            $shop_page_id = get_option('woocommerce_shop_page_id');
            $shop_page_name = get_the_title($shop_page_id);
            $order_meta = get_post_meta($order_id);
            $items = $order->get_items();
            $first_name = $order_meta["_billing_first_name"][0];
            $last_name = $order_meta["_billing_last_name"][0];
            $email = $order_meta["_billing_email"][0];
            if (wiremo_validate_gravatar($email)) {
                $avatar_url = get_avatar_url($email);
            } else {
                $avatar_url = '';
            }
            $item = array();
            foreach ($items as $item_id => $item_data) {
                $product_name = $item_data['name'];
                $product_id = $item_data['product_id'];
                $p = new WC_Product($product_id);
                $attr = $p->get_attribute('removefromarr');
                $product_description = get_post($product_id)->post_excerpt;
                $productId = $item_data['product_id'];
                if (class_exists('SitePress')) {
                    $wrpw_wpml_data = new WiremoWpmlData();
                    $productId= $wrpw_wpml_data->wrpw_get_main_product_id($item_data['product_id']);
                } else {
                    $productId = $item_data['product_id'];
                }
                $product_url = get_the_permalink($productId);
                $product_path = str_replace(home_url(),"",$product_url);
                $product_path = rawurlencode($product_path);
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
                $image_product = $image[0];
                if(isset($image_product) && !empty($image_product)) {
                    $image_product = $image[0];
                }
                else {
                    $image_product = "";
                }
                $date = get_post_meta($order_id,"_completed_date",true);
                $date = new DateTime($date);
                $order_date = $date->format('Y-m-d\TH:i:s.u');
                $order_date_split = explode(".", $order_date);
                $order_date = $order_date_split[0] . ".000Z";

                if ($attr != 1) 
                {
                $item[] = array(
                    "name" => $product_name,
                    "description" => $product_description,
                    "url" => $product_url,
                    "path" => $product_path,
                    "identifier" => (string)$productId,
                    "orderDate" => $order_date,
                    "orderId" => (string)$order_id,
                    "imageUrl" => $image_product
                );
                } // if attribute removefromarr is 1
            }
            for($i=0;$i<count($item);$i++) {
                $keys = array_keys($item[$i],"");
                foreach($keys as $key) {
                    unset($item[$i][$key]);
                }
            }
            $post_fields = array(
                "apiKey" => $api_key,
                "campaignId" => $campaign_id,
                "shopName" => $shop_page_name,
                "email" => $email,
                "name" => $first_name,
                "surname" => $last_name,
                "avatarUrl" => $avatar_url,
                "items" => $item
            );
            $key_post_fields = array_keys($post_fields,"");
            foreach($key_post_fields as $key) {
                unset($post_fields[$key]);
            }
            $url_api = WRPW_URLAPP."/v1/ecommerce/reviewRequest";
            $response = wp_remote_post( $url_api, array(
                    'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
                    'method' => 'POST',
                    'body' => json_encode($post_fields)
                )
            );
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                echo wiremo_wh_log("----------Request url route: /v1/ecommerce/reviewRequest return error ".esc_attr($error_message)." error ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
            } else {
                $data = json_decode($response["body"]);
                if(isset($data->success) && !empty($data->success) && $data->success > 0) {

                }
                else {
                    echo wiremo_wh_log("----------Request url route: /v1/ecommerce/reviewRequest return error ".$data->error." error ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                }
            }
        }
    }
    $wiremo_auth_rev_req = get_option("wiremo_automated_review_request");
    if($wiremo_auth_rev_req) {
        add_action( 'woocommerce_order_status_completed', 'wiremo_woo_order_status_completed', 10, 1);
    }


// vj new added end

// function for get templates API
function display_wiremo_email_templates()
{
    if (current_user_can('administrator')) {
        $api_key = get_option("wiremo-api-key");
        $wiremo_email_template = get_option("wiremo_email_template");
        $wiremo_templates = get_wiremo_email_templates($api_key);
        $j = 0;
        $default_template_name = '';
        foreach ($wiremo_templates as $key => $template) {
            if ($j == 0) {
                $default_template_name = $template;
            }
            $j = $j + 1;
        }

        if($wiremo_email_template) {
            $wiremo_template_name = get_option("wiremo_email_template_name");
            if(!wiremo_check_template($wiremo_email_template,$wiremo_templates)) {
                $wiremo_templates[$wiremo_email_template] = $wiremo_template_name;
            }
        }
        $options = array("name" => "Email templates",
            "desc" => "Set email template",
            "id" => "wiremo_email_template",
            "std" => "Default template",
            "type" => "select",
            "options" => $wiremo_templates);

        ?>

        <select <?php if(!(get_option('wiremo_widget_display'))) echo " enabled "; ?> <?php if(!(get_option('wiremo_automated_review_request'))) echo " disabled ";  ?> class="of-input" name="<?php if (isset($options['id'])) {
            echo $options['id'];
        } ?>" id="<?php if (isset($options['id'])) {
            echo $options['id'];
        } ?>">
            <?php
            if (is_array($options["options"])) {
                foreach ($options["options"] as $key => $option) {
                    $selected = '';
                    if ($options["std"] != '') {
                        if ($key == get_option("wiremo_email_template")) {
                            $selected = ' selected="selected"';
                        }
                    }
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <input type="hidden" name="wiremo_email_template_name" id="wiremo_email_template_name" value="<?php if(get_option("wiremo_email_template_name")) { echo get_option("wiremo_email_template_name"); } else { echo $default_template_name; } ?>">
        <?php
    }
}

//Functions for manual review request
if(!function_exists("wiremo_manual_datetime_start_display")) {
    function wiremo_manual_datetime_start_display() {
        if (current_user_can('administrator')) { ?>
            <?php
            $wiremo_date_time = wiremo_get_orders_datetime();
            ?>
            <input id="wiremo-datetime-start" <?php if(!(get_option('wiremo_widget_display'))) echo "enabled"; ?> type="text" name="wiremo_manual_datetime_start"
                   value="<?php if(isset($wiremo_date_time->old_date)) { echo $wiremo_date_time->old_date; }  ?>"/>
            <div class="wiremo-start-notification hidden alert alert-info"></div>
            <?php
        }
    }
}

if(!function_exists("wiremo_manual_datetime_end_display")) {
    function wiremo_manual_datetime_end_display() {
        if (current_user_can('administrator')) { ?>
            <?php
            $wiremo_date_time = wiremo_get_orders_datetime();
            ?>
            <input id="wiremo-datetime-end" <?php if(!(get_option('wiremo_widget_display'))) echo "enabled"; ?> type="text" name="wiremo_manual_datetime_end"
                   value="<?php if(isset($wiremo_date_time->future_date)) { echo $wiremo_date_time->future_date; } ?>"/>
            <div class="wiremo-end-notification hidden alert alert-info"></div>
            <?php
        }
    }
}

if(!function_exists("wiremo_manual_emails_day_display")) {
    function wiremo_manual_emails_day_display() {
        if (current_user_can('administrator')) {
            $options = array("name" => "Emails per day",
                "desc" => "Set emails per day",
                "id" => "wiremo_manual_emails_day",
                "std" => "5",
                "type" => "select",
                "options" => array("5"=>"5","10"=>"10","20"=>"20","50"=>"50","100"=>"100"));

            ?>
            <select <?php if(!(get_option('wiremo_widget_display'))) echo "enabled"; ?> class="of-input" name="<?php if (isset($options['id'])) {
                echo $options['id'];
            } ?>" id="<?php if (isset($options['id'])) {
                echo $options['id'];
            } ?>">
                <?php
                if (is_array($options["options"])) {
                    foreach ($options["options"] as $key => $option) {
                        $selected = '';
                        if ($options["std"] != '') {
                            if ($key == get_option("wiremo_manual_emails_day")) {
                                $selected = ' selected="selected"';
                            }
                        }
                        ?>
                        <option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <?php
        }
    }
}

if(!function_exists("wiremo_manual_email_template_display")) {
    function wiremo_manual_email_template_display() {
        if (current_user_can('administrator')) {
            $api_key = get_option("wiremo-api-key");
            $wiremo_email_template = get_option("wiremo_manual_email_template");
            $wiremo_templates = get_wiremo_email_templates($api_key);
            $j = 0;
            $default_template_name = '';
            foreach ($wiremo_templates as $key => $template) {
                if ($j == 0) {
                    $default_template_name = $template;
                }
                $j = $j + 1;
            }

            if($wiremo_email_template) {
                $wiremo_template_name = get_option("wiremo_manual_email_template_name");
                if(!wiremo_check_template($wiremo_email_template,$wiremo_templates)) {
                    $wiremo_templates[$wiremo_email_template] = $wiremo_template_name;
                }
            }
            $options = array("name" => "Email templates",
                "desc" => "Set email template",
                "id" => "wiremo_manual_email_template",
                "std" => "Default template",
                "type" => "select",
                "options" => $wiremo_templates);

            ?>

            <select <?php if(!(get_option('wiremo_widget_display'))) echo " enabled "; ?>  class="of-input" name="<?php if (isset($options['id'])) {
                echo $options['id'];
            } ?>" id="<?php if (isset($options['id'])) {
                echo $options['id'];
            } ?>">
                <?php
                if (is_array($options["options"])) {
                    foreach ($options["options"] as $key => $option) {
                        $selected = '';
                        if ($options["std"] != '') {
                            if ($key == get_option("wiremo_manual_email_template")) {
                                $selected = ' selected="selected"';
                            }
                        }
                        ?>
                        <option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <input type="hidden" name="wiremo_manual_email_template_name" id="wiremo_manual_email_template_name" value="<?php if(get_option("wiremo_manual_email_template_name")) { echo get_option("wiremo_manual_email_template_name"); } else { echo $default_template_name; } ?>">
            <?php
        }
    }
}

if(!function_exists("wiremo_date_sort")) {
    function wiremo_date_sort($a,$b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}

if(!function_exists("wiremo_manual_create_campaigns_display")) {
    function wiremo_manual_create_campaigns_display() {
        if (current_user_can('administrator')) {
            $wiremo_date_time = wiremo_get_orders_datetime();
            ?>
            <button <?php if(!(get_option('wiremo_widget_display'))) echo "enabled"; ?> data-old-time-orders="<?php if(isset($wiremo_date_time->old_date)) { echo $wiremo_date_time->old_date; } ?>" data-future-time-orders="<?php if(isset($wiremo_date_time->future_date)) { echo $wiremo_date_time->future_date; } ?>" class="wiremo-create-campaigns  btn btn-primary"
                                                                                         data-loading-text="<i class='fa fa-spinner fa-spin'></i>  Loading"><?php echo __("Create campaigns","wiremo-widget"); ?>
            </button>
            <div id="wiremo-import-bar">
                <div id="wiremo-import-bar-percent"></div>
            </div>
            <div class="wiremo-notification-campaigns hidden alert alert-success"></div>
            <?php
        }
    }
}

if(!function_exists("wiremo_manual_all_campaigns")) {
    function wiremo_manual_all_campaigns() {
        if (current_user_can('administrator')) {
            ?>
            <table class="table table-striped wiremo-campaigns">
                <thead>
                <tr>
                    <th><?php echo __("Template name","wiremo-widget"); ?></th>
                    <th><?php echo __("Start date","wiremo-widget"); ?></th>
                    <th><?php echo __("End date","wiremo-widget"); ?></th>
                    <th><?php echo __("Emails to send","wiremo-widget"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(get_option("wiremo_total_campaigns")) {
                    $wiremo_total_campaigns = json_decode(get_option("wiremo_total_campaigns"));
                    if($wiremo_total_campaigns->count > 0) {
                        $campaign_html = '';
                        for($i=1;$i<=$wiremo_total_campaigns->count;$i++) {
                            $wiremo_campaigns_information = json_decode(get_option("wiremo_campaigns_".$i));
                            $campaign_html .= '<tr><td>'.$wiremo_campaigns_information->template_name.'</td><td>'.$wiremo_campaigns_information->start_date.'</td><td>'.$wiremo_campaigns_information->end_date.'</td><td>'.$wiremo_campaigns_information->customers.'</td></tr>';
                        
}
                        echo $campaign_html;
			}
	}
                ?>
                </tbody>
            </table>
            <?php
        }
    }
}


function display_wiremo_panel_fields()
{
    if (current_user_can('administrator')) {
      $location = esc_attr(get_option("wiremo_widget_location"));
        if ($location=="Footer") {
            //$location = esc_attr(get_option("wiremo_widget_location"));
            $style_custom_title_reviews = "wiremo-custom-text-review";
            $style_custom_tab_name = "wiremo-widget-tab-name hidden";
                  }
          elseif ($location=="")
          {
            $style_custom_title_reviews = "wiremo-custom-text-review";
            $style_custom_tab_name = "wiremo-widget-tab-name hidden";
          }

        else {
            $style_custom_title_reviews = "wiremo-custom-text-review hidden";
            $style_custom_tab_name = "wiremo-widget-tab-name";
        }
        add_settings_section("section", "", null, "theme-options");
        add_settings_section("section", "", null, "wiremo-automated-request");
        add_settings_section("section", "", null, "wiremo-manual-request");


        add_settings_field("wiremo_disable_woo", __("Disable WooCommerce native reviews","wiremo-widget"), "wiremo_disable_woo", "theme-options", "section");
        add_settings_field("wiremo_widget_display", __("Enable Wiremo reviews","wiremo-widget"), "display_wiremo_widget", "theme-options", "section");
        add_settings_field("wiremo_generate_schema", __("Enable JSON-LD Schema","wiremo-widget"), "display_wiremo_schema", "theme-options", "section");

//review location
        add_settings_field("wiremo_widget_location", __("Reviews location","wiremo-widget"), "display_wiremo_location_widget", "theme-options", "section");

        add_settings_field("wiremo_custom_text_reviews", __("Custom title for reviews","wiremo-widget"), "display_wiremo_custom_text_reviews", "theme-options", "section",array('class' => $style_custom_title_reviews));
        add_settings_field("wiremo_custom_tab_name", __("Reviews Tab name","wiremo-widget"), "display_wiremo_widget_tab", "theme-options", "section", array('class' => $style_custom_tab_name));
        add_settings_field("wiremo_hide_mini_widget_home", __("Show stars on home page","wiremo-widget"), "display_wiremo_mini_widget_home", "theme-options", "section", array('class' => 'widget-mini-home'));
        add_settings_field("wiremo_hide_mini_widget_cat", __("Show stars in category page","wiremo-widget"), "display_wiremo_mini_widget_cat", "theme-options", "section", array('class' => 'widget-mini-cat'));
        add_settings_field("wiremo_hide_mini_widget_prod", __("Show stars in shop page","wiremo-widget"), "display_wiremo_mini_widget_prod", "theme-options", "section", array('class' => 'widget-mini-prod'));
        add_settings_field("wiremo_hide_mini_widget", __("Hide stars when no reviews","wiremo-widget"), "display_wiremo_mini_widget", "theme-options", "section", array('class' => 'widget-mini'));
if (!is_plugin_active( 'elementor/elementor.php' )) {
        add_settings_field("wiremo_show_custom_text_related", __("Show top rated products in footer","wiremo-widget"), "show_wiremo_custom_text_related", "theme-options", "section", array('class' => 'wiremo-text-related'));
        add_settings_field("wiremo_related_custom_text", __("Custom text for top rated products","wiremo-widget"), "display_wiremo_custom_text_related", "theme-options", "section", array('class' => 'custom-tex-related'));
        add_settings_field("wiremo_related_products_sort", __("Sort top rated products by average rating","wiremo-widget"), "display_wiremo_related_products_sort", "theme-options", "section", array('class' => 'related-products-sort'));
}
        add_settings_field("wiremo_automated_authentification", __("Automatic authentication for customers","wiremo-widget"), "display_wiremo_automated_authentification", "theme-options", "section");
        if (class_exists('SitePress')) {
            $wrpw_wpml_data = new WiremoWpmlData();
            $wrpw_wpml_data->wrpw_display_wpml_option();
        }
        add_settings_field("wiremo_import_reviews", __("Sync Wiremo with Wordpress (use once)","wiremo-widget"), "display_wiremo_import_reviews", "theme-options", "section", array('class' => 'widget-import'));
        (get_option("wiremo-display-import") != 1) ? add_settings_field("wiremo_widget_sync_products", __("Export WooCommerce reviews to Wiremo","wiremo-widget"), "display_wiremo_sync_products", "theme-options", "section", array('class' => 'wiremo-import-box')) : "";

        //Add settings fields for automated review request
        add_settings_field("wiremo_automated_review_request", __("Enable automated review request","wiremo-widget"), "wiremo_enable_automated_review_request", "wiremo-automated-request", "section");
        add_settings_field("wiremo_email_template", __("Email templates","wiremo-widget"), "display_wiremo_email_templates", "wiremo-automated-request", "section");

        //Add settings fields for manual review request
        add_settings_field("wiremo_manual_datetime_start", __("Start date","wiremo-widget"), "wiremo_manual_datetime_start_display", "wiremo-manual-request", "section");
        add_settings_field("wiremo_manual_datetime_end", __("End date","wiremo-widget"), "wiremo_manual_datetime_end_display", "wiremo-manual-request", "section");
        add_settings_field("wiremo_manual_emails_day", __("Emails per day","wiremo-widget"), "wiremo_manual_emails_day_display", "wiremo-manual-request", "section");
        add_settings_field("wiremo_manual_email_template", __("Email template","wiremo-widget"), "wiremo_manual_email_template_display", "wiremo-manual-request", "section");
        add_settings_field("wiremo_manual_create_campaigns", __("Create campaigns","wiremo-widget"), "wiremo_manual_create_campaigns_display", "wiremo-manual-request", "section");
        add_settings_field("wiremo_manual_display_campaigns", __("All campaigns","wiremo-widget"), "wiremo_manual_all_campaigns", "wiremo-manual-request", "section",array('class' => 'wiremo-manual-table-campaigns'));

        register_setting("section", "wiremo_widget_location");
        register_setting("section", "wiremo_custom_text_reviews");
        register_setting("section", "wiremo_custom_tab_name");
        register_setting("section", "wiremo_hide_mini_widget_home");
        register_setting("section", "wiremo_hide_mini_widget_cat");
        register_setting("section", "wiremo_hide_mini_widget_prod");
        register_setting("section", "wiremo_hide_mini_widget");
        register_setting("section", "wiremo_show_custom_text_related");
        register_setting("section", "wiremo_related_custom_text");
        register_setting("section", "wiremo_related_products_sort");
        register_setting("section", "wiremo_automated_authentification");
        register_setting("section", "wiremo_import_reviews");
        (get_option("wiremo-display-import") != 1) ? register_setting("section", "wiremo_widget_sync_products") : "";

        register_setting("section", "wiremo_widget_display");
        register_setting("section", "wiremo_disable_woo");
	register_setting("section", "wiremo_generate_schema");

        //Register setting for automated review request;
        register_setting("section", "wiremo_automated_review_request");
        register_setting("section", "wiremo_email_template");
        register_setting("section", "wiremo_email_template_name");

        //Register settings for manual review request
        register_setting("section", "wiremo_manual_datetime_start");
        register_setting("section", "wiremo_manual_datetime_end");
        register_setting("section", "wiremo_manual_emails_day");
        register_setting("section", "wiremo_manual_email_template");
        register_setting("section", "wiremo_manual_email_template_name");
        register_setting("section", "wiremo_manual_create_campaigns");
    }
}

add_action("admin_init", "display_wiremo_panel_fields");



/**
 * Set all configuration plugin
 */
$wiremoDisableWoo = esc_attr(get_option("wiremo_disable_woo"));
$displayWidget = esc_attr(get_option("wiremo_widget_display"));
$generateSchema = esc_attr(get_option("wiremo_generate_schema"));

/**
 * Add header scripts
 */
add_action('wp_head','wiremo_header_scripts');
if(!function_exists('wiremo_header_scripts')) {
    function wiremo_header_scripts() {
        $textFontOption = get_option("wiremo-widget-text-font");
        $wiremoTextFont = empty($textFontOption) ? "Open+Sans" : str_replace(' ', '+', $textFontOption);
        wp_enqueue_style( 'wiremo-google-fonts', "https://fonts.googleapis.com/css?family=$wiremoTextFont:400,500,600,700", false );
    }
}



if($generateSchema&&$displayWidget) {
    include dirname( __FILE__ ).'/includes/generate_schema_org.php';
    include dirname( __FILE__ ).'/includes/class-structured-data.php';
    add_action( 'init', 'wiremo_create_structured_data' );
    add_action("wp_footer","append_wiremo_schema");
}
//genschema
if (!function_exists("wiremo_generate_schema")) {
    function wiremo_generate_schema()
    {
        global $post,$product;
        $filter = new stdClass();
        $filter->siteId = esc_attr(get_option("wiremo-siteId"));
        $productId = $post->ID;
        if (class_exists('SitePress')) {
            $wrpw_wpml_data = new WiremoWpmlData();
            $productId= $wrpw_wpml_data->wrpw_get_main_product_id($post->ID);
        } else {
            $productId = $post->ID;
        }

 $productId = apply_filters("wiremo_get_final_product_id", $productId);

        $filter->identifier = "$productId";
        $filter_encoded = json_encode($filter);
        $url = WRPW_URLAPP . "/v1/reviews?filter=" . $filter_encoded;
       // $schema_org = json_decode(wiremo_generate_schema_org($url));
        $schema_product = wiremo_create_product_data($product);
        $reviews_data = $schema_org->data;
        $result_schema = array();
        $data = array();
        $total_schema = array();
        if (is_array($reviews_data)) {
            if (count($reviews_data) != 0) {
                if (get_post_meta($product->get_id(), "wiremo-review-count", true) != 0) {
                    $aggregateRating = array(
                        '@type' => 'AggregateRating',
                        'ratingValue' => get_post_meta($product->get_id(), "wiremo-review-average", true),
                        'reviewCount' => get_post_meta($product->get_id(), "wiremo-review-count", true),
                    );
                }
                for ($i = 0; $i < count($reviews_data); $i++) {
                    $result_schema[] = array(
                        "@type" => "Review",
                        "@id" => get_the_permalink()."#".$reviews_data[$i]->_id,
                        "datePublished" => $reviews_data[$i]->dateTime,
                        "description" => $reviews_data[$i]->message,
                        "itemReviewed" => array(
                            "@type" => "Product",
                            "name" => $reviews_data[$i]->title,
                            "aggregateRating" => $aggregateRating,
                            "sku" => getProductIdentifier($productId),
                        ),
                        "reviewRating" => array(
                            "@type" => "Rating",
                            "ratingValue" => $reviews_data[$i]->rating
                        ),
                        "author" => array(
                            "@type" => "Person",
                            "name" => $reviews_data[$i]->userId->name
                        )
                    );
                }
                $data = array(
                    "@context" => "https://schema.org/",
                    "@graph" => $result_schema
                );
            }
        }
        $total_schema["@graph"][] = $data;
        $total_schema["@graph"][] = $schema_product;
//        $total_schema["review"] = $result_schema;
        $wiremo_json_ld = '<script type="application/ld+json">' . json_encode($total_schema) . '</script>';
        echo $wiremo_json_ld;
        wp_add_inline_script('wiremo_json_ld', $wiremo_json_ld, 'after');
    }
}

function wiremo_create_structured_data() {
    global $structure_data;
    $structure_data = new WiremoStructuredData();
}

function append_wiremo_schema() {
    if(is_product()) {
    	echo wiremo_generate_schema();
    }
}

if($wiremoDisableWoo) {
    function disable_wiremo_woo_comments_off($open, $post_id)
    {
        $post_type = get_post_type($post_id);
        if ($post_type == 'product') {
            $open = false;
        }
        return $open;
    }

    add_filter('comments_open', 'disable_wiremo_woo_comments_off', 999, 2);

    function disable_wiremo_woo_reviews_remove_tab($tabs)
    {
        unset($tabs['reviews']);
        return $tabs;
    }

    add_filter('woocommerce_product_tabs', 'disable_wiremo_woo_reviews_remove_tab', 99);

    function disable_wiremo_woo_reviews_error()
    { ?>
        <style scoped>.comment_status_field {
                opacity: .4;
                pointer-events: none;
            }</style>
        <p style="font-style:italic;color:red;margin-left:5px"><?php __('Product reviews are currently disabled.', 'wiremo-widget'); ?></p><?php
    }

    add_action('woocommerce_product_options_reviews', 'disable_wiremo_woo_reviews_error', 10, 0);

    function disable_wiremo_woo_reviews_delete_metaboxes()
    {
        remove_meta_box('commentsdiv', 'product', 'normal');
    }

    add_action('add_meta_boxes', 'disable_wiremo_woo_reviews_delete_metaboxes', 99);

    function disable_wiremo_woo_reviews_delete_widgets_dashboard()
    {
        remove_meta_box('woocommerce_dashboard_recent_reviews', 'dashboard', 'normal');
    }

    add_action('wp_dashboard_setup', 'disable_wiremo_woo_reviews_delete_widgets_dashboard', 40);

    function disable_wiremo_woo_reviews_delete_widgets()
    {
        unregister_widget('WC_Widget_Recent_Reviews');
        unregister_widget('WC_Widget_Top_Rated_Products');
        unregister_widget('WC_Widget_Rating_Filter');
    }

    add_action('widgets_init', 'disable_wiremo_woo_reviews_delete_widgets', 99);
}

if($wiremoDisableWoo && !$displayWidget) {

    add_filter( 'body_class', 'wiremo_remove_action_single');
    if(!function_exists('wiremo_remove_action_single')) {
        function wiremo_remove_action_single($classes) {
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
            return $classes;
        }
    }

    if(!function_exists('wiremo_single_woocommerce_template_single_rating')) {
        function wiremo_single_woocommerce_template_single_rating() {
            echo "";
        }
    }
    add_action('woocommerce_single_product_summary', 'wiremo_single_woocommerce_template_single_rating', 10);
    if(!function_exists("wiremo_single_woocommerce_template_loop_rating")) {
        function wiremo_single_woocommerce_template_loop_rating() {
            echo "";
        }
    }
    add_action('woocommerce_after_shop_loop_item_title', 'wiremo_single_woocommerce_template_loop_rating', 5);
}

function get_widget_lite($id) {
    $widget_lite = "";
    if (get_option("wiremo_hide_mini_widget")) {
        if ($countReview != 0) {
            $widget_lite = wiremo_generate_widget_lite("page", $id);
        }
    }
    if (get_option("wiremo_hide_mini_widget_cat")) {
        $widget_lite = (is_product_category() && (!is_shop())) ? wiremo_generate_widget_lite("product-page", $id) : "";
    }
    if (get_option("wiremo_hide_mini_widget_prod")) {
        $widget_lite = (is_shop()) ? wiremo_generate_widget_lite("product-page", $id) : "";
    }
    $widget_lite = (is_product()) ? wiremo_generate_widget_lite("product-page", $id) : "";
    if(get_option("wiremo_hide_mini_widget_home")) {
        $widget_lite = ( (is_archive() && !is_shop() && !is_product_category()) || (is_page() && !is_shop())) ? wiremo_generate_widget_lite("product-page", $id) : "";
    }

    return $widget_lite;
}

if ($displayWidget && $wiremoDisableWoo) {
    include dirname( __FILE__ ).'/includes/widget-settings.php';
    include dirname( __FILE__ ).'/templates/widget_lite.php';
    include dirname( __FILE__ ).'/templates/generate_widget_lite.php';

    if (!defined('ABSPATH')) exit;

    add_filter( 'woocommerce_blocks_product_grid_item_html', 'wiremo_woocommerce_template_block', 10, 3 );
    if(!function_exists('wiremo_woocommerce_template_block')){
        function wiremo_woocommerce_template_block( $html, $data, $product ){
            $after_link_pos = strpos($html, "</a>");
            $before = substr($html, 0, $after_link_pos + 4);
            $after = substr($html, $after_link_pos + 4);
            $widget_lite = get_widget_lite($product->id);
            $replaced_html = $before.$widget_lite.$after;
            $replaced_html = preg_replace('/(<div\s*class="wc-block-grid__product-rating"\s*>)\s*.*(<\/div><\/div>)/', "", $replaced_html);

            return $replaced_html;
        }
    }

    add_filter( 'body_class', 'wiremo_remove_action_from_woocommerce');
    if (!function_exists('wiremo_remove_action_from_woocommerce')) {
        function wiremo_remove_action_from_woocommerce($classes)
        {
            (get_option("wiremo_show_custom_text_related")) ? remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20) : "";
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
            return $classes;
        }
    }

    if(!function_exists("wiremo_woocommerce_template_single_rating")) {
        function wiremo_woocommerce_template_single_rating()
        {
            $productId = get_the_ID();
            if (class_exists('SitePress')) {
                $wrpw_wpml_data = new WiremoWpmlData();
                $productId= $wrpw_wpml_data->wrpw_get_main_product_id(get_the_ID());
            } else {
                $productId = get_the_ID();
            }
            $objRating = get_post_meta($productId, "wiremo-review-total", true);
            $ratingTotal = json_decode($objRating);
            if($objRating) {
                $countReview = $ratingTotal->count;
            } else {
                $countReview = 0;
            }
            if (get_option("wiremo_hide_mini_widget")) {
                if ($countReview != 0) {
                    echo wiremo_get_widget_lite_template("page");
                }
            } else {
                echo wiremo_get_widget_lite_template("page");
            }
        }
    }
    //add shortcode [wiremo-rating-stars-widget-lite]
    function wiremo_custom_lite_shortcode() {
        echo wiremo_woocommerce_template_single_rating();
       }
       add_shortcode( 'wiremo-rating-stars', 'wiremo_custom_lite_shortcode' );


// render widget lite for single product page top - Woocommerce no elementor
    if(!function_exists("wiremo_woocommerce_template_single_rating_before_price")) {

        function wiremo_woocommerce_template_single_rating_before_price()
        {
            $stars = wiremo_woocommerce_template_single_rating();
            return $stars;
        }
    }
add_filter( 'woocommerce_single_product_summary', 'wiremo_woocommerce_template_single_rating_before_price', 10, 2);
//end of render widget lite for product page top - WooCommerce no elementor



//render rating-widget elementor
add_action( 'elementor/widget/render_content', function( $content, $widget ) {

   if ( 'woocommerce-product-price' === $widget->get_name() ) {
        echo wiremo_woocommerce_template_single_rating();
   }
  return $content;
}, 10, 2 );
//end of render rating-widget elementor


//Render review-widget for elementor + wordpress widget
add_filter( 'elementor/widget/render_content', function( $content, $widget) {

if  ('wp-widget-wiremo_review_widget' === $widget->get_name()) {
  echo wiremo_before_woocommerce_output_related_products_elementor();
}
return $content;
}, 10, 2 );


//function to render review widget in elementor
function wiremo_before_woocommerce_output_related_products_elementor()
{
    $location = esc_attr(get_option("wiremo_widget_location"));

    global $post;
    $countReview = 0;
    $siteId = esc_attr(get_option("wiremo-siteId"));
    $path = strtok($_SERVER["REQUEST_URI"],'?');
    $productId = rawurlencode($post->ID);
    if (class_exists('SitePress')) {
        $wrpw_wpml_data = new WiremoWpmlData();
        $productId= $wrpw_wpml_data->wrpw_get_main_product_id($post->ID);
    } else {
        $productId = $post->ID;
    }

    $productUrl = rawurlencode(get_the_permalink());
    $title = rawurlencode(get_the_title());
    $productFullPath = get_the_permalink();
    $productPath = str_replace(home_url(),"",$productFullPath);
    $productPath = rawurlencode($productPath);
    if(get_post_meta($productId, "wiremo-review-total", true)) {
        $wiremoReviewTotal = get_post_meta($productId, "wiremo-review-total", true);
        $wiremoTotalRating = json_decode($wiremoReviewTotal);
        $countReview = $wiremoTotalRating->count;
    }
    $custom_text_reviews = esc_attr(get_option("wiremo_custom_text_reviews"));
    $custom_text_reviews = str_replace("{count}",$countReview,$custom_text_reviews);
    $widget_location = get_option("wiremo_widget_location");
    if(get_option("wiremo_automated_authentification")) {
        if(is_user_logged_in()) {
            $current_user_id = get_current_user_id();
            $token = wiremo_get_user_token($current_user_id);
            if($token != "") {
                $token = ',token:"'.$token.'"';
            }
        }
        else {
            $token = "";
        }
    }
    else {
        $token = "";
    }

    if($widget_location == "Footer" OR $widget_location =="") {
        if(!empty($custom_text_reviews)) {
            ?>
            <h2 class="wiremo-custom-text-reviews"><?php echo $custom_text_reviews; ?></h2>
            <?php
        }
    }

    $product = new \stdClass();
    $product->title = get_the_title();
    $product->url = get_the_permalink();
    $product->image = get_the_post_thumbnail_url();
    $product->sku = getProductIdentifier($productId);

    $str_permalink = get_permalinkStructure();

    echo "<div id='customer-reviews-box'><div id='wiremo-widget' data-platform='woocommerce'></div></div>";
    wp_register_script('wiremo-customer-reviews-box-script', '', [], '', true);
    wp_enqueue_script('wiremo-customer-reviews-box-script');
    wp_add_inline_script('wiremo-customer-reviews-box-script', '!function(){var e=window.wiremo_config?new window.wiremo_config:{},t=Object.assign({platform:"woocommerce",permalinkStructure:"'.$str_permalink.'",reviewType:"product",product:'.json_encode($product).',reviewSource:"'.(string)$productId.'",identifier:"'.(string)$productId.'"'.$token.'},e),n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src="'.WRPW_URLWIDGET.'?k='.$siteId.'&w="+encodeURIComponent(JSON.stringify(t));var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(n,o)}();');

    }
//widget-elementor
//END OF function to render review widget in elementor


    if (get_option("wiremo_widget_location")) {
        $location = esc_attr(get_option("wiremo_widget_location"));
        if ($location == "Tab") {
            add_filter( 'woocommerce_product_tabs', 'wiremo_new_product_tab' );
            if(!function_exists("wiremo_new_product_tab")) {
                function wiremo_new_product_tab( $tabs ) {
                    global $post;
                    $countReview = 0;
                    $productId = $post->ID;
                    if (class_exists('SitePress')) {
                        $wrpw_wpml_data = new WiremoWpmlData();
                        $productId= $wrpw_wpml_data->wrpw_get_main_product_id($productId);
                    } else {
                        $productId = $post->ID;
                    }
                    if(get_post_meta($productId, "wiremo-review-total", true)) {
                        $wiremoReviewTotal = get_post_meta($productId, "wiremo-review-total", true);
                        $wiremoTotalRating = json_decode($wiremoReviewTotal);
                        $countReview = $wiremoTotalRating->count;
                    }
                    $wiremo_tab_name = esc_attr(get_option("wiremo_custom_tab_name"));
                    $wiremo_tab_name = str_replace("{count}",$countReview,$wiremo_tab_name);
                    if(!empty(get_option("wiremo_custom_tab_name"))) {
                        $title_tab = $wiremo_tab_name;
                    }
                    else {
                        $title_tab = __("Reviews","wiremo-widget")." (".$countReview.")";
                    }
                    $tabs['wiremo-review'] = array(
                        'title' 	=> $title_tab,
                        'priority' 	=> 50,
                        'callback' 	=> 'wiremo_product_tab_content'
                    );
                    return $tabs;
                }
            }
            if(!function_exists("wiremo_product_tab_content")) {
                function wiremo_product_tab_content() {
                    global $post;
                    $path = strtok($_SERVER["REQUEST_URI"],'?');
                    $siteId = esc_attr(get_option("wiremo-siteId"));
                    $productId = rawurlencode($post->ID);
                    if (class_exists('SitePress')) {
                        $wrpw_wpml_data = new WiremoWpmlData();
                        $productId= $wrpw_wpml_data->wrpw_get_main_product_id($post->ID);
                    } else {
                        $productId = $post->ID;
                    }
                    if(get_option("wiremo_automated_authentification")) {
                        if(is_user_logged_in()) {
                            $current_user_id = get_current_user_id();
                            $token = wiremo_get_user_token($current_user_id);
                            if($token != "") {
                                $token = ',token:"'.$token.'"';
                            }
                        }
                        else {
                            $token = "";
                        }
                    }
                    else {
                        $token = "";
                    }

                    $product = new \stdClass();
                    $product->title = get_the_title();
                    $product->url = get_the_permalink();
                    $product->image = get_the_post_thumbnail_url();
                    $product->sku = getProductIdentifier($productId);

                    $str_permalink = get_permalinkStructure();

                    echo '<div id="wiremo-widget" data-platform="woocommerce"></div>';
                    wp_register_script('wiremo-widget-script', '', [], '', true);
		    wp_enqueue_script('wiremo-widget-script');
                    wp_add_inline_script('wiremo-widget-script', '!function(){var e=window.wiremo_config?new window.wiremo_config:{},t=Object.assign({platform:"woocommerce",permalinkStructure:"'.$str_permalink.'",reviewType:"product",product:'.json_encode($product).',reviewSource:"'.(string)$productId.'",identifier:"'.(string)$productId.'"'.$token.'},e),n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src="'.WRPW_URLWIDGET.'?k='.$siteId.'&w="+encodeURIComponent(JSON.stringify(t));var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(n,o)}();');
//widget-tab
                }
            }
        } else if ($location == "Footer") {
            // Template footer
            function wiremo_before_woocommerce_output_related_products()
            {
                global $post;
                $countReview = 0;
                $siteId = esc_attr(get_option("wiremo-siteId"));
                $path = strtok($_SERVER["REQUEST_URI"],'?');
                $productId = rawurlencode($post->ID);
                if (class_exists('SitePress')) {
                    $wrpw_wpml_data = new WiremoWpmlData();
                    $productId= $wrpw_wpml_data->wrpw_get_main_product_id($post->ID);
                } else {
                    $productId = $post->ID;
                }
                $productUrl = rawurlencode(get_the_permalink());
                $title = rawurlencode(get_the_title());
                $productFullPath = get_the_permalink();
                $productPath = str_replace(home_url(),"",$productFullPath);
                $productPath = rawurlencode($productPath);
                if(get_post_meta($productId, "wiremo-review-total", true)) {
                    $wiremoReviewTotal = get_post_meta($productId, "wiremo-review-total", true);
                    $wiremoTotalRating = json_decode($wiremoReviewTotal);
                    $countReview = $wiremoTotalRating->count;
                }
                $custom_text_reviews = esc_attr(get_option("wiremo_custom_text_reviews"));
                $custom_text_reviews = str_replace("{count}",$countReview,$custom_text_reviews);
                $widget_location = get_option("wiremo_widget_location");
                if(get_option("wiremo_automated_authentification")) {
                    if(is_user_logged_in()) {
                        $current_user_id = get_current_user_id();
                        $token = wiremo_get_user_token($current_user_id);
                        if($token != "") {
                            $token = ',token:"'.$token.'"';
                        }
                    }
                    else {
                        $token = "";
                    }
                }
                else {
                    $token = "";
                }
                if($widget_location == "Footer") {
                    if(!empty($custom_text_reviews)) {
                        ?>
                        <h2 class="wiremo-custom-text-reviews"><?php echo $custom_text_reviews; ?></h2>
                        <?php
                    }
                }

                $product = new \stdClass();
                $product->title = get_the_title();
                $product->url = get_the_permalink();
                $product->image = get_the_post_thumbnail_url();
                $product->sku = getProductIdentifier($productId);

                $str_permalink = get_permalinkStructure();

                echo "<div id='customer-reviews-box'><div id='wiremo-widget' data-platform='woocommerce'></div></div>";
                wp_register_script('wiremo-customer-reviews-box-script', '', [], '', true);
                wp_enqueue_script('wiremo-customer-reviews-box-script');
                wp_add_inline_script('wiremo-customer-reviews-box-script', '!function(){var e=window.wiremo_config?new window.wiremo_config:{},t=Object.assign({platform:"woocommerce",permalinkStructure:"'.$str_permalink.'",reviewType:"product",product:'.json_encode($product).',reviewSource:"'.(string)$productId.'",identifier:"'.(string)$productId.'"'.$token.'},e),n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src="'.WRPW_URLWIDGET.'?k='.$siteId.'&w="+encodeURIComponent(JSON.stringify(t));var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(n,o)}();');
//widget-footer
                }

            add_action('woocommerce_after_single_product_summary', 'wiremo_before_woocommerce_output_related_products', 19);
        }
    }

    function get_permalinkStructure(){
        $structure_permalink = get_option("permalink_structure");
        if ($structure_permalink == ''){
            $str_permalink = 'plainPermalink';
        }else{
            $str_permalink = 'notRelevant';
        }

        return $str_permalink;
    }

    if(!function_exists('wrpw_check_template')) {
        function wrpw_check_template() {
            if (get_option("wiremo_hide_mini_widget_cat")) {
                (is_product_category() && (!is_shop())) ? wiremo_get_widget_lite_template("product-page") : "";
            }
            if (get_option("wiremo_hide_mini_widget_prod")) {
                (is_shop()) ? wiremo_get_widget_lite_template("product-page") : "";
            }
            (is_product()) ? wiremo_get_widget_lite_template("product-page") : "";
            if(get_option("wiremo_hide_mini_widget_home")) {
                ( (is_archive() && !is_shop() && !is_product_category()) || (is_page() && !is_shop())) ? wiremo_get_widget_lite_template("product-page") : "";
            }
        }
    }

    //Template for loop rating
    if(!function_exists("woocommerce_template_loop_rating")) {
        function woocommerce_template_loop_rating()
        {
            $productId = get_the_ID();
            if (class_exists('SitePress')) {
                $wrpw_wpml_data = new WiremoWpmlData();
                $productId= $wrpw_wpml_data->wrpw_get_main_product_id(get_the_ID());
            } else {
                $productId = get_the_ID();
            }
            $objRating = get_post_meta($productId, "wiremo-review-total", true);
            $ratingTotal = json_decode($objRating);
            if($objRating) {
                $countReview = $ratingTotal->count;
            } else {
                $countReview = 0;
            }
            if (get_option("wiremo_hide_mini_widget")) {

                if ($countReview != 0) {
                    echo wrpw_check_template();
                }
            } else {
                echo wrpw_check_template();
            }
        }
    }
   	$curr_theme=wp_get_theme();

	if (strpos($curr_theme, 'Woodmart') !== false) {
    add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 1);
} else {
    add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
	}

    if(!function_exists("wiremo_woocommerce_output_related_products")) {
        function wiremo_woocommerce_output_related_products()
        {
            if (!defined('ABSPATH')) {
                exit;
            }
            global $post, $product;
            $cats_array = array();
            $cats = wp_get_post_terms($post->ID, "product_cat");
            foreach ($cats as $cat) {
                $cats_array[] .= $cat->term_id;
            }
            $arrRelatedProducts = array();
            (get_option("wiremo_related_products_sort")) ? $sortArgs = array(
                'meta_value_num' => 'DESC',
                'review-average' => 'DESC',
                'review-count' => 'DESC',
                'title' => 'ASC'
            ) : $sortArgs = array(
                'title' => 'ASC',
            );
            $productsRelated = new WP_Query(
                array(
                    'orderby' => $sortArgs,
                    'posts_per_page' => 4,
                    'post_type' => 'product',
                    'post__not_in' => array($post->ID),
                    'meta_query' => array(
                        'review-average' => array(
                            'key' => 'wiremo-review-average',
                            'compare' => 'EXISTS'
                        ),
                        'review-count' => array(
                            'key' => 'wiremo-review-count',
                            'compare' => 'EXISTS',
                        ),
                    ),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $cats_array
                        ),

                    )
                )
            );
            if ($productsRelated->have_posts()) {
                while ($productsRelated->have_posts()) : $productsRelated->the_post();
                    $product = new WC_Product(get_the_ID());
                    $arrRelatedProducts[] = $product;
                endwhile;
            }
            wp_reset_postdata();

            if ($arrRelatedProducts) : ?>
                <?php
                if(get_option("wiremo_show_custom_text_related")) { ?>
                    <section class="related products">
                        <?php if (!empty(get_option("wiremo_related_custom_text"))) { ?>
                            <h2 class="wiremo-related-custom-text"><?php echo esc_attr(get_option("wiremo_related_custom_text")); ?></h2><?php } else { ?>
                            <h2 class="wiremo-related-custom-text"><?php esc_html_e('Related products', 'wiremo-widget'); ?></h2>
                        <?php } ?>
                        <?php woocommerce_product_loop_start(); ?>
                        <?php foreach ($arrRelatedProducts as $related_product) : ?>
                            <?php
                            $post_object = get_post($related_product->get_id());
                            setup_postdata($GLOBALS['post'] =& $post_object);
                            wc_get_template_part('content', 'product'); ?>
                        <?php endforeach; ?>
                        <?php woocommerce_product_loop_end(); ?>
                    </section>
                <?php } ?>
            <?php endif;
            wp_reset_postdata();
        }
    }
    (get_option("wiremo_show_custom_text_related")) ? add_action('woocommerce_after_single_product_summary', 'wiremo_woocommerce_output_related_products', 20) : "";

    function wiremo_get_catalog_ordering_args($args)
    {

        $orderby_value = isset($_GET['orderby']) ? woocommerce_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));
        if ('rating' == sanitize_text_field($orderby_value)) {
            unset($args["meta_key"]);
            unset($args["orderby"]);
            unset($args["order"]);
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 1000,
                'meta_query' => array(
                    'review-average' => array(
                        'key' => 'wiremo-review-average',
                        'compare' => 'EXISTS'
                    ),
                    'review-count' => array(
                        'key' => 'wiremo-review-count',
                        'compare' => 'EXISTS',
                    ),
                ),
                'orderby' => array(
                    'meta_value_num' => 'DESC',
                    'review-average' => 'DESC',
                    'review-count' => 'DESC',
                    'title' => 'ASC'
                ),
            );
        }
        return $args;
    }

    add_filter('woocommerce_get_catalog_ordering_args', 'wiremo_get_catalog_ordering_args');

    function wiremo_sort_average_product_query($q)
    {
        $orderby_value = isset($_GET['orderby']) ? woocommerce_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));
        if ('rating' == sanitize_text_field($orderby_value)) {
            $q->set('meta_query', array(
                'review-average' => array(
                    'key' => 'wiremo-review-average',
                    'compare' => 'EXISTS'
                ),
                'review-count' => array(
                    'key' => 'wiremo-review-count',
                    'compare' => 'EXISTS',
                )));
        }
    }

    add_action('woocommerce_product_query', 'wiremo_sort_average_product_query');

    if(!function_exists("wiremo_woo_order_status_completed")) {
        function wiremo_woo_order_status_completed($order_id) {
            $order = wc_get_order($order_id);
            $api_key = get_option("wiremo-api-key");
            $campaign_id = get_option("wiremo_email_template");
            $shop_page_id = get_option('woocommerce_shop_page_id');
            $shop_page_name = get_the_title($shop_page_id);
            $order_meta = get_post_meta($order_id);
            $items = $order->get_items();
            $first_name = $order_meta["_billing_first_name"][0];
            $last_name = $order_meta["_billing_last_name"][0];
            $email = $order_meta["_billing_email"][0];
            if (wiremo_validate_gravatar($email)) {
                $avatar_url = get_avatar_url($email);
            } else {
                $avatar_url = '';
            }
            $item = array();
            foreach ($items as $item_id => $item_data) {
                $product_name = $item_data['name'];
                $product_id = $item_data['product_id'];
                $product_description = get_post($product_id)->post_excerpt;
                $productId = $item_data['product_id'];
                if (class_exists('SitePress')) {
                    $wrpw_wpml_data = new WiremoWpmlData();
                    $productId= $wrpw_wpml_data->wrpw_get_main_product_id($item_data['product_id']);
                } else {
                    $productId = $item_data['product_id'];
                }
                $product_url = get_the_permalink($productId);
                $product_path = str_replace(home_url(),"",$product_url);
                $product_path = rawurlencode($product_path);
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
                $image_product = $image[0];
                if(isset($image_product) && !empty($image_product)) {
                    $image_product = $image[0];
                }
                else {
                    $image_product = "";
                }
                $date = get_post_meta($order_id,"_completed_date",true);
                $date = new DateTime($date);
                $order_date = $date->format('Y-m-d\TH:i:s.u');
                $order_date_split = explode(".", $order_date);
                $order_date = $order_date_split[0] . ".000Z";
                $item[] = array(
                    "name" => $product_name,
                    "description" => $product_description,
                    "url" => $product_url,
                    "path" => $product_path,
                    "identifier" => (string)$productId,
                    "orderDate" => $order_date,
                    "orderId" => (string)$order_id,
                    "imageUrl" => $image_product
                );
            }
            for($i=0;$i<count($item);$i++) {
                $keys = array_keys($item[$i],"");
                foreach($keys as $key) {
                    unset($item[$i][$key]);
                }
            }
            $post_fields = array(
                "apiKey" => $api_key,
                "campaignId" => $campaign_id,
                "shopName" => $shop_page_name,
                "email" => $email,
                "name" => $first_name,
                "surname" => $last_name,
                "avatarUrl" => $avatar_url,
                "items" => $item
            );
            $key_post_fields = array_keys($post_fields,"");
            foreach($key_post_fields as $key) {
                unset($post_fields[$key]);
            }
            $url_api = WRPW_URLAPP."/v1/ecommerce/reviewRequest";
            $response = wp_remote_post( $url_api, array(
                    'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
                    'method' => 'POST',
                    'body' => json_encode($post_fields)
                )
            );
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                echo wiremo_wh_log("----------Request url route: /v1/ecommerce/reviewRequest return error ".esc_attr($error_message)." error ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
            } else {
                $data = json_decode($response["body"]);
                if(isset($data->success) && !empty($data->success) && $data->success > 0) {

                }
                else {
                    echo wiremo_wh_log("----------Request url route: /v1/ecommerce/reviewRequest return error ".$data->error." error ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                }
            }
        }
    }
    $wiremo_auth_rev_req = get_option("wiremo_automated_review_request");
    if($wiremo_auth_rev_req) {
        add_action( 'woocommerce_order_status_completed', 'wiremo_woo_order_status_completed', 10, 1);
    }
}

function admin_footer_text( $footer_text ) {
              $current_screen = get_current_screen();
              $is_wiremo_screen = ( $current_screen && false !== strpos( $current_screen->id, 'wiremo-widget' ) );

              if ( $is_wiremo_screen ) {


   $footer_text = '



                          <div class="rate">
                           <span class="rate" style="margin-top:12px;">Enjoyed Wiremo? Please, select a rating and leave a review</span>
                           <input type="radio" id="star5" name="rate" value="5" />
                           <label for="star5" title="Rate us with 5 stars" onclick="window.open(\'https://wiremo.co/getreview.php?rating=5\')">5 stars</label>
                           <input type="radio" id="star4" name="rate" value="4" />
                           <label for="star4" title="Rate us with 4 stars" onclick="window.open(\'https://wiremo.co/getreview.php?rating=4\')">4 stars</label>
                           <input type="radio" id="star3" name="rate" value="3" />
                           <label for="star3" title="Rate us with 3 stars" onclick="window.open(\'https://wiremo.co/getreview.php?rating=3\')">3 stars</label>
                           <input type="radio" id="star2" name="rate" value="2" />
                           <label for="star2" title="Rate us with 2 stars" onclick="window.open(\'https://wiremo.co/getreview.php?rating=2\')">2 stars</label>
                           <input type="radio" id="star1" name="rate" value="1"/>
                           <label for="star1" title="Rate us with 1 star" onclick="window.open(\'https://wiremo.co/getreview.php?rating=1\')">1 star</label>
                         </div>';
              }

              return $footer_text;
      }

add_filter('admin_footer_text', 'admin_footer_text');


if (class_exists('SitePress')) {
    if(get_option("wrpw_wpml_option")) {
        add_action( 'before_delete_post', 'wrpw_delete_product');
        if(!function_exists("wrpw_delete_product")) {
            function wrpw_delete_product($post_id){
                $wrpw_wpml_data = new WiremoWpmlData();
                $wrpw_wpml_data->wrpw_migrate_statistic($post_id);
            }
        }
    }
}
