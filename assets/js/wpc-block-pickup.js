"use strict";

(function ($) {

    $(document).ready(function () {

		/*--------------------------------
        // checkout order time radio button on change
        // default close pickup
        -----------------------------------*/
        $("body #both_pickup").css("display", "none");

        //Get value from mini cart
        var deliverySavedValue = localStorage.getItem('wpcpro_delivery');
        $("input[name='wpc_pro_order_time'][value='" + deliverySavedValue + "']").attr('checked', 'checked');
        $('input[name="wpc_pro_order_time"]').trigger("change");

        var checkedValue = $('input[name="wpc_pro_order_time"]:checked').val();
        if (typeof checkedValue !== "undefined") {
            order_time_tab(checkedValue)
        }

        //update delivery / pickup value in checkout page
        $("body .woocommerce-input-wrapper").on('change', 'input[name="wpc_pro_order_time"]', function () {
            $(".wpc_error_message").css("display", "none").html("");
            var selectedVal = $(this).val();
            order_time_tab(selectedVal);
            localStorage.setItem('wpcpro_delivery',selectedVal);
            Cookies.set('wpcpro_delivery', selectedVal );
            jQuery('body').trigger('update_checkout');

        });

		function order_time_tab(param) {
            if (param !== undefined) {
                var divToShow = $("#both_" + param.toLowerCase());
                divToShow.slideDown().siblings().not('#wpc_pro_order_time_field').slideUp();
            } else {
                $(".wpc_pro_order_time_settings").children().hide('fast');
            }
        }


    });


})(jQuery);
