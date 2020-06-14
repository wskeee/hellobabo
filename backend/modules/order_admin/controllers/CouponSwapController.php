<?php

namespace backend\modules\order_admin\controllers;

use common\models\api\ApiResponse;
use common\models\order\Coupon;
use common\services\CouponService;
use common\utils\StringUtil;
use Yii;
use common\models\order\CouponSwap;
use common\models\order\searchs\CouponSwapSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CouponSwapController implements the CRUD actions for CouponSwap model.
 */
class CouponSwapController extends Controller
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
     * Lists all CouponSwap models.
     * @return mixed
     * @throws
     */
    public function actionIndex($coupon_id)
    {
        $coupon = Coupon::findOne(['id' => $coupon_id]);
        if ($coupon->used != Coupon::USED_CODE) {
            throw new ForbiddenHttpException('该优惠卷无法创建优惠码！');
        }
        $searchModel = new CouponSwapSearch(['coupon_id' => $coupon_id, 'is_del' => 0]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'remainNum' => $coupon->quota - CouponService::getCouponSwapNum($coupon_id),
        ]);
    }

    /**
     * Displays a single CouponSwap model.
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
     * Creates a new CouponSwap model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = 'json';
        $coupon_id = Yii::$app->request->getBodyParam('coupon_id');
        $num = Yii::$app->request->getBodyParam('num');

        if (empty($coupon_id)) {
            return new ApiResponse(ApiResponse::CODE_COMMON_MISS_PARAM, null, null, ['param' => 'coupon_id']);
        } else if (!($coupon = Coupon::findOne(['id' => $coupon_id]))) {
            return new ApiResponse(ApiResponse::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Coupon')]);
        }
        if ($num <= 0) {
            return new ApiResponse(ApiResponse::CODE_COMMON_DATA_INVALID, null, null, ['param' => 'num']);
        }

        $coupon_code_num = CouponService::getCouponSwapNum($coupon_id);
        if ($coupon_code_num + $num > $coupon->quota) {
            return new ApiResponse(ApiResponse::CODE_COMMON_DATA_INVALID, '超出发行数量');
        }

        $rows = [];
        $time = time();
        for ($i = 0; $i < $num; $i++) {
            $code = StringUtil::getRandomStr();
            $rows[] = [
                $coupon_id,
                $code,
                Yii::$app->user->id,
                $time,
                $time,
            ];
        }
        // 指量插入数据
        $result = Yii::$app->db->createCommand()->batchInsert(CouponSwap::tableName(),
            ['coupon_id', 'code', 'created_by', 'created_at', 'updated_at'], $rows)->execute();
        if ($result) {
            return new ApiResponse(ApiResponse::CODE_COMMON_OK);
        } else {
            return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL);
        }
    }

    /**
     * Updates an existing CouponSwap model.
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
     * Deletes an existing CouponSwap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $modle = $this->findModel($id);
        $modle->is_del = 1;
        $modle->save();
        return $this->redirect(['index', 'coupon_id' => $modle->coupon_id]);
    }

    /**
     * Finds the CouponSwap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CouponSwap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouponSwap::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
