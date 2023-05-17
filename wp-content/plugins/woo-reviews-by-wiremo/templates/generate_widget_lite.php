<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wiremo_generate_widget_lite($location, $productId) {
    global $wiremoTranslations;
    global $post;
    $siteId = get_option("wiremo-siteId");
    $idProduct = $productId;
    $countReview = 0;
    $rating1Count = 0;
    $rating2Count = 0;
    $rating3Count = 0;
    $rating4Count = 0;
    $rating5Count = 0;
    $ratingSum = 0;
    if(get_post_meta($idProduct, "wiremo-review-total", true)) {
        $wiremoReviewTotal = get_post_meta($idProduct, "wiremo-review-total", true);
        $wiremoTotalRating = json_decode($wiremoReviewTotal);
        $countReview = $wiremoTotalRating->count;
        $ratingSum = $wiremoTotalRating->ratingSum;
        $rating1Count = $wiremoTotalRating->detailedData[0]->count;
        $rating2Count = $wiremoTotalRating->detailedData[1]->count;
        $rating3Count = $wiremoTotalRating->detailedData[2]->count;
        $rating4Count = $wiremoTotalRating->detailedData[3]->count;
        $rating5Count = $wiremoTotalRating->detailedData[4]->count;
    }
    $rating1Percent = ($rating1Count != 0) ? (($rating1Count / $countReview) * 100) : 0;
    $rating2Percent = ($rating2Count != 0) ? (($rating2Count / $countReview) * 100) : 0;
    $rating3Percent = ($rating3Count != 0) ? (($rating3Count / $countReview) * 100) : 0;
    $rating4Percent = ($rating4Count != 0) ? (($rating4Count / $countReview) * 100) : 0;
    $rating5Percent = ($rating5Count != 0) ? (($rating5Count / $countReview) * 100) : 0;

    $ratingStar = ($ratingSum != 0) ? (($ratingSum / ($countReview * 5)) * 100) : 0;

    // get global template options
    $widgetStarColor = get_option("wiremo-widget-star-color");
    $widgetLanguage = get_option("wiremo-widget-language");
    $widgetHover = get_option("wiremo-widget-hover");
    $widgetStarStyle = get_option("wiremo-widget-star-style");
    $widgetStarSize = get_option("wiremo-widget-star-size");
    $widgetTextFontOption = get_option("wiremo-widget-text-font");
    $widgetTextFont = $widgetTextFontOption ? $widgetTextFontOption : "Open Sans";

    if(empty($widgetStarColor) && empty($widgetLanguage) && empty($widgetHover) && empty($widgetStarStyle) && empty($widgetStarSize)) {
        $url = WRPW_URLAPP."/v1/sites/".$siteId;
        $widgetSettingsResponse = wiremo_get_widget_settings($url);
        if(isset($widgetSettingsResponse->options)) {
            $widgetStarColor = $widgetSettingsResponse->options->starColor;
            $widgetLanguage = $widgetSettingsResponse->options->language;
            $widgetHover = $widgetSettingsResponse->options->ratingDetails == true;
            $widgetStarStyle = $widgetSettingsResponse->options->starStyle;
            $widgetStarSize = $widgetSettingsResponse->options->starSize;
            $widgetTextFont = $widgetSettingsResponse->options->textFont;
            (get_option("wiremo-widget-star-color")) ? update_option("wiremo-widget-star-color",$widgetStarColor) : add_option("wiremo-widget-star-color",$widgetStarColor);
            (get_option("wiremo-widget-language")) ? update_option("wiremo-widget-language",$widgetLanguage) : add_option("wiremo-widget-language",$widgetLanguage);
            (get_option("wiremo-widget-hover")) ? update_option("wiremo-widget-hover",$widgetHover) : add_option("wiremo-widget-hover",$widgetHover);
            (get_option("wiremo-widget-star-style")) ? update_option("wiremo-widget-star-style",$widgetStarStyle) : add_option("wiremo-widget-star-style",$widgetStarStyle);
            (get_option("wiremo-widget-star-size")) ? update_option("wiremo-widget-star-size",$widgetStarSize) : add_option("wiremo-widget-star-size",$widgetStarSize);
            (get_option("wiremo-widget-text-font")) ? update_option("wiremo-widget-text-font",$widgetTextFont) : add_option("wiremo-widget-text-font",$widgetTextFont);
        }
    }
    if(!empty(get_option("wiremo-widget-language"))) {
        $lang = get_option("wiremo-widget-language");
    }
    if(!empty(get_option("wiremo-widget-star-style"))) {
        $widgetStarStyle = get_option("wiremo-widget-star-style");
    }
    if(!empty(get_option("wiremo-widget-star-size"))) {
        $widgetStarSize = get_option("wiremo-widget-star-size");
    }
    if(!empty(get_option("wiremo-widget-text-font"))) {
        $widgetTextFont = get_option("wiremo-widget-text-font");
    }
    $wiremo_star_size = 20;
    switch($widgetStarSize) {
        case "0":
            $wiremo_star_size = 16;
            break;
        case "1":
            $wiremo_star_size = 20;
            break;
        case "2":
            $wiremo_star_size = 24;
            break;
        default:
            $wiremo_star_size = 20;
    }
    include dirname(__DIR__).'/lang/wiremo-widget-'.$lang.'.php';
    if($countReview == 0) {
        $wiremo_count_trans = $wiremoTranslations["Reviews-5-9"];
    }
    else {
        $wiremo_count_trans = wiremo_get_reviews_translation($countReview);
    }
    $wiremo_count_review_size = array(
        "16" => "14px",
        "20" => "18px",
        "24" => "24px"
    );
    $wiremo_count_review_padding = array(
        "16" => "6px",
        "20" => "8px",
        "24" => "10px"
    );
    $wrpw_svg_stroke = '<svg style="width:'.$wiremo_star_size.'px !important;height:'.$wiremo_star_size.'px !important;" xmlns="http://www.w3.org/2000/svg" height="'.$wiremo_star_size.'" viewBox="0 0 1792 1792"><path d="M1201 1004l306-297-422-62-189-382-189 382-422 62 306 297-73 421 378-199 377 199zm527-357q0 22-26 48l-363 354 86 500q1 7 1 20 0 50-41 50-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg>';
    $wrpw_svg_shape = '<svg style="width:'.$wiremo_star_size.'px !important;height:'.$wiremo_star_size.'px !important;" xmlns="http://www.w3.org/2000/svg" height="'.$wiremo_star_size.'" viewBox="0 0 1792 1792"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg>';
    $wrpw_svg = ($widgetStarStyle == 0) ? $wrpw_svg_stroke : $wrpw_svg_shape;
    $widget_top_class = ($location == "product-page") ? "wiremo-widget-top": "";
    $widgetStarStyleClass = ($widgetStarStyle == 1)? "fill-wr-style": "stroke-wr-style";

    $widget_score = "";
    if(isset($widgetHover) && !empty($widgetHover)) {
      $widget_score =
      '<div class="widget-lite-score">
        <table class="widget-lite-score-detailed">
          <tbody>
          <tr>
              <td>5 '.$wiremoTranslations["stars"].'</td>
              <td>
                  <div class="score-gray-bar">
                      <div class="score-active-bar"
                           style="width: '.round($rating5Percent).'%; background-color: '.$widgetStarColor.';"></div>
                  </div>
              </td>
              <td>'.round($rating5Percent).'%</td>
          </tr>
          <tr>
              <td>4 '.$wiremoTranslations["star234"].'</td>
              <td>
                  <div class="score-gray-bar">
                      <div class="score-active-bar"
                           style="width: '.round($rating4Percent).'%; background-color: '.$widgetStarColor.';"></div>
                  </div>
              </td>
              <td>'.round($rating4Percent).'%</td>
          </tr>
          <tr>
              <td>3 '.$wiremoTranslations["star234"].'</td>
              <td>
                  <div class="score-gray-bar">
                      <div class="score-active-bar"
                           style="width: '.round($rating3Percent).'%; background-color: '.$widgetStarColor.';"></div>
                  </div>
              </td>
              <td>'.round($rating3Percent).'%</td>
          </tr>
          <tr>
              <td>2 '.$wiremoTranslations["star234"].'</td>
              <td>
                  <div class="score-gray-bar">
                      <div class="score-active-bar"
                           style="width: '.round($rating2Percent).'%; background-color: '.$widgetStarColor.';"></div>
                  </div>
              </td>
              <td>'.round($rating2Percent).'%</td>
          </tr>
          <tr>
              <td>1 '.$wiremoTranslations["star"].'</td>
              <td>
                  <div class="score-gray-bar">
                      <div class="score-active-bar"
                           style="width: '.round($rating1Percent).'%; background-color: '.$widgetStarColor.';"></div>
                  </div>
              </td>
              <td>'.round($rating1Percent).'%</td>
          </tr>
          </tbody>
        </table>
      </div>';
    }

    $widget_link = '<a style="text-decoration: none;height:'.$wiremo_count_review_size[$wiremo_star_size].';font-size:'.$wiremo_count_review_size[$wiremo_star_size].';line-height: '.$wiremo_count_review_size[$wiremo_star_size].';" href="'.get_the_permalink($productId).'#customer-reviews">('.$countReview.')</a>';
    if($location != "product-page") {
      $widget_link = '<a id="customer-reviews-count" style="text-decoration: none;height:'.$wiremo_count_review_size[$wiremo_star_size].';font-size:'.$wiremo_count_review_size[$wiremo_star_size].';line-height:'.$wiremo_count_review_size[$wiremo_star_size].';" data-box="customer-reviews" href="'.get_the_permalink($productId).'#customer-reviews">'.$countReview.' '.$wiremo_count_trans.'</a>';
    }

    return
    '<div id="widget-lite-short-woo" class="'.$widget_top_class.' '.is_home().'home '.is_shop().'shop">
      <div class="widget-lite">
        <div class="widget-lite-container" style="font-family:'.$widgetTextFont.',sans-serif">
          <a id="customer-reviews-count" data-box="customer-reviews" href="'.get_the_permalink($productId).'#customer-reviews">
            <div class="floating__rating '.$widgetStarStyleClass.'" customizable="" customizetype="floatingContainer">
              <div style="height:'.$wiremo_star_size.'px;font-size:'.$wiremo_star_size.'px;line-height:'.$wiremo_star_size.'px;" class="floating__rating--stroke" customizable="" customizetype="floatingStar">
                '.$wrpw_svg.$wrpw_svg.$wrpw_svg.$wrpw_svg.$wrpw_svg.'
              </div>
              <div class="floating__rating--fill" customizable="" customizetype="floatingStar"style="position:relative;z-index:2;fill:'.$widgetStarColor.';width:'.round($ratingStar).'%; overflow: hidden;height:'.$wiremo_star_size.'px;font-size:'.$wiremo_star_size.'px;line-height:'.$wiremo_star_size.'px;">
                '.$wrpw_svg.$wrpw_svg.$wrpw_svg.$wrpw_svg.$wrpw_svg.'
              </div>
            </div>
          </a>
            '.$widget_score.'
          <div class="widget-lite-count" style="padding-left:'.$wiremo_count_review_padding[$wiremo_star_size].';">
            '.$widget_link.'
          </div>
        </div>
      </div>
    </div>';
}
