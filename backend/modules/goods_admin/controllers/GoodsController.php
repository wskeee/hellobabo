<?php

namespace backend\modules\goods_admin\controllers;

use common\models\api\ApiResponse;
use common\models\goods\Goods;
use common\models\goods\GoodsDetail;
use common\models\goods\GoodsTagRef;
use common\models\goods\searchs\GoodsSearch;
use common\models\Tags;
use common\widgets\grid\GridViewChangeSelfController;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends GridViewChangeSelfController
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
     * Lists all Goods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Goods model.
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
     * Creates a new Goods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goods([
            'created_by' => Yii::$app->user->id,
            'updated_by' => Yii::$app->user->id,
        ]);
        $model->loadDefaultValues();
        $model->goods_sn = date('YmdHis', time()) . rand(1000, 9999);

        $goodsDetail = new GoodsDetail();
//        var_dump(Yii::$app->request->post());exit;

        if ($model->load(Yii::$app->request->post()) && $goodsDetail->load(Yii::$app->request->post())) {
            $tran = Yii::$app->db->beginTransaction();
            if ($model->save()) {
                //新建商品详情数据
                $goodsDetail->goods_id = $model->id;
                $goodsDetail->save();
                //新建标签
                $tags = Tags::saveTags($model->tags);
                //添加引用
                GoodsTagRef::saveGoodsTagRef($model->id, $tags);

                $tran->commit();

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $tran->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $model = $this->findModel($id);

        if ($model->load($post)) {
            //检查标签是否更改
            $tagsChanged = $model->isAttributeChanged('tags');
            if ($model->save()) {
                //保存详情内容
                $model->goodsDetails->load($post);
                $model->goodsDetails->save();
                //更新标签
                if ($tagsChanged) {
                    //新建标签
                    $tags = Tags::saveTags($model->tags);
                    //添加引用
                    GoodsTagRef::saveGoodsTagRef($model->id, $tags);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                \Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Goods model.
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
     * 批量发布
     * @param array $post [goodsIds:xxx,xxxx,sss]
     */
    public function actionBatchPublish()
    {
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        //goodsIds不能为空
        if (!isset($post['goodsIds']) || empty($post['goodsIds'])) {
            return new ApiResponse(ApiResponse::CODE_COMMON_MISS_PARAM, null, null, ['param' => 'goodsIds']);
        }
        try {
            $goodsIds = explode(',', $post['goodsIds']);
            Goods::updateAll(['status' => Goods::STATUS_PUBLISHED], ['id' => $goodsIds]);
        } catch (\Exception $ex) {
            return new ApiResponse(ApiResponse::CODE_COMMON_UNKNOWN, $ex->getMessage(), $ex->getTraceAsString());
        }

        return new ApiResponse(ApiResponse::CODE_COMMON_OK);
    }

    /**
     * 批量下架
     * @param array $post [goodsIds:xxx,xxxx,sss]
     */
    public function actionBatchSoldOut()
    {
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        //goodsIds不能为空
        if (!isset($post['goodsIds']) || empty($post['goodsIds'])) {
            return new ApiResponse(ApiResponse::CODE_COMMON_MISS_PARAM, null, null, ['param' => 'goodsIds']);
        }
        try {
            $goodsIds = explode(',', $post['goodsIds']);
            Goods::updateAll(['status' => Goods::STATUS_SOLD_OUT], ['id' => $goodsIds]);
        } catch (\Exception $ex) {
            return new ApiResponse(ApiResponse::CODE_COMMON_UNKNOWN, $ex->getMessage(), $ex->getTraceAsString());
        }

        return new ApiResponse(ApiResponse::CODE_COMMON_OK);
    }

    /**
     * 获取所有可用商品列表
     */
    public function actionGetGoodsList()
    {
        Yii::$app->response->format = 'json';
        return Goods::getUseableList();
    }

    /**
     * 获取所有可用商品类型列表
     */
    public function actionGetGoodsTypeList()
    {
        Yii::$app->response->format = 'json';
        return Goods::$typeKeyMap;
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
