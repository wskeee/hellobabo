<?php

namespace backend\modules\goods_admin\controllers;

use common\models\api\ApiResponse;
use common\utils\PoseUtil;
use Yii;
use common\models\goods\GoodsPagePose;
use common\models\goods\searchs\GoodsPagePoseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PagePoseController implements the CRUD actions for GoodsPagePose model.
 */
class PagePoseController extends Controller
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
     * Lists all GoodsPagePose models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsPagePoseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodsPagePose model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GoodsPagePose model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GoodsPagePose();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->page_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GoodsPagePose model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/goods_admin/scene-page/index', 'scene_id' => $model->page->scene_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GoodsPagePose model.
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
     * 获取图片的pose数据
     *
     * @param string $filepath
     */
    public function actionGetPose($filepath)
    {
        Yii::$app->response->format = 'json';
        // 获取百度人体数据
        $pose_data = PoseUtil::getPose($filepath);

        // 生成人体pose标准
        $pose_required = PoseUtil::maskPoseRequired($pose_data);

        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, [
            'pose_data' => $pose_data,
            'pose_required' => $pose_required,
        ]);
    }

    /**
     * Finds the GoodsPagePose model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsPagePose the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsPagePose::findOne($id)) == null) {
            $model = new GoodsPagePose(['page_id' => $id]);
            $model->save();
        }

        return $model;
    }
}
