<?php
/**
 * Plugin Name: FastSearch
 * */

define("FS_ROOT_FILE", __FILE__);
define("FS_ROOT_DIR", __DIR__);
define("FS_TEMPLATES_DIR", FS_ROOT_DIR . DIRECTORY_SEPARATOR . "templates");
define("FS_ASSETS_DIR", FS_ROOT_DIR . DIRECTORY_SEPARATOR . "assets");
define("FS_ASSETS_URL", plugin_dir_url(FS_ROOT_FILE) . "assets");

define("FS_INDEX_PAGE_SLUG", "woocommerce-search");

require_once FS_ROOT_DIR . "/src/autoload.php";
require_once FS_ROOT_DIR . "/inc/template-functions.php";
require_once FS_ROOT_DIR . "/inc/assets-functions.php";
require_once FS_ROOT_DIR . "/inc/menu-functions.php";
require_once FS_ROOT_DIR . "/inc/search-functions.php";
require_once FS_ROOT_DIR . "/inc/ajax-functions.php";
require_once FS_ROOT_DIR . "/inc/adminbar-functions.php";

add_action('init', function () {

    $action = isset($_GET['ws-action']) ? $_GET['ws-action'] : null;

    switch ($action) {

        case "reindex":

            $perRequest = 200;
            $page = 1;
            $key = get_option(\FastSearch\Helper\OptionsHelper::SECRET_KEY_METAKEY, '');

            do {

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
                        'image'         => get_the_post_thumbnail_url($product->ID, 'thumbnail'),
                        'regular_price' => get_post_meta($product->ID, '_regular_price', true),
                        'sale_price'    => get_post_meta($product->ID, '_sale_price', true),
                    ];

                    $parsedProducts[] = $parsedProduct;
                }

                $json = json_encode($parsedProducts);

                $url = \FastSearch\Endpoint\WsEndpoint::getAddProductsUrl();

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                    'data' => $json,
                    'key'  => $key,
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($ch);
                curl_close($ch);

                $allSent = ($page * $perRequest) >= $query->found_posts;

                $page++;

            } while (!$allSent);

            break;
    }


});