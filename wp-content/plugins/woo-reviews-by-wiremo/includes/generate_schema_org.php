<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wiremo_generate_schema_org($url) {
    $response = wp_remote_get( $url);
    if(is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return $error_message;
    }
    else {
        $data = $response["body"];
        return $data;
    }
}

function wiremo_create_product_data($product) {
    if ( ! is_object( $product ) ) {
        global $product;
    }

    if ( ! is_a( $product, 'WC_Product' ) ) {
        return;
    }


    $brand = get_the_terms($product->get_id(),'pa_brand'); 
    $site_name      = get_bloginfo( 'name' );
    $site_url        = home_url();
    $currency        = get_woocommerce_currency();
    $schema          = array();
    $schema["@context"] = "https://schema.org/";
    $schema['@type'] = 'Product';
    /*if ($brand)  $schema['brand'] = array(
                        "@type"         =>  "Brand",
                        "name"          =>  $brand[0]->name
                ); */

    $schema['@id']   = get_permalink( $product->get_id() );
    $schema['url']   = $schema['@id'];
    $schema['name']  = $product->get_name();
    $schema['image'] = wp_get_attachment_url( $product->get_image_id() );
    $schema['description']   = $product->get_description();
    
    $productId = $product->get_id();
    $schema['sku'] = getProductIdentifier($productId);
    $gtin = get_post_meta( $productId, 'hwp_product_gtin', 1 );
    if ($gtin)  $schema['gtin']   = $gtin;

    if (class_exists('SitePress')) {
        $wrpw_wpml_data = new WiremoWpmlData();
        $productId= $wrpw_wpml_data->wrpw_get_main_product_id($product->get_id());
    } else {
        $productId = $product->get_id();
    }

    if ( '' !== $product->get_price() ) {
        $schema_offer = array(
            '@type'             => 'Offer',
            'priceCurrency'     => $currency,
            'availability'      => 'https://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
            'sku'               => $product->get_sku(),
            'image'             => wp_get_attachment_url( $product->get_image_id() ),
            'description'       => $product->get_description(),
            'url'               => $schema['url'],
            'priceValidUntil' => $product->is_on_sale() && ! empty( $product->get_date_on_sale_to() ) ? date_i18n( 'Y-m-d', strtotime( $product->get_date_on_sale_to() ) ) : date( 'Y-12-31', time() + YEAR_IN_SECONDS ),
            'seller'            => array(
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

    return $schema;
}
