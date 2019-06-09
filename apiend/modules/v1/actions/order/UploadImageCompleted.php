<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Order;
use common\models\order\OrderAction;
use common\models\order\OrderGoodsScene;
use common\models\order\WorkflowDesign;
use Yii;

/**
 * 上图完成
 */
class UploadImageCompleted extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_id'];

    public function run()
    {
        $order_id = $this->getSecretParam('order_id');

        $model = Order::findOne(['id' => $order_id]);
        if (!$model || $model->order_status != Order::ORDER_STATUS_WAIT_UPLOAD_PIC) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Order')]);
        }
        //查询是否所有需要上图的场景都已上传图片
        $result = OrderGoodsScene::find()->where(['order_id' => $order_id, 'is_required' => 1, 'is_del' => 0, 'user_img_url' => ""])->count();
        if ($result[0] > 0) {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, '发现需要上传图片的场景还没上传图片');
        }
        
        //上图完成，等待制作
        $model->order_status = Order::ORDER_STATUS_WAIT_MAKE;
        $model->upload_finish_at = time();
        
        //创建个设计任务
        $design = new WorkflowDesign([
                'order_id' => $model->id,
                'order_sn' => $model->order_sn,
            ]);

        //重新保存
        if ($model->validate() && $model->save() && $design->save()) {
            OrderAction::saveLog($model->id, '上图完成', '用户完成场景图片的上传');
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

}
