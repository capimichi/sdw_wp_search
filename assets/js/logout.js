var ws = window.ws || {};

ws.initLogout = function () {

    jQuery(".fs_logout").on("click", function () {

        jQuery(this).html("<img src='" + ws.loader_url + "'/>");

        jQuery.ajax({
            url: ws.ajax_url,
            data: {
                action: 'fs_logout'
            },
            method: 'get'
        }).done(function (r) {
            window.location.reload();
        });
    });
};

jQuery(function () {
    ws.initLogout();
});