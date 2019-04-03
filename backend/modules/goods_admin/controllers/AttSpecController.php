<?php

namespace backend\modules\goods_admin\controllers;

use common\models\api\ApiResponse;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecItem;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * SceneController implements the CRUD actions for Goods model.
 */
class AttSpecController extends GridViewChangeSelfController
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
     * Lists all GoodsScene models.
     * @return mixed
     */
    public function actionIndex($goods_id)
    {
        $model = $this->findModel($goods_id);

        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    /**
     * 更换 goods.model_id
     * @param int $goods_id 商品ID
     */
    public function actionChangeModel($goods_id)
    {
        $model = $this->findModel($goods_id);

        $model->load(\Yii::$app->request->post());
        $model->save();

        $this->redirect(['index', 'goods_id' => $goods_id]);
    }

    /**
     * 添加规格项
     * @param array $post [spec_id,goods_id,value]
     */
    public function actionAddSpecItem()
    {
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        $model = new GoodsSpecItem();
        if ($model->load($post, 'SpecItem') && $model->save()) {
            return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $model->toArray());
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
    }
    
    /**
     * 删除规格项
     * @param int $id
     */
    public function actionDelSpecItem($id){
        Yii::$app->response->format = 'json';
        $model = GoodsSpecItem::findOne(['id' => $id]);
        if($model){
            $model->is_del = 1;
            $model->save();
            return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $model->toArray());
        }else{
            return new ApiResponse(ApiResponse::CODE_COMMON_NOT_FOUND,null,null,['param' => Yii::t('app', 'Spec')]);
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_UNKNOWN);
    }

    /**
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
