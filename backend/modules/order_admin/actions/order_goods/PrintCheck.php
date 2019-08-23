<?php

namespace backend\modules\order_admin\actions\order_goods;

use common\models\api\ApiResponse;
use common\models\order\Order;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsAction;
use common\models\order\WorkflowDelivery;
use common\models\order\WorkflowPrint;
use common\utils\I18NUitl;
use Exception;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * 打印审核
 *
 * @author Administrator
 */
class PrintCheck extends Action
{

    public function run($id)
    {
        Yii::$app->response->format = 'json';
        $reason = Yii::$app->request->post('reason', '');   // 原因
        $result = Yii::$app->request->post('result', 0);    // 结果 1 通过 0 不通过
        $model = $this->findModel($id);
        $work = WorkflowPrint::findOne(['order_goods_id' => $id]);
        
        // 不是待审核状态
        if ($model->status != OrderGoods::STATUS_PRINT_CHECK) {
            return new ApiResponse(ApiResponse::CODE_COMMON_NOT_FOUND, null, null, ['param' => I18NUitl::t('app', '{Order}{Goods}')]);
        }
        $tran = Yii::$app->db->beginTransaction();
        try {
            $model->status = $result == 1 ? OrderGoods::STATUS_FINISH : OrderGoods::STATUS_PRINT_CHECK_FAIL;
            $work->status = $result == 1 ? WorkflowPrint::STATUS_ENDED : WorkflowPrint::STATUS_CHECK_FAIL;
            if ($result) {
                $model->print_at = time();
                $work->end_at = time();
            }

            if ($model->save() && $work->save()) {
                $title = $result ? '成功' : '失败';
                OrderGoodsAction::saveLog([$model->id], "打印审核{$title}", $reason);

                if ($result) {
                    //更改订单为待发货
                    $model->order->order_status = Order::ORDER_STATUS_WAIT_DELIVER;
                    if (!$model->order->save()) {
                        throw new Exception(implode(',', $model->order->getErrorSummary(true)));
                    }
                    // 
                    $print = new WorkflowDelivery([
                        'order_id' => $model->order_id,
                        'order_sn' => $model->order_sn,
                        'user_id' => $model->order->created_by,
                        'consignee' => $model->order->consignee,
                        'zipcode' => $model->order->zipcode,
                        'phone' => $model->order->phone,
                        'country' => $model->order->country,
                        'province' => $model->order->province,
                        'city' => $model->order->city,
                        'district' => $model->order->district,
                        'town' => $model->order->town,
                        'address' => $model->order->address,
                        'user_note' => $model->order->user_note,
                        'status' => WorkflowDelivery::STATUS_WAIT_START,
                    ]);
                    $print->setScenario(WorkflowDelivery::SCENARIO_CREATE);
                    if (!$print->save()) {
                        throw new Exception(implode(',', $print->getErrorSummary(true)));
                    }
                }
            } else {
                throw new Exception(implode(',', $model->getErrorSummary(true)));
            }
            $tran->commit();
        } catch (Exception $ex) {
            $tran->rollBack();
            Yii::debug($ex->getMessage());
            Yii::$app->session->setFlash('warn', $ex->getMessage());
            return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, $ex->getMessage(), $ex);
        }
        Yii::$app->session->setFlash('success', '操作成功!');
        return new ApiResponse(ApiResponse::CODE_COMMON_OK);
    }

    protected function findModel($id)
    {
        if (($model = OrderGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
