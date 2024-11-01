"use strict";

(function ($) {


    // get location saved data
    var location_data = localStorage.getItem('wpc_location');

    $(document).ready(function () {

        var obj = {};
        var wpc_booking_form_data = {};
        if (typeof wpc_form_client_data !== "undefined") {
            var wpc_form_data = JSON.parse(wpc_form_client_data);
            if ($.isArray(wpc_form_data.settings) && wpc_form_data.settings.length === 0) {
                wpc_booking_form_data = null;
            } else {
                wpc_booking_form_data = wpc_form_data.settings;
            }
        }

        var error_message = $('.wpc_error_message');
        var cancell_log_message = $('.wpc_cancell_log_message');
        var log_message = $('.wpc_log_message');
        // select location
        if (typeof location_data !== "undefined" && location_data !== null) {
            var location_data_parse = JSON.parse(location_data);
            $(".wpc_location_name").val(location_data_parse.value).html(location_data_parse.value);
            $("#filter_location option[value='" + location_data_parse.name + "']").attr("selected", true);
        } else {
            $(".location_heading").css("display", "none")
        }

        //custom tabs
        $('.wpc-food-tab-wrapper').on('click', '.wpc-tab-a', function (event) {
            event.preventDefault();
            var tab_wrpaper = $(this).closest(".wpc-food-tab-wrapper");

            tab_wrpaper.find(".wpc-tab").removeClass('tab-active');
            tab_wrpaper.find(".wpc-tab[data-id='" + $(this).attr('data-id') + "']").addClass("tab-active");
            tab_wrpaper.find(".wpc-tab-a").removeClass('wpc-active');
            $(this).parent().find(".wpc-tab-a").addClass('wpc-active');

        });

        // single page ajax
        if (typeof wc_cart_fragments_params !== "undefined") {
            var $warp_fragment_refresh = {
                url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                //TODO req method is POST but req body is missing, if don't have body, should use get method
                type: 'POST',
                success: function (data) {
                    if (data && data.fragments) {
                        $.each(data.fragments, function (key, value) {
                            $(key).replaceWith(value);
                        });

                        $(document.body).trigger('wc_fragments_refreshed');

                    }

                }
            };
        }

        // set location in local storage and cancel modal

        // press ok button
        $(".wpc_modal").on('click', '.wpc-select-location', function () {
            save_location_data();
            $(".saving_warning").addClass("hide_field");
        });
        // on change location
        $(".wpc-location").on('change', function () {
            $(".saving_warning").removeClass("hide_field");
        });

        function save_location_data() {
            var wpc_location_name = $('.wpc-location option:selected').val();
            var wpc_location_value = $('.wpc-location option:selected').text();

            var local_storage_value = localStorage.getItem('wpc_location');
            var wpc_location_value = wpc_location_name == "" ? "" : wpc_location_value;

            if (!$(this).siblings(".wpc-location-store").length) {
                // save location for single vendor
                //TODO remove if/else condition, cause we setItem(same data) for both cases.
                localStorage.setItem('wpc_location', JSON.stringify({ name: wpc_location_name, value: wpc_location_value }));
                $('#filter_location').find(`option[text="${wpc_location_value}"]`).attr("selected", true);
            }

            $(".wpc_modal").fadeOut();
            $('body').removeClass('wpc_location_popup');
        }

        // on close special_menu popup, save data in local storage
        $(".special-menu-close, .wpc-motd-order-btn, .wpc-motd-product").on('click', function () {
            close_popup("", ".wpc-menu-of-the-day", ".wpc-menu-of-the-day");
            save_special_menu_data();
        });

        function save_special_menu_data() {
            var wpc_special_menu = localStorage.getItem('wpc_special_menu');
            var local_storage_menu_value = (wpc_special_menu == null || wpc_special_menu == "") ? "yes" : wpc_special_menu;

            // TODO why used expiry in localStorage? for auto-epire cookie should be used instead of localStorage.
            localStorage.setItem('wpc_special_menu', JSON.stringify({ wpc_special_menu: local_storage_menu_value, expiry: new Date() }));
        }

        //TODO if we use cookie, no need to remove it manually(btw, first of all need to know the use-case)
        if ($('.wpc-menu-of-the-day').length > 0) {
            var wpc_special_menu = localStorage.getItem('wpc_special_menu');
            var special_menu = JSON.parse(wpc_special_menu);
            wpc_special_menu = wpc_special_menu !== null ? special_menu : null;

            var expTime = special_menu != null ? special_menu.expiry : null;

            if (expTime != null) {
                let currentDate = new Date();
                let expDate = new Date(Date.parse(expTime.toString()));
                var oneDay = 24 * 60 * 60 * 1000;

                if ((currentDate - expDate) > oneDay) {
                    localStorage.removeItem('wpc_special_menu');
                }
            }

            if (wpc_special_menu == null && wpc_special_menu != "yes") {
                jQuery('.wpc-menu-of-the-day').delay(5000).fadeIn();
            } else {
                jQuery('.wpc-menu-of-the-day').fadeOut();
            }

        }


        /*--------------------------------
        // Filter location wise food
        -----------------------------------*/
        if ($("#filter_location").length !== 0) {
            getting_location_data($("#filter_location"), true);
            $(document.body).on('added_to_cart', function () {
                $("#filter_location").attr("data-cart_empty", 0);
            });
        }


        $("#filter_location").on('change', function (e) {
            e.preventDefault();
            var location = $(this).val();
            var cart_empty = $("#filter_location").data("cart_empty");
            var previous_location = localStorage.getItem("wpc_location");
            previous_location = JSON.parse(previous_location);
            // if cart has data and selected location is not equal previous location
            if (location !== "" && cart_empty == 0 &&
                (previous_location !== null && previous_location.name !== location)) {
                $("#location_change").removeClass("hide_field");
                $("body").addClass("wpc_location_popup");
                $("#filter_location option[value='" + previous_location.name + "']").attr("selected", true);
            } else {
                getting_location_data($(this), 1, 0);
            }
        });

        $(".change_yes,.change_no").on('click', function (e) {
            // cart is empty = 1 , cart is not empty = 0
            var call_ajax = 0; var clear_cart = 0;
            if ($(this).hasClass("change_yes")) {
                call_ajax = 1;
                clear_cart = 1;
            }
            else if ($(this).hasClass("change_no")) {
                var cart_empty = $("#filter_location").data("cart_empty");
                // TODO convert if/else if to ternery
                call_ajax = cart_empty ? 1 : 0
                var previous_location = localStorage.getItem("wpc_location");
                previous_location = JSON.parse(previous_location);

                $("#filter_location option[value='" + previous_location.name + "']").attr("selected", true);
            }

            getting_location_data($("#filter_location"), call_ajax, clear_cart);

            close_popup("wpc_location_popup", "#wpc_location_modal", ".location_modal");

        });

        $(".discard_booking").on("click", function () {
            $("body").addClass("wpc_location_popup");
            $("#wpc_booking_modal").removeClass("hide_field");
        });

        function close_popup(...args) {
            $('body').removeClass(args[0]);
            $(args[1]).css("display", "none")
            $(args[2]).addClass("hide_field")
        }

        function getting_location_data($this, call_ajax = false, clear_cart = 0) {
            if (typeof wpc_form_data !== "undefined") {
                // TODO remove duplicate variables;
                var location = $this.val();
                var location_name = $("#filter_location option:selected").text();
                var location_menu = $('.location_menu');

                // TODO we can use single key in obj, if key and value is same name.
                let location_data_obj = { location, clear_cart, action: 'filter_food_location', _wpc_nonce: wpc_form_data._nonces.filter_food_location_nonce };
                if (location_menu.length !== 0) {
                    location_data_obj.product_data = location_menu.data('product_data');
                }

                if (call_ajax) {
                    $.ajax({
                        url: wpc_form_data.wpc_ajax_url,
                        type: 'POST',
                        // TODO should use only string in api-call/req-body
                        data: location_data_obj,
                        dataType: 'html',
                        beforeSend: function () {
                            $(".food_location").addClass("loading");
                        },
                        success: function (data) {
                            if (typeof data !== "undefined") {
                                var response = JSON.parse(data);
                                var food_location = location_menu.find('.food_location');
                                food_location.html("").html(response.html);

                                $("#filter_location").attr("data-cart_empty", response.cart_empty);
                                if (clear_cart == 1) {
                                    $('body').trigger('wc_fragment_refresh');
                                    $('body').trigger('wpc-mini-cart-count');
                                }

                                $("a.ajax_add_to_cart").attr("data-wpc_location_id", location);
                                $(".food_location").removeClass("loading");
                                // change store location data
                                localStorage.removeItem("wpc_location");
                                location_name = location == "" ? "" : location_name;
                                localStorage.setItem("wpc_location", JSON.stringify({ name: location, value: location_name }));

                            }
                        },

                    });
                }

            }
        }


        //====================== Reservation form actions start ================================= //

        var $wpc_booking_section = $('.reservation_section');
        var wpc_booking_date = $wpc_booking_section.find("#wpc_booking_date");

        if (wpc_booking_date.length > 0) {
            var wpc_pro_form_data = $(".wpc_calender_view").data('view');

            var inline_value = true;
            if (typeof wpc_pro_form_data !== 'undefined' && wpc_pro_form_data == 'no') {
                inline_value = false;
            }
            var reserve_status = $(".wpc-reservation-form").data('reservation_status');
            obj.wpc_booking_date = wpc_booking_date;
            obj.booking_form_type = "frontend";
            obj.inline_value = inline_value;
            obj.reserve_status = reserve_status;
            obj.wpc_form_client_data = wpc_booking_form_data;


            if (typeof reservation_form_actions == 'function') {
                reservation_form_actions($, obj);
            }
        }

        //====================== Reservation form actions end ================================= //


        //====================== Reservation  validation start ================================= //

        var booking_length = $(".reservation_form_submit").length;
        if (booking_length > 0) {
			var booking_field = [];
            $(".wpc_reservation_form_one input, .wpc_reservation_form_one select, .wpc_reservation_form input, .wpc_reservation_form select").not(':button,:submit,:hidden').each(function () {
				let $this = $(this);
				if ($this.prop('required') || ( $this.parent().data('validation') == "required" ) ) {
					booking_field.push($this.attr("id"));
				}
            });

			$(".step-two input,.step-two select").each(function () {
				let $this = $(this);
				if (  $this.prop('required') || $this.parent().data('validation') == "required" ) {
					booking_field.push($this.attr("id"));
				}
			});
			// pro
            $(".wpc-form-next").on('click', function () {
				validation_checking($, booking_field, ".reservation_form_submit", "wpc_booking_error", "wpc_reservation_form_disabled", ".wpc_reservation_table");
            });
            if (typeof validation_checking == 'function') {
                validation_checking($, booking_field, ".reservation_form_submit", "wpc_booking_error", "wpc_reservation_form_disabled", ".wpc_reservation_table");
            }
        }

        function cancel_form_validation() {
            var cancel_form = $(".wpc_reservation_cancel_form").css('display');
            if (cancel_form == "block") {
                var cancel_form_field = ["input[name='wpc_reservation_invoice']", "input[name='wpc_cancell_email']"];
                if (typeof validation_checking == 'function') {
                    validation_checking($, cancel_form_field, ".cancell_form_submit", "wpc_cancell_error", "wpc_cancell_form_submit_disabled", ".wpc_reservation_cancel_form");
                }
            }
        }
        //====================== Reservation  validation end ================================= //

        // pop up structure

        function food_menu_modal(modal_class, body_class) {
            if (document.querySelector("." + modal_class) !== null) {
                $("." + modal_class).fadeIn();
                $('body').addClass(body_class);
            }
        }

        function food_menu_modal_close(modal_class, body_class, from_icon = true, e = null, conent_id) {
            if (from_icon == true) {
                $("." + modal_class).fadeOut();
                $('body').removeClass(body_class);
            } else {
                var container = $("#" + conent_id);
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    $("." + modal_class).fadeOut();
                    $('body').removeClass(body_class);
                }
            }
        }

        // food location popup

        food_menu_modal('wpc_modal', 'wpc_location_popup');

        $('.wpc_modal').on('click', '.wpc-close', function () {
            food_menu_modal_close('wpc_modal', 'wpc_location_popup');

            if (!$("body").hasClass("wpc_location_popup")) {
                var wpc_special_menu = localStorage.getItem('wpc_special_menu');
                wpc_special_menu = wpc_special_menu !== null ? JSON.parse(wpc_special_menu) : null;
                if (wpc_special_menu == null && wpc_special_menu != "yes" || (typeof wpc_special_menu.value !== "undefined"
                    && wpc_special_menu.value == "")) {
                    $('.wpc-menu-of-the-day').show();
                } else {
                    $('.wpc-menu-of-the-day').hide();
                }
            }
        });

        $('.wpc_booking_modal').on('click', '.wpc-close', function (e) {
            e.preventDefault();
            close_popup("", "", "#wpc_booking_modal");
            $('body').removeClass("wpc_location_popup");
        });

        // reservation from show /hide
        $('.wpc_reservation_table').on('click', '#wpc_cancel_request', function () {
            $('.wpc-reservation-form .wpc_reservation_table').slideUp();
            $('.wpc-reservation-form .wpc_reservation_cancel_form').slideDown();
            cancel_form_validation();
        });

        $('.wpc_reservation_cancel_form').on('click', '#wpc_book_table', function () {
            $('.wpc-reservation-form .wpc_reservation_cancel_form').slideUp();
            $('.wpc-reservation-form .wpc_reservation_table').slideDown();
        });

        var wpc_cart_block = $('.wpc-cart_main_block');
        // cart icon open
        wpc_cart_block.on('click', '.wpc_cart_icon, .minicart-close', function (event) {
            event.preventDefault();
            wpc_cart_block.toggleClass('cart_icon_active');
        });

        $(document).on('mouseup', function (e) {
            if (!wpc_cart_block.is(e.target) && wpc_cart_block.has(e.target).length === 0) {
                wpc_cart_block.removeClass('cart_icon_active');
            }
        });



        if (typeof wpc_form_data !== 'undefined') {
            /*****************************
             * reservation form submit
             **************************/


            var wpc_ajax_url = wpc_form_data.wpc_ajax_url;
            var wpc_reservation_form_nonce = wpc_form_data.wpc_reservation_form_nonce;
            var reserv_extra = [];

            $(".reservation_form_submit").on('click', function (e) {
                e.preventDefault();
                if ($(window).width() < 992) {
                    $('html, body').animate({ scrollTop: $(this).parents().find('.reservation_section').offset().top }, 'slow');
                }

                if ($(".wpc_success_message").length > 0) {
                    $(".wpc_success_message").css('display', 'none').html("")
                }
                var wpc_reservation_first = $(".reservation_form_first_step").val();

                if (typeof wpc_reservation_first !== 'undefined' && wpc_reservation_first == 'reservation_form_first_step') {
                    var wpc_name = $("#wpc-name").val();
                    var wpc_webhook = $("#wpc-webhook").val();
                    var wpc_email = $("#wpc-email").val();
                    var wpc_phone = $("#wpc-phone").val();
                    var wpc_booking_date = $("#wpc_booking_date").val();
                    var wpc_from_time = $("#wpc_from_time").val();
                    var wpc_to_time = $("#wpc_to_time").val();

                    var wpc_guest_count = ($('.wpc_visual_selection').length == 0) ? $("#wpc-party option:selected").val() : $('.wpc_guest_count').val();
                    var wpc_branch = $("#wpc-branch option:selected").html();
                    var wpc_message = $("textarea#wpc-message").val();

                    // booking from for check
                    $(".wpc_reservation_form_one").fadeOut(100, "linear", function () {
                        $(".wpc_reservation_form_two").fadeIn(100);
                    });

                    $(".wpc_check_name").html(wpc_name);
                    $(".wpc_check_email").html(wpc_email);


                    var wpc_check_phone = "wpc_check_phone";

                    if (wpc_phone !== "") {
                        $("." + wpc_check_phone).html("").html(wpc_phone);
                        $("#" + wpc_check_phone).removeClass("hide_field");

                    } else {
                        $("." + wpc_check_phone).html("");
                        $("#" + wpc_check_phone).addClass("hide_field");
                    }

                    $(".wpc_check_guest").html(wpc_guest_count);
                    $(".wpc_check_start_time").html(wpc_from_time);
                    $(".wpc_check_end_time").html(wpc_to_time);
                    $(".wpc_check_booking_date").html(wpc_booking_date);
                    $(".wpc_check_message").html(wpc_message);
                    $(".wpc_check_branch").html(wpc_branch);

                    if (wpc_message !== "") {
                        $("#wpc_reserv_message").html('<strong>' + wpc_form_data.wpc_form_dynamic_text.wpc_additional_information + '</strong><span class="wpc_reserv_message">' + wpc_message + '<span>').css('display', 'block');
                    } else {
                        $("#wpc_reserv_message").css('display', 'none');
                    }

                }

                // reservation extra field
                if (typeof reservation_extra_field === "function") {
                    //TODO reservation_extra_field call here, also inside reservation_extra_field_list() function.
                    reserv_extra = reservation_extra_field()
                    reservation_extra_field_list();
                }

            });

            var confirm_booking_btn = $(".confirm_booking_btn");
            var cancell_form_submit = $(".cancell_form_submit");
            var another_reservation_free = $(".wpc-another-reservation-free");
            var reservation_submit_action = false;
            $(".cancell_form_submit,.confirm_booking_btn").on('click', function (e) {
                e.preventDefault();
                var cancel_form = false;
                var reservation_form = false;
                if (reservation_submit_action) {
                    return;
                }

                var wpc_invoice = $(".wpc-invoice").val();
                var wpc_email = $(".wpc_cancell_email").val();

                if (typeof wpc_invoice !== "undefined" && (wpc_invoice !== '' && wpc_email !== '')) {

                    var wpc_phone = $(".wpc_cancell_phone").val();
                    var wpc_message = $(".wpc_cancell_message").val();
                    var data = {
                        action: 'wpc_check_for_submission',
                        wpc_cancell_email: wpc_email,
                        wpc_cancell_phone: wpc_phone,
                        wpc_reservation_invoice: wpc_invoice,
                        wpc_message: wpc_message,
                        wpc_action: 'wpc_cancellation',
                        _wpc_nonce: wpc_form_data._nonces.wpc_check_for_submission_nonce
                    }
                    cancel_form = true;
                } else {
                    var reservation_form_second_step = $(this).data('id');
                    if (typeof reservation_form_second_step !== 'undefined' && reservation_form_second_step == 'reservation_form_second_step') {
                        //TODO clear out unnecessary variables
                        var data = {
                            action: 'wpc_check_for_submission',
                            _wpc_nonce: wpc_form_data._nonces.wpc_check_for_submission_nonce,
                            wpc_webhook: $(".wpc_webhook").val(),
                            wpc_name: $(".wpc_check_name").text(),
                            wpc_email: $(".wpc_check_email").text(),
                            wpc_phone: $(".wpc_check_phone").text(),
                            wpc_guest_count: $(".wpc_check_guest").text(),
                            wpc_from_time: $(".wpc_check_start_time").text(),
                            wpc_to_time: $(".wpc_check_end_time").text(),
                            wpc_booking_date: $("#wpc_booking_date").data("wpc_booking_date"),
                            wpc_message: $("textarea#wpc-message").val(),
                            wpc_branch: $(".wpc_check_branch").text(),
                            reserv_extra: reserv_extra,
                            wpc_action: 'wpc_reservation',
                        }

                        if ($(".wpc_visual_selection").length > 0) {

                            //TODO clear out unnecessary variables

                            data.wpc_visual_selection = $(".wpc_visual_selection").val();
                            data.wpc_schedule_slug = $(".wpc_schedule_slug").val();
                            data.wpc_booked_ids = $(".wpc_booked_ids").val();
                            data.wpc_booked_table_ids = $(".wpc_booked_table_ids").val();
                            data.wpc_obj_names = $(".wpc_obj_names").val();
                            data.wpc_intersected_data = $(".wpc_intersected_data").val();
                            data.wpc_mapping_data = $(".wpc_mapping_data").val();
                            data.wpc_webhook = $(".wpc_webhook").val();

                        }

                        var reservation_form = true;
                    }
                }
                if (cancel_form || reservation_form) {
                    $.ajax({
                        url: wpc_ajax_url,
                        method: 'post',
                        data: data,
                        beforeSend: function (params) {
                            reservation_submit_action = true;
                            $(".wpc-another-reservation").css("display", 'none');
                            if (reservation_form) {
                                confirm_booking_btn.addClass("loading");
                            }
                            else if (cancel_form) {
                                cancell_form_submit.addClass("loading");
                            }
                        },
                        success: function (response) {
                            reservation_submit_action = false
                            if (typeof response.data.data !== "undefined" && response.data.data.form_type == 'wpc_reservation' && ($.isArray(response.data.message) && response.data.message.length > 0)) {
                                confirm_booking_btn.removeClass("loading").fadeOut();
                                another_reservation_free.fadeIn();
                                $(".edit_booking_btn").css('display', 'none');
                                error_message.css('display', 'none');
                                error_message.html('');
                                var form_type = jQuery(".form_style").data("form_type");

                                var invoice = typeof response.data.data.invoice !== "undefined" ? response.data.data.invoice : "";
                                var message = typeof response.data.message[0] !== "undefined" ? response.data.message[0] : "";
                                if (typeof reservation_success_block !== "undefined" && form_type == "pro") {
                                    var arr = { invoice: invoice, message: message };
                                    reservation_success_block(arr);
                                } else {
                                    log_message.fadeIn().html(response.data.message[0]);
                                }

                                $("#wpc-webhook").val("");
                                $("#wpc-name").val("");
                                $("#wpc-email").val("");
                                $("#wpc-phone").val("");
                                $("#wpc_booking_date").val("");
                                $("#wpc_from_time").val("");
                                $("#wpc_to_time").val("");

                                if ($('.wpc_visual_selection').length == 0) {
                                    $("#wpc-party option:selected").removeAttr("selected");
                                } else {
                                    $('.wpc_guest_count').val('');
                                    $('.wpc_booked_ids').val('')
                                    $('.wpc_booked_table_ids').val('')
                                    $('.wpc_obj_names').val('')
                                }

                                $("#wpc-branch option:selected").removeAttr("selected");
                                $("#wpc-message").val("");

                            } else if (response.data.data.form_type == 'wpc_reservation_field_missing' && ($.isArray(response.data.message) && response.data.message.length > 0)) {
                                error_message.css('display', 'block').html(response.data.message[0]);
                            } else if (response.data.data.form_type == 'wpc_reservation_cancell' && ($.isArray(response.data.message) && response.data.message.length > 0)) {
                                error_message.css('display', 'none').html('');
                                cancell_log_message.css('display', 'block').html(response.data.message[0]);
                                cancell_form_submit.removeClass("loading").fadeOut();

                                $(".wpc_cancell_email").val("");
                                $(".wpc_cancell_phone").val("");
                                $(".wpc_cancell_message").val("");
                                $(".wpc-invoice").val("");
                                if (response.data.status_code === 200) {
                                    $(".cancell_form_submit").fadeOut('slow');
                                }

                            } else if (response.data.data.form_type == 'wpc_reservation_cancell_field_missing' && ($.isArray(response.data.message) && response.data.message.length > 0)) {
                                error_message.css('display', 'block').html(response.data.message[0]);
                                cancell_log_message.css('display', 'none');
                            }

                            $(".wpc-another-reservation").css("display", 'inline');
                        },
                        complete: function () {
                            reservation_submit_action = false
                        },
                    });
                }
            });
        }

        // back to edit form
        $(".edit_booking_btn,.wpc-another-reservation-free").on('click', function (e) {
            e.preventDefault();
            const isEdit = e.target == document.getElementsByClassName('edit_booking_btn')[0] ? true : false;
            reservation_form_action(isEdit);
            // booking from for check
            $(".wpc_reservation_form_two").fadeOut(100, "linear", function () {
                $(".wpc_reservation_form_one").fadeIn(100, "linear");
            });
            $('.wpc-another-reservation-free').removeAttr("style");
            $('.confirm_booking_btn').removeAttr("style");
            $('.edit_booking_btn').removeAttr("style");
            $('.wpc_log_message').removeAttr("style");
        });

        $(".wpc-another-reservation").on('click', function (e) {
            e.preventDefault();
            $('.wpc_reservation_form .wpc-field-set').css("display", "none");
            $('.wpc_reservation_form .wpc-reservation-success').css("display", "none");
            $(".wpc-reservation-pagination li").removeClass("active");
            $('.wpc_reservation_form .wpc-field-set:first-child').fadeIn(1000);
            $(".wpc-reservation-pagination li:first-child").addClass("active");
            $('.wpc_reservation_form .wpc-field-set .wpc_reservation_info').removeAttr("style");
            $('.wpc_reservation_form .wpc-field-set .wpc_reservation_info .confirm_booking_btn').removeAttr("style");
            $('.wpc_reservation_form .wpc-field-set #wpc_reserv_message').removeAttr("style");
            // reset form and disable button
            reservation_form_action();
        });

        /**
         * Book again button action
         */
        function reservation_form_action(isEdit = false) {
            // reset form and disable button
            $("#wpc-party option[value='1']").prop("selected", true);
            if (!isEdit) {
                $(".reservation_form_submit").addClass("wpc_reservation_form_disabled");
                $(".reservation_form_submit").prop("disabled", true);
            } else {
                $(".reservation_form_submit").removeClass("wpc_reservation_form_disabled");
                $(".reservation_form_submit").prop("disabled", false);
            }
        }

    });




})(jQuery);

