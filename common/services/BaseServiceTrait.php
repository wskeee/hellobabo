<?php


namespace common\services;


Trait BaseServiceTrait
{
    /**
     * 返回成功
     * @param array $data
     * @param string $msg
     * @return array
     */
    public static function success($data = [], $msg = 'OK')
    {
        return ['data' => $data, 'msg' => $msg, 'result' => 1];
    }

    /**
     * 返回失败
     * @param array $data
     * @param string $msg
     * @param int $resutl
     */
    public static function fail($data = [], $msg = 'ERROR', $resutl = 0)
    {
        return ['data' => $data, 'msg' => $msg, 'result' => $resutl];
    }
}