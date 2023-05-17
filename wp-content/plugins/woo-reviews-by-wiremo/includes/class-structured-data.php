<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WiremoStructuredData {
    public $_data = array();

    public function __construct() {
        remove_action( 'woocommerce_review_meta', array( WC()->structured_data, 'generate_review_data' ), 20 );
        remove_action( 'woocommerce_single_product_summary', array( WC()->structured_data, 'generate_product_data' ), 60 );
        remove_action( 'woocommerce_shop_loop', array(WC()->structured_data, 'generate_product_data' ), 10 );
        add_action( 'woocommerce_shop_loop', array( $this, 'wiremo_generate_product_data' ), 10 );
        add_action( 'wp_footer', array( $this, 'wiremo_display_product_data' ), 10 );
    }

    public function wiremo_set_data($data) {
        $this->_data[] = $data;
        return true;
    }

    public function wiremo_get_data() {
        return $this->_data;
    }

    public function wiremo_generate_product_data($product = null) {
        if ( ! is_object( $product ) ) {
            global $product;
        }

        if ( ! is_a( $product, 'WC_Product' ) ) {
            return;
        }

        $site_name      = get_bloginfo( 'name' );
        $site_url        = home_url();
        $currency        = get_woocommerce_currency();
        $schema          = array();
        $schema["@context"] = "https://schema.org/";
        $schema['@type'] = 'Product';
        $schema['@id']   = get_permalink( $product->get_id() );
        $schema['url']   = $schema['@id'];
        $schema['name']  = $product->get_name();

        $productId = $product->get_id();
        $schema['sku'] = getProductIdentifier($productId);
        if (class_exists('SitePress')) {
            $wrpw_wpml_data = new WiremoWpmlData();
            $productId= $wrpw_wpml_data->wrpw_get_main_product_id($product->get_id());
        } else {
            $productId = $product->get_id();
        }

        if ( '' !== $product->get_price() ) {
            $schema_offer = array(
                '@type'         => 'Offer',
                'priceCurrency' => $currency,
                'availability'  => 'https://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
                'sku'           => $product->get_sku(),
                'image'         => wp_get_attachment_url( $product->get_image_id() ),
                'description'   => $product->get_description(),
                'seller'        => array(
                    '@type' => 'Organization',
                    'name'  => $site_name,
                    'url'   => $site_url,
                ),
            );

            if ( $product->is_type( 'variable' ) ) {
                $prices = $product->get_variation_prices();

                if ( current( $prices['price'] ) === end( $prices['price'] ) ) {
                    $markup_offer['price'] = wc_format_decimal( $product->get_price(), wc_get_price_decimals() );
                } else {
                    $schema_offer['priceSpecification'] = array(
                        'price'         => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
                        'minPrice'      => wc_format_decimal( current( $prices['price'] ), wc_get_price_decimals() ),
                        'maxPrice'      => wc_format_decimal( end( $prices['price'] ), wc_get_price_decimals() ),
                        'priceCurrency' => $currency,
                    );
                }
            } else {
                $schema_offer['price'] = wc_format_decimal( $product->get_price(), wc_get_price_decimals() );
            }
            $schema["offers"] = $schema_offer;
        }

        if ( get_post_meta($productId,"wiremo-review-count",true) !=0) {
            $schema['aggregateRating'] = array(
                '@type'       => 'AggregateRating',
                'ratingValue' => get_post_meta($productId,"wiremo-review-average",true),
                'reviewCount' => get_post_meta($productId,"wiremo-review-count",true),
            );
        }
        $this->wiremo_set_data($schema);
    }
    public function wiremo_display_product_data() {
        if(count($this->_data) !=0) {
            $wiremo_json_ld = '<script type="application/ld+json">'.json_encode($this->wiremo_get_data()).'</script>';
            wp_register_script('wiremo_json_ld', '', [], '', true);
            wp_enqueue_script('wiremo_json_ld');
            wp_scripts()->add_inline_script('wiremo_json_ld', $wiremo_json_ld, 'after');
        }
    }
}