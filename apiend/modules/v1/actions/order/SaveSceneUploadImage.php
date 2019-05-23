<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderGoodsScene;

/**
 * 保存用户为场景上传的图片
 *
 * @author Administrator
 */
class SaveSceneUploadImage extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id', 'scene_id', 'img_url'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id');
        $scene_id = $this->getSecretParam('scene_id');
        $img_url = $this->getSecretParam('img_url');

        $model = OrderGoodsScene::findOne(['order_id' => $order_id, 'scene_id' => $scene_id, 'is_del' => 0]);
        if (!$model) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => \Yii::t('app', 'Scene')]);
        }
        $model->user_img_url = $img_url;

        //重新保存
        if ($model->validate() && $model->save()) {
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

}
