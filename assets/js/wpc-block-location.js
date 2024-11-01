"use strict";

(function ($) {

    // get location saved data
    var location_data = localStorage.getItem('wpc_location');

    $(document).ready(function () {

		// select location
        if (typeof location_data !== "undefined" && location_data !== null) {
            var location_data_parse = JSON.parse(location_data);
            $(".wpc_location_name").val(location_data_parse.value).html(location_data_parse.value);
            $("#filter_location option[value='" + location_data_parse.name + "']").attr("selected", true);
        } else {
            $(".location_heading").css("display", "none")
        }

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


    });


})(jQuery);
