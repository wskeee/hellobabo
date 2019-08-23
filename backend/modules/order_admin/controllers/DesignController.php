<?php

namespace backend\modules\order_admin\controllers;

use common\components\aliyuncs\Aliyun;
use common\models\api\ApiResponse;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsAction;
use common\models\order\OrderGoodsScenePage;
use common\models\order\searchs\WorkflowDesignSearch;
use common\models\order\WorkflowDesign;
use OSS\OssClient;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use ZipArchive;

/**
 * DesignController implements the CRUD actions for WorkflowDesign model.
 */
class DesignController extends Controller
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
     * Lists all WorkflowDesign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkflowDesignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkflowDesign model.
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
     * Creates a new WorkflowDesign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorkflowDesign();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing WorkflowDesign model.
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
     * Deletes an existing WorkflowDesign model.
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
        if ($model->status == WorkflowDesign::STATUS_WAIT_START) {
            // 修改当前任务为运行中状态
            $model->worker_id = \Yii::$app->user->id;
            $model->start_at = time();
            $model->status = WorkflowDesign::STATUS_RUNGING;

            // 修改当前绘本为设计中状态
            $model->orderGoods->status = OrderGoods::STATUS_DESIGNING;

            if ($model->save() && $model->orderGoods->save()) {
                //记录订单日志 
                OrderGoodsAction::saveLog([$model->order_goods_id], '开始设计', '绘本设计已开始！');
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
        if ($model->status == WorkflowDesign::STATUS_RUNGING || $model->status == WorkflowDesign::STATUS_CHECK_FAIL) {
            $tran = Yii::$app->db->beginTransaction();
            try {
                // 修改当前任务为审核状态
                $model->status = WorkflowDesign::STATUS_CHECK;
                // 修改当前绘本为设计审核状态
                $model->orderGoods->status = OrderGoods::STATUS_DESIGN_CHECK;

                if ($model->save() && $model->orderGoods->save())
                    $tran->commit();
            } catch (\Exception $ex) {
                $tran->rollBack();
                Yii::$app->session->addFlash('danger', $ex->getMessage());
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * 保存成品 
     * 
     * @param type $pid
     * @param type $path
     */
    public function actionSaveProduct()
    {
        Yii::$app->response->format = 'json';
        $pid = Yii::$app->request->post('pid');
        $skin_url = Yii::$app->request->post('skin_url');
        $adobe_id = Yii::$app->request->post('adobe_id');

        if ($pid == '') {
            return new ApiResponse(ApiResponse::CODE_COMMON_MISS_PARAM, null, null, ['param' => 'pid']);
        }

        $page = OrderGoodsScenePage::findOne(['id' => $pid]);
        if (!$page) {
            return new ApiResponse(ApiResponse::CODE_COMMON_NOT_FOUND, null, null, ['param' => 'OrderGoodsScenePage']);
        }

        if ($skin_url == '') {
            $page->finish_id = '';
            $page->finish_url = '';
            if ($page->save()) {
                return new ApiResponse(ApiResponse::CODE_COMMON_OK);
            }
        } else {
            $page->finish_id = $adobe_id;
            $page->finish_url = $skin_url;
            if ($page->save()) {
                return new ApiResponse(ApiResponse::CODE_COMMON_OK);
            }
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, implode(',', $page->getErrorSummary(true)));
    }

    /**
     * 下载用户上传的图片
     */
    public function actionBatchDownloadUserImg()
    {
        $ids = Yii::$app->request->post('selection', []);
        $models = OrderGoodsScenePage::find()->where(['id' => $ids])->all(); //获取需要下载的场景图片
        $zip = new ZipArchive();
        $zipName = uniqid();
        $zipPath = "upload/download/$zipName.zip";

        /* @var $model OrderGoodsScenePage */
        foreach ($models as $index => $model) {
            if ($model->user_img_url == '')
                continue;
            $filename = "upload/download/" . pathinfo($model->user_img_url, PATHINFO_BASENAME);
            $ext = pathinfo($model->user_img_url, PATHINFO_EXTENSION);
            //下载oss文件
            $object = Aliyun::getObjectKeyFormUrl($model->user_img_url);
            Aliyun::getOss()->getOutputObject($object, [OssClient::OSS_FILE_DOWNLOAD => $filename]);
            //添加文件到zip
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($filename, "{$index}.$ext");
                $zip->close();
            } else {
                Yii::$app->response->format = 'json';
                return new ApiResponse(ApiResponse::CODE_COMMON_UNKNOWN, '打包文件出错！');
            }
        }
        //发送文件
        Yii::$app->response->sendFile($zipPath);
    }

    /**
     * Finds the WorkflowDesign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkflowDesign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkflowDesign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
