<?php

namespace common\utils;

use common\components\redis\RedisService;
use Yii;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

/**
 * 查询快递信息
 *
 * @author Administrator
 */
class ExpressUtil
{

    const REDIS_KEY_EXPRESS = 'order:express:query:';

    /**
     * 查询快递单
     * 
     * @param string $sn            快递单号
     * @param string $ship_code     快递公司代号
     * @param bool $force           是否强制查询，默认1小时内只查询一次
     */
    public static function query($sn, $ship_code = '', $force = false)
    {
        $r = RedisService::getRedis();
        $key = self::REDIS_KEY_EXPRESS . $sn;

        if ($force || !$r->get($key)) {
            $result = self::__query($sn, $ship_code);
            $json_result = json_decode($result, true);
            if ($json_result['ret'] == 200) {
                $r->setex($key, 3600, $result);
            }
            return $json_result;
        }
        return json_decode($r->get($key), true);
    }

    /**
     * 
     * @param string $sn            快递单号
     * @param string $ship_code     快递公司代号
     * @return type
     */
    private static function __query($sn, $ship_code)
    {
        $host = "https://api09.aliyun.venuscn.com";
        $path = "/express/trace/query";
        $method = "GET";
        $appcode = Yii::$app->params['express']['AppCode'];
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "comid=$ship_code&number=$sn";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        return curl_exec($curl);
    }

}
