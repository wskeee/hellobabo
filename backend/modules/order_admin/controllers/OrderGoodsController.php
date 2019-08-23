<?php

namespace backend\modules\order_admin\controllers;

use backend\modules\order_admin\actions\order_goods\DesignCheck;
use backend\modules\order_admin\actions\order_goods\PrintCheck;
use backend\modules\order_admin\actions\order_goods\UpimgCheck;
use common\models\order\OrderGoods;
use common\models\order\searchs\OrderGoodsSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * OrderGoodsController implements the CRUD actions for OrderGoods model.
 */
class OrderGoodsController extends Controller
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
                    'upimg-check' => ['POST'],
                    'design-check' => ['POST'],
                    'print-check' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'upimg-check' => UpimgCheck::class,
            'design-check' => DesignCheck::class,
            'print-check' => PrintCheck::class
        ];
    }

    /**
     * Lists all OrderGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderGoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderGoods model.
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
     * Finds the OrderGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
