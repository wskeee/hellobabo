<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\system\Region;
use common\models\User;
use common\models\UserAddress;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 获取用户地址列表
 *
 * @author Administrator
 */
class GetAddressList extends BaseAction
{

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $list = UserAddress::find()
                ->where([
                    'user_id' => $user,
                    'is_del' => 0,
                ])
                ->orderBy(['is_default' => SORT_DESC])
                ->all();
        //查出所有省市区
        $regionIds = array_merge(
                ArrayHelper::getColumn($list, 'province'), ArrayHelper::getColumn($list, 'city'), ArrayHelper::getColumn($list, 'district'));
        $regionNames = Region::getRegionList(['id' => $regionIds]);
        foreach ($list as &$address) {
            $address = $address->toArray();
            //返回对应省市区名称
            $address['province_name'] = $regionNames[$address['province']];
            $address['city_name'] = $regionNames[$address['city']];
            $address['district_name'] = $regionNames[$address['district']];
        }

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }

}
