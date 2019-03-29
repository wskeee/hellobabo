<?php

namespace backend\modules\goods_config\controllers;

use common\models\goods\GoodsAttribute;
use common\models\goods\searchs\GoodsAttributeValueSearch;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;




/**
 * AttributeController implements the CRUD actions for GoodsAttribute model.
 */
class AttributeController extends GridViewChangeSelfController
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
     * 列出所有商品属性配置.
     * @return mixed
     */
    public function actionIndex()
    {
        //请查看/goods_config/goods-model/view
    }

    /**
     * 显示单个商品属性配置。
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $params = array_merge(['limit' => 20, 'attribute_id' => $id], Yii::$app->request->queryParams);
        $searchModel = new GoodsAttributeValueSearch(['attribute_id' => $id]);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $searchModel->search($params)
        ]);
    }

    /**
     * 创建 商品属性配置
     * 如果创建成功，浏览器将被重定向到“index”页面。
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GoodsAttribute();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/goods_config/goods-model/view', 'id' => $model->model_id]);
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 更新 商品属性配置
     * 如果更新成功，浏览器将被重定向到“index”页面。
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/goods_config/goods-model/view', 'id' => $model->model_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除 商品属性配置
     * 如果删除成功，浏览器将被重定向到“index”页面。
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        $this->redirect(['/goods_config/goods-model/view', 'id' => $model->model_id]);
    }

    /**
     * 根据其主键值查找模型。
     * 如果找不到模型，就会抛出404 HTTP异常。
     * @param string $id
     * @return GoodsAttribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsAttribute::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
