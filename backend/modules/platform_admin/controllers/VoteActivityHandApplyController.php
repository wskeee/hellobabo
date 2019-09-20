<?php

namespace backend\modules\platform_admin\controllers;

use apiend\components\voterank\VoteService;
use common\models\activity\searchs\VoteActivityHandApplySearch;
use common\models\activity\VoteActivityHand;
use common\models\activity\VoteActivityHandApply;
use common\models\api\ApiResponse;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * VoteActivityHandApplyController implements the CRUD actions for VoteActivityHandApply model.
 */
class VoteActivityHandApplyController extends Controller
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
     * Lists all VoteActivityHandApply models.
     * @return mixed
     */
    public function actionIndex($activity_id = null)
    {
        $searchModel = new VoteActivityHandApplySearch(['activity_id' => $activity_id, 'check_status' => VoteActivityHandApply::CHECK_STATUS_WAITING]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 审核
     */
    public function actionCheck()
    {
        Yii::$app->response->format = 'json';

        $ids = Yii::$app->request->post('ids');
        $result = Yii::$app->request->post('result', VoteActivityHandApply::CHECK_STATUS_FAILED);
        $feedback = Yii::$app->request->post('feedback', '');

        if ($result == VoteActivityHandApply::CHECK_STATUC_SUCCESS) {
            $needHands = VoteActivityHandApply::find()->where(['id' => $ids, 'check_status' => VoteActivityHandApply::CHECK_STATUS_WAITING])->all();
            foreach ($needHands as $apply_hand) {
                $hand = VoteActivityHand::findOne(['activity_id' => $apply_hand->activity_id, 'target_user_id' => $apply_hand->target_user_id]);
                if ($hand == null) {
                    $hand = new VoteActivityHand([
                        'activity_id' => $apply_hand->activity_id,
                        'target_user_id' => $apply_hand->target_user_id,
                        'num' => VoteService::getNum($apply_hand->activity_id)
                    ]);
                    $is_new = true;
                } else {
                    $is_new = false;
                }
                $hand->target_name = $apply_hand->target_name;
                $hand->target_age = $apply_hand->target_age;
                $hand->target_img = $apply_hand->target_img;

                if ($hand->save() && $is_new) {
                    VoteService::initVote($hand->activity_id, $hand->id);
                }
            }
        }

        VoteActivityHandApply::updateAll([
            'check_status' => $result,
            'check_feedback' => $feedback,
            'updated_at' => time()], [
            'id' => $ids,
            'check_status' => VoteActivityHandApply::CHECK_STATUS_WAITING
        ]);

        return new ApiResponse(ApiResponse::CODE_COMMON_OK);
    }

    /**
     * Finds the VoteActivityHandApply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VoteActivityHandApply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VoteActivityHandApply::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
