<?php

namespace backend\modules\platform_config\controllers;

use common\models\api\ApiResponse;
use common\models\goods\GoodsAttributeValue;
use common\models\goods\searchs\GoodsAttributeValueSearch;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * AttributeValueController implements the CRUD actions for GoodsAttributeValue model.
 */
class AttributeValueController extends GridViewChangeSelfController
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
     * 列出所有商品属性值配置.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsAttributeValueSearch();
        $results = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'filters' => $results['filter'],     //查询过滤
            'totalCount' => $results['total'],     //计算总数量
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $results['data']['attVal'],
                'key' => 'id',
            ]),
        ]);
    }

    /**
     * 创建 商品属性值配置
     * 如果创建成功，浏览器将被重定向到“index”页面。
     * @param string $attribute_id
     * @return mixed
     */
    public function actionCreate($attribute_id)
    {
        Yii::$app->getResponse()->format = 'json';

        $model = new GoodsAttributeValue(['attribute_id' => $attribute_id]);
        $model->loadDefaultValues();
        $model->save();

        if ($model->save()) {
            return new ApiResponse(ApiResponse::CODE_COMMON_OK);
        } else {
            return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

    /**
     * 更新 商品属性值配置
     * 如果更新成功，浏览器将被重定向到“index”页面。
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['attribute/view', 'id' => $model->attribute_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除 商品属性值配置
     * 如果删除成功，浏览器将被重定向到“index”页面。
     * @param string $id
     * @param string $attribute_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $attribute_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['attribute/view', 'id' => $attribute_id]);
    }

    /**
     * 根据其主键值查找模型。
     * 如果找不到模型，就会抛出404 HTTP异常。
     * @param string $id
     * @return GoodsAttributeValue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsAttributeValue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
