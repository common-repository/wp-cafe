(function ($, elementor) {
    "use strict";

    var wpc = {

        init: function () {
            var widgets = {
                'wpc-menu-tab.default': wpc.wpc_menu_tab,
                'wpc-location-menu.default': wpc.wpc_location_menu,
            };
            

            $.each(widgets, function (widget, callback) {
                elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
            });
        },

        //start for free widgets
        wpc_location_menu: function ($scope) {
            wpc_widgets_popup($, $(".food_location_wrapper"));
        },

    };
    $(window).on('elementor/frontend/init', wpc.init);
}(jQuery, window.elementorFrontend));