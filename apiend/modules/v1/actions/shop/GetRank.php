<?php


namespace apiend\modules\v1\actions\shop;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\shop\Shop;
use common\services\ShopService;
use Yii;
use yii\base\Action;

/**
 * 获取收益排行
 * Class GetIncomeRank
 * @package apiend\modules\v1\actions\shop
 */
class GetRank extends Action
{
    public function run()
    {
        $shop_id = Yii::$app->request->getQueryParam('shop_id');
        $year = Yii::$app->request->getQueryParam('year', '');
        $month = Yii::$app->request->getQueryParam('month', '');
        $page = Yii::$app->request->getQueryParam('page', 1);
        $page_size = Yii::$app->request->getQueryParam('page_size', 20);
        $type = Yii::$app->request->getQueryParam('type', 'income');
        $category = Yii::$app->request->getQueryParam('category', '');

        $start_time = $end_time = null;
        if ($year && $month) {
            $start_time = strtotime("first day of $year-$month");
            $end_time = strtotime("last day of $year-$month 23:59:59");
        } else if ($year) {
            $start_time = strtotime("first day of $year-01");
            $end_time = strtotime("last day of $year-12 23:59:59");
        }

        $params = [
            'ShopSaleRecord.shop_id' => $shop_id,
            'goods.category_id' => $category,
        ];

        if ($type == 'income') {
            $res = ShopService::getIncomeRank($params, $start_time, $end_time, $page, $page_size);
        } else {
            $res = ShopService::getCountRank($params, $start_time, $end_time, $page, $page_size);
        }

        return new Response(Response::CODE_COMMON_OK, null, $res);
    }
}