<?php

namespace backend\modules\order_admin\actions\order_goods;

use common\models\api\ApiResponse;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsAction;
use common\models\order\WorkflowDesign;
use common\utils\I18NUitl;
use Exception;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * 上图审核
 *
 * @author Administrator
 */
class UpimgCheck extends Action
{

    public function run($id)
    {
        Yii::$app->response->format = 'json';
        $reason = Yii::$app->request->post('reason', '');   // 原因
        $result = Yii::$app->request->post('result', 0);    // 结果 1 通过 0 不通过
        $model = $this->findModel($id);
        
        // 不是待审核状态
        if ($model->status != OrderGoods::STATUS_UPLOAD_PIC_CHECK) {
            return new ApiResponse(ApiResponse::CODE_COMMON_NOT_FOUND, null, null, ['param' => I18NUitl::t('app', '{Order}{Goods}')]);
        }
        $tran = Yii::$app->db->beginTransaction();
        try {
            $model->status = $result == 1 ? OrderGoods::STATUS_WAIT_DESIGN : OrderGoods::STATUS_UPLOAD_PIC_CHECK_FAIL;
            if ($result) {
                $model->upload_finish_at = time();
            }
            
            if ($model->save()) {
                $title = $result ? '成功' : '失败';
                OrderGoodsAction::saveLog([$model->id], "上图审核{$title}", $reason);

                if ($result) {
                    // 创建设计任务
                    $work = new WorkflowDesign([
                        'order_id' => $model->order_id,
                        'order_sn' => $model->order_sn,
                        'order_goods_id' => $model->id,
                        'created_at' => time(),
                        'updated_at' => time(),
                    ]);
                    if (!$work->save()) {
                        throw new Exception(implode(',', $work->getErrorSummary(true)));
                    }
                }
            } else {
                throw new Exception(implode(',', $model->getErrorSummary(true)));
            }
            $tran->commit();
        } catch (Exception $ex) {
            $tran->rollBack();
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
