<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\order\Groupon;
use common\models\order\GrouponRecord;
use Yii;

/**
 * 加入团购准备
 *
 * @author Administrator
 */
class JoinGrouponReady extends BaseAction
{

    protected $requiredParams = ['groupon_id'];

    public function run()
    {
        $groupon_id = $this->getSecretParam('groupon_id');
        $groupon = Groupon::findOne(['id' => $groupon_id]);
        $goods = Goods::findOne(['id' => $groupon->goods_id]);

        if ($groupon == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Groupon')]);
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'groupon' => $groupon,
            'groupon_records' => GrouponRecord::getGrouponRecordDetail($groupon_id),
            'goods' => $goods,
        ]);
    }

}
