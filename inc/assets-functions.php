<?php

add_action('wp_footer', function () {

    echo fs_get_template('assets/scripts', [
        'loader_url'       => get_site_url() . "/wp-admin/images/wpspin_light.gif",
        'admin_url'        => admin_url('admin-ajax.php'),
        'get_products_url' => \FastSearch\Endpoint\WsEndpoint::getListProductsNoQueryUrl(get_option(\FastSearch\Helper\OptionsHelper::PUBLIC_KEY_METAKEY)),
    ]);

    wp_enqueue_style('ws-search-css', FS_ASSETS_URL . '/css/search.css');

    wp_enqueue_script('ws-search-js', FS_ASSETS_URL . '/js/search.js');
});

add_action('admin_footer', function () {

    echo fs_get_template('assets/scripts', [
        'loader_url'       => get_site_url() . "/wp-admin/images/wpspin_light.gif",
        'admin_url'        => admin_url('admin-ajax.php'),
        'get_products_url' => \FastSearch\Endpoint\WsEndpoint::getListProductsNoQueryUrl(get_option(\FastSearch\Helper\OptionsHelper::PUBLIC_KEY_METAKEY)),
    ]);

    ?>
    <script src="<?php echo FS_ASSETS_URL . "/js/reindex.js"; ?>"></script>
    <script src="<?php echo FS_ASSETS_URL . "/js/logout.js"; ?>"></script>
    <?php
//    wp_enqueue_script('ws-reindex-js', );
});