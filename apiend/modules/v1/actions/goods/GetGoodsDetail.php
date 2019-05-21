<?php

namespace apiend\modules\v1\actions\goods;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use Yii;

/**
 * 获取绘本详情
 *
 * @author Administrator
 */
class GetGoodsDetail extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['id'];

    public function run()
    {
        $goods = Goods::findOne(['id' => $this->getSecretParam('id')]);
        if ($goods == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Goods')]);
        }

        return new Response(Response::CODE_COMMON_OK, null, $goods);
    }

}
