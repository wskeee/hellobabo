<?php

namespace backend\modules\order_admin\controllers;

use common\models\order\searchs\WorkflowPrintSearch;
use common\models\order\WorkflowDelivery;
use common\models\order\WorkflowPrint;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PrintController implements the CRUD actions for WorkflowPrint model.
 */
class PrintController extends Controller
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
     * Lists all WorkflowPrint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkflowPrintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkflowPrint model.
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
     * Creates a new WorkflowPrint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorkflowPrint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WorkflowPrint model.
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
     * Deletes an existing WorkflowPrint model.
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
     * 开始任务
     * @param int $id 设计ID
     */
    public function actionStart($id)
    {
        $model = $this->findModel($id);
        if ($model->status == WorkflowPrint::STATUS_WAIT_START) {
            $model->worker_id = \Yii::$app->user->id;
            $model->start_at = time();
            $model->status = WorkflowPrint::STATUS_RUNGING;
            $model->save();
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * 结束任务
     */
    public function actionEnd($id)
    {
        $model = $this->findModel($id);
        if ($model->status == WorkflowPrint::STATUS_RUNGING) {
            $tran = Yii::$app->db->beginTransaction();
            try {
                $model->end_at = time();
                $model->status = WorkflowPrint::STATUS_ENDED;
                $model->save();

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
                    'note' => $model->order->note,
                    'status' => WorkflowPrint::STATUS_WAIT_START,
                ]);
                $print->save();
                $tran->commit();
            } catch (\Exception $ex) {
                $tran->rollBack();
                Yii::$app->session->addFlash('danger', $ex->getMessage());
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * 批量下载成品图片
     */
    public function actionBatchDownloadFinishedImg()
    {
        
    }

    /**
     * Finds the WorkflowPrint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkflowPrint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkflowPrint::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
