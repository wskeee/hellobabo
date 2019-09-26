<?php

namespace backend\modules\order_admin\controllers;

use common\models\api\ApiResponse;
use common\models\order\Order;
use common\models\order\OrderAction;
use common\models\order\searchs\WorkflowDeliverySearch;
use common\models\order\WorkflowDelivery;
use common\models\platform\Express;
use common\utils\ExpressUtil;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DeliveryController implements the CRUD actions for WorkflowDelivery model.
 */
class DeliveryController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all WorkflowDelivery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkflowDeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkflowDelivery model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WorkflowDelivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorkflowDelivery();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing WorkflowDelivery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WorkflowDelivery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 发货
     */
    public function actionShipping($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->status == WorkflowDelivery::STATUS_WAIT_START) {
            $tran = \Yii::$app->db->beginTransaction();
            $model->start_at = time();
            $model->end_at = time();
            $model->status = WorkflowDelivery::STATUS_ENDED;
            $model->worker_id = \Yii::$app->user->id;
            $order = $model->order;
            $order->shipping_at = time();
            $order->order_status = Order::ORDER_STATUS_WAIT_CONFIRM;
            if ($model->save() && $order->save()) {
                //记录订单日志 
                OrderAction::saveLog([$model->order_id], '发货', '订单已发货！');

                $tran->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $tran->rollBack();
                $error = implode(',', $model->getErrorSummary(true)) . implode(',', $order->getErrorSummary(true));

                \Yii::$app->session->addFlash('danger', '保存失败！' . $error);
            }
        }

        return $this->render('shipping', [
                    'model' => $model,
        ]);
    }

    /**
     * 查询快递信息
     * 
     * @param string $sn 快递单号
     */
    public function actionQueryExpress($sn)
    {
        \Yii::$app->response->format = 'json';
        return new ApiResponse(ApiResponse::CODE_COMMON_OK,null, ExpressUtil::query($sn, null, true));
    }

    /**
     * Finds the WorkflowDelivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkflowDelivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkflowDelivery::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
