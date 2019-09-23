<?php

namespace backend\modules\platform_admin\controllers;

use apiend\components\voterank\VoteService;
use common\models\activity\searchs\VoteActivitySearch;
use common\models\activity\VoteActivity;
use common\models\activity\VoteActivityHand;
use common\utils\DateUtil;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * VoteActivityController implements the CRUD actions for VoteActivity model.
 */
class VoteActivityController extends GridViewChangeSelfController
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
     * Lists all VoteActivity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VoteActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VoteActivity model.
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
     * Creates a new VoteActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VoteActivity();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing VoteActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing VoteActivity model.
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
     * 活动情况
     * @param int $id   活动ID
     */
    public function actionInfo($id)
    {
        $model = $this->findModel($id);
        // 活动总统计
        $all_info = VoteService::getHandAllInfo($id);
        // 月统计
        $len = DateUtil::getMonthNum($model->start_time, $model->end_time);
        $months = [];
        // 先合并所有id
        $ids = array_keys($all_info['rank']);
        for ($i = 0; $i <= $len; $i++) {
            $month_time = strtotime("$model->start_time +$i month");
            $month_str = date('Ym', $month_time);
            $month = VoteService::getHandMonthInfo($model->id, $month_str);
            $weeks = DateUtil::getWeeksOfMonth($month_str);
            $weekInfos = [];
            foreach ($weeks as $week_num => $week_date) {
                $weekInfo = VoteService::getHandWeekInfo($model->id, $month_str, $week_num);
                $weekInfo['num'] = $week_num;
                $weekInfo['date'] = $week_date;
                $weekInfos [] = $weekInfo;
                // 添加周排行榜id
                $ids = array_merge(array_keys($weekInfo['rank']));
            }
            $month['weeks'] = $weekInfos;
            $month['name'] = $month_str;
            // 添加月排行榜id
            $ids = array_merge(array_keys($month['rank']));
            $months[] = $month;
        }

        $hands = ArrayHelper::index(VoteActivityHand::find()->where(['id' => array_unique($ids)])->asArray()->all(), 'id');
        
        // 转换总排行榜
        $all_info['rank'] = $this->getRankData($all_info['rank'], $hands);
        foreach($months as &$month){
            $month['rank'] = $this->getRankData($month['rank'], $hands);
            foreach($month['weeks'] as &$week){
                $week['rank'] = $this->getRankData($week['rank'], $hands);
            }
        }
        
        return $this->render('info', [
                    'model' => $model,
                    'all_info' => $all_info,
                    'month_info' => $months
        ]);
    }

    /**
     * 查询排行榜用户数据
     * 
     * @param array(id => score) $rank   排行榜
     * @return array
     */
    private function getRankData($rank, $hands)
    {
        $handList = [];
        foreach ($rank as $hid => $score) {
            $hand = $hands[$hid];
            $hand['score'] = $score;
            $handList [] = $hand;
        }
        return $handList;
    }

    /**
     * Finds the VoteActivity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VoteActivity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VoteActivity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
