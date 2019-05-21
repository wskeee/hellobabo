<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsMaterialValue;
use common\models\order\OrderGoodsMaterial;

/**
 * 保存用户选定的素材
 *
 * @author Administrator
 */
class SaveGoodsMaterial extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id', 'material_id', 'material_value_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id');
        $material_id = $this->getSecretParam('material_id');
        $value_id = $this->getSecretParam('material_value_id');

        $m_value = GoodsMaterialValue::findOne(['id' => $value_id]);
        $model = OrderGoodsMaterial::findOne(['order_id' => $order_id, 'value_id' => $value_id]);
        if ($model == null) {
            $model = new OrderGoodsMaterial([
                'order_id' => $order_id,
                'material_id' => $material_id,
                'value_id' => $m_value->id,
                'value_name' => $m_value->name,
                'value_effect_url' => $m_value->effect_url,
                'value_source_url' => $m_value->source_url,
                'value_des' => $m_value->des,
            ]);
        } else {
            $model->is_del = 1;
        }

        //清除上次记录
        OrderGoodsMaterial::updateAll(['is_del' => 1], ['order_id' => $order_id, 'material_id' => $material_id]);
        //重新保存
        if ($model->validate() && $model->save()) {
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

}
