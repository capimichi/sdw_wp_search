var ws = window.ws || {};

ws.initConfig = function () {


};

ws.initSearch = function () {

    var searchBlockIdentifier = ".ws-search-block";

    var searchBlock = jQuery(searchBlockIdentifier);

    var searchBlockList = searchBlock.find("ul");

    var searchInputs = jQuery("input[type='search']");

    searchBlock.hide();

    ws.initSearch.refreshLayout = function (input) {

        searchBlock.css({
            top: input.offset().top + 30,
            left: input.offset().left
        });

        var windowWidth = jQuery(window).width();

        if (windowWidth < 500) {
            searchBlock.css({
                height: 150
            });
        }

        var windowHeight = jQuery(window).height();
        if (searchBlock.offset().top + searchBlock.height() > windowHeight) {
            searchBlock.css({
                top: input.offset().top - 30 - searchBlock.height()
            });
        }
    };

    ws.initSearch.initSearchInput = function (input) {

        var requestOnGoing = null;
        var requestWait = null;
        var query = null;
        var scrollLayoutTimeout = null;

        ws.initSearch.refreshLayout(input);

        jQuery(window).on("scroll", function () {

            if (scrollLayoutTimeout) {
                clearTimeout(scrollLayoutTimeout);
            }

            scrollLayoutTimeout = setTimeout(function () {
                ws.initSearch.refreshLayout(input);
            }, 25);
        });

        input.on("input", function () {

            ws.initSearch.refreshLayout(input);

            searchBlock.show();

            searchBlockList.html("<img class='loader' src='" + ws.loader_url + "'>");

            if (requestOnGoing) {
                // requestOnGoing.abort();
            }

            query = input.val();

            if (query.length) {

                try {

                    requestWait = setTimeout(function () {

                        requestOnGoing = jQuery.ajax({
                            url: ws.get_products_url,
                            dataType: 'jsonp',
                            // jsonpCallback: 'callback',
                            data: {
                                'query': query,
                                // 'callback': 'callback'
                            },
                            method: 'get'
                        }).done(function (response) {

                            var sampleItem = searchBlock.data("item");

                            searchBlockList.html("");

                            if (response.status == "OK" && response.results.length) {

                                response.results.forEach(function (item, index) {

                                    var title = item.title;

                                    title = title.length > 120 ? title.substring(0, 120) + "..." : title;

                                    var queryPieces = query.split(' ');

                                    for (var j = 0; j < queryPieces.length; j++) {
                                        if (["b"].indexOf(queryPieces[j]) == -1) {
                                            var titleQueryRegex = new RegExp("(" + queryPieces[j] + ")", "i");

                                            for (var i = 1; i < (title.match(titleQueryRegex) || []).length; i++) {
                                                title = title.replace(titleQueryRegex, '<b>$' + i + '</b>');
                                            }
                                        }
                                    }

                                    var renderItem = sampleItem.replace(/{title}/g, title)
                                        .replace(/{regular_price}/g, item.regular_price)
                                        .replace(/{image}/g, item.image)
                                        .replace(/{url}/g, item.url)
                                    ;

                                    searchBlockList.append(renderItem);
                                });

                                ws.initSearch.refreshLayout(input);

                            } else {

                                searchBlockList.html("No result found...");
                            }
                        });
                    }, 10);
                } catch (exception) {
                    // console.log(exception.message);
                }
            } else {
                searchBlock.hide();
            }

        });
    };

    searchInputs.each(function () {
        var input = jQuery(this);
        ws.initSearch.initSearchInput(input);
    });


    //Close on click outside
    jQuery(document).click(function (event) {
        if (!jQuery(event.target).closest(searchBlockIdentifier).length) {
            if (searchBlock.is(":visible")) {
                searchBlock.hide();
            }
        }
    });
};

jQuery(function () {
    ws.initConfig();
    ws.initSearch();
});