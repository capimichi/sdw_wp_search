<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 04/05/18
 * Time: 22:29
 */

namespace WoocommerceSearch\Helper;


class WsRemoteHelper
{
    /**
     * @param $publicKey
     * @return mixed
     */
    public static function getCountProducts($publicKey)
    {
        $indexedProductsCountUrl = \WoocommerceSearch\Endpoint\WsEndpoint::getCountProductsUrl($publicKey);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $indexedProductsCountUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);
        $indexedProductsCountContent = curl_exec($ch);
        curl_close($ch);
        $indexedProductsCountJson = json_decode($indexedProductsCountContent, true);
        $indexedProductsCount = $indexedProductsCountJson['result'];

        return $indexedProductsCount;
    }

    /**
     * @param $secretKey
     * @return mixed
     */
    public static function getPublicKey($secretKey)
    {
        $publicKey = null;
        $publicKeyUrl = \WoocommerceSearch\Endpoint\WsEndpoint::getPublicApikeyUrl($secretKey);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $publicKeyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);
        $publicKeyContent = curl_exec($ch);
        curl_close($ch);
        $publicKeyJson = json_decode($publicKeyContent, true);
        if ($publicKeyJson['status'] == 'OK') {
            $publicKey = $publicKeyJson['result'];
        }

        return $publicKey;
    }
}