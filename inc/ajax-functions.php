<?php

add_action('wp_ajax_fs_reindex_products', 'fs_reindex_products');
add_action('wp_ajax_nopriv_fs_reindex_products', 'fs_reindex_products');

function fs_reindex_products()
{

    /*
    $perRequest = min(isset($_GET['per_request']) ? $_GET['per_request'] : 20, 100);
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $key = get_option(\WoocommerceSearch\Helper\OptionsHelper::SECRET_KEY_METAKEY, '');

    $query = new \WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => $perRequest,
        'post_status'    => 'publish',
        'paged'          => $page,
    ]);

    $products = $query->get_posts();

    $parsedProducts = [];

    foreach ($products as $product) {

        $parsedProduct = [
            'ID'            => $product->ID,
            'title'         => $product->post_title,
            'url'           => get_the_permalink($product->ID),
            'image'         => get_the_post_thumbnail_url($product->ID, 'thumbnail'),
            'regular_price' => get_post_meta($product->ID, '_regular_price', true),
            'sale_price'    => get_post_meta($product->ID, '_sale_price', true),
        ];

        $parsedProducts[] = $parsedProduct;
    }

    $json = json_encode($parsedProducts);

    $url = \WoocommerceSearch\Endpoint\WsEndpoint::getAddProductsUrl();

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'data' => $json,
        'key'  => $key,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    curl_close($ch);

    $progress = $page * $perRequest;
    $done = $progress >= intval($query->found_posts);

    wp_send_json([
        'status'   => 'OK',
        'progress' => $page * $perRequest,
        'max'      => intval($query->found_posts),
        'is_done'  => $done,
        'json'     => $json,
    ]);

    */

    die();
}


add_action('wp_ajax_fs_reindex_product', 'fs_reindex_product');
add_action('wp_ajax_nopriv_fs_reindex_product', 'fs_reindex_product');

function fs_reindex_product()
{

    $responseData = [
        'status' => 'OK',
    ];

    try {

        $syncProductsProgressKey = \WoocommerceSearch\Helper\OptionsHelper::SYNC_PRODUCTS_PROGRESS_METAKEY;
        $progress = isset($_GET['progress']) ? $_GET['progress'] : get_option($syncProductsProgressKey, 0);

        $query = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'offset'         => $progress,
        ]);

        $productsCount = intval($query->found_posts);

        $products = $query->get_posts();

        $responseData['found_post'] = count($products) ? true : false;

        if (count($products)) {
            $productId = $products[0]->ID;

            $productImage = get_the_post_thumbnail_url($productId, 'thumbnail');
            $productUrl = get_the_permalink($productId);
            $key = get_option(\WoocommerceSearch\Helper\OptionsHelper::SECRET_KEY_METAKEY, '');
            $product = new \WC_Product($productId);

            $url = \WoocommerceSearch\Endpoint\WsEndpoint::getAddProductUrl();

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'key'       => $key,
                'id'        => $productId,
                'title'     => $product->get_name(),
                'regprice'  => $product->get_regular_price(),
                'saleprice' => $product->get_sale_price(),
                'image'     => $productImage,
                'url'       => $productUrl,
                'cache'     => false,
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            $jsonResponse = json_decode($response, true);

            if ($jsonResponse['status'] == "OK") {

                $progress++;

                $responseData['progress'] = $progress;

                $responseData['count'] = $productsCount;

                $responseData['is_done'] = $progress >= $productsCount;

                update_option($syncProductsProgressKey, $progress);
            } else {
                $responseData = $jsonResponse;
            }
        } else {
            $responseData['status'] = "POST NOT FOUND";
        }

    } catch (\Exception $exception) {
        $responseData['status'] = strtoupper($exception->getMessage());
    }

    wp_send_json($responseData);

    die();
}


add_action('wp_ajax_fs_reindex_status_products', 'fs_reindex_status_products');

function fs_reindex_status_products()
{
    $responseData = [
        'status' => 'OK',
    ];

    if (!empty(get_option(\WoocommerceSearch\Helper\OptionsHelper::SECRET_KEY_METAKEY))) {

        try {
            $query = new WP_Query([
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'orderby'        => 'ID',
                'order'          => 'ASC',
            ]);

            $syncProductsProgressKey = \WoocommerceSearch\Helper\OptionsHelper::SYNC_PRODUCTS_PROGRESS_METAKEY;
            $syncProductsProgress = get_option($syncProductsProgressKey, 0);

            $productsCount = intval($query->found_posts);

            $responseData['progress'] = $syncProductsProgress;
            $responseData['count'] = $productsCount;
            $responseData['is_done'] = $syncProductsProgress >= $productsCount;

        } catch (\Exception $exception) {
            $responseData['status'] = strtoupper($exception->getMessage());
        }
    } else {
        $responseData['status'] = 'LOGIN BEFORE SYNC';
    }

    wp_send_json($responseData);

    die();
}


add_action('wp_ajax_fs_reindex_restart', 'fs_reindex_restart');

function fs_reindex_restart()
{
    $responseData = [
        'status' => 'OK',
    ];

    update_option(\WoocommerceSearch\Helper\OptionsHelper::SYNC_PRODUCTS_PROGRESS_METAKEY, 0);

    wp_send_json($responseData);

    die();
}

add_action('wp_ajax_fs_logout', 'fs_logout');

function fs_logout()
{
    update_option(\WoocommerceSearch\Helper\OptionsHelper::SECRET_KEY_METAKEY, "");
    update_option(\WoocommerceSearch\Helper\OptionsHelper::PUBLIC_KEY_METAKEY, "");
    die();
}