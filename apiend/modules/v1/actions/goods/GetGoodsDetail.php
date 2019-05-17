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

    public function run()
    {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        if (count($notfounds = $this->checkRequiredParams($this->getSecretParams(), ['id'])) != 0) {
            return new Response(Response::CODE_COMMON_MISS_PARAM, null, null, ['param' => implode(',', $notfounds)]);
        }
        $goods = Goods::findOne(['id' => $this->getSecretParam('id')]);
        if ($goods == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Goods')]);
        }
        
        return new Response(Response::CODE_COMMON_OK, null, $goods);
    }

}