// remove block
function remove_block(obj) {
    jQuery(obj.parent_block).on('click', obj.remove_button, function (e) {
        e.preventDefault();
        jQuery(this).parent(obj.removing_block).remove();
    });
}

/**
 * Ajax add to cart
 * @param {*} $ 
 */
function wpc_add_to_cart($) {

    $('body').on('submit', 'form.cart', function (evt) {
        evt.preventDefault();
        var $this = $(this);
        $this.find('.button').removeClass('added').addClass('loading');
        var product_url = window.location,
            form = $(this);
        var form_data;
        var simple_pro_id = $('.single_add_to_cart_button').val();
        if (typeof simple_pro_id !== 'undefined' && simple_pro_id !== '') {
            form_data = form.serialize() + '&' +
                encodeURI('add-to-cart') +
                '=' +
                encodeURI(simple_pro_id)
        } else {
            form_data = form.serialize();
        }
        $.post(product_url, form_data + '&_wp_http_referer=' + product_url, function (result) {
            $(document.body).trigger('wc_fragment_refresh');

            var cart_dropdown = $('.widget_shopping_cart', result)
            // update dropdown cart
            $('.widget_shopping_cart').replaceWith(cart_dropdown);

            // update fragments
            if (typeof $warp_fragment_refresh !== "undefined") {
                $.ajax($warp_fragment_refresh);
            }

            $this.find('.button').removeClass('loading').addClass('added');
            //clsoe the popup after added to cart
            $this.parents('#product_popup').find('.wpc-close').click()

            $("body").trigger('added_to_cart');
        });
    });
}
