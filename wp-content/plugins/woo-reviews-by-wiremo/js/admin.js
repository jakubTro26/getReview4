(function ($) {
    $(window).load(function () {
        if ($("select#wiremo_widget_location").length) {
            var location = $("select#wiremo_widget_location").find("option:selected").val();
            $("select#wiremo_widget_location").change(function () {
                location = $(this).find("option:selected").val();
                if (location == "Tab") {
                    $(".custom-tab-name").removeAttr("disabled", true);
                    $("#wiremo_custom_text_reviews").attr("disabled", true);
                    $(".wiremo-widget-tab-name").removeClass("hidden");
                    $(".wiremo-custom-text-review").addClass("hidden")
                } else {
                    $(".custom-tab-name").attr("disabled", true);
                    $("#wiremo_custom_text_reviews").removeAttr("disabled", true);
                    $(".wiremo-widget-tab-name").addClass("hidden");
                    $(".wiremo-custom-text-review").removeClass("hidden")
                }
            })
        }
        $(document).on("click", ".wiremo-container .step1 .wrpw-register-step", function () {
            var step = $(this).attr("data-step");
            if (step == "step2") {
                $(".wiremo-container .step1").addClass("hidden");
                $(".wiremo-container .step2").removeClass("hidden");
                $(".wiremo-container .step2").addClass("show")
            } else {
                $(".wiremo-container .step1").addClass("hidden");
                $(".wiremo-container .step3").removeClass("hidden");
                $(".wiremo-container .step3").addClass("show")
            }
        });
        $(document).on("click", ".wiremo-container .step2 .wrpw-register-back", function () {
            $(".wiremo-container .step2").removeClass("show");
            $(".wiremo-container .step2").addClass("hidden");
            $(".wiremo-container .step3").removeClass("hidden");
            $(".wiremo-container .step3").addClass("show")
        });
        $(document).on("click", ".wrpw-register-account", function () {
            var $this = $(this);
            $this.attr("disabled", true);
            var response, noticeError = "",
                apiKey;
            var step = $(this).attr("data-action");
            var firstName = $(".wiremo-container ." + step + " .wrpw-first-name").val();
            var lastName = $(".wiremo-container ." + step + " .wrpw-last-name").val();
            var email = $(".wiremo-container ." + step + " .wrpw-email").val();
            $.post(ajaxurl, {
                action: "wiremoAutoRegister",
                nonce: document.getElementById('nonceWiremoAutoRegister').innerText,
                firstName: firstName,
                lastName: lastName,
                email: email
            }, function (data) {
                response = JSON.parse(data);
                if (response.message) {
                    if (response.success == true) {
                        apiKey = response.apiKey;
                        $.post(ajaxurl, {
                            action: "wiremoAddRegisterHook",
                            nonce: document.getElementById('nonceWiremoAddRegisterHook').innerText,
                            apiKey: apiKey
                        }, function () {
                            window.location.reload(true)
                        })
                    } else {
                        $(".wiremo-container ." + step).removeClass("show");
                        $(".wiremo-container ." + step).addClass("hidden");
                        $(".wiremo-container .step1").removeClass("hidden");
                        $(".wiremo-container .step1").addClass("show");
                        noticeError = '<div class="notice notice-error is-dismissible"><p>' + response.message + "</p></div>"
                    }
                    $(".wiremo-container .load-account-box").html(noticeError);
                    $(".wiremo-container .load-account-box").removeClass("hidden");
                    $(".wiremo-container .load-account-box").addClass("show")
                }
                $this.attr("disabled", false)
            });
            return false
        });
        $(document).on("click", ".wiremo-import-reviews", function () {
            var $this = $(this),
                response;
            $this.bootstrapBtn("loading");
            $.post(ajaxurl, {
                action: "importReviewsToWiremo",
                nonce: document.getElementById('nonceImportReviewsToWiremo').innerText,
            }, function (data) {
                $(".wiremo-import-reviews").bootstrapBtn("reset");
                response = JSON.parse(data);
                $(".notification-import.to-wiremo").html(response.message);
                $(".notification-import.to-wiremo").removeClass("hidden").addClass("show");
                setTimeout(function () {
                    response.state == true ? $(".wiremo-import-box").remove() : ""
                }, 1e3)
            });
            return false
        });
        $(document).on("click", ".connect-account", function () {
            $.post(ajaxurl, {
                action: "wiremoAuth",
                nonce: document.getElementById('nonceWiremoAuth').innerText,
            }, function (data) {
                $(".load-account-box").html(data)
            });
            return false
        });
        if ($("#wiremo_widget_display").length) {
            $("#wiremo_widget_display").change(function () {
                if ($("#wiremo_widget_display").is(":checked")) {
                    $("#wiremo_widget_location").removeAttr("disabled");
                    $("#wiremo_custom_tab_name").removeAttr("disabled");
                    $("#wiremo_custom_text_reviews").removeAttr("disabled");
                    $("#wiremo_hide_mini_widget_home").prop("disabled", false);
                    $("#wiremo_hide_mini_widget_cat").prop("disabled", false);
                    $("#wiremo_hide_mini_widget_prod").prop("disabled", false);
                    $("#wiremo_hide_mini_widget").prop("disabled", false);
                    $("#wiremo_related_products_sort").prop("disabled", false);
                    $("#wiremo_show_custom_text_related").removeAttr("disabled");
                    $("#wiremo_related_custom_text").removeAttr("disabled");
                    $("#wiremo_automated_authentification").removeAttr("disabled");
                    $("#wiremo_automated_review_request").removeAttr("disabled");
                    $("#wiremo_email_template").removeAttr("disabled");
                    $("#wiremo-datetime-start").removeAttr("disabled");
                    $("#wiremo-datetime-end").removeAttr("disabled");
                    $("#wiremo_manual_emails_day").removeAttr("disabled");
                    $("#wiremo_manual_email_template").removeAttr("disabled");
                    $(".wiremo-create-campaigns").removeAttr("disabled");
                    $(".import-from-wiremo").removeAttr("disabled");
                    $(".wiremo-import-reviews").removeAttr("disabled")
                } else {
                    $("#wiremo_widget_location").attr("disabled", true);
                    $("#wiremo_custom_tab_name").attr("disabled", true);
                    $("#wiremo_custom_text_reviews").attr("disabled", true);
                    $("#wiremo_hide_mini_widget_home").prop("disabled", true);
                    $("#wiremo_hide_mini_widget_cat").prop("disabled", true);
                    $("#wiremo_hide_mini_widget_prod").prop("disabled", true);
                    $("#wiremo_hide_mini_widget").prop("disabled", true);
                    $("#wiremo_related_products_sort").prop("disabled", true);
                    $("#wiremo_show_custom_text_related").attr("disabled", true);
                    $("#wiremo_related_custom_text").attr("disabled", true);
                    $("#wiremo_automated_authentification").attr("disabled", true);
                    $("#wiremo_automated_review_request").attr("disabled", true);
                    $("#wiremo_email_template").attr("disabled", true);
                    $("#wiremo-datetime-start").attr("disabled", true);
                    $("#wiremo-datetime-end").attr("disabled", true);
                    $("#wiremo_manual_emails_day").attr("disabled", true);
                    $("#wiremo_manual_email_template").attr("disabled", true);
                    $(".wiremo-create-campaigns").attr("disabled", true);
                    $(".import-from-wiremo").attr("disabled", true);
                    $(".wiremo-import-reviews").attr("disabled", true)
                }
            })
        }
        if ($("#wiremo_show_custom_text_related").length) {
            $("#wiremo_show_custom_text_related").change(function () {
                if ($("#wiremo_show_custom_text_related").is(":checked")) {
                    $("#wiremo_related_custom_text").removeAttr("disabled");
                    $("#wiremo_related_products_sort").prop("disabled", false)
                } else {
                    $("#wiremo_related_custom_text").attr("disabled", true);
                    $("#wiremo_related_products_sort").prop("disabled", true)
                }
            })
        }
        if ($("#wiremo_automated_review_request").length) {
            $("#wiremo_automated_review_request").change(function () {
                if ($("#wiremo_automated_review_request").is(":checked")) {
                    $("#wiremo_email_template").removeAttr("disabled")
                } else {
                    $("#wiremo_email_template").attr("disabled", true)
                }
            })
        }
        if ($("#wiremo_email_template").length) {
            $("#wiremo_email_template").change(function () {
                var template = $(this).find("option:selected").html();
                $("#wiremo_email_template_name").val(template)
            })
        }

        function wiremoCreateCampaigns() {
            var startDate, endDate, emailsPerDay, templateId, templateName, currentDateFormated, oldDateOrders, futureDateOrders, validStartDate = true,
                validEndDate = true;
            var currentDate = new Date;
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
            if (day < 10) {
                day = "0" + day
            }
            if (month < 10) {
                month = "0" + month
            }
            currentDateFormated = year + "-" + month + "-" + day;
            if ($(".wiremo-create-campaigns").length) {
                oldDateOrders = $(".wiremo-create-campaigns").attr("data-old-time-orders")
            }
            if ($(".wiremo-create-campaigns").length) {
                futureDateOrders = $(".wiremo-create-campaigns").attr("data-future-time-orders")
            }
            if ($("#wiremo-datetime-start").length) {
                startDate = $("#wiremo-datetime-start").val();
                if (new Date(startDate).getTime() / 1e3 < new Date(oldDateOrders).getTime() / 1e3 || new Date(startDate).getTime() / 1e3 > new Date(futureDateOrders).getTime() / 1e3) {
                    $(".wiremo-start-notification").html("Start date must be between " + oldDateOrders + " and " + futureDateOrders);
                    $(".wiremo-start-notification").removeClass("hidden");
                    validStartDate = false
                } else if (new Date(startDate).getTime() / 1e3 > new Date(endDate).getTime() / 1e3) {
                    $(".wiremo-start-notification").html("Start date must be less than end date");
                    $(".wiremo-start-notification").removeClass("hidden");
                    validStartDate = false
                } else {
                    validStartDate = true
                }
                $("#wiremo-datetime-start").change(function () {
                    startDate = $(this).val();
                    if (new Date(startDate).getTime() / 1e3 < new Date(oldDateOrders).getTime() / 1e3 || new Date(startDate).getTime() / 1e3 > new Date(futureDateOrders).getTime() / 1e3) {
                        $(".wiremo-start-notification").html("Start date must be between " + oldDateOrders + " and " + futureDateOrders);
                        $(".wiremo-start-notification").removeClass("hidden");
                        $(".wiremo-create-campaigns").prop("disabled", true);
                        validStartDate = false
                    } else if (new Date(startDate).getTime() / 1e3 > new Date(endDate).getTime() / 1e3) {
                        $(".wiremo-start-notification").html("Start date must be less than end date");
                        $(".wiremo-start-notification").removeClass("hidden");
                        $(".wiremo-create-campaigns").prop("disabled", true);
                        validStartDate = false
                    } else {
                        $(".wiremo-start-notification").html("");
                        $(".wiremo-start-notification").addClass("hidden");
                        validStartDate = true;
                        if (validStartDate == true && validEndDate == true) {
                            $(".wiremo-create-campaigns").prop("disabled", false)
                        }
                    }
                })
            }
            if ($("#wiremo-datetime-end").length) {
                endDate = $("#wiremo-datetime-end").val();
                if (new Date(endDate).getTime() / 1e3 > new Date(currentDateFormated).getTime() / 1e3) {
                    $(".wiremo-end-notification").html("End date must be between " + startDate + " and " + currentDateFormated);
                    $(".wiremo-end-notification").removeClass("hidden");
                    $(".wiremo-create-campaigns").prop("disabled", true);
                    validEndDate = false
                } else if (new Date(endDate).getTime() / 1e3 < new Date(startDate).getTime() / 1e3) {
                    $(".wiremo-end-notification").html("End date must be greater than " + startDate);
                    $(".wiremo-end-notification").removeClass("hidden");
                    $(".wiremo-create-campaigns").prop("disabled", true);
                    validEndDate = false
                }
                $("#wiremo-datetime-end").change(function () {
                    endDate = $(this).val();
                    if (new Date(endDate).getTime() / 1e3 > new Date(currentDateFormated).getTime() / 1e3) {
                        $(".wiremo-end-notification").html("End date must be between " + startDate + " and " + currentDateFormated);
                        $(".wiremo-end-notification").removeClass("hidden");
                        $(".wiremo-create-campaigns").prop("disabled", true);
                        validEndDate = false
                    } else if (new Date(endDate).getTime() / 1e3 < new Date(startDate).getTime() / 1e3) {
                        $(".wiremo-end-notification").html("End date must be greater than " + startDate);
                        $(".wiremo-end-notification").removeClass("hidden");
                        $(".wiremo-create-campaigns").prop("disabled", true);
                        validEndDate = false
                    } else {
                        $(".wiremo-end-notification").html("");
                        $(".wiremo-end-notification").addClass("hidden");
                        validEndDate = true;
                        if (validStartDate == true && validEndDate == true) {
                            $(".wiremo-create-campaigns").prop("disabled", false)
                        }
                    }
                })
            }
            if ($("#wiremo_manual_emails_day").length) {
                emailsPerDay = $("#wiremo_manual_emails_day").find("option:selected").val();
                $("#wiremo_manual_emails_day").change(function () {
                    emailsPerDay = $(this).find("option:selected").val()
                })
            }
            if ($("#wiremo_manual_email_template").length) {
                templateId = $("#wiremo_manual_email_template").find("option:selected").val();
                templateName = $("#wiremo_manual_email_template").find("option:selected").text();
                $("#wiremo_manual_email_template").change(function () {
                    templateId = $(this).find("option:selected").val();
                    templateName = $(this).find("option:selected").text();
                    $("#wiremo_manual_email_template_name").val(templateName)
                })
            }
            if ($(".wiremo-create-campaigns").length) {
                if (startDate != undefined && startDate != "" && endDate != undefined && endDate != "" && emailsPerDay != undefined && emailsPerDay != "" && templateId != undefined && templateId != "") {
                    if (validStartDate == true && validEndDate == true) {
                        $(".wiremo-create-campaigns").prop("disabled", false)
                    }
                } else {
                    $(".wiremo-create-campaigns").prop("disabled", true)
                }
            }
            var customers = 0;

            function saveCampaignsInformation(campaign) {
                if (!campaign.customers) {
                    return;
                }

                var lineTable = "";
                $.post(ajaxurl, {
                    action: "wiremo_save_campaign_information",
                    nonce: document.getElementById('nonceWiremo_save_campaign_information').innerText,
                    start_date: campaign.start_date,
                    end_date: campaign.end_date,
                    template_name: campaign.template_name,
                    customers: campaign.customers
                }, function (data) {
                    response = JSON.parse(data);
                    if (response.success == true) {
                        $(".wiremo-create-campaigns").bootstrapBtn("reset");
                        if (response.template_name) {
                            lineTable += "<td>" + response.template_name + "</td>"
                        }
                        if (response.start_date) {
                            lineTable += "<td>" + response.start_date + "</td>"
                        }
                        if (response.end_date) {
                            lineTable += "<td>" + response.end_date + "</td>"
                        }
                        if (response.customers) {
                            lineTable += "<td>" + customers + "</td>"
                        }
                        $(".wiremo-campaigns tbody").append("<tr>" + lineTable + "</tr>");
                        $("#manual-review-request").removeClass("wiremo-no-campaigns")
                    }
                })
            }

            function sendOrderToApi(page) {
                var $this = $(this),
                    response;
                $.post(ajaxurl, {
                    action: "wiremo_send_completed_orders",
                    nonce: document.getElementById('nonceWiremo_send_completed_orders').innerText,
                    start_date: startDate,
                    end_date: endDate,
                    emails_per_day: emailsPerDay,
                    template_id: templateId,
                    template_name: templateName,
                    page: page
                }, function (data) {
                    response = JSON.parse(data);
                    if (response.success == true && response.customers) {

                        customers = customers + response.customers

                        if (page < response.limit) {
                            sendOrderToApi(page + 1)
                        } else {
                            response.customers = customers;
                            saveCampaignsInformation(response)
                        }
                    } else {
                        if (page == 1) {
                            if (response.customers == 0) {
                                alert("You don't have any completed orders for this period");
                                $(".wiremo-create-campaigns").bootstrapBtn("reset")
                            } else {
                                alert("Select a smaller period");
                                $(".wiremo-create-campaigns").bootstrapBtn("reset")
                            }
                        } else {
                            response.customers = customers;
                            saveCampaignsInformation(response);
                            $(".wiremo-create-campaigns").bootstrapBtn("reset")
                        }
                    }
                })
            }
            $(document).on("click", ".wiremo-create-campaigns", function () {
                customers = 0;
                var $this = $(this);
                $this.bootstrapBtn("loading");
                sendOrderToApi(1);
                return false
            })
        }
        if (!$(".wiremo-create-campaigns.disabled-btn-campaigns").length) {
            wiremoCreateCampaigns()
        }
    });
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $("#wiremo-datetime-start,#wiremo-datetime-end").datepicker({
            dateFormat: "yy-mm-dd"
        })
    })
})(jQuery);