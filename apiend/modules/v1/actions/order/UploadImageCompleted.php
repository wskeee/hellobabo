<?php
namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsAction;
use common\models\order\OrderGoodsScenePage;
use common\utils\I18NUitl;

/**
 * 上图完成
 */
class UploadImageCompleted extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['order_goods_id'];

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');

        $model = OrderGoods::findOne(['id' => $order_goods_id]);
        if (!$model || ($model->status != OrderGoods::STATUS_UPLOAD_PIC && $model->status != OrderGoods::STATUS_UPLOAD_PIC_CHECK_FAIL)) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => I18NUitl::t('app', '{Order}{Goods}')]);
        }
        //查询是否所有需要上图的场景都已上传图片
        $result = OrderGoodsScenePage::find()->where(['order_goods_id' => $order_goods_id, 'is_required' => 1, 'is_del' => 0, 'user_img_url' => ""])->count();
        if ($result[0] > 0) {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, '请先完成所有相片的上传！');
        }
        
        //上图完成，等待制作
        $model->status = OrderGoods::STATUS_UPLOAD_PIC_CHECK;
        $model->upload_finish_at = time();
        
        //重新保存
        if ($model->validate() && $model->save()) {
            OrderGoodsAction::saveLog($model->id, '上图完成', '用户已完成所有相片的上传');
            return new Response(Response::CODE_COMMON_OK);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

}
