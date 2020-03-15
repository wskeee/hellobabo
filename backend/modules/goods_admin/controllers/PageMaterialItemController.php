<?php

namespace backend\modules\goods_admin\controllers;

use common\models\goods\GoodsMaterialValue;
use common\models\goods\GoodsMaterialValueItem;
use Yii;
use common\models\goods\GoodsScenePageMaterialItem;
use common\models\goods\searchs\GoodsScenePageMaterialItemSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PageMaterialItemController implements the CRUD actions for GoodsScenePageMaterialItem model.
 */
class PageMaterialItemController extends Controller
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
     * Lists all GoodsScenePageMaterialItem models.
     * @return mixed
     */
    public function actionIndex($scene_page_id)
    {
        $searchModel = new GoodsScenePageMaterialItemSearch(['scene_page_id' => $scene_page_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodsScenePageMaterialItem model.
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
     * Creates a new GoodsScenePageMaterialItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($scene_page_id)
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post('GoodsScenePageMaterialItem');
            $model = GoodsScenePageMaterialItem::find()->where([
                'scene_page_id' => $post['scene_page_id'],
                'material_value_id' => $post['material_value_id'],
                'material_value_item_id' => $post['material_value_item_id'],
            ])->one();
        }
        if(!isset($model) || !$model){
            $model = new GoodsScenePageMaterialItem(['scene_page_id' => $scene_page_id]);
        }
        $model->is_del = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'scene_page_id' => $model->scene_page_id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GoodsScenePageMaterialItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'scene_page_id' => $model->scene_page_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GoodsScenePageMaterialItem model.
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
     * 获取素材项
     * @param int $material_value_id
     */
    public function actionGetMaterialItem($material_value_id)
    {
        Yii::$app->response->format = 'json';
        $result = GoodsMaterialValueItem::find()
            ->where(['material_value_id' => $material_value_id, 'is_del' => 0])
            ->asArray()->all();
        return $result;
    }

    /**
     * Finds the GoodsScenePageMaterialItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsScenePageMaterialItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsScenePageMaterialItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
