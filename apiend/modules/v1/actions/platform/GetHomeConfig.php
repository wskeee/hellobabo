<?php


namespace apiend\modules\v1\actions\platform;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\system\Config;

/**
 * 获取小程序首页配置
 * Class GetWxHomeConfig
 * @package apiend\modules\v1\actions\platform
 */
class GetHomeConfig extends BaseAction
{
    public function run()
    {
        $bg_data = Config::getValue('wx_min_home_bg');
        $data = [
            'config' => [
                'bgs' => array_filter(explode(',', $bg_data))
            ]
        ];
        return new Response(Response::CODE_COMMON_OK, null, $data);
    }
}