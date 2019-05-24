<?php

namespace apiend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($target_id)
    {

        $specs = [
            ['id' => 1, 'name' => 'A', 'items' => [1, 2]],
            ['id' => 2, 'name' => 'B', 'items' => [3, 6, 7]],
            ['id' => 3, 'name' => 'C', 'items' => [4, 5]],
        ];

        $gsps = [
            ['spec_key' => '1_3_4', 'store_count' => 0],
            ['spec_key' => '1_3_5', 'store_count' => 0],
            ['spec_key' => '1_4_6', 'store_count' => 1],
            ['spec_key' => '1_5_6', 'store_count' => 1],
            ['spec_key' => '1_4_7', 'store_count' => 1],
            ['spec_key' => '1_5_7', 'store_count' => 1],
            ['spec_key' => '2_3_4', 'store_count' => 1],
            ['spec_key' => '2_3_5', 'store_count' => 1],
            ['spec_key' => '2_4_6', 'store_count' => 0],
            ['spec_key' => '2_5_6', 'store_count' => 0],
            ['spec_key' => '2_4_7', 'store_count' => 1],
            ['spec_key' => '2_5_7', 'store_count' => 1],
        ];

        $gsps_map = ArrayHelper::map($gsps, 'spec_key', 'store_count');

        $counts = $this->getCount($target_id, $gsps_map);


        return $this->render('spec', [
                    'specs' => $specs,
                    'gsps' => $gsps
        ]);
    }
    
    public function actionGetCount($target_id){
        $specs = [
            ['id' => 1, 'name' => 'A', 'items' => [1, 2]],
            ['id' => 2, 'name' => 'B', 'items' => [3, 4, 5]],
            ['id' => 3, 'name' => 'C', 'items' => [6, 7]],
        ];

        $gsps = [
            ['spec_key' => '1_3_6', 'store_count' => 0],
            ['spec_key' => '1_3_7', 'store_count' => 0],
            ['spec_key' => '1_4_6', 'store_count' => 1],
            ['spec_key' => '1_4_7', 'store_count' => 1],
            ['spec_key' => '1_5_6', 'store_count' => 1],
            ['spec_key' => '1_5_7', 'store_count' => 1],
            ['spec_key' => '2_3_6', 'store_count' => 1],
            ['spec_key' => '2_3_7', 'store_count' => 1],
            ['spec_key' => '2_4_6', 'store_count' => 0],
            ['spec_key' => '2_4_7', 'store_count' => 0],
            ['spec_key' => '2_5_6', 'store_count' => 1],
            ['spec_key' => '2_5_7', 'store_count' => 1],
        ];

        $gsps_map = ArrayHelper::map($gsps, 'spec_key', 'store_count');

        return $this->getCount($target_id, $gsps_map);
    }

    private function getCount($spec_item_id, $gsps_map)
    {
        $spec_items = [];
        foreach ($gsps_map as $key => $count) {
            $spec_items_ids = explode('_', $key);
            if (!isset($spec_item_id) || in_array($spec_item_id, $spec_items_ids)) {
                foreach ($spec_items_ids as $id) {
                    if (!isset($spec_items[$id])) {
                        $spec_items[$id] = 0;
                    }
                    $spec_items[$id] += $count;
                }
            }
        }
        return $spec_items;
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
