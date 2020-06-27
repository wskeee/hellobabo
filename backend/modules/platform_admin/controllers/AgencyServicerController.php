<?php

namespace backend\modules\platform_admin\controllers;

use common\models\api\ApiResponse;
use Yii;
use common\models\platform\AgencyServicer;
use common\models\platform\searchs\AgencyServicerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgencyServicerController implements the CRUD actions for AgencyServicer model.
 */
class AgencyServicerController extends Controller
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
     * Lists all AgencyServicer models.
     * @return mixed
     */
    public function actionIndex($agency_id)
    {
        $searchModel = new AgencyServicerSearch(['agency_id' => $agency_id, 'is_del' => 0]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AgencyServicer model.
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
     * Creates a new AgencyServicer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AgencyServicer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AgencyServicer model.
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
     * Deletes an existing AgencyServicer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_del = 1;
        $model->save();

        return $this->redirect(['index', 'agency_id' => $model->agency_id]);
    }

    /**
     * 绑定
     * @param int $agency_id
     */
    public function actionBind($agency_id)
    {
        Yii::$app->response->format = 'json';

        $user_id = Yii::$app->request->getBodyParam('user_id');
        if (empty($user_id)) {
            Yii::$app->session->setFlash('error', 'user_id不能为空！');
            return new ApiResponse(ApiResponse::CODE_COMMON_MISS_PARAM, null, null, ['param' => 'user_id']);
        } else {
            $model = AgencyServicer::findOne(['agency_id' => $agency_id, 'user_id' => $user_id]);
            if ($model) {
                $model->is_del = 0;
            } else {
                $model = new AgencyServicer();
                $model->agency_id = $agency_id;
                $model->user_id = $user_id;
            }

            $model->save();
            return new ApiResponse(ApiResponse::CODE_COMMON_OK);
        }
    }

    /**
     * Finds the AgencyServicer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgencyServicer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgencyServicer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
