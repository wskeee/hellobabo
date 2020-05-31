<?php

namespace backend\modules\goods_admin\controllers;

use common\models\goods\GoodsMaterialValue;
use common\models\goods\GoodsSceneMaterial;
use common\models\goods\Goods;
use common\models\goods\GoodsScene;
use common\models\goods\GoodsScenePage;
use common\models\goods\searchs\GoodsSceneSearch;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * SceneController implements the CRUD actions for GoodsScene model.
 */
class SceneController extends GridViewChangeSelfController
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
        $goodsModel = Goods::findOne(['id' => $goods_id]);
        $searchModel = new GoodsSceneSearch(['goods_id' => $goods_id, 'is_del' => 0]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'goodsModel' => $goodsModel,
            'materials' => GoodsMaterialValue::getGoodsMaterialValue($goods_id, true),
        ]);
    }

    /**
     * Displays a single GoodsScene model.
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
     * Creates a new GoodsScene model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($goods_id)
    {
        $post = Yii::$app->request->post();
        $goodsModel = Goods::findOne(['id' => $goods_id]);
        $model = new GoodsScene(['goods_id' => $goods_id]);
        $scene_material_model = new GoodsSceneMaterial(['goods_id' => $goods_id]);
        $page_model = new GoodsScenePage();

        $tran = Yii::$app->db->beginTransaction();

        if ($model->load($post) && $model->validate() &&
            $scene_material_model->load($post) && $scene_material_model->validate() &&
            $page_model->load($post) && $page_model->validate() &&
            $model->save() ) {
            $scene_material_model->scene_id = $model->id;
            $page_model->scene_id = $model->id;
            $page_model->name = $model->name;
            if ($scene_material_model->save() && $page_model->save()) {
                $tran->commit();
                return $this->redirect(['index', 'goods_id' => $goods_id, 'material_value_id' => $scene_material_model->material_value_id]);
            } else {
                $tran->rollBack();
            }
        } else {
            $tran->rollBack();
        }

        return $this->render('create', [
            'model' => $model,
            'goodsModel' => $goodsModel,
            'scene_material_model' => $scene_material_model,
            'page_model' => $page_model,
        ]);
    }

    /**
     * Updates an existing GoodsScene model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $model = $this->findModel($id);
        $scene_material_model = GoodsSceneMaterial::findOne(['scene_id' => $id]);
        $page_model = GoodsScenePage::findOne(['scene_id' => $id]);

        $tran = Yii::$app->db->beginTransaction();

        if ($model->load($post) && $model->save() && $scene_material_model->load($post) && $page_model->load($post)) {
            $scene_material_model->scene_id = $model->id;
            $page_model->scene_id = $model->id;
            $page_model->name = $model->name;
            if ($scene_material_model->save() && $page_model->save()) {
                $tran->commit();
                return $this->redirect(['index', 'goods_id' => $model->goods_id, 'material_value_id' => $scene_material_model->material_value_id]);
            } else {
                $tran->rollBack();
            }
        } else {
            $tran->rollBack();
        }

        return $this->render('update', [
            'model' => $model,
            'scene_material_model' => $scene_material_model,
            'page_model' => $page_model,
        ]);
    }

    /**
     * Deletes an existing GoodsScene model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $material_rel = GoodsSceneMaterial::findOne(['scene_id' => $model->id]);
        $page = GoodsScenePage::findOne(['scene_id' => $model->id]);
        $tran = Yii::$app->db->beginTransaction();
        try {
            if($material_rel){
                $material_rel->is_del = 1;
                $material_rel->save();
            }
            if($page){
                $page->is_del = 1;
                $page->save();
            }

            $model->is_del = 1;
            $model->save();

            $tran->commit();
        }catch(Exception $e){
            $tran->rollBack();
        }

        return $this->redirect(['index', 'goods_id' => $model->goods_id]);
    }

    /**
     * Finds the GoodsScene model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsScene the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsScene::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @inheritDoc
     * @return array|void
     */
    public function actionChangeValue($id, $fieldName, $value)
    {
        if ($fieldName == 'page_is_required' || $fieldName == 'page_is_hide') {
            $page = GoodsScenePage::findOne(['scene_id' => $id]);
            if($page){
                if($fieldName == 'page_is_required'){
                    $page->is_required = $value;
                }else{
                    $page->is_hide = $value;
                }

                $page->save();
                return ['result' => 1, 'message' => '更新成功'];
            }else{
                return ['result' => 0, 'message' => '找不到 Page'];
            }
        } else {
            return parent::actionChangeValue($id, $fieldName, $value);
        }
    }
}
