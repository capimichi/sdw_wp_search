<?php

add_action('admin_menu', function () {
    add_menu_page("WooCommerce Search", "Woocommerce Search", "manage_options", FS_INDEX_PAGE_SLUG, function () {

        $template = "page/index";
        $templateArgs = [
            'api_keys' => [],
        ];

        $secretKey = isset($_POST['fs_secretkey']) ? $_POST['fs_secretkey'] : "";

        $templateArgs['secret_key'] = $secretKey;

        if ($secretKey) {

            $publicKey = \WoocommerceSearch\Helper\WsRemoteHelper::getPublicKey($secretKey);

            if ($publicKey) {
                update_option(\WoocommerceSearch\Helper\OptionsHelper::SECRET_KEY_METAKEY, $secretKey);
                update_option(\WoocommerceSearch\Helper\OptionsHelper::PUBLIC_KEY_METAKEY, $publicKey);
            }
        }

        $secretKey = get_option(\WoocommerceSearch\Helper\OptionsHelper::SECRET_KEY_METAKEY, "");
        $publicKey = get_option(\WoocommerceSearch\Helper\OptionsHelper::PUBLIC_KEY_METAKEY, "");

        if ($secretKey) {
            $remoteProductsCount = \WoocommerceSearch\Helper\WsRemoteHelper::getCountProducts($publicKey);

//            $reindexProgress = get_option(\WoocommerceSearch\Helper\OptionsHelper::REINDEX_PROGRESS_METAKEY, 0);

            $countQuery = new \WP_Query([
                'post_type'      => 'product',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            ]);

            $localProductsCount = max(intval($countQuery->found_posts), 0.1);

//            $templateArgs['reindex_progress'] = $reindexProgress;
            $templateArgs['local_products_count'] = $localProductsCount;
//            $templateArgs['remote_index_products'] = $reindexProgress;
        } else {

            $remoteProductsCount = 0;

            $template = "page/login";
        }

        $templateArgs['secret_key'] = $secretKey;
        $templateArgs['public_key'] = $publicKey;
        $templateArgs['remote_products_count'] = $remoteProductsCount;

        echo fs_get_template($template, $templateArgs);
    });
});