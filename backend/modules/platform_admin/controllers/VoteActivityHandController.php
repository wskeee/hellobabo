<?php

namespace backend\modules\platform_admin\controllers;

use apiend\components\voterank\VoteService;
use common\models\activity\searchs\VoteActivityHandSearch;
use common\models\activity\VoteActivityHand;
use common\models\api\ApiResponse;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * VoteActivityHandController implements the CRUD actions for VoteActivityHand model.
 */
class VoteActivityHandController extends Controller
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
     * Lists all VoteActivityHand models.
     * @return mixed
     */
    public function actionIndex($activity_id = null)
    {
        $searchModel = new VoteActivityHandSearch(['activity_id' => $activity_id, 'check_status' => VoteActivityHand::CHECK_STATUS_WAITING]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VoteActivityHand model.
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
     * Creates a new VoteActivityHand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VoteActivityHand();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing VoteActivityHand model.
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
     * Deletes an existing VoteActivityHand model.
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
     */
    public function actionCheck()
    {
        Yii::$app->response->format = 'json';

        $ids = Yii::$app->request->post('ids');
        $result = Yii::$app->request->post('result', VoteActivityHand::CHECK_STATUS_FAILED);
        $feedback = Yii::$app->request->post('feedback', '');
        
        if($result == VoteActivityHand::CHECK_STATUC_SUCCESS){
            $needHands = VoteActivityHand::find()->select(['id','activity_id'])->where(['id' => $ids,'check_status' => VoteActivityHand::CHECK_STATUS_WAITING])->asArray()->all();
            foreach($needHands as $hand){
                VoteService::initVote($hand['activity_id'], $hand['id']);
            }
        }

        VoteActivityHand::updateAll([
            'check_status' => $result,
            'check_feedback' => $feedback,
            'updated_at' => time()], [
            'id' => $ids,
            'check_status' => VoteActivityHand::CHECK_STATUS_WAITING
        ]);

        return new ApiResponse(ApiResponse::CODE_COMMON_OK);
    }

    /**
     * Finds the VoteActivityHand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VoteActivityHand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VoteActivityHand::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
