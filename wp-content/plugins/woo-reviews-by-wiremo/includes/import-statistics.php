<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Import statistics from wiremo
global $post;
$api_key = get_option("wiremo-api-key");
?>

<script>
    (function ($) {
        $(window).load(function () {
            var objIdentifiers = {};
            var idsProducts = [];
            var totalProducts;
            var productCount = 1;
            var wr_continue = true;
            var wiremoIdentifiers = [];
            $(document).on("click", ".import-from-wiremo", function () {
                var arrIdentifiers = [];
                var limitRequest = <?php echo WRPW_LIMIT_REQ; ?>;
                var page;
                var step = "step0";
                var currPage = 0;
                $(this).bootstrapBtn('loading');
                $("#wiremo-import-bar").css("display", "block");
                $(".widget-import .tooltip").remove();

                $.ajax({
                    type: 'GET',
                    url: "<?php echo WRPW_URLAPP.'/v1/sites/identifiers/all/?apiKey='.$api_key; ?>",
                    success: function (response) {
                        if(response.length == 0) {
                            setTimeout(function () {
                                $(".notification-import.from-wiremo").html("<?php echo __("You don't have any reviews","wiremo-widget"); ?>");
                                $(".notification-import.from-wiremo").removeClass("hidden").addClass("show");
                                location.reload(true);
                            }, 1000);
                        }
                        if(response.length % limitRequest == 0) {
                            page = response.length  / limitRequest;
                        }
                        else {
                            page = (Math.round(response.length  / limitRequest)) + 1;
                        }
                        for(var j=1;j<=page;j++) {
                            idsProducts.push(j);
                        }
                        totalProducts = idsProducts.length;

                        for(var i=0;i<response.length;i++) {
                            if(i % limitRequest == 0){
                                currPage = currPage + 1;
                                step = "step"+currPage;
                                arrIdentifiers = [];
                            }
                            wiremoIdentifiers.push(response[i]["identifier"]);
                            arrIdentifiers.push(response[i]);
                            objIdentifiers[step] = arrIdentifiers;
                        }
                        if (idsProducts != 0) {
                            $("#wiremo-import-bar").progressbar();
                            $("#wiremo-import-bar-percent").html("0%");
                        }
                        wiremoImportStatistics(idsProducts.shift());
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                return false;
            });
            function wiremoImportUpdateStatus() {
                $("#wiremo-import-bar").progressbar("value", (productCount / totalProducts) * 100);
                $("#wiremo-import-bar-percent").html(Math.round(( productCount / totalProducts ) * 1000) / 10 + "%");
                productCount = productCount + 1;

            }

            function wiremoImportFinishUp() {
                $.post(ajaxurl, {action: "wrpw_reset_old_identifiers",wiremoIdentifiers:wiremoIdentifiers, nonce: document.getElementById('nonceWrpw_reset_old_identifiers').innerText}, function () {
                    $(".import-from-wiremo").bootstrapBtn('reset');
                    $(".notification-import.from-wiremo").html("<?php echo __("Your reviews statistic successful imported from wiremo","wiremo-widget"); ?>");
                    $(".notification-import.from-wiremo").removeClass("hidden").addClass("show");
                    setTimeout(function () {
                        $(".notification-import.from-wiremo").removeClass("show").addClass("hidden");
                        $(".notification-import.from-wiremo").html("");
                        location.reload(true);
                    }, 1000);
                });
            }

            function wiremoImportStatistics(id) {
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {action: "importWiremoStatistics", nonce: document.getElementById('nonceImportWiremoStatistics').innerText, id: id,identifiers:objIdentifiers["step"+id]},
                    success: function (response) {
                        if (response.success) {
                            wiremoImportUpdateStatus();
                        }
                        else {
                            wiremoImportUpdateStatus();
                        }
                        if (idsProducts.length && wr_continue) {
                            wiremoImportStatistics(idsProducts.shift());
                        }
                        else {
                            wiremoImportFinishUp();
                        }
                    },
                    error: function () {
                        wiremoImportUpdateStatus();
                        if (idsProducts.length && wr_continue) {
                            wiremoImportStatistics(idsProducts.shift());
                        }
                        else {
                            wiremoImportFinishUp();
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
