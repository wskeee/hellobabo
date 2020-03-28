<?php

namespace apiend\modules\v1\actions\goods;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsMaterialValue;
use yii\helpers\ArrayHelper;

/**
 * 获取商品素材列表
 *
 * @author Administrator
 */
class GetGoodsMaterialValueList extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['goods_id'];

    public function run()
    {
        $goods_id = $this->getSecretParam('goods_id');
        $list = GoodsMaterialValue::getGoodsMaterialValue($goods_id);

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }

}
