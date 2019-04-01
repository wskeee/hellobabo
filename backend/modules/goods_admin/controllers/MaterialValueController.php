<?php

namespace backend\modules\goods_admin\controllers;

use common\models\goods\GoodsMaterial;
use common\models\goods\GoodsMaterialValue;
use common\models\goods\searchs\GoodsMaterialValueSearch;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * MaterialValueController implements the CRUD actions for GoodsMaterialValue model.
 */
class MaterialValueController extends GridViewChangeSelfController
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
     * Lists all GoodsMaterialValue models.
     * @return mixed
     */
    public function actionIndex($material_id)
    {
        $materialModel = GoodsMaterial::findOne(['id' => $material_id]);
        $searchModel = new GoodsMaterialValueSearch(['material_id' => $material_id , 'is_del' => 0]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'materialModel' => $materialModel,
        ]);
    }

    /**
     * Displays a single GoodsMaterialValue model.
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
     * Creates a new GoodsMaterialValue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($material_id)
    {
        $model = new GoodsMaterialValue(['material_id' => $material_id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'material_id' => $model->material_id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GoodsMaterialValue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'material_id' => $model->material_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GoodsMaterialValue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_del = 1;
        $model->save();

        return $this->redirect(['index', 'material_id' => $model->material_id]);
    }

    /**
     * Finds the GoodsMaterialValue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsMaterialValue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsMaterialValue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
