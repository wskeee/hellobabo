<?php

namespace backend\modules\order_admin\controllers;

use apiend\models\Response;
use common\components\aliyuncs\Aliyun;
use common\models\order\Order;
use common\models\order\OrderAction;
use common\models\order\OrderGoodsScene;
use common\models\order\searchs\WorkflowPrintSearch;
use common\models\order\WorkflowDelivery;
use common\models\order\WorkflowPrint;
use OSS\OssClient;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use ZipArchive;

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
            if($model->save()){
                //记录订单日志 
                OrderAction::saveLog([$model->order_id], '开始打印', '绘本打印已开始！');
            }
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
                
                //更改订单为待发货
                $model->order->print_at = time();
                $model->order->order_status = Order::ORDER_STATUS_WAIT_DELIVER;
                $model->order->save();

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
                    'status' => WorkflowPrint::STATUS_WAIT_START,
                ]);
                $print->save();
                
                
                //记录订单日志 
                OrderAction::saveLog([$model->order_id], '结束打印', '绘本打印已完成！');
                
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
        $ids = Yii::$app->request->post('selection', []);
        $models = OrderGoodsScene::findAll(['id' => $ids]); //获取需要下载的场景图片
        $zip = new ZipArchive();
        $zipName = uniqid();
        $zipPath = "upload/download/$zipName.zip";

        /* @var $model OrderGoodsScene */
        foreach ($models as $model) {
            $filename = "upload/download/" . pathinfo($model->finish_url, PATHINFO_BASENAME);
            $ext = pathinfo($model->source_url, PATHINFO_EXTENSION);
            //下载oss文件
            $object = Aliyun::getObjectKeyFormUrl($model->user_img_url);
            Aliyun::getOss()->getOutputObject($object, [OssClient::OSS_FILE_DOWNLOAD => $filename]);
            //var_dump($zipPath,$zip->open($zipPath, ZipArchive::OVERWRITE));exit;
            //添加文件到zip
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($filename, "{$model->name}.$ext");
                $zip->close();
            } else {
                Yii::$app->response->format = 'json';
                return new Response(Response::CODE_COMMON_UNKNOWN, '打包文件出错！');
            }
        }
        //发送文件
        Yii::$app->response->sendFile($zipPath);
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
