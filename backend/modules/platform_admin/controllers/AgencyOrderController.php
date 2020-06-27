<?php

namespace backend\modules\platform_admin\controllers;

use common\models\AdminUser;
use common\models\platform\AgencyStatistics;
use common\models\platform\searchs\AgencyOrderSearch;
use common\models\User;
use common\services\AgencyService;
use Yii;
use common\models\platform\Agency;
use common\models\platform\searchs\AgencySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgencyController implements the CRUD actions for Agency model.
 */
class AgencyOrderController extends Controller
{
    /**
     * {@inheritdoc}
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
        ];
    }

    /**
     * Lists all Agency models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var AdminUser $user */
        $user = Yii::$app->user->identity;
        $agency = AgencyService::getAgencyByAdminId($user->id);

        $searchModel = new AgencyOrderSearch();
        $agency && $searchModel->agency_id = $agency->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $agencys = $user->type == AdminUser::TYPE_AGENCY ? [] : AgencyService::getAllAgencys();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'agencys' => $agencys,
        ]);
    }
}
