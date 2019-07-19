<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderGoodsScene;
use Yii;

/**
 * 清除用户上传到场景的图片
 *
 * @author Administrator
 */
class ClearSceneUserImage extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_scene_id'];

    public function run()
    {
        $order_goods_scene_id = $this->getSecretParam('order_goods_scene_id');
        $order_goods_scene = OrderGoodsScene::findOne(['id' => $order_goods_scene_id, 'is_del' => 0]);
        if ($order_goods_scene == null) {
            return new Response(Response::CODE_COMMON_UNKNOWN, null, null, ['param' => Yii::t('app', 'Scene')]);
        }
        $order_goods_scene->user_img_url = "";
        if ($order_goods_scene->save()) {
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $order_goods_scene->getErrorSummary(true));
        }
    }

}
