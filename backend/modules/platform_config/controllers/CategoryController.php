<?php

namespace backend\modules\platform_config\controllers;

use common\models\goods\GoodsCategory;
use common\models\goods\searchs\GoodsCategorySearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CategoryController implements the CRUD actions for GoodsCategory model.
 */
class CategoryController extends Controller
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
     * 列出所有商品类目配置.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsCategorySearch();
        $params = array_merge(['limit' => 20], Yii::$app->request->queryParams);
        $results = $searchModel->search($params);
        $dp = new ArrayDataProvider([
            'totalCount' => $results['total'],
            'pagination' => [
                'pageSize' => $params['limit'],
                'totalCount' => $results['total'],
            ],
        ]);
        $dp->setModels($results['data']['category']);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'filters' => $results['filter'], //查询过滤
                    'dataProvider' => $dp,
        ]);
    }

    /**
     * 创建 商品类目配置
     * 如果创建成功，浏览器将被重定向到“index”页面。
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GoodsCategory();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * 更新 商品类目配置
     * 如果更新成功，浏览器将被重定向到“index”页面。
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除 商品类目配置
     * 如果删除成功，浏览器将被重定向到“index”页面。
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 根据其主键值查找模型。
     * 如果找不到模型，就会抛出404 HTTP异常。
     * @param string $id
     * @return GoodsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
