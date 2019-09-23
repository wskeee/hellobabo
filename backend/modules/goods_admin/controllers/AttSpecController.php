<?php

namespace backend\modules\goods_admin\controllers;

use common\models\api\ApiResponse;
use common\models\goods\Goods;
use common\models\goods\GoodsAttValueRef;
use common\models\goods\GoodsSpecItem;
use common\models\goods\GoodsSpecPrice;
use common\utils\MysqlUtil;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
        GoodsAttValueRef::updateAll(['is_del' => 1], ['goods_id' => $goods_id]);
        GoodsSpecPrice::updateAll(['is_del' => 1], ['goods_id' => $goods_id]);

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
    public function actionDelSpecItem($id)
    {
        Yii::$app->response->format = 'json';
        $model = GoodsSpecItem::findOne(['id' => $id]);
        if ($model) {
            $model->is_del = 1;
            $model->save();
            return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $model->toArray());
        } else {
            return new ApiResponse(ApiResponse::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Spec')]);
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_UNKNOWN);
    }

    /**
     * 保存商品属性
     * @param int  $goods_id
     */
    public function actionSaveAttribute($goods_id)
    {
        Yii::$app->response->format = 'json';
        $attrs = \Yii::$app->request->post('attrs', []);
        try {
            $goodsAttrRefRows = [];
            //删除已存在的属性
            GoodsAttValueRef::updateAll(['is_del' => 1], ['goods_id' => $goods_id]);
            foreach ($attrs as $attr_id => $values) {
                //准备数据
                foreach ($values as $value) {
                    $goodsAttrRefRows[] = [$goods_id, $attr_id, $value, 0];
                }
            }
            if (count($goodsAttrRefRows) > 0) {
                //更新选择类型引用数据
                MysqlUtil::batchInsertDuplicateUpdate(GoodsAttValueRef::tableName(), ['goods_id', 'attribute_id', 'attribute_value_id', 'is_del'], $goodsAttrRefRows, ['is_del']);
            }
        } catch (\Exception $ex) {
            return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, $ex->getMessage(), $ex);
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_OK);
    }

    /**
     * 保存商品规格
     * @param int  $goods_id
     */
    public function actionSaveSpec($goods_id)
    {
        Yii::$app->response->format = 'json';
        Yii::info(Yii::$app->request->rawBody);
        $post = json_decode(Yii::$app->request->rawBody, true);
        $specs = ArrayHelper::getValue($post, 'specs', []);
        try {
            GoodsSpecPrice::updateAll(['is_del' => 1], ['goods_id' => $goods_id]);
            foreach ($specs as $spec) {
                //准备数据
                $rows[] = [
                    $goods_id,
                    $spec['goods_cost'],
                    $spec['goods_price'],
                    $spec['scene_num'],
                    $spec['spec_key'],
                    $spec['spec_key_name'],
                    $spec['spec_img_url'],
                    $spec['spec_des'],
                    $spec['store_count'],
                    0,
                ];
            }
            //更新选择类型引用数据
            MysqlUtil::batchInsertDuplicateUpdate(GoodsSpecPrice::tableName(), ['goods_id', 'goods_cost', 'goods_price', 'scene_num', 'spec_key', 'spec_key_name', 'spec_img_url', 'spec_des', 'store_count', 'is_del'], $rows, ['goods_cost', 'goods_price', 'scene_num', 'spec_key_name', 'spec_img_url', 'spec_des', 'store_count', 'is_del']);
        } catch (\Exception $ex) {
            return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, $ex->getMessage(), $ex);
        }
        return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $post);
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
