var ws = window.ws || {};

ws.initReindex = function () {

    // jQuery(".fs_admin_sync").append("")

    ws.initReindex.getStatus = function (callback) {

        jQuery.ajax({
            url: ws.ajax_url,
            method: 'get',
            data: {
                action: 'fs_reindex_status_products'
            }
        }).done(function (response) {

            callback(response);
        });
    };

    ws.initReindex.reindex = function (status, progressCallback, doneCallback) {

        progressCallback(status);

        var endReindex = true;

        if (status.status == "OK") {

            if (!status.is_done) {

                endReindex = false;

                jQuery.ajax({
                    url: ws.ajax_url,
                    method: 'get',
                    data: {
                        action: 'fs_reindex_product'
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        ws.initReindex.reindex(status, progressCallback, doneCallback);
                    },
                    timeout: 60000
                }).done(function (response) {
                    ws.initReindex.reindex(response, progressCallback, doneCallback);
                });
            }
        }

        if (endReindex) {

            doneCallback(status);
        }
    };

    ws.initReindex.start = function () {
        ws.initReindex.getStatus(function (status) {

            if (status.status == "OK") {
                ws.initReindex.reindex(status, function (status) {

                    // Progress

                    var syncItem = jQuery(".fs_sync");

                    var progress = status ? status.progress : "?";
                    var count = status ? status.count : "?";

                    var content = syncItem.data("content")
                        .replace(/{progress}/ig, progress)
                        .replace(/{count}/ig, count)
                    ;
                    syncItem.html(content);

                }, function (status) {

                    // Done
                    ws.initReindex.done();
                });

                if (status.is_done) {
                    ws.initReindex.done();
                }
            } else {
                jQuery("#wp-admin-bar-fs_admin_index").remove();
            }
        });
    };

    ws.initReindex.done = function () {
        jQuery(".fs_sync_restart").fadeIn(0);
        jQuery(".fs_sync img").remove();
    };

    ws.initReindex.start();

    jQuery("body").on("click", ".fs_sync_restart", function () {

        jQuery.ajax({
            url: ws.ajax_url,
            method: 'get',
            data: {
                action: 'fs_reindex_restart'
            }
        }).done(function () {
            ws.initReindex.start();
        });
    });

};

jQuery(function () {
    ws.initReindex();
});