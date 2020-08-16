<?php

namespace backend\modules\order_admin\controllers;

use common\models\AdminUser;
use common\models\order\searchs\OrderBoardSearch;
use common\models\order\searchs\OrderGoodsSearch;
use Yii;
use yii\web\Controller;

/**
 * OrderBoardController implements the CRUD actions for OrderGoods model.
 */
class OrderBoardController extends Controller
{

    /**
     * Lists all OrderGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderBoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $worders = AdminUser::getUserByType();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'worders' => $worders,
        ]);
    }

}
