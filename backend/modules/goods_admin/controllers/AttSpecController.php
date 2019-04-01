<?php

namespace backend\modules\goods_admin\controllers;

use common\models\goods\Goods;
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
    public function actionChangeModel($goods_id){
        $model = $this->findModel($goods_id);
        
        $model->load(\Yii::$app->request->post());
        $model->save();
        
        $this->redirect(['index','goods_id' => $goods_id]);
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
