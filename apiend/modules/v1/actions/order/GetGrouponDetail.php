<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Groupon;
use Yii;

/**
 * 获取
 *
 * @author Administrator
 */
class GetGrouponDetail extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['groupon_id'];

    public function run()
    {
        $groupon_id = $this->getSecretParam('groupon_id');
        $groupon = Groupon::find()->where(['id' => $groupon_id])->andWhere(['<>', 'status', Groupon::STATUS_INVALID])->one();
        if ($groupon == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Groupon')]);
        }
        $groupon->goods_params = json_decode($groupon->goods_params);
        return new Response(Response::CODE_COMMON_OK, null, $groupon);
    }

}
