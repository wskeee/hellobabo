<?php

namespace backend\modules\system_admin\controllers;

use common\models\AdminUser;
use common\models\system\Banner;
use common\models\system\Region;
use common\models\system\searchs\BannerSearch;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class RegionController extends GridViewChangeSelfController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * 返回地区数据
     * @param int $parent_id
     */
    public function actionGetRegion($parent_id) {
        Yii::$app->response->format = 'json';
        return Region::getRegionList(['parent_id' => $parent_id]);
    }


    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
