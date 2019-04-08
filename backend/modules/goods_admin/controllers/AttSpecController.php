<?php

namespace backend\modules\goods_admin\controllers;

use common\models\api\ApiResponse;
use common\models\goods\Goods;
use common\models\goods\GoodsAttribute;
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
        GoodsSpecPrice::updateAll(['is_del' => 1],['goods_id' => $goods_id]);

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
     * 保存商品属性
     * @param int  $goods_id
     */
    public function actionSaveAttribute($goods_id){
        $attrs = \Yii::$app->request->post('attrs',[]);
        $inputAttr = GoodsAttribute::findAll(['id' => array_keys($attrs),'input_type' => [GoodsAttribute::TYPE_SINGLE_INPUT,GoodsAttribute::TYPE_MULTPLE_INPUT]]);
        $inputAttrIds = ArrayHelper::getColumn($inputAttr, 'id');
        //删除已存在的属性
        GoodsAttValueRef::updateAll(['is_del' => 1], ['goods_id' => $goods_id]);
        foreach ($attrs as $attr_id => $values){
            if(in_array($attr_id, $inputAttrIds)){
                //输入类型
            }else{
                //准备数据
                $goodsAttrs = [];
                foreach ($values as $value) {
                    $goodsAttrs[] = [$goods_id, $attr_id, $value , 0];
                }
                //保存关联
                MysqlUtil::batchInsertDuplicateUpdate(GoodsAttValueRef::tableName(), ['goods_id', 'attribute_id','attribute_value_id', 'is_del'], $goodsAttrs, ['is_del']);
            }
        }
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
