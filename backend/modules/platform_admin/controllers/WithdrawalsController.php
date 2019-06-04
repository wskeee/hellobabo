<?php

namespace backend\modules\platform_admin\controllers;

use common\models\api\ApiResponse;
use common\models\platform\searchs\WithdrawalsSearch;
use common\models\platform\Withdrawals;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * WithdrawalsController implements the CRUD actions for Withdrawals model.
 */
class WithdrawalsController extends Controller
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
     * Lists all Withdrawals models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WithdrawalsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Withdrawals model.
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
     * Creates a new Withdrawals model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Withdrawals();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Withdrawals model.
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
     * Deletes an existing Withdrawals model.
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
     * 审核
     * @return \backend\modules\platform_admin\controllers\ApiResponse
     */
    public function actionCheck(){
        Yii::$app->response->format = 'json';
        $ids = Yii::$app->request->post('ids', []);
        $des = Yii::$app->request->post('des', '');
        $result = Yii::$app->request->post('result', 0);
        try {
            Withdrawals::updateAll([
                'status' => $result == 0 ? Withdrawals::STATUS_CHECK_FAILED : Withdrawals::STATUS_CHECK_SUCCESS,
                'check_feedback' => $des,
                'check_user_id' => Yii::$app->user->id,
                'check_at' => time()], ['id' => $ids, 'status' => Withdrawals::STATUS_APPLYING]);
        } catch (\Exception $ex) {
            return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, $ex->getMessage(), $ex);
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_OK);
    }

    /**
     * Finds the Withdrawals model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Withdrawals the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Withdrawals::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
