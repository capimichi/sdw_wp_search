<?php

namespace WoocommerceSearch\Endpoint;

/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 03/05/18
 * Time: 19:42
 */
class WsEndpoint
{

    const WEBSITE_URL = "http://www.aforismando.com/woocommerce-search";
    const ADD_PRODUCT_URL = "/products/adds";
    const BULK_ADD_PRODUCTS_URL = "/products/adds/bulks";
    const LIST_PRODUCTS_URL = "/products?key={key}&query={query}";
    const LIST_PRODUCTS_NOQUERY_URL = "/products?key={key}";
    const COUNT_PRODUCTS_URL = "/products/count?key={key}";
    const LIST_APIKEYS_URL = "/apikeys?username={username}&password={password}";
    const PUBLIC_APIKEY_URL = "/apikey/public?secret={secret}";

    /**
     * @return string
     */
    public static function getBulkAddProductsUrl()
    {
        return self::WEBSITE_URL . self::BULK_ADD_PRODUCTS_URL;
    }

    /**
     * @return string
     */
    public static function getAddProductUrl()
    {
        return self::WEBSITE_URL . self::ADD_PRODUCT_URL;
    }

    /**
     * @param $query
     * @param $key
     * @return string
     */
    public static function getListProductsUrl($query, $key)
    {
        $url = self::WEBSITE_URL . self::LIST_PRODUCTS_URL;

        $url = str_replace("{query}", $query, $url);
        $url = str_replace("{key}", $key, $url);

        return $url;
    }

    /**
     * @param $key
     * @return string
     */
    public static function getListProductsNoQueryUrl($key)
    {
        $url = self::WEBSITE_URL . self::LIST_PRODUCTS_NOQUERY_URL;

        $url = str_replace("{key}", $key, $url);

        return $url;
    }

    /**
     * @param $key
     * @return string
     */
    public static function getCountProductsUrl($key)
    {
        $url = self::WEBSITE_URL . self::COUNT_PRODUCTS_URL;

        $url = str_replace("{key}", $key, $url);

        return $url;
    }

    /**
     * @param $username
     * @param $password
     * @return mixed|string
     */
    public static function getListApikeysUrl($username, $password)
    {
        $url = self::WEBSITE_URL . self::LIST_APIKEYS_URL;

        $url = str_replace("{username}", $username, $url);
        $url = str_replace("{password}", $password, $url);

        return $url;
    }

    /**
     * @param $secret
     * @return mixed|string
     */
    public static function getPublicApikeyUrl($secret)
    {
        $url = self::WEBSITE_URL . self::PUBLIC_APIKEY_URL;

        $url = str_replace("{secret}", $secret, $url);

        return $url;
    }
}